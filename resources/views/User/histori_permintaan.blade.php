@extends('layouts.layout')
@section('title', 'Histori Permintaan')
@section('Histori Permintaan', 'active')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg p-6 text-white mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold mb-2 flex items-center">
                    <i class="fas fa-history mr-3"></i>
                    Histori Permintaan & Surat Persetujuan
                </h2>
                <p class="text-blue-100">Lihat semua permintaan Anda beserta surat persetujuan yang telah diupload admin</p>
            </div>
            <div class="text-right">
                <div class="bg-white bg-opacity-20 rounded-lg p-4">
                    <p class="text-sm text-blue-100">Total Permintaan</p>
                    <p class="text-3xl font-bold">{{ $paginatedRequests->total() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter dan Search -->
    <div class="bg-white rounded-lg shadow mb-6 p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Filter Jenis</label>
                <select id="filterJenis" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Jenis</option>
                    <option value="SPB">Peminjaman Barang</option>
                    <option value="SPP">Permintaan Persediaan</option>
                    <option value="SPK">Peminjaman Kendaraan</option>
                    <option value="SPA">Peminjaman Aset</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Filter Status</label>
                <select id="filterStatus" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Status</option>
                    <option value="pending">Menunggu</option>
                    <option value="approved">Diterima</option>
                    <option value="rejected">Ditolak</option>
                    <option value="diteruskan_ke_kasubag">Diteruskan ke Kasubag</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Cari</label>
                <input type="text" id="searchInput" placeholder="Cari berdasarkan nama barang..."
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>
    </div>

    <!-- Daftar Permintaan -->
    <div class="space-y-4" id="requestsList">
        @forelse($paginatedRequests as $request)
        <div class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow duration-300 request-item"
             data-jenis="{{ $request['type'] }}"
             data-status="{{ $request['status'] }}"
             data-title="{{ strtolower($request['title']) }}">

            <!-- Header Card -->
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="p-3 rounded-full {{ $request['type'] == 'SPB' ? 'bg-blue-100 text-blue-600' : ($request['type'] == 'SPP' ? 'bg-green-100 text-green-600' : ($request['type'] == 'SPK' ? 'bg-yellow-100 text-yellow-600' : 'bg-purple-100 text-purple-600')) }}">
                            @if($request['type'] == 'SPB')
                                <i class="fas fa-box text-xl"></i>
                            @elseif($request['type'] == 'SPP')
                                <i class="fas fa-boxes text-xl"></i>
                            @elseif($request['type'] == 'SPK')
                                <i class="fas fa-car text-xl"></i>
                            @else
                                <i class="fas fa-building text-xl"></i>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ $request['title'] }}</h3>
                            <p class="text-sm text-gray-600">{{ $request['type_name'] }}</p>
                            <p class="text-xs text-gray-500">{{ $request['created_at']->format('d M Y, H:i') }}</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3">
                        <!-- Status Badge -->
                        <span class="px-3 py-1 rounded-full text-sm font-medium
                            @if($request['status'] == 'approved') bg-green-100 text-green-800
                            @elseif($request['status'] == 'rejected') bg-red-100 text-red-800
                            @elseif($request['status'] == 'diteruskan_ke_kasubag') bg-yellow-100 text-yellow-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            @if($request['status'] == 'approved') Diterima
                            @elseif($request['status'] == 'rejected') Ditolak
                            @elseif($request['status'] == 'diteruskan_ke_kasubag') Diteruskan ke Kasubag
                            @else Menunggu
                            @endif
                        </span>

                        <!-- Surat Persetujuan Badge -->
                        @if($request['surat_persetujuan'])
                        <span class="px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            <i class="fas fa-file-pdf mr-1"></i>
                            Surat Tersedia
                        </span>
                        @else
                        <span class="px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-600">
                            <i class="fas fa-clock mr-1"></i>
                            Menunggu Surat
                        </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Content Card -->
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Detail Permintaan -->
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                            <i class="fas fa-info-circle mr-2 text-blue-600"></i>
                            Detail Permintaan
                        </h4>
                        <div class="space-y-2 text-sm">
                            @if($request['type'] == 'SPB')
                                <p><span class="font-medium">Kode Barang:</span> {{ $request['request_data']->kode_barang ?? '-' }}</p>
                                <p><span class="font-medium">Kategori:</span> {{ $request['request_data']->kategori ?? '-' }}</p>
                                <p><span class="font-medium">Merek:</span> {{ $request['request_data']->marek ?? '-' }}</p>
                                <p><span class="font-medium">Jumlah:</span> {{ $request['request_data']->jumlah ?? '-' }}</p>
                            @elseif($request['type'] == 'SPP')
                                <p><span class="font-medium">Merek:</span> {{ $request['request_data']->merek ?? '-' }}</p>
                                <p><span class="font-medium">Jumlah:</span> {{ $request['request_data']->jumlah ?? '-' }}</p>
                            @elseif($request['type'] == 'SPK')
                                <p><span class="font-medium">Kategori:</span> {{ $request['request_data']->kategori ?? '-' }}</p>
                                <p><span class="font-medium">Merek:</span> {{ $request['request_data']->merek ?? '-' }}</p>
                                {{-- <p><span class="font-medium">Nomor Polisi:</span> {{ $request['request_data']->nomor_polisi ?? '-' }}</p> --}}
                            @else
                                <p><span class="font-medium">Jenis:</span> {{ $request['request_data']->jenis ?? '-' }}</p>
                                <p><span class="font-medium">Tanggal Pinjaman:</span> {{ $request['request_data']->tanggal_pinjaman ? $request['request_data']->tanggal_pinjaman->format('d M Y') : '-' }}</p>
                            @endif
                            <p><span class="font-medium">Deskripsi:</span> {{ $request['request_data']->deskripsi_peruntukan ?? '-' }}</p>
                        </div>
                    </div>

                    <!-- Surat Persetujuan -->
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                            <i class="fas fa-file-alt mr-2 text-green-600"></i>
                            Surat Persetujuan
                        </h4>
                        @if($request['surat_persetujuan'])
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center">
                                    <i class="fas fa-file-pdf text-red-600 mr-2"></i>
                                    <span class="font-medium text-green-800">{{ $request['surat_persetujuan']->file_name }}</span>
                                </div>
                                <span class="text-xs text-green-600">{{ $request['surat_persetujuan']->created_at->format('d M Y') }}</span>
                            </div>

                            @if($request['surat_persetujuan']->nomor_surat)
                            <p class="text-sm text-green-700 mb-2">
                                <span class="font-medium">Nomor Surat:</span> {{ $request['surat_persetujuan']->nomor_surat }}
                            </p>
                            @endif

                            @if($request['surat_persetujuan']->keterangan)
                            <p class="text-sm text-green-700 mb-3">
                                <span class="font-medium">Keterangan:</span> {{ $request['surat_persetujuan']->keterangan }}
                            </p>
                            @endif

                            <div class="flex space-x-2">
                                <a href="{{ route('surat-persetujuan.download', $request['surat_persetujuan']->id) }}"
                                   class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-download mr-2"></i>
                                    Download
                                </a>
                                <a href="{{ route('surat-persetujuan.preview', $request['surat_persetujuan']->id) }}"
                                   target="_blank"
                                   class="inline-flex items-center px-3 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
                                    <i class="fas fa-eye mr-2"></i>
                                    Lihat
                                </a>
                            </div>
                        </div>
                        @else
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-center">
                            <i class="fas fa-clock text-gray-400 text-2xl mb-2"></i>
                            <p class="text-gray-600 text-sm">Surat persetujuan belum tersedia</p>
                            <p class="text-gray-500 text-xs mt-1">Admin akan mengupload surat setelah permintaan Diterima</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <i class="fas fa-inbox text-gray-400 text-6xl mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Permintaan</h3>
            <p class="text-gray-600 mb-6">Anda belum membuat permintaan apapun. Mulai dengan membuat permintaan baru.</p>
            <a href="{{ route('user.request.create') }}"
               class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-plus mr-2"></i>
                Buat Permintaan Baru
            </a>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($paginatedRequests->hasPages())
        <div class="mt-8">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Menampilkan
                    <span class="font-medium">{{ $paginatedRequests->firstItem() }}</span>
                    sampai
                    <span class="font-medium">{{ $paginatedRequests->lastItem() }}</span>
                    dari
                    <span class="font-medium">{{ $paginatedRequests->total() }}</span>
                    hasil
                </div>

                <div class="flex items-center space-x-2">
                    {{-- Previous Page Link --}}
                    @if ($paginatedRequests->onFirstPage())
                        <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                            <i class="fas fa-chevron-left mr-1"></i>Sebelumnya
                        </span>
                    @else
                        <a href="{{ $paginatedRequests->previousPageUrl() }}" class="px-3 py-2 text-sm text-purple-600 bg-white border border-purple-200 rounded-lg hover:bg-purple-50 hover:border-purple-300 transition-colors">
                            <i class="fas fa-chevron-left mr-1"></i>Sebelumnya
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    <div class="flex items-center space-x-1">
                        @php
                            $currentPage = $paginatedRequests->currentPage();
                            $lastPage = $paginatedRequests->lastPage();

                            // Hitung range halaman yang akan ditampilkan (maksimal 5 halaman)
                            $maxPages = 5;
                            $halfMax = floor($maxPages / 2);

                            // Tentukan halaman awal dan akhir
                            if ($lastPage <= $maxPages) {
                                // Jika total halaman <= 5, tampilkan semua
                                $startPage = 1;
                                $endPage = $lastPage;
                            } else {
                                // Jika total halaman > 5, hitung range
                                if ($currentPage <= $halfMax + 1) {
                                    // Jika current page di awal (1-3), tampilkan 1-5
                                    $startPage = 1;
                                    $endPage = $maxPages;
                                } elseif ($currentPage >= $lastPage - $halfMax) {
                                    // Jika current page di akhir, tampilkan 5 halaman terakhir
                                    $startPage = $lastPage - $maxPages + 1;
                                    $endPage = $lastPage;
                                } else {
                                    // Jika current page di tengah, tampilkan 2 halaman sebelum dan sesudah
                                    $startPage = $currentPage - $halfMax;
                                    $endPage = $currentPage + $halfMax;
                                }
                            }
                        @endphp

                        {{-- First page dengan ellipsis --}}
                        @if ($startPage > 1)
                            <a href="{{ $paginatedRequests->url(1) }}" class="px-3 py-2 text-sm text-purple-600 bg-white border border-purple-200 rounded-lg hover:bg-purple-50 hover:border-purple-300 transition-colors">
                                1
                            </a>
                            @if ($startPage > 2)
                                <span class="px-2 text-sm text-gray-500">...</span>
                            @endif
                        @endif

                        {{-- Page range (maksimal 5 halaman) --}}
                        @for ($page = $startPage; $page <= $endPage; $page++)
                            @if ($page == $currentPage)
                                <span class="px-3 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $paginatedRequests->url($page) }}" class="px-3 py-2 text-sm text-purple-600 bg-white border border-purple-200 rounded-lg hover:bg-purple-50 hover:border-purple-300 transition-colors">
                                    {{ $page }}
                                </a>
                            @endif
                        @endfor

                        {{-- Last page dengan ellipsis --}}
                        @if ($endPage < $lastPage)
                            @if ($endPage < $lastPage - 1)
                                <span class="px-2 text-sm text-gray-500">...</span>
                            @endif
                            <a href="{{ $paginatedRequests->url($lastPage) }}" class="px-3 py-2 text-sm text-purple-600 bg-white border border-purple-200 rounded-lg hover:bg-purple-50 hover:border-purple-300 transition-colors">
                                {{ $lastPage }}
                            </a>
                        @endif
                    </div>

                    {{-- Next Page Link --}}
                    @if ($paginatedRequests->hasMorePages())
                        <a href="{{ $paginatedRequests->nextPageUrl() }}" class="px-3 py-2 text-sm text-purple-600 bg-white border border-purple-200 rounded-lg hover:bg-purple-50 hover:border-purple-300 transition-colors">
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
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterJenis = document.getElementById('filterJenis');
    const filterStatus = document.getElementById('filterStatus');
    const searchInput = document.getElementById('searchInput');
    const requestItems = document.querySelectorAll('.request-item');

    function filterRequests() {
        const jenisValue = filterJenis.value;
        const statusValue = filterStatus.value;
        const searchValue = searchInput.value.toLowerCase();

        requestItems.forEach(item => {
            const jenis = item.dataset.jenis;
            const status = item.dataset.status;
            const title = item.dataset.title;

            const jenisMatch = !jenisValue || jenis === jenisValue;
            const statusMatch = !statusValue || status === statusValue;
            const searchMatch = !searchValue || title.includes(searchValue);

            if (jenisMatch && statusMatch && searchMatch) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    }

    filterJenis.addEventListener('change', filterRequests);
    filterStatus.addEventListener('change', filterRequests);
    searchInput.addEventListener('input', filterRequests);
});
</script>
@endsection
