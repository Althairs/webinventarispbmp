@extends('layouts.layout') {{-- Asumsikan ini adalah layout utama Anda --}}
@section('title', 'Minta Barang & Riwayat')
@section('Peminjaman Barang & Riwayat', 'active')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg card-shadow p-6 mb-6">
        <h3 class="text-lg font-bold mb-4 flex items-center">
            <i class="fas fa-box mr-2 text-blue-600"></i>
            Form Permintaan Peminjaman Barang
        </h3>
        {{-- Mengubah method dari GET ke POST dan menambahkan action ke route store --}}
        <form id="requestForm" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-2">Pilih Barang *</label>
                <select name="product_id" id="productSelect" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required onchange="fillProductData()">
                    <option value="">-- Pilih Barang --</option>
                    @foreach($products as $product)
                        <option value="{{ $product['id']}}"
                                data-kode-barang="{{ $product['kode_barang'] }}"
                                data-nama-barang="{{ $product['nama_barang'] }}"
                                data-merek="{{ $product['merek'] }}"
                                data-kategori="{{ $product['kategori'] }}"
                                data-nup="{{ $product['nup'] }}"
                                data-jumlah="{{ $product['jumlah'] }}"
                                data-lokasi="{{ $product['lokasi'] }}"
                                data-kondisi="{{ $product['kondisi'] }}"
                                data-type="{{ $product['type'] }}">
                            {{ $product['nama_barang'] }} - {{ $product['merek'] }} ({{ $product['kategori'] }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">Nama Barang</label>
                <input type="text" id="nama_barang_display" class="w-full border rounded-lg px-3 py-2 bg-gray-100" readonly>
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">Merek</label>
                <input type="text" id="merek_display" class="w-full border rounded-lg px-3 py-2 bg-gray-100" readonly>
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">Jumlah yang Diminta *</label>
                <input type="number" name="jumlah" id="jumlah_request" placeholder="Masukkan jumlah yang diminta" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" min="1" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">Tanggal Peminjaman *</label>
                <input type="date" name="tanggal_peminjaman" id="tanggal_peminjaman" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" min="{{ date('Y-m-d') }}" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">Tanggal Pengembalian *</label>
                <input type="date" name="tanggal_pengembalian" id="tanggal_pengembalian" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" min="{{ date('Y-m-d') }}" required>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-2">Deskripsi Peruntukan Peminjaman *</label>
                <textarea name="deskripsi_peruntukan" rows="3" placeholder="Jelaskan untuk keperluan apa barang ini diperlukan. Contoh: Untuk keperluan rapat bulanan, penggantian peralatan yang rusak, kegiatan pembelajaran, dll." class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required></textarea>
            </div>
            <div class="md:col-span-2 flex items-end">
                <button type="submit" class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 transition-all font-semibold">
                    <i class="fas fa-paper-plane mr-2"></i>Kirim Permintaan Peminjaman Barang
                </button>
            </div>
        </form>
        <div class="mt-4 p-3 bg-blue-50 rounded-lg">
            <p class="text-sm text-blue-700">
                <i class="fas fa-info-circle mr-1"></i>
                <strong>Catatan:</strong> Pastikan semua data sudah benar sebelum mengirim permintaan. Admin akan meninjau dan memberikan feedback dalam 1-2 hari kerja.
            </p>
        </div>
    </div>

    <div class="bg-white rounded-lg card-shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold">📋 Histori Perminjaman Barang</h3>
            <div class="text-sm text-gray-600">
                Total: {{ $userRequests->count() }} permintaan
            </div>
        </div>

        @if ($userRequests->isEmpty())
            <div class="text-center py-12">
                <i class="fas fa-inbox text-gray-400 text-5xl mb-4"></i>
                <h4 class="text-lg font-medium text-gray-600 mb-2">Belum Ada Permintaan</h4>
                <p class="text-gray-500 mb-4">Mulai buat permintaan barang pertama Anda</p>
                <a href="{{ route('user.request.create') }}" class="btn-primary text-white px-6 py-2 rounded-lg hover:opacity-90">
                    <i class="fas fa-plus mr-2"></i>Buat Permintaan
                </a>
            </div>
        @else
            <div class="space-y-4 max-h-96 overflow-y-auto">
                @foreach ($userRequests as $req)
                    <div class="border rounded-lg p-4 hover:shadow-md transition-shadow
                        @if ($req->status === 'approved') border-green-200 bg-green-50
                        @elseif ($req->status === 'rejected') border-red-200 bg-red-50
                        @else border-yellow-200 bg-yellow-50 @endif">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h4 class="font-semibold text-gray-800">{{ $req->nama_barang }}</h4>
                                <p class="text-sm text-gray-600">{{ $req->kategori }} • {{ $req->merek }}</p>
                            </div>
                            <span class="px-3 py-1 rounded-full text-sm font-medium
                                @if ($req->status === 'approved') bg-green-100 text-green-800
                                @elseif ($req->status === 'rejected') bg-red-100 text-red-800
                                @else bg-yellow-100 text-yellow-800 @endif">
                                {{ $req->status === 'approved' ? '✅ Diterima' : ($req->status === 'rejected' ? '❌ Ditolak' : '⏳ Menunggu') }}
                            </span>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-3">
                            <div>
                                <p class="text-sm text-gray-600"><strong>Jumlah:</strong> {{ $req->jumlah }} unit</p>
                                <p class="text-sm text-gray-600"><strong>Kode Barang:</strong> {{ $req->kode_barang }}</p>
                            </div>
                            <div>
                                {{-- PERBAIKAN: Menggunakan Carbon untuk memformat tanggal --}}
                                <p class="text-sm text-gray-600"><strong>Tanggal:</strong> {{ $req->request_date->translatedFormat('d F Y') }}</p>
                                <p class="text-sm text-gray-600"><strong>Status:</strong>
                                    @if ($req->status === 'approved') Diterima Admin
                                    @elseif ($req->status === 'rejected') Ditolak Admin
                                    @else Menunggu Review @endif
                                </p>
                            </div>
                        </div>

                        @if ($req->deskripsi_peruntukan)
                            <div class="mt-3 p-3 bg-gray-50 rounded">
                                <p class="text-sm font-medium text-gray-700">Deskripsi Peruntukan:</p>
                                <p class="text-sm text-gray-600 mt-1">{{ $req->deskripsi_peruntukan }}</p>
                            </div>
                        @endif

                        @if ($req->komentar)
                            {{-- PERBAIKAN: Mengganti $req->comment menjadi $req->komentar --}}
                            <div class="mt-3 p-3 bg-white rounded border-l-4
                                @if ($req->status === 'approved') border-green-400
                                @else border-red-400 @endif">
                                <p class="text-sm font-medium text-gray-700">Komentar Admin:</p>
                                <p class="text-sm text-gray-600 mt-1">{{ $req->komentar }}</p>
                            </div>
                        @endif

                        <div class="mt-3 flex justify-end">
                            <a href="{{ route('user.user.peminjaman-barang.detail', $req->id) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                <i class="fas fa-eye mr-1"></i>Lihat Detail
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    </div>

@endsection

@push('scripts')
<script>
    function fillProductData() {
        const select = document.getElementById('productSelect');
        const selectedOption = select.options[select.selectedIndex];

        if (selectedOption.value) {
            document.getElementById('nama_barang_display').value = selectedOption.dataset.namaBarang;
            document.getElementById('merek_display').value = selectedOption.dataset.merek;

            // Set max value for jumlah request
            document.getElementById('jumlah_request').max = selectedOption.dataset.jumlah;
        } else {
            // Clear all fields
            document.getElementById('nama_barang_display').value = '';
            document.getElementById('merek_display').value = '';
            document.getElementById('jumlah_request').max = '';
        }
    }

    document.getElementById('requestForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        // Add hidden fields for the selected product data
        const select = document.getElementById('productSelect');
        const selectedOption = select.options[select.selectedIndex];

        if (selectedOption.value) {
            formData.append('nama_barang', selectedOption.dataset.namaBarang);
            formData.append('kategori', selectedOption.dataset.kategori);
            formData.append('merek', selectedOption.dataset.merek);
            formData.append('kode_barang', selectedOption.dataset.kodeBarang);
            formData.append('product_type', selectedOption.dataset.type);
        }

        try {
            const response = await fetch('{{ route("user.request.store") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            });

            if (response.ok) {
                const responseData = await response.json();
                alert('Permintaan berhasil dikirim!');
                location.reload();
            } else {
                const errorData = await response.json();
                let errorMessage = 'Gagal mengirim permintaan';
                if (errorData.message) {
                    errorMessage += ': ' + errorData.message;
                } else if (errorData.errors) {
                    errorMessage += ': ' + JSON.stringify(errorData.errors);
                } else {
                    errorMessage += ': ' + JSON.stringify(errorData);
                }
                alert(errorMessage);
            }
        } catch (error) {
            alert('Terjadi kesalahan saat mengirim permintaan.');
            console.error(error);
        }
    });

    // Validasi tanggal pengembalian harus setelah tanggal peminjaman
    document.getElementById('tanggal_peminjaman').addEventListener('change', function() {
        const tanggalPeminjaman = this.value;
        const tanggalPengembalian = document.getElementById('tanggal_pengembalian');

        if (tanggalPeminjaman) {
            tanggalPengembalian.min = tanggalPeminjaman;
            if (tanggalPengembalian.value && tanggalPengembalian.value < tanggalPeminjaman) {
                tanggalPengembalian.value = '';
                alert('Tanggal pengembalian harus setelah tanggal peminjaman');
            }
        }
    });

    document.getElementById('tanggal_pengembalian').addEventListener('change', function() {
        const tanggalPeminjaman = document.getElementById('tanggal_peminjaman').value;
        const tanggalPengembalian = this.value;

        if (tanggalPeminjaman && tanggalPengembalian && tanggalPengembalian < tanggalPeminjaman) {
            this.value = '';
            alert('Tanggal pengembalian harus setelah tanggal peminjaman');
        }
    });

</script>
@endpush
