@extends('layouts.layout')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="bg-blue-100 p-2 rounded-lg">
                        <i class="fas fa-car text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Detail Peminjaman Kendaraan</h1>
                        <p class="text-gray-600">ID: #{{ $request->id }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="px-3 py-1 rounded-full text-sm font-medium
                        @if($request->status === 'approved') bg-green-100 text-green-800
                        @elseif($request->status === 'rejected') bg-red-100 text-red-800
                        @elseif($request->status === 'diteruskan_ke_kasubag') bg-blue-100 text-blue-800
                        @else bg-yellow-100 text-yellow-800 @endif">
                        @if($request->status === 'approved') ✅ Diterima
                        @elseif($request->status === 'rejected') ❌ Ditolak
                        @elseif($request->status === 'diteruskan_ke_kasubag') 📋 Diteruskan ke Kasubag
                        @else ⏳ Menunggu Review
                        @endif
                    </span>
                </div>
            </div>
        </div>

        <!-- Detail Information -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Informasi Kendaraan -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-car text-blue-600 mr-2"></i>
                    Informasi Kendaraan
                </h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Kendaraan</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $request->nama_kendaraan }}</p>
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
                        <label class="block text-sm font-medium text-gray-700">Nomor Polisi</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $request->nomor_polisi }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tahun</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $request->tahun }}</p>
                    </div>
                </div>
            </div>

            <!-- Informasi Peminjaman -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-calendar text-green-600 mr-2"></i>
                    Informasi Peminjaman
                </h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal Permintaan</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $request->created_at->translatedFormat('d F Y, H:i') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tujuan</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $request->tujuan }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <p class="mt-1 text-sm text-gray-900">
                            @if($request->status === 'approved') Diterima
                            @elseif($request->status === 'rejected') Ditolak
                            @elseif($request->status === 'diteruskan_ke_kasubag') Diteruskan ke Kasubag
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

        <!-- Informasi User -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mt-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-user text-purple-600 mr-2"></i>
                Informasi Pemohon
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $request->user->nama_lengkap ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $request->user->email ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Username</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $request->user->username ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Role</label>
                    <p class="mt-1 text-sm text-gray-900">{{ ucfirst($request->user->role ?? 'N/A') }}</p>
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
                        <p class="text-sm text-green-700 mt-1">
                            <span class="font-medium">Uploaded by:</span> {{ $request->suratPersetujuan->uploadedBy->nama_lengkap ?? 'N/A' }}
                        </p>
                        <p class="text-sm text-green-700 mt-1">
                            <span class="font-medium">Tanggal Upload:</span> {{ $request->suratPersetujuan->created_at->translatedFormat('d F Y, H:i') }}
                        </p>
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
                <p class="text-sm text-gray-500 mt-1">Upload surat persetujuan melalui halaman manajemen peminjaman kendaraan</p>
            </div>
        </div>
        @endif

        <!-- Action Buttons -->
        <div class="flex justify-end space-x-3 mt-6">
            <a href="{{ route('admin.peminjaman-kendaraan') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali ke Manajemen
            </a>
            @if(!$request->suratPersetujuan)
            <button onclick="showUploadModal({{ $request->id }})" 
                    class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                <i class="fas fa-upload mr-2"></i>
                Upload Surat Persetujuan
            </button>
            @endif
        </div>
    </div>
</div>

<!-- Upload Modal -->
<div id="uploadModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Upload Surat Persetujuan</h3>
                <form id="uploadForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="jenis_surat" value="SPK">
                    <input type="hidden" name="request_id" id="request_id">
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Surat</label>
                        <input type="text" name="nomor_surat" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Contoh: SPK/001/2025">
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">File Surat</label>
                        <input type="file" name="file" accept=".pdf,.doc,.docx" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        <p class="text-xs text-gray-500 mt-1">Format: PDF, DOC, DOCX (Max: 10MB)</p>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                        <textarea name="keterangan" rows="3" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Keterangan tambahan (opsional)"></textarea>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeUploadModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Upload
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function showUploadModal(requestId) {
    document.getElementById('request_id').value = requestId;
    document.getElementById('uploadModal').classList.remove('hidden');
}

function closeUploadModal() {
    document.getElementById('uploadModal').classList.add('hidden');
    document.getElementById('uploadForm').reset();
}

// Handle form submission
document.getElementById('uploadForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    try {
        const response = await fetch('{{ route("admin.surat-persetujuan.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        if (response.ok) {
            alert('Surat persetujuan berhasil diupload!');
            location.reload();
        } else {
            const errorData = await response.json();
            alert('Gagal upload: ' + (errorData.message || 'Terjadi kesalahan'));
        }
    } catch (error) {
        alert('Terjadi kesalahan saat upload surat persetujuan.');
        console.error(error);
    }
});

// Close modal when clicking outside
document.getElementById('uploadModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeUploadModal();
    }
});
</script>
@endsection
