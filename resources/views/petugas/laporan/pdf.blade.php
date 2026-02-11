<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Peminjaman</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }

        .title {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .periode {
            text-align: center;
            margin-bottom: 20px;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
            border: 1px solid #444;
            padding: 8px;
        }

        td {
            border: 1px solid #777;
            padding: 6px;
        }

        .center {
            text-align: center;
        }

        .left {
            text-align: left;
        }
    </style>
</head>
<body>

<div class="title">
    LAPORAN PEMINJAMAN
</div>

<div class="periode">
    Periode: {{ $startDate }} sampai {{ $endDate }}
</div>

<table>
    <thead>
        <tr>
            <th width="5%">No</th>
            <th width="20%">Peminjam</th>
            <th width="25%">Alat</th>
            <th width="15%">Tanggal Pinjam</th>
            <th width="15%">Tanggal Kembali</th>
            <th width="10%">Status</th>
        </tr>
    </thead>
    <tbody>

        @foreach($peminjamans as $p)

        <tr>

            {{-- Nomor urut --}}
            <td class="center">
                {{ $loop->iteration }}
            </td>

            <td class="left">
                {{ $p->user->name ?? '-' }}
            </td>

            <td class="left">
                {{ implode(', ', \App\Models\Alat::whereIn('id', $p->alat_ids ?? [])->pluck('nama_alat')->toArray()) }}
            </td>

            <td class="center">
                {{ $p->tanggal_peminjaman }}
            </td>

            <td class="center">
                {{ $p->tanggal_dikembalikan ?? '-' }}
            </td>

            <td class="center">
                {{ ucfirst($p->status) }}
            </td>

        </tr>

        @endforeach

    </tbody>
</table>

</body>
</html>
