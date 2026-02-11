<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'username.required' => 'Username wajib diisi',
            'password.required' => 'Password wajib diisi',
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $credentials = $this->only('username', 'password');
        
        // Cek apakah username ada di database
        $user = \App\Models\User::where('username', $credentials['username'])->first();
        
        if (!$user) {
            throw ValidationException::withMessages([
                'username' => 'Username tidak terdaftar',
            ]);
        }
        
        // CEK APAKAH USER DI-BLOKIR
        if ($user->is_blocked) {
            throw ValidationException::withMessages([
                'username' => 'Akun Anda telah diblokir. Silakan hubungi petugas perpustakaan.',
            ]);
        }
        
        // Cek password
        if (!Auth::attempt($credentials, $this->boolean('remember'))) {
            throw ValidationException::withMessages([
                'password' => 'Password yang Anda masukkan salah',
            ]);
        }
    }
}