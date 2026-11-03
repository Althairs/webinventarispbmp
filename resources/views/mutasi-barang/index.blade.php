@extends('layouts.layout')
@section('title', 'Mutasi Barang')
@section('Mutasi Barang', 'active')

@section('content')
    <!-- Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-lg card-shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Total Mutasi</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $totalMutasi }}</p>
                </div>
                <i class="fas fa-exchange-alt text-blue-500 text-2xl"></i>
            </div>
        </div>
        <div class="bg-white rounded-lg card-shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Total Aset Tetap</p>
                    <p class="text-3xl font-bold text-green-600">{{ $totalAsetTetap }}</p>
                </div>
                <i class="fas fa-building text-green-500 text-2xl"></i>
            </div>
        </div>
        <div class="bg-white rounded-lg card-shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Mutasi Hari Ini</p>
                    <p class="text-3xl font-bold text-purple-600">{{ $totalMutasiHariIni }}</p>
                </div>
                <i class="fas fa-calendar-day text-purple-500 text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Menu Mutasi Barang -->
        <div class="bg-white rounded-lg card-shadow">
            <div class="p-6 border-b">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold">Mutasi Barang</h3>
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.mutasi-barang.data') }}" class="btn-secondary text-white px-4 py-2 rounded-lg hover:opacity-90">
                            <i class="fas fa-eye mr-2"></i>Lihat Data
                        </a>
                        <a href="{{ route('admin.mutasi-barang.create') }}" class="btn-primary text-white px-4 py-2 rounded-lg hover:opacity-90">
                            <i class="fas fa-plus mr-2"></i>Tambah Mutasi
                        </a>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <h4 class="font-semibold mb-4">Histori Mutasi Terbaru</h4>
                @if($historiMutasi->isEmpty())
                    <div class="text-center py-4">
                        <i class="fas fa-exchange-alt text-4xl text-gray-300 mb-2"></i>
                        <p class="text-gray-500">Belum ada mutasi barang</p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($historiMutasi as $item)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex-1">
                                <p class="font-medium">{{ $item->nama_barang }}</p>
                                <p class="text-sm text-gray-600">{{ $item->kode_barang }} • NUP: {{ $item->nup }}</p>
                                <p class="text-xs text-gray-500">{{ $item->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-600">{{ $item->lokasi_awal }}</p>
                                <i class="fas fa-arrow-right text-blue-500 mx-2"></i>
                                <p class="text-sm font-medium text-green-600">{{ $item->lokasi_akhir }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="mt-4 text-center">
                        <a href="{{ route('admin.mutasi-barang.histori') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                            <i class="fas fa-history mr-1"></i>Lihat Semua Histori
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Informasi Sistem -->
        <div class="bg-white rounded-lg card-shadow">
            <div class="p-6 border-b">
                <h3 class="text-lg font-bold">Informasi Sistem</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-info-circle text-blue-600"></i>
                        </div>
                        <div>
                            <p class="font-medium">Fitur Mutasi Barang</p>
                            <p class="text-sm text-gray-600">Pantau perpindahan lokasi aset tetap</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-600"></i>
                        </div>
                        <div>
                            <p class="font-medium">Update Otomatis</p>
                            <p class="text-sm text-gray-600">Lokasi aset akan terupdate otomatis</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-history text-purple-600"></i>
                        </div>
                        <div>
                            <p class="font-medium">Histori Lengkap</p>
                            <p class="text-sm text-gray-600">Semua mutasi tercatat dengan detail</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
