@extends('layouts-petugas.petugas')

@section('title', 'KELOLA PEMINJAMAN')

@section('content')
    <div class="space-y-8 animate-slide-in">

        <!-- Filter Section dengan Export Button di kanan -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex flex-wrap gap-3">
                    <div class="relative w-64">
                        <input type="hidden" id="statusFilter" value="{{ request('status') }}">
                        <button type="button" id="statusTrigger"
                            class="w-full pl-4 pr-12 py-3.5 border-2 border-emerald-200 rounded-2xl 
                                   focus:ring-3 focus:ring-emerald-500/30 focus:border-emerald-500 
                                   transition-all duration-200 bg-white text-left text-slate-700 
                                   font-medium hover:border-emerald-300 shadow-sm flex items-center justify-between">
                            <span id="statusLabel">
                                @switch(request('status'))
                                    @case('menunggu')
                                        Menunggu Konfirmasi
                                    @break

                                    @case('dipinjam')
                                        Sedang Dipinjam
                                    @break

                                    @case('selesai')
                                        Selesai
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
                                <div class="option flex items-center gap-3 px-4 py-2.5 hover:bg-emerald-50 cursor-pointer"
                                    data-value="" data-label="Semua Status">
                                    <i class="fas fa-list text-slate-500 w-4"></i> Semua Status
                                </div>
                                <!-- Di blade petugas -->
                                <div class="option flex items-center gap-3 px-4 py-2.5 hover:bg-yellow-50 cursor-pointer"
                                    data-value="menunggu_peminjaman" data-label="Menunggu Peminjaman">
                                    <i class="fas fa-clock text-yellow-500 w-4"></i> Menunggu Peminjaman
                                    <span class="ml-auto bg-yellow-500 text-white text-xs px-2 py-1 rounded-full">
                                        {{ $counts['menunggu_peminjaman'] ?? 0 }}
                                    </span>
                                </div>

                                <div class="option flex items-center gap-3 px-4 py-2.5 hover:bg-blue-50 cursor-pointer"
                                    data-value="menunggu_pengembalian" data-label="Menunggu Pengembalian">
                                    <i class="fas fa-hourglass-half text-blue-500 w-4"></i> Menunggu Pengembalian
                                    <span class="ml-auto bg-blue-500 text-white text-xs px-2 py-1 rounded-full">
                                        {{ $counts['menunggu_pengembalian'] ?? 0 }}
                                    </span>
                                </div>
                                <div class="option flex items-center gap-3 px-4 py-2.5 hover:bg-green-50 cursor-pointer"
                                    data-value="dipinjam" data-label="Sedang Dipinjam">
                                    <i class="fas fa-hand-holding text-green-500 w-4"></i> Sedang Dipinjam
                                    <span class="ml-auto bg-green-500 text-white text-xs px-2 py-1 rounded-full">
                                        {{ $counts['dipinjam'] ?? 0 }}
                                    </span>
                                </div>
                                <div class="option flex items-center gap-3 px-4 py-2.5 hover:bg-purple-50 cursor-pointer"
                                    data-value="selesai" data-label="Selesai">
                                    <i class="fas fa-check-circle text-purple-500 w-4"></i> Selesai
                                </div>
                                <div class="option flex items-center gap-3 px-4 py-2.5 hover:bg-red-50 cursor-pointer"
                                    data-value="ditolak" data-label="Ditolak">
                                    <i class="fas fa-times-circle text-red-500 w-4"></i> Ditolak
                                </div>
                                <div class="option flex items-center gap-3 px-4 py-2.5 hover:bg-orange-50 cursor-pointer"
                                    data-value="ditegur" data-label="Ditegur">
                                    <i class="fas fa-exclamation-triangle text-orange-500 w-4"></i> Ditegur
                                </div>
                            </div>
                        </div>
                    </div>

                    <button onclick="resetFilter()"
                        class="px-5 py-3.5 border-2 border-slate-200 rounded-2xl hover:bg-slate-50 hover:border-slate-300 transition-all duration-200 flex items-center gap-3 group shadow-sm">
                        <i class="fas fa-redo text-slate-600 group-hover:rotate-180 transition-transform duration-500"></i>
                        <span class="font-medium text-slate-700">Reset</span>
                    </button>
                </div>

                <!-- Export Button dipindah ke kanan -->
                <button onclick="showExportModal()"
                    class="px-5 py-3.5 bg-emerald-600 text-white rounded-2xl hover:bg-emerald-700 transition-all duration-200 flex items-center gap-3 group shadow-sm">
                    <i class="fas fa-file-export group-hover:translate-x-1 transition-transform"></i>
                    <span class="font-medium">Export Data</span>
                </button>
            </div>
        </div>

        <!-- Di blade petugas -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Card Menunggu Peminjaman -->
            <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 border border-yellow-200 rounded-2xl p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-yellow-800 font-medium">Menunggu Peminjaman</p>
                        <p class="text-2xl font-bold text-yellow-900 mt-1">{{ $counts['menunggu_peminjaman'] ?? 0 }}</p>
                        <p class="text-xs text-yellow-600 mt-1">Butuh persetujuan</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-yellow-500/20 flex items-center justify-center">
                        <i class="fas fa-clock text-yellow-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Card Menunggu Pengembalian -->
            <div class="bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 rounded-2xl p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-blue-800 font-medium">Menunggu Pengembalian</p>
                        <p class="text-2xl font-bold text-blue-900 mt-1">{{ $counts['menunggu_pengembalian'] ?? 0 }}</p>
                        <p class="text-xs text-blue-600 mt-1">Butuh konfirmasi</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-blue-500/20 flex items-center justify-center">
                        <i class="fas fa-hourglass-half text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Card Dipinjam -->
            <div class="bg-gradient-to-r from-green-50 to-green-100 border border-green-200 rounded-2xl p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-green-800 font-medium">Dipinjam</p>
                        <p class="text-2xl font-bold text-green-900 mt-1">{{ $counts['dipinjam'] ?? 0 }}</p>
                        <p class="text-xs text-green-600 mt-1">Belum dikembalikan</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-green-500/20 flex items-center justify-center">
                        <i class="fas fa-hand-holding text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Card Selesai -->
            <div class="bg-gradient-to-r from-purple-50 to-purple-100 border border-purple-200 rounded-2xl p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-purple-800 font-medium">Selesai</p>
                        <p class="text-2xl font-bold text-purple-900 mt-1">{{ $counts['selesai'] ?? 0 }}</p>
                        <p class="text-xs text-purple-600 mt-1">Sudah dikembalikan</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-purple-500/20 flex items-center justify-center">
                        <i class="fas fa-check-circle text-purple-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-emerald-50 border-b border-emerald-200">
                            <th class="text-left py-4 px-6 text-sm font-semibold text-emerald-800">Peminjam</th>
                            <th class="text-left py-4 px-6 text-sm font-semibold text-emerald-800">Alat</th>
                            <th class="text-left py-4 px-6 text-sm font-semibold text-emerald-800">Tanggal</th>
                            <th class="text-left py-4 px-6 text-sm font-semibold text-emerald-800">Status</th>
                            <th class="text-left py-4 px-6 text-sm font-semibold text-emerald-800">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($peminjamans as $peminjaman)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center">
                                            <i class="fas fa-user text-emerald-600"></i>
                                        </div>
                                        <div>
                                            <div class="font-medium text-slate-800">
                                                {{ $peminjaman->user->name ?? 'Tidak diketahui' }}</div>
                                            <div class="text-xs text-slate-500">{{ $peminjaman->user->email ?? '-' }}</div>
                                            <div class="text-xs text-slate-500">Kelas:
                                                {{ $peminjaman->user->kelas ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="max-w-xs">
                                        <div class="font-medium text-slate-800 mb-1 flex items-center gap-2">
                                            <i class="fas fa-tools text-emerald-500 text-sm"></i>
                                            {{ $peminjaman->nama_alat }}
                                        </div>
                                        <div class="text-xs text-slate-500">
                                            <i class="fas fa-boxes text-slate-400 mr-1"></i>
                                            {{ count($peminjaman->alat_ids ?? []) }} alat
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-calendar-alt text-blue-500 text-xs"></i>
                                            <span
                                                class="text-sm text-slate-700">{{ $peminjaman->tanggal_peminjaman->format('d/m/Y') }}</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-calendar-check text-green-500 text-xs"></i>
                                            <span
                                                class="text-sm text-slate-700">{{ $peminjaman->tanggal_pengembalian->format('d/m/Y') }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    @php
                                        $statusColors = [
                                            'menunggu_peminjaman' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                            'menunggu_pengembalian' => 'bg-blue-100 text-blue-800 border-blue-200',
                                            'dipinjam' => 'bg-green-100 text-green-800 border-green-200',
                                            'selesai' => 'bg-purple-100 text-purple-800 border-purple-200',
                                            'ditolak' => 'bg-red-100 text-red-800 border-red-200',
                                            'ditegur' => 'bg-orange-100 text-orange-800 border-orange-200',
                                        ];
                                        $statusIcons = [
                                            'menunggu_peminjaman' => 'clock',
                                            'menunggu_pengembalian' => 'hourglass-half',
                                            'dipinjam' => 'hand-holding',
                                            'selesai' => 'check-circle',
                                            'ditolak' => 'times-circle',
                                            'ditegur' => 'exclamation-triangle',
                                        ];
                                    @endphp
                                    <span
                                        class="px-2 py-1 rounded-full text-xs font-medium border {{ $statusColors[$peminjaman->status] ?? 'bg-gray-100 text-gray-800 border-gray-200' }}">
                                        {{ ucfirst(str_replace('_', ' ', $peminjaman->status)) }}
                                    </span>
                                    @if ($peminjaman->status == 'selesai' && $peminjaman->metode_pengembalian)
                                        <div class="text-xs text-slate-500 mt-1">
                                            Metode: {{ ucfirst($peminjaman->metode_pengembalian) }}
                                        </div>
                                    @endif
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex flex-wrap gap-2">
                                        <!-- Detail Button -->
                                        <button onclick="showDetailModal({{ $peminjaman->id }})"
                                            class="px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition flex items-center gap-1 text-sm"
                                            title="Detail">
                                            <i class="fas fa-eye text-xs"></i>
                                            <span class="hidden md:inline">Detail</span>
                                        </button>

                                        <!-- Di bagian action buttons -->
                                        @if ($peminjaman->status == 'menunggu_peminjaman')
                                            <!-- Tombol Setujui -->
                                            <button onclick="konfirmasiSetujui({{ $peminjaman->id }})"
                                                class="px-3 py-1.5 bg-emerald-50 text-emerald-700 rounded-lg hover:bg-emerald-100 transition flex items-center gap-1 text-sm"
                                                title="Setujui">
                                                <i class="fas fa-check text-xs"></i>
                                                <span class="hidden md:inline">Setujui</span>
                                            </button>

                                            <!-- Tombol Tolak -->
                                            <button onclick="konfirmasiTolak({{ $peminjaman->id }})"
                                                class="px-3 py-1.5 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 transition flex items-center gap-1 text-sm"
                                                title="Tolak">
                                                <i class="fas fa-times text-xs"></i>
                                                <span class="hidden md:inline">Tolak</span>
                                            </button>
                                        @endif

                                        @if ($peminjaman->status == 'dipinjam')
                                            <!-- Tombol Konfirmasi Pengembalian (langsung selesai) -->
                                            <button onclick="konfirmasiPengembalian({{ $peminjaman->id }})"
                                                class="px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition flex items-center gap-1 text-sm"
                                                title="Konfirmasi Pengembalian">
                                                <i class="fas fa-check-circle text-xs"></i>
                                                <span class="hidden md:inline">Konfirmasi</span>
                                            </button>

                                            <!-- Tombol Tegur (opsional) -->
                                            <button onclick="showTeguranModal({{ $peminjaman->id }})"
                                                class="px-3 py-1.5 bg-orange-50 text-orange-700 rounded-lg hover:bg-orange-100 transition flex items-center gap-1 text-sm"
                                                title="Beri Teguran">
                                                <i class="fas fa-exclamation-triangle text-xs"></i>
                                                <span class="hidden md:inline">Tegur</span>
                                            </button>
                                        @endif

                                        @if ($peminjaman->status == 'selesai' && $peminjaman->foto_bukti)
                                            <button onclick="showFotoModal('{{ $peminjaman->foto_bukti_url }}')"
                                                class="px-3 py-1.5 bg-purple-50 text-purple-700 rounded-lg hover:bg-purple-100 transition flex items-center gap-1 text-sm"
                                                title="Lihat Foto">
                                                <i class="fas fa-image text-xs"></i>
                                                <span class="hidden md:inline">Foto</span>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 px-6 text-center">
                                    <div class="text-slate-400 mb-4">
                                        <i class="fas fa-clipboard-list text-6xl"></i>
                                    </div>
                                    <h3 class="text-xl font-bold text-slate-700 mb-2">Belum Ada Peminjaman</h3>
                                    <p class="text-slate-500">Tidak ada peminjaman yang perlu dikelola saat ini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile Cards -->
            <div class="md:hidden">
                <div class="p-4 space-y-4">
                    @forelse($peminjamans as $peminjaman)
                        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-4">
                            <!-- Card Header -->
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center">
                                        <i class="fas fa-user text-emerald-600"></i>
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-800">
                                            {{ $peminjaman->user->name ?? 'Tidak diketahui' }}</div>
                                        <div class="text-xs text-slate-500">{{ $peminjaman->user->kelas ?? '-' }}</div>
                                    </div>
                                </div>
                                @php
                                    $statusColors = [
                                        'menunggu_peminjaman' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                        'menunggu_pengembalian' => 'bg-blue-100 text-blue-800 border-blue-200',
                                        'dipinjam' => 'bg-green-100 text-green-800 border-green-200',
                                        'selesai' => 'bg-purple-100 text-purple-800 border-purple-200',
                                        'ditolak' => 'bg-red-100 text-red-800 border-red-200',
                                        'ditegur' => 'bg-orange-100 text-orange-800 border-orange-200',
                                    ];
                                @endphp
                                <span
                                    class="px-2 py-1 rounded-full text-xs font-medium border {{ $statusColors[$peminjaman->status] ?? 'bg-gray-100 text-gray-800 border-gray-200' }}">
                                    {{ ucfirst(str_replace('_', ' ', $peminjaman->status)) }}
                                </span>
                            </div>

                            <!-- Card Content -->
                            <div class="space-y-3">
                                <!-- Alat -->
                                <div>
                                    <div class="text-sm font-medium text-slate-700 mb-1">Alat yang Dipinjam</div>
                                    <div class="text-slate-800">{{ $peminjaman->nama_alat }}</div>
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
                                                class="text-sm">{{ $peminjaman->tanggal_peminjaman->format('d/m/Y') }}</span>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-xs text-slate-500 mb-1">Tanggal Kembali</div>
                                        <div class="flex items-center gap-1">
                                            <i class="fas fa-calendar-check text-green-500 text-xs"></i>
                                            <span
                                                class="text-sm">{{ $peminjaman->tanggal_pengembalian->format('d/m/Y') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex flex-wrap gap-2 pt-3 border-t border-slate-100">
                                    <button onclick="showDetailModal({{ $peminjaman->id }})"
                                        class="flex-1 px-3 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition text-sm">
                                        <i class="fas fa-eye mr-1"></i> Detail
                                    </button>


                                    @if ($peminjaman->status == 'menunggu_peminjaman')
                                        <!-- Tombol Setujui -->
                                        <button onclick="konfirmasiAksi({{ $peminjaman->id }}, 'setujui')"
                                            class="flex-1 px-3 py-2 bg-emerald-50 text-emerald-700 rounded-lg hover:bg-emerald-100 transition text-sm">
                                            <i class="fas fa-check mr-1"></i> Setujui
                                        </button>

                                        <!-- Tombol Tolak -->
                                        <button onclick="konfirmasiAksi({{ $peminjaman->id }}, 'tolak')"
                                            class="flex-1 px-3 py-2 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 transition text-sm">
                                            <i class="fas fa-times mr-1"></i> Tolak
                                        </button>
                                    @endif

                                    @if ($peminjaman->status == 'menunggu_pengembalian')
                                        <button onclick="showKonfirmasiPengembalianModal({{ $peminjaman->id }})"
                                            class="flex-1 px-3 py-2 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition text-sm">
                                            <i class="fas fa-check-circle mr-1"></i> Konfirmasi
                                        </button>
                                    @endif

                                    @if ($peminjaman->status == 'dipinjam')
                                        <button onclick="konfirmasiLangsungKembali({{ $peminjaman->id }})"
                                            class="flex-1 px-3 py-2 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition text-sm">
                                            <i class="fas fa-check mr-1"></i> Kembalikan
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <div class="text-slate-400 mb-4">
                                <i class="fas fa-clipboard-list text-5xl"></i>
                            </div>
                            <h3 class="text-lg font-bold text-slate-700 mb-2">Belum Ada Peminjaman</h3>
                            <p class="text-slate-500 text-sm">Tidak ada peminjaman yang perlu dikelola.</p>
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
                                class="px-3 py-1.5 rounded-lg bg-emerald-100 text-emerald-600 hover:bg-emerald-200 transition">
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
                                      {{ $i == $current ? 'bg-emerald-500 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                                {{ $i }}
                            </a>
                        @endfor

                        <!-- Next -->
                        @if ($peminjamans->hasMorePages())
                            <a href="{{ $peminjamans->nextPageUrl() . (request('status') ? '&status=' . request('status') : '') }}"
                                class="px-3 py-1.5 rounded-lg bg-emerald-100 text-emerald-600 hover:bg-emerald-200 transition">
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

    <!-- Modals -->
    <!-- Modal Detail -->
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
                    class="flex-1 px-4 py-3 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi -->
    <div id="konfirmasiModal" class="fixed inset-0 bg-black/50 z-[60] hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md animate-slide-up">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-slate-800" id="konfirmasiTitle"></h3>
                    <button onclick="closeKonfirmasiModal()" class="text-slate-400 hover:text-slate-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <p class="text-slate-600 mb-6" id="konfirmasiMessage"></p>
                <div class="flex gap-3">
                    <button type="button" onclick="closeKonfirmasiModal()"
                        class="flex-1 px-4 py-3 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 transition">
                        Batal
                    </button>
                    <button type="button" id="konfirmasiActionBtn"
                        class="flex-1 px-4 py-3 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition">
                        Konfirmasi
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Verifikasi Foto -->
    <div id="verifikasiModal" class="fixed inset-0 bg-black/50 z-[60] hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl animate-slide-up">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-slate-800">Verifikasi Pengembalian Mandiri</h3>
                    <button onclick="closeVerifikasiModal()" class="text-slate-400 hover:text-slate-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <!-- Foto Bukti -->
                <div class="mb-6">
                    <h4 class="font-medium text-slate-700 mb-3">Foto Bukti Pengembalian:</h4>
                    <div class="text-center p-8 border-2 border-dashed border-slate-200 rounded-xl" id="fotoLoading">
                        <i class="fas fa-spinner fa-spin text-slate-400 text-2xl mb-2"></i>
                        <p class="text-slate-500">Memuat foto...</p>
                    </div>
                    <div id="fotoPreview" class="hidden">
                        <!-- Foto akan dimuat disini -->
                    </div>
                    <div id="noFotoMessage"
                        class="hidden text-center p-8 border-2 border-dashed border-red-200 rounded-xl">
                        <i class="fas fa-camera-slash text-red-400 text-2xl mb-2"></i>
                        <p class="text-red-500">Belum ada foto bukti</p>
                    </div>
                </div>

                <!-- Status Kondisi -->
                <div class="mb-6">
                    <h4 class="font-medium text-slate-700 mb-3">Status Kondisi Alat:</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label
                                class="flex items-center gap-3 p-4 border-2 border-emerald-200 rounded-xl cursor-pointer hover:bg-emerald-50 transition">
                                <input type="radio" name="kondisi" value="baik" class="text-emerald-600" checked>
                                <div>
                                    <div class="font-medium text-emerald-700">Baik</div>
                                    <div class="text-sm text-slate-500">Alat dalam kondisi baik</div>
                                </div>
                            </label>
                        </div>
                        <div>
                            <label
                                class="flex items-center gap-3 p-4 border-2 border-red-200 rounded-xl cursor-pointer hover:bg-red-50 transition">
                                <input type="radio" name="kondisi" value="rusak" class="text-red-600">
                                <div>
                                    <div class="font-medium text-red-700">Rusak</div>
                                    <div class="text-sm text-slate-500">Alat mengalami kerusakan</div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Catatan -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Catatan (Opsional)</label>
                    <textarea id="verifikasiCatatan" rows="3"
                        class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-emerald-500 focus:ring-3 focus:ring-emerald-500/30 transition"
                        placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="closeVerifikasiModal()"
                        class="flex-1 px-4 py-3 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 transition">
                        Batal
                    </button>
                    <button type="button" onclick="konfirmasiVerifikasi()"
                        class="flex-1 px-4 py-3 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition">
                        Verifikasi & Selesaikan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Teguran (Sederhana) -->
    <div id="teguranModal" class="fixed inset-0 bg-black/50 z-[60] hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md animate-slide-up">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-slate-800">Beri Teguran</h3>
                    <button onclick="closeTeguranModal()" class="text-slate-400 hover:text-slate-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <!-- Pilihan Alasan Teguran -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Pilih Alasan Teguran</label>
                    <select id="alasanTeguran"
                        class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-orange-500 focus:ring-3 focus:ring-orange-500/30 transition mb-3"
                        onchange="updateTeguranText()">
                        <option value="">Pilih alasan...</option>
                        <option value="foto_tidak_jelas">Foto tidak jelas</option>
                        <option value="foto_tidak_sesuai">Foto tidak sesuai kondisi alat</option>
                        <option value="alat_rusak">Alat dikembalikan dalam kondisi rusak</option>
                        <option value="terlambat">Terlambat mengembalikan</option>
                        <option value="tidak_lengkap">Alat tidak lengkap</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                </div>

                <!-- Textarea untuk edit teks teguran -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Teks Teguran</label>
                    <textarea id="teksTeguran" rows="4"
                        class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-orange-500 focus:ring-3 focus:ring-orange-500/30 transition"
                        placeholder="Teks teguran akan muncul disini..."></textarea>
                    <div class="text-xs text-slate-500 mt-1">Anda bisa mengedit teks ini</div>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="closeTeguranModal()"
                        class="flex-1 px-4 py-3 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 transition">
                        Batal
                    </button>
                    <button type="button" onclick="kirimTeguran()"
                        class="flex-1 px-4 py-3 bg-orange-600 text-white rounded-xl hover:bg-orange-700 transition">
                        Kirim Teguran
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Foto -->
    <div id="fotoModal" class="fixed inset-0 bg-black/70 z-[70] hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl animate-slide-up">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-slate-800">Foto Bukti Pengembalian</h3>
                    <button onclick="closeFotoModal()" class="text-slate-400 hover:text-slate-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="rounded-xl overflow-hidden border-2 border-slate-200">
                    <img id="fotoModalImage" src="" alt="Foto bukti"
                        class="w-full h-auto max-h-[70vh] object-contain">
                </div>
                <div class="mt-4 text-center">
                    <button onclick="closeFotoModal()"
                        class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Export -->
    <div id="exportModal" class="fixed inset-0 bg-black/50 z-[60] hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md animate-slide-up">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-slate-800">Export Data Peminjaman</h3>
                    <button onclick="closeExportModal()" class="text-slate-400 hover:text-slate-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <form id="exportForm">
                    <!-- Filter Status -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Status</label>
                        <select name="status"
                            class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-emerald-500 focus:ring-3 focus:ring-emerald-500/30 transition">
                            <option value="">Semua Status</option>
                            <option value="menunggu">Menunggu</option>
                            <option value="dipinjam">Dipinjam</option>
                            <option value="selesai">Selesai</option>
                            <option value="ditolak">Ditolak</option>
                            <option value="ditegur">Ditegur</option>
                        </select>
                    </div>

                    <!-- Tanggal Awal -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Tanggal Awal</label>
                        <input type="date" name="start_date"
                            class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-emerald-500 focus:ring-3 focus:ring-emerald-500/30 transition">
                    </div>

                    <!-- Tanggal Akhir -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Tanggal Akhir</label>
                        <input type="date" name="end_date"
                            class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-emerald-500 focus:ring-3 focus:ring-emerald-500/30 transition">
                    </div>

                    <div class="flex gap-3">
                        <button type="button" onclick="closeExportModal()"
                            class="flex-1 px-4 py-3 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 transition">
                            Batal
                        </button>
                        <button type="submit"
                            class="flex-1 px-4 py-3 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition">
                            Export Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Variables untuk menyimpan ID peminjaman
        let currentPeminjamanId = null;
        let currentAction = null;

        function konfirmasiSetujui(peminjamanId) {
            Swal.fire({
                title: 'Setujui Peminjaman',
                text: 'Apakah Anda yakin ingin menyetujui peminjaman ini? Stok alat akan berkurang.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Setujui',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/petugas/kelolapeminjaman/${peminjamanId}/setujui`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: data.message,
                                    icon: 'success'
                                }).then(() => location.reload());
                            } else {
                                Swal.fire({
                                    title: 'Gagal!',
                                    text: data.message,
                                    icon: 'error'
                                });
                            }
                        });
                }
            });
        }


function konfirmasiTolak(peminjamanId) {
    Swal.fire({
        title: 'Tolak Peminjaman',
        input: 'textarea',
        inputLabel: 'Alasan Penolakan',
        inputPlaceholder: 'Masukkan alasan penolakan...',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Tolak',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed && result.value) {
            fetch(`/petugas/kelolapeminjaman/${peminjamanId}/tolak`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ alasan: result.value })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: data.message,
                        icon: 'success'
                    }).then(() => location.reload());
                } else {
                    Swal.fire({
                        title: 'Gagal!',
                        text: data.message,
                        icon: 'error'
                    });
                }
            });
        }
    });
}
        function showSetujuiModal(peminjamanId) {
            Swal.fire({
                title: 'Setujui Peminjaman',
                text: 'Apakah Anda yakin ingin menyetujui peminjaman ini? Stok alat akan otomatis berkurang.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Setujui',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Gunakan route name yang benar
                    fetch(`/petugas/kelolapeminjaman/${peminjamanId}/setujui`, {
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
                                    confirmButtonColor: '#10b981',
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: 'Gagal!',
                                    text: data.message || 'Gagal menyetujui peminjaman',
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

        function showTolakModal(peminjamanId) {
            Swal.fire({
                title: 'Tolak Peminjaman',
                input: 'textarea',
                inputLabel: 'Alasan Penolakan',
                inputPlaceholder: 'Masukkan alasan penolakan...',
                inputAttributes: {
                    maxlength: 500
                },
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Tolak',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                inputValidator: (value) => {
                    if (!value || value.trim() === '') {
                        return 'Alasan penolakan harus diisi!';
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Gunakan route name yang benar
                    fetch(`/petugas/kelolapeminjaman/${peminjamanId}/tolak`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                alasan: result.value
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: data.message,
                                    icon: 'success',
                                    confirmButtonColor: '#10b981',
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: 'Gagal!',
                                    text: data.message || 'Gagal menolak peminjaman',
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

        function konfirmasiPengembalian(peminjamanId) {
    Swal.fire({
        title: 'Konfirmasi Pengembalian',
        text: 'Apakah alat sudah dikembalikan? Peminjaman akan langsung selesai dan stok alat bertambah.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3b82f6',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Konfirmasi',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/petugas/kelolapeminjaman/${peminjamanId}/konfirmasi-pengembalian`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: data.message,
                        icon: 'success'
                    }).then(() => location.reload());
                } else {
                    Swal.fire({
                        title: 'Gagal!',
                        text: data.message,
                        icon: 'error'
                    });
                }
            });
        }
    });
}

        // Filter Dropdown
        document.addEventListener('DOMContentLoaded', function() {
            const trigger = document.getElementById('statusTrigger');
            const dropdown = document.getElementById('statusOptions');
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
            window.location.href = "{{ route('petugas.kelolapeminjaman.index') }}";
        }

        // Modal Functions
        function showModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = 'auto';
        }

        // Detail Modal - PERBAIKAN ROUTE
        function showDetailModal(peminjamanId) {
            fetch(`/petugas/kelolapeminjaman/${peminjamanId}/detail`, {
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    const modalContent = createDetailContent(data);
                    document.getElementById('detailContent').innerHTML = modalContent;
                    showModal('detailModal');
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Gagal memuat data detail');
                });
        }


        function closeDetailModal() {
            closeModal('detailModal');
        }

        // Konfirmasi Modal
        function showKonfirmasiModal(peminjamanId, action) {
            currentPeminjamanId = peminjamanId;
            currentAction = action;

            const modal = document.getElementById('konfirmasiModal');
            const title = document.getElementById('konfirmasiTitle');
            const message = document.getElementById('konfirmasiMessage');
            const actionBtn = document.getElementById('konfirmasiActionBtn');

            if (action === 'setuju') {
                title.textContent = 'Konfirmasi Persetujuan';
                message.textContent = 'Apakah Anda yakin ingin menyetujui peminjaman ini?';
                actionBtn.textContent = 'Setujui';
                actionBtn.className =
                    'flex-1 px-4 py-3 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition';
            } else {
                title.textContent = 'Konfirmasi Penolakan';
                message.textContent = 'Apakah Anda yakin ingin menolak peminjaman ini?';
                actionBtn.textContent = 'Tolak';
                actionBtn.className = 'flex-1 px-4 py-3 bg-red-600 text-white rounded-xl hover:bg-red-700 transition';
            }

            actionBtn.onclick = () => prosesKonfirmasi(action);
            showModal('konfirmasiModal');
        }

        function closeKonfirmasiModal() {
            closeModal('konfirmasiModal');
        }

        function konfirmasiAksi(peminjamanId, aksi) {
            if (aksi === 'setujui') {
                showSetujuiModal(peminjamanId);
            } else if (aksi === 'tolak') {
                showTolakModal(peminjamanId);
            }
        }

        // Verifikasi Modal
        function showVerifikasiModal(peminjamanId) {
            currentPeminjamanId = peminjamanId;
            loadFotoBukti(peminjamanId);
            showModal('verifikasiModal');
        }

        function closeVerifikasiModal() {
            closeModal('verifikasiModal');
        }

        // Teguran Modal
        function showTeguranModal(peminjamanId) {
            currentPeminjamanId = peminjamanId;
            document.getElementById('teksTeguran').value = '';
            showModal('teguranModal');
        }

        function closeTeguranModal() {
            closeModal('teguranModal');
        }

        // Foto Modal
        function showFotoModal(fotoUrl) {
            document.getElementById('fotoModalImage').src = fotoUrl;
            showModal('fotoModal');
        }

        function closeFotoModal() {
            closeModal('fotoModal');
        }

        // Export Modal
        function showExportModal() {
            showModal('exportModal');
        }

        function closeExportModal() {
            closeModal('exportModal');
        }

        // Update teks teguran berdasarkan pilihan
        function updateTeguranText() {
            const select = document.getElementById('alasanTeguran');
            const textarea = document.getElementById('teksTeguran');

            const teksTeguran = {
                'foto_tidak_jelas': 'Foto tidak jelas - tidak dapat memverifikasi kondisi alat',
                'foto_tidak_sesuai': 'Foto tidak sesuai dengan kondisi alat sebenarnya',
                'alat_rusak': 'Alat dikembalikan dalam kondisi rusak',
                'terlambat': 'Terlambat mengembalikan alat',
                'tidak_lengkap': 'Alat tidak lengkap saat dikembalikan',
                'lainnya': ''
            };

            textarea.value = teksTeguran[select.value] || '';
        }

        // Helper Functions
        function createDetailContent(data) {
            let fotoSection = '';
            if (data.foto_bukti) {
                fotoSection = `
                    <div>
                        <h4 class="font-bold text-slate-800 mb-3 flex items-center gap-2">
                            <i class="fas fa-camera text-emerald-500"></i>
                            Foto Bukti Pengembalian
                        </h4>
                        <div class="bg-slate-50 p-4 rounded-xl">
                            <img src="${data.foto_bukti}" alt="Foto bukti" 
                                 class="w-full h-48 object-contain rounded-lg mb-2 cursor-pointer"
                                 onclick="showFotoModal('${data.foto_bukti}')">
                            <div class="text-sm text-slate-500 text-center">
                                Foto diupload ${data.waktu_pengembalian ? 'pada ' + data.waktu_pengembalian : ''}
                            </div>
                        </div>
                    </div>
                `;
            }

            let metodeSection = '';
            if (data.metode_pengembalian && data.status === 'selesai') {
                metodeSection = `
                    <div class="bg-slate-50 p-4 rounded-xl">
                        <div class="text-sm text-slate-500 mb-1">Metode Pengembalian</div>
                        <div class="font-medium text-slate-800">${data.metode_pengembalian.charAt(0).toUpperCase() + data.metode_pengembalian.slice(1)}</div>
                    </div>
                `;
            }

            return `
                <div class="space-y-6">
                    <!-- Informasi Peminjam -->
                    <div>
                        <h4 class="font-bold text-slate-800 mb-3 flex items-center gap-2">
                            <i class="fas fa-user text-emerald-500"></i>
                            Informasi Peminjam
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            ${createInfoCard('Nama', data.user.name)}
                            ${createInfoCard('Kelas', data.user.kelas)}
                            ${createInfoCard('Email', data.user.email)}
                            ${createInfoCard('Telepon', data.user.phone || '-')}
                        </div>
                    </div>

                    <!-- Informasi Peminjaman -->
                    <div>
                        <h4 class="font-bold text-slate-800 mb-3 flex items-center gap-2">
                            <i class="fas fa-info-circle text-emerald-500"></i>
                            Informasi Peminjaman
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            ${createInfoCard('Kode', '#' + data.id)}
                            ${createInfoCard('Status', data.status.charAt(0).toUpperCase() + data.status.slice(1))}
                            ${createInfoCard('Tanggal Pengajuan', data.created_at)}
                            ${createInfoCard('Tanggal Pinjam', data.tanggal_peminjaman)}
                            ${createInfoCard('Tanggal Kembali', data.tanggal_pengembalian)}
                            ${createInfoCard('Tanggal Dikembalikan', data.tanggal_dikembalikan || '-')}
                            ${metodeSection}
                        </div>
                    </div>

                    <!-- Alat yang Dipinjam -->
                    <div>
                        <h4 class="font-bold text-slate-800 mb-3 flex items-center gap-2">
                            <i class="fas fa-tools text-emerald-500"></i>
                            Alat yang Dipinjam
                        </h4>
                        <div class="space-y-3">
                            ${data.alat.map(alat => `
                                                            <div class="bg-slate-50 p-4 rounded-xl">
                                                                <div class="flex flex-col md:flex-row md:items-center justify-between mb-2">
                                                                    <div>
                                                                        <div class="font-medium text-slate-800">${alat.nama}</div>
                                                                        <div class="text-sm text-slate-500">Kode: ${alat.kode}</div>
                                                                    </div>
                                                                    <div class="flex items-center gap-2 mt-2 md:mt-0">
                                                                        <span class="px-2 py-1 ${alat.kondisi === 'baik' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'} rounded text-xs">
                                                                            ${alat.kondisi}
                                                                        </span>
                                                                        <span class="text-sm text-slate-500">Stok: ${alat.stok}</span>
                                                                    </div>
                                                                </div>
                                                                <div class="text-sm text-slate-500">Lokasi: ${alat.lokasi}</div>
                                                            </div>
                                                        `).join('')}
                        </div>
                    </div>

                    <!-- Keterangan -->
                    <div>
                        <h4 class="font-bold text-slate-800 mb-3 flex items-center gap-2">
                            <i class="fas fa-sticky-note text-emerald-500"></i>
                            Keterangan
                        </h4>
                        <div class="bg-slate-50 p-4 rounded-xl">
                            <div class="text-slate-800 whitespace-pre-line">${data.keterangan || 'Tidak ada catatan'}</div>
                        </div>
                    </div>

                    ${fotoSection}
                </div>
            `;
        }

        function createInfoCard(label, value) {
            return `
                <div class="bg-slate-50 p-4 rounded-xl">
                    <div class="text-sm text-slate-500 mb-1">${label}</div>
                    <div class="font-medium text-slate-800">${value}</div>
                </div>
            `;
        }

        function loadFotoBukti(peminjamanId) {
            const fotoLoading = document.getElementById('fotoLoading');
            const fotoPreview = document.getElementById('fotoPreview');
            const noFotoMessage = document.getElementById('noFotoMessage');

            fotoLoading.classList.remove('hidden');
            fotoPreview.classList.add('hidden');
            noFotoMessage.classList.add('hidden');

            // Ganti dengan URL langsung
            fetch(`/petugas/kelolapeminjaman/${peminjamanId}/detail`, {
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    fotoLoading.classList.add('hidden');

                    if (data.foto_bukti) {
                        fotoPreview.innerHTML = `
                    <div class="relative rounded-xl overflow-hidden border-2 border-emerald-200">
                        <img src="${data.foto_bukti}" alt="Foto bukti pengembalian" 
                             class="w-full h-64 object-contain bg-slate-50 cursor-pointer"
                             onclick="showFotoModal('${data.foto_bukti}')">
                        <div class="absolute bottom-0 left-0 right-0 bg-black/60 text-white text-xs p-2">
                            Klik untuk memperbesar
                        </div>
                    </div>
                    <div class="text-xs text-slate-500 mt-2 text-center">
                        Foto diupload oleh peminjam
                    </div>
                `;
                        fotoPreview.classList.remove('hidden');
                    } else {
                        noFotoMessage.classList.remove('hidden');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    fotoLoading.classList.add('hidden');
                    noFotoMessage.classList.remove('hidden');
                });
        }


        // Ganti fungsi prosesKonfirmasi dengan ini:
        function prosesKonfirmasi(action) {
            let formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('action', action);

            // Jika tolak, tambahkan alasan
            if (action === 'tolak') {
                const alasan = prompt('Masukkan alasan penolakan:');
                if (!alasan || alasan.trim() === '') {
                    alert('Alasan penolakan harus diisi');
                    return;
                }
                formData.append('alasan', alasan);
            }

            // Ganti dengan route name
            fetch(`/petugas/kelolapeminjaman/${currentPeminjamanId}/konfirmasi`, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: data.message,
                            icon: 'success',
                            confirmButtonColor: '#10b981',
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

            closeKonfirmasiModal();
        }

        function konfirmasiVerifikasi() {
            const kondisi = document.querySelector('input[name="kondisi"]:checked').value;
            const catatan = document.getElementById('verifikasiCatatan').value;

            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('kondisi', kondisi);
            formData.append('catatan', catatan);

            // Ganti dengan URL langsung
            fetch(`/petugas/kelolapeminjaman/${currentPeminjamanId}/verifikasi`, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Gagal memverifikasi');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan');
                });

            closeVerifikasiModal();
        }

        function konfirmasiLangsungKembali(peminjamanId) {
            if (confirm('Konfirmasi pengembalian langsung? Peminjaman akan diselesaikan.')) {
                // Ganti dengan URL langsung
                fetch(`/petugas/kelolapeminjaman/${peminjamanId}/langsung-kembali`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Gagal mengkonfirmasi pengembalian');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan');
                    });
            }
        }

        function kirimTeguran() {
            const alasan = document.getElementById('alasanTeguran').value;
            const deskripsi = document.getElementById('teksTeguran').value;

            if (!alasan || !deskripsi) {
                alert('Harap pilih alasan dan isi teks teguran');
                return;
            }

            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('alasan', alasan);
            formData.append('deskripsi', deskripsi);

            // Ganti dengan URL langsung
            fetch(`/petugas/kelolapeminjaman/${currentPeminjamanId}/tegur`, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Gagal mengirim teguran');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan');
                });

            closeTeguranModal();
        }

        // Export Form - Ini yang masih bisa pakai route()
        document.getElementById('exportForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            // Ini bisa pakai route() karena tidak perlu parameter
            fetch('{{ route('petugas.kelolapeminjaman.export') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(`Berhasil mengekspor ${data.count} data`);
                        closeExportModal();
                        // Download file bisa ditambahkan disini
                    } else {
                        alert('Gagal mengekspor data');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan');
                });
        });
        // Close modal dengan ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDetailModal();
                closeKonfirmasiModal();
                closeVerifikasiModal();
                closeTeguranModal();
                closeFotoModal();
                closeExportModal();
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

        /* Custom scrollbar for modals */
        .modal-content::-webkit-scrollbar {
            width: 8px;
        }

        .modal-content::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        .modal-content::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .modal-content::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
@endsection
