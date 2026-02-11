@extends('layouts-admin.admin')

@section('title', 'Import Data Peminjam')

@section('content')

<!-- HEADER -->
<div class="bg-white rounded-xl shadow p-5 mb-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.datapeminjam.index') }}" 
           class="w-10 h-10 flex items-center justify-center rounded-lg bg-slate-100 hover:bg-slate-200 transition">
            <i class="fa-solid fa-arrow-left text-slate-600"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-slate-700">Import Data Peminjam</h2>
            <p class="text-sm text-slate-500">Upload file Excel untuk menambahkan data peminjam</p>
        </div>
    </div>
</div>

<!-- ALERT MESSAGES -->
@if(session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-6">
    <div class="flex items-center gap-2">
        <i class="fa-solid fa-circle-check"></i>
        <span>{{ session('success') }}</span>
    </div>
</div>
@endif

@if(session('warning'))
<div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded-xl mb-6">
    <div class="flex items-center gap-2">
        <i class="fa-solid fa-triangle-exclamation"></i>
        <span>{{ session('warning') }}</span>
    </div>
</div>
@endif

@if(session('error'))
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-6">
    <div class="flex items-center gap-2">
        <i class="fa-solid fa-circle-xmark"></i>
        <span>{{ session('error') }}</span>
    </div>
</div>
@endif

@if($errors->any())
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-6">
    <div class="flex items-start gap-2">
        <i class="fa-solid fa-circle-xmark mt-1"></i>
        <div>
            <p class="font-semibold mb-2">Terjadi kesalahan:</p>
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endif

<!-- IMPORT ERRORS -->
@if(session('import_errors'))
<div class="bg-red-50 border border-red-200 rounded-xl p-5 mb-6">
    <h3 class="text-lg font-semibold text-red-700 mb-3">
        <i class="fa-solid fa-exclamation-circle"></i> Data yang Gagal Diimport
    </h3>
    <div class="max-h-96 overflow-y-auto">
        <table class="w-full text-sm">
            <thead class="bg-red-100 sticky top-0">
                <tr>
                    <th class="px-4 py-2 text-left">Baris</th>
                    <th class="px-4 py-2 text-left">Data</th>
                    <th class="px-4 py-2 text-left">Error</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-red-100">
                @foreach(session('import_errors') as $error)
                <tr>
                    <td class="px-4 py-2 font-medium">{{ $error['row'] }}</td>
                    <td class="px-4 py-2">
                        <div class="text-xs space-y-1">
                            <div><strong>Nama:</strong> {{ $error['data']['name'] ?? '-' }}</div>
                            <div><strong>Username:</strong> {{ $error['data']['username'] ?? '-' }}</div>
                            <div><strong>Email:</strong> {{ $error['data']['email'] ?? '-' }}</div>
                        </div>
                    </td>
                    <td class="px-4 py-2">
                        <ul class="list-disc list-inside text-xs text-red-600">
                            @foreach($error['errors'] as $msg)
                            <li>{{ $msg }}</li>
                            @endforeach
                        </ul>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<div class="grid md:grid-cols-2 gap-6">
    <!-- UPLOAD FORM -->
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="text-lg font-semibold text-slate-700 mb-4">
            <i class="fa-solid fa-upload text-indigo-600"></i> Upload File
        </h3>

        <form action="{{ route('admin.datapeminjam.import.process') }}" method="POST" enctype="multipart/form-data" id="importForm">
            @csrf

            <div class="mb-6">
                <label class="block text-sm font-medium text-slate-700 mb-2">
                    Pilih File Excel/CSV
                </label>
                <div class="relative">
                    <input type="file" 
                           name="file" 
                           id="fileInput"
                           accept=".xlsx,.xls,.csv"
                           class="block w-full text-sm text-slate-500
                                  file:mr-4 file:py-2.5 file:px-4
                                  file:rounded-lg file:border-0
                                  file:text-sm file:font-semibold
                                  file:bg-indigo-50 file:text-indigo-700
                                  hover:file:bg-indigo-100
                                  cursor-pointer"
                           required>
                </div>
                <p class="mt-2 text-xs text-slate-500">
                    Format: .xlsx, .xls, .csv (Maks. 2MB)
                </p>
            </div>

            <div id="fileInfo" class="hidden mb-6 p-4 bg-slate-50 rounded-lg">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-file-excel text-green-600 text-2xl"></i>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-slate-700" id="fileName"></p>
                        <p class="text-xs text-slate-500" id="fileSize"></p>
                    </div>
                    <button type="button" onclick="clearFile()" class="text-red-500 hover:text-red-700">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit" 
                        class="flex-1 bg-indigo-500 hover:bg-indigo-600 text-white px-5 py-2.5 rounded-xl shadow transition">
                    <i class="fa-solid fa-upload mr-2"></i>
                    Import Data
                </button>
                <a href="{{ route('admin.datapeminjam.index') }}" 
                   class="px-5 py-2.5 border border-slate-300 rounded-xl hover:bg-slate-50 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>

    <!-- INSTRUCTIONS -->
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="text-lg font-semibold text-slate-700 mb-4">
            <i class="fa-solid fa-circle-info text-blue-600"></i> Panduan Import
        </h3>

        <div class="space-y-4 text-sm text-slate-600">
            <div class="flex gap-3">
                <i class="fa-solid fa-1 w-6 h-6 flex items-center justify-center rounded-full bg-indigo-100 text-indigo-600 flex-shrink-0"></i>
                <div>
                    <p class="font-medium text-slate-700">Download Template</p>
                    <p class="text-xs">Unduh template Excel di bawah ini</p>
                </div>
            </div>

            <div class="flex gap-3">
                <i class="fa-solid fa-2 w-6 h-6 flex items-center justify-center rounded-full bg-indigo-100 text-indigo-600 flex-shrink-0"></i>
                <div>
                    <p class="font-medium text-slate-700">Isi Data</p>
                    <p class="text-xs">Lengkapi data sesuai kolom yang tersedia</p>
                </div>
            </div>

            <div class="flex gap-3">
                <i class="fa-solid fa-3 w-6 h-6 flex items-center justify-center rounded-full bg-indigo-100 text-indigo-600 flex-shrink-0"></i>
                <div>
                    <p class="font-medium text-slate-700">Upload File</p>
                    <p class="text-xs">Upload file yang sudah diisi</p>
                </div>
            </div>
        </div>

        <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
            <p class="text-sm font-medium text-yellow-800 mb-2">
                <i class="fa-solid fa-lightbulb"></i> Catatan Penting:
            </p>
            <ul class="text-xs text-yellow-700 space-y-1 list-disc list-inside">
                <li>Kolom <strong>Nama, Username, Password</strong> wajib diisi</li>
                <li>Kolom <strong>Email</strong> bersifat opsional (boleh kosong)</li>
                <li>Username harus unik (tidak boleh sama)</li>
                <li>Email harus valid jika diisi</li>
                <li>Password minimal 6 karakter</li>
                <li>Status: <code>active</code> atau <code>inactive</code></li>
            </ul>
        </div>

        <div class="mt-6">
            <a href="{{ route('admin.datapeminjam.import.template') }}" 
               class="flex items-center justify-center gap-2 w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2.5 rounded-xl shadow transition">
                <i class="fa-solid fa-download"></i>
                Download Template Excel
            </a>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.getElementById('fileInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const fileInfo = document.getElementById('fileInfo');
        const fileName = document.getElementById('fileName');
        const fileSize = document.getElementById('fileSize');
        
        fileName.textContent = file.name;
        fileSize.textContent = formatFileSize(file.size);
        fileInfo.classList.remove('hidden');
    }
});

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
}

function clearFile() {
    document.getElementById('fileInput').value = '';
    document.getElementById('fileInfo').classList.add('hidden');
}
</script>
@endsection