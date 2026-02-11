<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

echo "=== CEK USER ===\n\n";

// Cek semua user
$users = User::all();

if ($users->count() == 0) {
    echo "❌ Tidak ada user di database\n";
} else {
    echo "📋 Daftar User:\n";
    foreach ($users as $user) {
        echo "- ID: " . $user->id . " | ";
        echo "Nama: " . $user->name . " | ";
        echo "Email: " . $user->email . " | ";
        echo "Role: " . $user->role . "\n";
    }
}

// Cek user ID 1 khusus
echo "\n🔍 Cek user ID 1:\n";
$user1 = User::find(1);
if ($user1) {
    echo "✅ User ID 1 ditemukan: " . $user1->name . " (" . $user1->email . ")\n";
} else {
    echo "❌ User ID 1 TIDAK ditemukan\n";
    
    // Cari admin
    $admin = User::where('role', 'admin')->first();
    if ($admin) {
        echo "👑 Admin ditemukan: ID " . $admin->id . " - " . $admin->name . "\n";
    }
}