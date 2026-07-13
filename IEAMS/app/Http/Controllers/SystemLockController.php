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
        foreach ($files as $file) {
            if (CodeCrypter::encryptFile($file)) {
                $count++;
            }
        }

        return redirect()->route('system.lock')->with('success', "Successfully encrypted {$count} PHP source files!");
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
        foreach ($files as $file) {
            if (CodeCrypter::decryptFile($file)) {
                $count++;
            }
        }

        return redirect()->route('system.lock')->with('success', "Successfully decrypted and refreshed {$count} PHP source files!");
    }
}
