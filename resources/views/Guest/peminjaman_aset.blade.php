@extends('layouts.layout')
@section('title', 'Peminjaman Aset')
@section('Peminjaman Aset', 'active')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg card-shadow p-6 mb-6">
        <h3 class="text-lg font-bold mb-4">🏢 Form Permintaan Peminjaman Aset</h3>

        <form id="assetRequestForm" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-2">Nama Instansi *</label>
                <input type="text" name="nama_instansi" placeholder="Contoh: Dinas Pendidikan, Pemda Gorontalo, Universitas" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">Nama Peminjam *</label>
                <input type="text" name="nama_peminjam" placeholder="Nama lengkap peminjam" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">NIP *</label>
                <input type="text" name="nip" placeholder="Nomor Induk Pegawai" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">Kabupaten/Kota *</label>
                <input type="text" name="kabupaten_kota" placeholder="Contoh: Gorontalo, Bone Bolango, Pohuwato" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">Nama Gedung *</label>
                <select name="jenis" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="">Pilih</option>
                    <option value="Ruang Pertemuan Aula Dulohupa">Ruang Pertemuan (Aula Dulohupa)</option>
                    <option value="Ruang Pertemuan Ruang Ponuwa">Ruang Pertemuan (Ruang Ponuwa)</option>
                    <option value="Ruang Belajar Tinelo 1,2,3,4,5">Ruang Belajar (Tinelo 1,2,3,4,5)</option>
                    <option value="Ruang Belajar Tinelo 1,2,3">Ruang Belajar (Tinelo 1,2,3)</option>
                    <option value="Ruang Makan Olamita 1">Ruang Makan (Olamita 1)</option>
                    <option value="Ruang Makan Olamita 2">Ruang Makan (Olamita 2)</option>
                    <option value="Asrama Beledaa 1,2,3">Asrama Beledaa 1,2,3</option>
                    <option value="Asrama Beledaa 4">Asrama Beledaa 4</option>
                    <option value="Mess Bandayo Daa">Mess Bandayo Daa</option>
                    <option value="Mess Bandayo Kiki 1">Mess Bandayo Kiki 1</option>
                    <option value="Mess Bandayo Kiki 2">Mess Bandayo Kiki 2</option>
                    <option value="Mess Bandayo Kiki 3,4">Mess Bandayo Kiki 3,4</option>
                    <option value="Lapangan Volly/Takraw">Lapangan Volly/Takraw</option>
                    <option value="Lapangan Tenis">Lapangan Tenis</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">Tanggal Pinjaman *</label>
                <input type="date" name="tanggal_pinjaman" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" min="{{ date('Y-m-d') }}" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">Tanggal Selesai *</label>
                <input type="date" name="tanggal_selesai" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" min="{{ date('Y-m-d') }}" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">Jam Mulai *</label>
                <input type="time" name="jam_mulai" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">Jam Selesai *</label>
                <input type="time" name="jam_selesai" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-2">Upload Surat *</label>
                <input type="file" name="surat" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <p class="text-xs text-gray-500 mt-1">Format: PDF, DOC, DOCX, JPG, PNG (Maks 5MB)</p>
            </div>
            <div class="md:col-span-2 flex items-end">
                <button type="submit" class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 transition-all font-semibold">
                    <i class="fas fa-paper-plane mr-2"></i>Kirim Permintaan Peminjaman Aset
                </button>
            </div>
        </form>

        <div class="mt-4 p-3 bg-blue-50 rounded-lg">
            <p class="text-sm text-blue-700">
                <i class="fas fa-info-circle mr-1"></i>
                <strong>Catatan:</strong> Pastikan semua data sudah benar sebelum mengirim permintaan. Admin akan meninjau dan memberikan feedback dalam 1-2 hari kerja.
            </p>
        </div>
    </div>

    <div class="bg-white rounded-lg card-shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold">📋 Histori Peminjaman Aset</h3>
            <div class="text-sm text-gray-600">
                Total: {{ $assetRequests->count() }} permintaan
            </div>
        </div>

        @if ($assetRequests->isEmpty())
            <div class="text-center py-12">
                <i class="fas fa-building text-gray-400 text-5xl mb-4"></i>
                <h4 class="text-lg font-medium text-gray-600 mb-2">Belum Ada Permintaan</h4>
                <p class="text-gray-500 mb-4">Mulai buat permintaan peminjaman aset pertama Anda</p>
                <button onclick="document.getElementById('assetRequestForm').scrollIntoView()" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i>Buat Permintaan
                </button>
            </div>
        @else
            <div class="space-y-4 max-h-96 overflow-y-auto">
                @foreach ($assetRequests as $request)
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
                                <p class="text-sm text-gray-600"><strong>NIP:</strong> {{ $request->nip }}</p>
                                <p class="text-sm text-gray-600"><strong>Jenis:</strong> {{ $request->jenis }}</p>
                                @if($request->surat_path)
                                <p class="text-sm text-green-600 mt-1">
                                    <i class="fas fa-file-alt mr-1"></i>Surat terupload
                                </p>
                                @endif
                            </div>
                            <div>
                                <p class="text-sm text-gray-600"><strong>Tanggal:</strong> {{ $request->tanggal_pinjaman ? \Carbon\Carbon::parse($request->tanggal_pinjaman)->translatedFormat('d F Y') : 'N/A' }}</p>
                                <p class="text-sm text-gray-600"><strong>Waktu:</strong> {{ $request->jam_mulai ? \Carbon\Carbon::parse($request->jam_mulai)->format('H:i') : 'N/A' }} - {{ $request->jam_selesai ? \Carbon\Carbon::parse($request->jam_selesai)->format('H:i') : 'N/A' }}</p>
                                <p class="text-sm text-gray-600"><strong>Status:</strong>
                                    @if ($request->status === 'approved') Diterima Admin
                                    @elseif ($request->status === 'rejected') Ditolak Admin
                                    @else Menunggu Review @endif
                                </p>
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

                        <div class="mt-3 flex justify-end">
                            <button onclick="showDetailModal({{ $request->id }})" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                <i class="fas fa-eye mr-1"></i>Lihat Detail
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    </div>

    <!-- Modal Detail Peminjaman Aset -->
    <div id="detailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-10 mx-auto p-4 border max-w-3xl w-11/12 shadow-lg rounded-md bg-white max-h-[90vh] overflow-y-auto">
            <div class="mt-2">
                <div class="flex justify-between items-center pb-3 border-b">
                    <h3 class="text-lg font-bold text-gray-900">Detail Peminjaman Aset</h3>
                    <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="mt-4 px-4 py-2">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">ID Request</label>
                            <p id="detail-id" class="text-sm text-gray-900 font-medium"></p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Nama Instansi</label>
                            <p id="detail-nama-instansi" class="text-sm text-gray-900"></p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Nama Peminjam</label>
                            <p id="detail-nama-peminjam" class="text-sm text-gray-900"></p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">NIP</label>
                            <p id="detail-nip" class="text-sm text-gray-900"></p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Kabupaten/Kota</label>
                            <p id="detail-kabupaten-kota" class="text-sm text-gray-900"></p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Jenis Ruang</label>
                            <p id="detail-jenis" class="text-sm text-gray-900"></p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                            <p id="detail-status" class="text-sm"></p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Tanggal Pinjaman</label>
                            <p id="detail-tanggal-pinjaman" class="text-sm text-gray-900"></p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Tanggal Selesai</label>
                            <p id="detail-tanggal-selesai" class="text-sm text-gray-900"></p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Jam Mulai</label>
                            <p id="detail-jam-mulai" class="text-sm text-gray-900"></p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Jam Selesai</label>
                            <p id="detail-jam-selesai" class="text-sm text-gray-900"></p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Tanggal Request</label>
                            <p id="detail-tanggal-request" class="text-sm text-gray-900"></p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Admin Approval</label>
                            <p id="detail-admin" class="text-sm text-gray-900"></p>
                        </div>
                    </div>
                    <div id="detail-surat-section" class="mt-3 hidden">
                        <label class="block text-xs font-medium text-gray-700 mb-2">Surat yang Diupload</label>
                        <div class="p-3 bg-blue-50 rounded-md border-l-4 border-blue-400">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center flex-1 min-w-0">
                                    <i class="fas fa-file-alt text-blue-600 mr-2 flex-shrink-0"></i>
                                    <span class="text-sm font-medium text-blue-800 truncate" id="detail-surat-name"></span>
                                </div>
                                <span class="text-xs text-blue-600 ml-2 flex-shrink-0" id="detail-surat-date"></span>
                            </div>
                            <p class="text-xs text-blue-700 mb-3">Surat yang Anda upload saat mengajukan permintaan</p>
                            <div class="flex space-x-2">
                                <a id="detail-view-surat" href="#" target="_blank" class="px-3 py-1.5 bg-blue-500 text-white rounded hover:bg-blue-600 text-xs transition-colors">
                                    <i class="fas fa-eye mr-1"></i>Lihat
                                </a>
                                <a id="detail-download-surat" href="#" class="px-3 py-1.5 bg-purple-500 text-white rounded hover:bg-purple-600 text-xs transition-colors">
                                    <i class="fas fa-download mr-1"></i>Download
                                </a>
                            </div>
                        </div>
                    </div>
                    <div id="admin-comment" class="mt-3 hidden">
                        <label class="block text-xs font-medium text-gray-700 mb-2">Komentar Admin</label>
                        <div id="detail-komentar" class="p-3 bg-blue-50 rounded-md border-l-4 border-blue-400 text-sm text-gray-700">
                        </div>
                    </div>
                </div>
                <div class="flex justify-end pt-3 mt-3 border-t">
                    <button onclick="closeDetailModal()" class="px-4 py-2 bg-gray-500 text-white text-sm font-medium rounded-md shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-colors">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.getElementById('assetRequestForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        try {
            const response = await fetch('{{ route("guest.asset-request.store") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            });

            const data = await response.json();

            if (response.ok) {
                alert('Permintaan peminjaman aset berhasil dikirim!');
                location.reload();
            } else {
                if (data.errors) {
                    let errorMessage = 'Gagal mengirim permintaan:\n';
                    for (const field in data.errors) {
                        errorMessage += `- ${data.errors[field][0]}\n`;
                    }
                    alert(errorMessage);
                } else {
                    alert('Gagal mengirim permintaan: ' + (data.message || 'Terjadi kesalahan'));
                }
            }
        } catch (error) {
            alert('Terjadi kesalahan saat mengirim permintaan.');
            console.error(error);
        }
    });

    // Validasi jam selesai harus setelah jam mulai
    document.querySelector('input[name="jam_mulai"]').addEventListener('change', function() {
        const jamMulai = this.value;
        const jamSelesai = document.querySelector('input[name="jam_selesai"]');
        jamSelesai.min = jamMulai;
        if (jamSelesai.value && jamSelesai.value <= jamMulai) {
            jamSelesai.value = '';
        }
    });

    // Validasi tanggal selesai harus setelah tanggal pinjaman
    document.querySelector('input[name="tanggal_pinjaman"]').addEventListener('change', function() {
        const tanggalPinjaman = this.value;
        const tanggalSelesai = document.querySelector('input[name="tanggal_selesai"]');
        tanggalSelesai.min = tanggalPinjaman;
        if (tanggalSelesai.value && tanggalSelesai.value < tanggalPinjaman) {
            tanggalSelesai.value = '';
        }
    });

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
                document.getElementById('detail-jenis').textContent = data.jenis;

                // Set jenis with color
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
</script>
@endpush
