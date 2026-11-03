@extends('layouts.layout')

@section('title', 'Detail Peminjaman Aset - Kasubag')

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
                <div class="px-6 py-4 border-b border-gray-200 bg-purple-600 text-white rounded-t-lg">
                    <h3 class="text-lg font-medium flex items-center">
                        <i class="fas fa-building mr-2"></i>
                        Detail Peminjaman Aset
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h6 class="text-lg font-medium text-gray-900 mb-4">Informasi Aset</h6>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-700">Nama Barang:</span>
                                    <span class="text-gray-900">{{ $requestData->nama_barang ?? 'Gedung/Ruang' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-700">Jenis/Gedung/Ruang:</span>
                                    <span class="px-2 py-1 text-xs font-medium bg-purple-100 text-purple-800 rounded-full">{{ $requestData->jenis ?? 'N/A' }}</span>
                                </div>
                                @if($requestData->nama_gedung)
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-700">Nama Gedung:</span>
                                    <span class="text-gray-900">{{ $requestData->nama_gedung }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div>
                            <h6 class="text-lg font-medium text-gray-900 mb-4">Informasi Pemohon</h6>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-700">Nama Peminjam:</span>
                                    <span class="text-gray-900">{{ $requestData->nama_peminjam ?? 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-700">Nama Lengkap:</span>
                                    <span class="text-gray-900">{{ $requestData->user->nama_lengkap ?? 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-700">NIP:</span>
                                    <span class="text-gray-900">{{ $requestData->nip ?? 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-700">Instansi:</span>
                                    <span class="text-gray-900">{{ $requestData->nama_instansi ?? $requestData->user->instansi ?? 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-700">Kabupaten/Kota:</span>
                                    <span class="text-gray-900">{{ $requestData->kabupaten_kota ?? 'N/A' }}</span>
                                </div>
                                @if($requestData->user && $requestData->user->jabatan)
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-700">Jabatan:</span>
                                    <span class="text-gray-900">{{ $requestData->user->jabatan }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <hr class="my-6">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h6 class="text-lg font-medium text-gray-900 mb-4">Detail Peminjaman</h6>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-700">Tanggal Pinjaman:</span>
                                    <span class="text-gray-900">{{ $requestData->tanggal_pinjaman ? \Carbon\Carbon::parse($requestData->tanggal_pinjaman)->translatedFormat('d F Y') : 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-700">Tanggal Selesai:</span>
                                    <span class="text-gray-900">{{ $requestData->tanggal_selesai ? \Carbon\Carbon::parse($requestData->tanggal_selesai)->translatedFormat('d F Y') : 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-700">Jam Mulai:</span>
                                    <span class="text-gray-900">
                                        @php
                                            $jamMulai = $requestData->jam_mulai;
                                            if ($jamMulai) {
                                                if (is_string($jamMulai)) {
                                                    try {
                                                        echo \Carbon\Carbon::parse($jamMulai)->format('H:i');
                                                    } catch (\Exception $e) {
                                                        echo $jamMulai;
                                                    }
                                                } else {
                                                    echo $jamMulai->format('H:i');
                                                }
                                            } else {
                                                echo 'N/A';
                                            }
                                        @endphp
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-700">Jam Selesai:</span>
                                    <span class="text-gray-900">
                                        @php
                                            $jamSelesai = $requestData->jam_selesai;
                                            if ($jamSelesai) {
                                                if (is_string($jamSelesai)) {
                                                    try {
                                                        echo \Carbon\Carbon::parse($jamSelesai)->format('H:i');
                                                    } catch (\Exception $e) {
                                                        echo $jamSelesai;
                                                    }
                                                } else {
                                                    echo $jamSelesai->format('H:i');
                                                }
                                            } else {
                                                echo 'N/A';
                                            }
                                        @endphp
                                    </span>
                                </div>
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
                                        <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">Menunggu</span>
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

                    @if($requestData->surat_path)
                        <hr class="my-6">
                        <div>
                            <h6 class="text-lg font-medium text-gray-900 mb-4">Surat Uploaded</h6>
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <i class="fas fa-file-alt text-blue-600 mr-2"></i>
                                        <span class="text-gray-700">Surat telah diupload oleh pemohon</span>
                                    </div>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('kasubag.peminjaman-aset.view-surat', $requestData->id) }}" target="_blank" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 text-sm">
                                            <i class="fas fa-eye mr-1"></i>Lihat
                                        </a>
                                        <a href="{{ route('kasubag.peminjaman-aset.download-surat', $requestData->id) }}" class="px-3 py-1 bg-purple-500 text-white rounded hover:bg-purple-600 text-sm">
                                            <i class="fas fa-download mr-1"></i>Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($requestData->komentar)
                        <hr class="my-6">
                        <div>
                            <h6 class="text-lg font-medium text-gray-900 mb-4">Komentar</h6>
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex items-start">
                                    <i class="fas fa-comment text-blue-600 mr-2 mt-1"></i>
                                    <p class="text-gray-700">{{ $requestData->komentar }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($requestData->status == 'approved' || $requestData->status == 'rejected')
                        <hr class="my-6">
                        <div>
                            <h6 class="text-lg font-medium text-gray-900 mb-4">Keputusan Kasubag</h6>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <div class="flex justify-between mb-2">
                                        <span class="font-medium text-gray-700">Status:</span>
                                        @if($requestData->status == 'approved')
                                            <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Diterima</span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">Ditolak</span>
                                        @endif
                                    </div>
                                    @if($requestData->approved_by_kasubag_date)
                                        <div class="flex justify-between mb-2">
                                            <span class="font-medium text-gray-700">Tanggal Keputusan:</span>
                                            <span class="text-gray-900">
                                                @if(is_string($requestData->approved_by_kasubag_date))
                                                    {{ \Carbon\Carbon::parse($requestData->approved_by_kasubag_date)->translatedFormat('d F Y H:i') }}
                                                @else
                                                    {{ $requestData->approved_by_kasubag_date->translatedFormat('d F Y H:i') }}
                                                @endif
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    @if($requestData->approvedByKasubag)
                                        <div class="flex justify-between mb-2">
                                            <span class="font-medium text-gray-700">Diterima oleh:</span>
                                            <span class="text-gray-900">{{ $requestData->approvedByKasubag->nama_lengkap }}</span>
                                        </div>
                                    @endif
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
                    <form action="{{ route('kasubag.peminjaman-aset.approve', $requestData->id) }}" method="POST">
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
                <div class="px-6 py-4 border-b border-gray-200 {{ $requestData->status == 'approved' ? 'bg-purple-600' : 'bg-red-600' }} text-white rounded-t-lg">
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
                            <h4 class="text-lg font-medium text-gray-900 mb-2">Peminjaman Aset Diterima</h4>
                            <p class="text-gray-600">Permintaan peminjaman aset telah diterima oleh kasubag.</p>
                        @else
                            <div class="text-red-600 text-4xl mb-4">
                                <i class="fas fa-times-circle"></i>
                            </div>
                            <h4 class="text-lg font-medium text-gray-900 mb-2">Peminjaman Aset Ditolak</h4>
                            <p class="text-gray-600">Permintaan peminjaman aset telah ditolak oleh kasubag.</p>
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
                submitBtn.className = 'w-full bg-purple-600 text-white py-2 px-4 rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-colors';
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
                 konfirmasi = 'Anda yakin ingin menerima peminjaman aset ini?';
            } else {
                konfirmasi = 'Anda yakin ingin menolak peminjaman aset ini?';
            }

            if (!confirm(konfirmasi)) {
                e.preventDefault();
            }
        });
    });
</script>
@endpush
