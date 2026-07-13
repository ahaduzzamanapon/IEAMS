<?php
namespace App\Http\Controllers;

use App\Services\CodeCrypter;
use Illuminate\Http\Request;

class SystemLockController extends Controller
{
    /**
     * Display status of the code lock/encryption
     */
    public function index()
    {
        $status = CodeCrypter::getStatus();
        return view('system.lock', compact('status'));
    }

    /**
     * Encrypt all target controllers and models
     */
    public function encrypt(Request $request)
    {
        $request->validate([
            'shield_key' => 'required|string|min:4',
        ]);

        if (CodeCrypter::hasStoredKey()) {
            return redirect()->route('system.lock')->with('error', 'System is already locked and encrypted!');
        }

        $key   = $request->input('shield_key');

        // Save bcrypt hash (for admin auth) and the derived AES runtime key (for file execution)
        CodeCrypter::saveKeyHash($key);
        CodeCrypter::saveRuntimeKey($key);

        $files = CodeCrypter::sortFilesForProcessing(CodeCrypter::getTargetFiles());
        $count = 0;
        $logs  = [];
        $base  = base_path() . DIRECTORY_SEPARATOR;
        foreach ($files as $file) {
            $relativePath = str_replace($base, '', $file);
            if (CodeCrypter::encryptFile($file, $key)) {
                $count++;
                $logs[] = "Encrypted: {$relativePath}";
            } else {
                $logs[] = "Skipped (Already encrypted or invalid): {$relativePath}";
            }
        }

        return redirect()->route('system.lock')
            ->with('success', "Successfully encrypted {$count} PHP source files with your custom key!")
            ->with('logs', $logs);
    }

    /**
     * Decrypt/Restore all target controllers and models
     */
    public function decrypt(Request $request)
    {
        $request->validate([
            'shield_key' => 'required|string',
        ]);

        if (!CodeCrypter::hasStoredKey()) {
            return redirect()->route('system.lock')->with('error', 'System is not locked/encrypted!');
        }

        if (!CodeCrypter::verifyKey($request->input('shield_key'))) {
            return redirect()->route('system.lock')->with('error', 'Unauthorized: Invalid Security Decryption Key!');
        }

        $key   = $request->input('shield_key');
        $files = CodeCrypter::sortFilesForProcessing(CodeCrypter::getTargetFiles());
        $count = 0;
        $logs  = [];
        $base  = base_path() . DIRECTORY_SEPARATOR;
        foreach ($files as $file) {
            $relativePath = str_replace($base, '', $file);
            if (CodeCrypter::decryptFile($file, $key)) {
                $count++;
                $logs[] = "Decrypted/Refreshed: {$relativePath}";
            } else {
                $logs[] = "Skipped (Already decrypted): {$relativePath}";
            }
        }

        // Clean up the stored key hash upon successful decryption
        CodeCrypter::deleteKeyHash();

        return redirect()->route('system.lock')
            ->with('success', "Successfully decrypted and refreshed {$count} PHP source files!")
            ->with('logs', $logs);
    }
}
