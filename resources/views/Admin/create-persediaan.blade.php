@extends('layouts.layout')
@section('title', 'Tambah Data Persediaan')
@section('Manajemen Data Barang', 'active')
@section('Data Persediaan', 'active')

@section('content')
    <div class="bg-white rounded-lg card-shadow">
        <div class="p-6 border-b">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-bold">Tambah Data Persediaan</h3>
                <a href="{{ route('admin.persediaan') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </div>
        
        <div class="p-6">
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('admin.persediaan.store') }}" method="POST" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="kode_kategori" class="block text-sm font-medium mb-2">Kode Kategori <span class="text-red-500">*</span></label>
                        <input type="text" class="w-full border rounded-lg px-3 py-2 @error('kode_kategori') border-red-500 @enderror" 
                               id="kode_kategori" name="kode_kategori" value="{{ old('kode_kategori') }}" required>
                        @error('kode_kategori')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="kategori" class="block text-sm font-medium mb-2">Kategori <span class="text-red-500">*</span></label>
                        <input type="text" class="w-full border rounded-lg px-3 py-2 @error('kategori') border-red-500 @enderror" 
                               id="kategori" name="kategori" value="{{ old('kategori') }}" required>
                        @error('kategori')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="kode_barang" class="block text-sm font-medium mb-2">Kode Barang <span class="text-red-500">*</span></label>
                        <input type="text" class="w-full border rounded-lg px-3 py-2 @error('kode_barang') border-red-500 @enderror" 
                               id="kode_barang" name="kode_barang" value="{{ old('kode_barang') }}" required>
                        @error('kode_barang')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="nama_barang" class="block text-sm font-medium mb-2">Nama Barang <span class="text-red-500">*</span></label>
                        <input type="text" class="w-full border rounded-lg px-3 py-2 @error('nama_barang') border-red-500 @enderror" 
                               id="nama_barang" name="nama_barang" value="{{ old('nama_barang') }}" required>
                        @error('nama_barang')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="jenis" class="block text-sm font-medium mb-2">Jenis <span class="text-red-500">*</span></label>
                        <select id="jenis" name="jenis" class="w-full border rounded-lg px-3 py-2 @error('jenis') border-red-500 @enderror" required>
                            <option value="">-- Pilih Jenis --</option>
                            <option value="habis_pakai" {{ old('jenis', 'habis_pakai') == 'habis_pakai' ? 'selected' : '' }}>Habis Pakai</option>
                            <option value="tidak_habis_pakai" {{ old('jenis') == 'tidak_habis_pakai' ? 'selected' : '' }}>Tidak Habis Pakai</option>
                        </select>
                        @error('jenis')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="harga" class="block text-sm font-medium mb-2">Harga <span class="text-red-500">*</span></label>
                        <input type="number" class="w-full border rounded-lg px-3 py-2 @error('harga') border-red-500 @enderror" 
                               id="harga" name="harga" value="{{ old('harga') }}" 
                               min="0" step="0.01" required style="-moz-appearance: textfield;">
                        @error('harga')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="tanggal_masuk" class="block text-sm font-medium mb-2">Tanggal Masuk <span class="text-red-500">*</span></label>
                        <input type="date" class="w-full border rounded-lg px-3 py-2 @error('tanggal_masuk') border-red-500 @enderror" 
                               id="tanggal_masuk" name="tanggal_masuk" value="{{ old('tanggal_masuk', date('Y-m-d')) }}" required>
                        @error('tanggal_masuk')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="jumlah" class="block text-sm font-medium mb-2">Jumlah <span class="text-red-500">*</span></label>
                        <input type="number" class="w-full border rounded-lg px-3 py-2 @error('jumlah') border-red-500 @enderror" 
                               id="jumlah" name="jumlah" value="{{ old('jumlah', 1) }}" 
                               min="1" required style="-moz-appearance: textfield;">
                        @error('jumlah')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors duration-200">
                        <i class="fas fa-save mr-2"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
