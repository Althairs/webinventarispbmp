@extends('layouts.layout') {{-- Asumsikan ini adalah layout utama Anda --}}
@section('title', 'Minta Barang & Riwayat')
@section('Minta Barang & Riwayat', 'active')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg card-shadow p-6 mb-6">
        <h3 class="text-lg font-bold mb-4">📝 Form Permintaan Pengadaan Barang</h3>
        {{-- Mengubah method dari GET ke POST dan menambahkan action ke route store --}}
        <form id="requestForm" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-2">Nama Barang *</label>
                <input type="text" name="nama_barang" placeholder="Contoh: Laptop Dell Inspiron" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">Kategori *</label>
                <input type="text" name="kategori" placeholder="Contoh: Elektronik" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">Merek *</label>
                <input type="text" name="merek" placeholder="Contoh: Dell, HP, Canon" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">Ruangan Tujuan *</label>
                <input type="text" name="ruangan" placeholder="Contoh: Lab Komputer, Ruang Guru" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">Jumlah *</label>
                {{-- Mengubah nama field 'jumlah' --}}
                <input type="number" name="jumlah" placeholder="Masukkan jumlah" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" min="1" required>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full btn-primary text-white py-2 rounded-lg hover:opacity-90 transition-all">
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
            <h3 class="text-lg font-bold">📋 Histori Permintaan Barang</h3>
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
                                <p class="text-sm text-gray-600"><strong>Ruangan:</strong> {{ $req->ruangan }}</p>
                                {{-- PERBAIKAN: Menggunakan $req->jumlah --}}
                                <p class="text-sm text-gray-600"><strong>Jumlah:</strong> {{ $req->jumlah }} unit</p>
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

                        @if ($req->komentar)
                            {{-- PERBAIKAN: Mengganti $req->comment menjadi $req->komentar --}}
                            <div class="mt-3 p-3 bg-white rounded border-l-4
                                @if ($req->status === 'approved') border-green-400
                                @else border-red-400 @endif">
                                <p class="text-sm font-medium text-gray-700">Komentar Admin:</p>
                                <p class="text-sm text-gray-600 mt-1">{{ $req->komentar }}</p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('requestForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        try {
            // PERBAIKAN: Mengubah nama route
            const response = await fetch('{{ route("user.request.store") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(Object.fromEntries(formData.entries()))
            });

            if (response.ok) {
                alert('Permintaan berhasil dikirim!');
                location.reload();
            } else {
                const errorData = await response.json();
                alert('Gagal mengirim permintaan: ' + JSON.stringify(errorData.errors));
            }
        } catch (error) {
            alert('Terjadi kesalahan saat mengirim permintaan.');
            console.error(error);
        }
    });
</script>
@endpush
