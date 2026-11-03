@extends('layouts.layout')
@section('title', 'Import Data Aset Tetap')
@section('Manajemen Data Barang', 'active')
@section('Data Aset Tetap', 'active')

@section('content')
    <div class="bg-white rounded-lg card-shadow">
        <div class="p-6 border-b">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-bold">Import Data Aset Tetap dari Excel</h3>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.aset-tetap') }}" class="btn-secondary text-white px-4 py-2 rounded-lg hover:opacity-90">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                    <a href="{{ route('admin.aset-tetap.template') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                        <i class="fas fa-download mr-2"></i>Download Template
                    </a>
                </div>
            </div>
        </div>
        
        <div class="p-6">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('import_errors') && count(session('import_errors')) > 0)
                <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4">
                    <h4 class="font-bold mb-2">Beberapa data gagal diimport:</h4>
                    <ul class="list-disc list-inside">
                        @foreach(session('import_errors') as $error)
                            <li>Baris {{ $error['row'] }}: {{ implode(', ', $error['errors']) }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Informasi Format -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <h4 class="font-bold text-blue-800 mb-2">
                    <i class="fas fa-info-circle mr-2"></i>Panduan Format Excel
                </h4>
                <div class="text-blue-700 text-sm">
                    <p class="mb-2">Pastikan file Excel/CSV Anda memiliki kolom berikut (urutan harus sama):</p>
                    <p class="mb-2 text-yellow-600"><strong>💡 Tips:</strong> Jika Excel tidak berfungsi, gunakan file CSV (.csv) sebagai alternatif!</p>
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-2 text-xs">
                        <div class="bg-white p-2 rounded border">
                            <strong>A:</strong> Kode Barang <span class="text-red-500">*</span>
                        </div>
                        <div class="bg-white p-2 rounded border">
                            <strong>B:</strong> Nama Barang <span class="text-red-500">*</span>
                        </div>
                        <div class="bg-white p-2 rounded border">
                            <strong>C:</strong> Merek <span class="text-red-500">*</span>
                        </div>
                        <div class="bg-white p-2 rounded border">
                            <strong>D:</strong> Kategori <span class="text-red-500">*</span>
                        </div>
                        <div class="bg-white p-2 rounded border">
                            <strong>E:</strong> Kondisi <span class="text-red-500">*</span>
                        </div>
                        <div class="bg-white p-2 rounded border">
                            <strong>F:</strong> Lokasi <span class="text-red-500">*</span>
                        </div>
                        <div class="bg-white p-2 rounded border">
                            <strong>G:</strong> Nilai Perolehan
                        </div>
                        <div class="bg-white p-2 rounded border">
                            <strong>H:</strong> Tanggal Peroleh
                        </div>
                        <div class="bg-white p-2 rounded border">
                            <strong>I:</strong> Jumlah <span class="text-red-500">*</span>
                        </div>
                        <div class="bg-white p-2 rounded border">
                            <strong>J:</strong> NUP
                        </div>
                    </div>
                    <p class="mt-2 text-xs">
                        <span class="text-red-500">*</span> = Wajib diisi<br>
                        <strong>Kondisi:</strong> baik, rusak_ringan, atau rusak_berat<br>
                        <strong>Format Tanggal:</strong> YYYY-MM-DD (contoh: 2024-01-15)
                    </p>
                </div>
            </div>

            <!-- Form Upload -->
            <form action="{{ route('admin.aset-tetap.import.process') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                
                <div class="bg-gray-50 border-2 border-dashed border-gray-300 rounded-lg p-6">
                    <div class="text-center">
                        <i class="fas fa-file-excel text-4xl text-green-600 mb-4"></i>
                        <h4 class="text-lg font-semibold text-gray-700 mb-2">Pilih File Excel/CSV</h4>
                        <p class="text-gray-500 mb-4">Upload file Excel (.xlsx, .xls) atau CSV (.csv) dengan data aset tetap</p>
                        
                        <div class="flex justify-center">
                        <input type="file" 
                               name="file" 
                               id="file" 
                               accept=".xlsx,.xls,.csv"
                               class="hidden"
                               onchange="handleFileSelect(this)">
                            <label for="file" 
                                   class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg cursor-pointer transition-colors duration-200">
                                <i class="fas fa-upload mr-2"></i>Pilih File
                            </label>
                        </div>
                        
                        <div id="file-info" class="mt-4 hidden">
                            <div class="bg-white border border-gray-200 rounded-lg p-3">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <i class="fas fa-file-excel text-green-600 mr-2"></i>
                                        <span id="file-name" class="font-medium"></span>
                                    </div>
                                    <button type="button" onclick="clearFile()" class="text-red-500 hover:text-red-700">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <div class="text-sm text-gray-500 mt-1">
                                    <span id="file-size"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.aset-tetap') }}" class="btn-secondary text-white px-6 py-2 rounded-lg hover:opacity-90">
                        Batal
                    </a>
                    <button type="submit" 
                            id="submit-btn"
                            class="btn-primary text-white px-6 py-2 rounded-lg hover:opacity-90 disabled:opacity-50 disabled:cursor-not-allowed"
                            disabled>
                        <i class="fas fa-upload mr-2"></i>Import Data
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function handleFileSelect(input) {
            const file = input.files[0];
            if (file) {
                const fileInfo = document.getElementById('file-info');
                const fileName = document.getElementById('file-name');
                const fileSize = document.getElementById('file-size');
                const submitBtn = document.getElementById('submit-btn');
                
                fileName.textContent = file.name;
                fileSize.textContent = formatFileSize(file.size);
                fileInfo.classList.remove('hidden');
                submitBtn.disabled = false;
            }
        }
        
        function clearFile() {
            const fileInput = document.getElementById('file');
            const fileInfo = document.getElementById('file-info');
            const submitBtn = document.getElementById('submit-btn');
            
            fileInput.value = '';
            fileInfo.classList.add('hidden');
            submitBtn.disabled = true;
        }
        
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }
    </script>
@endsection
