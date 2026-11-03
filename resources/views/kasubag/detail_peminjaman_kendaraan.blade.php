@extends('layouts.layout')

@section('title', 'Detail Peminjaman Kendaraan - Kasubag')

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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200 bg-yellow-600 text-white rounded-t-lg">
                    <h3 class="text-lg font-medium flex items-center">
                        <i class="fas fa-car mr-2"></i>
                        Detail Peminjaman Kendaraan
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h6 class="text-lg font-medium text-gray-900 mb-4">Informasi Kendaraan</h6>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-700">Nama Kendaraan:</span>
                                    <span class="text-gray-900">{{ $requestData->nama_kendaraan ?? 'N/A' }}</span>
                                </div>
                                @if($requestData->kategori)
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-700">Kategori:</span>
                                    <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">{{ $requestData->kategori }}</span>
                                </div>
                                @endif
                                @if($requestData->merek)
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-700">Merek:</span>
                                    <span class="text-gray-900">{{ $requestData->merek }}</span>
                                </div>
                                @endif
                                @if($requestData->nomor_polisi)
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-700">Nomor Polisi:</span>
                                    <span class="text-gray-900">{{ $requestData->nomor_polisi }}</span>
                                </div>
                                @endif
                                @if($requestData->warna)
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-700">Warna:</span>
                                    <span class="text-gray-900">{{ $requestData->warna }}</span>
                                </div>
                                @endif
                                @if($requestData->tahun)
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-700">Tahun:</span>
                                    <span class="text-gray-900">{{ $requestData->tahun }}</span>
                                </div>
                                @endif
                                @if($requestData->jumlah)
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-700">Jumlah:</span>
                                    <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">{{ $requestData->jumlah }}</span>
                                </div>
                                @endif
                                @if($requestData->deskripsi_peruntukan)
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-700">Deskripsi Peruntukan:</span>
                                    <span class="text-gray-900">{{ $requestData->deskripsi_peruntukan }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div>
                            <h6 class="text-lg font-medium text-gray-900 mb-4">Informasi Pemohon</h6>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-700">Nama Lengkap:</span>
                                    <span class="text-gray-900">{{ $requestData->user->nama_lengkap ?? 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-700">Instansi:</span>
                                    <span class="text-gray-900">{{ $requestData->user->instansi ?? 'N/A' }}</span>
                                </div>
                                @if($requestData->user && $requestData->user->jabatan)
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-700">Jabatan:</span>
                                    <span class="text-gray-900">{{ $requestData->user->jabatan }}</span>
                                </div>
                                @endif
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-700">NIP:</span>
                                    <span class="text-gray-900">{{ $requestData->user->nip ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-6">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h6 class="text-lg font-medium text-gray-900 mb-4">Detail Peminjaman</h6>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-700">Tanggal Peminjaman:</span>
                                    <span class="text-gray-900">
                                        @if($requestData->tanggal_peminjaman)
                                            @if(is_string($requestData->tanggal_peminjaman))
                                                {{ \Carbon\Carbon::parse($requestData->tanggal_peminjaman)->translatedFormat('d F Y') }}
                                            @else
                                                {{ $requestData->tanggal_peminjaman->translatedFormat('d F Y') }}
                                            @endif
                                        @else
                                            N/A
                                        @endif
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-700">Tanggal Pengembalian:</span>
                                    <span class="text-gray-900">
                                        @if($requestData->tanggal_pengembalian)
                                            @if(is_string($requestData->tanggal_pengembalian))
                                                {{ \Carbon\Carbon::parse($requestData->tanggal_pengembalian)->translatedFormat('d F Y') }}
                                            @else
                                                {{ $requestData->tanggal_pengembalian->translatedFormat('d F Y') }}
                                            @endif
                                        @else
                                            N/A
                                        @endif
                                    </span>
                                </div>
                                @if($requestData->deskripsi_peruntukan)
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-700">Deskripsi Peruntukan:</span>
                                    <span class="text-gray-900">{{ $requestData->deskripsi_peruntukan }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div>
                            <h6 class="text-lg font-medium text-gray-900 mb-4">Proses Review</h6>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-700">Direview oleh:</span>
                                    <span class="text-gray-900">{{ $requestData->reviewedBy->nama_lengkap ?? 'Admin' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-700">Tanggal Diteruskan:</span>
                                    <span class="text-gray-900">
                                        @if($requestData->diteruskan_ke_kasubag_date)
                                            @if(is_string($requestData->diteruskan_ke_kasubag_date))
                                                {{ \Carbon\Carbon::parse($requestData->diteruskan_ke_kasubag_date)->translatedFormat('d F Y H:i') }}
                                            @else
                                                {{ $requestData->diteruskan_ke_kasubag_date->translatedFormat('d F Y H:i') }}
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-700">Status Saat Ini:</span>
                                    @if($requestData->status == 'diteruskan_ke_kasubag')
                                        <span class="px-2 py-1 text-xs font-medium bg-purple-100 text-purple-800 rounded-full">Diteruskan ke Kasubag</span>
                                    @elseif($requestData->status == 'approved')
                                        <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Diterima</span>
                                    @elseif($requestData->status == 'rejected')
                                        <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">Ditolak</span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">{{ ucfirst(str_replace('_', ' ', $requestData->status)) }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <h6 class="text-lg font-medium text-gray-900 mb-4">Timeline</h6>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-700">Tanggal Pengajuan:</span>
                                    <span class="text-gray-900">{{ $requestData->created_at->translatedFormat('d F Y H:i') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-700">Terakhir Diperbarui:</span>
                                    <span class="text-gray-900">{{ $requestData->updated_at->translatedFormat('d F Y H:i') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($requestData->komentar)
                        <hr class="my-6">
                        <div>
                            <h6 class="text-lg font-medium text-gray-900 mb-4">Komentar dari Admin</h6>
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex items-start">
                                    <i class="fas fa-comment text-blue-600 mr-2 mt-1"></i>
                                    <p class="text-gray-700">{{ $requestData->komentar }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="space-y-6">
            @if($requestData->status == 'diteruskan_ke_kasubag')
            <!-- Approval Card -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200 bg-purple-600 text-white rounded-t-lg">
                    <h3 class="text-lg font-medium flex items-center">
                        <i class="fas fa-gavel mr-2"></i>
                        Approval Kasubag
                    </h3>
                </div>
                <div class="p-6">
                    <form action="{{ route('kasubag.peminjaman-kendaraan.approve', $requestData->id) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="action" class="block text-sm font-medium text-gray-700 mb-2">Keputusan:</label>
                            <select name="action" id="action" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500" required>
                                <option value="">Pilih Keputusan</option>
                                 <option value="approve">✓ Terima Peminjaman</option>
                                <option value="reject">✗ Tolak Peminjaman</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="komentar" class="block text-sm font-medium text-gray-700 mb-2">Komentar/Keterangan:</label>
                            <textarea name="komentar" id="komentar" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500" rows="4" placeholder="Berikan komentar atau keterangan sesuai keputusan..."></textarea>
                        </div>

                        <button type="submit" class="w-full bg-purple-600 text-white py-2 px-4 rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-colors" id="submitBtn">
                            <i class="fas fa-check mr-2"></i>Simpan Keputusan
                        </button>
                    </form>
                </div>
            </div>
            @else
            <!-- Status Card -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200 {{ $requestData->status == 'approved' ? 'bg-yellow-600' : 'bg-red-600' }} text-white rounded-t-lg">
                    <h3 class="text-lg font-medium flex items-center">
                        <i class="fas fa-{{ $requestData->status == 'approved' ? 'check-circle' : 'times-circle' }} mr-2"></i>
                        Status Keputusan
                    </h3>
                </div>
                <div class="p-6">
                    <div class="text-center">
                        @if($requestData->status == 'approved')
                            <div class="text-green-600 text-4xl mb-4">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <h4 class="text-lg font-medium text-gray-900 mb-2">Peminjaman Kendaraan Diterima</h4>
                            <p class="text-gray-600">Permintaan Peminjaman Kendaraan telah diterima oleh kasubag.</p>
                        @else
                            <div class="text-red-600 text-4xl mb-4">
                                <i class="fas fa-times-circle"></i>
                            </div>
                            <h4 class="text-lg font-medium text-gray-900 mb-2">Peminjaman Kendaraan Di tolak</h4>
                            <p class="text-gray-600">Permintaan peminjaman kendaraan telah ditolak oleh kasubag.</p>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Navigation -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 text-center space-y-3">
                    <a href="{{ route('kasubag.peminjaman-aset') }}" class="w-full bg-gray-600 text-white py-2 px-4 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors inline-flex items-center justify-center">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar
                    </a>
                    <a href="{{ route('kasubag.dashboard') }}" class="w-full bg-gray-100 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors inline-flex items-center justify-center">
                        <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const actionSelect = document.getElementById('action');
        const submitBtn = document.getElementById('submitBtn');

        actionSelect.addEventListener('change', function() {
            if (this.value === 'approve') {
                 submitBtn.innerHTML = '<i class="fas fa-check mr-2"></i>Terima Peminjaman';
                submitBtn.className = 'w-full bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 transition-colors';
                submitBtn.hidden = false;
            } else if (this.value === 'reject') {
                submitBtn.innerHTML = '<i class="fas fa-times mr-2"></i>Tolak Peminjaman';
                submitBtn.className = 'w-full bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition-colors';
                submitBtn.hidden = false;
            } else {
                submitBtn.innerHTML = '<i class="fas fa-check mr-2"></i>Simpan Keputusan';
                submitBtn.className = 'w-full bg-yellow-600 text-white py-2 px-4 rounded-md hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 transition-colors';
                submitBtn.hidden = false;
            }
        });

        // Konfirmasi sebelum submit
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const action = actionSelect.value;
            const komentar = document.getElementById('komentar').value.trim();

            if (!action) {
                e.preventDefault();
                alert('Silakan pilih keputusan.');
                return;
            }

            let konfirmasi = '';
            if (action === 'approve') {
                konfirmasi = 'Anda yakin ingin menerima peminjaman kendaraan ini?';
            } else {
                konfirmasi = 'Anda yakin ingin menolak peminjaman kendaraan ini?';
            }

            if (!confirm(konfirmasi)) {
                e.preventDefault();
            }
        });
    });
</script>
@endpush
