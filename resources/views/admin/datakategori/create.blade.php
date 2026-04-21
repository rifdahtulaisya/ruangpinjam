@extends('layouts-admin.admin')

@section('content')

<!-- HEADER BOX -->
<div class="bg-white rounded-xl shadow p-5 mb-6">
    <div class="flex items-center gap-4">
        <!-- BACK BUTTON -->
        <a href="{{ route('admin.datakategori.index') }}"
           class="w-10 h-10 flex items-center justify-center rounded-lg
                  bg-slate-100 text-slate-600 hover:bg-slate-200 transition">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-xl font-semibold text-slate-800">Tambah Kategori</h1>
            <p class="text-sm text-slate-500">Tambah data kategori baru</p>
        </div>
    </div>
</div>

<!-- ALERT MESSAGE -->
@if(session('success'))
<div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <i class="fa-solid fa-check-circle text-green-500"></i>
            <p class="font-medium">{{ session('success') }}</p>
        </div>
        <button type="button" onclick="this.parentElement.parentElement.remove()" class="text-green-500 hover:text-green-700">
            <i class="fa-solid fa-times"></i>
        </button>
    </div>
</div>
@endif

<!-- FORM CARD -->
<div class="flex flex-col lg:flex-row gap-6">
   <!-- FORM SECTION -->
<div class="bg-white rounded-xl shadow p-6 flex-1">
    <form action="{{ route('admin.datakategori.storeMany') }}" method="POST">
        @csrf

        {{-- Error global --}}
        @if($errors->any())
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div id="kategori-list" class="grid gap-3">
            {{-- Row pertama (tidak bisa dihapus) --}}
            <div class="kategori-row flex items-center gap-2">
                <div class="flex-1">
                    <input type="text"
                           name="nama_kategori[]"
                           value="{{ old('nama_kategori.0') }}"
                           required
                           class="w-full px-4 py-2.5 border border-slate-300 rounded-lg
                                  focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                                  placeholder:text-slate-400"
                           placeholder="Nama kategori #1">
                </div>
                <button type="button" disabled
                        class="w-10 h-10 flex items-center justify-center rounded-lg
                               bg-slate-100 text-slate-300 cursor-not-allowed shrink-0">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </div>
        </div>

        {{-- Tombol Tambah Baris --}}
        <button type="button" id="btn-add-row"
                class="mt-4 w-full py-2.5 rounded-lg border-2 border-dashed border-indigo-300
                       text-indigo-500 hover:bg-indigo-50 hover:border-indigo-400
                       transition flex items-center justify-center gap-2 text-sm font-medium">
            <i class="fa-solid fa-plus"></i> Tambah Kategori
        </button>

        {{-- Info jumlah --}}
        <p class="text-xs text-slate-400 mt-2 text-center">
            <span id="row-count">1</span> kategori akan dibuat
        </p>

        <!-- BUTTON SUBMIT -->
        <div class="flex justify-end gap-3 pt-6 border-t border-slate-100 mt-6">
            <a href="{{ route('admin.datakategori.index') }}"
               class="px-5 py-2.5 rounded-lg bg-slate-200 text-slate-700 hover:bg-slate-300
                      transition flex items-center gap-2">
                <i class="fa-solid fa-times"></i> Batal
            </a>
            <button type="submit"
                    class="px-5 py-2.5 rounded-lg bg-indigo-500 text-white hover:bg-indigo-600
                           transition flex items-center gap-2">
                <i class="fa-solid fa-floppy-disk"></i>
                Simpan Semua
            </button>
        </div>
    </form>
</div>

    <!-- CATEGORY SECTION -->
<div class="hidden lg:flex w-full lg:w-1/3 xl:w-1/4 flex-col items-center justify-center">
    <div class="bg-white rounded-xl shadow p-8 w-full h-full flex flex-col items-center justify-center">
        <!-- Illustration -->
        <div class="mb-6">
            <div class="w-48 h-48 mx-auto bg-gradient-to-br from-blue-100 to-cyan-100 
                        rounded-full flex items-center justify-center">
                <i class="fa-solid fa-tags text-6xl text-blue-500"></i>
            </div>
        </div>
        
        <!-- Informasi -->
        <div class="text-center">
            <h3 class="text-lg font-semibold text-slate-800 mb-2">Buat Kategori Baru</h3>
            
            <!-- Tips Box -->
            <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-100">
                <h4 class="text-sm font-medium text-blue-800 mb-2 flex items-center gap-2">
                    <i class="fa-solid fa-lightbulb"></i> Tips Kategori
                </h4>
                <ul class="text-xs text-blue-700 space-y-1 text-left">
                    <li>• Gunakan nama kategori yang jelas dan singkat</li>
                    <li>• Hindari duplikasi dengan kategori yang sudah ada</li>
                    <li>• Nama kategori dapat diubah nanti jika diperlukan</li>
                </ul>
            </div>
        </div>
    </div>
</div>
</div>
@push('scripts')
<script>
    const list    = document.getElementById('kategori-list');
    const btnAdd  = document.getElementById('btn-add-row');
    const counter = document.getElementById('row-count');

    function updateCounter() {
        counter.textContent = list.querySelectorAll('.kategori-row').length;
    }

    function createRow(index) {
        const div = document.createElement('div');
        div.className = 'kategori-row flex items-center gap-2';
        div.innerHTML = `
            <div class="flex-1">
                <input type="text"
                       name="nama_kategori[]"
                       required
                       class="w-full px-4 py-2.5 border border-slate-300 rounded-lg
                              focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                              placeholder:text-slate-400"
                       placeholder="Nama kategori #${index}">
            </div>
            <button type="button"
                    class="btn-remove w-10 h-10 flex items-center justify-center rounded-lg
                           bg-red-50 text-red-400 hover:bg-red-100 hover:text-red-600
                           transition shrink-0">
                <i class="fa-solid fa-trash"></i>
            </button>
        `;
        div.querySelector('.btn-remove').addEventListener('click', () => {
            div.remove();
            updateCounter();
        });
        return div;
    }

    btnAdd.addEventListener('click', () => {
        const index = list.querySelectorAll('.kategori-row').length + 1;
        list.appendChild(createRow(index));
        updateCounter();
    });
</script>
@endpush
@endsection