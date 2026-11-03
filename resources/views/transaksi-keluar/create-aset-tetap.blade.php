@extends('layouts.layout')
@section('title', 'Tambah Transaksi Barang Keluar - Aset Tetap')
@section('Transaksi Barang Keluar', 'active')

@section('content')
    <div class="bg-white rounded-lg card-shadow">
        <div class="p-6 border-b">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-bold">Tambah Transaksi Barang Keluar - Aset Tetap</h3>
                <a href="{{ route('admin.transaksi-keluar.aset-tetap') }}" class="btn-secondary text-white px-4 py-2 rounded-lg hover:opacity-90">
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

            <form action="{{ route('admin.transaksi-keluar.store-aset-tetap') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="tanggal_input" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Input</label>
                        <input type="date" name="tanggal_input" id="tanggal_input" 
                               value="{{ old('tanggal_input', date('Y-m-d')) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        @error('tanggal_input')
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
                                        data-kategori="{{ $item->kategori ?? 'Aset Tetap' }}"
                                        data-nama-barang="{{ $item->nama_barang }}"
                                        data-stok="{{ $item->stok }}"
                                        {{ old('aset_tetap_id') == $item->id ? 'selected' : '' }}>
                                    {{ $item->nama_barang }} ({{ $item->kode_barang }}) - Stok: {{ $item->stok }}
                                </option>
                            @endforeach
                        </select>
                        @error('aset_tetap_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="jumlah_keluar" class="block text-sm font-medium text-gray-700 mb-2">Jumlah Keluar</label>
                        <input type="number" name="jumlah_keluar" id="jumlah_keluar" min="1" 
                               value="{{ old('jumlah_keluar') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        @error('jumlah_keluar')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-sm text-gray-500 mt-1">Stok tersedia: <span id="stok-tersedia">-</span></p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Informasi Aset Tetap</label>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="grid grid-cols-1 gap-4 text-sm">
                                <div>
                                    <span class="font-medium">Kode Barang:</span>
                                    <span id="info-kode-barang" class="ml-2">-</span>
                                </div>
                                <div>
                                    <span class="font-medium">Nama Barang:</span>
                                    <span id="info-nama-barang" class="ml-2">-</span>
                                </div>
                                <div>
                                    <span class="font-medium">Kategori:</span>
                                    <span id="info-kategori" class="ml-2">-</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-4">
                    <a href="{{ route('admin.transaksi-keluar.aset-tetap') }}" class="btn-secondary text-white px-6 py-2 rounded-lg hover:opacity-90">
                        Batal
                    </a>
                    <button type="submit" class="btn-primary text-white px-6 py-2 rounded-lg hover:opacity-90">
                        <i class="fas fa-save mr-2"></i>Simpan Transaksi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const asetSelect = document.getElementById('aset_tetap_id');
        function fillInfo() {
            const selectedOption = asetSelect.options[asetSelect.selectedIndex];
            if (selectedOption && selectedOption.value) {
                document.getElementById('info-kode-barang').textContent = selectedOption.dataset.kodeBarang;
                document.getElementById('info-nama-barang').textContent = selectedOption.dataset.namaBarang;
                document.getElementById('info-kategori').textContent = selectedOption.dataset.kategori || '-';
                document.getElementById('stok-tersedia').textContent = selectedOption.dataset.stok;
                document.getElementById('jumlah_keluar').max = selectedOption.dataset.stok;
            } else {
                document.getElementById('info-kode-barang').textContent = '-';
                document.getElementById('info-nama-barang').textContent = '-';
                document.getElementById('info-kategori').textContent = '-';
                document.getElementById('stok-tersedia').textContent = '-';
            }
        }
        asetSelect.addEventListener('change', fillInfo);
        // initial
        fillInfo();
    </script>
@endsection
