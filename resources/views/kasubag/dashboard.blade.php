@extends('layouts.layout')

@section('title', 'Dashboard Kasubag')

@section('content')
<div class="space-y-6">
    <!-- Alert Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
            </span>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-box text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Peminjaman Barang</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $peminjamanBarang->count() ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-clipboard-list text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Permintaan Persediaan</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $permintaanPersediaan->count() ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-car text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Peminjaman Kendaraan</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $peminjamanKendaraan->count() ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-building text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Peminjaman Aset</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $peminjamanAset->count() ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Menunggu Card -->
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
        <div class="text-center">
            <h3 class="text-2xl font-bold mb-2">Total Pengajuan Menunggu</h3>
            <p class="text-4xl font-bold">{{ $totalMenunggu ?? 0 }}</p>
            <p class="text-blue-100 mt-2">Pengajuan yang perlu ditinjau</p>
        </div>
    </div>

    <!-- Recent Requests Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Peminjaman Barang -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="fas fa-box mr-2 text-blue-600"></i>
                    Peminjaman Barang Terbaru
                </h3>
            </div>
            <div class="p-6">
                @if($peminjamanBarang->count() > 0)
                    <div class="space-y-4">
                        @foreach($peminjamanBarang->take(5) as $request)
                            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                <div class="flex items-center space-x-4">
                                    <div class="p-2 bg-blue-100 rounded-full">
                                        <i class="fas fa-box text-blue-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $request->product->nama_barang }}</p>
                                        <p class="text-sm text-gray-500">{{ $request->user->nama_lengkap }}</p>
                                        <p class="text-xs text-gray-400">
                                            Diteruskan: {{ $request->diteruskan_ke_kasubag_date ? $request->diteruskan_ke_kasubag_date->format('d/m/Y H:i') : '-' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="px-2 py-1 text-xs font-medium bg-purple-100 text-purple-800 rounded-full">
                                        Diteruskan ke Kasubag
                                    </span>
                                    <a href="{{ route('kasubag.peminjaman-barang.detail', $request->id) }}"
                                       class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                        @if($peminjamanBarang->count() > 5)
                            <div class="text-center text-sm text-gray-500">
                                Dan {{ $peminjamanBarang->count() - 5 }} pengajuan lainnya...
                            </div>
                        @endif
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-inbox text-gray-400 text-4xl mb-4"></i>
                        <p class="text-gray-500">Tidak ada pengajuan peminjaman barang</p>
                    </div>
                @endif
                <div class="mt-4 text-center">
                    <a href="{{ route('kasubag.peminjaman-barang') }}"
                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-eye mr-2"></i>
                        Lihat Semua
                    </a>
                </div>
            </div>
        </div>

        <!-- Permintaan Persediaan -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="fas fa-clipboard-list mr-2 text-green-600"></i>
                    Permintaan Persediaan Terbaru
                </h3>
            </div>
            <div class="p-6">
                @if($permintaanPersediaan->count() > 0)
                    <div class="space-y-4">
                        @foreach($permintaanPersediaan->take(5) as $request)
                            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                <div class="flex items-center space-x-4">
                                    <div class="p-2 bg-green-100 rounded-full">
                                        <i class="fas fa-clipboard-list text-green-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $request->nama_barang }}</p>
                                        <p class="text-sm text-gray-500">{{ $request->user->nama_lengkap }}</p>
                                        <p class="text-xs text-gray-400">Jumlah: {{ $request->jumlah }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="px-2 py-1 text-xs font-medium bg-purple-100 text-purple-800 rounded-full">
                                        Diteruskan ke Kasubag
                                    </span>
                                    <a href="{{ route('kasubag.permintaan-persediaan.detail', $request->id) }}"
                                       class="text-green-600 hover:text-green-800">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-clipboard-check text-gray-400 text-4xl mb-4"></i>
                        <p class="text-gray-500">Tidak ada permintaan persediaan</p>
                    </div>
                @endif
                <div class="mt-4 text-center">
                    <a href="{{ route('kasubag.permintaan-persediaan') }}"
                       class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fas fa-eye mr-2"></i>
                        Lihat Semua
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Peminjaman Kendaraan & Aset Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Peminjaman Kendaraan -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="fas fa-car mr-2 text-yellow-600"></i>
                    Peminjaman Kendaraan Terbaru
                </h3>
            </div>
            <div class="p-6">
                @if($peminjamanKendaraan->count() > 0)
                    <div class="space-y-4">
                        @foreach($peminjamanKendaraan->take(5) as $request)
                            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                <div class="flex items-center space-x-4">
                                    <div class="p-2 bg-yellow-100 rounded-full">
                                        <i class="fas fa-car text-yellow-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $request->nama_kendaraan }}</p>
                                        <p class="text-sm text-gray-500">{{ $request->user->nama_lengkap }}</p>
                                        <p class="text-xs text-gray-400">{{ $request->kategori }} - {{ $request->merek }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="px-2 py-1 text-xs font-medium bg-purple-100 text-purple-800 rounded-full">
                                        Diteruskan ke Kasubag
                                    </span>
                                    <a href="{{ route('kasubag.peminjaman-kendaraan.detail', $request->id) }}"
                                       class="text-yellow-600 hover:text-yellow-800">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-car text-gray-400 text-4xl mb-4"></i>
                        <p class="text-gray-500">Tidak ada peminjaman kendaraan</p>
                    </div>
                @endif
                <div class="mt-4 text-center">
                    <a href="{{ route('kasubag.peminjaman-kendaraan') }}"
                       class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors">
                        <i class="fas fa-eye mr-2"></i>
                        Lihat Semua
                    </a>
                </div>
            </div>
        </div>

        <!-- Peminjaman Aset -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="fas fa-building mr-2 text-purple-600"></i>
                    Peminjaman Aset Terbaru
                </h3>
            </div>
            <div class="p-6">
                @if($peminjamanAset->count() > 0)
                    <div class="space-y-4">
                        @foreach($peminjamanAset->take(5) as $request)
                            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                <div class="flex items-center space-x-4">
                                    <div class="p-2 bg-purple-100 rounded-full">
                                        <i class="fas fa-building text-purple-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $request->nama_barang }}</p>
                                        <p class="text-sm text-gray-500">{{ $request->nama_peminjam }}</p>
                                        <p class="text-xs text-gray-400">{{ $request->kategori }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="px-2 py-1 text-xs font-medium bg-purple-100 text-purple-800 rounded-full">
                                        Diteruskan ke Kasubag
                                    </span>
                                    <a href="{{ route('kasubag.peminjaman-aset.detail', $request->id) }}"
                                       class="text-purple-600 hover:text-purple-800">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-building text-gray-400 text-4xl mb-4"></i>
                        <p class="text-gray-500">Tidak ada peminjaman aset</p>
                    </div>
                @endif
                <div class="mt-4 text-center">
                    <a href="{{ route('kasubag.peminjaman-aset') }}"
                       class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                        <i class="fas fa-eye mr-2"></i>
                        Lihat Semua
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

