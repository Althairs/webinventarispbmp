<?php

namespace App\Http\Controllers;

use App\Models\RequestModel;
use App\Models\PermintaanPersediaan;
use App\Models\PeminjamanKendaraan;
use App\Models\PeminjamanAset;
use App\Models\SuratPersetujuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class UserController extends Controller
{
    // public function __construct()
    // {

    //     $this->middleware('auth');
    //     $this->middleware('role:user');
    // }

    public function dashboard()
    {
        $userId = Auth::id();

        // Ambil semua jenis permintaan user dengan relasi surat persetujuan
        $peminjamanBarang = RequestModel::where('user_id', $userId)->with('suratPersetujuan')->get();
        $permintaanPersediaan = PermintaanPersediaan::where('user_id', $userId)->with('suratPersetujuan')->get();
        $peminjamanKendaraan = PeminjamanKendaraan::where('user_id', $userId)->with('suratPersetujuan')->get();
        $peminjamanAset = PeminjamanAset::where('user_id', $userId)->with('suratPersetujuan')->get();

        // Gabungkan semua permintaan
        $allRequests = $peminjamanBarang->concat($permintaanPersediaan)
                                       ->concat($peminjamanKendaraan)
                                       ->concat($peminjamanAset);

        // Ambil semua surat persetujuan user dengan cara yang lebih aman
        $suratPersetujuan = collect();

        try {
            // Ambil surat persetujuan dari peminjaman barang
            $spbRequestIds = $peminjamanBarang->pluck('id');
            $spbSurat = SuratPersetujuan::whereIn('request_id', $spbRequestIds)
                ->where('jenis_surat', 'SPB')->get();

            // Ambil surat persetujuan dari permintaan persediaan
            $sppRequestIds = $permintaanPersediaan->pluck('id');
            $sppSurat = SuratPersetujuan::whereIn('request_id', $sppRequestIds)
                ->where('jenis_surat', 'SPP')->get();

            // Ambil surat persetujuan dari peminjaman kendaraan
            $spkRequestIds = $peminjamanKendaraan->pluck('id');
            $spkSurat = SuratPersetujuan::whereIn('request_id', $spkRequestIds)
                ->where('jenis_surat', 'SPK')->get();

            // Ambil surat persetujuan dari peminjaman aset
            $spaRequestIds = $peminjamanAset->pluck('id');
            $spaSurat = SuratPersetujuan::whereIn('request_id', $spaRequestIds)
                ->where('jenis_surat', 'SPA')->get();

            $suratPersetujuan = $spbSurat->concat($sppSurat)->concat($spkSurat)->concat($spaSurat);

        } catch (\Exception $e) {
            // Jika ada error, gunakan collection kosong
            $suratPersetujuan = collect();
        }

        // Hitung statistik dari semua jenis permintaan
        $stats = [
            'totalRequests' => $allRequests->count(),
            'MenungguRequests' => $allRequests->where('status', 'Menunggu')->count(),
            'approvedRequests' => $allRequests->where('status', 'approved')->count(),
            'rejectedRequests' => $allRequests->where('status', 'rejected')->count(),
            'totalSuratPersetujuan' => $suratPersetujuan->count(),
        ];

        // Ambil permintaan terbaru untuk ditampilkan
        $recentRequests = $allRequests->sortByDesc('created_at')->take(5);

        return view('user.index', compact('recentRequests', 'stats', 'suratPersetujuan'));
    }


    //ini PEMINJAMAN BARANG
    public function requestBarang()
    {
        $userRequests = RequestModel::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.request_barang', compact('userRequests'));
    }

    /**
     * Query aset tetap yang boleh dipinjam melalui fitur peminjaman barang.
     * Kendaraan dan alat angkutan bermotor dikelola melalui fitur peminjaman kendaraan.
     */
    private function asetTetapYangDapatDipinjam()
    {
        $kategoriDikecualikan = ['alat angkutan bermotor', 'kendaraan'];

        return \App\Models\AsetTetap::query()
            ->where(function ($query) use ($kategoriDikecualikan) {
                $query->whereNull('kategori')
                    ->orWhereRaw(
                        'LOWER(TRIM(kategori)) NOT IN (?, ?)',
                        $kategoriDikecualikan
                    );
            });
    }

    // Tambahkan atau perbaiki metode ini
    public function createRequest()
    {
        // Data barang yang bisa dipinjam: aset tetap + persediaan tidak habis pakai
        $products = collect();

        // Data dari aset tetap (tanpa kendaraan/alat angkutan bermotor)
        $asetTetap = $this->asetTetapYangDapatDipinjam()
            ->orderBy('nama_barang')
            ->get();

        foreach ($asetTetap as $aset) {
            $products->push([
                'id' => $aset->id,
                'kode_barang' => $aset->kode_barang,
                'nama_barang' => $aset->nama_barang,
                'merek' => $aset->merek,
                'kategori' => $aset->kategori,
                'nup' => $aset->nup,
                'jumlah' => $aset->stok ?? $aset->jumlah ?? 0,
                'lokasi' => $aset->lokasi,
                'kondisi' => $aset->kondisi,
                'type' => 'aset_tetap'
            ]);
        }

        // Hanya ambil persediaan dengan jenis TIDAK HABIS PAKAI untuk peminjaman barang
        $persediaan = \App\Models\Persediaan::where('jenis', 'tidak_habis_pakai')
            ->orderBy('nama_barang')
            ->get();

        // Data dari persediaan (jenis tidak habis pakai)
        foreach($persediaan as $pers) {
            $products->push([
                'id' => $pers->id,
                'kode_barang' => $pers->kode_barang,
                'nama_barang' => $pers->nama_barang,
                'merek' => $pers->merek,
                'kategori' => $pers->kategori,
                'nup' => null,
                'jumlah' => $pers->stok ?? $pers->jumlah ?? 0,
                'lokasi' => null,
                'kondisi' => null,
                'type' => 'persediaan'
            ]);
        }

        // Ambil histori permintaan barang untuk user yang sedang login
        $userRequests = RequestModel::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        // Tampilkan view dan kirim data
        return view('user.create_request', compact('products', 'userRequests'));
    }

    /**
     * Menampilkan histori permintaan dengan surat persetujuan
     */
    public function historiPermintaan(Request $request)
    {
        $userId = Auth::id();

        // Pastikan per_page dan page adalah integer, bukan Collection
        $perPageInput = $request->input('per_page', 10);
        $perPage = is_array($perPageInput) ? 10 : (int) $perPageInput;
        $perPage = max(1, min(100, $perPage)); // Batasi antara 1-100

        $pageInput = $request->input('page', 1);
        $currentPage = is_array($pageInput) ? 1 : (int) $pageInput;
        $currentPage = max(1, $currentPage); // Minimal 1

        // Ambil semua permintaan user dengan relasi surat persetujuan
        $peminjamanBarang = RequestModel::where('user_id', $userId)
            ->with('suratPersetujuan')
            ->orderBy('created_at', 'desc')
            ->get();

        $permintaanPersediaan = PermintaanPersediaan::where('user_id', $userId)
            ->with('suratPersetujuan')
            ->orderBy('created_at', 'desc')
            ->get();

        $peminjamanKendaraan = PeminjamanKendaraan::where('user_id', $userId)
            ->with('suratPersetujuan')
            ->orderBy('created_at', 'desc')
            ->get();

        $peminjamanAset = PeminjamanAset::where('user_id', $userId)
            ->with('suratPersetujuan')
            ->orderBy('created_at', 'desc')
            ->get();

        // Gabungkan semua data
        $allRequests = collect();

        // Tambahkan data peminjaman barang
        foreach($peminjamanBarang as $req) {
            $allRequests->push([
                'id' => $req->id,
                'type' => 'SPB',
                'type_name' => 'Peminjaman Barang',
                'title' => $req->nama_barang,
                'status' => $req->status,
                'created_at' => $req->created_at,
                'surat_persetujuan' => $req->suratPersetujuan,
                'request_data' => $req
            ]);
        }

        // Tambahkan data permintaan persediaan
        foreach($permintaanPersediaan as $req) {
            $allRequests->push([
                'id' => $req->id,
                'type' => 'SPP',
                'type_name' => 'Permintaan Persediaan',
                'title' => $req->nama_barang,
                'status' => $req->status,
                'created_at' => $req->created_at,
                'surat_persetujuan' => $req->suratPersetujuan,
                'request_data' => $req
            ]);
        }

        // Tambahkan data peminjaman kendaraan
        foreach($peminjamanKendaraan as $req) {
            $allRequests->push([
                'id' => $req->id,
                'type' => 'SPK',
                'type_name' => 'Peminjaman Kendaraan',
                'title' => $req->nama_kendaraan,
                'status' => $req->status,
                'created_at' => $req->created_at,
                'surat_persetujuan' => $req->suratPersetujuan,
                'request_data' => $req
            ]);
        }

        // Tambahkan data peminjaman aset
        foreach($peminjamanAset as $req) {
            $allRequests->push([
                'id' => $req->id,
                'type' => 'SPA',
                'type_name' => 'Peminjaman Aset',
                'title' => $req->nama_barang,
                'status' => $req->status,
                'created_at' => $req->created_at,
                'surat_persetujuan' => $req->suratPersetujuan,
                'request_data' => $req
            ]);
        }

        // Urutkan berdasarkan tanggal terbaru dan reset keys
        $allRequests = $allRequests->sortByDesc('created_at')->values();

        // Implementasi pagination manual
        $total = $allRequests->count();
        $offset = ($currentPage - 1) * $perPage;
        $items = $allRequests->slice($offset, $perPage)->values();

        // Pastikan semua parameter adalah integer
        $totalInt = (int) $total;
        $perPageInt = (int) $perPage;
        $currentPageInt = (int) $currentPage;

        // Buat pagination object
        $paginatedRequests = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $totalInt,
            $perPageInt,
            $currentPageInt,
            [
                'path' => $request->url(),
                'pageName' => 'page',
            ]
        );

        return view('user.histori_permintaan', compact('paginatedRequests'));
    }

    /**
     * Menyimpan permintaan peminjaman barang baru
     */
    public function storeRequest(Request $request)
    {
        try {
            // Debug: Log the request data
            \Log::info('Request Data:', $request->all());

            $request->validate([
                'product_id' => 'required|integer',
                'deskripsi_peruntukan' => 'required|string|max:1000',
                'jumlah' => 'required|integer|min:1',
                'tanggal_peminjaman' => 'required|date|after_or_equal:today',
                'tanggal_pengembalian' => 'required|date|after_or_equal:tanggal_peminjaman',
                'product_type' => 'required|string|in:aset_tetap,persediaan',
            ]);

            // Check if the requested amount is available
            $productType = $request->product_type;
            $productId = $request->product_id;
            $requestedAmount = $request->jumlah;

            if ($productType === 'aset_tetap') {
                // Tolak juga jika aset termasuk kategori kendaraan/alat angkutan bermotor
                $product = $this->asetTetapYangDapatDipinjam()->find($productId);
            } else {
                // Hanya izinkan peminjaman persediaan dengan jenis TIDAK HABIS PAKAI
                $product = \App\Models\Persediaan::where('id', $productId)
                    ->where('jenis', 'tidak_habis_pakai')
                    ->first();
            }

            if (!$product) {
                return response()->json(['message' => 'Barang tidak ditemukan atau tidak dapat dipinjam.'], 404);
            }

            // Gunakan stok tersedia (fallback ke jumlah untuk data lama)
            $availableStock = $product->stok ?? $product->jumlah ?? 0;

            if ($availableStock < $requestedAmount) {
                return response()->json([
                    'message' => 'Jumlah yang diminta melebihi stok yang tersedia (' . $availableStock . ' unit).'
                ], 422);
            }

            RequestModel::create([
                'user_id' => Auth::id(),
                'nama_barang' => $product->nama_barang,
                'kode_barang' => $product->kode_barang ?? 'Tidak Diketahui',
                'kategori' => $product->kategori ?? 'Tidak Diketahui',
                'merek' => $product->merek ?? 'Tidak Diketahui',
                // Kolom ruangan hanya varchar(50), jangan isi dengan deskripsi panjang.
                'ruangan' => $request->input('ruangan') ?: '-',
                'deskripsi_peruntukan' => $request->deskripsi_peruntukan,
                'jumlah' => $request->jumlah,
                'tanggal_peminjaman' => $request->tanggal_peminjaman,
                'tanggal_pengembalian' => $request->tanggal_pengembalian,
                'status' => 'Menunggu',
                'request_date' => now()->format('Y-m-d'),
            ]);

            return response()->json(['message' => 'Permintaan peminjaman barang berhasil dikirim.'], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validasi gagal.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error in storeRequest: ' . $e->getMessage());
            return response()->json(['message' => 'Gagal mengirim permintaan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Menampilkan halaman pengaturan akun
     */
    public function settings()
    {
        $user = Auth::user();
        return view('User.settings', compact('user'));
    }

    /**
     * Update profil user
     */
    public function updateProfile(Request $request)
    {
        try {
            $user = Auth::user();

            // Validasi input
            $request->validate([
                'nama_lengkap' => 'required|string|max:255',
                'instansi' => 'required|string|max:255',
                'jabatan' => 'nullable|string|max:255',
                'nip' => 'required|string|max:255|unique:users,nip,' . $user->id,
                'username' => 'required|string|max:255|unique:users,username,' . $user->id,
                'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
                'current_password' => 'nullable|string',
                'new_password' => 'nullable|string|min:8|confirmed',
                'signature_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Update data profil
            $user->update([
                'nama_lengkap' => $request->nama_lengkap,
                'instansi' => $request->instansi,
                'jabatan' => $request->jabatan,
                'nip' => $request->nip,
                'username' => $request->username,
                'email' => $request->email,
            ]);

            // Update password jika ada
            if ($request->filled('new_password')) {
                // Verifikasi password lama jika ada
                if ($request->filled('current_password')) {
                    if (!Hash::check($request->current_password, $user->password)) {
                        return redirect()->back()->withErrors(['current_password' => 'Password lama tidak sesuai.'])->withInput();
                    }
                }

                $user->update([
                    'password' => Hash::make($request->new_password)
                ]);
            }

            // Handle upload signature image
            if ($request->hasFile('signature_image')) {
                try {
                    // Validasi file
                    $file = $request->file('signature_image');

                    // Hapus file lama jika ada
                    if ($user->signature_image_path && Storage::exists($user->signature_image_path)) {
                        Storage::delete($user->signature_image_path);
                    }

                    // Upload file baru
                    $filename = 'signature_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('signatures', $filename, 'public');

                    // Pastikan file berhasil diupload
                    if ($path) {
                        $user->update([
                            'signature_image_path' => $path
                        ]);
                    } else {
                        throw new \Exception('Gagal mengupload file tanda tangan.');
                    }
                } catch (\Exception $e) {
                    return redirect()->back()->with('notification', [
                        'message' => 'Gagal mengupload tanda tangan: ' . $e->getMessage(),
                        'type' => 'error',
                    ])->withInput();
                }
            }

            return redirect()->route('user.settings')->with('notification', [
                'message' => 'Profil berhasil diperbarui.',
                'type' => 'success',
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('notification', [
                'message' => 'Terjadi kesalahan saat memperbarui profil.',
                'type' => 'error',
            ])->withInput();
        }
    }

    /**
     * Hapus signature image
     */
    public function deleteSignature()
    {
        try {
            $user = Auth::user();

            if ($user->signature_image_path && Storage::exists($user->signature_image_path)) {
                Storage::delete($user->signature_image_path);
                $user->update(['signature_image_path' => null]);

                return response()->json(['message' => 'Tanda tangan berhasil dihapus.']);
            }

            return response()->json(['message' => 'Tidak ada tanda tangan untuk dihapus.'], 404);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal menghapus tanda tangan.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Menampilkan halaman permintaan persediaan
     */
    public function permintaanPersediaan()
    {
        // Ambil data persediaan untuk form (hanya jenis HABIS PAKAI)
        $persediaan = \App\Models\Persediaan::where('jenis', 'habis_pakai')
            ->orderBy('nama_barang')
            ->get();

        // Ambil histori permintaan persediaan untuk user yang sedang login
        $supplyRequests = PermintaanPersediaan::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('User.permintaan_persediaan', compact('persediaan', 'supplyRequests'));
    }

    /**
     * Menyimpan permintaan persediaan baru
     */
    public function storeSupplyRequest(Request $request)
    {
        try {
            // Debug: Log the request data
            \Log::info('Supply Request Data:', $request->all());

            $request->validate([
                'product_id' => 'required|integer',
                'jumlah' => 'required|integer|min:1',
                'deskripsi_peruntukan' => 'required|string|max:1000',
            ]);

            // Check if the requested amount is available
            // Hanya ambil persediaan dengan jenis HABIS PAKAI untuk permintaan persediaan
            $product = \App\Models\Persediaan::where('id', $request->product_id)
                ->where('jenis', 'habis_pakai')
                ->first();

            if (!$product) {
                return response()->json(['message' => 'Barang tidak ditemukan atau tidak dapat diminta sebagai persediaan.'], 404);
            }

            // Gunakan stok tersedia (fallback ke jumlah untuk data lama)
            $availableStock = $product->stok ?? $product->jumlah ?? 0;

            if ($availableStock < $request->jumlah) {
                return response()->json([
                    'message' => 'Jumlah yang diminta melebihi stok yang tersedia (' . $availableStock . ' unit).'
                ], 422);
            }

            PermintaanPersediaan::create([
                'user_id' => Auth::id(),
                'nama_barang' => $product->nama_barang,
                'merek' => $product->merek ?? 'Tidak Diketahui',
                'jumlah' => $request->jumlah,
                'deskripsi_peruntukan' => $request->deskripsi_peruntukan,
                'status' => 'Menunggu',
            ]);

            return response()->json(['message' => 'Permintaan persediaan berhasil dikirim.'], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validasi gagal.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error in storeSupplyRequest: ' . $e->getMessage());
            return response()->json(['message' => 'Gagal mengirim permintaan persediaan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Menampilkan halaman peminjaman kendaraan
     */
    public function peminjamanKendaraan()
    {
        // Ambil data kendaraan dari tabel aset_tetap (filter berdasarkan kategori dan nama barang)
        $kendaraan = \App\Models\AsetTetap::where(function($query) {
            $query->where('kategori', 'like', '%kendaraan%')
                  ->orWhere('kategori', 'like', '%motor%')
                  ->orWhere('kategori', 'like', '%mobil%')
                  ->orWhere('kategori', 'like', '%kendaraan%')
                  ->orWhere('nama_barang', 'like', '%motor%')
                  ->orWhere('nama_barang', 'like', '%mobil%')
                  ->orWhere('nama_barang', 'like', '%kendaraan%')
                  ->orWhere('nama_barang', 'like', '%Yamaha%')
                  ->orWhere('nama_barang', 'like', '%Honda%')
                  ->orWhere('nama_barang', 'like', '%Suzuki%')
                  ->orWhere('nama_barang', 'like', '%Kawasaki%')
                  ->orWhere('nama_barang', 'like', '%Toyota%')
                  ->orWhere('nama_barang', 'like', '%Daihatsu%')
                  ->orWhere('nama_barang', 'like', '%Mitsubishi%')
                  ->orWhere('merek', 'like', '%Yamaha%')
                  ->orWhere('merek', 'like', '%Honda%')
                  ->orWhere('merek', 'like', '%Suzuki%')
                  ->orWhere('merek', 'like', '%Kawasaki%')
                  ->orWhere('merek', 'like', '%Toyota%')
                  ->orWhere('merek', 'like', '%Daihatsu%')
                  ->orWhere('merek', 'like', '%Mitsubishi%');
        })
            ->orderBy('nama_barang')
            ->get();

        // Ambil histori peminjaman kendaraan untuk user yang sedang login
        $vehicleRequests = PeminjamanKendaraan::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('User.peminjaman_kendaraan', compact('kendaraan', 'vehicleRequests'));
    }

    /**
     * Menyimpan permintaan peminjaman kendaraan baru
     */
    public function storeVehicleRequest(Request $request)
    {
        try {
            $request->validate([
                'vehicle_id' => 'required|integer',
                'jumlah' => 'required|integer|min:1',
                'deskripsi_peruntukan' => 'required|string|max:1000',
                'tanggal_peminjaman' => 'required|date|after_or_equal:today',
                'tanggal_pengembalian' => 'required|date|after_or_equal:tanggal_peminjaman',
            ]);

            // Check if the requested amount is available
            $vehicle = \App\Models\AsetTetap::find($request->vehicle_id);

            if (!$vehicle) {
                return response()->json(['message' => 'Kendaraan tidak ditemukan.'], 404);
            }

            // Gunakan stok tersedia (fallback ke jumlah untuk data lama)
            $availableStock = $vehicle->stok ?? $vehicle->jumlah ?? 0;

            if ($availableStock < $request->jumlah) {
                return response()->json([
                    'message' => 'Jumlah yang diminta melebihi stok yang tersedia (' . $availableStock . ' unit).'
                ], 422);
            }

            PeminjamanKendaraan::create([
                'user_id' => Auth::id(),
                'nama_kendaraan' => $vehicle->nama_barang,
                'kategori' => $vehicle->kategori ?? 'Tidak Diketahui',
                'merek' => $vehicle->merek ?? 'Tidak Diketahui',
                'jumlah' => $request->jumlah,
                'deskripsi_peruntukan' => $request->deskripsi_peruntukan,
                'tanggal_peminjaman' => $request->tanggal_peminjaman,
                'tanggal_pengembalian' => $request->tanggal_pengembalian,
                'status' => 'Menunggu',
            ]);

            return response()->json(['message' => 'Permintaan peminjaman kendaraan berhasil dikirim.'], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validasi gagal.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error in storeVehicleRequest: ' . $e->getMessage());
            return response()->json(['message' => 'Gagal mengirim permintaan peminjaman kendaraan: ' . $e->getMessage()], 500);
        }
    }

    // Method untuk mengambil detail histori via AJAX
    public function getDetailPeminjamanBarang($id)
    {
        try {
            $request = RequestModel::with(['user', 'suratPersetujuan'])->where('user_id', Auth::id())->findOrFail($id);
            return view('user.detail_peminjaman_barang', compact('request'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Permintaan tidak ditemukan atau Anda tidak memiliki akses.');
        }
    }

    public function getDetailPermintaanPersediaan($id)
    {
        try {
            $request = PermintaanPersediaan::with(['user', 'suratPersetujuan'])->where('user_id', Auth::id())->findOrFail($id);
            return view('user.detail_permintaan_persediaan', compact('request'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Permintaan tidak ditemukan atau Anda tidak memiliki akses.');
        }
    }

    public function getDetailPeminjamanKendaraan($id)
    {
        try {
            $request = PeminjamanKendaraan::with(['user', 'suratPersetujuan'])->where('user_id', Auth::id())->findOrFail($id);
            return view('user.detail_peminjaman_kendaraan', compact('request'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Permintaan tidak ditemukan atau Anda tidak memiliki akses.');
        }
    }
}
