<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login | RuangPinjam</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta http-equiv="X-UA-Compatible" content="ie=edge">
     @include('layouts.link')

    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-[#132440] via-blue-900 to-blue-700">

    <!-- Background Decoration -->
    <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1')] bg-cover bg-center opacity-20"></div>

    <!-- Login Card -->
    <div class="relative w-full max-w-md mx-4">
        <div class="bg-white/95 backdrop-blur-xl rounded-2xl shadow-2xl p-8">

            <!-- Logo -->
            <div class="text-center mb-6">
                <div class="w-14 h-14 mx-auto mb-3 rounded-xl bg-gradient-to-r from-blue-600 to-teal-500 flex items-center justify-center shadow-lg">
                    <i class="fas fa-school text-white text-xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-[#132440]">
                    School<span class="text-blue-600">Loan</span>Sphere
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Login ke sistem peminjaman
                </p>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Username
                    </label>
                    <div class="relative">
                        <i class="fas fa-envelope absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input
                            type="text"
                            name="username"
                            value="{{ old('username') }}"
                            required
                            autofocus
                            class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                        >
                    </div>
                    @error('username')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Password
                    </label>
                    <div class="relative">
                        <i class="fas fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input
                            type="password"
                            name="password"
                            required
                            class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                        >
                    </div>
                    @error('password')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember & Forgot -->
                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center gap-2 text-gray-600">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-blue-600">
                        Remember me
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-blue-600 hover:underline">
                            Lupa password?
                        </a>
                    @endif
                </div>

                <!-- Button -->
                <button
                    type="submit"
                    class="w-full py-3 rounded-lg bg-gradient-to-r from-blue-600 to-blue-500 text-white font-semibold hover:from-blue-700 hover:to-blue-600 transition shadow-lg"
                >
                    <i class="fas fa-sign-in-alt mr-2"></i> Login
                </button>
            </form>

            <!-- Back to Landing -->
            <div class="text-center mt-6">
                <a href="{{ url('/') }}" class="text-sm text-gray-500 hover:text-blue-600">
                    ← Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>

</body>
</html>
