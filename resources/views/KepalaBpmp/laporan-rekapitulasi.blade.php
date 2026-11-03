@extends('layouts.layout')
@section('title', 'Laporan Rekapitulasi')
@section('Laporan Rekapitulasi', 'active')

@section('content')
<div class="bg-white rounded-lg card-shadow">
    <div class="p-6 border-b">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-bold">Laporan Rekapitulasi Inventaris</h3>
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
        <!-- Ringkasan Utama -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-blue-50 p-6 rounded-lg border border-blue-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-600 text-sm font-medium">Total Aset Tetap</p>
                        <p class="text-2xl font-bold text-blue-800">{{ $totalAsetTetap }}</p>
                        <p class="text-sm text-blue-600">Nilai: Rp {{ number_format($totalNilaiAsetTetap, 0, ',', '.') }}</p>
                    </div>
                    <i class="fas fa-building text-blue-500 text-2xl"></i>
                </div>
            </div>
            
            <div class="bg-green-50 p-6 rounded-lg border border-green-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-600 text-sm font-medium">Total Persediaan</p>
                        <p class="text-2xl font-bold text-green-800">{{ $totalPersediaan }}</p>
                        <p class="text-sm text-green-600">Nilai: Rp {{ number_format($totalNilaiPersediaan, 0, ',', '.') }}</p>
                    </div>
                    <i class="fas fa-boxes text-green-500 text-2xl"></i>
                </div>
            </div>
            
            <div class="bg-purple-50 p-6 rounded-lg border border-purple-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-600 text-sm font-medium">Total Nilai Inventaris</p>
                        <p class="text-2xl font-bold text-purple-800">Rp {{ number_format($totalNilaiAsetTetap + $totalNilaiPersediaan, 0, ',', '.') }}</p>
                        <p class="text-sm text-purple-600">Keseluruhan</p>
                    </div>
                    <i class="fas fa-calculator text-purple-500 text-2xl"></i>
                </div>
            </div>
            
            <div class="bg-orange-50 p-6 rounded-lg border border-orange-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-orange-600 text-sm font-medium">Total Item</p>
                        <p class="text-2xl font-bold text-orange-800">{{ $totalAsetTetap + $totalPersediaan }}</p>
                        <p class="text-sm text-orange-600">Barang</p>
                    </div>
                    <i class="fas fa-list text-orange-500 text-2xl"></i>
                </div>
            </div>
        </div>
        
        <!-- Grafik Kategori -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <div class="bg-white border rounded-lg p-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4">Distribusi Aset Tetap per Kategori</h4>
                @if($transaksiPerKategori->count() > 0)
                    <div class="space-y-3">
                        @foreach($transaksiPerKategori as $kategori)
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-600">{{ $kategori->kategori ?? 'Tidak Diketahui' }}</span>
                            <div class="flex items-center space-x-2">
                                <div class="w-32 bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ ($kategori->total / $transaksiPerKategori->sum('total')) * 100 }}%"></div>
                                </div>
                                <span class="text-sm font-medium text-gray-800">{{ $kategori->total }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">Belum ada data kategori</p>
                @endif
            </div>
            
            <div class="bg-white border rounded-lg p-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4">Mutasi per Bulan ({{ date('Y') }})</h4>
                @if($mutasiPerBulan->count() > 0)
                    <div class="space-y-3">
                        @foreach($mutasiPerBulan as $bulan)
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-600">{{ date('F', mktime(0, 0, 0, $bulan->bulan, 1)) }}</span>
                            <div class="flex items-center space-x-2">
                                <div class="w-32 bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-600 h-2 rounded-full" style="width: {{ ($bulan->total / $mutasiPerBulan->max('total')) * 100 }}%"></div>
                                </div>
                                <span class="text-sm font-medium text-gray-800">{{ $bulan->total }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">Belum ada data mutasi</p>
                @endif
            </div>
        </div>
        
        <!-- Tabel Detail -->
        <div class="bg-gray-50 rounded-lg p-6">
            <h4 class="text-lg font-semibold text-gray-800 mb-4">Ringkasan Detail</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h5 class="font-medium text-gray-700 mb-2">Aset Tetap</h5>
                    <ul class="space-y-1 text-sm text-gray-600">
                        <li>• Total Item: {{ $totalAsetTetap }}</li>
                        <li>• Total Nilai: Rp {{ number_format($totalNilaiAsetTetap, 0, ',', '.') }}</li>
                        <li>• Rata-rata per item: Rp {{ $totalAsetTetap > 0 ? number_format($totalNilaiAsetTetap / $totalAsetTetap, 0, ',', '.') : '0' }}</li>
                    </ul>
                </div>
                <div>
                    <h5 class="font-medium text-gray-700 mb-2">Persediaan</h5>
                    <ul class="space-y-1 text-sm text-gray-600">
                        <li>• Total Item: {{ $totalPersediaan }}</li>
                        <li>• Total Nilai: Rp {{ number_format($totalNilaiPersediaan, 0, ',', '.') }}</li>
                        <li>• Rata-rata per item: Rp {{ $totalPersediaan > 0 ? number_format($totalNilaiPersediaan / $totalPersediaan, 0, ',', '.') : '0' }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// function exportToExcel() {
//     // Implementasi export ke Excel
// //     alert('Fitur export Excel akan segera tersedia');
// }
</script>
@endsection

