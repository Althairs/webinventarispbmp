@extends('layouts.layout')
@section('title', 'Request Barang')
@section('Request Barang', 'active')


@section('content')
    <div class="bg-white rounded-lg card-shadow">
        <div class="p-6 border-b">
            <h3 class="text-lg font-bold">Permintaan Pengadaan Barang</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left">ID</th>
                        <th class="px-6 py-3 text-left">Kode Barang</th>
                        <th class="px-6 py-3 text-left">Nama Barang</th>
                        <th class="px-6 py-3 text-left">Kategori</th>
                        <th class="px-6 py-3 text-left">Merek</th>
                        <th class="px-6 py-3 text-left">Ruang Tujuan</th>
                        <th class="px-6 py-3 text-left">Jumlah</th>
                        <th class="px-6 py-3 text-left">User</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-left">Tanggal Request</th>
                        <th class="px-6 py-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($requests as $req)
                        <tr class="border-t">
                            <td class="px-6 py-4">{{ $req->id }}</td>
                            <td class="px-6 py-4">{{ $req->kode_barang }}</td>
                            {{-- Mengubah quantity menjadi jumlah --}}
                            <td class="px-6 py-4">{{ $req->jumlah }}</td>
                            {{-- Mengakses nama user dari relasi 'user' --}}
                            <td class="px-6 py-4">{{ $req->user->nama ?? 'N/A' }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded text-sm
                                    @if ($req->status === 'approved') bg-green-100 text-green-800
                                    @elseif ($req->status === 'rejected') bg-red-100 text-red-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ $req->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4">{{ $req->request_date }}</td>
                            <td class="px-6 py-4">
                                @if ($req->status === 'Menunggu')
                                    <button onclick="reviewRequest({{ $req->id }})" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm mr-2">
                                        Review
                                    </button>
                                    <button onclick="approveRequest({{ $req->id }})" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm mr-2">
                                        Setujui
                                    </button>
                                    <button onclick="rejectRequest({{ $req->id }})" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                                        Tolak
                                    </button>
                                @elseif ($req->status === 'direview')
                                    <button onclick="forwardRequest({{ $req->id }})" class="bg-purple-500 hover:bg-purple-600 text-white px-3 py-1 rounded text-sm mr-2">
                                        Teruskan ke Kasubag
                                    </button>
                                    <button onclick="rejectRequest({{ $req->id }})" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                                        Tolak
                                    </button>
                                @else
                                    <button onclick="viewRequestDetail({{ $req->id }})" class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-eye"></i> Detail
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    async function approveRequest(id) {
        const comment = prompt('Masukkan komentar (opsional):');
        if (comment !== null) {
            try {
                const response = await fetch(`{{ url('admin/request-barang') }}/${id}/approve`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ comment })
                });
                const data = await response.json();
                if (response.ok) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert('Gagal menyetujui permintaan.');
                }
            } catch (error) {
                alert('Terjadi kesalahan.');
                console.error(error);
            }
        }
    }

    async function reviewRequest(id) {
        if (confirm('Apakah Anda yakin ingin mereview permintaan ini?')) {
            try {
                const response = await fetch(`{{ url('admin/peminjaman-barang') }}/${id}/review`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                });
                const data = await response.json();
                if (response.ok) {
                    alert(data.message || 'Permintaan berhasil direview.');
                    location.reload();
                } else {
                    alert('Gagal mereview permintaan.');
                }
            } catch (error) {
                alert('Terjadi kesalahan.');
                console.error(error);
            }
        }
    }

    async function forwardRequest(id) {
        if (confirm('Apakah Anda yakin ingin meneruskan permintaan ke Kasubag?')) {
            try {
                const response = await fetch(`{{ url('admin/peminjaman-barang') }}/${id}/forward`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ action: 'forward' })
                });
                const data = await response.json();
                if (response.ok) {
                    alert(data.message || 'Permintaan berhasil diteruskan ke Kasubag.');
                    location.reload();
                } else {
                    alert('Gagal meneruskan permintaan.');
                }
            } catch (error) {
                alert('Terjadi kesalahan.');
                console.error(error);
            }
        }
    }

    async function rejectRequest(id) {
        const comment = prompt('Masukkan alasan penolakan:');
        if (comment) {
            try {
                const response = await fetch(`{{ url('admin/request-barang') }}/${id}/reject`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ comment })
                });
                const data = await response.json();
                if (response.ok) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert('Gagal menolak permintaan.');
                }
            } catch (error) {
                alert('Terjadi kesalahan.');
                console.error(error);
            }
        }
    }
</script>
@endpush
