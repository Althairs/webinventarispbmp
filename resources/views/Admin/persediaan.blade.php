@extends('layouts.layout')
@section('title', 'Data Persediaan')
@section('Manajemen Data Barang', 'active')
@section('Data Persediaan', 'active')

@section('content')
    <div class="bg-white rounded-lg card-shadow">
        <div class="p-6 border-b">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-bold">Data Persediaan</h3>
                <a href="{{ route('admin.persediaan.create') }}" class="btn-primary text-white px-4 py-2 rounded-lg hover:opacity-90">
                    <i class="fas fa-plus mr-2"></i>Tambah Persediaan
                </a>
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

            @if ($persediaan->isEmpty())
                <div class="text-center py-8">
                    <i class="fas fa-warehouse text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-600 mb-4">Belum ada data persediaan.</p>
                    <a href="{{ route('admin.persediaan.create') }}" class="btn-primary text-white px-4 py-2 rounded-lg hover:opacity-90">
                        <i class="fas fa-plus mr-2"></i>Tambah Persediaan
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left">No</th>
                                <th class="px-6 py-3 text-left">Kode Kategori</th>
                                <th class="px-6 py-3 text-left">Kategori</th>
                                <th class="px-6 py-3 text-left">Kode Barang</th>
                                <th class="px-6 py-3 text-left">Nama Barang</th>
                                <th class="px-6 py-3 text-left">Jenis</th>
                                <th class="px-6 py-3 text-left">Harga</th>
                                <th class="px-6 py-3 text-left">Tanggal Masuk</th>
                                <th class="px-6 py-3 text-left">Jumlah</th>
                                <th class="px-6 py-3 text-left">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($persediaan as $index => $item)
                                <tr class="border-t hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-sm font-medium">{{ $index + 1 }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="font-mono text-sm bg-blue-100 text-blue-800 px-2 py-1 rounded">{{ $item->kode_kategori }}</span>
                                    </td>
                                    <td class="px-6 py-4">{{ $item->kategori }}</td>
                                    <td class="px-6 py-4">
                                        <span class="font-mono text-sm bg-gray-100 px-2 py-1 rounded">{{ $item->kode_barang }}</span>
                                    </td>
                                    <td class="px-6 py-4 font-medium">{{ $item->nama_barang }}</td>
                                    <td class="px-6 py-4">
                                        @php
                                            $labelJenis = $item->jenis === 'tidak_habis_pakai' ? 'Tidak Habis Pakai' : 'Habis Pakai';
                                            $warnaBg = $item->jenis === 'tidak_habis_pakai' ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800';
                                        @endphp
                                        <span class="px-2 py-1 rounded text-xs font-semibold {{ $warnaBg }}">
                                            {{ $labelJenis }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4">{{ $item->tanggal_masuk->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4">
                                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-sm font-medium">{{ $item->jumlah }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex space-x-2">
                                            <button onclick="editPersediaan({{ $item->id }})" class="text-blue-600 hover:text-blue-800">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button onclick="deletePersediaan({{ $item->id }})" class="text-red-600 hover:text-red-800">
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
                    <span class="font-medium">{{ $persediaan->firstItem() }}</span> 
                    sampai 
                    <span class="font-medium">{{ $persediaan->lastItem() }}</span> 
                    dari 
                    <span class="font-medium">{{ $persediaan->total() }}</span> 
                    hasil
                </div>
                
                <div class="flex items-center space-x-2">
                    {{-- Previous Page Link --}}
                    @if ($persediaan->onFirstPage())
                        <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                            <i class="fas fa-chevron-left mr-1"></i>Sebelumnya
                        </span>
                    @else
                        <a href="{{ $persediaan->previousPageUrl() }}" class="px-3 py-2 text-sm text-purple-600 bg-white border border-purple-200 rounded-lg hover:bg-purple-50 hover:border-purple-300 transition-colors">
                            <i class="fas fa-chevron-left mr-1"></i>Sebelumnya
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    <div class="flex items-center space-x-1">
                        @php
                            $currentPage = $persediaan->currentPage();
                            $lastPage = $persediaan->lastPage();
                            
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
                            <a href="{{ $persediaan->url(1) }}" class="px-3 py-2 text-sm text-purple-600 bg-white border border-purple-200 rounded-lg hover:bg-purple-50 hover:border-purple-300 transition-colors">
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
                                <a href="{{ $persediaan->url($page) }}" class="px-3 py-2 text-sm text-purple-600 bg-white border border-purple-200 rounded-lg hover:bg-purple-50 hover:border-purple-300 transition-colors">
                                    {{ $page }}
                                </a>
                            @endif
                        @endfor

                        {{-- Last page dengan ellipsis --}}
                        @if ($endPage < $lastPage)
                            @if ($endPage < $lastPage - 1)
                                <span class="px-2 text-sm text-gray-500">...</span>
                            @endif
                            <a href="{{ $persediaan->url($lastPage) }}" class="px-3 py-2 text-sm text-purple-600 bg-white border border-purple-200 rounded-lg hover:bg-purple-50 hover:border-purple-300 transition-colors">
                                {{ $lastPage }}
                            </a>
                        @endif
                    </div>

                    {{-- Next Page Link --}}
                    @if ($persediaan->hasMorePages())
                        <a href="{{ $persediaan->nextPageUrl() }}" class="px-3 py-2 text-sm text-purple-600 bg-white border border-purple-200 rounded-lg hover:bg-purple-50 hover:border-purple-300 transition-colors">
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
    async function editPersediaan(id) {
        // Redirect to edit page or implement edit functionality
        window.location.href = `{{ url('admin/persediaan') }}/${id}/edit`;
    }

    async function deletePersediaan(id) {
        if (confirm('Apakah Anda yakin ingin menghapus data persediaan ini?')) {
            try {
                const response = await fetch(`{{ url('admin/persediaan') }}/${id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ _method: 'DELETE' })
                });
                const data = await response.json();
                if (response.ok) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert('Gagal menghapus data persediaan.');
                }
            } catch (error) {
                alert('Terjadi kesalahan.');
                console.error(error);
            }
        }
    }
</script>
@endpush
