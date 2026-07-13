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
    public function encrypt()
    {
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
    public function decrypt()
    {
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
