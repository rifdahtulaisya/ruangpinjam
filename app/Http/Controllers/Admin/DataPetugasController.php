<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Exports\PetugasExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class DataPetugasController extends Controller
{

    public function index(Request $request)
    {
        $query = User::where('role', 'petugas');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $query->orderBy('created_at', 'desc');

        $perPage = $request->get('per_page', 5);
        $users = $query->paginate($perPage)->withQueryString();

        return view('admin.datapetugas.index', compact('users'));
    }


    public function create()
    {
        return view('admin.datapetugas.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username',
            'email' => 'nullable|email|unique:users,email',
        ]);

        $password = Str::random(8);
        $plainPassword = $password;

        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'] ?? null,
            'role' => 'petugas',
            'password' => Hash::make($password),
            'plain_password' => $plainPassword,
        ]);

        if ($user->email) {
        }

        session()->flash('generated_password', $plainPassword);

        return redirect()->route('admin.datapetugas.index')
            ->with('success', 'Akun petugas berhasil dibuat.');
    }

    public function export()
    {
        return Excel::download(new PetugasExport(true), 'data-petugas-' . date('Y-m-d') . '.xlsx');
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();

        return redirect()
            ->route('admin.datapetugas.index')
            ->with('success', 'Data petugas berhasil dihapus');
    }
}