@extends('layouts.layout')
@section('title', 'Transaksi Barang Keluar')
@section('Transaksi Barang Keluar', 'active')

@section('content')
    <!-- Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-lg card-shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Total Transaksi Persediaan</p>
                    <p class="text-3xl font-bold text-red-600">{{ $totalTransaksiPersediaan }}</p>
                </div>
                <i class="fas fa-boxes text-red-500 text-2xl"></i>
            </div>
        </div>
        <div class="bg-white rounded-lg card-shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Total Transaksi Aset Tetap</p>
                    <p class="text-3xl font-bold text-orange-600">{{ $totalTransaksiAsetTetap }}</p>
                </div>
                <i class="fas fa-building text-orange-500 text-2xl"></i>
            </div>
        </div>
        <div class="bg-white rounded-lg card-shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Transaksi Hari Ini</p>
                    <p class="text-3xl font-bold text-purple-600">{{ $totalTransaksiHariIni }}</p>
                </div>
                <i class="fas fa-calendar-day text-purple-500 text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Menu Transaksi Persediaan -->
        <div class="bg-white rounded-lg card-shadow">
            <div class="p-6 border-b">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold">Transaksi Persediaan</h3>
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.transaksi-keluar.persediaan') }}" class="btn-secondary text-white px-4 py-2 rounded-lg hover:opacity-90">
                            <i class="fas fa-eye mr-2"></i>Lihat Data
                        </a>
                        <a href="{{ route('admin.transaksi-keluar.create-persediaan') }}" class="btn-primary text-white px-4 py-2 rounded-lg hover:opacity-90">
                            <i class="fas fa-plus mr-2"></i>Tambah Data
                        </a>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <h4 class="font-semibold mb-4">Histori Transaksi Terbaru</h4>
                @if($historiPersediaan->isEmpty())
                    <div class="text-center py-4">
                        <i class="fas fa-boxes text-4xl text-gray-300 mb-2"></i>
                        <p class="text-gray-500">Belum ada transaksi persediaan</p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($historiPersediaan as $item)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex-1">
                                <p class="font-medium">{{ $item->nama_barang }}</p>
                                <p class="text-sm text-gray-600">{{ $item->kode_barang }} • {{ $item->kategori }}</p>
                                <p class="text-xs text-gray-500">{{ $item->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-red-600">-{{ $item->jumlah_keluar }}</p>
                                <p class="text-sm text-gray-600">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="mt-4 text-center">
                        <a href="{{ route('admin.transaksi-keluar.histori-persediaan') }}" class="text-red-600 hover:text-red-800 text-sm">
                            <i class="fas fa-history mr-1"></i>Lihat Semua Histori
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Menu Transaksi Aset Tetap -->
        <div class="bg-white rounded-lg card-shadow">
            <div class="p-6 border-b">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold">Transaksi Aset Tetap</h3>
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.transaksi-keluar.aset-tetap') }}" class="btn-secondary text-white px-4 py-2 rounded-lg hover:opacity-90">
                            <i class="fas fa-eye mr-2"></i>Lihat Data
                        </a>
                        <a href="{{ route('admin.transaksi-keluar.create-aset-tetap') }}" class="btn-primary text-white px-4 py-2 rounded-lg hover:opacity-90">
                            <i class="fas fa-plus mr-2"></i>Tambah Data
                        </a>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <h4 class="font-semibold mb-4">Histori Transaksi Terbaru</h4>
                @if($historiAsetTetap->isEmpty())
                    <div class="text-center py-4">
                        <i class="fas fa-building text-4xl text-gray-300 mb-2"></i>
                        <p class="text-gray-500">Belum ada transaksi aset tetap</p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($historiAsetTetap as $item)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex-1">
                                <p class="font-medium">{{ $item->nama_barang }}</p>
                                <p class="text-sm text-gray-600">{{ $item->kode_barang }} • {{ $item->kategori }}</p>
                                <p class="text-xs text-gray-500">{{ $item->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-red-600">-{{ $item->jumlah_keluar }}</p>
                                <p class="text-sm text-gray-600">Aset Tetap</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="mt-4 text-center">
                        <a href="{{ route('admin.transaksi-keluar.histori-aset-tetap') }}" class="text-orange-600 hover:text-orange-800 text-sm">
                            <i class="fas fa-history mr-1"></i>Lihat Semua Histori
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
