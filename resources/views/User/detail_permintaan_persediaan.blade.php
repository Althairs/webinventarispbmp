@extends('layouts.layout')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="bg-green-100 p-2 rounded-lg">
                        <i class="fas fa-shopping-cart text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Detail Permintaan Persediaan</h1>
                        <p class="text-gray-600">ID: #{{ $request->id }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="px-3 py-1 rounded-full text-sm font-medium
                        @if($request->status === 'approved') bg-green-100 text-green-800
                        @elseif($request->status === 'rejected') bg-red-100 text-red-800
                        @else bg-yellow-100 text-yellow-800 @endif">
                        @if($request->status === 'approved') ✅ Diterima
                        @elseif($request->status === 'rejected') ❌ Ditolak
                        @else ⏳ Menunggu Review
                        @endif
                    </span>
                </div>
            </div>
        </div>

        <!-- Detail Information -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Informasi Barang -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-shopping-cart text-green-600 mr-2"></i>
                    Informasi Barang
                </h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Barang</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $request->nama_barang }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Kategori</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $request->kategori }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Merek</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $request->merek }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Jumlah</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $request->jumlah }} unit</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Satuan</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $request->satuan }}</p>
                    </div>
                </div>
            </div>

            <!-- Informasi Permintaan -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-calendar text-blue-600 mr-2"></i>
                    Informasi Permintaan
                </h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal Permintaan</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $request->created_at->translatedFormat('d F Y, H:i') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <p class="mt-1 text-sm text-gray-900">
                            @if($request->status === 'approved') Diterima
                            @elseif($request->status === 'rejected') Ditolak
                            @else Menunggu Review
                            @endif
                        </p>
                    </div>
                    @if($request->komentar)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Komentar Admin</label>
                        <p class="mt-1 text-sm text-gray-900 bg-gray-50 p-3 rounded-lg">{{ $request->komentar }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Surat Persetujuan -->
        @if($request->suratPersetujuan)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mt-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-file-pdf text-red-600 mr-2"></i>
                Surat Persetujuan
            </h3>
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="font-medium text-green-800">Surat Persetujuan Tersedia!</h4>
                        <p class="text-sm text-green-700 mt-1">
                            <span class="font-medium">Nomor Surat:</span> {{ $request->suratPersetujuan->nomor_surat ?? 'N/A' }}
                        </p>
                        @if($request->suratPersetujuan->keterangan)
                        <p class="text-sm text-green-700 mt-1">
                            <span class="font-medium">Keterangan:</span> {{ $request->suratPersetujuan->keterangan }}
                        </p>
                        @endif
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('surat-persetujuan.preview', $request->suratPersetujuan->id) }}" 
                           target="_blank" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-eye mr-2"></i>
                            Lihat
                        </a>
                        <a href="{{ route('surat-persetujuan.download', $request->suratPersetujuan->id) }}" 
                           class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                            <i class="fas fa-download mr-2"></i>
                            Download
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mt-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-file-pdf text-gray-400 mr-2"></i>
                Surat Persetujuan
            </h3>
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-center">
                <i class="fas fa-clock text-gray-400 text-2xl mb-2"></i>
                <p class="text-gray-600">Surat persetujuan belum tersedia</p>
            </div>
        </div>
        @endif

        <!-- Action Buttons -->
        <div class="flex justify-end space-x-3 mt-6">
            <a href="{{ route('user.dashboard') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali ke Dashboard
            </a>
            <a href="{{ route('user.histori-permintaan') }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-history mr-2"></i>
                Lihat Histori
            </a>
        </div>
    </div>
</div>
@endsection
