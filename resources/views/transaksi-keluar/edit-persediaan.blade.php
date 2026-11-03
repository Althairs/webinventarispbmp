@extends('layouts.layout')
@section('title', 'Edit Transaksi Barang Keluar - Persediaan')
@section('Transaksi Barang Keluar', 'active')

@section('content')
    <div class="bg-white rounded-lg card-shadow">
        <div class="p-6 border-b">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-bold">Edit Transaksi Barang Keluar - Persediaan</h3>
                <a href="{{ route('admin.transaksi-keluar.persediaan') }}" class="btn-secondary text-white px-4 py-2 rounded-lg hover:opacity-90">
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

            <form action="{{ route('admin.transaksi-keluar.update-persediaan', $transaksi->id) }}" method="POST">
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
                        <label for="persediaan_id" class="block text-sm font-medium text-gray-700 mb-2">Pilih Barang</label>
                        <select name="persediaan_id" id="persediaan_id" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="">-- Pilih Barang --</option>
                            @foreach($persediaan as $item)
                                <option value="{{ $item->id }}" 
                                        data-stok="{{ $item->stok }}"
                                        data-jenis="{{ $item->jenis }}"
                                        {{ old('persediaan_id', $transaksi->persediaan_id) == $item->id ? 'selected' : '' }}>
                                    {{ $item->nama_barang }} ({{ $item->kode_barang }}) - Stok: {{ $item->stok }}
                                </option>
                            @endforeach
                        </select>
                        @error('persediaan_id')
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
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis</label>
                        <input type="text" id="jenis_display" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100" 
                               value="{{ $transaksi->jenis === 'tidak_habis_pakai' ? 'Tidak Habis Pakai' : 'Habis Pakai' }}" readonly>
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
    const persediaanSelect = document.getElementById('persediaan_id');
    const jumlahKeluarInput = document.getElementById('jumlah_keluar');
    const stokInfo = document.getElementById('stok-info');
    const jenisDisplay = document.getElementById('jenis_display');

    function labelJenis(jenis) {
        if (jenis === 'tidak_habis_pakai') return 'Tidak Habis Pakai';
        if (jenis === 'habis_pakai') return 'Habis Pakai';
        return jenisDisplay.value || '-';
    }

    function updateStokInfo() {
        const selectedOption = persediaanSelect.options[persediaanSelect.selectedIndex];
        if (selectedOption.value && selectedOption.dataset.stok) {
            stokInfo.textContent = 'Stok tersedia: ' + selectedOption.dataset.stok;
            jumlahKeluarInput.setAttribute('max', selectedOption.dataset.stok);

            if (selectedOption.dataset.jenis) {
                jenisDisplay.value = labelJenis(selectedOption.dataset.jenis);
            }
        } else {
            stokInfo.textContent = '';
        }
    }

    persediaanSelect.addEventListener('change', updateStokInfo);
    updateStokInfo();
});
</script>
@endsection
