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
            'shield_key' => 'required|string',
        ]);

        if (!CodeCrypter::verifyKey($request->input('shield_key'))) {
            return redirect()->route('system.lock')->with('error', 'Unauthorized: Invalid Security Encryption Key!');
        }

        $files = CodeCrypter::getTargetFiles();
        $count = 0;
        $logs = [];
        $base = base_path() . DIRECTORY_SEPARATOR;
        foreach ($files as $file) {
            $relativePath = str_replace($base, '', $file);
            if (CodeCrypter::encryptFile($file)) {
                $count++;
                $logs[] = "Encrypted: {$relativePath}";
            } else {
                $logs[] = "Skipped (Already encrypted or invalid): {$relativePath}";
            }
        }

        return redirect()->route('system.lock')
            ->with('success', "Successfully encrypted {$count} PHP source files!")
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

        if (!CodeCrypter::verifyKey($request->input('shield_key'))) {
            return redirect()->route('system.lock')->with('error', 'Unauthorized: Invalid Security Encryption Key!');
        }

        $files = CodeCrypter::getTargetFiles();
        $count = 0;
        $logs = [];
        $base = base_path() . DIRECTORY_SEPARATOR;
        foreach ($files as $file) {
            $relativePath = str_replace($base, '', $file);
            if (CodeCrypter::decryptFile($file)) {
                $count++;
                $logs[] = "Decrypted/Refreshed: {$relativePath}";
            } else {
                $logs[] = "Skipped (Already decrypted): {$relativePath}";
            }
        }

        return redirect()->route('system.lock')
            ->with('success', "Successfully decrypted and refreshed {$count} PHP source files!")
            ->with('logs', $logs);
    }
}
