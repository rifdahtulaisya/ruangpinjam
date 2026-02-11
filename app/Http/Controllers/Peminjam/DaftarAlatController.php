<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\Kategori;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DaftarAlatController extends Controller
{
    public function index(Request $request)
    {
        // Ambil filter dari request
        $search = $request->input('search');
        $kategoriId = $request->input('kategori_id');
        $perPage = $request->input('per_page', 12);
        
        // Query dengan filter hanya untuk alat yang tersedia (stok > 0)
        $dataalat = Alat::with('kategori')
            ->where('stok', '>', 0)
            ->when($search, function($query, $search) {
                return $query->where('nama_alat', 'like', '%' . $search . '%')
                            ->orWhere('lokasi', 'like', '%' . $search . '%')
                            ->orWhere('kode_alat', 'like', '%' . $search . '%');
            })
            ->when($kategoriId, function($query, $kategoriId) {
                return $query->where('kategori_id', $kategoriId);
            })
            ->orderBy('nama_alat')
            ->paginate($perPage)
            ->withQueryString();
        
        $kategori = Kategori::all();
        
        return view('peminjam.daftaralat', compact('dataalat', 'kategori'));
    }
    
    public function storePeminjaman(Request $request)
{
    
    $request->validate([
        'alat_ids' => 'required|array|min:1',
        'alat_ids.*' => 'exists:alat,id',
        'tanggal_peminjaman' => 'required|date|after_or_equal:today',
        'tanggal_pengembalian' => 'required|date|after:tanggal_peminjaman',
        'keterangan' => 'nullable|string|max:500',
    ]);

    try {

        $alatNames = [];
        $alatIdsValid = [];

        foreach ($request->alat_ids as $alatId) {

            $alat = Alat::findOrFail($alatId);

            if ($alat->stok <= 0) {
                return back()->with('error', "Alat {$alat->nama_alat} stok habis!");
            }

            $alatNames[] = $alat->nama_alat;
            $alatIdsValid[] = $alatId;
        }


        // SIMPAN PEMINJAMAN
        $peminjaman = Peminjaman::create([

            'user_id' => Auth::id(),

            // jika kolom json
            'alat_ids' => $alatIdsValid,


            'nama_alat' => implode(', ', $alatNames),

            'tanggal_peminjaman' => $request->tanggal_peminjaman,

            'tanggal_pengembalian' => $request->tanggal_pengembalian,

            'status' => 'menunggu',

            'keterangan' => $request->keterangan,

        ]);


        // KURANGI STOK
        foreach ($alatIdsValid as $alatId) {

            Alat::where('id', $alatId)->decrement('stok');

        }


        return redirect()
            ->route('peminjam.peminjaman.index')
            ->with('success', 'Peminjaman berhasil diajukan!');


    } catch (\Exception $e) {

        return back()->with('error', $e->getMessage());

    }

}

}