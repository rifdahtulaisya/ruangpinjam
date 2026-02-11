<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;


class DataPeminjamController extends Controller
{

public function index(Request $request)
{
    $query = User::where('role', 'peminjam');
    
    // Search hanya di name dan email
    if ($request->has('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
            // Hapus phone karena kolom tidak ada
        });
    }
    
    // Default order by latest
    $query->orderBy('created_at', 'desc');
    
    // Pagination
    $perPage = $request->get('per_page', 5);
    $users = $query->paginate($perPage)->withQueryString();
    
    return view('admin.datapeminjam.index', compact('users'));
}


    public function create()
    {
        return view('admin.datapeminjam.create');
    }
public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'username' => 'required|string|unique:users,username',
        'email' => 'nullable|email|unique:users,email',
        'kelas_jurusan' => 'required|string|max:50',
    ]);

    // Generate password default
    $password = Str::random(8);
    $plainPassword = $password; // Simpan dalam variable

    $user = User::create([
        'name' => $validated['name'],
        'username' => $validated['username'],
        'email' => $validated['email'] ?? null,
        'kelas_jurusan' => $validated['kelas_jurusan'],
        'role' => 'peminjam',
        'password' => Hash::make($password),
        'plain_password' => $plainPassword, // Simpan plain password
    ]);

    // Kirim email dengan password default jika email diisi
    if ($user->email) {
        // Logic kirim email
        // \Mail::to($user->email)->send(new \App\Mail\AccountCreatedMail($user, $plainPassword));
    }

    // Simpan password di session untuk ditampilkan
    session()->flash('generated_password', $plainPassword);

    return redirect()->route('admin.datapeminjam.index')
        ->with('success', 'Akun peminjam berhasil dibuat.');
}

public function updatePassword(Request $request)
{
    $request->validate([
        'password' => 'required|min:6|confirmed'
    ]);

    $user = User::find(Auth::id());

    if (!$user) {
        return redirect()->back()->with('error', 'User tidak ditemukan');
    }

    $user->password = Hash::make($request->password);
    $user->plain_password = null; // Kosongkan plain password saat user ganti password
    $user->save();

    return redirect()->route('dashboard')
        ->with('success', 'Password berhasil diubah');
}

    public function edit($id)
    {
        $user = User::findOrFail($id);

        return view('admin.datapeminjam.edit', compact('user'));
    }


  public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    $request->validate([
        'kelas_jurusan' => 'required|string|max:50',
    ]);

    $data = [
        'kelas_jurusan' => $request->kelas_jurusan,
    ];

    // Jika ada password baru (reset password oleh admin)
    if ($request->filled('password')) {
        $request->validate([
            'password' => 'required|min:6'
        ]);
        $data['password'] = Hash::make($request->password);
        $data['plain_password'] = $request->password;
        session()->flash('generated_password', $request->password);
    }

    $user->update($data);

    return redirect()
        ->route('admin.datapeminjam.index')
        ->with('success', 'Kelas peminjam berhasil diupdate');
}

    public function destroy($id)
    {
        User::findOrFail($id)->delete();

        return redirect()
            ->route('admin.datapeminjam.index')
            ->with('success', 'Data peminjam berhasil dihapus');
    }

}
