@extends('layouts.layout')
@section('title', 'Peminjaman Kendaraan')
@section('Peminjaman Kendaraan', 'active')

@section('content')
    <div class="bg-white rounded-lg card-shadow">
        <div class="p-6 border-b">
            <h3 class="text-lg font-bold">Riwayat Peminjaman Kendaraan </h3>
        </div>
        
        {{-- Search and Filter --}}
        @include('partials.search_filter_custom', ['placeholder' => 'Cari berdasarkan nama kendaraan, kategori, merek, atau deskripsi peruntukan...'])
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left">No</th>
                        <th class="px-6 py-3 text-left">Nama Kendaraan</th>
                        <th class="px-6 py-3 text-left">Kategori</th>
                        <th class="px-6 py-3 text-left">Jumlah</th>
                        <th class="px-6 py-3 text-left">Deskripsi Peruntukan</th>
                        <th class="px-6 py-3 text-left">Peminjam</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($requests as $index => $req)
                        <tr class="border-t">
                            <td class="px-6 py-4">
                                <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-sm font-medium">{{ $requests->firstItem() + $index }}</span>
                            </td>
                            <td class="px-6 py-4">{{ $req->nama_kendaraan ?? 'N/A' }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded text-xs bg-blue-100 text-blue-800">
                                    {{ ucfirst($req->kategori) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">{{ $req->jumlah }}</td>
                            <td class="px-6 py-4">
                                <div class="max-w-xs truncate" title="{{ $req->deskripsi_peruntukan }}">
                                    {{ $req->deskripsi_peruntukan }}
                                </div>
                            </td>
                            <td class="px-6 py-4">{{ $req->user->nama_lengkap ?? 'N/A' }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded text-sm
                                    @if ($req->status === 'approved') bg-green-100 text-green-800
                                    @elseif ($req->status === 'rejected') bg-red-100 text-red-800
                                    @elseif ($req->status === 'direview') bg-blue-100 text-blue-800
                                    @elseif ($req->status === 'diteruskan_ke_kasubag') bg-purple-100 text-purple-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ ucfirst(str_replace('_', ' ', $req->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    <button onclick="showDetailModal({{ $req->id }})" class="text-green-600 hover:text-green-800">
                                        <i class="fas fa-info-circle"></i> Lihat Detail
                                    </button>
                                    <!-- @if($req->suratPersetujuan)
                                        <a href="{{ route('surat-persetujuan.preview', $req->suratPersetujuan->id) }}" 
                                           target="_blank" 
                                           class="text-blue-600 hover:text-blue-800 ml-2">
                                            <i class="fas fa-eye"></i> Lihat Surat
                                        </a>
                                        <a href="{{ route('surat-persetujuan.download', $req->suratPersetujuan->id) }}" 
                                           class="text-purple-600 hover:text-purple-800 ml-2">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                    @endif -->
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t bg-gray-50">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Menampilkan 
                    <span class="font-medium">{{ $requests->firstItem() }}</span> 
                    sampai 
                    <span class="font-medium">{{ $requests->lastItem() }}</span> 
                    dari 
                    <span class="font-medium">{{ $requests->total() }}</span> 
                    hasil
                </div>
                
                <div class="flex items-center space-x-2">
                    @if ($requests->onFirstPage())
                        <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                            <i class="fas fa-chevron-left mr-1"></i>Sebelumnya
                        </span>
                    @else
                        <a href="{{ $requests->previousPageUrl() }}" class="px-3 py-2 text-sm text-purple-600 bg-white border border-purple-200 rounded-lg hover:bg-purple-50 hover:border-purple-300 transition-colors">
                            <i class="fas fa-chevron-left mr-1"></i>Sebelumnya
                        </a>
                    @endif

                    <div class="flex items-center space-x-1">
                        @php
                            $currentPage = $requests->currentPage();
                            $lastPage = $requests->lastPage();
                            $maxPages = 5;
                            $halfMax = floor($maxPages / 2);
                            
                            if ($lastPage <= $maxPages) {
                                $startPage = 1;
                                $endPage = $lastPage;
                            } else {
                                if ($currentPage <= $halfMax + 1) {
                                    $startPage = 1;
                                    $endPage = $maxPages;
                                } elseif ($currentPage >= $lastPage - $halfMax) {
                                    $startPage = $lastPage - $maxPages + 1;
                                    $endPage = $lastPage;
                                } else {
                                    $startPage = $currentPage - $halfMax;
                                    $endPage = $currentPage + $halfMax;
                                }
                            }
                        @endphp

                        @if ($startPage > 1)
                            <a href="{{ $requests->url(1) }}" class="px-3 py-2 text-sm text-purple-600 bg-white border border-purple-200 rounded-lg hover:bg-purple-50 hover:border-purple-300 transition-colors">
                                1
                            </a>
                            @if ($startPage > 2)
                                <span class="px-2 text-sm text-gray-500">...</span>
                            @endif
                        @endif

                        @for ($page = $startPage; $page <= $endPage; $page++)
                            @if ($page == $currentPage)
                                <span class="px-3 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $requests->url($page) }}" class="px-3 py-2 text-sm text-purple-600 bg-white border border-purple-200 rounded-lg hover:bg-purple-50 hover:border-purple-300 transition-colors">
                                    {{ $page }}
                                </a>
                            @endif
                        @endfor

                        @if ($endPage < $lastPage)
                            @if ($endPage < $lastPage - 1)
                                <span class="px-2 text-sm text-gray-500">...</span>
                            @endif
                            <a href="{{ $requests->url($lastPage) }}" class="px-3 py-2 text-sm text-purple-600 bg-white border border-purple-200 rounded-lg hover:bg-purple-50 hover:border-purple-300 transition-colors">
                                {{ $lastPage }}
                            </a>
                        @endif
                    </div>

                    @if ($requests->hasMorePages())
                        <a href="{{ $requests->nextPageUrl() }}" class="px-3 py-2 text-sm text-purple-600 bg-white border border-purple-200 rounded-lg hover:bg-purple-50 hover:border-purple-300 transition-colors">
                            Selanjutnya<i class="fas fa-chevron-right ml-1"></i>
                        </a>
                    @else
                        <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                            Selanjutnya<i class="fas fa-chevron-right ml-1"></i>
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail Peminjaman Kendaraan -->
    <div id="detailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center pb-3">
                    <h3 class="text-lg font-bold text-gray-900">Detail Peminjaman Kendaraan</h3>
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
                            <label class="block text-sm font-medium text-gray-700">Nama Kendaraan</label>
                            <p id="detail-nama-kendaraan" class="mt-1 text-sm text-gray-900"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kategori</label>
                            <p id="detail-kategori" class="mt-1 text-sm"></p>
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
                            <label class="block text-sm font-medium text-gray-700">Tanggal Peminjaman</label>
                            <p id="detail-tanggal-peminjaman" class="mt-1 text-sm text-gray-900"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Pengembalian</label>
                            <p id="detail-tanggal-pengembalian" class="mt-1 text-sm text-gray-900"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Pemohon</label>
                            <p id="detail-pemohon" class="mt-1 text-sm text-gray-900"></p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Deskripsi Peruntukan</label>
                            <p id="detail-deskripsi" class="mt-1 text-sm text-gray-900 p-3 bg-gray-50 rounded-md"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <p id="detail-status" class="mt-1 text-sm"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Request</label>
                            <p id="detail-tanggal" class="mt-1 text-sm text-gray-900"></p>
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
    async function showDetailModal(id) {
        try {
            const response = await fetch(`{{ url('kepala-bpmp/peminjaman-kendaraan') }}/${id}/detail`);
            const data = await response.json();
            
            if (response.ok) {
                document.getElementById('detail-id').textContent = data.id;
                document.getElementById('detail-nama-kendaraan').textContent = data.nama_kendaraan || 'N/A';
                document.getElementById('detail-merek').textContent = data.merek || 'N/A';
                document.getElementById('detail-jumlah').textContent = data.jumlah;
                document.getElementById('detail-tanggal-peminjaman').textContent = data.tanggal_peminjaman ? new Date(data.tanggal_peminjaman).toLocaleDateString('id-ID') : 'N/A';
                document.getElementById('detail-tanggal-pengembalian').textContent = data.tanggal_pengembalian ? new Date(data.tanggal_pengembalian).toLocaleDateString('id-ID') : 'N/A';
                document.getElementById('detail-deskripsi').textContent = data.deskripsi_peruntukan || 'N/A';
                document.getElementById('detail-pemohon').textContent = data.user ? data.user.nama_lengkap : 'N/A';
                
                const kategoriElement = document.getElementById('detail-kategori');
                kategoriElement.textContent = data.kategori ? data.kategori.charAt(0).toUpperCase() + data.kategori.slice(1) : 'N/A';
                kategoriElement.className = 'mt-1 text-sm px-2 py-1 rounded bg-blue-100 text-blue-800';
                
                const statusElement = document.getElementById('detail-status');
                statusElement.textContent = data.status ? data.status.charAt(0).toUpperCase() + data.status.slice(1).replace('_', ' ') : 'N/A';
                statusElement.className = 'mt-1 text-sm px-2 py-1 rounded';
                if (data.status === 'approved') {
                    statusElement.className += ' bg-green-100 text-green-800';
                } else if (data.status === 'rejected') {
                    statusElement.className += ' bg-red-100 text-red-800';
                } else if (data.status === 'direview') {
                    statusElement.className += ' bg-blue-100 text-blue-800';
                } else if (data.status === 'diteruskan_ke_kasubag') {
                    statusElement.className += ' bg-purple-100 text-purple-800';
                } else {
                    statusElement.className += ' bg-yellow-100 text-yellow-800';
                }
                
                document.getElementById('detail-tanggal').textContent = data.created_at ? new Date(data.created_at).toLocaleDateString('id-ID') : 'N/A';
                
                document.getElementById('detailModal').classList.remove('hidden');
            } else {
                alert('Gagal mengambil detail data.');
            }
        } catch (error) {
            alert('Terjadi kesalahan.');
            console.error(error);
        }
    }

    function closeDetailModal() {
        document.getElementById('detailModal').classList.add('hidden');
    }

    document.getElementById('detailModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDetailModal();
        }
    });
</script>
@endpush

