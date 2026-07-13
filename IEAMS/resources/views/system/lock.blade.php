@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    
    <!-- Header -->
    <div class="border-b border-slate-800 pb-4">
        <h2 class="text-3xl font-extrabold text-white tracking-wider">🔒 System Source Code Lock</h2>
        <p class="text-slate-400 text-sm mt-1">Manage local PHP source code protection and toggle hex-obfuscation mode.</p>
    </div>

    <!-- Feedback Message -->
    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 text-sm">
            {{ session('error') }}
        </div>
    @endif
    @if($errors->any())
        <div class="p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 text-sm">
            {{ $errors->first() }}
        </div>
    @endif

    <!-- Encryption Log Console -->
    @if(session('logs'))
        <div class="p-6 rounded-2xl bg-[#080b12] border border-slate-800/80 space-y-3 shadow-xl">
            <div class="flex items-center justify-between border-b border-slate-800 pb-2">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest flex items-center gap-2">
                    <span class="inline-block w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    Execution Log Output
                </span>
                <span class="text-[10px] text-slate-500 font-mono">Total Files Processed: {{ count(session('logs')) }}</span>
            </div>
            <div class="max-h-48 overflow-y-auto font-mono text-xs text-slate-300 space-y-1.5 p-2 bg-slate-950/80 rounded-xl scrollbar-thin scrollbar-thumb-slate-800 scrollbar-track-transparent">
                @foreach(session('logs') as $log)
                    <div class="flex items-start gap-2">
                        <span class="text-slate-500 select-none">&gt;</span>
                        <span class="{{ str_contains($log, 'Encrypted') || str_contains($log, 'Decrypted') ? 'text-emerald-400' : 'text-slate-500' }}">{{ $log }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        
        <!-- Status Card -->
        <div class="md:col-span-1 p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/80 flex flex-col justify-between space-y-6">
            <div>
                <span class="text-xs font-bold text-indigo-400 uppercase tracking-widest">Current Status</span>
                <div class="mt-4 flex items-baseline gap-2">
                    <span class="text-4xl font-extrabold text-white">{{ $status['status'] }}</span>
                </div>
                <p class="text-slate-400 text-xs mt-2">{{ $status['encrypted'] }} of {{ $status['total'] }} files currently obfuscated.</p>
            </div>

            <!-- Progress Bar -->
            <div class="space-y-2">
                <div class="flex justify-between text-xs font-semibold text-slate-400">
                    <span>Lock Level</span>
                    <span>{{ $status['percentage'] }}%</span>
                </div>
                <div class="w-full h-2.5 bg-slate-800 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-indigo-500 to-pink-500 transition-all duration-500" style="width: {{ $status['percentage'] }}%"></div>
                </div>
            </div>
        </div>

        <!-- Controls Card -->
        <div class="md:col-span-2 p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/80 space-y-6">
            <h3 class="text-lg font-bold text-white uppercase tracking-wider border-b border-slate-850 pb-2">Lock Controls</h3>
            
            <p class="text-slate-300 text-sm leading-relaxed">
                You can toggle the source code state of all core controllers and models. When locked, files on the server are compiled to hex strings inside <code>eval()</code> wrappers, rendering them unreadable to FTP/SSH viewers, while the application runs fully normally.
            </p>

            <div class="space-y-4 pt-2">
                <div class="space-y-2">
                    <label for="shield_key" class="text-xs font-semibold text-slate-400">
                        @if($status['status'] === 'Encrypted')
                            Security Decryption Key / Passphrase
                        @else
                            Security Encryption Key / Passphrase
                        @endif
                    </label>
                    <input type="password" id="shield_key" name="shield_key" placeholder="{{ $status['status'] === 'Encrypted' ? 'Enter the passphrase to Unlock' : 'Enter any custom passphrase to Lock' }}" class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-800 text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 text-sm" required>
                </div>

                @if($status['status'] !== 'Encrypted')
                    <div class="space-y-2">
                        <label for="shield_key_confirm" class="text-xs font-semibold text-slate-400">Confirm Encryption Key / Passphrase</label>
                        <input type="password" id="shield_key_confirm" name="shield_key_confirm" placeholder="Confirm your custom passphrase" class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-800 text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 text-sm" required>
                    </div>
                @endif

                <div class="flex flex-col sm:flex-row gap-4 pt-2">
                    @if($status['status'] !== 'Encrypted')
                        <button type="button" onclick="submitLockForm('{{ route('system.lock.encrypt') }}', 'WARNING: This will encrypt/obfuscate all controllers and models files on the server disk. The application will continue to run normally but the source code files will not be readable. Proceed?', true)" class="px-5 py-3 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-semibold text-sm transition-all duration-200 shadow-lg shadow-rose-900/20 cursor-pointer">
                            🔒 Lock / Encrypt Code
                        </button>
                    @endif

                    @if($status['status'] !== 'Decrypted')
                        <button type="button" onclick="submitLockForm('{{ route('system.lock.decrypt') }}', 'This will restore all encrypted controllers and models back to original clean PHP source code. Proceed?', false)" class="px-5 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-sm transition-all duration-200 shadow-lg shadow-emerald-900/20 cursor-pointer">
                            🔓 Refresh / Decrypt Code
                        </button>
                    @endif
                </div>
            </div>

            <!-- Hidden Helper Form -->
            <form id="lockForm" method="POST" style="display: none;">
                @csrf
                <input type="hidden" id="form_shield_key" name="shield_key">
                <input type="hidden" id="form_shield_key_confirmation" name="shield_key_confirmation">
            </form>

            <script>
                function submitLockForm(actionUrl, confirmMessage, isEncrypting) {
                    const keyVal = document.getElementById('shield_key').value.trim();
                    if (!keyVal) {
                        alert('Please enter your Passphrase Key!');
                        document.getElementById('shield_key').focus();
                        return;
                    }
                    if (isEncrypting) {
                        const confirmVal = document.getElementById('shield_key_confirm').value.trim();
                        if (!confirmVal) {
                            alert('Please confirm your Passphrase Key!');
                            document.getElementById('shield_key_confirm').focus();
                            return;
                        }
                        if (keyVal !== confirmVal) {
                            alert('Passphrase Keys do not match! Please verify both fields.');
                            document.getElementById('shield_key_confirm').focus();
                            return;
                        }
                        document.getElementById('form_shield_key_confirmation').value = confirmVal;
                    }
                    if (confirm(confirmMessage)) {
                        const form = document.getElementById('lockForm');
                        form.action = actionUrl;
                        document.getElementById('form_shield_key').value = keyVal;
                        form.submit();
                    }
                }
            </script>
        </div>

    </div>

    <!-- Warnings / Documentation Panel -->
    <div class="p-6 rounded-2xl bg-amber-500/5 border border-amber-500/10 space-y-4">
        <h4 class="text-sm font-bold text-amber-400 uppercase tracking-widest flex items-center gap-2">
            ⚠️ IMPORTANT OPERATIONAL NOTICE
        </h4>
        <ul class="list-disc list-inside text-xs text-slate-300 space-y-2 leading-relaxed">
            <li><strong>Dynamic Passphrase Lock:</strong> You can choose and enter any custom key to encrypt/lock the system. However, you must provide that exact same key to decrypt and restore the source files back to normal.</li>
            <li><strong>Zero Extensions Required:</strong> This utility uses standard PHP native hex execution wrappers (<code>eval(hex2bin(...))</code>), meaning it works instantly on any basic shared hosting server.</li>
            <li><strong>Safe Areas:</strong> The SystemLockController, CodeCrypter service, AppServiceProvider, and standard Authentication controllers are automatically skipped from encryption to guarantee you can always log back in and unlock the source code anytime.</li>
            <li><strong>Git Workflows:</strong> Ensure you run <strong>Decrypt / Refresh</strong> before modifying any code locally or pushing to Git, to avoid committing encrypted files into your version history.</li>
        </ul>
    </div>

</div>
@endsection
