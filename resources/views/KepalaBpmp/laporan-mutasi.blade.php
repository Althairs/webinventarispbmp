@extends('layouts.layout')
@section('title', 'Laporan Mutasi Barang')
@section('Laporan Mutasi', 'active')

@section('content')
<div class="bg-white rounded-lg card-shadow">
    <div class="p-6 border-b">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-bold">Laporan Mutasi Barang</h3>
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
        @if($mutasi->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">No</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Tanggal Mutasi</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Kode Barang</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Nama Barang</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">NUP</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Lokasi Awal</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Lokasi Akhir</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mutasi as $index => $item)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $mutasi->firstItem() + $index }}</td>
                            <td class="px-4 py-3">{{ \Carbon\Carbon::parse($item->tanggal_mutasi)->format('d/m/Y') }}</td>
                            <td class="px-4 py-3">
                                <span class="font-mono text-sm bg-gray-100 px-2 py-1 rounded">{{ $item->kode_barang }}</span>
                            </td>
                            <td class="px-4 py-3 font-medium">{{ $item->nama_barang }}</td>
                            <td class="px-4 py-3">
                                <span class="font-mono text-sm bg-blue-100 text-blue-800 px-2 py-1 rounded">{{ $item->nup ?? '-' }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-sm">{{ $item->lokasi_awal }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-sm">{{ $item->lokasi_akhir }}</span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">
                                {{ $item->keterangan ? \Illuminate\Support\Str::limit($item->keterangan, 40) : '-' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="mt-6">
                {{ $mutasi->links() }}
            </div>
        @else
            <div class="text-center py-8">
                <i class="fas fa-exchange-alt text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-600">Belum ada data mutasi barang</p>
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








