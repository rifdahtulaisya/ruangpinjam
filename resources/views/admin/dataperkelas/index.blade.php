@extends('layouts-admin.admin')

@section('content')

<!-- HEADER BOX -->
<div class="bg-white rounded-xl shadow p-5 mb-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 flex items-center justify-center rounded-xl
                        bg-indigo-100 text-indigo-600">
                <i class="fa-solid fa-building-columns text-lg"></i>
            </div>
            <div>
                <h1 class="text-xl font-semibold text-slate-800">Data Peminjam Per Kelas</h1>
                <p class="text-sm text-slate-500">Kelompokkan peminjam berdasarkan kelas & jurusan</p>
            </div>
        </div>
    </div>
</div>

<!-- STATISTIC CARDS -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4 mb-6">
    @foreach($groups as $group)
    <a href="{{ route('admin.dataperkelas.index', ['kelas' => $group->kelas_jurusan]) }}"
       class="bg-white rounded-xl shadow p-5 hover:shadow-lg transition cursor-pointer
              {{ $selectedKelas == $group->kelas_jurusan ? 'ring-2 ring-indigo-500' : '' }}">
        <div class="flex flex-col">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-slate-600 truncate">
                    {{ $group->kelas_jurusan }}
                </span>
                <span class="text-xs text-slate-400">
                    <i class="fa-solid fa-users"></i>
                </span>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl font-bold text-slate-800">{{ $group->total }}</span>
                <span class="text-sm text-slate-500">peminjam</span>
            </div>
            <div class="mt-3 h-2 bg-slate-100 rounded-full overflow-hidden">
                @php
                    $maxCount = $groups->max('total');
                    $percentage = $maxCount > 0 ? ($group->total / $maxCount) * 100 : 0;
                @endphp
                <div class="h-full bg-gradient-to-r from-indigo-400 to-indigo-600 rounded-full"
                     style="width: {{ $percentage }}%"></div>
            </div>
        </div>
    </a>
    @endforeach
</div>

<!-- DETAIL TABLE (jika kelas dipilih) -->
@if($selectedKelas)
<div class="bg-white rounded-xl shadow p-6 mb-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-lg font-semibold text-slate-800">
                Detail Peminjam - {{ $selectedKelas }}
            </h2>
            <p class="text-sm text-slate-500 mt-1">
                Total: {{ $users->total() }} peminjam
            </p>
        </div>
        
        <a href="{{ route('admin.dataperkelas.index') }}"
           class="flex items-center gap-2 text-indigo-600 hover:text-indigo-700">
            <i class="fa-solid fa-times"></i>
            Tutup Detail
        </a>
    </div>

    @if($users->count() > 0)
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-slate-600">
            <thead class="bg-slate-50 text-xs uppercase text-slate-500">
                <tr>
                    <th class="px-4 py-3">No</th>
                    <th class="px-4 py-3">Nama Lengkap</th>
                    <th class="px-4 py-3">Username</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($users as $user)
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-4 py-3">
                        {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600">
                                <i class="fa-solid fa-user text-xs"></i>
                            </div>
                            <span class="font-medium text-slate-700">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <code class="bg-slate-100 px-2 py-1 rounded text-xs">{{ $user->username }}</code>
                    </td>
                    <td class="px-4 py-3">
                        @if(empty(trim($user->email)))
                            <span class="text-red-500 italic text-xs">-</span>
                        @else
                            {{ $user->email }}
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-600">
                            Active
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <div class="flex justify-center gap-2">
                            <a href="{{ route('admin.datapeminjam.edit', $user->id) }}"
                               class="w-7 h-7 flex items-center justify-center rounded-lg 
                                      bg-blue-100 text-blue-600 hover:bg-blue-200 transition"
                               title="Edit">
                                <i class="fa-solid fa-pen-to-square text-xs"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <!-- Pagination -->
        @if($users->hasPages())
        <div class="mt-4 pt-4 border-t border-slate-100">
            {{ $users->withQueryString()->links() }}
        </div>
        @endif
    </div>
    @else
    <div class="text-center py-8">
        <i class="fa-solid fa-users-slash text-3xl mb-3 text-slate-300"></i>
        <p class="text-slate-500">Tidak ada data peminjam untuk kelas ini</p>
    </div>
    @endif
</div>
@endif

<!-- INFO BOX -->
<div class="bg-indigo-50 border border-indigo-100 rounded-xl p-5">
    <div class="flex items-start gap-3">
        <i class="fa-solid fa-circle-info text-indigo-500 mt-0.5"></i>
        <div>
            <h4 class="text-sm font-medium text-indigo-800 mb-1">Informasi</h4>
            <p class="text-xs text-indigo-700">
                Klik pada salah satu kartu kelas di atas untuk melihat detail peminjam pada kelas tersebut.
                Data dikelompokkan berdasarkan format: [Tingkat] [Jurusan] [Nomor Kelas]
            </p>
        </div>
    </div>
</div>

@endsection