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
                <h1 class="text-xl font-semibold text-slate-800">Edit Alat</h1>
                <p class="text-sm text-slate-500">Perbarui data alat</p>
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
            <button type="button" onclick="this.parentElement.parentElement.remove()" 
                    class="text-green-500 hover:text-green-700">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
    </div>
    @endif

    <!-- FORM -->
    <div class="bg-white rounded-xl shadow p-6">
      <form action="{{ route('admin.dataalat.update', $dataalat->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Foto Alat -->
<div class="md:col-span-2">
    <label class="block text-sm font-medium text-slate-600 mb-1">
        Foto Alat <span class="text-gray-400">(Opsional)</span>
    </label>
    <div class="flex items-center gap-6">
        <!-- Current Foto Preview -->
       <div id="previewContainer"
     class="w-32 h-32 border-2 border-dashed border-slate-300 rounded-lg overflow-hidden bg-gray-50">

            @if($dataalat->has_foto)
            <img id="fotoPreview" src="{{ $dataalat->foto_url }}" 
                 alt="{{ $dataalat->nama_alat }}"
                 class="w-full h-full object-cover">
            @else
            <div id="noFotoPreview" class="w-full h-full flex flex-col items-center justify-center p-2">
                <i class="fa-solid fa-image text-gray-300 text-3xl mb-2"></i>
                <span class="text-gray-400 text-xs text-center">Belum ada foto</span>
            </div>
            @endif
        </div>
        
        <!-- Upload Controls -->
        <div class="flex-1 space-y-4">
            <!-- File Input & Buttons -->
            <div class="flex flex-wrap gap-3">
                <!-- File Input (Hidden) -->
                <input type="file" 
                       name="foto" 
                       id="fotoInput"
                       accept="image/*"
                       class="hidden"
                       onchange="handleFileSelect(this)">
                
                <!-- Upload/Replace Button -->
                <button type="button" 
                        onclick="document.getElementById('fotoInput').click()"
                        class="px-4 py-2.5 bg-blue-500 text-white rounded-lg 
                               hover:bg-blue-600 transition flex items-center gap-2">
                    <i class="fa-solid fa-upload"></i>
                    <span id="uploadBtnText">
                        {{ $dataalat->has_foto ? 'Ganti Foto' : 'Pilih Foto' }}
                    </span>
                </button>
                
                <!-- Remove Button (only show if has foto) -->
                @if($dataalat->has_foto)
                <button type="button"
                        onclick="confirmRemoveFoto()"
                        class="px-4 py-2.5 bg-red-500 text-white rounded-lg 
                               hover:bg-red-600 transition flex items-center gap-2">
                    <i class="fa-solid fa-trash"></i>
                    Hapus Foto
                </button>
                
                <!-- Hidden checkbox for delete -->
                <input type="checkbox" 
                       name="hapus_foto" 
                       id="hapus_foto"
                       class="hidden" 
                       value="1">
                @endif
            </div>
            
            <!-- Info Section -->
            <div class="bg-blue-50 border border-blue-100 rounded-lg p-3">
                <div class="flex items-start gap-2">
                    <i class="fa-solid fa-info-circle text-blue-500 mt-0.5"></i>
                    <div class="text-sm text-blue-700">
                        <p class="font-medium">Informasi:</p>
                        <ul class="mt-1 space-y-1">
                            <li>• Foto bersifat opsional</li>
                            <li>• Format: JPG, PNG, GIF</li>
                            <li>• Maksimal ukuran: 2MB</li>
                            @if($dataalat->has_foto)
                            <li>• Foto saat ini: <span class="font-mono text-xs">{{ $dataalat->nama_foto }}</span></li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
            
            @error('foto')
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-exclamation-circle"></i>
                    <p class="text-sm">{{ $message }}</p>
                </div>
            </div>
            @enderror
        </div>
    </div>
</div>

                <!-- Nama Alat -->
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">
                        Nama Alat <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama_alat" value="{{ old('nama_alat', $dataalat->nama_alat) }}" required
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
                        <option value="{{ $kat->id }}" 
                            {{ old('kategori_id', $dataalat->kategori_id) == $kat->id ? 'selected' : '' }}>
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
                        <option value="baik" {{ old('kondisi', $dataalat->kondisi) == 'baik' ? 'selected' : '' }}>Baik</option>
                        <option value="rusak_ringan" {{ old('kondisi', $dataalat->kondisi) == 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                        <option value="rusak_berat" {{ old('kondisi', $dataalat->kondisi) == 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                        <option value="perlu_perbaikan" {{ old('kondisi', $dataalat->kondisi) == 'perlu_perbaikan' ? 'selected' : '' }}>Perlu Perbaikan</option>
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
                    <input type="number" name="stok" value="{{ old('stok', $dataalat->stok) }}" required min="0"
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
                    <input type="text" name="lokasi" value="{{ old('lokasi', $dataalat->lokasi) }}"
                           class="w-full px-4 py-2.5 border border-slate-300 rounded-lg
                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Contoh: Rak A1, Gudang Utara">
                    @error('lokasi')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>


            </div>

            <!-- BUTTONS -->
            <div class="flex justify-between gap-3 pt-6 mt-6 border-t border-slate-200">
                <!-- Delete Button -->
               

                <!-- Cancel & Save Buttons -->
                <div class="flex gap-3">
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
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(input) {
    const preview = document.getElementById('fotoPreview');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection

@section('scripts')
<script>
// Handle file selection
function handleFileSelect(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const reader = new FileReader();
        
        // Validasi ukuran file (2MB = 2 * 1024 * 1024 bytes)
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file terlalu besar. Maksimal 2MB.');
            input.value = '';
            return;
        }
        
        // Validasi tipe file
        const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
        if (!validTypes.includes(file.type)) {
            alert('Format file tidak didukung. Harus JPG, PNG, atau GIF.');
            input.value = '';
            return;
        }
        
        reader.onload = function(e) {
            // Update preview
            const previewContainer = document.querySelector('.w-32.h-32');
            
            // Hapus preview lama jika ada
            const oldPreview = document.getElementById('fotoPreview');
            const noFotoPreview = document.getElementById('noFotoPreview');
            
            if (oldPreview) {
                oldPreview.src = e.target.result;
            } else if (noFotoPreview) {
                // Hapus "no foto" preview
                noFotoPreview.remove();
                
                // Buat elemen img baru
                const img = document.createElement('img');
                img.id = 'fotoPreview';
                img.src = e.target.result;
                img.alt = 'Preview';
                img.className = 'w-full h-full object-cover';
                previewContainer.appendChild(img);
            } else {
                // Buat elemen img baru
                const img = document.createElement('img');
                img.id = 'fotoPreview';
                img.src = e.target.result;
                img.alt = 'Preview';
                img.className = 'w-full h-full object-cover';
                previewContainer.innerHTML = '';
                previewContainer.appendChild(img);
            }
            
            // Update tombol upload text
            document.getElementById('uploadBtnText').textContent = 'Ganti Foto';
            
            // Tampilkan tombol hapus jika belum ada
            if (!document.querySelector('[onclick="confirmRemoveFoto()"]')) {
                showRemoveButton();
            }
            
            // Uncheck hapus_foto jika ada
            const hapusFotoCheckbox = document.getElementById('hapus_foto');
            if (hapusFotoCheckbox) {
                hapusFotoCheckbox.checked = false;
            }
        }
        
        reader.readAsDataURL(file);
    }
}

// Show remove button
function showRemoveButton() {
    const buttonContainer = document.querySelector('.flex-wrap');
    
    const removeBtnHtml = `
        <button type="button"
                onclick="confirmRemoveFoto()"
                class="px-4 py-2.5 bg-red-500 text-white rounded-lg 
                       hover:bg-red-600 transition flex items-center gap-2">
            <i class="fa-solid fa-trash"></i>
            Hapus Foto
        </button>
        <input type="checkbox" 
               name="hapus_foto" 
               id="hapus_foto"
               class="hidden" 
               value="1">
    `;
    
    buttonContainer.insertAdjacentHTML('beforeend', removeBtnHtml);
}

// Confirm remove foto
function confirmRemoveFoto() {
    Swal.fire({
        title: 'Hapus Foto?',
        text: "Foto akan dihapus dari alat ini.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            removeFoto();
        }
    });
}

// Remove foto
function removeFoto() {
    const previewContainer = document.querySelector('.w-32.h-32');
    
    // Reset preview ke "no foto"
    previewContainer.innerHTML = `
        <div id="noFotoPreview" class="w-full h-full flex flex-col items-center justify-center p-2">
            <i class="fa-solid fa-image text-gray-300 text-3xl mb-2"></i>
            <span class="text-gray-400 text-xs text-center">Belum ada foto</span>
        </div>
    `;
    
    // Reset file input
    document.getElementById('fotoInput').value = '';
    
    // Check hapus_foto checkbox
    const hapusFotoCheckbox = document.getElementById('hapus_foto');
    if (hapusFotoCheckbox) {
        hapusFotoCheckbox.checked = true;
    } else {
        // Buat checkbox jika belum ada
        const buttonContainer = document.querySelector('.flex-wrap');
        buttonContainer.insertAdjacentHTML('beforeend', 
            '<input type="checkbox" name="hapus_foto" id="hapus_foto" class="hidden" value="1" checked>'
        );
    }
    
    // Update tombol upload text
    document.getElementById('uploadBtnText').textContent = 'Pilih Foto';
    
    // Hapus tombol hapus
    const removeBtn = document.querySelector('[onclick="confirmRemoveFoto()"]');
    if (removeBtn) {
        removeBtn.remove();
    }
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    console.log('Foto status:', {
        hasFoto: {{ $dataalat->has_foto ? 'true' : 'false' }},
        fotoUrl: '{{ $dataalat->foto_url }}',
        fotoPath: '{{ $dataalat->foto }}'
    });
});
</script>
@endsection