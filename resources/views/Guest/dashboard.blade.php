@extends('layouts.layout')
@section('title', 'Dashboard Tamu')
@section('Dashboard', 'active')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Welcome Banner -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-lg p-6 mb-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold mb-2">Selamat Datang, {{ $user->nama_lengkap }}!</h1>
                <p class="text-blue-200">Sebagai Tamu, Anda dapat mengajukan permintaan peminjaman aset</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-blue-200">Instansi: {{ $user->instansi }}</p>
                <p class="text-sm text-blue-200">NIP: {{ $user->nip }}</p>
                <a href="{{ route('guest.settings') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg transition-colors mt-2 inline-block">
                    <i class="fas fa-cog mr-2"></i>Pengaturan Akun
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-lg card-shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-full">
                    <i class="fas fa-clipboard-list text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-800">Total Permintaan</h3>
                    <p class="text-2xl font-bold text-blue-600">{{ $assetRequests->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg card-shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-full">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-800">Menunggu</h3>
                    <p class="text-2xl font-bold text-yellow-600">{{ $assetRequests->where('status', 'Menunggu')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg card-shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-full">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-800">Diterima</h3>
                    <p class="text-2xl font-bold text-green-600">{{ $assetRequests->where('status', 'approved')->count() }}</p>
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
                <a href="{{ route('guest.histori-peminjaman-aset') }}" 
                   class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-eye mr-2"></i>Lihat Histori
                </a>
            </div>
        </div>
    </div>
    @endif

    <!-- Poster Tarif Sewa Fasilitas -->
    <div class="bg-white rounded-lg card-shadow p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold">Tarif Sewa Fasilitas</h3>
        </div>
        <div class="w-full flex justify-center">
            <div class="max-w-4xl w-full">
                <img 
                    src="{{ asset('storage/poster-tarif-sewa-fasilitas.jpg') }}" 
                    alt="Tarif Sewa Fasilitas BPMP Provinsi Gorontalo" 
                    class="w-full h-auto rounded-lg shadow-md object-contain"
                    style="max-height: 800px;"
                >
                <p class="text-center text-sm text-gray-600 mt-3">
                    Informasi lengkap mengenai tarif sewa fasilitas yang tersedia di BPMP Provinsi Gorontalo
                </p>
            </div>
        </div>
    </div>

    <!-- Recent Requests -->
    <div class="bg-white rounded-lg card-shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold">Permintaan Terbaru</h3>
            <a href="{{ route('guest.peminjaman-aset') }}" class="text-blue-600 hover:text-blue-800 text-sm">Lihat Semua</a>
        </div>
        
        @if ($assetRequests->isEmpty())
            <div class="text-center py-12">
                <i class="fas fa-building text-gray-400 text-5xl mb-4"></i>
                <h4 class="text-lg font-medium text-gray-600 mb-2">Belum Ada Permintaan</h4>
                <p class="text-gray-500 mb-4">Mulai buat permintaan peminjaman aset pertama Anda</p>
                <a href="{{ route('guest.peminjaman-aset') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i>Buat Permintaan
                </a>
            </div>
        @else
            <div class="space-y-4">
                @foreach ($assetRequests->take(5) as $request)
                    <div class="border rounded-lg p-4 hover:shadow-md transition-shadow 
                        @if ($request->status === 'approved') border-green-200 bg-green-50
                        @elseif ($request->status === 'rejected') border-red-200 bg-red-50
                        @else border-yellow-200 bg-yellow-50 @endif">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h4 class="font-semibold text-gray-800">{{ $request->nama_barang }}</h4>
                                <p class="text-sm text-gray-600">{{ $request->nama_instansi }} • {{ $request->kabupaten_kota }}</p>
                            </div>
                            <span class="px-3 py-1 rounded-full text-sm font-medium
                                @if ($request->status === 'approved') bg-green-100 text-green-800
                                @elseif ($request->status === 'rejected') bg-red-100 text-red-800
                                @else bg-yellow-100 text-yellow-800 @endif">
                                {{ $request->status === 'approved' ? '✅ Diterima' : ($request->status === 'rejected' ? '❌ Ditolak' : '⏳ Menunggu') }}
                            </span>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 mb-3">
                            <div>
                                <p class="text-sm text-gray-600"><strong>Peminjam:</strong> {{ $request->nama_peminjam }}</p>
                                <p class="text-sm text-gray-600"><strong>Nama Gedung:</strong> {{ $request->nama_gedung }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600"><strong>Tanggal:</strong> {{ $request->tanggal_pinjaman ? \Carbon\Carbon::parse($request->tanggal_pinjaman)->translatedFormat('d F Y') : 'N/A' }}</p>
                                <p class="text-sm text-gray-600"><strong>Waktu:</strong> {{ $request->jam_mulai ? \Carbon\Carbon::parse($request->jam_mulai)->format('H:i') : 'N/A' }} - {{ $request->jam_selesai ? \Carbon\Carbon::parse($request->jam_selesai)->format('H:i') : 'N/A' }}</p>
                            </div>
                        </div>
                        
                        @if ($request->komentar)
                            <div class="mt-3 p-3 bg-white rounded border-l-4 
                                @if ($request->status === 'approved') border-green-400
                                @else border-red-400 @endif">
                                <p class="text-sm font-medium text-gray-700">Komentar Admin:</p>
                                <p class="text-sm text-gray-600 mt-1">{{ $request->komentar }}</p>
                            </div>
                        @endif
                        
                        <div class="mt-3 flex justify-end space-x-2">
                            <button onclick="showDetailModal({{ $request->id }})" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                <i class="fas fa-eye mr-1"></i>Lihat Detail
                            </button>
                            <!-- <button onclick="previewSurat({{ $request->id }}, 'peminjaman-aset')" class="text-green-600 hover:text-green-800 text-sm font-medium">
                                <i class="fas fa-file-alt mr-1"></i>Lihat Surat
                            </button>
                            <button onclick="downloadSurat({{ $request->id }}, 'peminjaman-aset')" class="text-purple-600 hover:text-purple-800 text-sm font-medium">
                                <i class="fas fa-download mr-1"></i>Download PDF
                            </button> -->
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
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
                        <label class="block text-sm font-medium text-gray-700">Nama Barang/Aset</label>
                        <p id="detail-nama-barang" class="mt-1 text-sm text-gray-900"></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Jenis</label>
                        <p id="detail-jenis" class="mt-1 text-sm"></p>
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
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal Request</label>
                        <p id="detail-tanggal-request" class="mt-1 text-sm text-gray-900"></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Admin Approval</label>
                        <p id="detail-admin" class="mt-1 text-sm text-gray-900"></p>
                    </div>
                </div>
                <div id="detail-surat-section" class="mt-4 hidden">
                    <label class="block text-sm font-medium text-gray-700">Surat yang Diupload</label>
                    <div class="mt-2 p-3 bg-blue-50 rounded-md border-l-4 border-blue-400">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center">
                                <i class="fas fa-file-alt text-blue-600 mr-2"></i>
                                <span class="text-sm font-medium text-blue-800" id="detail-surat-name"></span>
                            </div>
                            <span class="text-xs text-blue-600" id="detail-surat-date"></span>
                        </div>
                        <p class="text-xs text-blue-700 mb-3">Surat yang Anda upload saat mengajukan permintaan</p>
                        <div class="flex space-x-2">
                            <a id="detail-view-surat" href="#" target="_blank" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 text-xs">
                                <i class="fas fa-eye mr-1"></i>Lihat
                            </a>
                            <a id="detail-download-surat" href="#" class="px-3 py-1 bg-purple-500 text-white rounded hover:bg-purple-600 text-xs">
                                <i class="fas fa-download mr-1"></i>Download
                            </a>
                        </div>
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
    async function showDetailModal(id) {
        try {
            const response = await fetch(`{{ url('guest/peminjaman-aset') }}/${id}/ajax-detail`);
            const data = await response.json();
            
            if (response.ok) {
                // Populate modal with data
                document.getElementById('detail-id').textContent = data.id;
                document.getElementById('detail-nama-instansi').textContent = data.nama_instansi;
                document.getElementById('detail-nama-peminjam').textContent = data.nama_peminjam;
                document.getElementById('detail-nip').textContent = data.nip;
                document.getElementById('detail-kabupaten-kota').textContent = data.kabupaten_kota;
                document.getElementById('detail-nama-barang').textContent = data.nama_barang;
                
                // Set jenis with color
                const jenisElement = document.getElementById('detail-jenis');
                jenisElement.textContent = data.jenis;
                jenisElement.className = 'mt-1 text-sm px-2 py-1 rounded bg-purple-100 text-purple-800';
                
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
                
                // Format dates and times
                document.getElementById('detail-tanggal-pinjaman').textContent = new Date(data.tanggal_pinjaman).toLocaleDateString('id-ID');
                document.getElementById('detail-tanggal-selesai').textContent = new Date(data.tanggal_selesai).toLocaleDateString('id-ID');
                document.getElementById('detail-jam-mulai').textContent = data.jam_mulai;
                document.getElementById('detail-jam-selesai').textContent = data.jam_selesai;
                document.getElementById('detail-tanggal-request').textContent = new Date(data.created_at).toLocaleDateString('id-ID');
                document.getElementById('detail-admin').textContent = data.approved_by ? data.approved_by.nama_lengkap : (data.status === 'Menunggu' ? 'Belum diproses' : 'N/A');
                
                // Show/hide surat upload section
                const suratSection = document.getElementById('detail-surat-section');
                if (data.surat_path) {
                    document.getElementById('detail-surat-name').textContent = data.surat_path.split('/').pop();
                    document.getElementById('detail-surat-date').textContent = new Date(data.created_at).toLocaleDateString('id-ID');
                    document.getElementById('detail-view-surat').href = `{{ url('guest/peminjaman-aset') }}/${id}/view-surat`;
                    document.getElementById('detail-download-surat').href = `{{ url('guest/peminjaman-aset') }}/${id}/download-surat`;
                    suratSection.classList.remove('hidden');
                } else {
                    suratSection.classList.add('hidden');
                }
                
                // Show/hide admin comment
                if (data.komentar) {
                    document.getElementById('admin-comment').classList.remove('hidden');
                    document.getElementById('detail-komentar').textContent = data.komentar;
                } else {
                    document.getElementById('admin-comment').classList.add('hidden');
                }
                
                // Show modal
                document.getElementById('detailModal').classList.remove('hidden');
            } else {
                const errorData = await response.json();
                alert('Gagal mengambil detail data: ' + (errorData.error || 'Terjadi kesalahan'));
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
        const url = `{{ url('guest/surat/preview') }}/${jenis}/${id}`;
        window.open(url, '_blank', 'width=800,height=600');
    }

    // Fungsi untuk download surat PDF
    function downloadSurat(id, jenis) {
        const url = `{{ url('guest/surat') }}/${jenis}/${id}`;
        window.open(url, '_blank');
    }
</script>
@endpush
