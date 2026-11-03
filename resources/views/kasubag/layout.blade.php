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
                    <p class="text-2xl font-semibold text-gray-900">{{ $totalMenunggu ?? 0 }}</p>
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

    <!-- Recent Requests -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Pengajuan Terbaru</h3>
        </div>
        <div class="p-6">
            @if(isset($peminjamanBarang) && $peminjamanBarang->count() > 0)
                <div class="space-y-4">
                    @foreach($peminjamanBarang->take(5) as $request)
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                            <div class="flex items-center space-x-4">
                                <div class="p-2 bg-blue-100 rounded-full">
                                    <i class="fas fa-box text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $request->user->nama_lengkap }}</p>
                                    <p class="text-sm text-gray-500">{{ $request->product->nama_barang ?? 'N/A' }}</p>
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
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-inbox text-gray-400 text-4xl mb-4"></i>
                    <p class="text-gray-500">Belum ada pengajuan yang perlu ditinjau</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
