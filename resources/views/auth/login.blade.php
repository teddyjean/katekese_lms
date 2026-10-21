<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — SMPSI Paroki Kalasan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 flex items-center justify-center p-4 py-6">

    <div class="w-full max-w-sm lg:max-w-4xl lg:grid lg:grid-cols-2 lg:gap-12 lg:items-center">

        {{-- Branding --}}
        <div class="text-center lg:text-left mb-6 lg:mb-0">
            <img src="{{ asset('img/LOGO PAROKI-WARNA.png') }}" alt="Logo Paroki"
                 class="w-16 h-16 lg:w-24 lg:h-24 object-contain mx-auto lg:mx-0 mb-3 lg:mb-5 drop-shadow-lg">
            <p class="text-slate-500 text-xs font-semibold tracking-widest uppercase mb-1">SMPSI</p>
            <h1 class="text-lg sm:text-xl lg:text-3xl font-bold text-white leading-snug">Sistem Manajemen Pembelajaran Sakramen Inisiasi</h1>
            <p class="text-slate-400 text-sm lg:text-base mt-1 lg:mt-2">Paroki Maria Marganingsih Kalasan</p>
        </div>

        {{-- Form --}}
        <div>
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 sm:p-7 shadow-2xl">
                <h2 class="text-lg font-semibold text-white mb-5">Masuk ke akun Anda</h2>

                @if(session('success'))
                    <div class="mb-4 p-3.5 bg-green-500/10 border border-green-500/30 rounded-xl text-sm text-green-400">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-4 p-3.5 bg-red-500/10 border border-red-500/30 rounded-xl text-sm text-red-400">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1.5">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                               placeholder="email@gereja.org"
                               class="w-full bg-slate-800 border border-slate-700 text-white placeholder-slate-500 rounded-xl px-4 py-2.5 text-sm
                                      focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition
                                      @error('email') border-red-500 @enderror">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1.5">Password</label>
                        <input type="password" name="password" required placeholder="••••••••"
                               class="w-full bg-slate-800 border border-slate-700 text-white placeholder-slate-500 rounded-xl px-4 py-2.5 text-sm
                                      focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="remember" class="rounded border-slate-600 bg-slate-800 text-blue-600 focus:ring-blue-500 focus:ring-offset-slate-900">
                            <span class="text-sm text-slate-400">Ingat saya</span>
                        </label>
                        <a href="{{ route('password.request') }}" class="text-sm text-blue-400 hover:text-blue-300 font-medium transition-colors">Lupa password?</a>
                    </div>

                    <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-500 text-white font-semibold py-2.5 rounded-xl text-sm transition-colors shadow-lg shadow-blue-900/30 mt-2">
                        Masuk
                    </button>
                </form>

                <p class="text-center text-sm text-slate-500 mt-5 pt-5 border-t border-slate-800">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="text-blue-400 hover:text-blue-300 font-medium transition-colors">Daftar sekarang</a>
                </p>
            </div>
        </div>

    </div>

</body>
</html>
