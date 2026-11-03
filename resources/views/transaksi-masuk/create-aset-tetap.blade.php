@extends('layouts.layout')
@section('title', 'Tambah Transaksi Barang Masuk - Aset Tetap')
@section('Transaksi Barang Masuk', 'active')

@section('content')
    <div class="bg-white rounded-lg card-shadow">
        <div class="p-6 border-b">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-bold">Tambah Transaksi Barang Masuk - Aset Tetap</h3>
                <a href="{{ route('admin.transaksi-masuk.aset-tetap') }}" class="btn-secondary text-white px-4 py-2 rounded-lg hover:opacity-90">
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

            <form action="{{ route('admin.transaksi-masuk.store-aset-tetap') }}" method="POST" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="tanggal_input" class="block text-sm font-medium mb-2">Tanggal Input <span class="text-red-500">*</span></label>
                        <input type="date" class="w-full border rounded-lg px-3 py-2 @error('tanggal_input') border-red-500 @enderror"
                               id="tanggal_input" name="tanggal_input"
                               value="{{ old('tanggal_input', date('Y-m-d')) }}" required>
                        @error('tanggal_input')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="kategori" class="block text-sm font-medium mb-2">Kategori <span class="text-red-500">*</span></label>
                        <input type="text" class="w-full border rounded-lg px-3 py-2 bg-gray-100 @error('kategori') border-red-500 @enderror"
                               id="kategori" name="kategori" readonly>
                        @error('kategori')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="aset_tetap_id" class="block text-sm font-medium mb-2">Pilih Aset Tetap <span class="text-red-500">*</span></label>
                        <select class="w-full border rounded-lg px-3 py-2 @error('aset_tetap_id') border-red-500 @enderror"
                                id="aset_tetap_id" name="aset_tetap_id" required>
                            <option value="">-- Pilih Aset Tetap --</option>
                            @foreach($asetTetap as $item)
                                <option value="{{ $item->id }}"
                                        data-kategori="{{ $item->kategori }}"
                                        data-kode="{{ $item->kode_barang }}"
                                        data-nama="{{ $item->nama_barang }}"
                                        data-harga="{{ $item->nilai_perolehan }}"
                                        {{ old('aset_tetap_id') == $item->id ? 'selected' : '' }}>
                                    {{ $item->kode_barang }} - {{ $item->nama_barang }}
                                </option>
                            @endforeach
                        </select>
                        @error('aset_tetap_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="kode_barang" class="block text-sm font-medium mb-2">Kode Barang</label>
                        <input type="text" class="w-full border rounded-lg px-3 py-2 bg-gray-100" id="kode_barang" name="kode_barang" readonly>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="nama_barang" class="block text-sm font-medium mb-2">Nama Barang</label>
                        <input type="text" class="w-full border rounded-lg px-3 py-2 bg-gray-100" id="nama_barang" name="nama_barang" readonly>
                    </div>
                    <div>
                        <label for="jumlah_masuk" class="block text-sm font-medium mb-2">Jumlah Masuk <span class="text-red-500">*</span></label>
                        <input type="number" class="w-full border rounded-lg px-3 py-2 @error('jumlah_masuk') border-red-500 @enderror"
                               id="jumlah_masuk" name="jumlah_masuk" value="{{ old('jumlah_masuk') }}"
                               min="1" required>
                        @error('jumlah_masuk')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="harga" class="block text-sm font-medium mb-2">Harga <span class="text-red-500">*</span></label>
                        <input type="number" class="w-full border rounded-lg px-3 py-2 @error('harga') border-red-500 @enderror"
                               id="harga" name="harga" value="{{ old('harga') }}"
                               min="0" step="0.01" required>
                        @error('harga')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Total</label>
                        <input type="text" class="w-full border rounded-lg px-3 py-2 bg-gray-100 font-bold text-green-600" id="total" readonly>
                    </div>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="submit" class="btn-primary text-white px-6 py-2 rounded-lg hover:opacity-90">
                        <i class="fas fa-save mr-2"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const asetTetapSelect = document.getElementById('aset_tetap_id');
    const kodeBarangInput = document.getElementById('kode_barang');
    const kategoriInput = document.getElementById('kategori');
    const namaBarangInput = document.getElementById('nama_barang');
    const hargaInput = document.getElementById('harga');
    const jumlahMasukInput = document.getElementById('jumlah_masuk');
    const totalInput = document.getElementById('total');

    function updateFields() {
        const selectedOption = asetTetapSelect.options[asetTetapSelect.selectedIndex];
        if (selectedOption.value) {
            kodeBarangInput.value = selectedOption.dataset.kode;
            namaBarangInput.value = selectedOption.dataset.nama;
            hargaInput.value = selectedOption.dataset.harga;
            kategoriInput.value = selectedOption.dataset.kategori;
            calculateTotal();
        } else {
            kodeBarangInput.value = '';
            namaBarangInput.value = '';
            hargaInput.value = '';
            totalInput.value = '';
        }
    }

    function calculateTotal() {
        const harga = parseFloat(hargaInput.value) || 0;
        const jumlah = parseInt(jumlahMasukInput.value) || 0;
        const total = harga * jumlah;
        totalInput.value = 'Rp ' + total.toLocaleString('id-ID');
    }

    asetTetapSelect.addEventListener('change', updateFields);
    hargaInput.addEventListener('input', calculateTotal);
    jumlahMasukInput.addEventListener('input', calculateTotal);
});
</script>
@endsection
