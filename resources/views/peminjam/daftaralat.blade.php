@extends('layouts-peminjam.peminjam')

@section('title', 'DAFTAR ALAT')

@section('content')
<div class="space-y-8 animate-slide-in">

    <!-- Filter Section -->
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Search Input -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Cari Alat</label>
                <div class="relative">
                    <input type="text" id="searchInput" value="{{ request('search') }}"
                        class="w-full pl-10 pr-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                        placeholder="Nama alat, lokasi, atau kode...">
                    <i class="fas fa-search absolute left-3 top-3 text-slate-400"></i>
                </div>
            </div>

            <!-- Kategori Filter -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Kategori</label>
                <select id="kategoriFilter" class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    <option value="">Semua Kategori</option>
                    @foreach($kategori as $kat)
                        <option value="{{ $kat->id }}" {{ request('kategori_id') == $kat->id ? 'selected' : '' }}>
                            {{ $kat->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Reset Button -->
            <div class="flex items-end">
                <button onclick="resetFilters()" class="w-full px-4 py-2.5 border border-slate-300 rounded-xl hover:bg-slate-50 transition flex items-center justify-center gap-2">
                    <i class="fas fa-redo"></i> Reset Filter
                </button>
            </div>
        </div>
    </div>

    <!-- Alat Grid -->
<div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6">
    @forelse($dataalat as $alat)
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 border border-slate-200 relative group">
            <!-- Checkbox untuk memilih -->
            <div class="absolute top-4 left-4 z-10">
                <input type="checkbox" 
                       id="alat_{{ $alat->id }}" 
                       value="{{ $alat->id }}"
                       class="alat-checkbox hidden peer"
                       data-nama="{{ $alat->nama_alat }}"
                       data-stok="{{ $alat->stok }}"
                       data-kondisi="{{ $alat->kondisi }}"
                       onchange="updateSelectedAlat()">
                <label for="alat_{{ $alat->id }}" 
                       class="w-8 h-8 flex items-center justify-center bg-white rounded-full shadow-lg cursor-pointer transition-all duration-200 hover:scale-110 peer-checked:bg-blue-600 peer-checked:text-white">
                    <i class="far fa-square peer-checked:hidden"></i>
                    <i class="fas fa-check-square hidden peer-checked:block"></i>
                </label>
            </div>

            <!-- Foto Alat -->
            <div class="h-40 md:h-48 overflow-hidden bg-slate-100 cursor-pointer" onclick="toggleAlatCheckbox({{ $alat->id }})">
                @if($alat->foto)
                    <img src="{{ asset('storage/' . $alat->foto) }}" 
                         alt="{{ $alat->nama_alat }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                @else
                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-50">
                        <i class="fas fa-tools text-3xl md:text-4xl text-blue-300"></i>
                    </div>
                @endif
                
                <!-- Stok Badge -->
                <div class="absolute top-4 right-4">
                    <span class="px-2 py-1 md:px-3 md:py-1 bg-green-100 text-green-800 rounded-full text-xs md:text-sm font-medium">
                        {{ $alat->stok }} Tersedia
                    </span>
                </div>
            </div>

            <!-- Content -->
            <div class="p-4 md:p-6" onclick="toggleAlatCheckbox({{ $alat->id }})">
                <!-- Kategori -->
                <div class="mb-2 md:mb-3">
                    <span class="px-2 py-1 md:px-3 md:py-1 bg-blue-50 text-blue-700 rounded-full text-xs font-medium">
                        {{ $alat->kategori->nama_kategori ?? 'Tanpa Kategori' }}
                    </span>
                </div>

                <!-- Nama Alat -->
                <h3 class="text-base md:text-lg font-bold text-slate-800 mb-2 line-clamp-1">{{ $alat->nama_alat }}</h3>

                <!-- Kondisi -->
                <div class="mb-3 md:mb-4">
                    @switch($alat->kondisi)
                        @case('baik')
                            <div class="flex items-center gap-1 md:gap-2 text-green-600">
                                <i class="fas fa-check-circle text-sm md:text-base"></i>
                                <span class="text-xs md:text-sm">Baik</span>
                            </div>
                            @break
                        @case('rusak_ringan')
                            <div class="flex items-center gap-1 md:gap-2 text-yellow-600">
                                <i class="fas fa-exclamation-triangle text-sm md:text-base"></i>
                                <span class="text-xs md:text-sm">Rusak Ringan</span>
                            </div>
                            @break
                        @case('perlu_perbaikan')
                            <div class="flex items-center gap-1 md:gap-2 text-orange-600">
                                <i class="fas fa-tools text-sm md:text-base"></i>
                                <span class="text-xs md:text-sm">Perlu Perbaikan</span>
                            </div>
                            @break
                        @default
                            <div class="flex items-center gap-1 md:gap-2 text-red-600">
                                <i class="fas fa-times-circle text-sm md:text-base"></i>
                                <span class="text-xs md:text-sm">Rusak Berat</span>
                            </div>
                    @endswitch
                </div>

                <!-- Lokasi -->
                <div class="flex items-center gap-1 md:gap-2 text-slate-600">
                    <i class="fas fa-map-marker-alt text-blue-500 text-sm md:text-base"></i>
                    <span class="text-xs md:text-sm truncate">{{ $alat->lokasi ?? 'Lokasi tidak tersedia' }}</span>
                </div>
            </div>
        </div>
    @empty
        <div class="col-span-2 md:col-span-full bg-white rounded-2xl shadow-lg p-8 md:p-12 text-center">
            <div class="text-slate-400 mb-4">
                <i class="fas fa-search text-4xl md:text-6xl"></i>
            </div>
            <h3 class="text-lg md:text-xl font-bold text-slate-700 mb-2">Alat Tidak Ditemukan</h3>
            <p class="text-slate-500 text-sm md:text-base mb-6">Tidak ada alat yang sesuai dengan kriteria pencarian Anda.</p>
            <button onclick="resetFilters()" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 md:px-6 md:py-3 rounded-lg font-medium transition text-sm md:text-base">
                <i class="fas fa-redo"></i> Reset Pencarian
            </button>
        </div>
    @endforelse
</div>

    <!-- Pagination -->
    @if($dataalat->hasPages())
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="text-slate-600">
                    Menampilkan {{ $dataalat->firstItem() }} - {{ $dataalat->lastItem() }} dari {{ $dataalat->total() }} alat
                </div>
                <div class="flex gap-2">
                    {{ $dataalat->links('vendor.pagination.tailwind') }}
                </div>
            </div>
        </div>
    @endif

    <!-- Summary Peminjaman (Fixed di bawah) -->
    <div id="summaryBox" class="fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 shadow-lg transform translate-y-full transition-transform duration-300 z-50">
        <div class="container mx-auto px-4 py-4">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <!-- Total Alat Dipilih -->
                <div class="flex items-center gap-4">
                    <div class="bg-blue-100 p-3 rounded-xl">
                        <i class="fas fa-tools text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-slate-600">Total Alat Dipilih</p>
                        <p id="totalAlatText" class="text-2xl font-bold text-slate-800">0 alat</p>
                    </div>
                </div>

                <!-- Daftar Alat Dipilih -->
                <div class="flex-1 max-w-md">
                    <div id="selectedAlatList" class="flex flex-wrap gap-2">
                        <span class="text-slate-500 text-sm">Belum ada alat yang dipilih</span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3">
                    <button onclick="clearSelection()" class="px-6 py-3 border border-slate-300 rounded-xl hover:bg-slate-50 transition flex items-center gap-2">
                        <i class="fas fa-times"></i> Kosongkan
                    </button>
                    <button onclick="showPinjamModal()" id="pinjamButton" disabled
                           class="px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-paper-plane"></i> Ajukan Peminjaman
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Form Peminjaman -->
<div id="pinjamModal" class="fixed inset-0 bg-black/50 z-[60] hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl animate-slide-up">
        <!-- Header Modal -->
        <div class="p-6 border-b border-slate-200">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold text-slate-800">Formulir Peminjaman</h3>
                <button onclick="closePinjamModal()" class="text-slate-400 hover:text-slate-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <p class="text-slate-600 text-sm mt-2">Isi data peminjaman</p>
        </div>

        <!-- Form -->
        <form id="pinjamForm" action="{{ route('peminjam.daftaralat.pinjam') }}" method="POST">
            @csrf
            <div class="p-6">
                <!-- Hidden Input untuk alat yang dipilih -->
                <div id="selectedAlatInputs"></div>

                <!-- Daftar Alat yang Dipilih -->
                <div class="mb-6">
                    <h4 class="font-medium text-slate-700 mb-3">Alat yang akan dipinjam:</h4>
                    <div id="modalAlatList" class="grid grid-cols-1 md:grid-cols-2 gap-3"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Tanggal Peminjaman -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            <i class="fas fa-calendar-alt mr-2"></i>Tanggal Peminjaman
                        </label>
                        <input type="date" name="tanggal_peminjaman" required
                               min="{{ date('Y-m-d') }}"
                               class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    </div>

                    <!-- Tanggal Pengembalian -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            <i class="fas fa-calendar-check mr-2"></i>Tanggal Pengembalian
                        </label>
                        <input type="date" name="tanggal_pengembalian" required
                               min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                               class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    </div>
                </div>
            </div>

            <!-- Footer Modal -->
            <div class="p-6 border-t border-slate-200 flex gap-3">
                <button type="button" onclick="closePinjamModal()"
                        class="flex-1 px-4 py-3 border border-slate-300 rounded-xl hover:bg-slate-50 transition">
                    Batal
                </button>
                <button type="submit"
                        class="flex-1 px-4 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition flex items-center justify-center gap-2">
                    <i class="fas fa-paper-plane"></i> Ajukan Peminjaman
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// State untuk menyimpan alat yang dipilih
let selectedAlat = [];

function validateStokBeforeSelect(alatId, stok) {
    if (stok <= 0) {
        Swal.fire({
            title: 'Stok Habis',
            text: 'Alat ini stoknya habis. Silakan pilih alat lain.',
            icon: 'warning',
            confirmButtonColor: '#3b82f6'
        });
        return false;
    }
    return true;
}

// Modifikasi fungsi toggleAlatCheckbox:
function toggleAlatCheckbox(alatId) {
    const checkbox = document.getElementById(`alat_${alatId}`);
    if (checkbox) {
        const stok = parseInt(checkbox.getAttribute('data-stok'));
        const kondisi = checkbox.getAttribute('data-kondisi');
        
        // Validasi stok dan kondisi
        if (stok <= 0) {
            Swal.fire({
                title: 'Stok Habis',
                text: 'Alat ini tidak tersedia untuk dipinjam.',
                icon: 'warning',
                confirmButtonColor: '#3b82f6'
            });
            return;
        }
        
        if (kondisi !== 'baik') {
            Swal.fire({
                title: 'Kondisi Alat',
                text: 'Alat ini dalam kondisi ' + kondisi + '. Mungkin tidak tersedia untuk dipinjam.',
                icon: 'info',
                confirmButtonColor: '#3b82f6'
            });
        }
        
        checkbox.checked = !checkbox.checked;
        updateSelectedAlat();
    }
}

// Fungsi update alat yang dipilih
function updateSelectedAlat() {
    selectedAlat = [];
    const checkboxes = document.querySelectorAll('.alat-checkbox:checked');
    
    checkboxes.forEach(checkbox => {
        selectedAlat.push({
            id: checkbox.value,
            nama: checkbox.getAttribute('data-nama'),
            stok: checkbox.getAttribute('data-stok'),
            kondisi: checkbox.getAttribute('data-kondisi')
        });
    });

    // Update summary box
    updateSummaryBox();
}

// Fungsi update summary box
function updateSummaryBox() {
    const totalAlatText = document.getElementById('totalAlatText');
    const selectedAlatList = document.getElementById('selectedAlatList');
    const pinjamButton = document.getElementById('pinjamButton');
    const summaryBox = document.getElementById('summaryBox');
    
    totalAlatText.textContent = `${selectedAlat.length} alat`;
    
    if (selectedAlat.length > 0) {
        // Tampilkan daftar alat yang dipilih
        selectedAlatList.innerHTML = selectedAlat.map(alat => `
            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm flex items-center gap-1">
                ${alat.nama}
                <button onclick="removeAlat(${alat.id})" class="ml-1 hover:text-red-600">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </span>
        `).join('');
        
        pinjamButton.disabled = false;
        summaryBox.classList.remove('translate-y-full');
    } else {
        selectedAlatList.innerHTML = '<span class="text-slate-500 text-sm">Belum ada alat yang dipilih</span>';
        pinjamButton.disabled = true;
        summaryBox.classList.add('translate-y-full');
    }
}

// Fungsi hapus alat dari selection
function removeAlat(alatId) {
    const checkbox = document.getElementById(`alat_${alatId}`);
    if (checkbox) {
        checkbox.checked = false;
        updateSelectedAlat();
    }
}

// Fungsi clear semua selection
function clearSelection() {
    document.querySelectorAll('.alat-checkbox').forEach(checkbox => {
        checkbox.checked = false;
    });
    updateSelectedAlat();
}

// Fungsi tampilkan modal peminjaman
function showPinjamModal() {
    const modal = document.getElementById('pinjamModal');
    const modalAlatList = document.getElementById('modalAlatList');
    const selectedAlatInputs = document.getElementById('selectedAlatInputs');
    
    // Update daftar alat di modal dengan format yang lebih baik
    modalAlatList.innerHTML = selectedAlat.map(alat => `
        <div class="bg-slate-50 p-3 rounded-xl border border-slate-200">
            <div class="flex items-center justify-between mb-2">
                <span class="font-medium text-slate-800">${alat.nama}</span>
                <span class="text-xs px-2 py-1 bg-green-100 text-green-800 rounded">Stok: ${alat.stok}</span>
            </div>
            <div class="text-xs text-slate-500">
                Kondisi: ${getKondisiText(alat.kondisi)}
            </div>
        </div>
    `).join('');
    
    // Buat hidden inputs untuk alat yang dipilih
    selectedAlatInputs.innerHTML = selectedAlat.map(alat => `
        <input type="hidden" name="alat_ids[]" value="${alat.id}">
    `).join('');
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

// Fungsi konversi kondisi ke text
function getKondisiText(kondisi) {
    const kondisiMap = {
        'baik': 'Baik',
        'rusak_ringan': 'Rusak Ringan',
        'perlu_perbaikan': 'Perlu Perbaikan',
        'rusak_berat': 'Rusak Berat'
    };
    return kondisiMap[kondisi] || kondisi;
}
// Fungsi tutup modal
function closePinjamModal() {
    document.getElementById('pinjamModal').classList.add('hidden');
    document.getElementById('pinjamModal').classList.remove('flex');
}

// Fungsi untuk filter dengan AJAX (opsional) atau redirect
document.getElementById('searchInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        applyFilters();
    }
});

document.getElementById('kategoriFilter').addEventListener('change', function() {
    applyFilters();
});

function applyFilters() {
    const search = document.getElementById('searchInput').value;
    const kategori = document.getElementById('kategoriFilter').value;
    
    let url = new URL(window.location.href);
    let params = new URLSearchParams(url.search);
    
    if (search) params.set('search', search);
    else params.delete('search');
    
    if (kategori) params.set('kategori_id', kategori);
    else params.delete('kategori_id');
    
    params.delete('page'); // Reset ke halaman 1
    
    window.location.href = `${url.pathname}?${params.toString()}`;
}

function resetFilters() {
    window.location.href = "{{ route('peminjam.daftaralat.index') }}";
}

// Close modal dengan ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closePinjamModal();
    }
});

// Initialize saat halaman load
document.addEventListener('DOMContentLoaded', function() {
    updateSelectedAlat();
});
</script>

<!-- CSS -->
<style>
.animate-slide-up {
    animation: slideUp 0.3s ease-out;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

#summaryBox {
    box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.1);
}

.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Responsive summary box */
@media (max-width: 768px) {
    #summaryBox .container {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    #selectedAlatList {
        max-height: 60px;
        overflow-y: auto;
    }
}
</style>
@endsection