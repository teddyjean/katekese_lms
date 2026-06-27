<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar — SMPSI Paroki Kalasan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 flex items-center justify-center p-4 py-10">

    <div class="w-full max-w-sm">

        {{-- Logo --}}
        <div class="text-center mb-8">
            <img src="{{ asset('img/LOGO PAROKI-WARNA.png') }}" alt="Logo Paroki" class="w-20 h-20 object-contain mx-auto mb-4 drop-shadow-lg">
            <h1 class="text-xl font-bold text-white">SMPSI</h1>
            <p class="text-slate-400 text-sm mt-1">Paroki Maria Marganingsih Kalasan</p>
        </div>

        {{-- Card --}}
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-7 shadow-2xl">
            <h2 class="text-lg font-semibold text-white mb-6">Buat akun baru</h2>

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" required autofocus
                           placeholder="Nama lengkap Anda"
                           class="w-full bg-slate-800 border border-slate-700 text-white placeholder-slate-500 rounded-xl px-4 py-2.5 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition
                                  @error('name') border-red-500 @enderror">
                    @error('name') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           placeholder="email@example.com"
                           class="w-full bg-slate-800 border border-slate-700 text-white placeholder-slate-500 rounded-xl px-4 py-2.5 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition
                                  @error('email') border-red-500 @enderror">
                    @error('email') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">No HP</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" required
                           placeholder="08xxxxxxxxxx"
                           class="w-full bg-slate-800 border border-slate-700 text-white placeholder-slate-500 rounded-xl px-4 py-2.5 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition
                                  @error('phone') border-red-500 @enderror">
                    @error('phone') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Saya adalah</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="relative cursor-pointer">
                            <input type="radio" name="role" value="peserta" class="peer sr-only"
                                   {{ old('role', 'peserta') === 'peserta' ? 'checked' : '' }}>
                            <div class="border-2 border-slate-700 bg-slate-800 rounded-xl p-4 text-center transition-all
                                        peer-checked:border-blue-500 peer-checked:bg-blue-500/10">
                                <p class="text-2xl mb-1.5">🙏</p>
                                <p class="text-sm font-semibold text-slate-200">Siswa</p>
                                <p class="text-xs text-slate-500 mt-0.5">Calon Baptis / Krisma</p>
                            </div>
                        </label>
                        <label class="relative cursor-pointer">
                            <input type="radio" name="role" value="katekis" class="peer sr-only"
                                   {{ old('role') === 'katekis' ? 'checked' : '' }}>
                            <div class="border-2 border-slate-700 bg-slate-800 rounded-xl p-4 text-center transition-all
                                        peer-checked:border-violet-500 peer-checked:bg-violet-500/10">
                                <p class="text-2xl mb-1.5">📖</p>
                                <p class="text-sm font-semibold text-slate-200">Katekis</p>
                                <p class="text-xs text-slate-500 mt-0.5">Pembina / Pengajar</p>
                            </div>
                        </label>
                    </div>
                    @error('role') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Password</label>
                    <input type="password" name="password" required placeholder="Minimal 8 karakter"
                           class="w-full bg-slate-800 border border-slate-700 text-white placeholder-slate-500 rounded-xl px-4 py-2.5 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition
                                  @error('password') border-red-500 @enderror">
                    @error('password') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" required placeholder="Ulangi password"
                           class="w-full bg-slate-800 border border-slate-700 text-white placeholder-slate-500 rounded-xl px-4 py-2.5 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                </div>

                <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-500 text-white font-semibold py-2.5 rounded-xl text-sm transition-colors shadow-lg shadow-blue-900/30 mt-2">
                    Buat Akun
                </button>
            </form>
        </div>

        <p class="text-center text-sm text-slate-500 mt-6">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-blue-400 hover:text-blue-300 font-medium transition-colors">Masuk</a>
        </p>
    </div>

</body>
</html>
