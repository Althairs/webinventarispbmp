@extends('layouts.layout')
@section('title', 'Peminjaman Aset')
@section('Peminjaman Aset', 'active')

@section('content')
    <div class="bg-white rounded-lg card-shadow">
        <div class="p-6 border-b">
            <h3 class="text-lg font-bold"> Riwayat Peminjaman Aset</h3>
        </div>
        
        {{-- Search and Filter --}}
        @include('partials.search_filter_custom', ['placeholder' => 'Cari berdasarkan nama instansi, nama peminjam, NIP, nama barang, jenis, atau kabupaten/kota...'])
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left">No</th>
                        <th class="px-6 py-3 text-left">Nama Instansi</th>
                        <th class="px-6 py-3 text-left">Nama Peminjam</th>
                        <th class="px-6 py-3 text-left">NIP</th>
                        <th class="px-6 py-3 text-left">Kabupaten/Kota</th>
                        <th class="px-6 py-3 text-left">Jenis Ruang</th>
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
                            <td class="px-6 py-4">{{ $req->nama_instansi }}</td>
                            <td class="px-6 py-4">{{ $req->nama_peminjam }}</td>
                            <td class="px-6 py-4">{{ $req->nip }}</td>
                            <td class="px-6 py-4">{{ $req->kabupaten_kota }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded text-xs bg-purple-100 text-purple-800">
                                    {{ $req->jenis }}
                                </span>
                            </td>
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
                                    <!-- @if($req->surat_path)
                                        <a href="{{ route('admin.peminjaman-aset.view-surat', $req->id) }}" target="_blank" class="text-blue-600 hover:text-blue-800 ml-2">
                                            <i class="fas fa-file-alt"></i> Lihat Surat Uploaded
                                        </a>
                                        <a href="{{ route('admin.peminjaman-aset.download-surat', $req->id) }}" class="text-purple-600 hover:text-purple-800 ml-2">
                                            <i class="fas fa-download"></i> Download Surat
                                        </a>
                                    @endif
                                    @if($req->suratPersetujuan)
                                        <a href="{{ route('surat-persetujuan.preview', $req->suratPersetujuan->id) }}" 
                                           target="_blank" 
                                           class="text-blue-600 hover:text-blue-800 ml-2">
                                            <i class="fas fa-eye"></i> Lihat Surat Persetujuan
                                        </a>
                                        <a href="{{ route('surat-persetujuan.download', $req->suratPersetujuan->id) }}" 
                                           class="text-purple-600 hover:text-purple-800 ml-2">
                                            <i class="fas fa-download"></i> Download Persetujuan
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

    <!-- Modal Detail Peminjaman Aset -->
    <div id="detailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-2/3 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center pb-3">
                    <h3 class="text-lg font-bold text-gray-900">Detail Peminjaman Aset</h3>
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
                            <label class="block text-sm font-medium text-gray-700">Nama Instansi</label>
                            <p id="detail-nama-instansi" class="mt-1 text-sm text-gray-900"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama Peminjam</label>
                            <p id="detail-nama-peminjam" class="mt-1 text-sm text-gray-900"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">NIP</label>
                            <p id="detail-nip" class="mt-1 text-sm text-gray-900"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kabupaten/Kota</label>
                            <p id="detail-kabupaten-kota" class="mt-1 text-sm text-gray-900"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jenis Ruang</label>
                            <p id="detail-nama-gedung" class="mt-1 text-sm text-gray-900"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <p id="detail-status" class="mt-1 text-sm"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Pinjaman</label>
                            <p id="detail-tanggal-pinjaman" class="mt-1 text-sm text-gray-900"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
                            <p id="detail-tanggal-selesai" class="mt-1 text-sm text-gray-900"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jam Mulai</label>
                            <p id="detail-jam-mulai" class="mt-1 text-sm text-gray-900"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jam Selesai</label>
                            <p id="detail-jam-selesai" class="mt-1 text-sm text-gray-900"></p>
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
            const response = await fetch(`{{ url('kepala-bpmp/peminjaman-aset') }}/${id}/detail`);
            const data = await response.json();
            
            if (response.ok) {
                document.getElementById('detail-id').textContent = data.id;
                document.getElementById('detail-nama-instansi').textContent = data.nama_instansi || 'N/A';
                document.getElementById('detail-nama-peminjam').textContent = data.nama_peminjam || 'N/A';
                document.getElementById('detail-nip').textContent = data.nip || 'N/A';
                document.getElementById('detail-kabupaten-kota').textContent = data.kabupaten_kota || 'N/A';
                document.getElementById('detail-nama-gedung').textContent = data.jenis || 'N/A';
                document.getElementById('detail-tanggal-pinjaman').textContent = data.tanggal_pinjaman || 'N/A';
                document.getElementById('detail-tanggal-selesai').textContent = data.tanggal_selesai || 'N/A';
                document.getElementById('detail-jam-mulai').textContent = data.jam_mulai || 'N/A';
                document.getElementById('detail-jam-selesai').textContent = data.jam_selesai || 'N/A';
                
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

