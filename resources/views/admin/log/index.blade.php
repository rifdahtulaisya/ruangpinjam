@extends('layouts-admin.admin')

@section('content')

<div class="bg-white rounded-2xl shadow-xl border border-slate-200">

    {{-- HEADER --}}
    <div class="p-6 border-b border-slate-200 flex justify-between items-center">

        <h1 class="text-xl font-bold flex items-center gap-2">
            <i class="fas fa-history text-indigo-500"></i>
            Log Aktivitas
        </h1>

        <span class="text-sm text-slate-500">
            Total: {{ $logs->total() }} aktivitas
        </span>

    </div>


    {{-- FILTER --}}
    <div class="p-4 border-b border-slate-200 flex gap-2 flex-wrap">

        @php
        function btn($label, $value, $filter){
            $active = $filter == $value;
            return $active
                ? 'bg-indigo-500 text-white'
                : 'bg-white hover:bg-slate-100';
        }
        @endphp

        <a href="{{ route('admin.logaktivitas.index') }}"
           class="px-4 py-2 rounded-xl border {{ btn('Semua','',$filter) }}">
           Semua
        </a>

        <a href="?filter=user"
           class="px-4 py-2 rounded-xl border {{ btn('User','user',$filter) }}">
           User
        </a>

        <a href="?filter=petugas"
           class="px-4 py-2 rounded-xl border {{ btn('Petugas','petugas',$filter) }}">
           Petugas
        </a>

        <a href="?filter=admin"
           class="px-4 py-2 rounded-xl border {{ btn('Admin','admin',$filter) }}">
           Admin
        </a>

        <a href="?filter=peminjaman"
           class="px-4 py-2 rounded-xl border {{ btn('Peminjaman','peminjaman',$filter) }}">
           Peminjaman
        </a>

    </div>


    {{-- TABLE --}}
    <div class="overflow-x-auto">

        <table class="w-full">

            <thead class="bg-slate-50">

                <tr class="text-left text-slate-600">

                    <th class="p-4">Waktu</th>
                    <th class="p-4">User</th>
                    <th class="p-4">Role</th>
                    <th class="p-4">Aktivitas</th>

                </tr>

            </thead>


            <tbody>

                @forelse($logs as $log)

                <tr class="border-t hover:bg-slate-50 transition">

                    {{-- WAKTU --}}
                    <td class="p-4 whitespace-nowrap">

                        <div class="flex items-center gap-2">

                            <i class="fas fa-clock text-slate-400"></i>

                            <div>

                                <div class="font-medium">
                                    {{ $log->created_at->format('d M Y') }}
                                </div>

                                <div class="text-xs text-slate-500">
                                    {{ $log->created_at->format('H:i') }}
                                </div>

                            </div>

                        </div>

                    </td>


                    {{-- USER --}}
                    <td class="p-4">

                        <div class="flex items-center gap-2">

                            <i class="fas fa-user text-slate-400"></i>

                            {{ $log->user->name ?? '-' }}

                        </div>

                    </td>


                    {{-- ROLE --}}
                    <td class="p-4">

                        @if($log->role=='admin')

                        <span class="bg-red-100 text-red-600 px-3 py-1 rounded-full text-xs">
                            Admin
                        </span>

                        @elseif($log->role=='petugas')

                        <span class="bg-blue-100 text-blue-600 px-3 py-1 rounded-full text-xs">
                            Petugas
                        </span>

                        @else

                        <span class="bg-green-100 text-green-600 px-3 py-1 rounded-full text-xs">
                            User
                        </span>

                        @endif

                    </td>


                    {{-- AKTIVITAS --}}
                    <td class="p-4">

                        <div class="flex items-center gap-2">

                            <i class="fas fa-circle text-indigo-400 text-[8px]"></i>

                            {{ $log->aktivitas }}

                        </div>

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="4" class="text-center p-10 text-slate-400">

                        <i class="fas fa-box-open text-2xl mb-2"></i>

                        <div>
                            Belum ada aktivitas
                        </div>

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    {{-- PAGINATION --}}
    <div class="p-4 border-t border-slate-200">

        {{ $logs->links() }}

    </div>


</div>

@endsection
