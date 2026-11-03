@extends('layouts.layout')
@section('title', 'Laporan Transaksi Masuk')
@section('Laporan Transaksi Masuk', 'active')

@section('content')
<div class="bg-white rounded-lg card-shadow">
    <div class="p-6 border-b">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-bold">Laporan Transaksi Barang Masuk</h3>
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
        <!-- Aset Tetap -->
        <div class="mb-8">
            <h4 class="text-lg font-semibold text-gray-800 mb-4">Transaksi Masuk Aset Tetap</h4>
            @if($transaksiAset->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">No</th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Tanggal</th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Kode Barang</th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Nama Barang</th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Kategori</th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Jumlah Masuk</th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Harga</th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transaksiAset as $index => $transaksi)
                            <tr class="border-t hover:bg-gray-50">
                                <td class="px-4 py-3">{{ $index + 1 }}</td>
                                <td class="px-4 py-3">{{ \Carbon\Carbon::parse($transaksi->tanggal_input)->format('d/m/Y') }}</td>
                                <td class="px-4 py-3 font-medium">{{ $transaksi->kode_barang }}</td>
                                <td class="px-4 py-3">{{ $transaksi->nama_barang }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 rounded text-xs bg-blue-100 text-blue-800">
                                        {{ $transaksi->kategori }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">{{ number_format($transaksi->jumlah_masuk, 0, ',', '.') }}</td>
                                <td class="px-4 py-3">Rp {{ number_format($transaksi->harga ?? 0, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 font-medium">Rp {{ number_format(($transaksi->jumlah_masuk ?? 0) * ($transaksi->harga ?? 0), 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500 text-center py-4">Belum ada transaksi masuk aset tetap</p>
            @endif
        </div>
        
        <!-- Persediaan -->
        <div>
            <h4 class="text-lg font-semibold text-gray-800 mb-4">Transaksi Masuk Persediaan</h4>
            @if($transaksiPersediaan->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">No</th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Tanggal</th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Kode Barang</th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Nama Barang</th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Kategori</th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Jumlah Masuk</th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Harga</th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transaksiPersediaan as $index => $transaksi)
                            <tr class="border-t hover:bg-gray-50">
                                <td class="px-4 py-3">{{ $index + 1 }}</td>
                                <td class="px-4 py-3">{{ \Carbon\Carbon::parse($transaksi->tanggal_input)->format('d/m/Y') }}</td>
                                <td class="px-4 py-3 font-medium">{{ $transaksi->kode_barang }}</td>
                                <td class="px-4 py-3">{{ $transaksi->nama_barang }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 rounded text-xs bg-green-100 text-green-800">
                                        {{ $transaksi->kategori }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">{{ number_format($transaksi->jumlah_masuk, 0, ',', '.') }}</td>
                                <td class="px-4 py-3">Rp {{ number_format($transaksi->harga ?? 0, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 font-medium">Rp {{ number_format(($transaksi->jumlah_masuk ?? 0) * ($transaksi->harga ?? 0), 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500 text-center py-4">Belum ada transaksi masuk persediaan</p>
            @endif
        </div>
    </div>
</div>

<script>
// function exportToExcel() {
//     // Implementasi export ke Excel
//     alert('Fitur export Excel akan segera tersedia');
// }
</script>
@endsection








