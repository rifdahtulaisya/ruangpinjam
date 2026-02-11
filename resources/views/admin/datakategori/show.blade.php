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
            <h1 class="text-xl font-semibold text-slate-800">Detail Kategori</h1>
            <p class="text-sm text-slate-500">Informasi lengkap kategori</p>
        </div>
    </div>
</div>

<!-- DETAIL CARD -->
<div class="flex flex-col lg:flex-row gap-6">
    <!-- MAIN DETAIL SECTION -->
    <div class="bg-white rounded-xl shadow p-6 flex-1">
        <div class="grid gap-6">
            <!-- HEADER DETAIL -->
            <div class="flex items-center justify-between pb-4 border-b border-slate-200">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-tag text-xl text-indigo-600"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-slate-800">{{ $datakategori->nama_kategori }}</h2>
                        <p class="text-sm text-slate-500">ID Kategori: #{{ $datakategori->id }}</p>
                    </div>
                </div>
                <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-medium rounded-full">
                    Aktif
                </span>
            </div>
            
            <!-- DETAIL INFORMATION -->
            <div class="grid md:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-4">
                    <div class="bg-slate-50 p-4 rounded-lg">
                        <h3 class="text-sm font-semibold text-slate-700 mb-3 flex items-center gap-2">
                            <i class="fa-solid fa-circle-info text-indigo-500"></i>
                            Informasi Dasar
                        </h3>
                        <div class="space-y-2">
                            <div class="flex justify-between items-center py-2 border-b border-slate-200">
                                <span class="text-sm text-slate-600">Nama Kategori</span>
                                <span class="text-sm font-medium text-slate-800">{{ $datakategori->nama_kategori }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-slate-200">
                                <span class="text-sm text-slate-600">Slug</span>
                                <span class="text-sm font-medium text-slate-800">
                                    {{ Str::slug($datakategori->nama_kategori) }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="text-sm text-slate-600">Status</span>
                                <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full">
                                    Aktif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Right Column -->
                <div class="space-y-4">
                    <div class="bg-slate-50 p-4 rounded-lg">
                        <h3 class="text-sm font-semibold text-slate-700 mb-3 flex items-center gap-2">
                            <i class="fa-regular fa-calendar text-indigo-500"></i>
                            Informasi Waktu
                        </h3>
                        <div class="space-y-2">
                            <div class="flex justify-between items-center py-2 border-b border-slate-200">
                                <span class="text-sm text-slate-600">Dibuat Pada</span>
                                <span class="text-sm font-medium text-slate-800">
                                    {{ $datakategori->created_at ? $datakategori->created_at->format('d F Y H:i') : '-' }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-slate-200">
                                <span class="text-sm text-slate-600">Diperbarui</span>
                                <span class="text-sm font-medium text-slate-800">
                                    {{ $datakategori->updated_at ? $datakategori->updated_at->format('d F Y H:i') : '-' }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="text-sm text-slate-600">Umur Kategori</span>
                                <span class="text-sm font-medium text-slate-800">
                                    {{ $datakategori->created_at ? $datakategori->created_at->diffForHumans() : '-' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
        </div>
    </div>

    <!-- SIDEBAR INFO SECTION -->
    <div class="hidden lg:flex w-full lg:w-1/3 xl:w-1/4 flex-col gap-6">
        <!-- Category Info Card -->
        <div class="bg-white rounded-xl shadow p-6">
            <div class="flex flex-col items-center text-center">
                <div class="w-24 h-24 bg-gradient-to-br from-blue-100 to-cyan-100 rounded-full 
                            flex items-center justify-center mb-4">
                    <i class="fa-solid fa-tags text-4xl text-blue-500"></i>
                </div>
                <h3 class="font-semibold text-slate-800">{{ $datakategori->nama_kategori }}</h3>
                <p class="text-xs text-slate-500 mt-1">Kategori Produk</p>
            </div>
            
            <div class="mt-6 pt-6 border-t border-slate-200">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm text-slate-600">Total Penggunaan</span>
                    <span class="text-sm font-semibold text-slate-800">0 Items</span>
                </div>
                <div class="w-full bg-slate-200 rounded-full h-2">
                    <div class="bg-indigo-600 h-2 rounded-full" style="width: 0%"></div>
                </div>
            </div>
        </div>
        
    </div>
</div>

<!-- DELETE CONFIRMATION MODAL -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-lg max-w-md w-full mx-4 p-6">
        <div class="flex items-center gap-4 text-red-600 mb-4">
            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                <i class="fa-solid fa-exclamation-triangle text-2xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-slate-800">Hapus Kategori</h3>
                <p class="text-sm text-slate-500">Tindakan ini tidak dapat dibatalkan</p>
            </div>
        </div>
        
        <p class="text-slate-600 mb-6">
            Apakah Anda yakin ingin menghapus kategori <span id="kategoriName" class="font-semibold"></span>?
        </p>
        
        <div class="flex justify-end gap-3">
            <button onclick="closeDeleteModal()" 
                    class="px-4 py-2 rounded-lg bg-slate-200 text-slate-700 hover:bg-slate-300 transition">
                Batal
            </button>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="px-4 py-2 rounded-lg bg-red-500 text-white hover:bg-red-600 transition">
                    Ya, Hapus
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmDelete(id) {
    const modal = document.getElementById('deleteModal');
    const deleteForm = document.getElementById('deleteForm');
    const kategoriName = "{{ $datakategori->nama_kategori }}";
    
    document.getElementById('kategoriName').textContent = kategoriName;
    
    // ✅ CARA BENAR: Generate route dengan parameter lengkap
    deleteForm.action = "{{ route('admin.datakategori.destroy', ':id') }}".replace(':id', id);
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('deleteModal');
    if (event.target == modal) {
        closeDeleteModal();
    }
}
</script>
@endpush

@endsection