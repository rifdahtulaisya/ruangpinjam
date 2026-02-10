@extends('layouts-admin.admin')

@section('title', 'Data Peminjam')

@section('content')

<!-- HEADER BOX -->
<div class="bg-white rounded-xl shadow p-5 mb-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 flex items-center justify-center rounded-xl
                        bg-indigo-100 text-indigo-600">
                <i class="fa-solid fa-users text-lg"></i>
            </div>
            <div>
                <p class="text-sm text-slate-400">Total Peminjam</p>
                <h2 class="text-2xl font-bold text-slate-700">
                    {{ $users->total() }}
                </h2>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 sm:gap-4">
    <!-- Button Unduh -->
    <a href=""
       class="flex items-center justify-center gap-2 bg-red-500 hover:bg-red-600
              text-white px-4 sm:px-5 py-2.5 rounded-xl shadow transition
              w-full sm:w-auto">
         <i class="fa-solid fa-download"></i>
        <span class="text-sm sm:text-base">Unduh</span>
    </a>

    <!-- Button Import -->
    <a href=""
       class="flex items-center justify-center gap-2 bg-yellow-500 hover:bg-yellow-600
              text-white px-4 sm:px-5 py-2.5 rounded-xl shadow transition
              w-full sm:w-auto">
         <i class="fa-solid fa-upload"></i>
        <span class="text-sm sm:text-base">Import</span>
    </a>

    <!-- Button Tambah Peminjam -->
    <a href="{{ route('admin.datapeminjam.create') }}"
       class="flex items-center justify-center gap-2 bg-indigo-500 hover:bg-indigo-600
              text-white px-4 sm:px-5 py-2.5 rounded-xl shadow transition
              w-full sm:w-auto">
         <i class="fa-solid fa-plus"></i>
        <span class="text-sm sm:text-base">Tambah Peminjam</span>
    </a>
</div>
    </div>
</div>

<!-- SEARCH & FILTER -->
<div class="bg-white rounded-xl shadow p-4 mb-6">
    <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
        <!-- SEARCH BOX - Form untuk auto submit -->
        <div class="w-full md:w-auto">
            <form id="searchForm" method="GET" action="{{ route('admin.datapeminjam.index') }}" class="relative w-full md:w-72">
                <div class="relative">
                    <input type="text" 
                           name="search" 
                           id="searchInput"
                           value="{{ request('search') }}"
                           placeholder="Cari nama atau email..."
                           class="w-full pl-10 pr-4 py-2.5 border border-slate-300 rounded-lg 
                                  focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <i class="fa-solid fa-search absolute left-3 top-3.5 text-slate-400"></i>
                </div>
                
                <!-- Hidden inputs untuk maintain per_page value -->
                @if(request('per_page'))
                <input type="hidden" name="per_page" value="{{ request('per_page') }}">
                @endif
            </form>
        </div>

        <!-- ROWS PER PAGE -->
        <div class="flex items-center gap-3">
            <span class="text-sm text-slate-600">Tampilkan:</span>
            <div class="flex bg-slate-100 rounded-lg p-1">
                @foreach([5, 10, 15, 20] as $perPage)
                <a href="{{ route('admin.datapeminjam.index', array_merge(request()->except('page'), ['per_page' => $perPage])) }}"
                   class="px-3 py-1 rounded-md text-sm font-medium transition
                          {{ request('per_page', 5) == $perPage ? 'bg-white text-indigo-600 shadow' : 'text-slate-600 hover:text-indigo-600' }}">
                    {{ $perPage }}
                </a>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- TABLE CARD -->
<div class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-slate-600">
            <thead class="bg-slate-50 text-xs uppercase text-slate-500">
                <tr>
                    <th class="px-6 py-4">No</th>
                    <th class="px-6 py-4">Name</th>
                    <th class="px-6 py-4">Username</th>
                    <th class="px-6 py-4">Email</th>
                    <th class="px-6 py-4">Status</th>
                   
                    <th class="px-6 py-4 text-center">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($users as $user)
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-6 py-4 font-medium">
                        {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600">
                                <i class="fa-solid fa-user"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-slate-700">
                                    {{ $user->name }}
                                </p>
                                <p class="text-xs text-slate-400">
                                    ID: {{ $user->id }}
                                </p>
                            </div>
                        </div>
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            {{ $user->username }}
                        </div>
                    </td>

            <td class="px-6 py-4">
                @if(empty(trim($user->email)))
                    <span class="text-red-500 italic">nothing</span>
                @else
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-envelope text-slate-400 text-xs"></i>
                        <span class="text-slate-700">{{ $user->email }}</span>
                    </div>
                @endif
            </td>

                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-xs font-medium
                            @if($user->status == 'active')
                                bg-green-100 text-green-600
                            @elseif($user->status == 'inactive')
                                bg-red-100 text-red-600
                            @else
                                bg-green-100 text-green-600
                            @endif">
                            {{ $user->status ?? 'active' }}
                        </span>
                    </td>

                  

                    <td class="px-6 py-4">
    <div class="flex justify-center gap-2">
        <!-- View Button -->
        <a href="{{ route('admin.datapeminjam.show', $user->id) }}"
           class="w-8 h-8 flex items-center justify-center rounded-lg 
                  bg-blue-100 text-blue-600 hover:bg-blue-200 transition-all duration-200"
           title="Lihat Detail">
            <i class="fa fa-eye text-sm"></i>
        </a>

        <!-- Edit Button -->
        <a href="{{ route('admin.datapeminjam.edit', $user->id) }}"
           class="w-8 h-8 flex items-center justify-center rounded-lg 
                  bg-yellow-100 text-yellow-600 hover:bg-yellow-200 transition-all duration-200"
           title="Edit">
            <i class="fa fa-edit text-sm"></i>
        </a>

        <!-- Delete Button -->
        <form action="{{ route('admin.datapeminjam.destroy', $user->id) }}" 
              method="POST" 
              class="delete-form inline-block">
            @csrf
            @method('DELETE')
            <button type="submit" 
                    class="w-8 h-8 flex items-center justify-center rounded-lg 
                           bg-red-100 text-red-600 hover:bg-red-200 transition-all duration-200"
                    title="Hapus">
                <i class="fa fa-trash text-sm"></i>
            </button>
        </form>
    </div>
</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-slate-500">
                        <div class="flex flex-col items-center">
                            <i class="fa-solid fa-users-slash text-3xl mb-3 text-slate-300"></i>
                            <p class="text-slate-500">Tidak ada data peminjam</p>
                            @if(request()->has('search'))
                            <p class="text-sm text-slate-400 mt-1">
                                Hasil pencarian "<span class="font-medium">{{ request('search') }}</span>" tidak ditemukan
                            </p>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- PAGINATION -->
    @if($users->hasPages())
    <div class="px-6 py-4 border-t border-slate-100">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="text-sm text-slate-500">
                Menampilkan 
                <span class="font-medium">{{ $users->firstItem() ?? 0 }}</span> 
                - 
                <span class="font-medium">{{ $users->lastItem() ?? 0 }}</span> 
                dari 
                <span class="font-medium">{{ $users->total() }}</span> 
                data
            </div>
            
            <div class="flex items-center gap-2">
                <!-- Previous Button -->
                @if($users->onFirstPage())
                <span class="px-3 py-1.5 rounded-lg bg-slate-100 text-slate-400 cursor-not-allowed">
                    <i class="fa-solid fa-chevron-left"></i>
                </span>
                @else
                <a href="{{ $users->previousPageUrl() . (request('search') ? '&search=' . request('search') : '') . (request('per_page') ? '&per_page=' . request('per_page') : '') }}" 
                   class="px-3 py-1.5 rounded-lg bg-indigo-100 text-indigo-600 hover:bg-indigo-200 transition">
                    <i class="fa-solid fa-chevron-left"></i>
                </a>
                @endif

                <!-- Page Numbers -->
                @php
                    $current = $users->currentPage();
                    $last = $users->lastPage();
                    $start = max(1, $current - 1);
                    $end = min($last, $current + 1);
                    
                    if ($last <= 5) {
                        $start = 1;
                        $end = $last;
                    } else {
                        if ($current <= 3) {
                            $start = 1;
                            $end = 5;
                        } elseif ($current >= $last - 2) {
                            $start = $last - 4;
                            $end = $last;
                        }
                    }
                @endphp

                @for ($i = $start; $i <= $end; $i++)
                <a href="{{ $users->url($i) . (request('search') ? '&search=' . request('search') : '') . (request('per_page') ? '&per_page=' . request('per_page') : '') }}" 
                   class="px-3 py-1.5 min-w-[40px] text-center rounded-lg transition 
                          {{ $i == $current ? 'bg-indigo-500 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    {{ $i }}
                </a>
                @endfor

                <!-- Next Button -->
                @if($users->hasMorePages())
                <a href="{{ $users->nextPageUrl() . (request('search') ? '&search=' . request('search') : '') . (request('per_page') ? '&per_page=' . request('per_page') : '') }}" 
                   class="px-3 py-1.5 rounded-lg bg-indigo-100 text-indigo-600 hover:bg-indigo-200 transition">
                    <i class="fa-solid fa-chevron-right"></i>
                </a>
                @else
                <span class="px-3 py-1.5 rounded-lg bg-slate-100 text-slate-400 cursor-not-allowed">
                    <i class="fa-solid fa-chevron-right"></i>
                </span>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>

@endsection

@section('scripts')
<script>
// Auto submit search form on typing
document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.getElementById('searchForm');
    const searchInput = document.getElementById('searchInput');
    let searchTimeout;

    if (searchInput) {
        // Auto submit after typing stops
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            
            // Show loading indicator (optional)
            const searchIcon = this.parentElement.querySelector('.fa-search');
            if (searchIcon) {
                searchIcon.classList.remove('fa-search');
                searchIcon.classList.add('fa-spinner', 'fa-spin');
            }
            
            searchTimeout = setTimeout(() => {
                // Submit the form
                searchForm.submit();
            }, 800); // 800ms delay
        });

        // Clear search button
        const clearBtn = document.getElementById('clearSearch');
        if (clearBtn) {
            clearBtn.addEventListener('click', function() {
                searchInput.value = '';
                searchForm.submit();
            });
        }

        // Allow Enter key to submit immediately
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                clearTimeout(searchTimeout);
                searchForm.submit();
            }
        });
    }

    // Delete confirmation
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data peminjam akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    });
});
</script>

<style>
/* Optional: Style for loading spinner */
.fa-spinner {
    color: #4f46e5 !important;
}
</style>