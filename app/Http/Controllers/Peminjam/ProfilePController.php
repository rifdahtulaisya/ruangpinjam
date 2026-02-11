<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\User; // <<< TAMBAHKAN INI!
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB; 

class ProfilePController extends Controller
{
    /**
     * Display the profile page
     */
    public function index()
    {
        $user = Auth::user();
        return view('peminjam.profile', compact('user'));
    }

    /**
     * Update profile information (name, username, email)
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . Auth::id(),
            'email' => 'required|email|unique:users,email,' . Auth::id(),
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // PERBAIKAN: Gunakan User::find() instead of Auth::user()
        $user = User::find(Auth::id());
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->save(); // <<< SEKARANG BERFUNGSI!

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Change password
     */
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|current_password',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // METHOD 1: Via Query Builder (SUDAH BENAR)
        DB::table('users')
            ->where('id', Auth::id())
            ->update([
                'password' => Hash::make($request->new_password),
                'updated_at' => now()
            ]);

        return redirect()->back()->with('success', 'Password berhasil diubah!');
    }
}