@extends('layouts.layout')
@section('title', 'Laporan Persediaan')
@section('Laporan Persediaan', 'active')

@section('content')
<div class="bg-white rounded-lg card-shadow">
    <div class="p-6 border-b">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-bold">Laporan Data Persediaan</h3>
            <div class="flex space-x-2">
                <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                    <i class="fas fa-print mr-2"></i>Cetak
                </button>
                <!-- <button onclick="exportToExcel()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                    <i class="fas fa-file-excel mr-2"></i>Export Excel
                </button> -->
            </div>
        </div>
    </div>
    
    <div class="p-6">
        @if($persediaan->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">No</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Kode Barang</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Nama Barang</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Kategori</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Harga</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Stok</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Kondisi</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Tanggal Masuk</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($persediaan as $index => $item)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $persediaan->firstItem() + $index }}</td>
                            <td class="px-4 py-3 font-medium">{{ $item->kode_barang }}</td>
                            <td class="px-4 py-3">{{ $item->nama_barang }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded text-xs bg-green-100 text-green-800">
                                    {{ $item->kategori }}
                                </span>
                            </td>
                            <td class="px-4 py-3 font-medium">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded text-xs {{ $item->stok > 10 ? 'bg-green-100 text-green-800' : ($item->stok > 5 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ $item->stok }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded text-xs {{ isset($item->kondisi) && $item->kondisi === 'baik' ? 'bg-green-100 text-green-800' : (isset($item->kondisi) && $item->kondisi === 'rusak_ringan' ? 'bg-yellow-100 text-yellow-800' : (isset($item->kondisi) && $item->kondisi === 'rusak_berat' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                                    {{ isset($item->kondisi) ? ucfirst(str_replace('_', ' ', $item->kondisi)) : '-' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">{{ $item->created_at->format('d M Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="mt-6">
                {{ $persediaan->links() }}
            </div>
        @else
            <div class="text-center py-8">
                <i class="fas fa-boxes text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-600">Belum ada data persediaan</p>
            </div>
        @endif
    </div>
</div>

<script>
function exportToExcel() {
//     // Implementasi export ke Excel
//     alert('Fitur export Excel akan segera tersedia');
// }
</script>
@endsection








