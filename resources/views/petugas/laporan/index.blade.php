{{-- resources/views/petugas/laporan/index.blade.php --}}
@extends('layouts-petugas.petugas')

@section('title', 'LAPORAN PEMINJAMAN')

@section('content')
<div class="space-y-8 animate-slide-in">
    <!-- Filter Section -->
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <h2 class="text-xl font-bold text-slate-800 mb-4">Filter Laporan</h2>
        <form id="filterForm" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Periode -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Periode</label>
                    <select name="period" class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-emerald-500 focus:ring-3 focus:ring-emerald-500/30 transition">
                        <option value="daily" {{ request('period') == 'daily' ? 'selected' : '' }}>Harian</option>
                        <option value="weekly" {{ request('period') == 'weekly' ? 'selected' : '' }}>Mingguan</option>
                        <option value="monthly" {{ request('period') == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                        <option value="yearly" {{ request('period') == 'yearly' ? 'selected' : '' }}>Tahunan</option>
                    </select>
                </div>
                
                <!-- Tanggal Awal -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Tanggal Awal</label>
                    <input type="date" name="start_date" value="{{ $startDate }}" 
                           class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-emerald-500 focus:ring-3 focus:ring-emerald-500/30 transition">
                </div>
                
                <!-- Tanggal Akhir -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Tanggal Akhir</label>
                    <input type="date" name="end_date" value="{{ $endDate }}"
                           class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-emerald-500 focus:ring-3 focus:ring-emerald-500/30 transition">
                </div>
            </div>
            
            <div class="flex gap-3">
                <button type="submit" class="px-5 py-3 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition">
                    <i class="fas fa-filter mr-2"></i> Terapkan Filter
                </button>
                <button type="button" onclick="resetFilter()" class="px-5 py-3 bg-slate-200 text-slate-700 rounded-xl hover:bg-slate-300 transition">
                    <i class="fas fa-redo mr-2"></i> Reset
                </button>
                <button type="button" onclick="showExportModal()" class="px-5 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition ml-auto">
                    <i class="fas fa-file-export mr-2"></i> Export Laporan
                </button>
            </div>
        </form>
    </div>

    <!-- Statistik Card -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-gradient-to-r from-emerald-50 to-emerald-100 border border-emerald-200 rounded-2xl p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-emerald-800 font-medium">Total Peminjaman</p>
                    <p class="text-2xl font-bold text-emerald-900 mt-1">{{ $data['general']['total_peminjaman'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-500/20 flex items-center justify-center">
                    <i class="fas fa-clipboard-list text-emerald-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-green-50 to-green-100 border border-green-200 rounded-2xl p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-green-800 font-medium">Selesai</p>
                    <p class="text-2xl font-bold text-green-900 mt-1">{{ $data['general']['selesai'] }}</p>
                    <p class="text-xs text-green-600 mt-1">{{ $data['general']['total_peminjaman'] > 0 ? round(($data['general']['selesai'] / $data['general']['total_peminjaman']) * 100, 1) : 0 }}%</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-green-500/20 flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 rounded-2xl p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-blue-800 font-medium">Sedang Dipinjam</p>
                    <p class="text-2xl font-bold text-blue-900 mt-1">{{ $data['general']['diproses'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-blue-500/20 flex items-center justify-center">
                    <i class="fas fa-hand-holding text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 border border-yellow-200 rounded-2xl p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-yellow-800 font-medium">Rata-rata Hari</p>
                    <p class="text-2xl font-bold text-yellow-900 mt-1">{{ $data['time_stats']['avg_borrow_days'] }}</p>
                    <p class="text-xs text-yellow-600 mt-1">hari/peminjaman</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-yellow-500/20 flex items-center justify-center">
                    <i class="fas fa-calendar-alt text-yellow-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Top 5 Alat Populer -->
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-slate-800">Alat Paling Sering Dipinjam</h3>
            <span class="text-sm text-slate-500">Top 5</span>
        </div>
        <div class="space-y-4">
            @forelse($data['popular_tools']->take(5) as $index => $tool)

            <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-emerald-100 flex items-center justify-center">
                        <i class="fas fa-tools text-emerald-600"></i>
                    </div>
                    <div>
                        <div class="font-medium text-slate-800">{{ $tool['alat']->nama_alat }}</div>
                        <div class="text-xs text-slate-500">Kode: {{ $tool['alat']->kode_alat }}</div>
                    </div>
                </div>
                <div class="text-right">
                    <div class="font-bold text-emerald-600">{{ $tool['count'] }}x</div>
                    <div class="text-xs text-slate-500">dipinjam</div>
                </div>
            </div>
            @empty
            <div class="text-center py-8 text-slate-400">
                <i class="fas fa-chart-bar text-4xl mb-2"></i>
                <p>Belum ada data alat yang dipinjam</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Top 5 Peminjam Aktif -->
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-slate-800">Peminjam Paling Aktif</h3>
            <span class="text-sm text-slate-500">Top 5</span>
        </div>
        <div class="space-y-4">
            @forelse($data['active_users']->take(5) as $user)
            <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                        <i class="fas fa-user text-blue-600"></i>
                    </div>
                    <div>
                        <div class="font-medium text-slate-800">{{ $user->user->name }}</div>
                        <div class="text-xs text-slate-500">Kelas: {{ $user->user->kelas_jurusan }}</div>
                    </div>
                </div>
                <div class="text-right">
                    <div class="font-bold text-blue-600">{{ $user->total_peminjaman }}x</div>
                    <div class="text-xs text-slate-500">
                        {{ $user->selesai }} selesai • {{ $user->ditolak }} ditolak
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-8 text-slate-400">
                <i class="fas fa-users text-4xl mb-2"></i>
                <p>Belum ada data peminjam</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Export Modal -->
<div id="exportModal" class="fixed inset-0 bg-black/50 z-[60] hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md animate-slide-up">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-slate-800">Export Laporan</h3>
                <button onclick="closeExportModal()" class="text-slate-400 hover:text-slate-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form id="exportForm" action="{{ route('petugas.laporan.export') }}" method="POST">
                @csrf
                <input type="hidden" name="start_date" value="{{ $startDate }}">
                <input type="hidden" name="end_date" value="{{ $endDate }}">
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-slate-700 mb-3">Pilih Format:</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="flex items-center gap-3 p-4 border-2 border-emerald-200 rounded-xl cursor-pointer hover:bg-emerald-50 transition">
                            <input type="radio" name="format" value="pdf" class="text-emerald-600" checked>
                            <div>
                                <div class="font-medium text-emerald-700">PDF</div>
                                <div class="text-sm text-slate-500">Untuk cetak/dokumen</div>
                            </div>
                        </label>
                        
                        <label class="flex items-center gap-3 p-4 border-2 border-blue-200 rounded-xl cursor-pointer hover:bg-blue-50 transition">
                            <input type="radio" name="format" value="excel" class="text-blue-600">
                            <div>
                                <div class="font-medium text-blue-700">Excel</div>
                                <div class="text-sm text-slate-500">Untuk analisis data</div>
                            </div>
                        </label>
                    </div>
                </div>
                
                <div class="flex gap-3">
                    <button type="button" onclick="closeExportModal()"
                        class="flex-1 px-4 py-3 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 px-4 py-3 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition">
                        Export
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>    
    // Modal Functions
    function showExportModal() {
        document.getElementById('exportModal').classList.remove('hidden');
        document.getElementById('exportModal').classList.add('flex');
        document.body.style.overflow = 'hidden';
    }
    
    function closeExportModal() {
        document.getElementById('exportModal').classList.add('hidden');
        document.getElementById('exportModal').classList.remove('flex');
        document.body.style.overflow = 'auto';
    }
    
    function resetFilter() {
        const today = new Date().toISOString().split('T')[0];
        const firstDay = new Date(new Date().getFullYear(), new Date().getMonth(), 1).toISOString().split('T')[0];
        
        document.querySelector('input[name="start_date"]').value = firstDay;
        document.querySelector('input[name="end_date"]').value = today;
        document.querySelector('select[name="period"]').value = 'monthly';
        document.getElementById('filterForm').submit();
    }
    
    // Close modal dengan ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeExportModal();
        }
    });
</script>
@endpush
@endsection