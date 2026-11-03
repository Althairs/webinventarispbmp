@extends('layouts.layout')
@section('title', 'Histori Transaksi Keluar - Persediaan')
@section('Transaksi Barang Keluar', 'active')

@section('content')
    <div class="bg-white rounded-lg card-shadow">
        <div class="p-6 border-b">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-bold">Histori Transaksi Barang Keluar - Persediaan</h3>
                <a href="{{ route('admin.transaksi-keluar.index') }}" class="btn-secondary text-white px-4 py-2 rounded-lg hover:opacity-90">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </div>
        {{-- Search and Filter --}}
        @include('partials.search_filter_custom', ['placeholder' => 'Cari berdasarkan nama barang...'])
        
        <div class="p-6">
            @if ($transaksi->isEmpty())
                <div class="text-center py-8">
                    <i class="fas fa-boxes text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-600 mb-4">Belum ada data histori transaksi persediaan.</p>
                    <a href="{{ route('admin.transaksi-keluar.create-persediaan') }}" class="btn-primary text-white px-4 py-2 rounded-lg hover:opacity-90">
                        <i class="fas fa-plus mr-2"></i>Tambah Transaksi
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left">No</th>
                                <th class="px-6 py-3 text-left">Tanggal Input</th>
                                <th class="px-6 py-3 text-left">Kategori</th>
                                <th class="px-6 py-3 text-left">Kode Barang</th>
                                <th class="px-6 py-3 text-left">Nama Barang</th>
                                <th class="px-6 py-3 text-left">Jumlah Keluar</th>
                                <th class="px-6 py-3 text-left">Harga</th>
                                <th class="px-6 py-3 text-left">Total</th>
                                <th class="px-6 py-3 text-left">Waktu Input</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transaksi as $index => $item)
                                <tr class="border-t hover:bg-gray-50">
                                    <td class="px-6 py-4">{{ $transaksi->firstItem() + $index }}</td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm text-gray-600">{{ $item->tanggal_input->format('d/m/Y') }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-sm">{{ $item->kategori }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="font-mono text-sm bg-gray-100 px-2 py-1 rounded">{{ $item->kode_barang }}</span>
                                    </td>
                                    <td class="px-6 py-4 font-medium">{{ $item->nama_barang }}</td>
                                    <td class="px-6 py-4">
                                        <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-sm font-medium">-{{ $item->jumlah_keluar }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 font-bold text-red-600">Rp {{ number_format($item->jumlah_keluar * $item->harga, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-xs text-gray-500">{{ $item->created_at->format('d/m/Y H:i:s') }}</td>
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
                            <span class="font-medium">{{ $transaksi->firstItem() }}</span> 
                            sampai 
                            <span class="font-medium">{{ $transaksi->lastItem() }}</span> 
                            dari 
                            <span class="font-medium">{{ $transaksi->total() }}</span> 
                            hasil
                        </div>
                        
                        <div class="flex items-center space-x-2">
                            {{-- Previous Page Link --}}
                            @if ($transaksi->onFirstPage())
                                <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                                    <i class="fas fa-chevron-left mr-1"></i>Sebelumnya
                                </span>
                            @else
                                <a href="{{ $transaksi->previousPageUrl() }}" class="px-3 py-2 text-sm text-purple-600 bg-white border border-purple-200 rounded-lg hover:bg-purple-50 hover:border-purple-300 transition-colors">
                                    <i class="fas fa-chevron-left mr-1"></i>Sebelumnya
                                </a>
                            @endif

                            {{-- Pagination Elements --}}
                            <div class="flex items-center space-x-1">
                                @php
                                    $currentPage = $transaksi->currentPage();
                                    $lastPage = $transaksi->lastPage();
                                    
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
                                    <a href="{{ $transaksi->url(1) }}" class="px-3 py-2 text-sm text-purple-600 bg-white border border-purple-200 rounded-lg hover:bg-purple-50 hover:border-purple-300 transition-colors">
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
                                        <a href="{{ $transaksi->url($page) }}" class="px-3 py-2 text-sm text-purple-600 bg-white border border-purple-200 rounded-lg hover:bg-purple-50 hover:border-purple-300 transition-colors">
                                            {{ $page }}
                                        </a>
                                    @endif
                                @endfor

                                {{-- Last page dengan ellipsis --}}
                                @if ($endPage < $lastPage)
                                    @if ($endPage < $lastPage - 1)
                                        <span class="px-2 text-sm text-gray-500">...</span>
                                    @endif
                                    <a href="{{ $transaksi->url($lastPage) }}" class="px-3 py-2 text-sm text-purple-600 bg-white border border-purple-200 rounded-lg hover:bg-purple-50 hover:border-purple-300 transition-colors">
                                        {{ $lastPage }}
                                    </a>
                                @endif
                            </div>

                            {{-- Next Page Link --}}
                            @if ($transaksi->hasMorePages())
                                <a href="{{ $transaksi->nextPageUrl() }}" class="px-3 py-2 text-sm text-purple-600 bg-white border border-purple-200 rounded-lg hover:bg-purple-50 hover:border-purple-300 transition-colors">
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
    </div>
@endsection
