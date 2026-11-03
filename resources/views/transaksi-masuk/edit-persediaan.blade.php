@extends('layouts.layout')
@section('title', 'Edit Transaksi Barang Masuk - Persediaan')
@section('Transaksi Barang Masuk', 'active')

@section('content')
    <div class="bg-white rounded-lg card-shadow">
        <div class="p-6 border-b">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-bold">Edit Transaksi Barang Masuk - Persediaan</h3>
                <a href="{{ route('admin.transaksi-masuk.persediaan') }}" class="btn-secondary text-white px-4 py-2 rounded-lg hover:opacity-90">
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

            <form action="{{ route('admin.transaksi-masuk.update-persediaan', $transaksi->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="tanggal_input" class="block text-sm font-medium mb-2">Tanggal Input <span class="text-red-500">*</span></label>
                        <input type="date" class="w-full border rounded-lg px-3 py-2 @error('tanggal_input') border-red-500 @enderror" 
                               id="tanggal_input" name="tanggal_input" 
                               value="{{ old('tanggal_input', $transaksi->tanggal_input->format('Y-m-d')) }}" required>
                        @error('tanggal_input')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="kode_kategori" class="block text-sm font-medium mb-2">Kode Kategori <span class="text-red-500">*</span></label>
                        <input type="text" class="w-full border rounded-lg px-3 py-2 bg-gray-100 @error('kode_kategori') border-red-500 @enderror" 
                               id="kode_kategori" name="kode_kategori" value="{{ old('kode_kategori', $transaksi->kode_kategori) }}" readonly required>
                        @error('kode_kategori')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="kategori" class="block text-sm font-medium mb-2">Kategori <span class="text-red-500">*</span></label>
                        <input type="text" class="w-full border rounded-lg px-3 py-2 bg-gray-100 @error('kategori') border-red-500 @enderror" 
                               id="kategori" name="kategori" value="{{ old('kategori', $transaksi->kategori) }}" readonly required>
                        @error('kategori')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="persediaan_id" class="block text-sm font-medium mb-2">Pilih Barang <span class="text-red-500">*</span></label>
                        <select class="w-full border rounded-lg px-3 py-2 @error('persediaan_id') border-red-500 @enderror" 
                                id="persediaan_id" name="persediaan_id" required>
                            <option value="">-- Pilih Barang --</option>
                            @foreach($persediaan as $item)
                                <option value="{{ $item->id }}" 
                                        data-kode="{{ $item->kode_barang }}"
                                        data-nama="{{ $item->nama_barang }}"
                                        data-harga="{{ $item->harga }}"
                                        data-kode-kategori="{{ $item->kode_kategori }}"
                                        data-kategori="{{ $item->kategori }}"
                                        data-jenis="{{ $item->jenis }}"
                                        {{ old('persediaan_id', $transaksi->persediaan_id) == $item->id ? 'selected' : '' }}>
                                    {{ $item->kode_barang }} - {{ $item->nama_barang }}
                                </option>
                            @endforeach
                        </select>
                        @error('persediaan_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="kode_barang" class="block text-sm font-medium mb-2">Kode Barang</label>
                        <input type="text" class="w-full border rounded-lg px-3 py-2 bg-gray-100" id="kode_barang" name="kode_barang" 
                               value="{{ old('kode_barang', $transaksi->kode_barang) }}" readonly>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="nama_barang" class="block text-sm font-medium mb-2">Nama Barang</label>
                        <input type="text" class="w-full border rounded-lg px-3 py-2 bg-gray-100" id="nama_barang" name="nama_barang" 
                               value="{{ old('nama_barang', $transaksi->nama_barang) }}" readonly>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Jenis</label>
                        <input type="text" id="jenis_display" class="w-full border rounded-lg px-3 py-2 bg-gray-100" 
                               value="{{ $transaksi->jenis === 'tidak_habis_pakai' ? 'Tidak Habis Pakai' : 'Habis Pakai' }}" readonly>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="jumlah_masuk" class="block text-sm font-medium mb-2">Jumlah Masuk <span class="text-red-500">*</span></label>
                        <input type="number" class="w-full border rounded-lg px-3 py-2 @error('jumlah_masuk') border-red-500 @enderror" 
                               id="jumlah_masuk" name="jumlah_masuk" value="{{ old('jumlah_masuk', $transaksi->jumlah_masuk) }}" 
                               min="1" required>
                        @error('jumlah_masuk')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="harga" class="block text-sm font-medium mb-2">Harga <span class="text-red-500">*</span></label>
                        <input type="number" class="w-full border rounded-lg px-3 py-2 @error('harga') border-red-500 @enderror" 
                               id="harga" name="harga" value="{{ old('harga', $transaksi->harga) }}" 
                               min="0" step="0.01" required>
                        @error('harga')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium mb-2">Total</label>
                        <input type="text" class="w-full border rounded-lg px-3 py-2 bg-gray-100 font-bold text-green-600" id="total" readonly>
                    </div>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="submit" class="btn-primary text-white px-6 py-2 rounded-lg hover:opacity-90">
                        <i class="fas fa-save mr-2"></i>Update
                    </button>
                </div>
            </form>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const persediaanSelect = document.getElementById('persediaan_id');
    const kodeBarangInput = document.getElementById('kode_barang');
    const namaBarangInput = document.getElementById('nama_barang');
    const hargaInput = document.getElementById('harga');
    const jumlahMasukInput = document.getElementById('jumlah_masuk');
    const totalInput = document.getElementById('total');
    const kodeKategoriInput = document.getElementById('kode_kategori');
    const kategoriInput = document.getElementById('kategori');
    const jenisDisplay = document.getElementById('jenis_display');

    function labelJenis(jenis) {
        if (jenis === 'tidak_habis_pakai') return 'Tidak Habis Pakai';
        if (jenis === 'habis_pakai') return 'Habis Pakai';
        return jenisDisplay.value || '-';
    }

    function updateFields() {
        const selectedOption = persediaanSelect.options[persediaanSelect.selectedIndex];
        if (selectedOption.value) {
            // Update kode barang dan nama barang
            kodeBarangInput.value = selectedOption.dataset.kode;
            namaBarangInput.value = selectedOption.dataset.nama;
            hargaInput.value = selectedOption.dataset.harga;
            
            // Update kode kategori dan kategori dari data persediaan
            kodeKategoriInput.value = selectedOption.dataset.kodeKategori || '';
            kategoriInput.value = selectedOption.dataset.kategori || '';

            // Update jenis tampilan jika ada di option
            if (selectedOption.dataset.jenis) {
                jenisDisplay.value = labelJenis(selectedOption.dataset.jenis);
            }
            
            calculateTotal();
        } else {
            // Reset semua field (kecuali jenis agar tetap menampilkan nilai lama)
            kodeBarangInput.value = '';
            namaBarangInput.value = '';
            hargaInput.value = '';
            kodeKategoriInput.value = '';
            kategoriInput.value = '';
            totalInput.value = '';
        }
    }

    function calculateTotal() {
        const harga = parseFloat(hargaInput.value) || 0;
        const jumlah = parseInt(jumlahMasukInput.value) || 0;
        const total = harga * jumlah;
        totalInput.value = 'Rp ' + total.toLocaleString('id-ID');
    }

    persediaanSelect.addEventListener('change', updateFields);
    hargaInput.addEventListener('input', calculateTotal);
    jumlahMasukInput.addEventListener('input', calculateTotal);
    
    // Hitung total saat halaman dimuat
    calculateTotal();
});
</script>
@endsection
