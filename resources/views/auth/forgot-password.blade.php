<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password — SMPSI Paroki Kalasan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 flex items-center justify-center p-4 py-6">

    <div class="w-full max-w-sm">
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 sm:p-7 shadow-2xl">
            <h2 class="text-lg font-semibold text-white mb-2">Lupa password?</h2>
            <p class="text-sm text-slate-400 mb-5">Masukkan email Anda, kami akan kirim link untuk reset password.</p>

            @if(session('status'))
                <div class="mb-4 p-3.5 bg-green-500/10 border border-green-500/30 rounded-xl text-sm text-green-400">
                    {{ session('status') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 p-3.5 bg-red-500/10 border border-red-500/30 rounded-xl text-sm text-red-400">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                           placeholder="email@gereja.org"
                           class="w-full bg-slate-800 border border-slate-700 text-white placeholder-slate-500 rounded-xl px-4 py-2.5 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition
                                  @error('email') border-red-500 @enderror">
                </div>

                <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-500 text-white font-semibold py-2.5 rounded-xl text-sm transition-colors shadow-lg shadow-blue-900/30 mt-2">
                    Kirim Link Reset
                </button>
            </form>

            <p class="text-center text-sm text-slate-500 mt-5 pt-5 border-t border-slate-800">
                <a href="{{ route('login') }}" class="text-blue-400 hover:text-blue-300 font-medium transition-colors">Kembali ke halaman masuk</a>
            </p>
        </div>
    </div>

</body>
</html>
