<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — SMPSI Paroki Kalasan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 flex items-center justify-center p-4">

    <div class="w-full max-w-sm">

        {{-- Logo --}}
        <div class="text-center mb-8">
            <img src="{{ asset('img/LOGO PAROKI-WARNA.png') }}" alt="Logo Paroki" class="w-20 h-20 object-contain mx-auto mb-4 drop-shadow-lg">
            <h1 class="text-xl font-bold text-white">SMPSI</h1>
            <p class="text-slate-400 text-sm mt-1">Paroki Maria Marganingsih Kalasan</p>
        </div>

        {{-- Card --}}
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-7 shadow-2xl">
            <h2 class="text-lg font-semibold text-white mb-6">Masuk ke akun Anda</h2>

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
                </div>

                <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-500 text-white font-semibold py-2.5 rounded-xl text-sm transition-colors shadow-lg shadow-blue-900/30 mt-2">
                    Masuk
                </button>
            </form>
        </div>

        <p class="text-center text-sm text-slate-500 mt-6">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-blue-400 hover:text-blue-300 font-medium transition-colors">Daftar sekarang</a>
        </p>
    </div>

</body>
</html>
