@extends('layouts.layout') {{-- Asumsikan ini adalah layout utama Anda --}}
@section('title', 'Permintaan Persediaan & Riwayat')
@section('Permintaan Persediaan', 'active')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg card-shadow p-6 mb-6">
        <h3 class="text-lg font-bold mb-4">📦 Form Permintaan Persediaan</h3>
        <form id="supplyRequestForm" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-2">Pilih Barang Persediaan *</label>
                <select name="product_id" id="productSelect" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required onchange="fillProductData()">
                    <option value="">-- Pilih Barang Persediaan --</option>
                    @foreach($persediaan as $product)
                        <option value="{{ $product->id }}" 
                                data-kode-barang="{{ $product->kode_barang }}"
                                data-nama-barang="{{ $product->nama_barang }}"
                                data-merek="{{ $product->merek }}"
                                data-kategori="{{ $product->kategori }}"
                                data-kode-kategori="{{ $product->kode_kategori }}"
                                data-jumlah="{{ $product->jumlah }}"
                                data-harga="{{ $product->harga }}">
                            {{ $product->nama_barang }} - {{ $product->merek }} ({{ $product->kategori }})
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
            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-2">Deskripsi Peruntukan Permintaan *</label>
                <textarea name="deskripsi_peruntukan" rows="4" placeholder="Jelaskan untuk keperluan apa barang ini diperlukan. Contoh: Untuk keperluan rapat bulanan, penggantian peralatan yang rusak, penambahan stok kantor, dll." class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required></textarea>
            </div>
            <div class="md:col-span-2 flex items-end">
                <button type="submit" class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 transition-all font-semibold">
                    <i class="fas fa-paper-plane mr-2"></i>Kirim Permintaan Persediaan
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
            <h3 class="text-lg font-bold">📋 Histori Permintaan Persediaan</h3>
            <div class="text-sm text-gray-600">
                Total: {{ $supplyRequests->count() ?? 0 }} permintaan
            </div>
        </div>
        
        @if (empty($supplyRequests) || $supplyRequests->isEmpty())
            <div class="text-center py-12">
                <i class="fas fa-boxes text-gray-400 text-5xl mb-4"></i>
                <h4 class="text-lg font-medium text-gray-600 mb-2">Belum Ada Permintaan Persediaan</h4>
                <p class="text-gray-500 mb-4">Mulai buat permintaan persediaan pertama Anda</p>
                <button onclick="document.getElementById('supplyRequestForm').scrollIntoView()" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-all">
                    <i class="fas fa-plus mr-2"></i>Buat Permintaan
                </button>
            </div>
        @else
            <div class="space-y-4 max-h-96 overflow-y-auto">
                @foreach ($supplyRequests as $req)
                    <div class="border rounded-lg p-4 hover:shadow-md transition-shadow 
                        @if ($req->status === 'approved') border-green-200 bg-green-50
                        @elseif ($req->status === 'rejected') border-red-200 bg-red-50
                        @else border-yellow-200 bg-yellow-50 @endif">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h4 class="font-semibold text-gray-800">{{ $req->nama_barang }}</h4>
                                <p class="text-sm text-gray-600">{{ $req->merek }} • {{ $req->jumlah }} unit</p>
                            </div>
                            <span class="px-3 py-1 rounded-full text-sm font-medium
                                @if ($req->status === 'approved') bg-green-100 text-green-800
                                @elseif ($req->status === 'rejected') bg-red-100 text-red-800
                                @else bg-yellow-100 text-yellow-800 @endif">
                                {{ $req->status === 'approved' ? '✅ Diterima' : ($req->status === 'rejected' ? '❌ Ditolak' : '⏳ Menunggu') }}
                            </span>
                        </div>
                        
                        <div class="mb-3">
                            <p class="text-sm text-gray-600"><strong>Deskripsi Peruntukan:</strong></p>
                            <p class="text-sm text-gray-800 mt-1">{{ $req->deskripsi_peruntukan }}</p>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 mb-3">
                            <div>
                                <p class="text-sm text-gray-600"><strong>Tanggal:</strong> {{ $req->created_at->translatedFormat('d F Y') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600"><strong>Status:</strong> 
                                    @if ($req->status === 'approved') Diterima Admin
                                    @elseif ($req->status === 'rejected') Ditolak Admin
                                    @else Menunggu Review @endif
                                </p>
                            </div>
                        </div>
                        
                        @if ($req->komentar)
                            <div class="mt-3 p-3 bg-white rounded border-l-4 
                                @if ($req->status === 'approved') border-green-400
                                @else border-red-400 @endif">
                                <p class="text-sm font-medium text-gray-700">Komentar Admin:</p>
                                <p class="text-sm text-gray-600 mt-1">{{ $req->komentar }}</p>
                            </div>
                        @endif
                        
                        <div class="mt-3 flex justify-end">
                            <button onclick="showDetailModal({{ $req->id }})" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                <i class="fas fa-eye mr-1"></i>Lihat Detail
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    </div>

    <!-- Modal Detail Permintaan Persediaan -->
    <div id="detailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center pb-3">
                    <h3 class="text-lg font-bold text-gray-900">Detail Permintaan Persediaan</h3>
                    <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="mt-2 px-7 py-3">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">ID Request</label>
                            <p id="detail-id" class="mt-1 text-sm text-gray-900"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama Barang</label>
                            <p id="detail-nama-barang" class="mt-1 text-sm text-gray-900"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Merek</label>
                            <p id="detail-merek" class="mt-1 text-sm text-gray-900"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jumlah</label>
                            <p id="detail-jumlah" class="mt-1 text-sm text-gray-900"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Pemohon</label>
                            <p id="detail-pemohon" class="mt-1 text-sm text-gray-900"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <p id="detail-status" class="mt-1 text-sm"></p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Deskripsi Peruntukan</label>
                            <p id="detail-deskripsi" class="mt-1 text-sm text-gray-900 p-3 bg-gray-50 rounded-md"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Request</label>
                            <p id="detail-tanggal" class="mt-1 text-sm text-gray-900"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Admin Approval</label>
                            <p id="detail-admin" class="mt-1 text-sm text-gray-900"></p>
                        </div>
                    </div>
                    <div id="admin-comment" class="mt-4 hidden">
                        <label class="block text-sm font-medium text-gray-700">Komentar Admin</label>
                        <div id="detail-komentar" class="mt-1 p-3 bg-blue-50 rounded-md border-l-4 border-blue-400">
                        </div>
                    </div>
                </div>
                <div class="flex justify-end pt-2">
                    <button onclick="closeDetailModal()" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Tutup
                    </button>
                </div>
            </div>
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

    document.getElementById('supplyRequestForm').addEventListener('submit', async function(e) {
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
        }
        
        try {
            const response = await fetch('{{ route("user.supply-request.store") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            });

            if (response.ok) {
                alert('Permintaan persediaan berhasil dikirim!');
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

    function showDetailModal(id) {
        // Redirect ke halaman detail
        window.location.href = `{{ url('user/permintaan-persediaan') }}/${id}/detail`;
    }

    function closeDetailModal() {
        document.getElementById('detailModal').classList.add('hidden');
    }

    // Close modal when clicking outside
    document.getElementById('detailModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDetailModal();
        }
    });
</script>
@endpush
