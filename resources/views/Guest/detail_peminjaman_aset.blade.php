@extends('layouts.layout')
@section('title', 'Detail Peminjaman Aset')
@section('Detail Peminjaman Aset', 'active')

@section('content')
<div class="max-w-lg  mx-auto">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-lg p-6 text-white mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold mb-2 flex items-center">
                    <i class="fas fa-building mr-3"></i>
                    Detail Peminjaman Aset
                </h2>
                <p class="text-blue-200">Informasi lengkap permintaan peminjaman aset Anda</p>
            </div>
            <div class="text-right">
                <a href="{{ route('guest.histori-peminjaman-aset') }}"
                   class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <!-- Main Content -->
        <div class="lg:col-span-2 flex justify-center">
            <div class="bg-white rounded-lg p-6 w-full max-w-lg">

>
                <!-- Status dan Info Utama -->
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-4">
                        <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                            <i class="fas fa-building text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900">{{ $request->jenis }}</h3>
                            <p class="text-xs text-gray-500">{{ $request->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3">
                        <!-- Status Badge -->
                        <span class="px-3 py-1 rounded-full text-sm font-medium
                            @if($request->status == 'approved') bg-green-100 text-green-800
                            @elseif($request->status == 'rejected') bg-red-100 text-red-800
                            @elseif($request->status == 'diteruskan_ke_kasubag') bg-yellow-100 text-yellow-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            @if($request->status == 'approved') Diterima
                            @elseif($request->status == 'rejected') Ditolak
                            @elseif($request->status == 'diteruskan_ke_kasubag') Diteruskan ke Kasubag
                            @else Menunggu
                            @endif
                        </span>

                        <!-- Surat Persetujuan Badge -->
                        @if($request->suratPersetujuan)
                        <span class="px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            <i class="fas fa-file-pdf mr-1"></i>
                            Surat Tersedia
                        </span>
                        @else
                        <span class="px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-600">
                            <i class="fas fa-clock mr-1"></i>
                            Menunggu Surat
                        </span>
                        @endif
                    </div>
                </div>

                <!-- Detail Permintaan -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                            <i class="fas fa-info-circle mr-2 text-blue-600"></i>
                            Informasi Peminjam
                        </h4>
                        <div class="space-y-3">
                            <div>
                                <span class="text-sm font-medium text-gray-600">Nama Instansi:</span>
                                <p class="text-sm text-gray-900">{{ $request->nama_instansi }}</p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-600">Nama Peminjam:</span>
                                <p class="text-sm text-gray-900">{{ $request->nama_peminjam }}</p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-600">NIP:</span>
                                <p class="text-sm text-gray-900">{{ $request->nip }}</p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-600">Kabupaten/Kota:</span>
                                <p class="text-sm text-gray-900">{{ $request->kabupaten_kota }}</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                            <i class="fas fa-calendar-alt mr-2 text-green-600"></i>
                            Detail Peminjaman
                        </h4>
                        <div class="space-y-3">
                            <div>
                                <span class="text-sm font-medium text-gray-600">Nama Gedung:</span>
                                <p class="text-sm text-gray-900">{{ $request->jenis }}</p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-600">Tanggal Pinjaman:</span>
                                <p class="text-sm text-gray-900">{{ $request->tanggal_pinjaman->format('d M Y') }}</p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-600">Jam:</span>
                                <p class="text-sm text-gray-900">{{ $request->jam_mulai->format('H:i') }} - {{ $request->jam_selesai->format('H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Surat Uploaded -->
                @if($request->surat_path)
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                        <i class="fas fa-file-upload mr-2 text-blue-600"></i>
                        Surat yang Diupload
                    </h4>
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center">
                                <i class="fas fa-file-alt text-blue-600 mr-2"></i>
                                <span class="font-medium text-blue-800">{{ basename($request->surat_path) }}</span>
                            </div>
                            <span class="text-xs text-blue-600">{{ $request->created_at->format('d M Y') }}</span>
                        </div>

                        <p class="text-sm text-blue-700 mb-3">
                            <span class="font-medium">Status:</span> Surat yang Anda upload saat mengajukan permintaan
                        </p>

                        <div class="flex space-x-2">
                            <a href="{{ route('guest.peminjaman-aset.view-surat', $request->id) }}"
                               target="_blank"
                               class="px-3 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 text-sm">
                                <i class="fas fa-eye mr-1"></i>Lihat Surat
                            </a>
                            <a href="{{ route('guest.peminjaman-aset.download-surat', $request->id) }}"
                               class="px-3 py-2 bg-purple-500 text-white rounded hover:bg-purple-600 text-sm">
                                <i class="fas fa-download mr-1"></i>Download Surat
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Surat Persetujuan -->
                @if($request->suratPersetujuan)
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                        <i class="fas fa-file-alt mr-2 text-green-600"></i>
                        Surat Persetujuan
                    </h4>
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center">
                                <i class="fas fa-file-pdf text-red-600 mr-2"></i>
                                <span class="font-medium text-green-800">{{ $request->suratPersetujuan->file_name }}</span>
                            </div>
                            <span class="text-xs text-green-600">{{ $request->suratPersetujuan->created_at->format('d M Y') }}</span>
                        </div>

                        @if($request->suratPersetujuan->nomor_surat)
                        <p class="text-sm text-green-700 mb-2">
                            <span class="font-medium">Nomor Surat:</span> {{ $request->suratPersetujuan->nomor_surat }}
                        </p>
                        @endif

                        @if($request->suratPersetujuan->keterangan)
                        <p class="text-sm text-green-700 mb-3">
                            <span class="font-medium">Keterangan:</span> {{ $request->suratPersetujuan->keterangan }}
                        </p>
                        @endif

                        <div class="flex space-x-2">
                            <a href="{{ route('surat-persetujuan.download', $request->suratPersetujuan->id) }}"
                               class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-download mr-2"></i>
                                Download
                            </a>
                            <a href="{{ route('surat-persetujuan.preview', $request->suratPersetujuan->id) }}"
                               target="_blank"
                               class="inline-flex items-center px-3 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
                                <i class="fas fa-eye mr-2"></i>
                                Lihat
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Komentar Admin -->
                @if($request->komentar)
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                        <i class="fas fa-comment mr-2 text-yellow-600"></i>
                        Komentar Admin
                    </h4>
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <p class="text-sm text-yellow-800">{{ $request->komentar }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg card-shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-info-circle mr-2 text-blue-600"></i>Informasi
                </h3>

                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-600">Status:</span>
                        @if($request->status == 'approved')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check mr-1"></i>Diterima
                            </span>
                        @elseif($request->status == 'rejected')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <i class="fas fa-times mr-1"></i>Ditolak
                            </span>
                        @elseif($request->status == 'diteruskan_ke_kasubag')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <i class="fas fa-clock mr-1"></i>Diteruskan ke Kasubag
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                <i class="fas fa-clock mr-1"></i>Menunggu
                            </span>
                        @endif
                    </div>

                    <div>
                        <span class="text-sm font-medium text-gray-600">Dibuat:</span>
                        <p class="text-sm text-gray-500">{{ $request->created_at->format('d F Y, H:i') }}</p>
                    </div>

                    @if($request->tanggal_approval)
                        <div>
                            <span class="text-sm font-medium text-gray-600">Tanggal Approval:</span>
                            <p class="text-sm text-gray-500">{{ $request->tanggal_approval->format('d F Y, H:i') }}</p>
                        </div>
                    @endif

                    <div>
                        <span class="text-sm font-medium text-gray-600">ID Permintaan:</span>
                        <p class="text-sm text-gray-500">#{{ $request->id }}</p>
                    </div>
                </div>

                @if($request->status == 'approved')
                <div class="mt-6">
                    <a href="{{ route('guest.surat.peminjaman-aset', $request->id) }}"
                       class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors text-center block">
                        <i class="fas fa-file-pdf mr-2"></i>
                        Generate Surat
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
