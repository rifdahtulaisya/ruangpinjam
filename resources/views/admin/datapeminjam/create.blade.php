@extends('layouts-admin.admin')

@section('content')

<!-- HEADER BOX -->
<div class="bg-white rounded-xl shadow p-5 mb-6">
    <div class="flex items-center gap-4">
        <!-- BACK BUTTON -->
        <a href="{{ route('admin.datapeminjam.index') }}"
           class="w-10 h-10 flex items-center justify-center rounded-lg
                  bg-slate-100 text-slate-600 hover:bg-slate-200 transition">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-xl font-semibold text-slate-800">Tambah Akun Peminjam</h1>
            <p class="text-sm text-slate-500">Tambah data peminjam baru</p>
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
        <form action="{{ route('admin.datapeminjam.store') }}" method="POST">
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

                <!-- USERNAME (WAJIB) -->
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
                           placeholder="Untuk login">
                    @error('username')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-slate-500 mt-1">Username ini digunakan untuk login sistem</p>
                </div>

                <!-- KELAS JURUSAN -->
<div>
    <label class="block text-sm font-medium text-slate-600 mb-1">
        Kelas & Jurusan <span class="text-red-500">*</span>
    </label>
    <select name="kelas_jurusan"
            required
            class="w-full px-4 py-2.5 border border-slate-300 rounded-lg
                   focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                   bg-white">
        <option value="">Pilih Kelas & Jurusan</option>
        
        <!-- Kelas 10 -->
        <optgroup label="Kelas 10">
            <option value="10 PPLG 1" {{ old('kelas_jurusan') == '10 PPLG 1' ? 'selected' : '' }}>10 PPLG 1</option>
            <option value="10 PPLG 2" {{ old('kelas_jurusan') == '10 PPLG 2' ? 'selected' : '' }}>10 PPLG 2</option>
            <option value="10 PPLG 3" {{ old('kelas_jurusan') == '10 PPLG 3' ? 'selected' : '' }}>10 PPLG 3</option>
            
            <option value="10 ANM 1" {{ old('kelas_jurusan') == '10 ANM 1' ? 'selected' : '' }}>10 ANM 1</option>
            <option value="10 ANM 2" {{ old('kelas_jurusan') == '10 ANM 2' ? 'selected' : '' }}>10 ANM 2</option>
            <option value="10 ANM 3" {{ old('kelas_jurusan') == '10 ANM 3' ? 'selected' : '' }}>10 ANM 3</option>
            
            <option value="10 BC 1" {{ old('kelas_jurusan') == '10 BC 1' ? 'selected' : '' }}>10 BC 1</option>
            <option value="10 BC 2" {{ old('kelas_jurusan') == '10 BC 2' ? 'selected' : '' }}>10 BC 2</option>
            <option value="10 BC 3" {{ old('kelas_jurusan') == '10 BC 3' ? 'selected' : '' }}>10 BC 3</option>
            
            <option value="10 TPFL 1" {{ old('kelas_jurusan') == '10 TPFL 1' ? 'selected' : '' }}>10 TPFL 1</option>
            <option value="10 TPFL 2" {{ old('kelas_jurusan') == '10 TPFL 2' ? 'selected' : '' }}>10 TPFL 2</option>
            <option value="10 TPFL 3" {{ old('kelas_jurusan') == '10 TPFL 3' ? 'selected' : '' }}>10 TPFL 3</option>
            
            <option value="10 TO 1" {{ old('kelas_jurusan') == '10 TO 1' ? 'selected' : '' }}>10 TO 1</option>
            <option value="10 TO 2" {{ old('kelas_jurusan') == '10 TO 2' ? 'selected' : '' }}>10 TO 2</option>
            <option value="10 TO 3" {{ old('kelas_jurusan') == '10 TO 3' ? 'selected' : '' }}>10 TO 3</option>
        </optgroup>
        
        <!-- Kelas 11 -->
        <optgroup label="Kelas 11">
            <option value="11 PPLG 1" {{ old('kelas_jurusan') == '11 PPLG 1' ? 'selected' : '' }}>11 PPLG 1</option>
            <option value="11 PPLG 2" {{ old('kelas_jurusan') == '11 PPLG 2' ? 'selected' : '' }}>11 PPLG 2</option>
            <option value="11 PPLG 3" {{ old('kelas_jurusan') == '11 PPLG 3' ? 'selected' : '' }}>11 PPLG 3</option>
            
            <option value="11 ANM 1" {{ old('kelas_jurusan') == '11 ANM 1' ? 'selected' : '' }}>11 ANM 1</option>
            <option value="11 ANM 2" {{ old('kelas_jurusan') == '11 ANM 2' ? 'selected' : '' }}>11 ANM 2</option>
            <option value="11 ANM 3" {{ old('kelas_jurusan') == '11 ANM 3' ? 'selected' : '' }}>11 ANM 3</option>
            
            <option value="11 BC 1" {{ old('kelas_jurusan') == '11 BC 1' ? 'selected' : '' }}>11 BC 1</option>
            <option value="11 BC 2" {{ old('kelas_jurusan') == '11 BC 2' ? 'selected' : '' }}>11 BC 2</option>
            <option value="11 BC 3" {{ old('kelas_jurusan') == '11 BC 3' ? 'selected' : '' }}>11 BC 3</option>
            
            <option value="11 TPFL 1" {{ old('kelas_jurusan') == '11 TPFL 1' ? 'selected' : '' }}>11 TPFL 1</option>
            <option value="11 TPFL 2" {{ old('kelas_jurusan') == '11 TPFL 2' ? 'selected' : '' }}>11 TPFL 2</option>
            <option value="11 TPFL 3" {{ old('kelas_jurusan') == '11 TPFL 3' ? 'selected' : '' }}>11 TPFL 3</option>
            
            <option value="11 TO 1" {{ old('kelas_jurusan') == '11 TO 1' ? 'selected' : '' }}>11 TO 1</option>
            <option value="11 TO 2" {{ old('kelas_jurusan') == '11 TO 2' ? 'selected' : '' }}>11 TO 2</option>
            <option value="11 TO 3" {{ old('kelas_jurusan') == '11 TO 3' ? 'selected' : '' }}>11 TO 3</option>
        </optgroup>
        
        <!-- Kelas 12 -->
        <optgroup label="Kelas 12">
            <option value="12 PPLG 1" {{ old('kelas_jurusan') == '12 PPLG 1' ? 'selected' : '' }}>12 PPLG 1</option>
            <option value="12 PPLG 2" {{ old('kelas_jurusan') == '12 PPLG 2' ? 'selected' : '' }}>12 PPLG 2</option>
            <option value="12 PPLG 3" {{ old('kelas_jurusan') == '12 PPLG 3' ? 'selected' : '' }}>12 PPLG 3</option>
            
            <option value="12 ANM 1" {{ old('kelas_jurusan') == '12 ANM 1' ? 'selected' : '' }}>12 ANM 1</option>
            <option value="12 ANM 2" {{ old('kelas_jurusan') == '12 ANM 2' ? 'selected' : '' }}>12 ANM 2</option>
            <option value="12 ANM 3" {{ old('kelas_jurusan') == '12 ANM 3' ? 'selected' : '' }}>12 ANM 3</option>
            
            <option value="12 BC 1" {{ old('kelas_jurusan') == '12 BC 1' ? 'selected' : '' }}>12 BC 1</option>
            <option value="12 BC 2" {{ old('kelas_jurusan') == '12 BC 2' ? 'selected' : '' }}>12 BC 2</option>
            <option value="12 BC 3" {{ old('kelas_jurusan') == '12 BC 3' ? 'selected' : '' }}>12 BC 3</option>
            
            <option value="12 TPFL 1" {{ old('kelas_jurusan') == '12 TPFL 1' ? 'selected' : '' }}>12 TPFL 1</option>
            <option value="12 TPFL 2" {{ old('kelas_jurusan') == '12 TPFL 2' ? 'selected' : '' }}>12 TPFL 2</option>
            <option value="12 TPFL 3" {{ old('kelas_jurusan') == '12 TPFL 3' ? 'selected' : '' }}>12 TPFL 3</option>
            
            <option value="12 TO 1" {{ old('kelas_jurusan') == '12 TO 1' ? 'selected' : '' }}>12 TO 1</option>
            <option value="12 TO 2" {{ old('kelas_jurusan') == '12 TO 2' ? 'selected' : '' }}>12 TO 2</option>
            <option value="12 TO 3" {{ old('kelas_jurusan') == '12 TO 3' ? 'selected' : '' }}>12 TO 3</option>
        </optgroup>
    </select>
    @error('kelas_jurusan')
    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
    <p class="text-xs text-slate-500 mt-1">Format: [Tingkat] [Jurusan] [Nomor Kelas] - Contoh: 10 PPLG 1</p>
</div>

                <!-- EMAIL (OPSIONAL) -->
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">
                        Email (opsional)
                    </label>
                    <input type="email"
                           name="email"
                           value="{{ old('email') }}"
                           class="w-full px-4 py-2.5 border border-slate-300 rounded-lg
                                  focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                                  placeholder:text-slate-400"
                           placeholder="contoh@email.com (bisa dikosongkan)">
                    @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-slate-500 mt-1">Email tidak wajib, bisa dikosongkan</p>
                </div>

                <!-- BUTTON -->
                <div class="flex justify-end gap-3 pt-4">
                    <a href="{{ route('admin.datapeminjam.index') }}"
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
                    <i class="fa-solid fa-user-plus text-6xl text-indigo-500"></i>
                </div>
            </div>
            
            <!-- Informasi -->
            <div class="text-center">
                <h3 class="text-lg font-semibold text-slate-800 mb-2">Tambahkan Peminjam Baru</h3>
                <p class="text-sm text-slate-600">
                    Pastikan data yang dimasukkan akurat. Username akan digunakan untuk login ke sistem.
                </p>
                
                <!-- Tips Box -->
                <div class="mt-6 p-4 bg-indigo-50 rounded-lg border border-indigo-100">
                    <h4 class="text-sm font-medium text-indigo-800 mb-2 flex items-center gap-2">
                        <i class="fa-solid fa-lightbulb"></i> Tips
                    </h4>
                    <ul class="text-xs text-indigo-700 space-y-1 text-left">
                        <li>• Pilih jurusan dan kelas dengan benar</li>
                        <li>• Username harus unik dan mudah diingat</li>
                        <li>• Email opsional untuk notifikasi</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection