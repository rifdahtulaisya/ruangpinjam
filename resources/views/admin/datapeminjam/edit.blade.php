@extends('layouts-admin.admin')

@section('title', 'Edit Kelas Peminjam')

@section('content')

    <!-- BREADCRUMB -->
    <div class="mb-6">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin.dashboard') }}"
                        class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-indigo-600">
                        <i class="fa-solid fa-home mr-2"></i>
                        Dashboard
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fa-solid fa-chevron-right text-slate-400 text-xs"></i>
                        <a href="{{ route('admin.datapeminjam.index') }}"
                            class="ml-1 text-sm font-medium text-slate-700 hover:text-indigo-600 md:ml-2">
                            Data Peminjam
                        </a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fa-solid fa-chevron-right text-slate-400 text-xs"></i>
                        <span class="ml-1 text-sm font-medium text-slate-500 md:ml-2">Edit Kelas</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <!-- MAIN CARD -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- HEADER -->
        <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 px-6 py-8">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                    <i class="fa-solid fa-user-edit text-2xl text-white"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-white mb-1">Edit Kelas Peminjam</h1>
                    <p class="text-indigo-100 text-sm">Ubah kelas untuk peminjam {{ $user->name }}</p>
                </div>
            </div>
        </div>

        <!-- FORM CONTENT -->
        <div class="p-6">
            <!-- INFO PEMINJAM (READ ONLY) -->
            <div class="bg-slate-50 rounded-xl p-6 mb-6">
                <h3 class="text-lg font-semibold text-slate-700 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-info-circle text-indigo-500"></i>
                    Informasi Peminjam
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama -->
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-2">
                            <i class="fa-solid fa-user text-slate-400 mr-2"></i>
                            Nama Lengkap
                        </label>
                        <div class="bg-white border border-slate-200 rounded-lg px-4 py-3 text-slate-700">
                            {{ $user->name }}
                        </div>
                    </div>

                    <!-- Username -->
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-2">
                            <i class="fa-solid fa-at text-slate-400 mr-2"></i>
                            Username
                        </label>
                        <div class="bg-white border border-slate-200 rounded-lg px-4 py-3 text-slate-700">
                            {{ $user->username }}
                        </div>
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-2">
                            <i class="fa-solid fa-envelope text-slate-400 mr-2"></i>
                            Email
                        </label>
                        <div class="bg-white border border-slate-200 rounded-lg px-4 py-3 text-slate-700">
                            {{ $user->email ?? 'Tidak ada email' }}
                        </div>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-2">
                            <i class="fa-solid fa-circle-check text-slate-400 mr-2"></i>
                            Status
                        </label>
                        <div class="bg-white border border-slate-200 rounded-lg px-4 py-3">
                            <span
                                class="px-3 py-1 rounded-full text-xs font-medium
                            @if ($user->status == 'active') bg-green-100 text-green-600
                            @else
                                bg-red-100 text-red-600 @endif">
                                {{ ucfirst($user->status ?? 'active') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FORM EDIT KELAS -->
<form action="{{ route('admin.datapeminjam.update', $user->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="bg-white border-2 border-indigo-100 rounded-xl p-6">
        <h3 class="text-lg font-semibold text-slate-700 mb-4 flex items-center gap-2">
            <i class="fa-solid fa-school text-indigo-500"></i>
            Edit Kelas/Jurusan
        </h3>

        <div class="mb-6">
            <label for="kelas_jurusan" class="block text-sm font-medium text-slate-700 mb-2">
                Kelas/Jurusan <span class="text-red-500">*</span>
            </label>
            <select name="kelas_jurusan" 
                    id="kelas_jurusan" 
                    required
                    class="w-full px-4 py-3 border border-slate-300 rounded-lg 
                           focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                           @error('kelas_jurusan') border-red-500 @enderror">
                <option value="">-- Pilih Kelas/Jurusan --</option>
                
                <!-- Kelas 10 -->
                <optgroup label="Kelas 10">
                    <option value="10 PPLG 1" {{ old('kelas_jurusan', $user->kelas_jurusan) == '10 PPLG 1' ? 'selected' : '' }}>10 PPLG 1</option>
                    <option value="10 PPLG 2" {{ old('kelas_jurusan', $user->kelas_jurusan) == '10 PPLG 2' ? 'selected' : '' }}>10 PPLG 2</option>
                    <option value="10 PPLG 3" {{ old('kelas_jurusan', $user->kelas_jurusan) == '10 PPLG 3' ? 'selected' : '' }}>10 PPLG 3</option>
                    
                    <option value="10 ANM 1" {{ old('kelas_jurusan', $user->kelas_jurusan) == '10 ANM 1' ? 'selected' : '' }}>10 ANM 1</option>
                    <option value="10 ANM 2" {{ old('kelas_jurusan', $user->kelas_jurusan) == '10 ANM 2' ? 'selected' : '' }}>10 ANM 2</option>
                    <option value="10 ANM 3" {{ old('kelas_jurusan', $user->kelas_jurusan) == '10 ANM 3' ? 'selected' : '' }}>10 ANM 3</option>
                    
                    <option value="10 BC 1" {{ old('kelas_jurusan', $user->kelas_jurusan) == '10 BC 1' ? 'selected' : '' }}>10 BC 1</option>
                    <option value="10 BC 2" {{ old('kelas_jurusan', $user->kelas_jurusan) == '10 BC 2' ? 'selected' : '' }}>10 BC 2</option>
                    <option value="10 BC 3" {{ old('kelas_jurusan', $user->kelas_jurusan) == '10 BC 3' ? 'selected' : '' }}>10 BC 3</option>
                    
                    <option value="10 TPFL 1" {{ old('kelas_jurusan', $user->kelas_jurusan) == '10 TPFL 1' ? 'selected' : '' }}>10 TPFL 1</option>
                    <option value="10 TPFL 2" {{ old('kelas_jurusan', $user->kelas_jurusan) == '10 TPFL 2' ? 'selected' : '' }}>10 TPFL 2</option>
                    <option value="10 TPFL 3" {{ old('kelas_jurusan', $user->kelas_jurusan) == '10 TPFL 3' ? 'selected' : '' }}>10 TPFL 3</option>
                    
                    <option value="10 TO 1" {{ old('kelas_jurusan', $user->kelas_jurusan) == '10 TO 1' ? 'selected' : '' }}>10 TO 1</option>
                    <option value="10 TO 2" {{ old('kelas_jurusan', $user->kelas_jurusan) == '10 TO 2' ? 'selected' : '' }}>10 TO 2</option>
                    <option value="10 TO 3" {{ old('kelas_jurusan', $user->kelas_jurusan) == '10 TO 3' ? 'selected' : '' }}>10 TO 3</option>
                </optgroup>
                
                <!-- Kelas 11 -->
                <optgroup label="Kelas 11">
                    <option value="11 PPLG 1" {{ old('kelas_jurusan', $user->kelas_jurusan) == '11 PPLG 1' ? 'selected' : '' }}>11 PPLG 1</option>
                    <option value="11 PPLG 2" {{ old('kelas_jurusan', $user->kelas_jurusan) == '11 PPLG 2' ? 'selected' : '' }}>11 PPLG 2</option>
                    <option value="11 PPLG 3" {{ old('kelas_jurusan', $user->kelas_jurusan) == '11 PPLG 3' ? 'selected' : '' }}>11 PPLG 3</option>
                    
                    <option value="11 ANM 1" {{ old('kelas_jurusan', $user->kelas_jurusan) == '11 ANM 1' ? 'selected' : '' }}>11 ANM 1</option>
                    <option value="11 ANM 2" {{ old('kelas_jurusan', $user->kelas_jurusan) == '11 ANM 2' ? 'selected' : '' }}>11 ANM 2</option>
                    <option value="11 ANM 3" {{ old('kelas_jurusan', $user->kelas_jurusan) == '11 ANM 3' ? 'selected' : '' }}>11 ANM 3</option>
                    
                    <option value="11 BC 1" {{ old('kelas_jurusan', $user->kelas_jurusan) == '11 BC 1' ? 'selected' : '' }}>11 BC 1</option>
                    <option value="11 BC 2" {{ old('kelas_jurusan', $user->kelas_jurusan) == '11 BC 2' ? 'selected' : '' }}>11 BC 2</option>
                    <option value="11 BC 3" {{ old('kelas_jurusan', $user->kelas_jurusan) == '11 BC 3' ? 'selected' : '' }}>11 BC 3</option>
                    
                    <option value="11 TPFL 1" {{ old('kelas_jurusan', $user->kelas_jurusan) == '11 TPFL 1' ? 'selected' : '' }}>11 TPFL 1</option>
                    <option value="11 TPFL 2" {{ old('kelas_jurusan', $user->kelas_jurusan) == '11 TPFL 2' ? 'selected' : '' }}>11 TPFL 2</option>
                    <option value="11 TPFL 3" {{ old('kelas_jurusan', $user->kelas_jurusan) == '11 TPFL 3' ? 'selected' : '' }}>11 TPFL 3</option>
                    
                    <option value="11 TO 1" {{ old('kelas_jurusan', $user->kelas_jurusan) == '11 TO 1' ? 'selected' : '' }}>11 TO 1</option>
                    <option value="11 TO 2" {{ old('kelas_jurusan', $user->kelas_jurusan) == '11 TO 2' ? 'selected' : '' }}>11 TO 2</option>
                    <option value="11 TO 3" {{ old('kelas_jurusan', $user->kelas_jurusan) == '11 TO 3' ? 'selected' : '' }}>11 TO 3</option>
                </optgroup>
                
                <!-- Kelas 12 -->
                <optgroup label="Kelas 12">
                    <option value="12 PPLG 1" {{ old('kelas_jurusan', $user->kelas_jurusan) == '12 PPLG 1' ? 'selected' : '' }}>12 PPLG 1</option>
                    <option value="12 PPLG 2" {{ old('kelas_jurusan', $user->kelas_jurusan) == '12 PPLG 2' ? 'selected' : '' }}>12 PPLG 2</option>
                    <option value="12 PPLG 3" {{ old('kelas_jurusan', $user->kelas_jurusan) == '12 PPLG 3' ? 'selected' : '' }}>12 PPLG 3</option>
                    
                    <option value="12 ANM 1" {{ old('kelas_jurusan', $user->kelas_jurusan) == '12 ANM 1' ? 'selected' : '' }}>12 ANM 1</option>
                    <option value="12 ANM 2" {{ old('kelas_jurusan', $user->kelas_jurusan) == '12 ANM 2' ? 'selected' : '' }}>12 ANM 2</option>
                    <option value="12 ANM 3" {{ old('kelas_jurusan', $user->kelas_jurusan) == '12 ANM 3' ? 'selected' : '' }}>12 ANM 3</option>
                    
                    <option value="12 BC 1" {{ old('kelas_jurusan', $user->kelas_jurusan) == '12 BC 1' ? 'selected' : '' }}>12 BC 1</option>
                    <option value="12 BC 2" {{ old('kelas_jurusan', $user->kelas_jurusan) == '12 BC 2' ? 'selected' : '' }}>12 BC 2</option>
                    <option value="12 BC 3" {{ old('kelas_jurusan', $user->kelas_jurusan) == '12 BC 3' ? 'selected' : '' }}>12 BC 3</option>
                    
                    <option value="12 TPFL 1" {{ old('kelas_jurusan', $user->kelas_jurusan) == '12 TPFL 1' ? 'selected' : '' }}>12 TPFL 1</option>
                    <option value="12 TPFL 2" {{ old('kelas_jurusan', $user->kelas_jurusan) == '12 TPFL 2' ? 'selected' : '' }}>12 TPFL 2</option>
                    <option value="12 TPFL 3" {{ old('kelas_jurusan', $user->kelas_jurusan) == '12 TPFL 3' ? 'selected' : '' }}>12 TPFL 3</option>
                    
                    <option value="12 TO 1" {{ old('kelas_jurusan', $user->kelas_jurusan) == '12 TO 1' ? 'selected' : '' }}>12 TO 1</option>
                    <option value="12 TO 2" {{ old('kelas_jurusan', $user->kelas_jurusan) == '12 TO 2' ? 'selected' : '' }}>12 TO 2</option>
                    <option value="12 TO 3" {{ old('kelas_jurusan', $user->kelas_jurusan) == '12 TO 3' ? 'selected' : '' }}>12 TO 3</option>
                </optgroup>
            </select>
            
            @error('kelas_jurusan')
            <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                <i class="fa-solid fa-circle-exclamation"></i>
                {{ $message }}
            </p>
            @enderror

            <p class="mt-2 text-sm text-slate-500 flex items-center gap-1">
                <i class="fa-solid fa-info-circle text-indigo-500"></i>
                Format: [Tingkat] [Jurusan] [Nomor Kelas] - Contoh: 12 PPLG 1
            </p>
        </div>

        <!-- Current Kelas Info -->
        <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-6">
            <div class="flex items-start gap-3">
                <i class="fa-solid fa-lightbulb text-amber-500 text-lg mt-0.5"></i>
                <div>
                    <p class="text-sm font-medium text-amber-800 mb-1">Kelas/Jurusan Saat Ini</p>
                    <p class="text-sm text-amber-700">
                        @if($user->kelas_jurusan)
                            <span class="font-semibold">{{ $user->kelas_jurusan }}</span>
                        @else
                            <span class="italic">Belum ada kelas/jurusan yang dipilih</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Submit Button -->
        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.datapeminjam.index') }}" 
               class="px-5 py-2.5 rounded-lg bg-slate-200 text-slate-700 hover:bg-slate-300 transition">
                Batal
            </a>
            <button type="submit" 
                    class="px-5 py-2.5 rounded-lg bg-indigo-500 text-white hover:bg-indigo-600 transition">
                Simpan Perubahan
            </button>
        </div>
    </div>
</form>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-select highlight on focus
            const selectKelas = document.getElementById('kelas_jurusan');
            if (selectKelas) {
                selectKelas.addEventListener('focus', function() {
                    this.classList.add('ring-2', 'ring-indigo-500');
                });

                selectKelas.addEventListener('blur', function() {
                    this.classList.remove('ring-2', 'ring-indigo-500');
                });
            }

            // Form validation
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const kelas = document.getElementById('kelas_jurusan').value;

                    if (!kelas) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'warning',
                            title: 'Peringatan',
                            text: 'Silakan pilih kelas/jurusan terlebih dahulu!',
                            confirmButtonColor: '#6366f1'
                        });
                        return false;
                    }
                });
            }
        });

        // Show success message if exists
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false
            });
        @endif

        // Show error message if exists
        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session('error') }}',
                confirmButtonColor: '#dc2626'
            });
        @endif
    </script>

    <style>
        /* Custom select arrow */
        select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.75rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }

        /* Focus styles */
        select:focus {
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        /* Smooth transitions */
        * {
            transition: all 0.2s ease;
        }
    </style>
@endsection
