@extends('layouts-admin.admin')

@section('content')
<div class="container-fluid">
    <!-- HEADER -->
    <div class="bg-white rounded-xl shadow p-5 mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.dataalat.index') }}"
               class="w-10 h-10 flex items-center justify-center rounded-lg
                      bg-slate-100 text-slate-600 hover:bg-slate-200 transition">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-xl font-semibold text-slate-800">Detail Alat</h1>
                <p class="text-sm text-slate-500">Informasi lengkap alat</p>
            </div>
        </div>
    </div>

    <!-- DETAIL CARD -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informasi Utama -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow p-6">
                <div class="flex items-start justify-between mb-6">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-800">{{ $dataalat->nama_alat }}</h2>
                        <p class="text-sm text-slate-500">{{ $dataalat->kode_alat }}</p>
                    </div>
                    @php
                    $badgeColors = [
                        'baik' => 'bg-green-100 text-green-800',
                        'rusak_ringan' => 'bg-yellow-100 text-yellow-800',
                        'rusak_berat' => 'bg-red-100 text-red-800',
                        'perlu_perbaikan' => 'bg-orange-100 text-orange-800'
                    ];
                    $color = $badgeColors[$dataalat->kondisi] ?? 'bg-gray-100 text-gray-800';
                    @endphp
                    <span class="px-4 py-1.5 rounded-full text-sm font-medium {{ $color }}">
                        {{ ucfirst(str_replace('_', ' ', $dataalat->kondisi)) }}
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Kategori -->
                    <div>
                        <h3 class="text-sm font-medium text-slate-600 mb-1">Kategori</h3>
                        <p class="text-slate-800">{{ $dataalat->kategori->nama_kategori ?? '-' }}</p>
                    </div>

                    <!-- Stok -->
                    <div>
                        <h3 class="text-sm font-medium text-slate-600 mb-1">Stok Tersedia</h3>
                        <p class="text-xl font-semibold {{ $dataalat->stok > 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $dataalat->stok }} unit
                        </p>
                    </div>

                    <!-- Lokasi -->
                    <div>
                        <h3 class="text-sm font-medium text-slate-600 mb-1">Lokasi</h3>
                        <p class="text-slate-800">{{ $dataalat->lokasi ?? '-' }}</p>
                    </div>

                    <!-- Tanggal Dibuat -->
                    <div>
                        <h3 class="text-sm font-medium text-slate-600 mb-1">Tanggal Ditambahkan</h3>
                        <p class="text-slate-800">{{ $dataalat->created_at->format('d F Y H:i') }}</p>
                    </div>

                    <!-- Terakhir Update -->
                    <div>
                        <h3 class="text-sm font-medium text-slate-600 mb-1">Terakhir Diupdate</h3>
                        <p class="text-slate-800">{{ $dataalat->updated_at->format('d F Y H:i') }}</p>
                    </div>
                </div>

            </div>
        </div>

        <!-- Action Sidebar -->
        <div>
            <div class="bg-white rounded-xl shadow p-6 mb-6">
                <h3 class="text-sm font-medium text-slate-600 mb-4">Aksi</h3>
                <div class="space-y-3">
                    <a href="{{ route('admin.dataalat.edit', $dataalat->id) }}"
                       class="w-full px-4 py-2.5 bg-yellow-500 text-white rounded-lg
                              hover:bg-yellow-600 transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-edit"></i>
                        Edit Alat
                    </a>
                    
                    <form action="{{ route('admin.dataalat.destroy', $dataalat->id) }}" 
                          method="POST" onsubmit="return confirm('Hapus alat ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-full px-4 py-2.5 bg-red-500 text-white rounded-lg
                                       hover:bg-red-600 transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-trash"></i>
                            Hapus Alat
                        </button>
                    </form>
                </div>
            </div>

            <!-- Info Status -->
            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="text-sm font-medium text-slate-600 mb-4">Status Alat</h3>
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm text-slate-600">Ketersediaan</span>
                            <span class="text-sm font-medium {{ $dataalat->stok > 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $dataalat->stok > 0 ? 'Tersedia' : 'Habis' }}
                            </span>
                        </div>
                        <div class="h-2 bg-slate-200 rounded-full overflow-hidden">
                            <div class="h-full {{ $dataalat->stok > 0 ? 'bg-green-500' : 'bg-red-500' }}" 
                                 style="width: {{ min($dataalat->stok * 10, 100) }}%"></div>
                        </div>
                    </div>
                    
                    <div class="p-4 bg-blue-50 rounded-lg border border-blue-100">
                        <h4 class="text-sm font-medium text-blue-800 mb-2">
                            <i class="fa-solid fa-info-circle mr-1"></i> Catatan
                        </h4>
                        <p class="text-xs text-blue-700">
                            Pastikan data alat selalu diperbarui untuk monitoring inventaris.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection