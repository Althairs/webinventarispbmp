@extends('layouts.layout')
@section('title', 'Edit Transaksi Barang Keluar - Aset Tetap')
@section('Transaksi Barang Keluar', 'active')

@section('content')
    <div class="bg-white rounded-lg card-shadow">
        <div class="p-6 border-b">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-bold">Edit Transaksi Barang Keluar - Aset Tetap</h3>
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

            <form action="{{ route('admin.transaksi-keluar.update-aset-tetap', $transaksi->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="tanggal_input" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Input</label>
                        <input type="date" name="tanggal_input" id="tanggal_input" 
                               value="{{ old('tanggal_input', $transaksi->tanggal_input->format('Y-m-d')) }}" 
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
                                        data-stok="{{ $item->stok }}"
                                        {{ old('aset_tetap_id', $transaksi->aset_tetap_id) == $item->id ? 'selected' : '' }}>
                                    {{ $item->nama_barang }} ({{ $item->kode_barang }}) - Stok: {{ $item->stok }}
                                </option>
                            @endforeach
                        </select>
                        @error('aset_tetap_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div>
                        <label for="jumlah_keluar" class="block text-sm font-medium text-gray-700 mb-2">Jumlah Keluar</label>
                        <input type="number" name="jumlah_keluar" id="jumlah_keluar" 
                               value="{{ old('jumlah_keluar', $transaksi->jumlah_keluar) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                               min="1" required>
                        @error('jumlah_keluar')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-sm text-gray-500 mt-1" id="stok-info"></p>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <i class="fas fa-save mr-2"></i>Update
                    </button>
                </div>
            </form>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const asetTetapSelect = document.getElementById('aset_tetap_id');
    const jumlahKeluarInput = document.getElementById('jumlah_keluar');
    const stokInfo = document.getElementById('stok-info');

    function updateStokInfo() {
        const selectedOption = asetTetapSelect.options[asetTetapSelect.selectedIndex];
        if (selectedOption.value && selectedOption.dataset.stok) {
            stokInfo.textContent = 'Stok tersedia: ' + selectedOption.dataset.stok;
            jumlahKeluarInput.setAttribute('max', selectedOption.dataset.stok);
        } else {
            stokInfo.textContent = '';
        }
    }

    asetTetapSelect.addEventListener('change', updateStokInfo);
    updateStokInfo();
});
</script>
@endsection
