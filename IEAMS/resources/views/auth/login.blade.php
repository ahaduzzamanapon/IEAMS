<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - IEAMS NHA</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full flex items-center justify-center bg-[#F8FAFC] px-4">

    <div class="w-full max-w-md space-y-8 bg-white p-8 rounded-2xl border border-slate-200 shadow-xl">
        <!-- Logo and Heading -->
        <div class="text-center space-y-2">
            <div class="inline-flex w-12 h-12 rounded-xl bg-gradient-to-tr from-[#0e76bc] to-blue-500 items-center justify-center font-bold text-white shadow-md shadow-blue-500/20 text-lg">
                M
            </div>
            <h2 class="text-2xl font-extrabold text-slate-900">Sign in to Mysoft IEAMS</h2>
            <p class="text-xs text-slate-500">National Housing Authority System</p>
        </div>

        <!-- Session Status / Errors -->
        @if ($errors->any())
            <div class="p-4 rounded-xl bg-rose-50 border border-rose-100 text-rose-600 text-xs space-y-1">
                @foreach ($errors->all() as $error)
                    <div>• {{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label for="email" class="block text-xs font-semibold text-slate-600 mb-1.5">Email Address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="admin@nha.gov.bd" class="w-full bg-white border border-slate-300 rounded-xl px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-[#0e76bc] focus:ring-1 focus:ring-[#0e76bc] transition">
            </div>

            <div>
                <label for="password" class="block text-xs font-semibold text-slate-600 mb-1.5">Password</label>
                <div class="relative" style="position: relative;">
                    <input id="password" type="password" name="password" required placeholder="••••••••" class="w-full bg-white border border-slate-300 rounded-xl pl-4 pr-12 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-[#0e76bc] focus:ring-1 focus:ring-[#0e76bc] transition">
                    <button type="button" onclick="togglePasswordVisibility()" style="position: absolute; right: 16px; top: 50%; transform: translateY(-50%); background: none; border: none; padding: 0;" class="text-slate-400 hover:text-[#0e76bc] transition cursor-pointer flex items-center justify-center">
                        <!-- Eye Icon -->
                        <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                        <!-- Eye Slash Icon -->
                        <svg id="eye-slash-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 hidden">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 text-[#0e76bc] bg-white border-slate-300 rounded focus:ring-[#0e76bc]">
                    <label for="remember_me" class="ml-2 text-xs text-slate-500 select-none">Remember me</label>
                </div>
            </div>

            <button type="submit" class="w-full py-3 bg-[#0e76bc] hover:bg-[#0b5d94] text-white font-medium text-sm rounded-xl transition shadow-lg shadow-blue-500/10 cursor-pointer">
                Sign In
            </button>
        </form>

        <div class="text-center pt-2">
            <span class="text-[10px] text-slate-400">Default Credentials: admin@nha.gov.bd / password</span>
        </div>
    </div>

    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            const eyeSlashIcon = document.getElementById('eye-slash-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.add('hidden');
                eyeSlashIcon.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('hidden');
                eyeSlashIcon.classList.add('hidden');
            }
        }
    </script>
</body>
</html>
