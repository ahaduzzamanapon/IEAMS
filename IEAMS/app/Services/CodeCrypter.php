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

    /**
     * Retrieve list of target files (Controllers and Models)
     */
    public static function getTargetFiles()
    {
        $files = [];
        
        // Target Controllers
        $controllerPath = app_path('Http/Controllers');
        if (file_exists($controllerPath)) {
            $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($controllerPath));
            foreach ($iterator as $file) {
                if ($file->isFile() && $file->getExtension() === 'php') {
                    // Skip SystemLockController and Auth Controllers to prevent lockout
                    $filename = $file->getFilename();
                    if ($filename !== 'SystemLockController.php' && !str_contains($file->getPathname(), 'Auth')) {
                        $files[] = $file->getRealPath();
                    }
                }
            }
        }

        // Target Models
        $modelPath = app_path('Models');
        if (file_exists($modelPath)) {
            $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($modelPath));
            foreach ($iterator as $file) {
                if ($file->isFile() && $file->getExtension() === 'php') {
                    $files[] = $file->getRealPath();
                }
            }
        }

        return $files;
    }
}
