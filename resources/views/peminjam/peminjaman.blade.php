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
                                    @case('menunggu_peminjaman')
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

                                <!-- Update option untuk status baru -->
                                <div class="option flex items-center gap-3 px-4 py-2.5 hover:bg-yellow-50 cursor-pointer"
                                    data-value="menunggu_peminjaman" data-label="Menunggu Persetujuan">
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
                    <button onclick="bukaPengembalianMandiri()" id="btnPengembalianMandiri"
                        class="px-6 py-3.5 bg-blue-800 text-white rounded-2xl hover:bg-blue-900 transition-all duration-200 flex items-center gap-3 group shadow-md hover:shadow-lg {{ !$cekBarangDipinjam ? 'opacity-50 cursor-not-allowed' : '' }}"
                        {{ !$cekBarangDipinjam ? 'disabled' : '' }}>
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
                                        // Update status colors - HAPUS menunggu_pengembalian
                                        $statusColors = [
                                            'menunggu_peminjaman' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                            'dipinjam' => 'bg-green-100 text-green-800 border-green-200',
                                            'selesai' => 'bg-purple-100 text-purple-800 border-purple-200',
                                            'ditolak' => 'bg-red-100 text-red-800 border-red-200',
                                            'ditegur' => 'bg-orange-100 text-orange-800 border-orange-200',
                                        ];
                                        $statusIcons = [
                                            'menunggu_peminjaman' => 'clock',
                                            'dipinjam' => 'hand-holding',
                                            'selesai' => 'check-circle',
                                            'ditolak' => 'times-circle',
                                            'ditegur' => 'exclamation-triangle',
                                        ];
                                    @endphp
                                    <span
                                        class="px-3 py-1 rounded-full text-sm font-medium border flex items-center gap-1 w-fit {{ $statusColors[$peminjaman->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        <i class="fas fa-{{ $statusIcons[$peminjaman->status] ?? 'circle' }} text-xs"></i>
                                        {{ ucfirst(str_replace('_', ' ', $peminjaman->status)) }}
                                    </span>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex flex-wrap gap-2 pt-3 border-t border-slate-100">
                                        <!-- Detail Button -->
                                        <button onclick="showDetailModal({{ $peminjaman->id }})"
                                            class="flex-1 px-3 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition flex items-center justify-center gap-1">
                                            <i class="fas fa-eye text-xs"></i>
                                            <span class="text-sm">Detail</span>
                                        </button>

                                        @if ($peminjaman->status == 'ditegur' && $peminjaman->isPengembalianMandiri())
                                            <!-- Tampilkan teguran dan button foto ulang -->
                                            <div class="w-full mt-2 p-3 bg-orange-50 border border-orange-200 rounded-lg"
                                                data-teguran-container="{{ $peminjaman->id }}">
                                                <div class="flex items-start gap-2">
                                                    <i class="fas fa-exclamation-triangle text-orange-600 mt-1"></i>
                                                    <div class="flex-1">
                                                        <div class="text-xs font-bold text-orange-800 mb-1">TEGURAN DARI
                                                            PETUGAS:</div>
                                                        <div class="text-xs text-orange-700 mb-2">
                                                            {{ $peminjaman->getTeksTeguran() }}
                                                        </div>
                                                        <div class="flex gap-2">
                                                            <button
                                                                onclick="bukaPengembalianMandiriWithItem({{ $peminjaman->id }})"
                                                                class="px-3 py-1.5 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition flex items-center gap-1 text-xs">
                                                                <i class="fas fa-camera"></i>
                                                                Foto Ulang
                                                            </button>
                                                        </div>
                                                        @if ($peminjaman->teguran_dikirim_at)
                                                            <div class="text-xs text-slate-500 mt-2">
                                                                Teguran dikirim:
                                                                {{ $peminjaman->teguran_dikirim_at->format('d/m/Y H:i') }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif ($peminjaman->status == 'ditegur')
                                            <!-- Teguran untuk pengembalian manual -->
                                            <div class="w-full mt-2 p-3 bg-orange-50 border border-orange-200 rounded-lg">
                                                <div class="flex items-start gap-2">
                                                    <i class="fas fa-exclamation-triangle text-orange-600 mt-1"></i>
                                                    <div class="flex-1">
                                                        <div class="text-xs font-bold text-orange-800 mb-1">TEGURAN:</div>
                                                        <div class="text-xs text-orange-700">
                                                            {{ $peminjaman->getTeksTeguran() }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif ($peminjaman->status == 'dipinjam')
                                            <span
                                                class="flex-1 px-3 py-2 bg-green-100 text-green-800 rounded-lg text-sm font-medium border border-green-200 flex items-center justify-center gap-1">
                                                <i class="fas fa-hand-holding text-xs"></i>
                                                <span>Sedang Dipinjam</span>
                                            </span>
                                        @endif
                                    </div>
                                </td>
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
                                    // Update untuk mobile juga
                                    $statusColors = [
                                        'menunggu_peminjaman' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                        'dipinjam' => 'bg-green-100 text-green-800 border-green-200',
                                        'selesai' => 'bg-purple-100 text-purple-800 border-purple-200',
                                        'ditolak' => 'bg-red-100 text-red-800 border-red-200',
                                        'ditegur' => 'bg-orange-100 text-orange-800 border-orange-200',
                                    ];
                                @endphp
                                <span
                                    class="px-2 py-1 rounded-full text-xs font-medium border {{ $statusColors[$peminjaman->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst(str_replace('_', ' ', $peminjaman->status)) }}
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
                                <div class="flex flex-wrap gap-2 pt-3 border-t border-slate-100">
                                    <button onclick="showDetailModal({{ $peminjaman->id }})"
                                        class="flex-1 px-3 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition text-sm">
                                        <i class="fas fa-eye mr-1"></i> Detail
                                    </button>

                                    @if ($peminjaman->status == 'ditegur' && $peminjaman->isPengembalianMandiri())
                                        <div class="w-full mt-2 p-3 bg-orange-50 border border-orange-200 rounded-lg">
                                            <div class="text-xs font-bold text-orange-800 mb-1">TEGURAN:</div>
                                            <div class="text-xs text-orange-700 mb-2">
                                                {{ $peminjaman->getTeksTeguran() }}
                                            </div>
                                            <div class="flex gap-2">
                                                <button onclick="bukaPengembalianMandiri()"
                                                    class="flex-1 px-3 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition text-xs">
                                                    <i class="fas fa-camera mr-1"></i> Foto Ulang
                                                </button>
                                                <button onclick="showKembalikanManual({{ $peminjaman->id }})"
                                                    class="flex-1 px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-xs">
                                                    <i class="fas fa-upload mr-1"></i> Kembalikan
                                                </button>
                                            </div>
                                        </div>
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

    <!-- Modal Pengembalian Mandiri -->
    <div id="pengembalianMandiriModal" class="fixed inset-0 bg-black/80 z-[70] hidden items-center justify-center p-4">
        <div
            class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl animate-slide-up max-h-[90vh] overflow-hidden flex flex-col">
            <!-- Header Modal -->
            <div class="p-6 border-b border-slate-200 flex-shrink-0">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-camera-retro text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-slate-800">Pengembalian Mandiri</h3>
                            <p class="text-slate-600 text-sm mt-1">Ambil foto barang untuk proses pengembalian</p>
                        </div>
                    </div>
                    <button onclick="closePengembalianMandiri()" class="text-slate-400 hover:text-slate-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>

            <!-- Content -->
            <div class="p-6 overflow-y-auto flex-grow">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Bagian Kiri: Camera -->
                    <div class="bg-slate-900 rounded-2xl overflow-hidden border-4 border-slate-700 shadow-xl">
                        <div class="relative">
                            <!-- Video Stream -->
                            <video id="video" autoplay playsinline
                                class="w-full h-[300px] object-cover bg-black"></video>

                            <!-- Canvas untuk capture -->
                            <canvas id="canvas" class="hidden"></canvas>

                            <!-- Hasil Foto -->
                            <div id="hasilFotoContainer" class="hidden absolute inset-0">
                                <img id="hasilFoto" class="w-full h-[300px] object-cover">
                                <button onclick="ambilUlangFoto()"
                                    class="absolute top-4 right-4 bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700 transition shadow-lg flex items-center gap-2">
                                    <i class="fas fa-redo"></i> Ambil Ulang
                                </button>
                            </div>

                            <!-- Overlay saat kamera mati -->
                            <div id="kameraMatiOverlay"
                                class="hidden absolute inset-0 bg-black/90 flex flex-col items-center justify-center text-white p-6">
                                <i class="fas fa-video-slash text-5xl mb-4 text-red-400"></i>
                                <h4 class="text-lg font-bold mb-2">Kamera Tidak Terdeteksi</h4>
                                <p class="text-sm text-center text-slate-300 mb-4">Pastikan kamera terhubung dan izin akses
                                    diberikan</p>
                                <button onclick="startKamera()"
                                    class="bg-blue-600 hover:bg-blue-700 px-6 py-2 rounded-lg text-sm flex items-center gap-2">
                                    <i class="fas fa-redo"></i> Coba Lagi
                                </button>
                            </div>
                        </div>

                        <!-- Kontrol Kamera -->
                        <div class="bg-slate-800 p-4 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="text-white text-sm flex items-center gap-2">
                                    <i class="fas fa-circle text-green-400 text-xs"></i>
                                    <span id="statusKamera">Menyiapkan kamera...</span>
                                </span>
                            </div>
                            <div class="flex gap-2">
                                <button onclick="gantiKamera()"
                                    class="bg-slate-700 hover:bg-slate-600 text-white px-4 py-2 rounded-lg text-sm transition flex items-center gap-2">
                                    <i class="fas fa-sync-alt"></i>
                                    <span class="hidden md:inline">Ganti Kamera</span>
                                </button>
                                <button onclick="ambilFoto()" id="btnAmbilFoto"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm transition flex items-center gap-2 shadow-lg">
                                    <i class="fas fa-camera"></i>
                                    <span class="hidden md:inline">Ambil Foto</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Bagian Kanan: Daftar Barang Dipinjam -->
                    <div class="bg-slate-50 rounded-2xl p-5 border border-slate-200">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="font-bold text-slate-800 flex items-center gap-2">
                                <i class="fas fa-hand-holding text-blue-600"></i>
                                Pilih Barang yang Dikembalikan
                            </h4>
                            <span id="selectedCount"
                                class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-medium">
                                0 dipilih
                            </span>
                        </div>

                        <!-- Search Box -->
                        <div class="relative mb-4">
                            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                            <input type="text" id="searchBarang" placeholder="Cari nama atau kode barang..."
                                class="w-full pl-9 pr-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 text-sm">
                        </div>

                        <!-- Daftar Barang yang sedang dipinjam -->
                        <div id="daftarBarangContainer"
                            class="space-y-3 max-h-[400px] overflow-y-auto pr-2 custom-scrollbar">
                            <!-- Loading State -->
                            <div id="loadingBarang" class="text-center py-8">
                                <div
                                    class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-slate-300 border-t-blue-600">
                                </div>
                                <p class="text-sm text-slate-500 mt-3">Memuat barang...</p>
                            </div>

                            <!-- Data akan diisi via JavaScript -->
                            <div id="listBarang" class="space-y-3 hidden"></div>

                            <!-- Empty State -->
                            <div id="emptyBarang" class="hidden text-center py-12">
                                <div
                                    class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-box-open text-slate-400 text-3xl"></i>
                                </div>
                                <h5 class="font-medium text-slate-700 mb-2">Tidak Ada Barang Dipinjam</h5>
                                <p class="text-sm text-slate-500">Anda sedang tidak meminjam barang apapun</p>
                            </div>
                        </div>

                        <!-- Info Tambahan -->
                        <div class="mt-4 p-4 bg-blue-50 rounded-xl border border-blue-200 flex items-start gap-3">
                            <i class="fas fa-info-circle text-blue-600 mt-0.5"></i>
                            <div class="text-xs text-blue-800">
                                <p class="font-medium mb-1">Petunjuk Pengembalian Mandiri:</p>
                                <ul class="list-disc list-inside space-y-1">
                                    <li>Pastikan foto barang jelas dan terbaca</li>
                                    <li>Pilih minimal 1 barang yang akan dikembalikan</li>
                                    <li>Foto akan diverifikasi oleh admin</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Modal -->
            <div class="p-6 border-t border-slate-200 flex justify-end gap-3 flex-shrink-0">
                <button onclick="closePengembalianMandiri()"
                    class="px-6 py-3 border-2 border-slate-300 text-slate-700 rounded-xl hover:bg-slate-50 transition font-medium">
                    Batal
                </button>
                <button onclick="kirimPengembalian()" id="btnKirim"
                    class="px-8 py-3 bg-blue-800 text-white rounded-xl hover:bg-blue-900 transition font-medium flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-blue-800"
                    disabled>
                    <i class="fas fa-paper-plane"></i>
                    Kirim Pengembalian
                </button>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="fixed inset-0 bg-black/60 z-[80] hidden items-center justify-center">
        <div class="bg-white rounded-2xl p-8 max-w-sm w-full mx-4 text-center animate-slide-up">
            <div class="inline-block animate-spin rounded-full h-12 w-12 border-4 border-slate-200 border-t-blue-600 mb-4">
            </div>
            <h4 class="text-lg font-bold text-slate-800 mb-2">Memproses Pengembalian</h4>
            <p class="text-sm text-slate-600">Mohon tunggu sebentar...</p>
        </div>
    </div>

    <!-- Notifikasi -->
    <div id="notification" class="fixed top-4 right-4 z-[100] hidden animate-slide-in">
        <div class="bg-white rounded-xl shadow-2xl border-l-4 border-green-500 p-4 min-w-[320px] flex items-start gap-3">
            <div id="notificationIcon" class="text-green-500 text-xl">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="flex-1">
                <h5 id="notificationTitle" class="font-bold text-slate-800 mb-1">Berhasil!</h5>
                <p id="notificationMessage" class="text-sm text-slate-600">Pengembalian berhasil diajukan</p>
            </div>
            <button onclick="closeNotification()" class="text-slate-400 hover:text-slate-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

    <script>
       // ============ FITUR PENGEMBALIAN MANDIRI ============
let stream = null;
let currentFacingMode = 'environment';
let capturedImage = null;
let selectedItems = new Set();
let allBarangDipinjam = [];
let forcedPeminjamanId = null; // Untuk foto ulang dari teguran

// CEK STATUS BARANG DIPINJAM SAAT HALAMAN DIMUAT
document.addEventListener('DOMContentLoaded', function() {
    cekStatusBarangDipinjam();
    
    // Load teguran untuk setiap peminjaman yang statusnya ditegur
    const teguranItems = document.querySelectorAll('[data-teguran-container]');
    teguranItems.forEach(item => {
        const match = item.getAttribute('data-teguran-container');
        if (match) {
            loadTeguranDetail(match);
        }
    });
});

function cekStatusBarangDipinjam() {
    fetch('/peminjam/peminjaman/cek-barang-dipinjam', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        const btn = document.getElementById('btnPengembalianMandiri');
        if (btn) {
            if (data.bisa_pengembalian) {
                btn.disabled = false;
                btn.classList.remove('opacity-50', 'cursor-not-allowed');
                btn.classList.add('hover:bg-blue-900');
            } else {
                btn.disabled = true;
                btn.classList.add('opacity-50', 'cursor-not-allowed');
                btn.classList.remove('hover:bg-blue-900');
            }
        }
    })
    .catch(error => console.error('Error cek status:', error));
}

        // Buka Modal Pengembalian Mandiri
function bukaPengembalianMandiri() {
    forcedPeminjamanId = null; // Reset forced item
    const modal = document.getElementById('pengembalianMandiriModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    // Reset state
    selectedItems.clear();
    capturedImage = null;
    document.getElementById('btnKirim').disabled = true;
    document.getElementById('selectedCount').innerText = '0 dipilih';
    document.getElementById('hasilFotoContainer').classList.add('hidden');
    document.getElementById('kameraMatiOverlay').classList.add('hidden');
    
    // Load daftar barang yang sedang dipinjam
    loadBarangDipinjam();
    
    // Start kamera
    startKamera();
}

// Buka Modal Pengembalian Mandiri dengan Item Tertentu (untuk foto ulang)
function bukaPengembalianMandiriWithItem(peminjamanId) {
    forcedPeminjamanId = peminjamanId;
    
    const modal = document.getElementById('pengembalianMandiriModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    // Reset state
    selectedItems.clear();
    capturedImage = null;
    document.getElementById('btnKirim').disabled = true;
    document.getElementById('selectedCount').innerText = '0 dipilih';
    document.getElementById('hasilFotoContainer').classList.add('hidden');
    document.getElementById('kameraMatiOverlay').classList.add('hidden');
    
    // Load daftar barang yang ditegur
    loadBarangDitegur(peminjamanId);
    
    // Start kamera
    startKamera();
}

        // Close Modal
        function closePengembalianMandiri() {
            const modal = document.getElementById('pengembalianMandiriModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');

            // Stop kamera
            stopKamera();

            // Reset
            capturedImage = null;
            selectedItems.clear();
        }

        // Load Daftar Barang yang Sedang Dipinjam
function loadBarangDipinjam() {
    fetch('/peminjam/peminjaman/barang-dipinjam', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        const loadingEl = document.getElementById('loadingBarang');
        const listBarangEl = document.getElementById('listBarang');
        const emptyBarangEl = document.getElementById('emptyBarang');
        
        loadingEl.classList.add('hidden');
        
        if (data.success && data.data.length > 0) {
            allBarangDipinjam = data.data;
            renderDaftarBarang(data.data, false);
            listBarangEl.classList.remove('hidden');
            emptyBarangEl.classList.add('hidden');
        } else {
            listBarangEl.classList.add('hidden');
            emptyBarangEl.classList.remove('hidden');
            emptyBarangEl.innerHTML = `
                <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-box-open text-slate-400 text-3xl"></i>
                </div>
                <h5 class="font-medium text-slate-700 mb-2">Tidak Ada Barang Dipinjam</h5>
                <p class="text-sm text-slate-500">Anda sedang tidak meminjam barang apapun</p>
            `;
        }
    })
    .catch(error => {
        console.error('Error loading barang:', error);
        showNotification('error', 'Gagal memuat data barang', 'Silakan coba lagi');
    });
}

// Load Teguran Detail
function loadTeguranDetail(peminjamanId) {
    fetch(`/peminjam/peminjaman/${peminjamanId}/teguran`, {
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.data) {
            const teguranContainer = document.querySelector(`[data-teguran-container="${peminjamanId}"]`);
            if (teguranContainer) {
                // Update konten teguran dengan detail yang lebih lengkap
                const teguranContent = teguranContainer.querySelector('.flex-1');
                if (teguranContent) {
                    const existingButton = teguranContent.querySelector('.flex.gap-2');
                    if (existingButton) {
                        existingButton.innerHTML = `
                            <button onclick="bukaPengembalianMandiriWithItem(${peminjamanId})" 
                                class="px-3 py-1.5 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition flex items-center gap-1 text-xs">
                                <i class="fas fa-camera"></i>
                                Foto Ulang
                            </button>
                        `;
                    }
                }
            }
        }
    })
    .catch(error => console.error('Error loading teguran:', error));
}

        // Load Daftar Barang yang Ditegur (untuk foto ulang)
function loadBarangDitegur(peminjamanId) {
    fetch('/peminjam/peminjaman/barang-dipinjam?filter_teguran=ditegur', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        const loadingEl = document.getElementById('loadingBarang');
        const listBarangEl = document.getElementById('listBarang');
        const emptyBarangEl = document.getElementById('emptyBarang');
        
        loadingEl.classList.add('hidden');
        
        if (data.success && data.data.length > 0) {
            // Filter hanya peminjaman yang sesuai dengan forcedPeminjamanId
            const filteredData = data.data.filter(item => item.id == peminjamanId);
            
            if (filteredData.length > 0) {
                allBarangDipinjam = filteredData;
                renderDaftarBarang(filteredData, true); // true = auto select
                listBarangEl.classList.remove('hidden');
                emptyBarangEl.classList.add('hidden');
            } else {
                listBarangEl.classList.add('hidden');
                emptyBarangEl.classList.remove('hidden');
                emptyBarangEl.innerHTML = `
                    <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-exclamation-circle text-slate-400 text-3xl"></i>
                    </div>
                    <h5 class="font-medium text-slate-700 mb-2">Tidak Ada Barang yang Ditegur</h5>
                    <p class="text-sm text-slate-500">Barang yang ditegur tidak ditemukan</p>
                `;
            }
        } else {
            listBarangEl.classList.add('hidden');
            emptyBarangEl.classList.remove('hidden');
            emptyBarangEl.innerHTML = `
                <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-check-circle text-slate-400 text-3xl"></i>
                </div>
                <h5 class="font-medium text-slate-700 mb-2">Tidak Ada Teguran Aktif</h5>
                <p class="text-sm text-slate-500">Semua pengembalian mandiri Anda sudah diproses</p>
            `;
        }
    })
    .catch(error => {
        console.error('Error loading barang ditegur:', error);
        showNotification('error', 'Gagal memuat data', 'Silakan coba lagi');
    });
}

        // Render Daftar Barang dengan Opsi Auto Select
function renderDaftarBarang(barangList, autoSelect = false) {
    const container = document.getElementById('listBarang');
    container.innerHTML = '';
    
    barangList.forEach(item => {
        const alatList = item.alat_ids || [];
        const alatNames = item.nama_alat ? item.nama_alat.split(',').map(n => n.trim()) : [];
        
        const card = document.createElement('div');
        card.className = 'barang-item bg-white rounded-xl p-4 border-2 transition-all cursor-pointer hover:border-blue-400';
        if (item.is_ditegur) {
            card.classList.add('border-orange-300', 'bg-orange-50/30');
        }
        card.dataset.peminjamanId = item.id;
        
        let alatHtml = '';
        alatList.forEach((alatId, index) => {
            const alatName = alatNames[index] || 'Alat';
            const isChecked = autoSelect ? 'checked' : '';
            const disabledAttr = autoSelect ? 'disabled' : '';
            
            alatHtml += `
                <div class="flex items-center justify-between py-2 border-b border-slate-100 last:border-0">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 ${item.is_ditegur ? 'bg-orange-100' : 'bg-slate-100'} rounded-lg flex items-center justify-center">
                            <i class="fas fa-tools ${item.is_ditegur ? 'text-orange-600' : 'text-slate-600'} text-sm"></i>
                        </div>
                        <div>
                            <div class="font-medium text-slate-800 text-sm">${alatName}</div>
                            <div class="text-xs text-slate-500">Kode: AL-${String(alatId).padStart(3, '0')}</div>
                            ${item.is_ditegur ? '<span class="text-xs text-orange-600">⛔ Perlu foto ulang</span>' : ''}
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-xs px-2 py-1 ${item.is_ditegur ? 'bg-orange-100 text-orange-800' : 'bg-green-100 text-green-800'} rounded-full">
                            ${item.is_ditegur ? 'Ditegur' : 'Dipinjam'}
                        </span>
                        <input type="checkbox" 
                            onchange="toggleSelectBarang(${item.id}, ${alatId}, '${alatName}', this)"
                            class="w-5 h-5 text-blue-600 rounded border-slate-300 focus:ring-blue-500"
                            ${isChecked}
                            ${disabledAttr}>
                    </div>
                </div>
            `;
            
            // Jika auto select, tambahkan ke selectedItems
            if (autoSelect) {
                const itemKey = `${item.id}-${alatId}`;
                selectedItems.add(itemKey);
            }
        });
        
        // Tambahkan info teguran jika ada
        let teguranInfo = '';
        if (item.teks_teguran) {
            teguranInfo = `
                <div class="mt-2 p-2 bg-orange-100/50 rounded-lg text-xs text-orange-700">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    Teguran: ${item.teks_teguran}
                </div>
            `;
        }
        
        card.innerHTML = `
            <div class="mb-2 flex justify-between items-start">
                <div>
                    <span class="text-xs font-medium ${item.is_ditegur ? 'bg-orange-100 text-orange-800' : 'bg-blue-50 text-blue-600'} px-2 py-1 rounded-full">
                        ID Pinjam: #${item.id} ${item.is_ditegur ? '(Teguran)' : ''}
                    </span>
                </div>
                <span class="text-xs text-slate-500">
                    ${item.tanggal_peminjaman_formatted || ''}
                </span>
            </div>
            <div class="space-y-1">
                ${alatHtml}
            </div>
            ${teguranInfo}
        `;
        
        container.appendChild(card);
    });
    
    // Update selected count jika auto select
    if (autoSelect) {
        updateSelectedCount();
        validateForm();
    }
    
    // Setup search
    setupSearchBarang();
}
        // Setup Search
        function setupSearchBarang() {
            const searchInput = document.getElementById('searchBarang');
            searchInput.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                const cards = document.querySelectorAll('#listBarang .barang-item');

                cards.forEach(card => {
                    const text = card.textContent.toLowerCase();
                    if (text.includes(searchTerm)) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        }

        function toggleSelectBarang(peminjamanId, alatId, namaAlat, checkbox) {
    const itemKey = `${peminjamanId}-${alatId}`;
    
    if (checkbox.checked) {
        selectedItems.add(itemKey);
    } else {
        selectedItems.delete(itemKey);
    }
    
    updateSelectedCount();
    validateForm();
}

        function updateSelectedCount() {
    const count = selectedItems.size;
    document.getElementById('selectedCount').innerHTML = `${count} dipilih`;
}

        function validateForm() {
    const btnKirim = document.getElementById('btnKirim');
    const hasFoto = capturedImage !== null;
    const hasSelected = selectedItems.size > 0;
    
    btnKirim.disabled = !(hasFoto && hasSelected);
}

        // ============ FITUR KAMERA ============
        // Start Kamera
        async function startKamera() {
            const video = document.getElementById('video');
            const statusKamera = document.getElementById('statusKamera');
            const kameraMatiOverlay = document.getElementById('kameraMatiOverlay');
            const btnAmbilFoto = document.getElementById('btnAmbilFoto');

            try {
                // Stop existing stream
                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                }

                statusKamera.innerText = 'Meminta akses kamera...';
                btnAmbilFoto.disabled = true;

                const constraints = {
                    video: {
                        facingMode: currentFacingMode,
                        width: {
                            ideal: 1280
                        },
                        height: {
                            ideal: 720
                        }
                    }
                };

                stream = await navigator.mediaDevices.getUserMedia(constraints);
                video.srcObject = stream;

                await video.play();

                statusKamera.innerText = 'Kamera siap';
                btnAmbilFoto.disabled = false;
                kameraMatiOverlay.classList.add('hidden');

            } catch (err) {
                console.error('Camera error:', err);
                statusKamera.innerText = 'Gagal mengakses kamera';
                btnAmbilFoto.disabled = true;
                kameraMatiOverlay.classList.remove('hidden');

                if (err.name === 'NotAllowedError') {
                    showNotification('error', 'Izin Kamera Ditolak', 'Mohon izinkan akses kamera');
                } else {
                    showNotification('error', 'Kamera Tidak Tersedia', 'Pastikan kamera terhubung');
                }
            }
        }

        // Stop Kamera
        function stopKamera() {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
                stream = null;
            }
        }

        // Ganti Kamera (depan/belakang)
        function gantiKamera() {
            currentFacingMode = currentFacingMode === 'environment' ? 'user' : 'environment';
            startKamera();
        }

        // Ambil Foto
        function ambilFoto() {
            const video = document.getElementById('video');
            const canvas = document.getElementById('canvas');
            const hasilFoto = document.getElementById('hasilFoto');
            const hasilFotoContainer = document.getElementById('hasilFotoContainer');

            // Set canvas size
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;

            // Draw video frame to canvas
            const context = canvas.getContext('2d');

            // Mirror effect untuk selfie
            if (currentFacingMode === 'user') {
                context.translate(canvas.width, 0);
                context.scale(-1, 1);
            }

            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            // Convert to image
            capturedImage = canvas.toDataURL('image/jpeg', 0.9);
            hasilFoto.src = capturedImage;

            // Show captured image
            video.classList.add('hidden');
            hasilFotoContainer.classList.remove('hidden');

            // Validate form
            validateForm();

            showNotification('success', 'Foto Berhasil', 'Silakan pilih barang yang dikembalikan');
        }

        // Ambil Ulang Foto
        function ambilUlangFoto() {
            const video = document.getElementById('video');
            const hasilFotoContainer = document.getElementById('hasilFotoContainer');

            video.classList.remove('hidden');
            hasilFotoContainer.classList.add('hidden');
            capturedImage = null;

            validateForm();
        }

        function kirimPengembalian() {
    if (!capturedImage || selectedItems.size === 0) {
        showNotification('error', 'Form Belum Lengkap', 'Ambil foto dan pilih barang terlebih dahulu');
        return;
    }
    
    // Show Kembalikan Manual
function showKembalikanManual(peminjamanId) {
    Swal.fire({
        title: 'Pengembalian Langsung',
        text: 'Apakah Anda yakin ingin mengembalikan alat secara langsung?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3b82f6',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Kembalikan',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Memproses...',
                text: 'Mohon tunggu',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
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
                        confirmButtonColor: '#10b981'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Gagal!',
                        text: data.message,
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

    document.getElementById('loadingOverlay').classList.remove('hidden');
    document.getElementById('loadingOverlay').classList.add('flex');
    
    const barangDikembalikan = Array.from(selectedItems).map(item => {
        const [peminjamanId, alatId] = item.split('-');
        return {
            peminjaman_id: parseInt(peminjamanId),
            alat_id: parseInt(alatId)
        };
    });
    
    const formData = new FormData();
    formData.append('foto', capturedImage);
    formData.append('barang', JSON.stringify(barangDikembalikan));
    
    fetch('/peminjam/pengembalian-mandiri', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('loadingOverlay').classList.add('hidden');
        
        if (data.success) {
            showNotification('success', 'Berhasil!', data.message || 'Foto bukti berhasil dikirim');
            closePengembalianMandiri();
            
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        } else {
            showNotification('error', 'Gagal!', data.message || 'Terjadi kesalahan');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('loadingOverlay').classList.add('hidden');
        showNotification('error', 'Error!', 'Gagal mengirim data');
    });
}

        // ============ NOTIFICATION ============
        function showNotification(type, title, message) {
            const notification = document.getElementById('notification');
            const icon = document.getElementById('notificationIcon');
            const titleEl = document.getElementById('notificationTitle');
            const messageEl = document.getElementById('notificationMessage');

            // Set warna dan icon sesuai type
            if (type === 'success') {
                notification.className =
                    'bg-white rounded-xl shadow-2xl border-l-4 border-green-500 p-4 min-w-[320px] flex items-start gap-3';
                icon.innerHTML = '<i class="fas fa-check-circle"></i>';
                icon.className = 'text-green-500 text-xl';
            } else if (type === 'error') {
                notification.className =
                    'bg-white rounded-xl shadow-2xl border-l-4 border-red-500 p-4 min-w-[320px] flex items-start gap-3';
                icon.innerHTML = '<i class="fas fa-exclamation-circle"></i>';
                icon.className = 'text-red-500 text-xl';
            }

            titleEl.innerText = title;
            messageEl.innerText = message;

            notification.classList.remove('hidden');

            // Auto close after 5 seconds
            setTimeout(() => {
                closeNotification();
            }, 5000);
        }

        function closeNotification() {
            document.getElementById('notification').classList.add('hidden');
        }

        // Close modal with ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closePengembalianMandiri();
                closeDetailModal();
            }
        });

        // Click outside to close
        document.addEventListener('click', function(e) {
            const modal = document.getElementById('pengembalianMandiriModal');
            if (e.target === modal) {
                closePengembalianMandiri();
            }
        });
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

        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 20px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 20px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Animations */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .animate-slide-in {
            animation: slideIn 0.3s ease-out;
        }

        /* Camera overlay */
        #video {
            transform: scaleX(-1);
            /* Mirror effect */
        }

        /* Item barang hover effect */
        .barang-item {
            transition: all 0.2s ease;
        }

        .barang-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
    </style>
@endsection
