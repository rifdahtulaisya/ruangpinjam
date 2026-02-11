<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;


class DataPetugasController extends Controller
{

public function index(Request $request)
{
    $query = User::where('role', 'petugas');
    
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

    // Generate password default
    $password = Str::random(8);
    $plainPassword = $password; // Simpan dalam variable

    $user = User::create([
        'name' => $validated['name'],
        'username' => $validated['username'],
        'email' => $validated['email'] ?? null,
        'role' => 'petugas',
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

    return redirect()->route('admin.datapetugas.index')
        ->with('success', 'Akun petugas berhasil dibuat.');
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

        return view('admin.datapetugas.edit', compact('user'));
    }


   public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    $request->validate([
        'name' => 'required|max:100',
        'username' => 'required|max:50|unique:users,username,' . $id,
        'email' => 'nullable|email|unique:users,email,' . $id,
    ]);

    $data = [
        'name' => $request->name,
        'username' => $request->username,
    ];

    // Email bisa kosong
    if ($request->filled('email')) {
        $data['email'] = $request->email;
    } else {
        $data['email'] = null;
    }

    // Jika ada password baru (reset password oleh admin)
    if ($request->password) {
        $data['password'] = Hash::make($request->password);
        $data['plain_password'] = $request->password; // Simpan plain password
        
        // Simpan password di session untuk ditampilkan
        session()->flash('generated_password', $request->password);
    }

    $user->update($data);

    return redirect()
        ->route('admin.datapetugas.index')
        ->with('success', 'Data petugas berhasil diupdate');
}

    public function destroy($id)
    {
        User::findOrFail($id)->delete();

        return redirect()
            ->route('admin.datapetugas.index')
            ->with('success', 'Data petugas berhasil dihapus');
    }

}
