@extends('layouts.layout')
@section('title', 'Data Aset Tetap')
@section('Manajemen Data Barang', 'active')
@section('Data Aset Tetap', 'active')

@section('content')
    <div class="bg-white rounded-lg card-shadow">
        <div class="p-6 border-b">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-bold">Data Aset Tetap</h3>
                <div class="flex space-x-2">
                    <!-- <a href="{{ route('admin.aset-tetap.import') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                        <i class="fas fa-file-excel mr-2"></i>Import Excel
                    </a> -->
                    <a href="{{ route('admin.aset-tetap.create') }}" class="btn-primary text-white px-4 py-2 rounded-lg hover:opacity-90">
                        <i class="fas fa-plus mr-2"></i>Tambah Aset Tetap
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

            <!-- @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('import_errors') && count(session('import_errors')) > 0)
                <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4">
                    <h4 class="font-bold mb-2">Beberapa data gagal diimport:</h4>
                    <ul class="list-disc list-inside">
                        @foreach(session('import_errors') as $error)
                            <li>Baris {{ $error['row'] }}: {{ implode(', ', $error['errors']) }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif -->

            @if ($asetTetap->isEmpty())
                <div class="text-center py-8">
                    <i class="fas fa-building text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-600 mb-4">Belum ada data aset tetap.</p>
                    <a href="{{ route('admin.aset-tetap.create') }}" class="btn-primary text-white px-4 py-2 rounded-lg hover:opacity-90">
                        <i class="fas fa-plus mr-2"></i>Tambah Aset Tetap
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left">No</th>
                                <th class="px-6 py-3 text-left">Kode Barang</th>
                                <th class="px-6 py-3 text-left">NUP</th>
                                <th class="px-6 py-3 text-left">Nama Barang</th>
                                <th class="px-6 py-3 text-left">Merek</th>
                                <th class="px-6 py-3 text-left">Kategori</th>
                                <th class="px-6 py-3 text-left">Tanggal Peroleh</th>
                                <th class="px-6 py-3 text-left">Nilai Perolehan</th>
                                <th class="px-6 py-3 text-left">Kondisi</th>
                                <th class="px-6 py-3 text-left">Lokasi</th>
                                <th class="px-6 py-3 text-left">Jumlah</th>
                                <th class="px-6 py-3 text-left">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($asetTetap as $index => $item)
                                <tr class="border-t hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-sm font-medium">{{ $index + 1 }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="font-mono text-sm bg-gray-100 px-2 py-1 rounded">{{ $item->kode_barang }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="font-mono text-sm bg-blue-100 text-blue-800 px-2 py-1 rounded">{{ $item->nup }}</span>
                                    </td>
                                    <td class="px-6 py-4 font-medium">{{ $item->nama_barang }}</td>
                                    <td class="px-6 py-4">{{ $item->merek }}</td>
                                    <td class="px-6 py-4">
                                        @if($item->kategori)
                                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-sm font-medium">{{ $item->kategori }}</span>
                                        @else
                                            <span class="text-gray-400 text-sm">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">{{ $item->tanggal_peroleh->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4">Rp {{ number_format($item->nilai_perolehan, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 rounded text-sm
                                            @if ($item->kondisi === 'baik') bg-green-100 text-green-800
                                            @elseif ($item->kondisi === 'rusak_ringan') bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800 @endif">
                                            {{ ucfirst(str_replace('_', ' ', $item->kondisi)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">{{ $item->lokasi }}</td>
                                    <td class="px-6 py-4">
                                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-sm font-medium">{{ $item->jumlah }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex space-x-2">
                                            <button onclick="editAsetTetap({{ $item->id }})" class="text-blue-600 hover:text-blue-800">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button onclick="deleteAsetTetap({{ $item->id }})" class="text-red-600 hover:text-red-800">
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
                    <span class="font-medium">{{ $asetTetap->firstItem() }}</span> 
                    sampai 
                    <span class="font-medium">{{ $asetTetap->lastItem() }}</span> 
                    dari 
                    <span class="font-medium">{{ $asetTetap->total() }}</span> 
                    hasil
                </div>
                
                <div class="flex items-center space-x-2">
                    {{-- Previous Page Link --}}
                    @if ($asetTetap->onFirstPage())
                        <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                            <i class="fas fa-chevron-left mr-1"></i>Sebelumnya
                        </span>
                    @else
                        <a href="{{ $asetTetap->previousPageUrl() }}" class="px-3 py-2 text-sm text-purple-600 bg-white border border-purple-200 rounded-lg hover:bg-purple-50 hover:border-purple-300 transition-colors">
                            <i class="fas fa-chevron-left mr-1"></i>Sebelumnya
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    <div class="flex items-center space-x-1">
                        @php
                            $currentPage = $asetTetap->currentPage();
                            $lastPage = $asetTetap->lastPage();
                            
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
                            <a href="{{ $asetTetap->url(1) }}" class="px-3 py-2 text-sm text-purple-600 bg-white border border-purple-200 rounded-lg hover:bg-purple-50 hover:border-purple-300 transition-colors">
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
                                <a href="{{ $asetTetap->url($page) }}" class="px-3 py-2 text-sm text-purple-600 bg-white border border-purple-200 rounded-lg hover:bg-purple-50 hover:border-purple-300 transition-colors">
                                    {{ $page }}
                                </a>
                            @endif
                        @endfor

                        {{-- Last page dengan ellipsis --}}
                        @if ($endPage < $lastPage)
                            @if ($endPage < $lastPage - 1)
                                <span class="px-2 text-sm text-gray-500">...</span>
                            @endif
                            <a href="{{ $asetTetap->url($lastPage) }}" class="px-3 py-2 text-sm text-purple-600 bg-white border border-purple-200 rounded-lg hover:bg-purple-50 hover:border-purple-300 transition-colors">
                                {{ $lastPage }}
                            </a>
                        @endif
                    </div>

                    {{-- Next Page Link --}}
                    @if ($asetTetap->hasMorePages())
                        <a href="{{ $asetTetap->nextPageUrl() }}" class="px-3 py-2 text-sm text-purple-600 bg-white border border-purple-200 rounded-lg hover:bg-purple-50 hover:border-purple-300 transition-colors">
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
    async function editAsetTetap(id) {
        // Redirect to edit page or implement edit functionality
        window.location.href = `{{ url('admin/aset-tetap') }}/${id}/edit`;
    }

    async function deleteAsetTetap(id) {
        if (confirm('Apakah Anda yakin ingin menghapus data aset tetap ini?')) {
            try {
                const response = await fetch(`{{ url('admin/aset-tetap') }}/${id}`, {
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
                    alert('Gagal menghapus data aset tetap.');
                }
            } catch (error) {
                alert('Terjadi kesalahan.');
                console.error(error);
            }
        }
    }
</script>
@endpush
