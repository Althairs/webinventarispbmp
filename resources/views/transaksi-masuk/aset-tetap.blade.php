@extends('layouts.layout')
@section('title', 'Transaksi Barang Masuk - Aset Tetap')
@section('Transaksi Barang Masuk', 'active')

@section('content')
    <div class="bg-white rounded-lg card-shadow">
        <div class="p-6 border-b">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-bold">Transaksi Barang Masuk - Aset Tetap</h3>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.transaksi-masuk.index') }}" class="btn-secondary text-white px-4 py-2 rounded-lg hover:opacity-90">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                    <a href="{{ route('admin.transaksi-masuk.create-aset-tetap') }}" class="btn-primary text-white px-4 py-2 rounded-lg hover:opacity-90">
                        <i class="fas fa-plus mr-2"></i>Tambah Data
                    </a>
                </div>
            </div>
        </div>
        {{-- Search and Filter --}}
        @include('partials.search_filter_custom', ['placeholder' => 'Cari berdasarkan nama barang...'])
        
        <div class="p-6">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            @if ($transaksi->isEmpty())
                <div class="text-center py-8">
                    <i class="fas fa-building text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-600 mb-4">Belum ada data transaksi aset tetap.</p>
                    <a href="{{ route('admin.transaksi-masuk.create-aset-tetap') }}" class="btn-primary text-white px-4 py-2 rounded-lg hover:opacity-90">
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
                                <th class="px-6 py-3 text-left">Jumlah Masuk</th>
                                <th class="px-6 py-3 text-left">Harga</th>
                                <th class="px-6 py-3 text-left">Total</th>
                                <th class="px-6 py-3 text-left">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transaksi as $index => $item)
                                <tr class="border-t hover:bg-gray-50">
                                    <td class="px-6 py-4">{{ $index + 1 }}</td>
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
                                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-sm font-medium">{{ $item->jumlah_masuk }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 font-bold text-green-600">Rp {{ number_format($item->jumlah_masuk * $item->harga, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex space-x-2">
                                            <button onclick="editTransaksiAsetTetap({{ $item->id }})" class="text-blue-600 hover:text-blue-800 transition-colors" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button onclick="deleteTransaksiAsetTetap({{ $item->id }})" class="text-red-600 hover:text-red-800 transition-colors" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
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
    </div>

    <!-- Modal Konfirmasi Delete -->
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg p-6 max-w-md w-full">
                <div class="flex items-center mb-4">
                    <i class="fas fa-exclamation-triangle text-red-500 text-2xl mr-3"></i>
                    <h3 class="text-lg font-semibold">Konfirmasi Hapus</h3>
                </div>
                <p class="text-gray-600 mb-6">Apakah Anda yakin ingin menghapus transaksi ini? Tindakan ini tidak dapat dibatalkan.</p>
                <div class="flex justify-end space-x-3">
                    <button onclick="closeDeleteModal()" class="px-4 py-2 text-gray-600 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors">
                        Batal
                    </button>
                    <button onclick="confirmDelete()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let transaksiIdToDelete = null;

        function editTransaksiAsetTetap(id) {
            window.location.href = `/admin/transaksi-masuk/aset-tetap/${id}/edit`;
        }

        function deleteTransaksiAsetTetap(id) {
            transaksiIdToDelete = id;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            transaksiIdToDelete = null;
        }

        function confirmDelete() {
            if (transaksiIdToDelete) {
                // Buat form untuk submit delete
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/transaksi-masuk/aset-tetap/${transaksiIdToDelete}`;
                
                // Tambahkan CSRF token
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);
                
                // Tambahkan method DELETE
                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';
                form.appendChild(methodField);
                
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
@endsection
