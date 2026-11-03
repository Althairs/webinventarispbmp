@extends('layouts.layout')
@section('title', 'Histori Peminjaman Aset')
@section('Histori Peminjaman Aset', 'active')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-lg p-6 text-white mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold mb-2 flex items-center">
                    <i class="fas fa-building mr-3"></i>
                    Histori Peminjaman Aset & Surat Persetujuan
                </h2>
                <p class="text-blue-200">Lihat semua permintaan peminjaman aset Anda beserta surat persetujuan yang telah diupload admin</p>
            </div>
            <div class="text-right">
                <div class="bg-white bg-opacity-20 rounded-lg p-4">
                    <p class="text-sm text-blue-100">Total Permintaan</p>
                    <p class="text-3xl font-bold">{{ $assetRequests->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter dan Search -->
    <div class="bg-white rounded-lg shadow mb-6 p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
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
            <!-- <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Filter Jenis</label>
                <select id="filterJenis" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Jenis</option>
                    <option value="Gedung">Gedung</option>
                    <option value="Ruang">Ruang</option>
                    <option value="Lapangan">Lapangan</option>
                    <option value="Fasilitas">Fasilitas</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div> -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Cari</label>
                <input type="text" id="searchInput" placeholder="Cari berdasarkan nama barang..." 
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>
    </div>

    <!-- Daftar Permintaan -->
    <div class="space-y-4" id="requestsList">
        @forelse($assetRequests as $request)
        <div class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow duration-300 request-item" 
             data-jenis="{{ $request->jenis }}" 
             data-status="{{ $request->status }}"
             data-title="{{ strtolower($request->jenis) }}">
            
            <!-- Header Card -->
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                            <i class="fas fa-building text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ $request->jenis }}</h3>
                            <p class="text-sm text-gray-600">Peminjaman Aset</p>
                            <p class="text-xs text-gray-500">{{ $request->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-3">
                        <!-- Status Badge -->
                        <span class="px-3 py-1 rounded-full text-sm font-medium
                            @if($request->status == 'approved') bg-green-100 text-green-800
                            @elseif($request->status == 'rejected') bg-red-100 text-red-800
                            @elseif($request->status == 'diteruskan_ke_kasubag') bg-yellow-100 text-yellow-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            @if($request->status == 'approved') Diterima
                            @elseif($request->status == 'rejected') Ditolak
                            @elseif($request->status == 'diteruskan_ke_kasubag') Diteruskan ke Kasubag
                            @else Menunggu
                            @endif
                        </span>
                        
                        <!-- Surat Persetujuan Badge -->
                        @if($request->suratPersetujuan)
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
                            <p><span class="font-medium">Nama Instansi:</span> {{ $request->nama_instansi }}</p>
                            <p><span class="font-medium">Nama Peminjam:</span> {{ $request->nama_peminjam }}</p>
                            <p><span class="font-medium">NIP:</span> {{ $request->nip }}</p>
                            <p><span class="font-medium">Kabupaten/Kota:</span> {{ $request->kabupaten_kota }}</p>
                            <p><span class="font-medium">Jenis Ruang:</span> {{ $request->jenis }}</p>
                            <p><span class="font-medium">Tanggal Pinjaman:</span> {{ $request->tanggal_pinjaman->format('d M Y') }}</p>
                            <p><span class="font-medium">Jam:</span> {{ $request->jam_mulai->format('H:i') }} - {{ $request->jam_selesai->format('H:i') }}</p>
                        </div>
                    </div>
                    
                    <!-- Surat Persetujuan -->
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                            <i class="fas fa-file-alt mr-2 text-green-600"></i>
                            Surat Persetujuan
                        </h4>
                        @if($request->suratPersetujuan)
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center">
                                    <i class="fas fa-file-pdf text-red-600 mr-2"></i>
                                    <span class="font-medium text-green-800">{{ $request->suratPersetujuan->file_name }}</span>
                                </div>
                                <span class="text-xs text-green-600">{{ $request->suratPersetujuan->created_at->format('d M Y') }}</span>
                            </div>
                            
                            @if($request->suratPersetujuan->nomor_surat)
                            <p class="text-sm text-green-700 mb-2">
                                <span class="font-medium">Nomor Surat:</span> {{ $request->suratPersetujuan->nomor_surat }}
                            </p>
                            @endif
                            
                            @if($request->suratPersetujuan->keterangan)
                            <p class="text-sm text-green-700 mb-3">
                                <span class="font-medium">Keterangan:</span> {{ $request->suratPersetujuan->keterangan }}
                            </p>
                            @endif
                            
                            <div class="flex space-x-2">
                                <a href="{{ route('surat-persetujuan.download', $request->suratPersetujuan->id) }}" 
                                   class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-download mr-2"></i>
                                    Download
                                </a>
                                <a href="{{ route('surat-persetujuan.preview', $request->suratPersetujuan->id) }}" 
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

                <!-- Surat Uploaded -->
                @if($request->surat_path)
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                        <i class="fas fa-file-upload mr-2 text-blue-600"></i>
                        Surat yang Diupload
                    </h4>
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center">
                                <i class="fas fa-file-alt text-blue-600 mr-2"></i>
                                <span class="font-medium text-blue-800">{{ basename($request->surat_path) }}</span>
                            </div>
                            <span class="text-xs text-blue-600">{{ $request->created_at->format('d M Y') }}</span>
                        </div>
                        
                        <p class="text-sm text-blue-700 mb-3">
                            <span class="font-medium">Status:</span> Surat yang Anda upload saat mengajukan permintaan
                        </p>
                        
                        <div class="flex space-x-2">
                            <a href="{{ route('guest.peminjaman-aset.view-surat', $request->id) }}" 
                               target="_blank" 
                               class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-eye mr-2"></i>
                                Lihat Surat
                            </a>
                            <a href="{{ route('guest.peminjaman-aset.download-surat', $request->id) }}" 
                               class="inline-flex items-center px-3 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 transition-colors">
                                <i class="fas fa-download mr-2"></i>
                                Download Surat
                            </a>
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- Action Buttons -->
                <div class="mt-6 pt-4 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <div class="text-sm text-gray-500">
                            <span class="font-medium">ID Permintaan:</span> #{{ $request->id }}
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('guest.peminjaman-aset.detail', $request->id) }}" 
                               class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-eye mr-2"></i>
                                Detail
                            </a>
                            @if($request->status == 'approved')
                            <a href="{{ route('guest.surat.peminjaman-aset', $request->id) }}" 
                               class="inline-flex items-center px-3 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                                <i class="fas fa-file-pdf mr-2"></i>
                                Generate Surat
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-12">
            <i class="fas fa-building text-gray-400 text-5xl mb-4"></i>
            <h4 class="text-lg font-medium text-gray-600 mb-2">Belum Ada Permintaan</h4>
            <p class="text-gray-500 mb-4">Mulai buat permintaan peminjaman aset pertama Anda</p>
            <a href="{{ route('guest.peminjaman-aset') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-plus mr-2"></i>Buat Permintaan
            </a>
        </div>
        @endforelse
    </div>
    
    <!-- Pagination -->
    @if($assetRequests->hasPages())
        <div class="mt-8">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Menampilkan 
                    <span class="font-medium">{{ $assetRequests->firstItem() }}</span> 
                    sampai 
                    <span class="font-medium">{{ $assetRequests->lastItem() }}</span> 
                    dari 
                    <span class="font-medium">{{ $assetRequests->total() }}</span> 
                    hasil
                </div>
                
                <div class="flex items-center space-x-2">
                    {{-- Previous Page Link --}}
                    @if ($assetRequests->onFirstPage())
                        <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                            <i class="fas fa-chevron-left mr-1"></i>Sebelumnya
                        </span>
                    @else
                        <a href="{{ $assetRequests->previousPageUrl() }}" class="px-3 py-2 text-sm text-purple-600 bg-white border border-purple-200 rounded-lg hover:bg-purple-50 hover:border-purple-300 transition-colors">
                            <i class="fas fa-chevron-left mr-1"></i>Sebelumnya
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    <div class="flex items-center space-x-1">
                        @php
                            $currentPage = $assetRequests->currentPage();
                            $lastPage = $assetRequests->lastPage();
                            
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
                            <a href="{{ $assetRequests->url(1) }}" class="px-3 py-2 text-sm text-purple-600 bg-white border border-purple-200 rounded-lg hover:bg-purple-50 hover:border-purple-300 transition-colors">
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
                                <a href="{{ $assetRequests->url($page) }}" class="px-3 py-2 text-sm text-purple-600 bg-white border border-purple-200 rounded-lg hover:bg-purple-50 hover:border-purple-300 transition-colors">
                                    {{ $page }}
                                </a>
                            @endif
                        @endfor

                        {{-- Last page dengan ellipsis --}}
                        @if ($endPage < $lastPage)
                            @if ($endPage < $lastPage - 1)
                                <span class="px-2 text-sm text-gray-500">...</span>
                            @endif
                            <a href="{{ $assetRequests->url($lastPage) }}" class="px-3 py-2 text-sm text-purple-600 bg-white border border-purple-200 rounded-lg hover:bg-purple-50 hover:border-purple-300 transition-colors">
                                {{ $lastPage }}
                            </a>
                        @endif
                    </div>

                    {{-- Next Page Link --}}
                    @if ($assetRequests->hasMorePages())
                        <a href="{{ $assetRequests->nextPageUrl() }}" class="px-3 py-2 text-sm text-purple-600 bg-white border border-purple-200 rounded-lg hover:bg-purple-50 hover:border-purple-300 transition-colors">
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterStatus = document.getElementById('filterStatus');
    const filterJenis = document.getElementById('filterJenis');
    const searchInput = document.getElementById('searchInput');
    const requestItems = document.querySelectorAll('.request-item');

    function filterRequests() {
        const statusValue = filterStatus.value;
        const jenisValue = filterJenis.value;
        const searchValue = searchInput.value.toLowerCase();

        requestItems.forEach(item => {
            const status = item.dataset.status;
            const jenis = item.dataset.jenis;
            const title = item.dataset.title;

            const statusMatch = !statusValue || status === statusValue;
            const jenisMatch = !jenisValue || jenis === jenisValue;
            const searchMatch = !searchValue || title.includes(searchValue);

            if (statusMatch && jenisMatch && searchMatch) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    }

    filterStatus.addEventListener('change', filterRequests);
    filterJenis.addEventListener('change', filterRequests);
    searchInput.addEventListener('input', filterRequests);
});
</script>
@endpush
@endsection
