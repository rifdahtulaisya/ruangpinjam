<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DataAlatController extends Controller
{
    public function index(Request $request)
    {
        // Ambil filter dari request
        $search = $request->input('search');
        $kategoriId = $request->input('kategori_id');
        $kondisi = $request->input('kondisi');
        $perPage = $request->input('per_page', 5);
        
        // Query dengan filter
        $dataalat = Alat::with('kategori')
            ->when($search, function($query, $search) {
                return $query->where('nama_alat', 'like', '%' . $search . '%');
            })
            ->when($kategoriId, function($query, $kategoriId) {
                return $query->where('kategori_id', $kategoriId);
            })
            ->when($kondisi, function($query, $kondisi) {
                return $query->where('kondisi', $kondisi);
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
        
        $kategori = Kategori::all();
        
        return view('admin.dataalat.index', compact('dataalat', 'kategori'));
    }

    public function create()
    {
        $kategori = Kategori::all();
        return view('admin.dataalat.create', compact('kategori'));
    }

   public function store(Request $request)
{
    $validated = $request->validate([
        'kategori_id' => 'required|exists:kategoris,id',
        'nama_alat' => 'required|string|max:100',
        'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'kondisi' => 'required|in:baik,rusak_ringan,rusak_berat,perlu_perbaikan',
        'stok' => 'required|integer|min:0',
        'lokasi' => 'nullable|string|max:100',
    ]);

    // Handle upload foto
    if ($request->hasFile('foto')) {
        // Simpan file dan dapatkan full path relative
        $fotoPath = $request->file('foto')->store('alat', 'public');
        $validated['foto'] = $fotoPath; // Contoh: 'alat/filename.jpg'
    } else {
        $validated['foto'] = null;
    }

    Alat::create($validated);

    return redirect()->route('admin.dataalat.index')
        ->with('success', 'Data alat berhasil ditambahkan.');
}

    public function show(Alat $dataalat)
    {
        return view('admin.dataalat.show', compact('dataalat'));
    }

    public function edit(Alat $dataalat)
    {
        $kategori = Kategori::all();
        return view('admin.dataalat.edit', compact('dataalat', 'kategori'));
    }

    public function update(Request $request, Alat $dataalat)
{
    $validated = $request->validate([
        'kategori_id' => 'required|exists:kategoris,id',
        'nama_alat' => 'required|string|max:100',
        'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'kondisi' => 'required|in:baik,rusak_ringan,rusak_berat,perlu_perbaikan',
        'stok' => 'required|integer|min:0',
        'lokasi' => 'nullable|string|max:100',
    ]);

    // Handle upload foto
    if ($request->hasFile('foto')) {
        // Hapus foto lama jika ada
        if ($dataalat->foto && Storage::disk('public')->exists($dataalat->foto)) {
            Storage::disk('public')->delete($dataalat->foto);
        }
        
        // Upload foto baru
        $fotoPath = $request->file('foto')->store('alat', 'public');
        $validated['foto'] = $fotoPath;
    } elseif ($request->boolean('hapus_foto')) {
        // Hapus foto jika checkbox di-check
        if ($dataalat->foto && Storage::disk('public')->exists($dataalat->foto)) {
            Storage::disk('public')->delete($dataalat->foto);
        }
        $validated['foto'] = null;
    } else {
        // Pertahankan foto lama
        unset($validated['foto']);
    }

    $dataalat->update($validated);

    return redirect()->route('admin.dataalat.index')
        ->with('success', 'Data alat berhasil diperbarui.');
}

    public function destroy(Alat $dataalat)
    {
        // Hapus foto jika ada
        if ($dataalat->foto && Storage::disk('public')->exists('dataalat/' . $dataalat->foto)) {
            Storage::disk('public')->delete('dataalat/' . $dataalat->foto);
        }
        
        $dataalat->delete();
        
        return redirect()->route('admin.dataalat.index')
            ->with('success', 'Data alat berhasil dihapus.');
    }
}