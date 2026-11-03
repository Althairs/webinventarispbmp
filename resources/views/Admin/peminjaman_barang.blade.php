@extends('layouts.layout')
@section('title', 'Peminjaman Barang')
@section('Peminjaman Barang', 'active')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
    <div class="bg-white rounded-lg card-shadow">
        <div class="p-6 border-b">
            <h3 class="text-lg font-bold flex items-center">
                <i class="fas fa-box mr-2 text-blue-600"></i>
                Manajemen Peminjaman Barang
            </h3>
        </div>

        {{-- Search and Filter --}}
        @include('partials.search_filter_custom', ['placeholder' => 'Cari berdasarkan kode barang, kategori, merek, ruangan, atau nama peminjam...'])

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left">No</th>
                        <th class="px-6 py-3 text-left">Nama Barang</th>
                        <th class="px-6 py-3 text-left">Kategori</th>
                        <th class="px-6 py-3 text-left">Jumlah</th>
                        <th class="px-6 py-3 text-left">Deskripsi Peruntukan</th>
                        <th class="px-6 py-3 text-left">Peminjam</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($requests as $index => $req)
                        <tr class="border-t">
                            <td class="px-6 py-4">
                                <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-sm font-medium">{{ $index + 1 }}</span>
                            </td>
                            <td class="px-6 py-4">{{ $req->nama_barang ?? 'N/A' }}</td>
                            <td class="px-6 py-4">{{ $req->kategori ?? 'N/A' }}</td>
                            <td class="px-6 py-4">{{ $req->jumlah }}</td>
                            <td class="px-6 py-4">
                                <div class="max-w-xs" title="{{ $req->deskripsi_peruntukan }}">
                                    {{ $req->deskripsi_peruntukan ?? 'N/A' }}
                                </div>
                            </td>
                            <td class="px-6 py-4">{{ $req->user->nama_lengkap ?? 'N/A' }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded text-sm
                                    @if ($req->status === 'approved') bg-green-100 text-green-800
                                    @elseif ($req->status === 'rejected') bg-red-100 text-red-800
                                    @elseif ($req->status === 'direview') bg-blue-100 text-blue-800
                                    @elseif ($req->status === 'diteruskan_ke_kasubag') bg-purple-100 text-purple-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ ucfirst(str_replace('_', ' ', $req->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    @if ($req->status === 'Menunggu')
                                        <button id="review-btn-{{ $req->id }}" onclick="reviewPeminjamanBarang({{ $req->id }}, this)" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm mr-2">
                                            <i class="fas fa-eye"></i> Review
                                        </button>
                                    @elseif ($req->status === 'direview')
                                        <button onclick="showForwardModal({{ $req->id }})" class="bg-purple-500 hover:bg-purple-600 text-white px-3 py-1 rounded text-sm">
                                            <i class="fas fa-share"></i> Forward
                                        </button>
                                    @elseif ($req->status === 'diteruskan_ke_kasubag')
                                        <span class="text-sm text-purple-600 font-medium">
                                            <i class="fas fa-check-circle"></i> Diteruskan ke Kasubag
                                        </span>
                                    @elseif ($req->status === 'approved')
                                        <span class="text-sm text-green-600 font-medium">
                                            <i class="fas fa-check-circle"></i> Diterima Kasubag
                                        </span>
                                    @elseif ($req->status === 'rejected')
                                        <span class="text-sm text-red-600 font-medium">
                                            <i class="fas fa-times-circle"></i> Ditolak
                                        </span>
                                    @else
                                        <span class="text-sm text-gray-600">
                                            {{ ucfirst(str_replace('_', ' ', $req->status)) }}
                                        </span>
                                    @endif
                                    <button onclick="showDetailModal({{ $req->id }})" class="text-green-600 hover:text-green-800">
                                        <i class="fas fa-info-circle"></i> Lihat Detail
                                    </button>
                                    <button onclick="previewSurat({{ $req->id }}, 'peminjaman-barang')" class="text-blue-600 hover:text-blue-800 ml-2">
                                        <i class="fas fa-file-alt"></i> Lihat Surat
                                    </button>
                                    <button onclick="downloadSurat({{ $req->id }}, 'peminjaman-barang')" class="text-purple-600 hover:text-purple-800 ml-2">
                                        <i class="fas fa-download"></i> Download PDF
                                    </button>
                                    @if($req->suratPersetujuan)
                                        <div class="flex items-center space-x-2">
                                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs">
                                                <i class="fas fa-file-pdf mr-1"></i>Surat Tersedia
                                            </span>
                                            <a href="{{ route('surat-persetujuan.preview', $req->suratPersetujuan->id) }}"
                                               target="_blank"
                                               class="text-blue-600 hover:text-blue-800 text-sm">
                                                <i class="fas fa-eye"></i> Lihat
                                            </a>
                                            <a href="{{ route('surat-persetujuan.download', $req->suratPersetujuan->id) }}"
                                               class="text-purple-600 hover:text-purple-800 text-sm">
                                                <i class="fas fa-download"></i> Download
                                            </a>
                                        </div>
                                    @else
                                        <button onclick="showUploadSuratModal({{ $req->id }}, 'SPB')" class="text-green-600 hover:text-green-800 ml-2">
                                            <i class="fas fa-upload"></i> Upload Surat Persetujuan
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t bg-gray-50">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Menampilkan
                    <span class="font-medium">{{ $requests->firstItem() }}</span>
                    sampai
                    <span class="font-medium">{{ $requests->lastItem() }}</span>
                    dari
                    <span class="font-medium">{{ $requests->total() }}</span>
                    hasil
                </div>

                <div class="flex items-center space-x-2">
                    {{-- Previous Page Link --}}
                    @if ($requests->onFirstPage())
                        <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                            <i class="fas fa-chevron-left mr-1"></i>Sebelumnya
                        </span>
                    @else
                        <a href="{{ $requests->previousPageUrl() }}" class="px-3 py-2 text-sm text-purple-600 bg-white border border-purple-200 rounded-lg hover:bg-purple-50 hover:border-purple-300 transition-colors">
                            <i class="fas fa-chevron-left mr-1"></i>Sebelumnya
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    <div class="flex items-center space-x-1">
                        @php
                            $currentPage = $requests->currentPage();
                            $lastPage = $requests->lastPage();

                            // Hitung range halaman yang akan ditampilkan (maksimal 5 halaman)
                            $maxPages = 5;
                            $halfMax = floor($maxPages / 2);

                            // Tentukan halaman awal dan akhir
                            if ($lastPage <= $maxPages) {
                                // Jika total halaman <= 5, tampilkan semua
                                $startPage = 1;
                                $endPage = $lastPage;
                            } else {
                                // Jika total halaman > 5, hitung range
                                if ($currentPage <= $halfMax + 1) {
                                    // Jika current page di awal (1-3), tampilkan 1-5
                                    $startPage = 1;
                                    $endPage = $maxPages;
                                } elseif ($currentPage >= $lastPage - $halfMax) {
                                    // Jika current page di akhir, tampilkan 5 halaman terakhir
                                    $startPage = $lastPage - $maxPages + 1;
                                    $endPage = $lastPage;
                                } else {
                                    // Jika current page di tengah, tampilkan 2 halaman sebelum dan sesudah
                                    $startPage = $currentPage - $halfMax;
                                    $endPage = $currentPage + $halfMax;
                                }
                            }
                        @endphp

                        {{-- First page dengan ellipsis --}}
                        @if ($startPage > 1)
                            <a href="{{ $requests->url(1) }}" class="px-3 py-2 text-sm text-purple-600 bg-white border border-purple-200 rounded-lg hover:bg-purple-50 hover:border-purple-300 transition-colors">
                                1
                            </a>
                            @if ($startPage > 2)
                                <span class="px-2 text-sm text-gray-500">...</span>
                            @endif
                        @endif

                        {{-- Page range (maksimal 5 halaman) --}}
                        @for ($page = $startPage; $page <= $endPage; $page++)
                            @if ($page == $currentPage)
                                <span class="px-3 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $requests->url($page) }}" class="px-3 py-2 text-sm text-purple-600 bg-white border border-purple-200 rounded-lg hover:bg-purple-50 hover:border-purple-300 transition-colors">
                                    {{ $page }}
                                </a>
                            @endif
                        @endfor

                        {{-- Last page dengan ellipsis --}}
                        @if ($endPage < $lastPage)
                            @if ($endPage < $lastPage - 1)
                                <span class="px-2 text-sm text-gray-500">...</span>
                            @endif
                            <a href="{{ $requests->url($lastPage) }}" class="px-3 py-2 text-sm text-purple-600 bg-white border border-purple-200 rounded-lg hover:bg-purple-50 hover:border-purple-300 transition-colors">
                                {{ $lastPage }}
                            </a>
                        @endif
                    </div>

                    {{-- Next Page Link --}}
                    @if ($requests->hasMorePages())
                        <a href="{{ $requests->nextPageUrl() }}" class="px-3 py-2 text-sm text-purple-600 bg-white border border-purple-200 rounded-lg hover:bg-purple-50 hover:border-purple-300 transition-colors">
                            Selanjutnya<i class="fas fa-chevron-right ml-1"></i>
                        </a>
                    @else
                        <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                            Selanjutnya<i class="fas fa-chevron-right ml-1"></i>
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail Peminjaman Barang -->
    <div id="detailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center pb-3">
                    <h3 class="text-lg font-bold text-gray-900">Detail Peminjaman Barang</h3>
                    <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="mt-2 px-7 py-3">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">ID Request</label>
                            <p id="detail-id" class="mt-1 text-sm text-gray-900"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kode Barang</label>
                            <p id="detail-kode-barang" class="mt-1 text-sm text-gray-900"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama Barang</label>
                            <p id="detail-nama-barang" class="mt-1 text-sm text-gray-900"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kategori</label>
                            <p id="detail-kategori" class="mt-1 text-sm text-gray-900"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Merek</label>
                            <p id="detail-merek" class="mt-1 text-sm text-gray-900"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jumlah</label>
                            <p id="detail-jumlah" class="mt-1 text-sm text-gray-900"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Peminjaman</label>
                            <p id="detail-tanggal-peminjaman" class="mt-1 text-sm text-gray-900"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Pengembalian</label>
                            <p id="detail-tanggal-pengembalian" class="mt-1 text-sm text-gray-900"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Peminjam</label>
                            <p id="detail-peminjam" class="mt-1 text-sm text-gray-900"></p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Deskripsi Peruntukan</label>
                            <p id="detail-deskripsi" class="mt-1 text-sm text-gray-900 p-3 bg-gray-50 rounded-md"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <p id="detail-status" class="mt-1 text-sm"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Request</label>
                            <p id="detail-tanggal" class="mt-1 text-sm text-gray-900"></p>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end pt-2">
                    <button onclick="closeDetailModal()" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Upload Surat Persetujuan -->
    <div id="uploadSuratModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center pb-3">
                    <h3 class="text-lg font-bold text-gray-900">Upload Surat Persetujuan</h3>
                    <button onclick="closeUploadSuratModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <form id="uploadSuratForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="upload_request_id" name="request_id">
                    <input type="hidden" id="upload_jenis_surat" name="jenis_surat">

                    <div class="mt-2 px-7 py-3 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Surat</label>
                            <input type="text" name="nomor_surat" id="upload_nomor_surat"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Contoh: SPB/001/2025">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">File Surat Persetujuan <span class="text-red-500">*</span></label>
                            <input type="file" name="file" id="upload_file"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   accept=".pdf,.doc,.docx" required>
                            <p class="text-xs text-gray-500 mt-1">Format yang diperbolehkan: PDF, DOC, DOCX (Max: 10MB)</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                            <textarea name="keterangan" id="upload_keterangan" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="Keterangan tambahan (opsional)"></textarea>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" onclick="closeUploadSuratModal()"
                                class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                            Batal
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-green-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-300">
                            <i class="fas fa-upload mr-2"></i>Upload Surat
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Forward -->
    <div id="forwardModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Teruskan ke Kasubag</h3>
                <form id="forwardForm" method="POST" onsubmit="return false;">
                    @csrf
                     <input type="hidden" id="forwardRequestId" name="request_id">
                    <div class="mb-4">
                        <label for="action" class="block text-sm font-medium text-gray-700 mb-2">Aksi</label>
                        <select id="action" name="action" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="forward">Teruskan ke Kasubag</option>
                            <option value="reject">Tolak Pengajuan</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="komentar" class="block text-sm font-medium text-gray-700 mb-2">Komentar</label>
                        <textarea id="komentar" name="komentar" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500" placeholder="Masukkan komentar..."></textarea>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeForwardModal()" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                            Batal
                        </button>
                        <button type="button" id="submitForwardBtn" onclick="handleForwardSubmit()" class="px-4 py-2 bg-purple-500 text-white rounded-md hover:bg-purple-600">
                            <i class="fas fa-share me-2"></i>Teruskan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Fungsi untuk review peminjaman barang
    async function reviewPeminjamanBarang(id, buttonElement) {
        if (!confirm('Apakah Anda yakin ingin mereview permintaan ini?')) {
            return;
        }

        // Disable button and show loading state
        const originalHtml = buttonElement.innerHTML;
        buttonElement.disabled = true;
        buttonElement.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
        buttonElement.classList.add('opacity-50', 'cursor-not-allowed');

        try {
            const response = await fetch(`{{ url('admin/peminjaman-barang') }}/${id}/review`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            });

            let data;
            try {
                data = await response.json();
            } catch (jsonError) {
                // If response is not JSON, try to get text
                const text = await response.text();
                throw new Error(text || 'Response tidak valid');
            }

            if (response.ok && data.status === 'success') {
                alert('✅ ' + (data.message || 'Permintaan berhasil direview.'));
                // Reload after a short delay to ensure server has processed
                setTimeout(() => {
                    location.reload();
                }, 100);
            } else {
                // Handle error response
                const errorMessage = data.message || data.error || 'Gagal mereview permintaan.';
                if (data.status === 'already_reviewed') {
                    alert('⚠️ ' + errorMessage + '\n\nMengambil data terbaru...');
                    setTimeout(() => {
                        location.reload();
                    }, 100);
                } else if (data.status === 'invalid_status') {
                    alert('⚠️ ' + errorMessage + '\n\nMengambil data terbaru...');
                    setTimeout(() => {
                        location.reload();
                    }, 100);
                } else {
                    alert('❌ ' + errorMessage);
                    // Re-enable button on error
                    buttonElement.disabled = false;
                    buttonElement.innerHTML = originalHtml;
                    buttonElement.classList.remove('opacity-50', 'cursor-not-allowed');
                }
            }
        } catch (error) {
            console.error('Error details:', error);
            alert('❌ Terjadi kesalahan: ' + (error.message || 'Silakan coba lagi atau refresh halaman.'));
            // Re-enable button on error
            buttonElement.disabled = false;
            buttonElement.innerHTML = originalHtml;
            buttonElement.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    }

    // Fungsi untuk modal forward
    function showForwardModal(id) {
        const modal = document.getElementById('forwardModal');
        const form = document.getElementById('forwardForm');
        const submitUrl = `{{ url('admin/peminjaman-barang') }}/${id}/forward`;
        form.setAttribute('action', submitUrl);
        form.setAttribute('data-request-id', id);
        modal.classList.remove('hidden');
    }

    function closeForwardModal() {
        const modal = document.getElementById('forwardModal');
        modal.classList.add('hidden');
        document.getElementById('forwardForm').reset();
        document.getElementById('submitForwardBtn').innerHTML = '<i class="fas fa-share me-2"></i>Teruskan';
        document.getElementById('submitForwardBtn').className = 'px-4 py-2 bg-purple-500 text-white rounded-md hover:bg-purple-600';
    }

    // Update button berdasarkan action
    document.getElementById('action').addEventListener('change', function() {
        const submitBtn = document.getElementById('submitForwardBtn');
        if (this.value === 'forward') {
            submitBtn.innerHTML = '<i class="fas fa-share me-2"></i>Kirim ke Kasubag';
            submitBtn.className = 'px-4 py-2 bg-purple-500 text-white rounded-md hover:bg-purple-600';
        } else if (this.value === 'reject') {
            submitBtn.innerHTML = '<i class="fas fa-times me-2"></i>Tolak Pengajuan';
            submitBtn.className = 'px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600';
        }
    });

    // Handle forward button click dengan onclick langsung
    function handleForwardSubmit() {
        const form = document.getElementById('forwardForm');
        const formData = new FormData(form);
        const submitBtn = document.getElementById('submitForwardBtn');
        const originalText = submitBtn.innerHTML;

        // Show loading state
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';
        submitBtn.disabled = true;

        const targetUrl = form.getAttribute('action');
        if (!targetUrl) {
            alert('❌ URL tujuan tidak ditemukan. Silakan refresh halaman dan coba lagi.');
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            return;
        }

        fetch(targetUrl, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert(data.message || 'Permintaan berhasil diproses.');
                closeForwardModal();
                location.reload();
            } else {
                if (data.status === 'already_forwarded') {
                    alert('⚠️ ' + data.message);
                } else if (data.status === 'invalid_status') {
                    alert('❌ ' + data.message);
                } else {
                    alert('❌ ' + (data.message || 'Gagal memproses permintaan.'));
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('❌ Terjadi kesalahan saat memproses permintaan.');
        })
        .finally(() => {
            // Reset button state
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
    }

    async function approveRequest(id) {
        const comment = prompt('Masukkan komentar (opsional):');
        if (comment !== null) {
            try {
                const response = await fetch(`{{ url('admin.peminjaman-barang') }}/${id}/approve`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ comment })
                });
                const data = await response.json();
                if (response.ok) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert('Gagal menyetujui permintaan.');
                }
            } catch (error) {
                alert('Terjadi kesalahan.');
                console.error(error);
            }
        }
    }

    async function rejectRequest(id) {
        const comment = prompt('Masukkan alasan penolakan:');
        if (comment) {
            try {
                const response = await fetch(`{{ url('admin/peminjaman-barang') }}/${id}/reject`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ comment })
                });
                const data = await response.json();
                if (response.ok) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert('Gagal menolak permintaan.');
                }
            } catch (error) {
                alert('Terjadi kesalahan.');
                console.error(error);
            }
        }
    }

    async function showDetailModal(id) {
        try {
            const response = await fetch(`{{ url('admin/peminjaman-barang') }}/${id}/detail`);
            const data = await response.json();

            if (response.ok) {
                // Populate modal with data
                document.getElementById('detail-id').textContent = data.id;
                document.getElementById('detail-kode-barang').textContent = data.kode_barang || 'N/A';
                document.getElementById('detail-nama-barang').textContent = data.nama_barang || 'N/A';
                document.getElementById('detail-kategori').textContent = data.kategori || 'N/A';
                document.getElementById('detail-merek').textContent = data.merek || 'N/A';
                document.getElementById('detail-jumlah').textContent = data.jumlah;
                document.getElementById('detail-tanggal-peminjaman').textContent = data.tanggal_peminjaman ? new Date(data.tanggal_peminjaman).toLocaleDateString('id-ID') : 'N/A';
                document.getElementById('detail-tanggal-pengembalian').textContent = data.tanggal_pengembalian ? new Date(data.tanggal_pengembalian).toLocaleDateString('id-ID') : 'N/A';
                document.getElementById('detail-deskripsi').textContent = data.deskripsi_peruntukan || 'N/A';
                document.getElementById('detail-peminjam').textContent = data.user ? data.user.nama_lengkap : 'N/A';

                // Set status with color
                const statusElement = document.getElementById('detail-status');
                statusElement.textContent = data.status.charAt(0).toUpperCase() + data.status.slice(1);
                statusElement.className = 'mt-1 text-sm px-2 py-1 rounded';
                if (data.status === 'approved') {
                    statusElement.className += ' bg-green-100 text-green-800';
                } else if (data.status === 'rejected') {
                    statusElement.className += ' bg-red-100 text-red-800';
                } else {
                    statusElement.className += ' bg-yellow-100 text-yellow-800';
                }

                document.getElementById('detail-tanggal').textContent = data.request_date || new Date(data.created_at).toLocaleDateString('id-ID');

                // Show modal
                document.getElementById('detailModal').classList.remove('hidden');
            } else {
                alert('Gagal mengambil detail data.');
            }
        } catch (error) {
            alert('Terjadi kesalahan.');
            console.error(error);
        }
    }

    function closeDetailModal() {
        document.getElementById('detailModal').classList.add('hidden');
    }

    // Close modal when clicking outside
    document.getElementById('detailModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDetailModal();
        }
    });

    // Fungsi untuk preview surat
    function previewSurat(id, jenis) {
        const url = `{{ url('admin/surat/preview') }}/${jenis}/${id}`;
        window.open(url, '_blank', 'width=800,height=600');
    }

    // Fungsi untuk download surat PDF
    function downloadSurat(id, jenis) {
        const url = `{{ url('admin/surat') }}/${jenis}/${id}`;
        window.open(url, '_blank');
    }

    // Update button berdasarkan action
    document.getElementById('action').addEventListener('change', function() {
        const submitBtn = document.getElementById('submitForwardBtn');
        if (this.value === 'forward') {
            submitBtn.innerHTML = '<i class="fas fa-share me-2"></i>Kirim ke Kasubag';
            submitBtn.className = 'px-4 py-2 bg-purple-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-purple-600 focus:outline-none focus:ring-2 focus:ring-purple-300';
        } else if (this.value === 'reject') {
            submitBtn.innerHTML = '<i class="fas fa-times me-2"></i>Tolak Pengajuan';
            submitBtn.className = 'px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300';
        } else {
            submitBtn.innerHTML = '<i class="fas fa-share me-2"></i>Teruskan';
            submitBtn.className = 'px-4 py-2 bg-purple-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-purple-600 focus:outline-none focus:ring-2 focus:ring-purple-300';
        }
    });

    // Fungsi untuk menampilkan modal upload surat persetujuan
    function showUploadSuratModal(requestId, jenisSurat) {
        document.getElementById('upload_request_id').value = requestId;
        document.getElementById('upload_jenis_surat').value = jenisSurat;
        document.getElementById('uploadSuratModal').classList.remove('hidden');
    }

    // Fungsi untuk menutup modal upload surat persetujuan
    function closeUploadSuratModal() {
        document.getElementById('uploadSuratModal').classList.add('hidden');
        document.getElementById('uploadSuratForm').reset();
    }

    // Handle form submit upload surat persetujuan
    document.getElementById('uploadSuratForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        try {
            const response = await fetch('{{ route("admin.surat-persetujuan.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            if (response.ok) {
                alert('Surat persetujuan berhasil diupload!');
                closeUploadSuratModal();
                location.reload();
            } else {
                const errorData = await response.json();
                alert('Gagal mengupload surat persetujuan: ' + (errorData.message || 'Terjadi kesalahan'));
            }
        } catch (error) {
            alert('Terjadi kesalahan saat mengupload surat persetujuan.');
            console.error(error);
        }
    });

    // Close modal when clicking outside
    document.getElementById('uploadSuratModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeUploadSuratModal();
        }
    });
</script>
@endpush
