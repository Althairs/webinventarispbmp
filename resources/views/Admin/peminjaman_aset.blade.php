@extends('layouts.layout')
@section('title', 'Peminjaman Aset')
@section('Peminjaman Aset', 'active')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
    <div class="bg-white rounded-lg card-shadow">
        <div class="p-6 border-b">
            <h3 class="text-lg font-bold">Manajemen Peminjaman Aset</h3>
        </div>
        
        {{-- Search and Filter --}}
        @include('partials.search_filter_custom', ['placeholder' => 'Cari berdasarkan nama instansi, nama peminjam, NIP, nama barang, jenis, atau kabupaten/kota...'])
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left">No</th>
                        <th class="px-6 py-3 text-left">Nama Instansi</th>
                        <th class="px-6 py-3 text-left">Nama Peminjam</th>
                        <th class="px-6 py-3 text-left">NIP</th>
                        <th class="px-6 py-3 text-left">Kabupaten/Kota</th>
                        <th class="px-6 py-3 text-left">Jenis Ruang</th>
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
                            <td class="px-6 py-4">{{ $req->nama_instansi }}</td>
                            <td class="px-6 py-4">{{ $req->nama_peminjam }}</td>
                            <td class="px-6 py-4">{{ $req->nip }}</td>
                            <td class="px-6 py-4">{{ $req->kabupaten_kota }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded text-xs bg-purple-100 text-purple-800">
                                    {{ $req->jenis }}
                                </span>
                            </td>
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
                                        <form action="{{ route('admin.peminjaman-aset.review', $req->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm mr-2">
                                                <i class="fas fa-eye"></i> Review
                                            </button>
                                        </form>
                                    @elseif ($req->status === 'direview')
                                        <button onclick="if(typeof showForwardModal === 'function') { showForwardModal({{ $req->id }}); } else { console.error('showForwardModal tidak ditemukan!'); alert('Error: Fungsi tidak ditemukan. Silakan refresh halaman.'); }" class="bg-purple-500 hover:bg-purple-600 text-white px-3 py-1 rounded text-sm">
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
                                    @if($req->surat_path)
                                        <div class="flex items-center space-x-2">
                                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs">
                                                <i class="fas fa-file-alt mr-1"></i>Surat Uploaded
                                            </span>
                                        </div>
                                    @endif
                                    <button onclick="showDetailModal({{ $req->id }})" class="text-green-600 hover:text-green-800">
                                        <i class="fas fa-info-circle"></i> Lihat Detail
                                    </button>
                                    @if($req->surat_path)
                                        <a href="{{ route('admin.peminjaman-aset.view-surat', $req->id) }}" target="_blank" class="text-blue-600 hover:text-blue-800 ml-2">
                                            <i class="fas fa-file-alt"></i> Lihat Surat Uploaded
                                        </a>
                                        <a href="{{ route('admin.peminjaman-aset.download-surat', $req->id) }}" class="text-purple-600 hover:text-purple-800 ml-2">
                                            <i class="fas fa-download"></i> Download Surat Uploaded
                                        </a>
                                    @else
                                        <button onclick="previewSurat({{ $req->id }}, 'peminjaman-aset')" class="text-blue-600 hover:text-blue-800 ml-2">
                                            <i class="fas fa-file-alt"></i> Lihat Surat Generated
                                        </button>
                                        <button onclick="downloadSurat({{ $req->id }}, 'peminjaman-aset')" class="text-purple-600 hover:text-purple-800 ml-2">
                                            <i class="fas fa-download"></i> Download PDF Generated
                                        </button>
                                    @endif
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
                                        <button onclick="if(typeof showUploadSuratModal === 'function') { showUploadSuratModal({{ $req->id }}, 'SPA'); } else { console.error('showUploadSuratModal tidak ditemukan!'); alert('Error: Fungsi tidak ditemukan. Silakan refresh halaman.'); }" class="text-green-600 hover:text-green-800 ml-2">
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

    <!-- Modal Detail Peminjaman Aset -->
    <div id="detailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-2/3 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center pb-3">
                    <h3 class="text-lg font-bold text-gray-900">Detail Peminjaman Aset</h3>
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
                            <label class="block text-sm font-medium text-gray-700">Nama Instansi</label>
                            <p id="detail-nama-instansi" class="mt-1 text-sm text-gray-900"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama Peminjam</label>
                            <p id="detail-nama-peminjam" class="mt-1 text-sm text-gray-900"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">NIP</label>
                            <p id="detail-nip" class="mt-1 text-sm text-gray-900"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kabupaten/Kota</label>
                            <p id="detail-kabupaten-kota" class="mt-1 text-sm text-gray-900"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jenis Ruang</label>
                            <p id="detail-nama-gedung" class="mt-1 text-sm text-gray-900"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <p id="detail-status" class="mt-1 text-sm"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Pinjaman</label>
                            <p id="detail-tanggal-pinjaman" class="mt-1 text-sm text-gray-900"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
                            <p id="detail-tanggal-selesai" class="mt-1 text-sm text-gray-900"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jam Mulai</label>
                            <p id="detail-jam-mulai" class="mt-1 text-sm text-gray-900"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jam Selesai</label>
                            <p id="detail-jam-selesai" class="mt-1 text-sm text-gray-900"></p>
                        </div>
                        <div id="detail-surat-section" class="hidden">
                            <label class="block text-sm font-medium text-gray-700">Surat Uploaded</label>
                            <div class="flex items-center space-x-2 mt-2">
                                <a id="detail-view-surat" href="#" target="_blank" class="px-3 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 text-sm">
                                    <i class="fas fa-eye mr-1"></i>Lihat Surat Uploaded
                                </a>
                                <a id="detail-download-surat" href="#" class="px-3 py-2 bg-purple-500 text-white rounded hover:bg-purple-600 text-sm">
                                    <i class="fas fa-download mr-1"></i>Download Surat Uploaded
                                </a>
                            </div>
                        </div>
                        <div id="detail-surat-generated-section" class="hidden">
                            <label class="block text-sm font-medium text-gray-700">Surat Generated</label>
                            <div class="flex items-center space-x-2 mt-2">
                                <button id="detail-preview-generated" onclick="previewSuratFromModal()" class="px-3 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 text-sm">
                                    <i class="fas fa-file-alt mr-1"></i>Lihat Surat Generated
                                </button>
                                <button id="detail-download-generated" onclick="downloadSuratFromModal()" class="px-3 py-2 bg-purple-500 text-white rounded hover:bg-purple-600 text-sm">
                                    <i class="fas fa-download mr-1"></i>Download PDF Generated
                                </button>
                            </div>
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
{{-- @endsection --}}

    <!-- Modal Forward -->
    <div id="forwardModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center pb-3">
                    <h3 class="text-lg font-bold text-gray-900">Teruskan ke Kasubag</h3>
                    <button onclick="closeForwardModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <form id="forwardForm" method="POST" onsubmit="return false;">
                    @csrf
                    <div class="px-7 py-3">
                        <div class="mb-4">
                            <label for="action" class="block text-sm font-medium text-gray-700 mb-2">Keputusan:</label>
                            <select id="action" name="action" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-transparent" required>
                                <option value="">Pilih Keputusan</option>
                                <option value="forward">Kirim ke Kasubag</option>
                                <option value="reject">Tolak Pengajuan</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="komentar" class="block text-sm font-medium text-gray-700 mb-2">Komentar/Keterangan:</label>
                            <textarea id="komentar" name="komentar" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-transparent" placeholder="Berikan komentar atau keterangan sesuai keputusan..." required></textarea>
                        </div>
                    </div>
                    <div class="flex justify-end pt-2 space-x-2">
                        <button type="button" onclick="closeForwardModal()" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                            Batal
                        </button>
                        <button type="button" id="submitForwardBtn" onclick="handleForwardSubmitAset()" class="px-4 py-2 bg-purple-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-purple-600 focus:outline-none focus:ring-2 focus:ring-purple-300">
                            <i class="fas fa-share me-2"></i>Teruskan
                        </button>
                    </div>
                </form>
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
                                   placeholder="Contoh: SPA/001/2025">
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
@endsection

@push('scripts')
<script>
    // Definisikan fungsi showForwardModal SEBELUM semua fungsi lain agar tersedia lebih awal
    // Pastikan tidak ada deklarasi duplikat - gunakan window scope
    // Deklarasikan hanya sekali untuk menghindari SyntaxError
    if (typeof window.currentRequestId === 'undefined') {
        window.currentRequestId = null;
    }
    
    // Buat referensi lokal untuk kemudahan
    var currentRequestId = window.currentRequestId;
    
    function showForwardModal(id) {
        console.log('showForwardModal called with ID:', id);
        try {
            window.currentRequestId = id;
            currentRequestId = id;
            const modal = document.getElementById('forwardModal');
            
            if (!modal) {
                console.error('Modal dengan ID forwardModal tidak ditemukan!');
                alert('Error: Modal tidak ditemukan. Silakan refresh halaman.');
                return;
            }
            
            const form = document.getElementById('forwardForm');
            if (!form) {
                console.error('Form dengan ID forwardForm tidak ditemukan!');
                alert('Error: Form tidak ditemukan. Silakan refresh halaman.');
                return;
            }
            
            // Simpan ID di data attribute untuk digunakan nanti
            form.setAttribute('data-request-id', id);
            
            // Pastikan modal visible dengan menghapus class hidden dan menambahkan display block
            modal.classList.remove('hidden');
            modal.style.display = 'flex';
            modal.style.zIndex = '9999';
            modal.style.position = 'fixed';
            modal.style.top = '0';
            modal.style.left = '0';
            modal.style.width = '100%';
            modal.style.height = '100%';
            modal.style.alignItems = 'center';
            modal.style.justifyContent = 'center';
            
            console.log('Modal should be visible now');
            
            // Reset form
            form.reset();
            
            // Reset button text
            const submitBtn = document.getElementById('submitForwardBtn');
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fas fa-share me-2"></i>Teruskan';
                submitBtn.className = 'px-4 py-2 bg-purple-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-purple-600 focus:outline-none focus:ring-2 focus:ring-purple-300';
                submitBtn.disabled = false;
            }
            
            // Reset action select
            const actionSelect = document.getElementById('action');
            if (actionSelect) {
                actionSelect.value = '';
            }
        } catch (error) {
            console.error('Error in showForwardModal:', error);
            alert('Terjadi error: ' + error.message);
        }
    }
    
    function closeForwardModal() {
        try {
            const modal = document.getElementById('forwardModal');
            if (modal) {
                modal.classList.add('hidden');
                modal.style.display = 'none';
            }
            const form = document.getElementById('forwardForm');
            if (form) {
                form.reset();
            }
            const submitBtn = document.getElementById('submitForwardBtn');
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fas fa-share me-2"></i>Teruskan';
                submitBtn.className = 'px-4 py-2 bg-purple-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-purple-600 focus:outline-none focus:ring-2 focus:ring-purple-300';
            }
            window.currentRequestId = null;
            currentRequestId = null;
        } catch (error) {
            console.error('Error in closeForwardModal:', error);
        }
    }
    
    // Pastikan fungsi tersedia secara global SEBELUM DOMContentLoaded
    window.showForwardModal = showForwardModal;
    window.closeForwardModal = closeForwardModal;
    
    async function approveRequest(id) {
        const comment = prompt('Masukkan komentar (opsional):');
        if (comment !== null) {
            try {
                const response = await fetch(`{{ url('admin/peminjaman-aset') }}/${id}/approve`, {
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
                const response = await fetch(`{{ url('admin/peminjaman-aset') }}/${id}/reject`, {
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
            const response = await fetch(`{{ url('admin/peminjaman-aset') }}/${id}/detail`);
            const data = await response.json();
            
            if (response.ok) {
                // Populate modal with data
                document.getElementById('detail-id').textContent = data.id;
                document.getElementById('detail-nama-instansi').textContent = data.nama_instansi;
                document.getElementById('detail-nama-peminjam').textContent = data.nama_peminjam;
                document.getElementById('detail-nip').textContent = data.nip;
                document.getElementById('detail-kabupaten-kota').textContent = data.kabupaten_kota;
                document.getElementById('detail-nama-gedung').textContent = data.jenis;
                document.getElementById('detail-tanggal-pinjaman').textContent = data.tanggal_pinjaman;
                document.getElementById('detail-tanggal-selesai').textContent = data.tanggal_selesai;
                document.getElementById('detail-jam-mulai').textContent = data.jam_mulai;
                document.getElementById('detail-jam-selesai').textContent = data.jam_selesai;
                
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
                
                // Show/hide surat upload section
                const suratSection = document.getElementById('detail-surat-section');
                const suratGeneratedSection = document.getElementById('detail-surat-generated-section');
                
                if (data.surat_path) {
                    // Show uploaded surat section
                    document.getElementById('detail-view-surat').href = `{{ url('admin/peminjaman-aset') }}/${id}/view-surat`;
                    document.getElementById('detail-download-surat').href = `{{ url('admin/peminjaman-aset') }}/${id}/download-surat`;
                    suratSection.classList.remove('hidden');
                    suratGeneratedSection.classList.add('hidden');
                } else {
                    // Show generated surat section
                    suratSection.classList.add('hidden');
                    suratGeneratedSection.classList.remove('hidden');
                    
                    // Store the ID for generated surat functions
                    window.currentDetailId = id;
                }
                
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

    // Close modal when clicking outside - pastikan element ada
    document.addEventListener('DOMContentLoaded', function() {
        const detailModal = document.getElementById('detailModal');
        if (detailModal) {
            detailModal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeDetailModal();
                }
            });
        }
    });

    // Fungsi untuk preview surat
    function previewSurat(id, jenis) {
        // Pastikan route ini sudah didefinisikan di routes/web.php
        const url = `{{ url('admin/surat/preview') }}/${jenis}/${id}`; 
        window.open(url, '_blank', 'width=800,height=600');
    }

    // Fungsi untuk download surat PDF
    function downloadSurat(id, jenis) {
        // Pastikan route ini sudah didefinisikan di routes/web.php
        const url = `{{ url('admin/surat') }}/${jenis}/${id}`;
        window.open(url, '_blank');
    }

    // Fungsi sudah didefinisikan di atas, tidak perlu didefinisikan lagi

    // Handle forward button click untuk peminjaman aset
    function handleForwardSubmitAset() {
        const form = document.getElementById('forwardForm');
        const requestId = window.currentRequestId || currentRequestId || form.getAttribute('data-request-id');
        
        if (!requestId) {
            alert('❌ Error: Request ID tidak ditemukan.');
            console.error('Request ID tidak ditemukan');
            return;
        }
        
        // Validasi form sebelum submit
        const actionSelect = document.getElementById('action');
        const komentarTextarea = document.getElementById('komentar');
        
        if (!actionSelect.value || actionSelect.value === '') {
            alert('❌ Silakan pilih keputusan terlebih dahulu!');
            actionSelect.focus();
            return;
        }
        
        if (!komentarTextarea.value || komentarTextarea.value.trim() === '') {
            alert('❌ Silakan isi komentar/keterangan terlebih dahulu!');
            komentarTextarea.focus();
            return;
        }
        
        const formData = new FormData(form);
        const submitBtn = document.getElementById('submitForwardBtn');
        const originalText = submitBtn.innerHTML;
        
        // Show loading state
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';
        submitBtn.disabled = true;
        
        // Gunakan route yang benar dengan ID
        const routeUrl = `{{ url('admin/peminjaman-aset') }}/${requestId}/forward`;
        console.log('Forward URL:', routeUrl);
        console.log('Request ID:', requestId);
        console.log('Form Data:', Object.fromEntries(formData));
        
        fetch(routeUrl, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(async response => {
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);
            
            // Check if response is JSON
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                const text = await response.text();
                console.error('Non-JSON response:', text);
                throw new Error('Server mengembalikan response yang tidak valid. Silakan refresh halaman dan coba lagi.');
            }
            
            const data = await response.json();
            console.log('Response data:', data);
            
            if (!response.ok) {
                // Handle error responses
                if (data.status === 'already_forwarded') {
                    alert('⚠️ ' + data.message);
                } else if (data.status === 'invalid_status') {
                    alert('❌ ' + data.message);
                } else if (data.status === 'validation_error' && data.errors) {
                    // Handle validation errors
                    let errorMessages = [];
                    for (const field in data.errors) {
                        errorMessages.push(data.errors[field][0]);
                    }
                    alert('❌ Validasi gagal:\n' + errorMessages.join('\n'));
                } else {
                    alert('❌ ' + (data.message || 'Gagal memproses permintaan.'));
                }
                return;
            }
            
            // Handle success responses
            if (data.status === 'success') {
                alert('✅ ' + (data.message || 'Permintaan berhasil diproses.'));
                closeForwardModal();
                setTimeout(() => {
                    location.reload();
                }, 500);
            } else {
                alert('❌ ' + (data.message || 'Gagal memproses permintaan.'));
            }
        })
        .catch(error => {
            console.error('Error details:', error);
            alert('❌ Terjadi kesalahan saat memproses permintaan: ' + (error.message || 'Silakan coba lagi atau hubungi administrator.'));
        })
        .finally(() => {
            // Reset button state
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
    }
    
    // Event listener untuk close modal ketika klik di luar modal
    document.addEventListener('DOMContentLoaded', function() {
        const forwardModal = document.getElementById('forwardModal');
        if (forwardModal) {
            forwardModal.addEventListener('click', function(e) {
                if (e.target === forwardModal) {
                    closeForwardModal();
                }
            });
        }
        
        // Pastikan fungsi handleForwardSubmitAset tersedia secara global
        window.handleForwardSubmitAset = handleForwardSubmitAset;
        
        console.log('Forward modal functions initialized');
        console.log('showForwardModal available:', typeof window.showForwardModal);
        console.log('closeForwardModal available:', typeof window.closeForwardModal);
    });
    
    // Update button berdasarkan action - pastikan element ada dulu
    document.addEventListener('DOMContentLoaded', function() {
        const actionSelect = document.getElementById('action');
        if (actionSelect) {
            actionSelect.addEventListener('change', function() {
                const submitBtn = document.getElementById('submitForwardBtn');
                if (!submitBtn) return;
                
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
        }
    });

    // Fungsi untuk menampilkan modal upload surat persetujuan - Pastikan tersedia global
    function showUploadSuratModal(requestId, jenisSurat) {
        console.log('showUploadSuratModal called', { requestId, jenisSurat });
        try {
            const requestIdInput = document.getElementById('upload_request_id');
            const jenisSuratInput = document.getElementById('upload_jenis_surat');
            const modal = document.getElementById('uploadSuratModal');
            
            if (!requestIdInput || !jenisSuratInput || !modal) {
                console.error('Modal elements not found', {
                    requestIdInput: !!requestIdInput,
                    jenisSuratInput: !!jenisSuratInput,
                    modal: !!modal
                });
                alert('Error: Elemen modal tidak ditemukan. Silakan refresh halaman.');
                return;
            }
            
            requestIdInput.value = requestId;
            jenisSuratInput.value = jenisSurat;
            modal.classList.remove('hidden');
            
            // Reset form fields (kecuali request_id dan jenis_surat)
            const form = document.getElementById('uploadSuratForm');
            if (form) {
                const nomorSurat = document.getElementById('upload_nomor_surat');
                const keterangan = document.getElementById('upload_keterangan');
                const fileInput = document.getElementById('upload_file');
                
                if (nomorSurat) nomorSurat.value = '';
                if (keterangan) keterangan.value = '';
                if (fileInput) fileInput.value = '';
            }
            
            console.log('Modal opened successfully');
        } catch (error) {
            console.error('Error opening upload modal:', error);
            alert('Terjadi error saat membuka modal: ' + error.message);
        }
    }
    
    // Fungsi untuk menutup modal upload surat persetujuan - Pastikan tersedia global
    function closeUploadSuratModal() {
        try {
            const modal = document.getElementById('uploadSuratModal');
            const form = document.getElementById('uploadSuratForm');
            
            if (modal) {
                modal.classList.add('hidden');
            }
            
            if (form) {
                form.reset();
                // Pastikan request_id dan jenis_surat juga direset
                const requestIdInput = document.getElementById('upload_request_id');
                const jenisSuratInput = document.getElementById('upload_jenis_surat');
                if (requestIdInput) requestIdInput.value = '';
                if (jenisSuratInput) jenisSuratInput.value = '';
            }
        } catch (error) {
            console.error('Error closing upload modal:', error);
        }
    }
    
    // Pastikan fungsi tersedia secara global
    window.showUploadSuratModal = showUploadSuratModal;
    window.closeUploadSuratModal = closeUploadSuratModal;

    // Fungsi untuk preview surat generated dari modal
    function previewSuratFromModal() {
        if (window.currentDetailId) {
            previewSurat(window.currentDetailId, 'peminjaman-aset');
        }
    }

    // Fungsi untuk download surat generated dari modal
    function downloadSuratFromModal() {
        if (window.currentDetailId) {
            downloadSurat(window.currentDetailId, 'peminjaman-aset');
        }
    }

    // Handle form submit upload surat persetujuan - dipanggil setelah DOM ready
    document.addEventListener('DOMContentLoaded', function() {
        const uploadForm = document.getElementById('uploadSuratForm');
        if (!uploadForm) {
            console.error('Upload form tidak ditemukan!');
            return;
        }
        
        uploadForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const form = this;
            const formData = new FormData(form);
            
            // Validasi form sebelum submit
            const fileInput = document.getElementById('upload_file');
            const requestIdInput = document.getElementById('upload_request_id');
            const jenisSuratInput = document.getElementById('upload_jenis_surat');
            
            if (!fileInput) {
                alert('❌ Error: File input tidak ditemukan. Silakan refresh halaman.');
                return;
            }
            
            if (!requestIdInput) {
                alert('❌ Error: Request ID input tidak ditemukan. Silakan refresh halaman.');
                return;
            }
            
            if (!jenisSuratInput) {
                alert('❌ Error: Jenis surat input tidak ditemukan. Silakan refresh halaman.');
                return;
            }
            
            const requestId = requestIdInput.value;
            const jenisSurat = jenisSuratInput.value;
            
            if (!requestId || requestId === '' || requestId === '0') {
                alert('❌ Error: Request ID tidak ditemukan. Silakan refresh halaman dan coba lagi.');
                return;
            }
            
            if (!jenisSurat || jenisSurat === '') {
                alert('❌ Error: Jenis surat tidak ditemukan. Silakan refresh halaman dan coba lagi.');
                return;
            }
            
            if (!fileInput.files || fileInput.files.length === 0) {
                alert('❌ Silakan pilih file surat persetujuan terlebih dahulu!');
                fileInput.focus();
                return;
            }
            
            // Validasi ukuran file (max 10MB)
            const fileSize = fileInput.files[0].size;
            const maxSize = 10 * 1024 * 1024; // 10MB
            if (fileSize > maxSize) {
                alert('❌ Ukuran file terlalu besar! Maksimum ukuran file adalah 10MB.');
                fileInput.focus();
                return;
            }
            
            // Validasi tipe file
            const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
            const fileType = fileInput.files[0].type;
            const fileName = fileInput.files[0].name.toLowerCase();
            const hasValidExtension = fileName.endsWith('.pdf') || fileName.endsWith('.doc') || fileName.endsWith('.docx');
            
            if (!allowedTypes.includes(fileType) && !hasValidExtension) {
                alert('❌ Format file tidak diperbolehkan! Format yang diperbolehkan: PDF, DOC, DOCX');
                fileInput.focus();
                return;
            }
            
            // Show loading state
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn ? submitBtn.innerHTML : '';
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengupload...';
                submitBtn.disabled = true;
            }
            
            try {
                console.log('Starting upload...', {
                    request_id: requestId,
                    jenis_surat: jenisSurat,
                    file_name: fileInput.files[0].name,
                    file_size: fileSize
                });
                
                // Buat AbortController untuk timeout
                const controller = new AbortController();
                const timeoutId = setTimeout(() => controller.abort(), 60000); // 60 detik timeout
                
                const response = await fetch('{{ route("admin.surat-persetujuan.store") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    signal: controller.signal
                });
                
                clearTimeout(timeoutId);
                console.log('Response status:', response.status);
                
                if (response.ok) {
                    let responseData = null;
                    try {
                        responseData = await response.json();
                        console.log('Upload success:', responseData);
                    } catch (e) {
                        // Jika bukan JSON, anggap sukses dan reload
                        console.log('Response is not JSON, assuming success');
                    }
                    
                    alert('✅ Surat persetujuan berhasil diupload!');
                    closeUploadSuratModal();
                    // Reload halaman untuk refresh data surat persetujuan
                    setTimeout(() => {
                        window.location.reload();
                    }, 300);
                } else {
                    // Error handling
                    let errorMessage = 'Gagal mengupload surat persetujuan';
                    
                    try {
                        const errorData = await response.json();
                        if (errorData.message) {
                            errorMessage = errorData.message;
                        } else if (errorData.errors) {
                            const errorMessages = [];
                            for (const field in errorData.errors) {
                                if (Array.isArray(errorData.errors[field])) {
                                    errorMessages.push(errorData.errors[field].join(', '));
                                } else {
                                    errorMessages.push(errorData.errors[field]);
                                }
                            }
                            errorMessage = '❌ ' + errorMessages.join('\n');
                        }
                    } catch (e) {
                        errorMessage = `Gagal mengupload. Status: ${response.status}`;
                    }
                    
                    console.error('Upload failed:', errorMessage);
                    alert(errorMessage);
                    if (submitBtn) {
                        submitBtn.innerHTML = originalBtnText;
                        submitBtn.disabled = false;
                    }
                }
            } catch (error) {
                console.error('Upload error:', error);
                let errorMsg = '❌ Terjadi kesalahan saat mengupload surat persetujuan';
                
                if (error.name === 'AbortError') {
                    errorMsg = '❌ Upload timeout setelah 60 detik! File mungkin terlalu besar atau koneksi lambat. Silakan coba lagi dengan file yang lebih kecil atau periksa koneksi internet.';
                } else if (error.message) {
                    errorMsg += ': ' + error.message;
                }
                
                alert(errorMsg + '\n\nSilakan coba lagi atau hubungi administrator.');
                if (submitBtn) {
                    submitBtn.innerHTML = originalBtnText;
                    submitBtn.disabled = false;
                }
            }
        });
        
        console.log('Upload form event listener attached');
        
        // Close modal when clicking outside
        const uploadModal = document.getElementById('uploadSuratModal');
        if (uploadModal) {
            uploadModal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeUploadSuratModal();
                }
            });
        }
    });
</script>
@endpush
