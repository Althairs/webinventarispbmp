@extends('layouts.layout')
@section('title', 'Dashboard Kepala BPMP')
@section('Dashboard', 'active')

@section('content')
<!-- Welcome Section -->
<div class="bg-gradient-to-r from-red-600 to-red-800 rounded-lg card-shadow p-6 mb-8 text-white">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold mb-2">Selamat Datang, {{ Auth::user()->nama_lengkap }}!</h1>
            <p class="text-red-100">Dashboard Laporan Inventaris BPMP Provinsi Gorontalo</p>
        </div>
        <div class="text-right">
            <p class="text-red-100 text-sm">{{ now()->format('d F Y') }}</p>
            <p class="text-red-100 text-sm">{{ now()->format('H:i') }} WITA</p>
        </div>
    </div>
</div>

<!-- Statistik Utama -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-lg card-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Total Aset Tetap</p>
                <p class="text-3xl font-bold text-blue-600">{{ $totalAsetTetap }}</p>
                <p class="text-sm text-gray-500">Nilai: Rp {{ number_format($totalNilaiAsetTetap, 0, ',', '.') }}</p>
            </div>
            <i class="fas fa-building text-blue-500 text-2xl"></i>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg card-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Total Persediaan</p>
                <p class="text-3xl font-bold text-green-600">{{ $totalPersediaan }}</p>
                <p class="text-sm text-gray-500">Nilai: Rp {{ number_format($totalNilaiPersediaan, 0, ',', '.') }}</p>
            </div>
            <i class="fas fa-boxes text-green-500 text-2xl"></i>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg card-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Transaksi Masuk</p>
                <p class="text-3xl font-bold text-purple-600">{{ $totalTransaksiMasuk }}</p>
                <p class="text-sm text-gray-500">Total transaksi</p>
            </div>
            <i class="fas fa-arrow-down text-purple-500 text-2xl"></i>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg card-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Transaksi Keluar</p>
                <p class="text-3xl font-bold text-orange-600">{{ $totalTransaksiKeluar }}</p>
                <p class="text-sm text-gray-500">Total transaksi</p>
            </div>
            <i class="fas fa-arrow-up text-orange-500 text-2xl"></i>
        </div>
    </div>
</div>

<!-- Laporan Cepat -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Aset Tetap Terbaru -->
    <div class="bg-white rounded-lg card-shadow">
        <div class="p-6 border-b">
            <h3 class="text-lg font-bold text-gray-800">Aset Tetap Terbaru</h3>
        </div>
        <div class="p-6">
            @if($asetTerbaru->count() > 0)
                <div class="space-y-4">
                    @foreach($asetTerbaru as $aset)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-800">{{ $aset->nama_barang }}</p>
                            <p class="text-sm text-gray-600">{{ $aset->kategori }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-800">Rp {{ number_format($aset->nilai_perolehan, 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-500">{{ $aset->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-4">Belum ada data aset tetap</p>
            @endif
        </div>
    </div>

    <!-- Persediaan Terbaru -->
    <div class="bg-white rounded-lg card-shadow">
        <div class="p-6 border-b">
            <h3 class="text-lg font-bold text-gray-800">Persediaan Terbaru</h3>
        </div>
        <div class="p-6">
            @if($persediaanTerbaru->count() > 0)
                <div class="space-y-4">
                    @foreach($persediaanTerbaru as $persediaan)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-800">{{ $persediaan->nama_barang }}</p>
                            <p class="text-sm text-gray-600">{{ $persediaan->kategori }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-800">Rp {{ number_format($persediaan->harga, 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-500">{{ $persediaan->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-4">Belum ada data persediaan</p>
            @endif
        </div>
    </div>
</div>

<!-- Mutasi Terbaru -->
<div class="bg-white rounded-lg card-shadow">
    <div class="p-6 border-b">
        <h3 class="text-lg font-bold text-gray-800">Mutasi Barang Terbaru</h3>
    </div>
    <div class="p-6">
        @if($mutasiTerbaru->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">No</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Nama Barang</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Jenis</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Dari</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Ke</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">User</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mutasiTerbaru as $index => $mutasi)
                        <tr class="border-t">
                            <td class="px-4 py-3">{{ $index + 1 }}</td>
                            <td class="px-4 py-3">{{ $mutasi->nama_barang }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded text-xs {{ $mutasi->jenis_barang === 'aset_tetap' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $mutasi->jenis_barang === 'aset_tetap' ? 'Aset Tetap' : 'Persediaan' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">{{ $mutasi->ruang_asal }}</td>
                            <td class="px-4 py-3">{{ $mutasi->ruang_tujuan }}</td>
                            <td class="px-4 py-3">{{ $mutasi->user->nama_lengkap ?? 'N/A' }}</td>
                            <td class="px-4 py-3">{{ $mutasi->created_at->format('d M Y H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500 text-center py-4">Belum ada data mutasi</p>
        @endif
    </div>
</div>
@endsection














