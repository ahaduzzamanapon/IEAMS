<?php

namespace App\Services;

class CodeCrypter
{
    /**
     * Encrypt a PHP file by encoding its content to hex and wrapping it in eval(hex2bin(...))
     */
    public static function encryptFile($path)
    {
        if (!file_exists($path)) return false;
        $content = file_get_contents($path);

        // Check if already encrypted
        if (str_contains($content, '/* ENCRYPTED BY IEAMS SHIELD */')) {
            return false;
        }

        // Strip <?php tag and encode the rest
        $cleanContent = preg_replace('/^<\?php\s*/i', '', $content);
        $hex = bin2hex($cleanContent);

        $encryptedContent = "<?php\n/* ENCRYPTED BY IEAMS SHIELD */\neval(hex2bin('{$hex}'));\n";
        file_put_contents($path, $encryptedContent);
        return true;
    }

    /**
     * Decrypt an encrypted PHP file back to its original source code structure
     */
    public static function decryptFile($path)
    {
        if (!file_exists($path)) return false;
        $content = file_get_contents($path);

        // Check if encrypted
        if (!str_contains($content, '/* ENCRYPTED BY IEAMS SHIELD */')) {
            return false;
        }

        // Extract hex string and decode
        if (preg_match("/eval\(hex2bin\('([0-9a-fA-F]+)'\)\);/i", $content, $matches)) {
            $hex = $matches[1];
            $original = hex2bin($hex);
            $decryptedContent = "<?php\n" . $original;
            file_put_contents($path, $decryptedContent);
            return true;
        }

        return false;
    }

    /**
     * Verify the entered security encryption key against bcrypt hash
     */
    public static function verifyKey($key)
    {
        $hash = env('SHIELD_KEY_HASH', '$2y$10$Ek4QwiimVRcNazx/hhriqOVoZNBL07gk6VyilnVV8suUWqz9LsiDW');
        return password_verify($key, $hash);
    }

    /**
     * Get status of files (how many are encrypted out of total)
     */
    public static function getStatus()
    {
        $files = self::getTargetFiles();
        if (empty($files)) {
            return ['status' => 'No files found', 'percentage' => 0, 'encrypted' => 0, 'total' => 0];
        }

        $encryptedCount = 0;
        foreach ($files as $file) {
            $content = @file_get_contents($file);
            if (str_contains($content, '/* ENCRYPTED BY IEAMS SHIELD */')) {
                $encryptedCount++;
            }
        }

        $percentage = round(($encryptedCount / count($files)) * 100);

        return [
            'status' => $encryptedCount === count($files) ? 'Encrypted' : ($encryptedCount === 0 ? 'Decrypted' : 'Partially Encrypted'),
            'percentage' => $percentage,
            'encrypted' => $encryptedCount,
            'total' => count($files)
        ];
    }

    public static function getTargetFiles()
    {
        $files = [];
        
        // 1. Scan app/ directory recursively
        $appPath = app_path();
        if (file_exists($appPath)) {
            $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($appPath));
            foreach ($iterator as $file) {
                if ($file->isFile() && $file->getExtension() === 'php') {
                    $filename = $file->getFilename();
                    $pathname = $file->getPathname();
                    
                    // Exclusions to prevent lockout or bootstrap issues
                    if ($filename === 'SystemLockController.php') continue;
                    if ($filename === 'CodeCrypter.php') continue;
                    if ($filename === 'AppServiceProvider.php') continue;
                    if (str_contains($pathname, 'app' . DIRECTORY_SEPARATOR . 'Http' . DIRECTORY_SEPARATOR . 'Controllers' . DIRECTORY_SEPARATOR . 'Auth')) continue;
                    
                    $files[] = $file->getRealPath();
                }
            }
        }

        // 2. Scan routes/ directory recursively
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
}
