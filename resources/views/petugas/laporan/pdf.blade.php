@php
    use Carbon\Carbon;
    
    // Ambil data user dan alat jika ada filter
    $filteredUser = null;
    $filteredAlat = null;
    
    if(request('user_id')) {
        $filteredUser = \App\Models\User::find(request('user_id'));
    }
    if(request('alat_id')) {
        $filteredAlat = \App\Models\Alat::find(request('alat_id'));
    }
@endphp

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Peminjaman</title>
    <style>
        /* style tetap sama */
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #0d9488;
        }
        .title {
            font-size: 20px;
            font-weight: bold;
            color: #0d9488;
            margin-bottom: 5px;
        }
        .periode {
            font-size: 12px;
            color: #555;
        }
        .filter-info {
            background-color: #f8fafc;
            border-left: 4px solid #0d9488;
            padding: 8px 12px;
            margin-bottom: 15px;
            font-size: 11px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th {
            background-color: #0d9488;
            color: white;
            font-weight: bold;
            text-align: center;
            border: 1px solid #0a7669;
            padding: 10px 6px;
            font-size: 11px;
        }
        td {
            border: 1px solid #cbd5e1;
            padding: 8px 6px;
            vertical-align: top;
        }
        .center {
            text-align: center;
        }
        .left {
            text-align: left;
        }
        .status {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
            text-transform: capitalize;
        }
        .status-dipinjam { background-color: #dbeafe; color: #1e40af; }
        .status-selesai { background-color: #dcfce7; color: #166534; }
        .status-ditolak { background-color: #fee2e2; color: #991b1b; }
        .status-ditegur { background-color: #fef9c3; color: #854d0e; }
        .status-menunggu { background-color: #ffedd5; color: #9a3412; }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">LAPORAN PEMINJAMAN ALAT LABORATORIUM</div>
        <div class="periode">
            Periode: {{ Carbon::parse($startDate)->format('d/m/Y') }} - {{ Carbon::parse($endDate)->format('d/m/Y') }}
        </div>
    </div>
    
    @if($filteredUser || $filteredAlat)
    <div class="filter-info">
        <strong>Filter yang diterapkan:</strong><br>
        @if($filteredUser)
            - Peminjam: {{ $filteredUser->name }} {{ $filteredUser->kelas_jurusan ? '(' . $filteredUser->kelas_jurusan . ')' : '' }}<br>
        @endif
        @if($filteredAlat)
            - Alat: {{ $filteredAlat->nama_alat }} ({{ $filteredAlat->kode_alat }})<br>
        @endif
    </div>
    @endif
    
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="18%">Peminjam</th>
                <th width="25%">Nama Alat</th>
                <th width="10%">Kode Alat</th>
                <th width="14%">Tgl Pinjam</th>
                <th width="14%">Tgl Kembali</th>
                <th width="14%">Status</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @forelse($peminjamans as $peminjaman)
                @php
                    $alats = !empty($peminjaman->alat_ids) 
                        ? \App\Models\Alat::whereIn('id', $peminjaman->alat_ids)->get() 
                        : collect();
                    $rowspan = $alats->count() > 0 ? $alats->count() : 1;
                @endphp
                
                @if($alats->count() > 0)
                    @foreach($alats as $index => $alat)
                    <tr>
                        @if($loop->first)
                            <td class="center" rowspan="{{ $rowspan }}">{{ $no }}</td>
                            <td class="left" rowspan="{{ $rowspan }}">
                                {{ $peminjaman->user->name ?? '-' }}<br>
                                <small style="color: #666;">{{ $peminjaman->user->kelas_jurusan ?? '-' }}</small>
                            </td>
                        @endif
                        <td class="left">{{ $alat->nama_alat }}</td>
                        <td class="center">{{ $alat->kode_alat }}</td>
                        @if($loop->first)
                            <td class="center" rowspan="{{ $rowspan }}">{{ Carbon::parse($peminjaman->tanggal_peminjaman)->format('d/m/Y') }}</td>
                            <td class="center" rowspan="{{ $rowspan }}">{{ $peminjaman->tanggal_dikembalikan ? Carbon::parse($peminjaman->tanggal_dikembalikan)->format('d/m/Y') : '-' }}</td>
                            <td class="center" rowspan="{{ $rowspan }}">
                                @php
                                    $statusClass = match($peminjaman->status) {
                                        'dipinjam' => 'status-dipinjam',
                                        'selesai' => 'status-selesai',
                                        'ditolak' => 'status-ditolak',
                                        'ditegur' => 'status-ditegur',
                                        'menunggu' => 'status-menunggu',
                                        default => ''
                                    };
                                    $statusLabel = match($peminjaman->status) {
                                        'dipinjam' => 'Dipinjam',
                                        'selesai' => 'Selesai',
                                        'ditolak' => 'Ditolak',
                                        'ditegur' => 'Ditegur',
                                        'menunggu' => 'Menunggu',
                                        default => ucfirst($peminjaman->status)
                                    };
                                @endphp
                                <span class="status {{ $statusClass }}">{{ $statusLabel }}</span>
                                @if($peminjaman->catatan)
                                <br><small style="font-size: 9px;">Catatan: {{ $peminjaman->catatan }}</small>
                                @endif
                            </td>
                        @endif
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="center">{{ $no }}</td>
                        <td class="left">
                            {{ $peminjaman->user->name ?? '-' }}<br>
                            <small style="color: #666;">{{ $peminjaman->user->kelas_jurusan ?? '-' }}</small>
                        </td>
                        <td class="center" colspan="2">-</td>
                        <td class="center">{{ Carbon::parse($peminjaman->tanggal_peminjaman)->format('d/m/Y') }}</td>
                        <td class="center">{{ $peminjaman->tanggal_dikembalikan ? Carbon::parse($peminjaman->tanggal_dikembalikan)->format('d/m/Y') : '-' }}</td>
                        <td class="center">
                            @php
                                $statusClass = match($peminjaman->status) {
                                    'dipinjam' => 'status-dipinjam',
                                    'selesai' => 'status-selesai',
                                    'ditolak' => 'status-ditolak',
                                    'ditegur' => 'status-ditegur',
                                    'menunggu' => 'status-menunggu',
                                    default => ''
                                };
                                $statusLabel = match($peminjaman->status) {
                                    'dipinjam' => 'Dipinjam',
                                    'selesai' => 'Selesai',
                                    'ditolak' => 'Ditolak',
                                    'ditegur' => 'Ditegur',
                                    'menunggu' => 'Menunggu',
                                    default => ucfirst($peminjaman->status)
                                };
                            @endphp
                            <span class="status {{ $statusClass }}">{{ $statusLabel }}</span>
                        </td>
                    </tr>
                @endif
                @php $no++ @endphp
            @empty
                <tr>
                    <td colspan="7" class="center" style="padding: 30px; color: #666;">
                        <i>Tidak ada data peminjaman pada periode ini</i>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    <div class="footer">
        <p>Dicetak pada: {{ Carbon::now()->format('d/m/Y H:i:s') }}</p>
        <p>Total Data: {{ $peminjamans->count() }} peminjaman</p>
    </div>
</body>
</html>