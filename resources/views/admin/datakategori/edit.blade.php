@extends('layouts-admin.admin')

@section('content')

<!-- HEADER BOX -->
<div class="bg-white rounded-xl shadow p-5 mb-6">
    <div class="flex items-center gap-4">
        <!-- BACK BUTTON -->
        <a href="{{ route('admin.datakategori.index') }}"
           class="w-10 h-10 flex items-center justify-center rounded-lg
                  bg-slate-100 text-slate-600 hover:bg-slate-200 transition">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-xl font-semibold text-slate-800">Edit Kategori</h1>
            <p class="text-sm text-slate-500">Perbarui data kategori</p>
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

<!-- ERROR MESSAGE -->
@if($errors->any())
<div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <i class="fa-solid fa-exclamation-circle text-red-500"></i>
            <p class="font-medium">Terjadi kesalahan: {{ $errors->first() }}</p>
        </div>
        <button type="button" onclick="this.parentElement.parentElement.remove()" class="text-red-500 hover:text-red-700">
            <i class="fa-solid fa-times"></i>
        </button>
    </div>
</div>
@endif

<!-- FORM CARD -->
<div class="flex flex-col lg:flex-row gap-6">
    <!-- FORM SECTION -->
    <div class="bg-white rounded-xl shadow p-6 flex-1">
      <form action="{{ route('admin.datakategori.update', $datakategori) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid gap-5">
                <!-- NAMA KATEGORI -->
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">
                        Nama Kategori <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="nama_kategori"
                           id="nama_kategori"
                           value="{{ old('nama_kategori', $datakategori->nama_kategori) }}"
                           required
                           class="w-full px-4 py-2.5 border border-slate-300 rounded-lg
                                  focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                                  placeholder:text-slate-400 @error('nama_kategori') border-red-500 @enderror"
                           placeholder="Masukkan nama kategori">
                    @error('nama_kategori')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- PREVIEW NAMA KATEGORI -->
                <div class="bg-indigo-50 p-4 rounded-lg border border-indigo-100">
                    <div class="flex items-center gap-2 text-indigo-700 mb-2">
                        <i class="fa-solid fa-eye"></i>
                        <span class="text-sm font-medium">Preview</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs bg-indigo-100 text-indigo-800 px-2 py-1 rounded">Slug:</span>
                        <span id="slugPreview" class="text-sm text-slate-600 italic">
                            {{ Str::slug($datakategori->nama_kategori) }}
                        </span>
                    </div>
                </div>

                <!-- INFO TAMBAHAN -->
                <div class="bg-slate-50 p-4 rounded-lg border border-slate-200">
                    <div class="flex items-center gap-2 text-slate-600 mb-3">
                        <i class="fa-solid fa-info-circle"></i>
                        <span class="text-sm font-medium">Informasi Kategori</span>
                    </div>
                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div>
                            <span class="text-slate-500">ID Kategori:</span>
                            <span class="ml-2 font-medium text-slate-700">{{ $datakategori->id }}</span>
                        </div>
                        <div>
                            <span class="text-slate-500">Dibuat:</span>
                            <span class="ml-2 font-medium text-slate-700">
                                {{ $datakategori->created_at ? $datakategori->created_at->format('d/m/Y H:i') : '-' }}
                            </span>
                        </div>
                        <div>
                            <span class="text-slate-500">Diperbarui:</span>
                            <span class="ml-2 font-medium text-slate-700">
                                {{ $datakategori->updated_at ? $datakategori->updated_at->format('d/m/Y H:i') : '-' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- BUTTON -->
                <div class="flex justify-end gap-3 pt-4 border-t border-slate-200">
                    <a href="{{ route('admin.datakategori.index') }}"
                       class="px-5 py-2.5 rounded-lg bg-slate-200 text-slate-700 hover:bg-slate-300
                              transition flex items-center gap-2">
                        <i class="fa-solid fa-times"></i>
                        Batal
                    </a>
                    <button type="submit"
                            class="px-5 py-2.5 rounded-lg bg-indigo-500 text-white hover:bg-indigo-600
                                   transition flex items-center gap-2">
                        <i class="fa-solid fa-save"></i>
                        Update Kategori
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- EDIT INFO SECTION -->
    <div class="hidden lg:flex w-full lg:w-1/3 xl:w-1/4 flex-col items-center justify-center">
        <div class="bg-white rounded-xl shadow p-8 w-full h-full flex flex-col items-center justify-center">
            <!-- Illustration -->
            <div class="mb-6">
                <div class="w-48 h-48 mx-auto bg-gradient-to-br from-yellow-100 to-amber-100 
                            rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-pen-to-square text-6xl text-amber-500"></i>
                </div>
            </div>
            
            <!-- Informasi Edit -->
            <div class="text-center">
                <h3 class="text-lg font-semibold text-slate-800 mb-2">Edit Kategori</h3>
                <p class="text-sm text-slate-500 mb-4">
                    Anda sedang mengedit kategori 
                    <span class="font-semibold text-indigo-600">"{{ $datakategori->nama_kategori }}"</span>
                </p>
                
                <!-- Tips Box -->
                <div class="mt-6 p-4 bg-yellow-50 rounded-lg border border-yellow-100">
                    <h4 class="text-sm font-medium text-yellow-800 mb-2 flex items-center gap-2">
                        <i class="fa-solid fa-lightbulb"></i> Tips Edit
                    </h4>
                    <ul class="text-xs text-yellow-700 space-y-1 text-left">
                        <li>• Periksa kembali nama kategori sebelum menyimpan</li>
                        <li>• Pastikan tidak ada duplikasi dengan kategori lain</li>
                        <li>• Perubahan akan langsung terlihat di sistem</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Live slug preview
    document.getElementById('nama_kategori').addEventListener('keyup', function() {
        let slug = this.value
            .toLowerCase()
            .replace(/[^\w\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim();
        
        document.getElementById('slugPreview').textContent = slug || '-';
    });
</script>
@endpush

@endsection