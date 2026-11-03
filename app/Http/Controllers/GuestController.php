<?php

namespace App\Http\Controllers;

use App\Models\PeminjamanAset;
use App\Models\SuratPersetujuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GuestController extends Controller
{
    /**
     * Menampilkan dashboard untuk role Tamu
     */
    public function dashboard()
    {
        $user = Auth::user();
        $assetRequests = PeminjamanAset::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Ambil surat persetujuan untuk peminjaman aset
        $suratPersetujuan = collect();
        try {
            $spaRequestIds = $assetRequests->pluck('id');
            $suratPersetujuan = SuratPersetujuan::whereIn('request_id', $spaRequestIds)
                ->where('jenis_surat', 'SPA')
                ->get();
        } catch (\Exception $e) {
            $suratPersetujuan = collect();
        }
        
        // Hitung statistik
        $stats = [
            'totalSuratPersetujuan' => $suratPersetujuan->count(),
        ];
        
        return view('Guest.dashboard', compact('user', 'assetRequests', 'stats', 'suratPersetujuan'));
    }

    /**
     * Menampilkan halaman pengaturan akun untuk role Tamu
     */
    public function settings()
    {
        $user = Auth::user();
        return view('Guest.settings', compact('user'));
    }

    /**
     * Menampilkan halaman peminjaman aset
     */
    public function peminjamanAset()
    {
        $assetRequests = PeminjamanAset::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('Guest.peminjaman_aset', compact('assetRequests'));
    }

    /**
     * Menyimpan permintaan peminjaman aset baru
     */
    public function storeAssetRequest(Request $request)
    {
        try {
            // Pastikan user sudah login
            if (!Auth::check()) {
                return response()->json([
                    'message' => 'Anda harus login terlebih dahulu.'
                ], 401);
            }

            $request->validate([
                'nama_instansi' => 'required|string|max:255',
                'nama_peminjam' => 'required|string|max:255',
                'nip' => 'required|string|max:255',
                'kabupaten_kota' => 'required|string|max:255',
                'jenis' => 'required|string|max:255',
                'tanggal_pinjaman' => 'required|date|after_or_equal:today',
                'tanggal_selesai' => 'required|date|after_or_equal:tanggal_pinjaman',
                'jam_mulai' => 'required|date_format:H:i',
                'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
                'surat' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120', // 5MB max
            ]);

            $suratPath = null;
            if ($request->hasFile('surat')) {
                try {
                    $file = $request->file('surat');
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $suratPath = $file->storeAs('peminjaman_aset/surat', $fileName, 'public');
                } catch (\Exception $e) {
                    \Log::error('Error storing file: ' . $e->getMessage());
                    // Lanjutkan tanpa file jika upload gagal
                }
            }

            // Siapkan data untuk insert
            $data = [
                'user_id' => Auth::id(),
                'nama_instansi' => $request->nama_instansi,
                'nama_peminjam' => $request->nama_peminjam,
                'nip' => $request->nip,
                'kabupaten_kota' => $request->kabupaten_kota,
                'nama_barang' => 'Gedung/Ruang',
                'jenis' => $request->jenis,
                'tanggal_pinjaman' => $request->tanggal_pinjaman,
                'tanggal_selesai' => $request->tanggal_selesai,
                'status' => 'Menunggu',
            ];

            // Tambahkan surat_path jika ada
            if ($suratPath) {
                $data['surat_path'] = $suratPath;
            }

            // Konversi jam ke datetime
            try {
                $data['jam_mulai'] = Carbon::parse($request->tanggal_pinjaman . ' ' . $request->jam_mulai)->format('Y-m-d H:i:s');
                $data['jam_selesai'] = Carbon::parse($request->tanggal_selesai . ' ' . $request->jam_selesai)->format('Y-m-d H:i:s');
            } catch (\Exception $e) {
                \Log::error('Error parsing datetime: ' . $e->getMessage());
                // Fallback: gunakan format sederhana
                $data['jam_mulai'] = $request->tanggal_pinjaman . ' ' . $request->jam_mulai . ':00';
                $data['jam_selesai'] = $request->tanggal_selesai . ' ' . $request->jam_selesai . ':00';
            }

            // Log data sebelum insert untuk debugging (tanpa user_id untuk keamanan)
            $logData = $data;
            unset($logData['user_id']);
            \Log::info('Creating peminjaman aset with data:', $logData);

            // Gunakan DB::table untuk insert langsung jika model create gagal
            try {
                $peminjamanAset = PeminjamanAset::create($data);
            } catch (\Exception $e) {
                \Log::error('Model create failed, trying DB insert: ' . $e->getMessage());
                // Coba insert langsung ke database
                $id = DB::table('peminjaman_asets')->insertGetId($data);
                $peminjamanAset = PeminjamanAset::find($id);
            }

            return response()->json(['message' => 'Permintaan peminjaman aset berhasil dikirim.'], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validasi gagal.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Illuminate\Database\QueryException $e) {
            \Log::error('Database error in storeAssetRequest: ' . $e->getMessage());
            \Log::error('SQL: ' . $e->getSql());
            \Log::error('Bindings: ' . json_encode($e->getBindings()));
            return response()->json([
                'message' => 'Gagal mengirim permintaan peminjaman aset.',
                'error' => config('app.debug') ? $e->getMessage() : 'Terjadi kesalahan saat menyimpan data ke database.'
            ], 500);
        } catch (\Exception $e) {
            \Log::error('Error in storeAssetRequest: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'message' => 'Gagal mengirim permintaan peminjaman aset.',
                'error' => config('app.debug') ? $e->getMessage() : 'Terjadi kesalahan saat menyimpan data.'
            ], 500);
        }
    }

    /**
     * Memperbarui profil pengguna tamu
     */
    public function updateProfile(Request $request)
    {
        try {
            $user = Auth::user();
            
            // Debug: Log request data
            \Log::info('Guest profile update request', [
                'user_id' => $user->id,
                'request_data' => $request->except(['signature_image', 'current_password', 'new_password', 'new_password_confirmation'])
            ]);
            
            $request->validate([
                'nama_lengkap' => 'required|string|max:255',
                'instansi' => 'required|string|max:255',
                'nip' => 'required|string|max:255|unique:users,nip,' . $user->id,
                'username' => 'required|string|max:255|unique:users,username,' . $user->id,
                'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
                'current_password' => 'nullable|string',
                'new_password' => 'nullable|string|min:8|confirmed',
                'signature_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Update basic profile information
            $updateData = [
                'nama_lengkap' => $request->nama_lengkap,
                'instansi' => $request->instansi,
                'nip' => $request->nip,
                'username' => $request->username,
                'email' => $request->email,
            ];
            
            \Log::info('Updating user profile', [
                'user_id' => $user->id,
                'update_data' => $updateData
            ]);
            
            try {
                $user->update($updateData);
                \Log::info('User profile updated successfully', ['user_id' => $user->id]);
            } catch (\Exception $updateError) {
                \Log::error('Database update error', [
                    'user_id' => $user->id,
                    'error' => $updateError->getMessage(),
                    'update_data' => $updateData
                ]);
                throw $updateError;
            }

            // Update password if provided
            if ($request->filled('new_password')) {
                if (!Hash::check($request->current_password, $user->password)) {
                    return response()->json([
                        'message' => 'Password saat ini tidak sesuai.',
                        'errors' => ['current_password' => ['Password saat ini tidak sesuai.']]
                    ], 422);
                }
                
                $user->update([
                    'password' => Hash::make($request->new_password)
                ]);
            }

            // Handle signature image upload
            if ($request->hasFile('signature_image')) {
                try {
                    // Validasi file
                    $file = $request->file('signature_image');
                    
                    // Delete old signature if exists
                    if ($user->signature_image_path && Storage::exists($user->signature_image_path)) {
                        Storage::delete($user->signature_image_path);
                    }

                    // Store new signature
                    $signaturePath = $file->store('signatures', 'public');
                    
                    // Pastikan file berhasil diupload
                    if ($signaturePath) {
                        $user->update(['signature_image_path' => $signaturePath]);
                    } else {
                        throw new \Exception('Gagal mengupload file tanda tangan.');
                    }
                } catch (\Exception $e) {
                    return response()->json([
                        'message' => 'Gagal mengupload tanda tangan: ' . $e->getMessage()
                    ], 500);
                }
            }

            return response()->json([
                'message' => 'Profil berhasil diperbarui!'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validasi gagal.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Guest profile update error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'request_data' => $request->except(['signature_image']),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'message' => 'Terjadi kesalahan saat memperbarui profil: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menghapus tanda tangan digital pengguna tamu
     */
    public function deleteSignature()
    {
        try {
            $user = Auth::user();
            
            if ($user->signature_image_path && Storage::exists($user->signature_image_path)) {
                Storage::delete($user->signature_image_path);
                $user->update(['signature_image_path' => null]);
                
                return response()->json([
                    'message' => 'Tanda tangan berhasil dihapus.'
                ]);
            }
            
            return response()->json([
                'message' => 'Tidak ada tanda tangan untuk dihapus.'
            ], 404);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat menghapus tanda tangan.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mengambil detail histori peminjaman aset via AJAX
     */
    public function getDetailPeminjamanAset($id)
    {
        try {
            $request = PeminjamanAset::with('user')->where('user_id', Auth::id())->findOrFail($id);
            return response()->json($request);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }
    }

    public function viewSuratPeminjamanAset($id)
    {
        $request = PeminjamanAset::where('user_id', Auth::id())->findOrFail($id);
        
        if (!$request->surat_path) {
            abort(404, 'Surat tidak ditemukan');
        }
        
        $filePath = storage_path('app/public/' . $request->surat_path);
        
        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan');
        }
        
        return response()->file($filePath);
    }

    public function downloadSuratPeminjamanAset($id)
    {
        $request = PeminjamanAset::where('user_id', Auth::id())->findOrFail($id);
        
        if (!$request->surat_path) {
            abort(404, 'Surat tidak ditemukan');
        }
        
        $filePath = storage_path('app/public/' . $request->surat_path);
        
        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan');
        }
        
        $filename = basename($request->surat_path);
        
        return response()->download($filePath, $filename);
    }

    /**
     * Menampilkan halaman histori peminjaman aset
     */
    public function historiPeminjamanAset(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        
        $assetRequests = PeminjamanAset::with('suratPersetujuan')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
        
        return view('Guest.histori_peminjaman_aset', compact('assetRequests'));
    }

    /**
     * Menampilkan detail peminjaman aset
     */
    public function detailPeminjamanAset($id)
    {
        $request = PeminjamanAset::with(['suratPersetujuan', 'user'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);
        
        return view('Guest.detail_peminjaman_aset', compact('request'));
    }
}
