@extends('layouts-admin.admin')

@section('content')
<div class="container-fluid">
    <!-- HEADER -->
    <div class="bg-white rounded-xl shadow p-5 mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.dataalat.index') }}"
               class="w-10 h-10 flex items-center justify-center rounded-lg
                      bg-slate-100 text-slate-600 hover:bg-slate-200 transition">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-xl font-semibold text-slate-800">Tambah Alat Baru</h1>
                <p class="text-sm text-slate-500">Isi data alat baru</p>
            </div>
        </div>
    </div>

    <!-- FORM -->
    <div class="bg-white rounded-xl shadow p-6">
        <form action="{{ route('admin.dataalat.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Foto Alat -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-600 mb-1">
                        Foto Alat (Opsional)
                    </label>
                    <div class="flex items-center gap-6">
                        <!-- Preview -->
                        <div class="w-32 h-32 border-2 border-dashed border-slate-300 rounded-lg 
                                    flex items-center justify-center bg-slate-50">
                            <img id="fotoPreview" src="{{ asset('assets-admin/images/default-alat.png') }}" 
                                 alt="Preview Foto" 
                                 class="w-full h-full object-cover rounded-lg hidden">
                            <div id="fotoPlaceholder" class="text-center p-4">
                                <i class="fa-solid fa-camera text-3xl text-slate-400 mb-2"></i>
                                <p class="text-xs text-slate-500">Belum ada foto</p>
                            </div>
                        </div>
                        
                        <!-- Upload Controls -->
                        <div class="flex-1">
                            <input type="file" 
                                   name="foto" 
                                   id="fotoInput"
                                   accept="image/*"
                                   class="hidden"
                                   onchange="previewImage(this)">
                            <button type="button" 
                                    onclick="document.getElementById('fotoInput').click()"
                                    class="px-4 py-2.5 bg-blue-500 text-white rounded-lg 
                                           hover:bg-blue-600 transition flex items-center gap-2 mb-2">
                                <i class="fa-solid fa-upload"></i>
                                Pilih Foto
                            </button>
                            <p class="text-xs text-slate-500">
                                Format: JPG, PNG, GIF (Max: 2MB)
                            </p>
                            @error('foto')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Nama Alat -->
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">
                        Nama Alat <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama_alat" value="{{ old('nama_alat') }}" required
                           class="w-full px-4 py-2.5 border border-slate-300 rounded-lg
                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Masukkan nama alat">
                    @error('nama_alat')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Kategori -->
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">
                        Kategori <span class="text-red-500">*</span>
                    </label>
                    <select name="kategori_id" required
                            class="w-full px-4 py-2.5 border border-slate-300 rounded-lg
                                   focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Pilih Kategori</option>
                        @foreach($kategori as $kat)
                        <option value="{{ $kat->id }}" {{ old('kategori_id') == $kat->id ? 'selected' : '' }}>
                            {{ $kat->nama_kategori }}
                        </option>
                        @endforeach
                    </select>
                    @error('kategori_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kondisi -->
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">
                        Kondisi <span class="text-red-500">*</span>
                    </label>
                    <select name="kondisi" required
                            class="w-full px-4 py-2.5 border border-slate-300 rounded-lg
                                   focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="baik" {{ old('kondisi') == 'baik' ? 'selected' : '' }}>Baik</option>
                        <option value="rusak_ringan" {{ old('kondisi') == 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                        <option value="rusak_berat" {{ old('kondisi') == 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                        <option value="perlu_perbaikan" {{ old('kondisi') == 'perlu_perbaikan' ? 'selected' : '' }}>Perlu Perbaikan</option>
                    </select>
                    @error('kondisi')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Stok -->
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">
                        Stok <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="stok" value="{{ old('stok', 0) }}" required min="0"
                           class="w-full px-4 py-2.5 border border-slate-300 rounded-lg
                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="0">
                    @error('stok')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Lokasi -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-600 mb-1">
                        Lokasi Penyimpanan
                    </label>
                    <input type="text" name="lokasi" value="{{ old('lokasi') }}"
                           class="w-full px-4 py-2.5 border border-slate-300 rounded-lg
                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Contoh: Rak A1, Gudang Utara">
                    @error('lokasi')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- BUTTONS -->
            <div class="flex justify-end gap-3 pt-6 mt-6 border-t border-slate-200">
                <a href="{{ route('admin.dataalat.index') }}"
                   class="px-5 py-2.5 rounded-lg bg-slate-200 text-slate-700 hover:bg-slate-300
                          transition flex items-center gap-2">
                    <i class="fa-solid fa-times"></i>
                    Batal
                </a>
                <button type="submit"
                        class="px-5 py-2.5 rounded-lg bg-blue-500 text-white hover:bg-blue-600
                               transition flex items-center gap-2">
                    <i class="fa-solid fa-save"></i>
                    Simpan Alat
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(input) {
    const preview = document.getElementById('fotoPreview');
    const placeholder = document.getElementById('fotoPlaceholder');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            placeholder.classList.add('hidden');
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection