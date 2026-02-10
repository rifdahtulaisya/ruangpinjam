<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class DataPerkelasController extends Controller
{
    public function index(Request $request)
    {
        // Group data by kelas_jurusan
        $groups = User::where('role', 'peminjam')
            ->selectRaw('kelas_jurusan, COUNT(*) as total')
            ->groupBy('kelas_jurusan')
            ->orderBy('kelas_jurusan')
            ->get();

        // Jika ada kelas tertentu yang dipilih
        $selectedKelas = $request->get('kelas');
        $users = collect();

        if ($selectedKelas) {
            $users = User::where('role', 'peminjam')
                ->where('kelas_jurusan', $selectedKelas)
                ->orderBy('name')
                ->paginate(10)
                ->withQueryString();
        }

        return view('admin.dataperkelas.index', compact('groups', 'users', 'selectedKelas'));
    }
}