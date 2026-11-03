@extends('layouts.layout')

@section('title', 'Peminjaman Kendaraan - Kasubag')

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
    <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-lg shadow-lg p-6 text-white">
        <div class="text-center">
            <h3 class="text-2xl font-bold mb-2">Total Pengajuan Peminjaman Kendaraan</h3>
            <p class="text-4xl font-bold">{{ $requests->total() ?? 0 }}</p>
            <p class="text-yellow-100 mt-2">Pengajuan yang menunggu approval kasubag</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="fas fa-car mr-2 text-yellow-600"></i>
                    Daftar Pengajuan Peminjaman Kendaraan
                </h3>
                <span class="bg-yellow-100 text-yellow-800 text-sm font-medium px-2.5 py-0.5 rounded-full">
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
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kendaraan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Merek</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pemohon</th>
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
                                            <div class="p-2 bg-yellow-100 rounded-full mr-3">
                                                <i class="fas fa-car text-yellow-600"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $request->nama_kendaraan }}</div>
                                                <div class="text-sm text-gray-500">No. Polisi: {{ $request->nomor_polisi ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                            {{ $request->kategori ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $request->merek ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $request->user->nama_lengkap }}</div>
                                        <div class="text-sm text-gray-500">{{ $request->user->instansi }}</div>
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
                                            <a href="{{ route('kasubag.peminjaman-kendaraan.detail', $request->id) }}"
                                               class="text-yellow-600 hover:text-yellow-900">
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
                    <i class="fas fa-car text-gray-400 text-6xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada pengajuan peminjaman kendaraan</h3>
                    <p class="text-gray-500">Belum ada pengajuan peminjaman kendaraan yang diteruskan ke kasubag.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
