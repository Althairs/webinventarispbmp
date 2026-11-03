@extends('layouts.layout') {{-- Asumsikan ini adalah layout utama Anda --}}
@section('title', 'User Dashboard')
@section('User Dashboard', 'active')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg p-6 text-white mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold mb-2">Selamat Datang, {{ Auth::user()->nama_lengkap }}! 👋</h2>
                <p class="text-blue-100">Kelola permintaan barang Anda dengan mudah melalui sistem inventaris BPMP Provinsi Gorontalo</p>
            </div>
            <div class="text-right">
                <div class="mb-2">
                    <a href="{{ route('user.settings') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-cog mr-2"></i>Pengaturan Akun
                    </a>
                </div>
                <p class="text-blue-100 text-sm">Hari ini</p>
                <p class="text-xl font-bold">{{ now()->translatedFormat('l, d F Y') }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg card-shadow border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Total Permintaan</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $stats['totalRequests'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">Semua waktu</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-clipboard-list text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white p-6 rounded-lg card-shadow border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Menunggu Review</p>
                    <p class="text-3xl font-bold text-yellow-600">{{ $stats['MenungguRequests'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">Sedang diproses</p>
                </div>
                <div class="bg-yellow-100 p-3 rounded-full">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white p-6 rounded-lg card-shadow border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Diterima</p>
                    <p class="text-3xl font-bold text-green-600">{{ $stats['approvedRequests'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">Berhasil Diterima</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white p-6 rounded-lg card-shadow border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Ditolak</p>
                    <p class="text-3xl font-bold text-red-600">{{ $stats['rejectedRequests'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">Perlu perbaikan</p>
                </div>
                <div class="bg-red-100 p-3 rounded-full">
                    <i class="fas fa-times-circle text-red-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Surat Persetujuan Card -->
    @if(isset($stats['totalSuratPersetujuan']) && $stats['totalSuratPersetujuan'] > 0)
    <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-6 text-white mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="bg-white bg-opacity-20 rounded-full p-3 mr-4">
                    <i class="fas fa-file-pdf text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold mb-1">Surat Persetujuan Tersedia!</h3>
                    <p class="text-green-100">Anda memiliki {{ $stats['totalSuratPersetujuan'] }} surat persetujuan yang dapat diunduh</p>
                </div>
            </div>
            <div class="text-right">
                <a href="{{ route('user.histori-permintaan') }}" 
                   class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-eye mr-2"></i>Lihat Histori
                </a>
            </div>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-lg card-shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-800">📋 Permintaan Terbaru</h3>
                {{-- PERBAIKAN: Menggunakan $stats['totalRequests'] --}}
                <span class="text-sm text-gray-500">{{ $stats['totalRequests'] }} total permintaan</span>
            </div>
            
            @if ($recentRequests->isEmpty())
                <div class="text-center py-12">
                    <i class="fas fa-inbox text-gray-400 text-5xl mb-4"></i>
                    <h4 class="text-lg font-medium text-gray-600 mb-2">Belum Ada Permintaan</h4>
                    <p class="text-gray-500 mb-4">Mulai buat permintaan barang pertama Anda</p>
                    <a href="{{ route('user.request.create') }}" class="btn-primary text-white px-6 py-2 rounded-lg hover:opacity-90">
                        <i class="fas fa-plus mr-2"></i>Buat Permintaan
                    </a>
                </div>
            @else
                <div class="space-y-4 max-h-80 overflow-y-auto">
                    @foreach ($recentRequests as $req)
                        <div class="border rounded-lg p-4 hover:shadow-md transition-all
                            @if ($req->status === 'approved') bg-green-50 border-green-200
                            @elseif ($req->status === 'rejected') bg-red-50 border-red-200
                            @else bg-yellow-50 border-yellow-200 @endif">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    @php
                                        // Tentukan jenis permintaan dan data yang akan ditampilkan
                                        $requestType = '';
                                        $requestTitle = '';
                                        $requestDetail = '';
                                        
                                        if (isset($req->nama_kendaraan)) {
                                            $requestType = 'Peminjaman Kendaraan';
                                            $requestTitle = $req->nama_kendaraan;
                                            $requestDetail = $req->kategori . ' • ' . $req->merek;
                                        } elseif (isset($req->nama_instansi)) {
                                            $requestType = 'Peminjaman Aset';
                                            $requestTitle = $req->nama_instansi;
                                            $requestDetail = $req->nama_peminjam . ' • ' . $req->nama_barang;
                                        } elseif (isset($req->kategori) && isset($req->merek)) {
                                            $requestType = 'Peminjaman Barang';
                                            $requestTitle = $req->nama_barang;
                                            $requestDetail = $req->kategori . ' • ' . $req->merek;
                                        } else {
                                            $requestType = 'Permintaan Persediaan';
                                            $requestTitle = $req->nama_barang ?? 'Permintaan #' . $req->id;
                                            $requestDetail = 'Detail permintaan';
                                        }
                                    @endphp
                                    <h4 class="font-semibold text-gray-800">{{ $requestTitle }}</h4>
                                    <p class="text-sm text-gray-600">{{ $requestDetail }}</p>
                                    <p class="text-xs text-blue-600 font-medium">{{ $requestType }}</p>
                                </div>
                                <span class="px-3 py-1 rounded-full text-xs font-medium
                                    @if ($req->status === 'approved') bg-green-100 text-green-800
                                    @elseif ($req->status === 'rejected') bg-red-100 text-red-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ $req->status === 'approved' ? '✅ Diterima' : ($req->status === 'rejected' ? '❌ Ditolak' : '⏳ Menunggu') }}
                                </span>
                            </div>
                            <div class="flex justify-between text-sm text-gray-600">
                                <span>📅 {{ $req->created_at->translatedFormat('d F Y') }}</span>
                                <span>🕒 {{ $req->created_at->translatedFormat('H:i') }}</span>
                            </div>
                            @if ($req->komentar)
                                <div class="mt-3 p-2 bg-white rounded border-l-4
                                    @if ($req->status === 'approved') border-green-400
                                    @else border-red-400 @endif">
                                    <p class="text-xs font-medium text-gray-700">Komentar Admin:</p>
                                    <p class="text-sm text-gray-600">{{ $req->komentar }}</p>
                                </div>
                            @endif
                            
                            <div class="mt-3 flex justify-end space-x-2">
                                @php
                                    // Tentukan route dan parameter berdasarkan jenis permintaan
                                    $detailRoute = '';
                                    $suratId = null;
                                    
                                    if (isset($req->nama_kendaraan)) {
                                        // Peminjaman Kendaraan
                                        $detailRoute = route('user.user.peminjaman-kendaraan.detail', $req->id);
                                    } elseif (isset($req->nama_instansi)) {
                                        // Peminjaman Aset - redirect ke histori karena tidak ada route detail untuk user
                                        $detailRoute = route('user.histori-permintaan');
                                    } elseif (isset($req->kategori) && isset($req->merek)) {
                                        // Peminjaman Barang
                                        $detailRoute = route('user.user.peminjaman-barang.detail', $req->id);
                                    } else {
                                        // Permintaan Persediaan
                                        $detailRoute = route('user.user.permintaan-persediaan.detail', $req->id);
                                    }
                                    
                                    // Cari surat persetujuan yang terkait
                                    $suratPersetujuan = null;
                                    if (isset($req->suratPersetujuan)) {
                                        $suratPersetujuan = $req->suratPersetujuan;
                                    }
                                @endphp
                                
                                <a href="{{ $detailRoute }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    <i class="fas fa-eye mr-1"></i>Lihat Detail
                                </a>
                                
                                @if($suratPersetujuan)
                                    <a href="{{ route('surat-persetujuan.preview', $suratPersetujuan->id) }}" 
                                       target="_blank" 
                                       class="text-green-600 hover:text-green-800 text-sm font-medium">
                                        <i class="fas fa-file-alt mr-1"></i>Lihat Surat
                                    </a>
                                    <a href="{{ route('surat-persetujuan.download', $suratPersetujuan->id) }}" 
                                       class="text-purple-600 hover:text-purple-800 text-sm font-medium">
                                        <i class="fas fa-download mr-1"></i>Download PDF
                                    </a>
                                @else
                                    <span class="text-gray-400 text-sm font-medium">
                                        <i class="fas fa-file-alt mr-1"></i>Surat Belum Tersedia
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        
        <div class="bg-white rounded-lg card-shadow p-6">
            <h3 class="text-lg font-bold mb-4 text-gray-800">🚀 Menu Utama</h3>
            <div class="space-y-3">
                <a href="{{ route('user.request.create') }}" class="w-full btn-primary text-white py-4 rounded-lg hover:opacity-90 flex items-center justify-center text-sm font-medium transition-all">
                    <i class="fas fa-plus-circle mr-2"></i>Buat Permintaan Barang
                </a>
                <button onclick="logout()" class="w-full bg-red-500 hover:bg-red-600 text-white py-4 rounded-lg flex items-center justify-center text-sm font-medium transition-all">
                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                </button>
            </div>
            
            <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                <h4 class="font-medium text-blue-800 mb-2 flex items-center">
                    <i class="fas fa-info-circle mr-2"></i>Informasi Penting
                </h4>
                <ul class="text-sm text-blue-700 space-y-2">
                    <li class="flex items-start">
                        <i class="fas fa-check text-blue-500 mr-2 mt-0.5 text-xs"></i>
                        Permintaan akan diproses dalam 1-2 hari kerja
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-blue-500 mr-2 mt-0.5 text-xs"></i>
                        Pastikan data lengkap dan akurat
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-blue-500 mr-2 mt-0.5 text-xs"></i>
                        Hubungi admin jika ada pertanyaan
                    </li>
                </ul>
            </div>

            <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                <h4 class="font-medium text-gray-800 mb-3">📊 Ringkasan Status</h4>
                <div class="space-y-2">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">⏳ Menunggu</span>
                        <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs font-medium">{{ $stats['MenungguRequests'] }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">✅ Diterima</span>
                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-medium">{{ $stats['approvedRequests'] }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">❌ Ditolak</span>
                        <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs font-medium">{{ $stats['rejectedRequests'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Peminjaman Barang -->
<div id="detailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center pb-3">
                <h3 class="text-lg font-bold text-gray-900 flex items-center">
                    <i class="fas fa-box mr-2 text-blue-600"></i>
                    Detail Peminjaman Barang
                </h3>
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
                        <label class="block text-sm font-medium text-gray-700">Peminjam</label>
                        <p id="detail-peminjam" class="mt-1 text-sm text-gray-900"></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <p id="detail-status" class="mt-1 text-sm"></p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Deskripsi Peruntukan</label>
                        <p id="detail-deskripsi" class="mt-1 text-sm text-gray-900 p-3 bg-gray-50 rounded-md"></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal Request</label>
                        <p id="detail-tanggal" class="mt-1 text-sm text-gray-900"></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Admin Approval</label>
                        <p id="detail-admin" class="mt-1 text-sm text-gray-900"></p>
                    </div>
                </div>
                <div id="admin-comment" class="mt-4 hidden">
                    <label class="block text-sm font-medium text-gray-700">Komentar Admin</label>
                    <div id="detail-komentar" class="mt-1 p-3 bg-blue-50 rounded-md border-l-4 border-blue-400">
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
@endsection

@push('scripts')
<script>

    // Logout function
    function logout() {
        if (confirm('Apakah Anda yakin ingin logout?')) {
            window.location.href = '{{ route("logout") }}';
        }
    }

    // Fungsi untuk preview surat
    function previewSurat(id, jenis) {
        const url = `{{ url('user/surat/preview') }}/${jenis}/${id}`;
        window.open(url, '_blank', 'width=800,height=600');
    }

    // Fungsi untuk download surat PDF
    function downloadSurat(id, jenis) {
        const url = `{{ url('user/surat') }}/${jenis}/${id}`;
        window.open(url, '_blank');
    }
</script>
@endpush
