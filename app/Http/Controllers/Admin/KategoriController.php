<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategoris = Kategori::latest()->paginate(5);
        return view('admin.datakategori.index', compact('kategoris'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.datakategori.create');
    }

    public function storeMany(Request $request)
    {
        $request->validate([
            'nama_kategori'   => 'required|array|min:1',
            'nama_kategori.*' => 'required|string|max:255|distinct',
        ], [
            'nama_kategori.required'   => 'Minimal isi 1 kategori.',
            'nama_kategori.*.required' => 'Nama kategori tidak boleh kosong.',
            'nama_kategori.*.distinct' => 'Ada nama kategori yang duplikat.',
        ]);

        foreach ($request->nama_kategori as $nama) {
            Kategori::create(['nama_kategori' => $nama]);
        }

        return redirect()
            ->route('admin.datakategori.index')
            ->with('success', count($request->nama_kategori) . ' kategori berhasil dibuat.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategoris,nama_kategori',
        ]);

        Kategori::create($request->all());

        return redirect()->route('admin.datakategori.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Kategori $datakategori)
    {
        return view('admin.datakategori.show', compact('datakategori'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kategori $datakategori)
    {
        return view('admin.datakategori.edit', compact('datakategori'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kategori $datakategori)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategoris,nama_kategori,' . $datakategori->id,
        ]);

        $datakategori->update($request->all());

        return redirect()->route('admin.datakategori.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kategori $datakategori)
    {
        $datakategori->delete();

        return redirect()->route('admin.datakategori.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}