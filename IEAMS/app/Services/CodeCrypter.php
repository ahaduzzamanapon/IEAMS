<?php

namespace App\Services;

class CodeCrypter
{
    private const CIPHER    = 'aes-256-cbc';
    private const MARKER    = '/* ENCRYPTED BY IEAMS SHIELD */';
    private const HASH_FILE = '.shield_hash';
    private const KEY_FILE  = '.runtime_key';

    // -----------------------------------------------------------------
    // Derive a 32-byte AES key from a passphrase (SHA-256, raw binary)
    // -----------------------------------------------------------------
    private static function deriveAesKey(string $passphrase): string
    {
        return hash('sha256', $passphrase, true);
    }

    // -----------------------------------------------------------------
    // Encrypt a PHP file with AES-256-CBC using the passphrase
    // -----------------------------------------------------------------
    public static function encryptFile(string $path, string $passphrase): bool
    {
        if (!file_exists($path)) return false;
        $content = file_get_contents($path);

        if (str_contains($content, self::MARKER)) return false;

        $cleanContent = preg_replace('/^<\?php\s*/i', '', $content);
        $aesKey       = self::deriveAesKey($passphrase);
        $iv           = openssl_random_pseudo_bytes(openssl_cipher_iv_length(self::CIPHER));
        $ciphertext   = openssl_encrypt($cleanContent, self::CIPHER, $aesKey, OPENSSL_RAW_DATA, $iv);

        if ($ciphertext === false) return false;

        $cB64  = base64_encode($ciphertext);
        $ivB64 = base64_encode($iv);

        // The wrapper reads the runtime key file (which contains the derived AES key).
        // It does NOT contain any salt/secret — those are only inside CodeCrypter.php
        // which itself gets encrypted, hiding the entire implementation.
        $rkPath = addslashes(storage_path('app/' . self::KEY_FILE));

        $encryptedContent = <<<PHP
<?php
/* ENCRYPTED BY IEAMS SHIELD */
\$_rk=@file_get_contents('{$rkPath}');if(\$_rk){\$_k=base64_decode(trim(\$_rk));eval(openssl_decrypt(base64_decode('{$cB64}'),'aes-256-cbc',\$_k,OPENSSL_RAW_DATA,base64_decode('{$ivB64}')));}
PHP;

        file_put_contents($path, $encryptedContent);
        return true;
    }

    // -----------------------------------------------------------------
    // Decrypt a PHP file using the provided passphrase
    // -----------------------------------------------------------------
    public static function decryptFile(string $path, string $passphrase): bool
    {
        if (!file_exists($path)) return false;
        $content = file_get_contents($path);

        if (!str_contains($content, self::MARKER)) return false;

        if (!preg_match(
            "/eval\(openssl_decrypt\(base64_decode\('([A-Za-z0-9+\/=]+)'\),'aes-256-cbc',\\\$_k,OPENSSL_RAW_DATA,base64_decode\('([A-Za-z0-9+\/=]+)'\)\)\)/",
            $content,
            $matches
        )) {
            return false;
        }

        $ciphertext = base64_decode($matches[1]);
        $iv         = base64_decode($matches[2]);
        $aesKey     = self::deriveAesKey($passphrase);

        $original = openssl_decrypt($ciphertext, self::CIPHER, $aesKey, OPENSSL_RAW_DATA, $iv);
        if ($original === false) return false;

        file_put_contents($path, "<?php\n" . $original);
        return true;
    }

    // -----------------------------------------------------------------
    // Save the derived AES key to .runtime_key so encrypted files run.
    // This file is in storage/app/ (outside public/) and contains only
    // a base64-encoded 32-byte key — meaningless without knowing the
    // cipher, file structure, and IVs (all hidden inside encrypted CodeCrypter.php).
    // -----------------------------------------------------------------
    public static function saveRuntimeKey(string $passphrase): bool
    {
        $aesKey  = self::deriveAesKey($passphrase);
        $encoded = base64_encode($aesKey);
        return file_put_contents(storage_path('app/' . self::KEY_FILE), $encoded) !== false;
    }

    // -----------------------------------------------------------------
    // Save bcrypt hash of the passphrase (for admin auth verification)
    // -----------------------------------------------------------------
    public static function saveKeyHash(string $key): bool
    {
        $hash = password_hash($key, PASSWORD_BCRYPT);
        return file_put_contents(storage_path('app/' . self::HASH_FILE), $hash) !== false;
    }

    // -----------------------------------------------------------------
    // Check if the system is currently locked/encrypted
    // -----------------------------------------------------------------
    public static function hasStoredKey(): bool
    {
        return file_exists(storage_path('app/' . self::HASH_FILE));
    }

    // -----------------------------------------------------------------
    // Verify the admin passphrase against the stored bcrypt hash
    // -----------------------------------------------------------------
    public static function verifyKey(string $key): bool
    {
        $hashPath = storage_path('app/' . self::HASH_FILE);
        if (!file_exists($hashPath)) return true;

        $hash = file_get_contents($hashPath);
        return password_verify($key, trim($hash));
    }

    // -----------------------------------------------------------------
    // Remove both key files after successful decryption
    // -----------------------------------------------------------------
    public static function deleteKeyHash(): bool
    {
        $deleted = true;
        foreach ([self::HASH_FILE, self::KEY_FILE] as $file) {
            $path = storage_path('app/' . $file);
            if (file_exists($path)) {
                $deleted = unlink($path) && $deleted;
            }
        }
        return $deleted;
    }

    // -----------------------------------------------------------------
    // Status: how many files are encrypted vs total
    // -----------------------------------------------------------------
    public static function getStatus(): array
    {
        $files = self::getTargetFiles();
        if (empty($files)) {
            return ['status' => 'No files found', 'percentage' => 0, 'encrypted' => 0, 'total' => 0];
        }

        $encryptedCount = 0;
        foreach ($files as $file) {
            $content = @file_get_contents($file);
            if ($content && str_contains($content, self::MARKER)) {
                $encryptedCount++;
            }
        }

        $percentage = round(($encryptedCount / count($files)) * 100);
        $isLocked   = self::hasStoredKey();

        return [
            'status'     => $isLocked ? 'Encrypted' : 'Decrypted',
            'percentage' => $percentage,
            'encrypted'  => $encryptedCount,
            'total'      => count($files),
        ];
    }

    // -----------------------------------------------------------------
    // Collect all target PHP files (app/ and routes/)
    // -----------------------------------------------------------------
    public static function getTargetFiles(): array
    {
        $files = [];

        $appPath = app_path();
        if (file_exists($appPath)) {
            $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($appPath));
            foreach ($iterator as $file) {
                if ($file->isFile() && $file->getExtension() === 'php') {
                    if ($file->getFilename() === 'AppServiceProvider.php') continue;
                    $files[] = $file->getRealPath();
                }
            }
        }

        $routesPath = base_path('routes');
        if (file_exists($routesPath)) {
            $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($routesPath));
            foreach ($iterator as $file) {
                if ($file->isFile() && $file->getExtension() === 'php') {
                    $files[] = $file->getRealPath();
                }
            }
        }

        return $files;
    }

    // -----------------------------------------------------------------
    // Sort so CodeCrypter.php & SystemLockController.php are last
    // -----------------------------------------------------------------
    public static function sortFilesForProcessing(array $files): array
    {
        $lastFiles = ['SystemLockController.php', 'CodeCrypter.php'];
        $normal    = [];
        $deferred  = [];

        foreach ($files as $file) {
            if (in_array(basename($file), $lastFiles)) {
                $deferred[] = $file;
            } else {
                $normal[] = $file;
            }
        }

        return array_merge($normal, $deferred);
    }
}
