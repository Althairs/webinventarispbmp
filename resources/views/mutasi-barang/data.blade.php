@extends('layouts.layout')
@section('title', 'Data Mutasi Barang')
@section('Mutasi Barang', 'active')

@section('content')
<div class="bg-white rounded-lg shadow-md">
    <div class="p-6 border-b flex justify-between items-center">
        <h3 class="text-lg font-bold">Data Mutasi Barang</h3>
        <div class="flex space-x-2">
            <a href="{{ route('admin.mutasi-barang.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
            <a href="{{ route('admin.mutasi-barang.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                <i class="fas fa-plus mr-2"></i>Tambah Mutasi
            </a>
        </div>
    </div>
    {{-- Search and Filter --}}
        @include('partials.search_filter_custom', ['placeholder' => 'Cari berdasarkan nama barang...'])
        
    <div class="p-6">
        {{-- Notifikasi --}}
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

        {{-- Jika belum ada data --}}
        @if ($mutasi->isEmpty())
            <div class="text-center py-8">
                <i class="fas fa-exchange-alt text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-600 mb-4">Belum ada data mutasi barang.</p>
                <a href="{{ route('admin.mutasi-barang.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-plus mr-2"></i>Tambah Mutasi
                </a>
            </div>
        @else
            {{-- Tabel Data --}}
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead class="bg-gray-100 text-left">
                        <tr>
                            <th class="px-6 py-3 text-sm font-semibold">No</th>
                            <th class="px-6 py-3 text-sm font-semibold">Tanggal Mutasi</th>
                            <th class="px-6 py-3 text-sm font-semibold">Kode Barang</th>
                            <th class="px-6 py-3 text-sm font-semibold">Nama Barang</th>
                            <th class="px-6 py-3 text-sm font-semibold">NUP</th>
                            <th class="px-6 py-3 text-sm font-semibold">Lokasi Awal</th>
                            <th class="px-6 py-3 text-sm font-semibold">Lokasi Akhir</th>
                            <th class="px-6 py-3 text-sm font-semibold">Keterangan</th>
                            <th class="px-6 py-3 text-sm font-semibold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($mutasi as $index => $item)
                            <tr class="border-t hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $item->tanggal_mutasi->format('d/m/Y') }}</td>
                                <td class="px-6 py-4">
                                    <span class="font-mono text-sm bg-gray-100 px-2 py-1 rounded">{{ $item->kode_barang }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-800">{{ $item->nama_barang }}</td>
                                <td class="px-6 py-4">
                                    <span class="font-mono text-sm bg-blue-100 text-blue-800 px-2 py-1 rounded">{{ $item->nup }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-sm">{{ $item->lokasi_awal }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-sm">{{ $item->lokasi_akhir }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $item->keterangan ? Str::limit($item->keterangan, 40) : '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex space-x-2">
                                        <a href="{{ url('admin/mutasi-barang/' . $item->id . '/edit') }}"
                                           class="text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button onclick="deleteMutasiBarang({{ $item->id }})"
                                                class="text-red-600 hover:text-red-800">
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
</div>
<!-- Pagination -->
        <div class="px-6 py-4 border-t bg-gray-50">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Menampilkan
                    <span class="font-medium">{{ $mutasi->firstItem() }}</span>
                    sampai
                    <span class="font-medium">{{ $mutasi->lastItem() }}</span>
                    dari
                    <span class="font-medium">{{ $mutasi->total() }}</span>
                    hasil
                </div>

                <div class="flex items-center space-x-2">
                    {{-- Previous Page Link --}}
                    @if ($mutasi->onFirstPage())
                        <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                            <i class="fas fa-chevron-left mr-1"></i>Sebelumnya
                        </span>
                    @else
                        <a href="{{ $mutasi->previousPageUrl() }}" class="px-3 py-2 text-sm text-purple-600 bg-white border border-purple-200 rounded-lg hover:bg-purple-50 hover:border-purple-300 transition-colors">
                            <i class="fas fa-chevron-left mr-1"></i>Sebelumnya
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    <div class="flex items-center space-x-1">
                        @php
                            $currentPage = $mutasi->currentPage();
                            $lastPage = $mutasi->lastPage();

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
                            <a href="{{ $mutasi->url(1) }}" class="px-3 py-2 text-sm text-purple-600 bg-white border border-purple-200 rounded-lg hover:bg-purple-50 hover:border-purple-300 transition-colors">
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
                                <a href="{{ $mutasi->url($page) }}" class="px-3 py-2 text-sm text-purple-600 bg-white border border-purple-200 rounded-lg hover:bg-purple-50 hover:border-purple-300 transition-colors">
                                    {{ $page }}
                                </a>
                            @endif
                        @endfor

                        {{-- Last page dengan ellipsis --}}
                        @if ($endPage < $lastPage)
                            @if ($endPage < $lastPage - 1)
                                <span class="px-2 text-sm text-gray-500">...</span>
                            @endif
                            <a href="{{ $mutasi->url($lastPage) }}" class="px-3 py-2 text-sm text-purple-600 bg-white border border-purple-200 rounded-lg hover:bg-purple-50 hover:border-purple-300 transition-colors">
                                {{ $lastPage }}
                            </a>
                        @endif
                    </div>

                    {{-- Next Page Link --}}
                    @if ($mutasi->hasMorePages())
                        <a href="{{ $mutasi->nextPageUrl() }}" class="px-3 py-2 text-sm text-purple-600 bg-white border border-purple-200 rounded-lg hover:bg-purple-50 hover:border-purple-300 transition-colors">
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

@endsection



@push('scripts')
<script>
    // Hapus data mutasi barang
    async function deleteMutasiBarang(id) {
        if (confirm('Apakah Anda yakin ingin menghapus data mutasi barang ini?')) {
            try {
                const response = await fetch(`/admin/mutasi-barang/${id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ _method: 'DELETE' })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert(data.message || 'Gagal menghapus data mutasi barang.');
                }
            } catch (error) {
                alert('Terjadi kesalahan saat menghapus data.');
                console.error(error);
            }
        }
    }
</script>
@endpush
