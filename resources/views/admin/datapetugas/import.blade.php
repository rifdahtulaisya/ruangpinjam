@extends('layouts-admin.admin')

@section('title', 'Import Data Petugas')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <!-- Card Import -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-white/20">
                        <i class="fa-solid fa-users text-white text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-white">Import Data Petugas</h2>
                        <p class="text-yellow-100 text-sm">Import data petugas dari file Excel/CSV</p>
                    </div>
                </div>
            </div>

            <!-- Body -->
            <div class="p-6">
                <!-- Alert Info -->
                <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex items-start gap-3">
                        <i class="fa-solid fa-circle-info text-blue-500 mt-0.5"></i>
                        <div class="text-sm text-blue-700">
                            <p class="font-semibold mb-1">Petunjuk Import:</p>
                            <ul class="list-disc list-inside space-y-1">
                                <li>Format file yang didukung: .xlsx, .xls, .csv (Max 2MB)</li>
                                <li>Kolom yang wajib diisi: Nama Lengkap, Username</li>
                                <li>Kolom opsional: Email, Status, Password</li>
                                <li>Status: active atau inactive (default: active)</li>
                                <li>Jika password tidak diisi, akan digenerate otomatis (8 karakter)</li>
                                <li>Jika username sudah ada, data akan diupdate</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Download Template -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center justify-between flex-wrap gap-3">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-download text-gray-500"></i>
                            <div>
                                <p class="font-medium text-gray-700">Belum punya template?</p>
                                <p class="text-sm text-gray-500">Download template Excel untuk memulai</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.datapetugas.import.template') }}" 
                           class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition flex items-center gap-2">
                            <i class="fa-solid fa-file-excel"></i>
                            <span>Download Template</span>
                        </a>
                    </div>
                </div>

                <!-- Form Import -->
                <form action="{{ route('admin.datapetugas.import.process') }}" method="POST" enctype="multipart/form-data" id="importForm">
                    @csrf
                    
                    <!-- File Input -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Pilih File <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-yellow-500 transition" id="dropzone">
                            <div class="space-y-1 text-center">
                                <i class="fa-solid fa-cloud-upload-alt text-4xl text-gray-400"></i>
                                <div class="flex text-sm text-gray-600">
                                    <label for="file" class="relative cursor-pointer bg-white rounded-md font-medium text-yellow-600 hover:text-yellow-500 focus-within:outline-none">
                                        <span>Upload file</span>
                                        <input id="file" name="file" type="file" class="sr-only" accept=".xlsx,.xls,.csv">
                                    </label>
                                    <p class="pl-1">atau drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">
                                    Excel (xlsx, xls) atau CSV up to 2MB
                                </p>
                            </div>
                        </div>
                        @error('file')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <div id="fileNameDisplay" class="mt-2 text-sm text-gray-600 hidden">
                            <i class="fa-solid fa-file text-yellow-500"></i>
                            <span id="fileName"></span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-3 justify-end">
                        <a href="{{ route('admin.datapetugas.index') }}" 
                           class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                            Batal
                        </a>
                        <button type="submit" 
                                class="px-6 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg transition flex items-center gap-2" 
                                id="submitBtn">
                            <i class="fa-solid fa-upload"></i>
                            <span>Import Data</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('file');
    const fileNameDisplay = document.getElementById('fileNameDisplay');
    const fileName = document.getElementById('fileName');
    const dropzone = document.getElementById('dropzone');
    const form = document.getElementById('importForm');
    const submitBtn = document.getElementById('submitBtn');

    // Handle file selection
    fileInput.addEventListener('change', function(e) {
        if (this.files && this.files[0]) {
            const file = this.files[0];
            fileName.textContent = file.name;
            fileNameDisplay.classList.remove('hidden');
            dropzone.classList.add('border-yellow-500', 'bg-yellow-50');
        } else {
            fileNameDisplay.classList.add('hidden');
            dropzone.classList.remove('border-yellow-500', 'bg-yellow-50');
        }
    });

    // Drag and drop
    dropzone.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('border-yellow-500', 'bg-yellow-50');
    });

    dropzone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.classList.remove('border-yellow-500', 'bg-yellow-50');
    });

    dropzone.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('border-yellow-500', 'bg-yellow-50');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            const event = new Event('change');
            fileInput.dispatchEvent(event);
        }
    });

    // Form submission with loading state
    form.addEventListener('submit', function() {
        if (fileInput.files.length > 0) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i><span>Importing...</span>';
        }
    });
});
</script>

<style>
#dropzone {
    transition: all 0.3s ease;
}
</style>
@endsection