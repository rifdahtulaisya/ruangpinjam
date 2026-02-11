@extends('layouts-peminjam.peminjam')

@section('title', 'RIWAYAT PEMINJAMAN')

@section('content')

    <div class="space-y-8 animate-slide-in">

        <!-- Filter Section -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">

                <!-- Bagian kiri: Filter dan Reset -->
                <div class="flex flex-wrap gap-3">
                    <div class="relative w-64">
                        <!-- Hidden input untuk kirim value -->
                        <input type="hidden" id="statusFilter" value="{{ request('status') }}">

                        <!-- Trigger Button -->
                        <button type="button" id="statusTrigger"
                            class="w-full pl-4 pr-12 py-3.5 border-2 border-slate-200 rounded-2xl 
                    focus:ring-3 focus:ring-blue-500/30 focus:border-blue-500 
                    transition-all duration-200 bg-white text-left text-slate-700 
                    font-medium hover:border-slate-300 shadow-sm flex items-center justify-between">

                            <span id="statusLabel">
                                @switch(request('status'))
                                    @case('menunggu')
                                        Menunggu Persetujuan
                                    @break

                                    @case('dipinjam')
                                        Sedang Dipinjam
                                    @break

                                    @case('selesai')
                                        Selesai Dikembalikan
                                    @break

                                    @case('ditolak')
                                        Ditolak
                                    @break

                                    @case('ditegur')
                                        Ditegur
                                    @break

                                    @default
                                        Semua Status
                                @endswitch
                            </span>

                            <i class="fas fa-chevron-down text-slate-500 text-sm transition-transform duration-200"></i>
                        </button>

                        <div id="statusOptions"
                            class="absolute z-50 w-full mt-1 bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden hidden animate-fade-in">

                            <div class="py-2">
                                <div class="option flex items-center gap-3 px-4 py-2.5 hover:bg-blue-50 cursor-pointer"
                                    data-value="" data-label="Semua Status">
                                    <i class="fas fa-list text-slate-500 w-4"></i>
                                    Semua Status
                                </div>

                                <div class="option flex items-center gap-3 px-4 py-2.5 hover:bg-yellow-50 cursor-pointer"
                                    data-value="menunggu" data-label="Menunggu Persetujuan">
                                    <i class="fas fa-clock text-yellow-500 w-4"></i>
                                    Menunggu Persetujuan
                                </div>

                                <div class="option flex items-center gap-3 px-4 py-2.5 hover:bg-green-50 cursor-pointer"
                                    data-value="dipinjam" data-label="Sedang Dipinjam">
                                    <i class="fas fa-hand-holding text-green-500 w-4"></i>
                                    Sedang Dipinjam
                                </div>

                                <div class="option flex items-center gap-3 px-4 py-2.5 hover:bg-purple-50 cursor-pointer"
                                    data-value="selesai" data-label="Selesai Dikembalikan">
                                    <i class="fas fa-check-circle text-purple-500 w-4"></i>
                                    Selesai Dikembalikan
                                </div>

                                <div class="option flex items-center gap-3 px-4 py-2.5 hover:bg-red-50 cursor-pointer"
                                    data-value="ditolak" data-label="Ditolak">
                                    <i class="fas fa-times-circle text-red-500 w-4"></i>
                                    Ditolak
                                </div>

                                <div class="option flex items-center gap-3 px-4 py-2.5 hover:bg-orange-50 cursor-pointer"
                                    data-value="ditegur" data-label="Ditegur">
                                    <i class="fas fa-exclamation-triangle text-orange-500 w-4"></i>
                                    Ditegur
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Reset Button -->
                    <button onclick="resetFilter()"
                        class="px-5 py-3.5 border-2 border-slate-200 rounded-2xl hover:bg-slate-50 hover:border-slate-300 transition-all duration-200 flex items-center gap-3 group shadow-sm">
                        <i class="fas fa-redo text-slate-600 group-hover:rotate-180 transition-transform duration-500"></i>
                        <span class="font-medium text-slate-700">Reset Filter</span>
                    </button>
                </div>

                <!-- Bagian kanan: Pengembalian Mandiri -->
                <div>
                    <button onclick="bukaPengembalianMandiri()"
                        class="px-6 py-3.5 bg-blue-800 text-white rounded-2xl hover:bg-blue-900 transition-all duration-200 flex items-center gap-3 group shadow-md hover:shadow-lg">
                        <i class="fas fa-undo-alt group-hover:rotate-180 transition-transform duration-500"></i>
                        <span class="font-medium">Pengembalian Mandiri</span>
                    </button>
                </div>
            </div>
        </div>


        <!-- Table Section -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <!-- Desktop Table -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="text-left py-4 px-6 text-sm font-semibold text-slate-700">Alat yang Dipinjam</th>
                            <th class="text-left py-4 px-6 text-sm font-semibold text-slate-700">Tanggal Pinjam</th>
                            <th class="text-left py-4 px-6 text-sm font-semibold text-slate-700">Tanggal Kembali</th>
                            <th class="text-left py-4 px-6 text-sm font-semibold text-slate-700">Status</th>
                            <th class="text-left py-4 px-6 text-sm font-semibold text-slate-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($peminjamans as $peminjaman)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="py-4 px-6">
                                    <div class="max-w-xs">
                                        <div class="font-medium text-slate-800 mb-1">{{ $peminjaman->nama_alat }}</div>
                                        <div class="text-xs text-slate-500">
                                            {{ count($peminjaman->alat_ids ?? []) }} alat
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-calendar-alt text-blue-500"></i>
                                        <span
                                            class="text-slate-700">{{ $peminjaman->tanggal_peminjaman->format('d/m/Y') }}</span>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-calendar-check text-green-500"></i>
                                        <span
                                            class="text-slate-700">{{ $peminjaman->tanggal_pengembalian->format('d/m/Y') }}</span>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    @php
                                        $statusColors = [
                                            'menunggu' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                            'dipinjam' => 'bg-green-100 text-green-800 border-green-200',
                                            'selesai' => 'bg-purple-100 text-purple-800 border-purple-200',
                                            'ditolak' => 'bg-red-100 text-red-800 border-red-200',
                                            'ditegur' => 'bg-orange-100 text-orange-800 border-orange-200',
                                        ];
                                        $statusIcons = [
                                            'menunggu' => 'clock',
                                            'dipinjam' => 'tools',
                                            'selesai' => 'check-circle',
                                            'ditolak' => 'times-circle',
                                            'ditegur' => 'exclamation-triangle',
                                        ];
                                    @endphp
                                    <span
                                        class="px-3 py-1 rounded-full text-sm font-medium border flex items-center gap-1 w-fit {{ $statusColors[$peminjaman->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        <i class="fas fa-{{ $statusIcons[$peminjaman->status] ?? 'circle' }} text-xs"></i>
                                        {{ ucfirst($peminjaman->status) }}
                                    </span>
                                </td>
                                <td class="py-4 px-6">
    <!-- Action Buttons -->
    <div class="flex gap-2 pt-3 border-t border-slate-100">
        <button onclick="showDetailModal({{ $peminjaman->id }})"
            class="flex-1 px-3 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition flex items-center justify-center gap-1">
            <i class="fas fa-eye text-xs"></i>
            <span class="text-sm">Detail</span>
        </button>
        
        @if ($peminjaman->status == 'dipinjam')
            <button onclick="showKembalikanModal({{ $peminjaman->id }})"
                class="flex-1 px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center justify-center gap-1">
                <i class="fas fa-upload text-xs"></i>
                <span class="text-sm">Kembalikan</span>
            </button>
        @elseif ($peminjaman->status == 'menunggu')
            <span
                class="flex-1 px-3 py-2 bg-yellow-100 text-yellow-800 rounded-lg text-sm font-medium border border-yellow-200 flex items-center justify-center gap-1">
                <i class="fas fa-clock text-xs"></i>
                <span>Menunggu</span>
            </span>
        @endif
    </div>
</td>
<script>
    // Test function - hapus setelah berhasil
console.log('Script loaded successfully');

// Test jika button diklik
window.testKembalikan = function(id) {
    console.log('Button clicked for ID:', id);
    showKembalikanModal(id);
}
</script>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 px-6 text-center">
                                    <div class="text-slate-400 mb-4">
                                        <i class="fas fa-history text-6xl"></i>
                                    </div>
                                    <h3 class="text-xl font-bold text-slate-700 mb-2">Belum Ada Riwayat Peminjaman</h3>
                                    <p class="text-slate-500 mb-6">Anda belum melakukan peminjaman alat.</p>
                                    <a href="{{ route('peminjam.daftaralat.index') }}"
                                        class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition">
                                        <i class="fas fa-tools"></i> Pinjam Alat Sekarang
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile Cards -->
            <div class="md:hidden">
                <div class="p-4">
                    @forelse($peminjamans as $peminjaman)
                        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-4 mb-4">
                            <!-- Header Card -->
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <div class="w-8 h-8 flex items-center justify-center bg-blue-100 rounded-lg">
                                            <i class="fas fa-file-alt text-blue-600 text-sm"></i>
                                        </div>
                                        <div class="font-bold text-slate-800">#{{ $peminjaman->id }}</div>
                                    </div>
                                    <div class="text-xs text-slate-500 ml-10">
                                        {{ $peminjaman->created_at->format('d/m/Y H:i') }}</div>
                                </div>

                                @php
                                    $statusColors = [
                                        'menunggu' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                        'dipinjam' => 'bg-green-100 text-green-800 border-green-200',
                                        'selesai' => 'bg-purple-100 text-purple-800 border-purple-200',
                                        'ditolak' => 'bg-red-100 text-red-800 border-red-200',
                                        'ditegur' => 'bg-orange-100 text-orange-800 border-orange-200',
                                    ];
                                @endphp
                                <span
                                    class="px-2 py-1 rounded-full text-xs font-medium border {{ $statusColors[$peminjaman->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($peminjaman->status) }}
                                </span>
                            </div>

                            <!-- Content Card -->
                            <div class="space-y-3">
                                <!-- Nama Alat -->
                                <div>
                                    <div class="text-sm font-medium text-slate-700 mb-1">Alat yang Dipinjam</div>
                                    <div class="text-slate-800 font-medium text-sm">{{ $peminjaman->nama_alat }}</div>
                                    <div class="text-xs text-slate-500 mt-1">
                                        {{ count($peminjaman->alat_ids ?? []) }} alat
                                    </div>
                                </div>

                                <!-- Tanggal -->
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <div class="text-xs text-slate-500 mb-1">Tanggal Pinjam</div>
                                        <div class="flex items-center gap-1">
                                            <i class="fas fa-calendar-alt text-blue-500 text-xs"></i>
                                            <span
                                                class="text-sm font-medium text-slate-700">{{ $peminjaman->tanggal_peminjaman->format('d/m/Y') }}</span>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-xs text-slate-500 mb-1">Tanggal Kembali</div>
                                        <div class="flex items-center gap-1">
                                            <i class="fas fa-calendar-check text-green-500 text-xs"></i>
                                            <span
                                                class="text-sm font-medium text-slate-700">{{ $peminjaman->tanggal_pengembalian->format('d/m/Y') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
<div class="flex gap-2 pt-3 border-t border-slate-100">
    <button onclick="showDetailModal({{ $peminjaman->id }})"
        class="flex-1 px-3 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition flex items-center justify-center gap-1">
        <i class="fas fa-eye text-xs"></i>
        <span class="text-sm">Detail</span>
    </button>
    
    @if ($peminjaman->status == 'dipinjam')
        <button onclick="showKembalikanModal({{ $peminjaman->id }})"
            class="flex-1 px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center justify-center gap-1">
            <i class="fas fa-upload text-xs"></i>
            <span class="text-sm">Kembalikan</span>
        </button>
    @elseif ($peminjaman->status == 'menunggu')
        <span
            class="flex-1 px-3 py-2 bg-yellow-100 text-yellow-800 rounded-lg text-sm font-medium border border-yellow-200 flex items-center justify-center gap-1">
            <i class="fas fa-clock text-xs"></i>
            <span>Menunggu</span>
        </span>
    @endif
</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <div class="text-slate-400 mb-4">
                                <i class="fas fa-history text-5xl"></i>
                            </div>
                            <h3 class="text-lg font-bold text-slate-700 mb-2">Belum Ada Riwayat Peminjaman</h3>
                            <p class="text-slate-500 text-sm mb-6">Anda belum melakukan peminjaman alat.</p>
                            <a href="{{ route('peminjam.daftaralat.index') }}"
                                class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg font-medium transition text-sm">
                                <i class="fas fa-tools"></i> Pinjam Alat Sekarang
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- PAGINATION -->
        @if ($peminjamans->total() > 0)

            <div class="px-6 py-4 border-t border-slate-100">
                <div class="flex flex-col md:flex-row items-center justify-between gap-4">

                    <!-- Info -->
                    <div class="text-sm text-slate-500">
                        Menampilkan
                        <span class="font-medium">{{ $peminjamans->firstItem() ?? 0 }}</span>
                        -
                        <span class="font-medium">{{ $peminjamans->lastItem() ?? 0 }}</span>
                        dari
                        <span class="font-medium">{{ $peminjamans->total() }}</span>
                        data
                    </div>

                    <!-- Navigation -->
                    <div class="flex items-center gap-2">

                        <!-- Previous -->
                        @if ($peminjamans->onFirstPage())
                            <span class="px-3 py-1.5 rounded-lg bg-slate-100 text-slate-400 cursor-not-allowed">
                                <i class="fa-solid fa-chevron-left"></i>
                            </span>
                        @else
                            <a href="{{ $peminjamans->previousPageUrl() . (request('status') ? '&status=' . request('status') : '') }}"
                                class="px-3 py-1.5 rounded-lg bg-indigo-100 text-indigo-600 hover:bg-indigo-200 transition">
                                <i class="fa-solid fa-chevron-left"></i>
                            </a>
                        @endif


                        <!-- Page Numbers -->
                        @php
                            $current = $peminjamans->currentPage();
                            $last = $peminjamans->lastPage();
                            $start = max(1, $current - 1);
                            $end = min($last, $current + 1);

                            if ($last <= 5) {
                                $start = 1;
                                $end = $last;
                            } else {
                                if ($current <= 3) {
                                    $start = 1;
                                    $end = 5;
                                } elseif ($current >= $last - 2) {
                                    $start = $last - 4;
                                    $end = $last;
                                }
                            }
                        @endphp

                        @for ($i = $start; $i <= $end; $i++)
                            <a href="{{ $peminjamans->url($i) . (request('status') ? '&status=' . request('status') : '') }}"
                                class="px-3 py-1.5 min-w-[40px] text-center rounded-lg transition 
                      {{ $i == $current ? 'bg-indigo-500 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                                {{ $i }}
                            </a>
                        @endfor


                        <!-- Next -->
                        @if ($peminjamans->hasMorePages())
                            <a href="{{ $peminjamans->nextPageUrl() . (request('status') ? '&status=' . request('status') : '') }}"
                                class="px-3 py-1.5 rounded-lg bg-indigo-100 text-indigo-600 hover:bg-indigo-200 transition">
                                <i class="fa-solid fa-chevron-right"></i>
                            </a>
                        @else
                            <span class="px-3 py-1.5 rounded-lg bg-slate-100 text-slate-400 cursor-not-allowed">
                                <i class="fa-solid fa-chevron-right"></i>
                            </span>
                        @endif

                    </div>
                </div>
            </div>
        @endif

    </div>

    <!-- Modal Detail Peminjaman -->
    <div id="detailModal" class="fixed inset-0 bg-black/50 z-[60] hidden items-center justify-center p-4">
        <div
            class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl animate-slide-up max-h-[90vh] overflow-hidden flex flex-col">
            <!-- Header Modal -->
            <div class="p-6 border-b border-slate-200 flex-shrink-0">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-slate-800">Detail Peminjaman</h3>
                    <button onclick="closeDetailModal()" class="text-slate-400 hover:text-slate-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <p class="text-slate-600 text-sm mt-2">Informasi lengkap peminjaman</p>
            </div>

            <!-- Content -->
            <div class="p-6 overflow-y-auto flex-grow">
                <div id="detailContent">
                    <!-- Content akan diisi via JavaScript -->
                </div>
            </div>

            <!-- Footer Modal -->
            <div class="p-6 border-t border-slate-200 flex gap-3 flex-shrink-0">
                <button type="button" onclick="closeDetailModal()"
                    class="flex-1 px-4 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <script>
        function showKembalikanModal(peminjamanId) {
            Swal.fire({
                title: 'Konfirmasi Pengembalian',
                text: 'Apakah Anda yakin ingin mengembalikan alat? Status akan berubah menjadi "Menunggu Konfirmasi"',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3b82f6',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Kembalikan',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // AJAX untuk mengubah status ke 'menunggu_pengembalian'
                    fetch(`/peminjam/peminjaman/${peminjamanId}/kembalikan`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: data.message,
                                    icon: 'success',
                                    confirmButtonColor: '#3b82f6',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: 'Gagal!',
                                    text: data.message || 'Gagal mengajukan pengembalian',
                                    icon: 'error',
                                    confirmButtonColor: '#ef4444'
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                title: 'Error!',
                                text: 'Terjadi kesalahan. Silakan coba lagi.',
                                icon: 'error',
                                confirmButtonColor: '#ef4444'
                            });
                        });
                }
            });
        }
        document.addEventListener('DOMContentLoaded', function() {

            const trigger = document.getElementById('statusTrigger');
            const dropdown = document.getElementById('statusOptions');
            const hiddenInput = document.getElementById('statusFilter');
            const label = document.getElementById('statusLabel');
            const options = document.querySelectorAll('.option');

            // Toggle dropdown
            trigger.addEventListener('click', function(e) {
                e.stopPropagation();
                dropdown.classList.toggle('hidden');
            });

            // Select option
            options.forEach(option => {

                option.addEventListener('click', function() {

                    const value = this.dataset.value;
                    const text = this.dataset.label;

                    hiddenInput.value = value;
                    label.innerText = text;

                    dropdown.classList.add('hidden');

                    // Redirect filter
                    let url = new URL(window.location.href);

                    if (value) {
                        url.searchParams.set('status', value);
                    } else {
                        url.searchParams.delete('status');
                    }

                    url.searchParams.delete('page');

                    window.location.href = url.toString();

                });

            });

            // Close when click outside
            document.addEventListener('click', function() {
                dropdown.classList.add('hidden');
            });

        });

        function resetFilter() {
            window.location.href = "{{ route('peminjam.peminjaman.index') }}";
        }

        // Filter functions
        document.getElementById('statusFilter').addEventListener('change', function() {
            const status = this.value;
            let url = new URL(window.location.href);

            if (status) {
                url.searchParams.set('status', status);
            } else {
                url.searchParams.delete('status');
            }

            url.searchParams.delete('page'); // Reset ke halaman 1
            window.location.href = url.toString();
        });

        function resetFilter() {
            window.location.href = "{{ route('peminjam.peminjaman.index') }}";
        }

        // Modal Detail Functions
        function showDetailModal(peminjamanId) {
            // Dummy data - nanti bisa diganti dengan AJAX
            const modalContent = `
        <div class="space-y-6">
            <!-- Informasi Peminjaman -->
            <div>
                <h4 class="font-bold text-slate-800 mb-3 flex items-center gap-2">
                    <i class="fas fa-info-circle text-blue-500"></i>
                    Informasi Peminjaman
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-slate-50 p-4 rounded-xl">
                        <div class="text-sm text-slate-500 mb-1">Kode Peminjaman</div>
                        <div class="font-medium text-slate-800">#${peminjamanId}</div>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-xl">
                        <div class="text-sm text-slate-500 mb-1">Status</div>
                        <div class="font-medium text-slate-800">Menunggu</div>
                    </div>
                </div>
            </div>

            <!-- Tanggal -->
            <div>
                <h4 class="font-bold text-slate-800 mb-3 flex items-center gap-2">
                    <i class="fas fa-calendar-alt text-blue-500"></i>
                    Tanggal Peminjaman
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-slate-50 p-4 rounded-xl">
                        <div class="text-sm text-slate-500 mb-1">Tanggal Pinjam</div>
                        <div class="font-medium text-slate-800">01/01/2024</div>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-xl">
                        <div class="text-sm text-slate-500 mb-1">Tanggal Kembali</div>
                        <div class="font-medium text-slate-800">05/01/2024</div>
                    </div>
                </div>
            </div>

            <!-- Alat yang Dipinjam -->
            <div>
                <h4 class="font-bold text-slate-800 mb-3 flex items-center gap-2">
                    <i class="fas fa-tools text-blue-500"></i>
                    Alat yang Dipinjam
                </h4>
                <div class="space-y-3">
                    <div class="bg-slate-50 p-4 rounded-xl">
                        <div class="flex items-center justify-between mb-2">
                            <div class="font-medium text-slate-800">Bor Listrik</div>
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs">Baik</span>
                        </div>
                        <div class="text-sm text-slate-500">Kode: BR-001 | Lokasi: Gudang A</div>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-xl">
                        <div class="flex items-center justify-between mb-2">
                            <div class="font-medium text-slate-800">Gergaji Mesin</div>
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs">Baik</span>
                        </div>
                        <div class="text-sm text-slate-500">Kode: GR-005 | Lokasi: Gudang B</div>
                    </div>
                </div>
            </div>

            <!-- Keterangan -->
            <div>
                <h4 class="font-bold text-slate-800 mb-3 flex items-center gap-2">
                    <i class="fas fa-sticky-note text-blue-500"></i>
                    Keterangan
                </h4>
                <div class="bg-slate-50 p-4 rounded-xl">
                    <div class="text-sm text-slate-500 mb-1">Catatan</div>
                    <div class="text-slate-800">Digunakan untuk proyek renovasi ruang kelas.</div>
                </div>
            </div>
        </div>
    `;

            document.getElementById('detailContent').innerHTML = modalContent;

            const modal = document.getElementById('detailModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeDetailModal() {
            const modal = document.getElementById('detailModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // Close modal dengan ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDetailModal();
            }
        });
    </script>

    <style>
        .animate-slide-in {
            animation: slideIn 0.5s ease-out;
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

        /* Scrollbar styling untuk modal */
        #detailModal>div>div:nth-child(2) {
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 #f1f5f9;
        }

        #detailModal>div>div:nth-child(2)::-webkit-scrollbar {
            width: 6px;
        }

        #detailModal>div>div:nth-child(2)::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        #detailModal>div>div:nth-child(2)::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 20px;
        }

        * Style untuk select dropdown */ #statusFilter:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        /* Style untuk custom dropdown */
        #statusOptions {
            max-height: 400px;
            overflow-y: auto;
        }

        #statusOptions::-webkit-scrollbar {
            width: 6px;
        }

        #statusOptions::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 0 8px 8px 0;
        }

        #statusOptions::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 20px;
        }

        /* Animasi */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.2s ease-out;
        }

        /* Style untuk button status di table - Diperlebar */
        .table-status-btn {
            min-width: 120px !important;
            padding: 8px 16px !important;
            justify-content: center !important;
            gap: 8px !important;
        }

        .mobile-status-btn {
            min-width: 90px !important;
            padding: 6px 12px !important;
            justify-content: center !important;
        }

        /* Style untuk status di table */
        .status-badge {
            min-width: 120px;
            padding: 8px 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-weight: 500;
        }

        .mobile-status-badge {
            min-width: 90px;
            padding: 6px 12px;
            font-size: 0.75rem;
        }
    </style>
@endsection
