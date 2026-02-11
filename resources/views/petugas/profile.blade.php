@extends('layouts-petugas.petugas')

@section('title', 'PROFIL')
@section('subtitle', 'Kelola informasi akun Anda')

@section('content')
<div class="space-y-8 animate-slide-in">
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-full bg-emerald-100 flex items-center justify-center">
                <i class="fa-solid fa-user-gear text-emerald-600 text-2xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Profil Petugas</h2>
                <p class="text-slate-500">Kelola username, email, dan password Anda</p>
            </div>
        </div>
    </div>

    <!-- Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column - Profile Info (Sederhana, tanpa foto) -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-circle-user text-emerald-500"></i>
                    Informasi Akun
                </h3>
                
                <div class="flex flex-col items-center">
                    <!-- Avatar default dengan inisial -->
                    <div class="w-32 h-32 rounded-full bg-gradient-to-br from-emerald-400 to-teal-500 
                                flex items-center justify-center text-white text-4xl font-bold mb-4
                                shadow-lg">
                        {{ strtoupper(substr(Auth::user()->name ?? 'P', 0, 1)) }}
                    </div>
                    
                    <h4 class="text-xl font-bold text-slate-800">{{ Auth::user()->name }}</h4>
                    <p class="text-sm text-slate-500">{{ Auth::user()->username }}</p>
                    <p class="text-sm text-slate-500">{{ Auth::user()->email }}</p>
                    
                    <div class="mt-4 w-full">
                        <div class="bg-emerald-50 rounded-xl p-3 text-center">
                            <span class="px-3 py-1.5 bg-emerald-100 text-emerald-700 rounded-full text-xs font-medium">
                                <i class="fa-solid fa-shield-alt mr-1"></i> Petugas
                            </span>
                        </div>
                    </div>

                    <!-- Info Akun -->
                    <div class="mt-6 w-full border-t border-slate-200 pt-4">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-500">Bergabung</span>
                            <span class="font-medium text-slate-800">
                                {{ Auth::user()->created_at ? Auth::user()->created_at->format('d/m/Y') : '-' }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center text-sm mt-2">
                            <span class="text-slate-500">Terakhir Update</span>
                            <span class="font-medium text-slate-800">
                                {{ Auth::user()->updated_at ? Auth::user()->updated_at->format('d/m/Y') : '-' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Edit Profile Form -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Form Edit Profil -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-user-pen text-emerald-500"></i>
                    Edit Profil
                </h3>

                @if(session('success'))
                    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 flex items-center gap-3">
                        <i class="fa-solid fa-circle-check"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li class="text-sm">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('petugas.profile.update') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nama Lengkap -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                <i class="fa-regular fa-user mr-1 text-emerald-500"></i>
                                Nama Lengkap
                            </label>
                            <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" required
                                   class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl 
                                          focus:border-emerald-500 focus:ring-3 focus:ring-emerald-500/30 
                                          transition @error('name') border-red-500 @enderror"
                                   placeholder="Masukkan nama lengkap">
                            @error('name')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Username -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                <i class="fa-regular fa-at mr-1 text-emerald-500"></i>
                                Username
                            </label>
                            <input type="text" name="username" value="{{ old('username', Auth::user()->username) }}" required
                                   class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl 
                                          focus:border-emerald-500 focus:ring-3 focus:ring-emerald-500/30 
                                          transition @error('username') border-red-500 @enderror"
                                   placeholder="Masukkan username">
                            @error('username')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                <i class="fa-regular fa-envelope mr-1 text-emerald-500"></i>
                                Email
                            </label>
                            <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" required
                                   class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl 
                                          focus:border-emerald-500 focus:ring-3 focus:ring-emerald-500/30 
                                          transition @error('email') border-red-500 @enderror"
                                   placeholder="Masukkan email">
                            @error('email')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Tombol Submit -->
                    <div class="flex gap-3">
                        <button type="submit"
                                class="px-6 py-3 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 
                                       transition font-medium flex items-center gap-2 shadow-md hover:shadow-lg">
                            <i class="fa-regular fa-floppy-disk"></i>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            <!-- Form Ganti Password -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-lock text-emerald-500"></i>
                    Ganti Password
                </h3>

                <form action="{{ route('petugas.profile.change-password') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 gap-6">
                        <!-- Password Saat Ini -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                Password Saat Ini
                            </label>
                            <div class="relative">
                                <input type="password" name="current_password" id="current_password" required
                                       class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl 
                                              focus:border-emerald-500 focus:ring-3 focus:ring-emerald-500/30 
                                              transition pr-12">
                                <button type="button" 
                                        onclick="togglePassword('current_password', this)"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Password Baru -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                Password Baru
                            </label>
                            <div class="relative">
                                <input type="password" name="new_password" id="new_password" required
                                       class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl 
                                              focus:border-emerald-500 focus:ring-3 focus:ring-emerald-500/30 
                                              transition pr-12">
                                <button type="button" 
                                        onclick="togglePassword('new_password', this)"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>
                            <p class="text-xs text-slate-500 mt-1">Minimal 8 karakter</p>
                        </div>

                        <!-- Konfirmasi Password Baru -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                Konfirmasi Password Baru
                            </label>
                            <div class="relative">
                                <input type="password" name="new_password_confirmation" id="new_password_confirmation" required
                                       class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl 
                                              focus:border-emerald-500 focus:ring-3 focus:ring-emerald-500/30 
                                              transition pr-12">
                                <button type="button" 
                                        onclick="togglePassword('new_password_confirmation', this)"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit"
                                class="px-6 py-3 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 
                                       transition font-medium flex items-center gap-2 shadow-md hover:shadow-lg">
                            Ubah Password
                        </button>
                        <button type="reset"
                                class="px-6 py-3 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 
                                       transition font-medium flex items-center gap-2">
                            Reset
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Fungsi untuk toggle show/hide password
function togglePassword(inputId, button) {
    const input = document.getElementById(inputId);
    const icon = button.querySelector('i');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Animasi
document.addEventListener('DOMContentLoaded', function() {
    const animateElements = document.querySelectorAll('.animate-slide-in');
    animateElements.forEach((el, index) => {
        el.style.animationDelay = `${index * 0.1}s`;
    });
});
</script>

<style>
.animate-slide-in {
    animation: slideIn 0.5s ease-out forwards;
    opacity: 0;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
@endsection