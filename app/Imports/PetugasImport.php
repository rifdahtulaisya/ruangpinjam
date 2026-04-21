<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Validators\Failure;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PetugasImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;
    
    private $successCount = 0;
    private $errorCount = 0;
    private $errors = [];
    private $generatedPasswords = [];

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Generate password default jika tidak diisi
        $password = !empty($row['password']) ? $row['password'] : Str::random(8);
        $plainPassword = $password;
        
        // Cek apakah user sudah ada berdasarkan username
        $existingUser = User::where('username', $row['username'])->first();
        
        if ($existingUser) {
            // Update data existing user
            $existingUser->update([
                'name' => $row['nama_lengkap'],
                'email' => $row['email'] ?? null,
                'status' => $row['status'] ?? 'active',
                // Jangan update password jika tidak ada password baru
                'password' => !empty($row['password']) ? Hash::make($password) : $existingUser->password,
                'plain_password' => !empty($row['password']) ? $plainPassword : $existingUser->plain_password,
            ]);
            
            $this->successCount++;
            return null;
        }
        
        // Buat user baru
        $this->generatedPasswords[] = [
            'username' => $row['username'],
            'password' => $plainPassword
        ];
        
        $this->successCount++;
        
        return new User([
            'name' => $row['nama_lengkap'],
            'username' => $row['username'],
            'email' => $row['email'] ?? null,
            'role' => 'petugas',
            'status' => $row['status'] ?? 'active',
            'password' => Hash::make($password),
            'plain_password' => $plainPassword,
        ]);
    }

    /**
     * Validasi data
     */
    public function rules(): array
    {
        return [
            '*.nama_lengkap' => 'required|string|max:255',
            '*.username' => 'required|string|max:255',
            '*.email' => 'nullable|email|max:255',
            '*.status' => 'nullable|in:active,inactive',
            '*.password' => 'nullable|string|min:6',
        ];
    }

    /**
     * Custom validation messages
     */
    public function customValidationMessages()
    {
        return [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi',
            'username.required' => 'Username wajib diisi',
            'username.unique' => 'Username sudah terdaftar',
            'email.email' => 'Format email tidak valid',
            'status.in' => 'Status harus active atau inactive',
            'password.min' => 'Password minimal 6 karakter',
        ];
    }

    /**
     * Handle failures
     */
    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $failure) {
            $this->errors[] = "Baris {$failure->row()}: " . implode(', ', $failure->errors());
            $this->errorCount++;
        }
    }

    public function getSuccessCount()
    {
        return $this->successCount;
    }

    public function getErrorCount()
    {
        return $this->errorCount;
    }

    public function getErrors()
    {
        return $this->errors;
    }
    
    public function getGeneratedPasswords()
    {
        return $this->generatedPasswords;
    }
}