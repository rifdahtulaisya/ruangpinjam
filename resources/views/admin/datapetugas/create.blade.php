@extends('layouts-admin.admin')

@section('content')

<!-- HEADER BOX -->
<div class="bg-white rounded-xl shadow p-5 mb-6">
    <div class="flex items-center gap-4">
        <!-- BACK BUTTON -->
        <a href="{{ route('admin.datapetugas.index') }}"
           class="w-10 h-10 flex items-center justify-center rounded-lg
                  bg-slate-100 text-slate-600 hover:bg-slate-200 transition">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-xl font-semibold text-slate-800">Tambah Akun Petugas</h1>
            <p class="text-sm text-slate-500">Tambah data petugas baru</p>
        </div>
    </div>
</div>

<!-- ALERT MESSAGE -->
@if(session('success'))
<div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <i class="fa-solid fa-check-circle text-green-500"></i>
            <p class="font-medium">{{ session('success') }}</p>
        </div>
        <button type="button" onclick="this.parentElement.parentElement.remove()" class="text-green-500 hover:text-green-700">
            <i class="fa-solid fa-times"></i>
        </button>
    </div>
</div>
@endif

<!-- FORM CARD -->
<div class="flex flex-col lg:flex-row gap-6">
    <!-- FORM SECTION -->
    <div class="bg-white rounded-xl shadow p-6 flex-1">
        <form action="{{ route('admin.datapetugas.store') }}" method="POST">
            @csrf
            
            <div class="grid gap-5">
                <!-- NAME -->
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="name"
                           value="{{ old('name') }}"
                           required
                           class="w-full px-4 py-2.5 border border-slate-300 rounded-lg
                                  focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                                  placeholder:text-slate-400"
                           placeholder="Masukkan nama lengkap">
                    @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- USERNAME -->
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">
                        Username <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="username"
                           value="{{ old('username') }}"
                           required
                           class="w-full px-4 py-2.5 border border-slate-300 rounded-lg
                                  focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                                  placeholder:text-slate-400"
                           placeholder="Masukkan username untuk login">
                    @error('username')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-slate-500 mt-1">Username digunakan untuk login sistem</p>
                </div>

                <!-- EMAIL -->
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email"
                           name="email"
                           value="{{ old('email') }}"
                           required
                           class="w-full px-4 py-2.5 border border-slate-300 rounded-lg
                                  focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                                  placeholder:text-slate-400"
                           placeholder="contoh@email.com">
                    @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

    

                <!-- BUTTON -->
                <div class="flex justify-end gap-3 pt-4">
                    <a href="{{ route('admin.datapetugas.index') }}"
                       class="px-5 py-2.5 rounded-lg bg-slate-200 text-slate-700 hover:bg-slate-300
                              transition flex items-center gap-2">
                        <i class="fa-solid fa-times"></i>
                        Batal
                    </a>
                    <button type="submit"
                            class="px-5 py-2.5 rounded-lg bg-indigo-500 text-white hover:bg-indigo-600
                                   transition flex items-center gap-2">
                        <i class="fa-solid fa-user-plus"></i>
                        Buat Akun
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- IMAGE SECTION -->
    <div class="hidden lg:flex w-full lg:w-1/3 xl:w-1/4 flex-col items-center justify-center">
        <div class="bg-white rounded-xl shadow p-8 w-full h-full flex flex-col items-center justify-center">
            <!-- Illustration Image -->
            <div class="mb-6">
                <div class="w-48 h-48 mx-auto bg-gradient-to-br from-indigo-100 to-purple-100 
                            rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-user-tie text-6xl text-indigo-500"></i>
                </div>
            </div>
            
            <!-- Informasi -->
            <div class="text-center">
                <h3 class="text-lg font-semibold text-slate-800 mb-2">Tambahkan Petugas Baru</h3>
                <p class="text-sm text-slate-600">
                    Pastikan data yang dimasukkan akurat. Username dan password default akan digunakan untuk login.
                </p>
                
                <!-- Tips Box -->
                <div class="mt-6 p-4 bg-indigo-50 rounded-lg border border-indigo-100">
                    <h4 class="text-sm font-medium text-indigo-800 mb-2 flex items-center gap-2">
                        <i class="fa-solid fa-lightbulb"></i> Informasi
                    </h4>
                    <ul class="text-xs text-indigo-700 space-y-1 text-left">
                        <li>• Username bersifat unik</li>
                        <li>• Password default: <span class="font-mono bg-indigo-200 px-1.5 py-0.5 rounded">password123</span></li>
                        <li>• Petugas dapat mereset password sendiri</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection