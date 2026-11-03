@extends('layouts.layout')
@section('title', 'Tambah Mutasi Barang')
@section('Mutasi Barang', 'active')

@section('content')
    <div class="bg-white rounded-lg card-shadow">
        <div class="p-6 border-b">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-bold">Tambah Mutasi Barang</h3>
                <a href="{{ route('admin.mutasi-barang.data') }}" class="btn-secondary text-white px-4 py-2 rounded-lg hover:opacity-90">
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

            <form action="{{ route('admin.mutasi-barang.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="tanggal_mutasi" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mutasi</label>
                        <input type="date" name="tanggal_mutasi" id="tanggal_mutasi" 
                               value="{{ old('tanggal_mutasi', date('Y-m-d')) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        @error('tanggal_mutasi')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="aset_tetap_id" class="block text-sm font-medium text-gray-700 mb-2">Pilih Aset Tetap</label>
                        <select name="aset_tetap_id" id="aset_tetap_id" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="">-- Pilih Aset Tetap --</option>
                            @foreach($asetTetap as $item)
                                <option value="{{ $item->id }}" 
                                        data-kode-barang="{{ $item->kode_barang }}"
                                        data-nama-barang="{{ $item->nama_barang }}"
                                        data-nup="{{ $item->nup }}"
                                        data-lokasi="{{ $item->lokasi }}"
                                        {{ old('aset_tetap_id') == $item->id ? 'selected' : '' }}>
                                    {{ $item->nama_barang }} ({{ $item->kode_barang }}) - {{ $item->lokasi }}
                                </option>
                            @endforeach
                        </select>
                        @error('aset_tetap_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="lokasi_awal" class="block text-sm font-medium text-gray-700 mb-2">Lokasi Awal</label>
                        <input type="text" name="lokasi_awal" id="lokasi_awal" 
                               value="{{ old('lokasi_awal') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        @error('lokasi_awal')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="lokasi_akhir" class="block text-sm font-medium text-gray-700 mb-2">Lokasi Akhir</label>
                        <input type="text" name="lokasi_akhir" id="lokasi_akhir" 
                               value="{{ old('lokasi_akhir') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        @error('lokasi_akhir')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                        <textarea name="keterangan" id="keterangan" rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                  placeholder="Masukkan keterangan mutasi (opsional)">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Informasi Aset Tetap</label>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="font-medium">Kode Barang:</span>
                                    <span id="info-kode-barang" class="ml-2">-</span>
                                </div>
                                <div>
                                    <span class="font-medium">NUP:</span>
                                    <span id="info-nup" class="ml-2">-</span>
                                </div>
                                <div>
                                    <span class="font-medium">Nama Barang:</span>
                                    <span id="info-nama-barang" class="ml-2">-</span>
                                </div>
                                <div>
                                    <span class="font-medium">Lokasi Saat Ini:</span>
                                    <span id="info-lokasi" class="ml-2">-</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-4">
                    <a href="{{ route('admin.mutasi-barang.data') }}" class="btn-secondary text-white px-6 py-2 rounded-lg hover:opacity-90">
                        Batal
                    </a>
                    <button type="submit" class="btn-primary text-white px-6 py-2 rounded-lg hover:opacity-90">
                        <i class="fas fa-save mr-2"></i>Simpan Mutasi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('aset_tetap_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            
            if (selectedOption.value) {
                document.getElementById('info-kode-barang').textContent = selectedOption.dataset.kodeBarang;
                document.getElementById('info-nup').textContent = selectedOption.dataset.nup;
                document.getElementById('info-nama-barang').textContent = selectedOption.dataset.namaBarang;
                document.getElementById('info-lokasi').textContent = selectedOption.dataset.lokasi;
                
                // Auto-fill lokasi awal dengan lokasi saat ini
                document.getElementById('lokasi_awal').value = selectedOption.dataset.lokasi;
            } else {
                document.getElementById('info-kode-barang').textContent = '-';
                document.getElementById('info-nup').textContent = '-';
                document.getElementById('info-nama-barang').textContent = '-';
                document.getElementById('info-lokasi').textContent = '-';
                document.getElementById('lokasi_awal').value = '';
            }
        });
    </script>
@endsection
