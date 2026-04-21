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
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-stretch">
            <!-- Informasi Utama -->
            <div class="lg:col-span-2 h-full">
                <div class="bg-white rounded-xl shadow p-6 h-full">
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
                                'perlu_perbaikan' => 'bg-orange-100 text-orange-800',
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
                                {{ $dataalat->stok }} Stok
                            </p>
                        </div>

                        <!-- Tanggal Dibuat -->
                        <div>
                            <h3 class="text-sm font-medium text-slate-600 mb-1">Tanggal Ditambahkan</h3>
                            <p class="text-slate-800">{{ $dataalat->created_at->format('d F Y') }}</p>
                        </div>

                        <!-- Terakhir Update -->
                        <div>
                            <h3 class="text-sm font-medium text-slate-600 mb-1">Terakhir Diupdate</h3>
                            <p class="text-slate-800">{{ $dataalat->updated_at->format('d F Y') }}</p>
                        </div>
                    </div>

                </div>
            </div>

            <!-- FOTO SIDEBAR -->
            <div>
                <div class="bg-white rounded-xl shadow p-6 h-full flex flex-col">
                    <h3 class="text-sm font-medium text-slate-600 mb-4">Foto Alat</h3>

                    <div class="flex-1 flex items-center justify-center">
                        @if ($dataalat->foto)
                            <img src="{{ asset('storage/' . $dataalat->foto) }}" alt="{{ $dataalat->nama_alat }}"
                                class="w-full h-full object-cover rounded-xl">
                        @else
                            <div class="w-full h-full bg-slate-100 rounded-xl flex items-center justify-center">
                                <i class="fa-solid fa-camera text-3xl text-slate-400"></i>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
