<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Peminjaman;
use App\Models\LogAktivitas;
use App\Models\User;
use Illuminate\Support\Facades\DB;

echo "=== TEST LOG AKTIVITAS (FINAL) ===\n\n";

// 1. Cari user admin yang ada
echo "👑 Mencari user admin...\n";
$adminUser = User::where('role', 'admin')->first();

if (!$adminUser) {
    // Cari user apa saja yang ada
    $anyUser = User::first();
    if (!$anyUser) {
        echo "❌ Tidak ada user sama sekali di database\n";
        exit;
    }
    echo "ℹ️  Admin tidak ditemukan, pakai user ID: " . $anyUser->id . "\n";
    $userId = $anyUser->id;
    $userRole = $anyUser->role;
} else {
    $userId = $adminUser->id;
    $userRole = $adminUser->role;
    echo "✅ Admin ditemukan: ID " . $userId . " - " . $adminUser->name . "\n";
}

// 2. Cek data peminjaman
$peminjaman = Peminjaman::with('user')->latest()->first();

if (!$peminjaman) {
    echo "❌ Tidak ada data peminjaman\n";
    exit;
}

echo "\n📋 Data Peminjaman:\n";
echo "- ID: " . $peminjaman->id . "\n";
echo "- Alat: " . $peminjaman->nama_alat . "\n";
echo "- User: " . $peminjaman->user->name . " (ID: " . $peminjaman->user_id . ")\n";
echo "- Status: " . $peminjaman->status . "\n\n";

// 3. Buat log aktivitas
echo "📝 Membuat log aktivitas...\n";

try {
    // Method 1: Pakai model (setelah fix timestamps)
    $log = LogAktivitas::create([
        'user_id' => $userId,
        'role' => $userRole,
        'aktivitas' => 'Menyetujui peminjaman "' . $peminjaman->nama_alat . 
                      '" oleh "' . $peminjaman->user->name . '"',
        'modul' => 'peminjaman'
    ]);
    
    echo "✅ Log berhasil dibuat via Model! ID: " . $log->id . "\n";
    
} catch (\Exception $e) {
    echo "❌ Error model: " . $e->getMessage() . "\n";
    
    // Method 2: Pakai query builder
    try {
        echo "🔄 Coba dengan query builder...\n";
        $logId = DB::table('log_aktivitas')->insertGetId([
            'user_id' => $userId,
            'role' => $userRole,
            'aktivitas' => 'Menyetujui peminjaman "' . $peminjaman->nama_alat . 
                          '" oleh "' . $peminjaman->user->name . '"',
            'modul' => 'peminjaman',
            'created_at' => now()
        ]);
        
        echo "✅ Log berhasil dibuat via Query Builder! ID: " . $logId . "\n";
    } catch (\Exception $e2) {
        echo "❌ Masih error: " . $e2->getMessage() . "\n";
        
        // Debug foreign key
        echo "\n🔧 Debug Foreign Key:\n";
        $exists = DB::table('users')->where('id', $userId)->exists();
        echo "User ID " . $userId . " exists: " . ($exists ? 'YES' : 'NO') . "\n";
    }
}

// 4. Cek semua log
echo "\n📊 Database Status:\n";
echo "- Total users: " . User::count() . "\n";
echo "- Total peminjaman: " . Peminjaman::count() . "\n";
echo "- Total log aktivitas: " . DB::table('log_aktivitas')->count() . "\n";

// 5. Tampilkan log terbaru
$logs = DB::table('log_aktivitas')
    ->leftJoin('users', 'log_aktivitas.user_id', '=', 'users.id')
    ->select('log_aktivitas.*', 'users.name as user_name')
    ->orderBy('log_aktivitas.id', 'desc')
    ->limit(5)
    ->get();

if ($logs->count() > 0) {
    echo "\n📋 Log terbaru:\n";
    foreach ($logs as $log) {
        echo "[" . $log->created_at . "] ";
        echo "ID: " . $log->id . " | ";
        echo "User: " . ($log->user_name ?: 'ID:' . $log->user_id) . " | ";
        echo "Role: " . $log->role . " | ";
        echo "Aktivitas: " . substr($log->aktivitas, 0, 50) . "...\n";
    }
}

echo "\n🌐 Buka halaman: http://localhost:8000/admin/logaktivitas\n";
echo "✅ Test selesai!\n";