<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email — SMPSI Paroki Kalasan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 flex items-center justify-center p-4">

    <div class="w-full max-w-sm">

        <div class="text-center mb-8">
            <img src="{{ asset('img/LOGO PAROKI-WARNA.png') }}" alt="Logo Paroki" class="w-20 h-20 object-contain mx-auto mb-4 drop-shadow-lg">
            <h1 class="text-xl font-bold text-white">SMPSI</h1>
            <p class="text-slate-400 text-sm mt-1">Paroki Maria Marganingsih Kalasan</p>
        </div>

        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-7 shadow-2xl text-center">
            <h2 class="text-lg font-semibold text-white mb-2">Verifikasi email Anda</h2>
            <p class="text-sm text-slate-400 mb-6">
                Kami sudah mengirim link verifikasi ke email Anda. Buka email itu dan klik link-nya untuk mengaktifkan akun.
            </p>

            @if(session('success'))
                <div class="mb-4 p-3.5 bg-green-500/10 border border-green-500/30 rounded-xl text-sm text-green-400">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-500 text-white font-semibold py-2.5 rounded-xl text-sm transition-colors shadow-lg shadow-blue-900/30">
                    Kirim ulang email verifikasi
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}" class="mt-3">
                @csrf
                <button type="submit" class="w-full text-slate-500 hover:text-slate-300 text-sm py-2 transition-colors">
                    Keluar
                </button>
            </form>
        </div>
    </div>

</body>
</html>
