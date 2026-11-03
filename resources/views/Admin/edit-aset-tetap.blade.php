@extends('layouts.layout')
@section('title', 'Edit Data Aset Tetap')
@section('Manajemen Data Barang', 'active')
@section('Data Aset Tetap', 'active')

@section('content')
    <div class="bg-white rounded-lg card-shadow">
        <div class="p-6 border-b">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-bold">Edit Data Aset Tetap</h3>
                <a href="{{ route('admin.aset-tetap') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors duration-200">
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

            <form action="{{ route('admin.aset-tetap.update', $asetTetap->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="kode_barang" class="block text-sm font-medium mb-2">Kode Barang <span class="text-red-500">*</span></label>
                        <input type="text" class="w-full border rounded-lg px-3 py-2 @error('kode_barang') border-red-500 @enderror" 
                               id="kode_barang" name="kode_barang" value="{{ old('kode_barang', $asetTetap->kode_barang) }}" required>
                        @error('kode_barang')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="nup" class="block text-sm font-medium mb-2">NUP (Nomor Urut Pendaftaran) <span class="text-red-500">*</span></label>
                        <input type="text" class="w-full border rounded-lg px-3 py-2 @error('nup') border-red-500 @enderror" 
                               id="nup" name="nup" value="{{ old('nup', $asetTetap->nup) }}" required>
                        @error('nup')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="nama_barang" class="block text-sm font-medium mb-2">Nama Barang <span class="text-red-500">*</span></label>
                        <input type="text" class="w-full border rounded-lg px-3 py-2 @error('nama_barang') border-red-500 @enderror" 
                               id="nama_barang" name="nama_barang" value="{{ old('nama_barang', $asetTetap->nama_barang) }}" required>
                        @error('nama_barang')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="merek" class="block text-sm font-medium mb-2">Merek <span class="text-red-500">*</span></label>
                        <input type="text" class="w-full border rounded-lg px-3 py-2 @error('merek') border-red-500 @enderror" 
                               id="merek" name="merek" value="{{ old('merek', $asetTetap->merek) }}" required>
                        @error('merek')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="tanggal_peroleh" class="block text-sm font-medium mb-2">Tanggal Peroleh <span class="text-red-500">*</span></label>
                        <input type="date" class="w-full border rounded-lg px-3 py-2 @error('tanggal_peroleh') border-red-500 @enderror" 
                               id="tanggal_peroleh" name="tanggal_peroleh" value="{{ old('tanggal_peroleh', $asetTetap->tanggal_peroleh ? \Carbon\Carbon::parse($asetTetap->tanggal_peroleh)->format('Y-m-d') : '') }}" required>
                        @error('tanggal_peroleh')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="nilai_perolehan" class="block text-sm font-medium mb-2">Nilai Perolehan <span class="text-red-500">*</span></label>
                        <input type="number" class="w-full border rounded-lg px-3 py-2 @error('nilai_perolehan') border-red-500 @enderror" 
                               id="nilai_perolehan" name="nilai_perolehan" value="{{ old('nilai_perolehan', $asetTetap->nilai_perolehan) }}" 
                               min="0" step="0.01" required style="-moz-appearance: textfield;">
                        @error('nilai_perolehan')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="kondisi" class="block text-sm font-medium mb-2">Kondisi <span class="text-red-500">*</span></label>
                        <select class="w-full border rounded-lg px-3 py-2 @error('kondisi') border-red-500 @enderror" 
                                id="kondisi" name="kondisi" required>
                            <option value="">-- Pilih Kondisi --</option>
                            <option value="baik" {{ old('kondisi', $asetTetap->kondisi) == 'baik' ? 'selected' : '' }}>Baik</option>
                            <option value="rusak_ringan" {{ old('kondisi', $asetTetap->kondisi) == 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                            <option value="rusak_berat" {{ old('kondisi', $asetTetap->kondisi) == 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                        </select>
                        @error('kondisi')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="lokasi" class="block text-sm font-medium mb-2">Lokasi <span class="text-red-500">*</span></label>
                        <input type="text" class="w-full border rounded-lg px-3 py-2 @error('lokasi') border-red-500 @enderror" 
                               id="lokasi" name="lokasi" value="{{ old('lokasi', $asetTetap->lokasi) }}" required>
                        @error('lokasi')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="jumlah" class="block text-sm font-medium mb-2">Jumlah <span class="text-red-500">*</span></label>
                        <input type="number" class="w-full border rounded-lg px-3 py-2 @error('jumlah') border-red-500 @enderror" 
                               id="jumlah" name="jumlah" value="{{ old('jumlah', $asetTetap->jumlah) }}" 
                               min="1" required style="-moz-appearance: textfield;">
                        @error('jumlah')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="kode_kategori" class="block text-sm font-medium mb-2">Kode Kategori</label>
                        <input type="text" class="w-full border rounded-lg px-3 py-2 @error('kode_kategori') border-red-500 @enderror" 
                               id="kode_kategori" name="kode_kategori" value="{{ old('kode_kategori', $asetTetap->kode_kategori) }}" 
                               placeholder="Contoh: FURN-001">
                        @error('kode_kategori')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="kategori" class="block text-sm font-medium mb-2">Kategori</label>
                        <input type="text" class="w-full border rounded-lg px-3 py-2 @error('kategori') border-red-500 @enderror" 
                               id="kategori" name="kategori" value="{{ old('kategori', $asetTetap->kategori) }}" 
                               placeholder="Contoh: Furniture, Elektronik, Kendaraan, dll">
                        @error('kategori')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>


                <div class="flex justify-end space-x-3">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors duration-200">
                        <i class="fas fa-save mr-2"></i>Update
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
