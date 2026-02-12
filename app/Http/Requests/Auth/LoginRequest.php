<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'Username wajib diisi',
            'password.required' => 'Password wajib diisi',
        ];
    }

   // App\Http\Requests\Auth\LoginRequest.php

public function authenticate(): void
{
    $credentials = $this->only('username', 'password');
    
    $user = \App\Models\User::where('username', $credentials['username'])->first();
    
    if (!$user) {
        throw ValidationException::withMessages([
            'username' => 'Username tidak terdaftar',
        ]);
    }
    
    // CEK APAKAH USER DI-BLOKIR
    if ($user->is_blocked) {
        $blockedReason = $user->blocked_reason ?? 'Akun diblokir karena memiliki peminjaman telat.';
        $blockedAt = $user->blocked_at ? $user->blocked_at->format('d/m/Y H:i') : '';
        
        // CEK APAKAH MASIH ADA PEMINJAMAN TELAT
        $masihTelat = $user->masihMemilikiPeminjamanTelat();
        
        if (!$masihTelat) {
            // OTOMATIS UNBLOCK JIKA TIDAK ADA PEMINJAMAN TELAT
            $user->unblock("Akun dipulihkan otomatis saat login - Tidak ada peminjaman telat");
        } else {
            throw ValidationException::withMessages([
                'username' => "AKUN ANDA DIBLOKIR!\n\n{$blockedReason}\n\n Diblokir pada: {$blockedAt}\n\n Untuk membuka blokir, selesaikan SEMUA peminjaman telat Anda dan minta petugas mengkonfirmasi pengembalian.",
            ]);
        }
    }
    
    if (!Auth::attempt($credentials, $this->boolean('remember'))) {
        throw ValidationException::withMessages([
            'password' => 'Password yang Anda masukkan salah',
        ]);
    }
}
}