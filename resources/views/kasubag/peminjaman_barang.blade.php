@extends('layouts.layout')

@section('title', 'Peminjaman Barang - Kasubag')

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

    <!-- Header Stats -->
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
        <div class="text-center">
            <h3 class="text-2xl font-bold mb-2">Total Pengajuan Peminjaman Barang</h3>
            <p class="text-4xl font-bold">{{ $requests->total() ?? 0 }}</p>
            <p class="text-blue-100 mt-2">Pengajuan yang menunggu approval kasubag</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="fas fa-box mr-2 text-blue-600"></i>
                    Daftar Pengajuan Peminjaman Barang
                </h3>
                <span class="bg-blue-100 text-blue-800 text-sm font-medium px-2.5 py-0.5 rounded-full">
                    {{ $requests->count() }} pengajuan
                </span>
            </div>
        </div>

        <div class="p-6">
            @if($requests->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Barang</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pemohon</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Direview Admin</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Diteruskan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($requests as $index => $request)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $requests->firstItem() + $index }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="p-2 bg-blue-100 rounded-full mr-3">
                                                <i class="fas fa-box text-blue-600"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $request->product->nama_barang ?? $request->nama_barang }}</div>
                                                <div class="text-sm text-gray-500">{{ $request->product->merek ?? $request->merek }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $request->user->nama_lengkap }}</div>
                                        <div class="text-sm text-gray-500">{{ $request->user->instansi }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                            {{ $request->jumlah }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $request->reviewedBy->nama_lengkap ?? 'Admin' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $request->diteruskan_ke_kasubag_date ? $request->diteruskan_ke_kasubag_date->format('d/m/Y H:i') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                         @if($request->status == 'diteruskan_ke_kasubag')
                                            <span class="px-2 py-1 text-xs font-medium bg-purple-100 text-purple-800 rounded-full">
                                                Diteruskan ke Kasubag
                                            </span>
                                        @elseif($request->status == 'approved')
                                            <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                                Diterima
                                            </span>
                                        @elseif($request->status == 'rejected')
                                            <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">
                                                Ditolak
                                            </span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">
                                                Menunggu
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('kasubag.peminjaman-barang.detail', $request->id) }}"
                                               class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($requests->hasPages())
                    <div class="mt-6">
                        {{ $requests->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-12">
                    <i class="fas fa-box text-gray-400 text-6xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada pengajuan peminjaman barang</h3>
                    <p class="text-gray-500">Belum ada pengajuan peminjaman barang yang diteruskan ke kasubag.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
