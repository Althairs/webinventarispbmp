@extends('layouts.layout')
@section('title', 'Admin Dashboard')
@section('dashboard', 'active')

@section('content')
<!-- Welcome Section -->
<div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-lg card-shadow p-6 mb-8 text-white">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold mb-2">Selamat Datang, {{ Auth::user()->name }}!</h1>
            <p class="text-blue-100">Dashboard SIPANDU BPMP Provinsi Gorontalo</p>
        </div>
        <div class="text-right">
            <p class="text-blue-100 text-sm">{{ now()->format('d F Y') }}</p>
            <p class="text-blue-100 text-sm">{{ now()->format('H:i') }} WITA</p>
        </div>
    </div>
</div>

<!-- Statistik Utama -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-lg card-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Total Persediaan</p>
                <p class="text-3xl font-bold text-blue-600">{{ $stats['totalPersediaan'] }}</p>
            </div>
            <i class="fas fa-boxes text-blue-500 text-2xl"></i>
        </div>
    </div>
    <div class="bg-white p-6 rounded-lg card-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Total Aset Tetap</p>
                <p class="text-3xl font-bold text-green-600">{{ $stats['totalAsetTetap'] }}</p>
            </div>
            <i class="fas fa-building text-green-500 text-2xl"></i>
        </div>
    </div>
    <div class="bg-white p-6 rounded-lg card-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Request Menunggu</p>
                <p class="text-3xl font-bold text-yellow-600">{{ $stats['MenungguRequests'] }}</p>
            </div>
            <i class="fas fa-clock text-yellow-500 text-2xl"></i>
        </div>
    </div>
    <div class="bg-white p-6 rounded-lg card-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Total User</p>
                <p class="text-3xl font-bold text-purple-600">{{ $stats['totalUsers'] }}</p>
            </div>
            <i class="fas fa-users text-purple-500 text-2xl"></i>
        </div>
    </div>
</div>

<!-- Statistik Transaksi -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-lg card-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Transaksi Masuk Hari Ini</p>
                <p class="text-2xl font-bold text-green-600">{{ $stats['transaksiMasukHariIni'] }}</p>
            </div>
            <i class="fas fa-arrow-down text-green-500 text-xl"></i>
        </div>
    </div>
    <div class="bg-white p-6 rounded-lg card-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Transaksi Keluar Hari Ini</p>
                <p class="text-2xl font-bold text-red-600">{{ $stats['transaksiKeluarHariIni'] }}</p>
            </div>
            <i class="fas fa-arrow-up text-red-500 text-xl"></i>
        </div>
    </div>
    <div class="bg-white p-6 rounded-lg card-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Mutasi Hari Ini</p>
                <p class="text-2xl font-bold text-blue-600">{{ $stats['mutasiHariIni'] }}</p>
            </div>
            <i class="fas fa-exchange-alt text-blue-500 text-xl"></i>
        </div>
    </div>
    <div class="bg-white p-6 rounded-lg card-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Total Pengajuan</p>
                <p class="text-2xl font-bold text-orange-600">{{ $stats['totalPengajuan'] }}</p>
            </div>
            <i class="fas fa-clipboard-list text-orange-500 text-xl"></i>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Aktivitas Terbaru -->
    <div class="bg-white rounded-lg card-shadow p-6">
        <h3 class="text-lg font-bold mb-4 flex items-center">
            <i class="fas fa-history text-blue-500 mr-2"></i>
            Aktivitas Terbaru
        </h3>
        <div class="space-y-4">
            @if($stats['recentActivities']->count() > 0)
                @foreach($stats['recentActivities'] as $activity)
                <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas {{ $activity['icon'] }} text-blue-600 text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-medium text-sm">{{ $activity['description'] }}</p>
                        <p class="text-xs text-gray-500">{{ $activity['time'] }}</p>
                    </div>
                </div>
                @endforeach
            @else
                <div class="text-center py-4">
                    <i class="fas fa-clock text-4xl text-gray-300 mb-2"></i>
                    <p class="text-gray-500">Belum ada aktivitas terbaru</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg card-shadow p-6">
        <h3 class="text-lg font-bold mb-4 flex items-center">
            <i class="fas fa-bolt text-yellow-500 mr-2"></i>
            Quick Actions
        </h3>
        <div class="grid grid-cols-2 gap-4">
            <a href="{{ route('admin.transaksi-masuk.index') }}" class="p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                <div class="text-center">
                    <i class="fas fa-arrow-down text-green-600 text-2xl mb-2"></i>
                    <p class="font-medium text-sm">Transaksi Masuk</p>
                </div>
            </a>
            <a href="{{ route('admin.transaksi-keluar.index') }}" class="p-4 bg-red-50 rounded-lg hover:bg-red-100 transition-colors">
                <div class="text-center">
                    <i class="fas fa-arrow-up text-red-600 text-2xl mb-2"></i>
                    <p class="font-medium text-sm">Transaksi Keluar</p>
                </div>
            </a>
            <a href="{{ route('admin.mutasi-barang.index') }}" class="p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                <div class="text-center">
                    <i class="fas fa-exchange-alt text-blue-600 text-2xl mb-2"></i>
                    <p class="font-medium text-sm">Mutasi Barang</p>
                </div>
            </a>
            <a href="{{ route('admin.peminjaman-barang') }}" class="p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                <div class="text-center">
                    <i class="fas fa-hand-holding text-purple-600 text-2xl mb-2"></i>
                    <p class="font-medium text-sm">Peminjaman</p>
                </div>
            </a>
        </div>
    </div>
</div>

<!-- Fitur Sistem -->
<div class="bg-white rounded-lg card-shadow p-6">
    <h3 class="text-lg font-bold mb-4 flex items-center">
        <i class="fas fa-cogs text-gray-500 mr-2"></i>
        Fitur SIPANDU
    </h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <div class="flex items-center space-x-3 p-4 bg-blue-50 rounded-lg">
            <i class="fas fa-boxes text-blue-500 text-xl"></i>
            <div>
                <p class="font-medium">Manajemen Data Barang</p>
                <p class="text-sm text-gray-600">Kelola persediaan & aset tetap</p>
            </div>
        </div>
        <div class="flex items-center space-x-3 p-4 bg-green-50 rounded-lg">
            <i class="fas fa-arrow-down text-green-500 text-xl"></i>
            <div>
                <p class="font-medium">Transaksi Barang Masuk</p>
                <p class="text-sm text-gray-600">Input barang masuk & update stok</p>
            </div>
        </div>
        <div class="flex items-center space-x-3 p-4 bg-red-50 rounded-lg">
            <i class="fas fa-arrow-up text-red-500 text-xl"></i>
            <div>
                <p class="font-medium">Transaksi Barang Keluar</p>
                <p class="text-sm text-gray-600">Input barang keluar & kurangi stok</p>
            </div>
        </div>
        <div class="flex items-center space-x-3 p-4 bg-blue-50 rounded-lg">
            <i class="fas fa-exchange-alt text-blue-500 text-xl"></i>
            <div>
                <p class="font-medium">Mutasi Barang</p>
                <p class="text-sm text-gray-600">Pantau perpindahan lokasi aset</p>
            </div>
        </div>
        <div class="flex items-center space-x-3 p-4 bg-purple-50 rounded-lg">
            <i class="fas fa-clipboard-list text-purple-500 text-xl"></i>
            <div>
                <p class="font-medium">Manajemen Pengajuan</p>
                <p class="text-sm text-gray-600">Kelola peminjaman & permintaan</p>
            </div>
        </div>
        <div class="flex items-center space-x-3 p-4 bg-orange-50 rounded-lg">
            <i class="fas fa-chart-bar text-orange-500 text-xl"></i>
            <div>
                <p class="font-medium">Laporan & Analitik</p>
                <p class="text-sm text-gray-600">Lihat laporan & statistik</p>
            </div>
        </div>
    </div>
</div>
@endsection
