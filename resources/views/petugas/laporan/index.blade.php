@extends('layouts-petugas.petugas')

@section('title', 'LAPORAN PEMINJAMAN')

@section('content')
<div class="space-y-8 animate-slide-in">
    <!-- Filter Section -->
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <h2 class="text-xl font-bold text-slate-800 mb-4">Filter Laporan</h2>
        <form id="filterForm" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Filter User -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        <i class="fas fa-user mr-1"></i> Filter Peminjam
                    </label>
                    <select name="user_id" class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-emerald-500 focus:ring-3 focus:ring-emerald-500/30 transition">
                        <option value="">Semua Peminjam</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} {{ $user->kelas_jurusan ? ' - ' . $user->kelas_jurusan : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Filter Alat -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        <i class="fas fa-tools mr-1"></i> Filter Alat
                    </label>
                    <select name="alat_id" class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-emerald-500 focus:ring-3 focus:ring-emerald-500/30 transition">
                        <option value="">Semua Alat</option>
                        @foreach($alats as $alat)
                            <option value="{{ $alat->id }}" {{ request('alat_id') == $alat->id ? 'selected' : '' }}>
                                {{ $alat->nama_alat }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Tanggal Awal -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        <i class="fas fa-calendar-alt mr-1"></i> Tanggal Awal
                    </label>
                    <input type="date" name="start_date" value="{{ $startDate }}" 
                           class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-emerald-500 focus:ring-3 focus:ring-emerald-500/30 transition">
                </div>
                
                <!-- Tanggal Akhir -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        <i class="fas fa-calendar-alt mr-1"></i> Tanggal Akhir
                    </label>
                    <input type="date" name="end_date" value="{{ $endDate }}"
                           class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-emerald-500 focus:ring-3 focus:ring-emerald-500/30 transition">
                </div>
            </div>
            
            <div class="flex gap-3">
                <button type="submit" class="px-5 py-3 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition">
                    <i class="fas fa-search mr-2"></i> Tampilkan
                </button>
                <a href="{{ route('petugas.laporan.index') }}" class="px-5 py-3 bg-slate-200 text-slate-700 rounded-xl hover:bg-slate-300 transition">
                    <i class="fas fa-redo mr-2"></i> Reset
                </a>
                <button type="button" onclick="showExportModal()" class="px-5 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition ml-auto">
                    <i class="fas fa-file-export mr-2"></i> Export Laporan
                </button>
            </div>
        </form>
    </div>

    <!-- Info Filter Aktif -->
    @if(request('user_id') || request('alat_id'))
    <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4">
        <div class="flex items-center gap-3">
            <i class="fas fa-filter text-blue-600"></i>
            <span class="text-blue-800 font-medium">Filter Aktif:</span>
            <div class="flex flex-wrap gap-2">
                @if(request('user_id') && $users->where('id', request('user_id'))->first())
                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-white rounded-full text-sm">
                        <i class="fas fa-user text-blue-600"></i>
                        {{ $users->where('id', request('user_id'))->first()->name }}
                    </span>
                @endif
                @if(request('alat_id') && $alats->where('id', request('alat_id'))->first())
                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-white rounded-full text-sm">
                        <i class="fas fa-tools text-blue-600"></i>
                        {{ $alats->where('id', request('alat_id'))->first()->nama_alat }}
                    </span>
                @endif
            </div>
        </div>
    </div>
    @endif


    <!-- Tabel Detail Peminjaman -->
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-slate-800">
                <i class="fas fa-list mr-2"></i> Detail Peminjaman
            </h3>
            <span class="text-sm text-slate-500">
                Total: {{ count($data['filtered_peminjamans']) }} peminjaman
            </span>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50">
                        <th class="px-4 py-3 text-left text-sm font-semibold text-slate-600">No</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-slate-600">Peminjam</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-slate-600">Alat</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-slate-600">Tanggal Pinjam</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-slate-600">Tanggal Kembali</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-slate-600">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data['filtered_peminjamans'] as $index => $peminjaman)
                    <tr class="border-b border-slate-200 hover:bg-slate-50">
                        <td class="px-4 py-3 text-sm">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3 text-sm">
                            <div class="font-medium">{{ $peminjaman->user->name ?? '-' }}</div>
                            <div class="text-xs text-slate-500">{{ $peminjaman->user->kelas_jurusan ?? '-' }}</div>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            @php
                                $alats = App\Models\Alat::whereIn('id', $peminjaman->alat_ids ?? [])->get();
                            @endphp
                            @foreach($alats as $alat)
                                <span class="inline-flex items-center px-2 py-1 bg-emerald-100 text-emerald-800 text-xs rounded-lg mb-1 mr-1">
                                    {{ $alat->nama_alat }}
                                </span>
                            @endforeach
                        </td>
                        <td class="px-4 py-3 text-sm">{{ Carbon\Carbon::parse($peminjaman->tanggal_peminjaman)->format('d/m/Y') }}</td>
                        <td class="px-4 py-3 text-sm">{{ $peminjaman->tanggal_dikembalikan ? Carbon\Carbon::parse($peminjaman->tanggal_dikembalikan)->format('d/m/Y') : '-' }}</td>
                        <td class="px-4 py-3 text-sm">
                            @php
                                $statusColors = [
                                    'dipinjam' => 'bg-blue-100 text-blue-800',
                                    'selesai' => 'bg-green-100 text-green-800',
                                    'ditolak' => 'bg-red-100 text-red-800',
                                    'ditegur' => 'bg-yellow-100 text-yellow-800',
                                    'menunggu' => 'bg-orange-100 text-orange-800'
                                ];
                                $statusLabels = [
                                    'dipinjam' => 'Dipinjam',
                                    'selesai' => 'Selesai',
                                    'ditolak' => 'Ditolak',
                                    'ditegur' => 'Ditegur',
                                    'menunggu' => 'Menunggu'
                                ];
                            @endphp
                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $statusColors[$peminjaman->status] ?? 'bg-slate-100 text-slate-800' }}">
                                {{ $statusLabels[$peminjaman->status] ?? ucfirst($peminjaman->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-slate-400">
                            <i class="fas fa-inbox text-4xl mb-2"></i>
                            <p>Tidak ada data peminjaman</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Export Modal -->
<div id="exportModal" class="fixed inset-0 bg-black/50 z-[60] hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md animate-slide-up">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-slate-800">
                    <i class="fas fa-file-export mr-2"></i> Export Laporan
                </h3>
                <button onclick="closeExportModal()" class="text-slate-400 hover:text-slate-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form id="exportForm" action="{{ route('petugas.laporan.export') }}" method="POST">
                @csrf
                <input type="hidden" name="start_date" value="{{ $startDate }}">
                <input type="hidden" name="end_date" value="{{ $endDate }}">
                <input type="hidden" name="user_id" value="{{ request('user_id') }}">
                <input type="hidden" name="alat_id" value="{{ request('alat_id') }}">
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-slate-700 mb-3">Pilih Format:</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="flex items-center gap-3 p-4 border-2 border-emerald-200 rounded-xl cursor-pointer hover:bg-emerald-50 transition">
                            <input type="radio" name="format" value="pdf" class="text-emerald-600" checked>
                            <div>
                                <i class="fas fa-file-pdf text-emerald-600 text-xl"></i>
                                <div class="font-medium text-emerald-700 mt-1">PDF</div>
                                <div class="text-xs text-slate-500">Untuk cetak/dokumen</div>
                            </div>
                        </label>
                        
                        <label class="flex items-center gap-3 p-4 border-2 border-blue-200 rounded-xl cursor-pointer hover:bg-blue-50 transition">
                            <input type="radio" name="format" value="excel" class="text-blue-600">
                            <div>
                                <i class="fas fa-file-excel text-blue-600 text-xl"></i>
                                <div class="font-medium text-blue-700 mt-1">CSV</div>
                                <div class="text-xs text-slate-500">Untuk analisis data</div>
                            </div>
                        </label>
                    </div>
                </div>
                
                <div class="bg-slate-50 rounded-xl p-4 mb-6">
                    <div class="text-sm text-slate-600">
                        <div class="font-medium mb-2">Informasi Export:</div>
                        <div class="space-y-1">
                            <div><i class="fas fa-calendar mr-2"></i> Periode: {{ Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</div>
                            @if(request('user_id'))
                                <div><i class="fas fa-user mr-2"></i> Peminjam: {{ $users->where('id', request('user_id'))->first()->name ?? '-' }}</div>
                            @endif
                            @if(request('alat_id'))
                                <div><i class="fas fa-tools mr-2"></i> Alat: {{ $alats->where('id', request('alat_id'))->first()->nama_alat ?? '-' }}</div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="flex gap-3">
                    <button type="button" onclick="closeExportModal()"
                        class="flex-1 px-4 py-3 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 px-4 py-3 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition">
                        <i class="fas fa-download mr-2"></i> Export
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
    
    // Close modal dengan ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeExportModal();
        }
    });
</script>
@endpush
@endsection