<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SuratPersetujuan;
use App\Models\RequestModel;
use App\Models\PermintaanPersediaan;
use App\Models\PeminjamanKendaraan;
use App\Models\PeminjamanAset;
use App\Models\User;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class SuratPersetujuanController extends Controller
{
    /**
     * Menyimpan surat persetujuan yang diupload
     */
    public function store(Request $request)
    {
        // Pastikan selalu return JSON untuk AJAX request
        try {
            // Log request untuk debugging
            Log::info('Upload surat persetujuan request received', [
                'admin_id' => Auth::id(),
                'jenis_surat' => $request->jenis_surat,
                'request_id' => $request->request_id,
                'has_file' => $request->hasFile('file'),
                'all_data' => $request->except(['file', '_token'])
            ]);
            
            // Validasi request
            $validated = $request->validate([
                'jenis_surat' => 'required|in:SPB,SPP,SPK,SPA',
                'request_id' => 'required|integer',
                'nomor_surat' => 'nullable|string|max:255',
                'file' => 'required|file|mimes:pdf,doc,docx|max:10240', // Max 10MB
                'keterangan' => 'nullable|string|max:1000'
            ]);

            // Validasi request_id berdasarkan jenis surat
            try {
                $this->validateRequestId($request->jenis_surat, $request->request_id);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 422);
            }

            // Upload file
            $file = $request->file('file');
            
            // Validasi tambahan file
            if (!$file || !$file->isValid()) {
                return response()->json([
                    'success' => false,
                    'message' => 'File tidak valid atau corrupt. Silakan pilih file lain.'
                ], 422);
            }

            // Generate unique filename - pastikan extension tetap ada
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            
            // Jika extension kosong, coba dari mime type atau filename
            if (empty($extension)) {
                $mimeType = $file->getMimeType();
                if (str_contains($mimeType, 'pdf')) {
                    $extension = 'pdf';
                } elseif (str_contains($mimeType, 'word')) {
                    $extension = 'docx';
                } elseif (str_contains($mimeType, 'msword')) {
                    $extension = 'doc';
                } else {
                    // Coba dari nama file
                    $extension = pathinfo($originalName, PATHINFO_EXTENSION);
                    if (empty($extension)) {
                        $extension = 'pdf'; // Default ke PDF
                    }
                }
            }
            
            $nameWithoutExt = pathinfo($originalName, PATHINFO_FILENAME);
            // Pastikan tidak ada karakter khusus yang bisa menyebabkan masalah
            $safeName = Str::slug($nameWithoutExt);
            if (empty($safeName)) {
                $safeName = 'surat';
            }
            $fileName = time() . '_' . $safeName . '.' . strtolower($extension);
            
            Log::info('Attempting to store file', [
                'original_name' => $originalName,
                'generated_name' => $fileName,
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType()
            ]);
            
            // Pastikan direktori ada
            $directory = storage_path('app/public/surat-persetujuan');
            if (!is_dir($directory)) {
                try {
                    mkdir($directory, 0755, true);
                    Log::info('Created directory', ['directory' => $directory]);
                } catch (\Exception $e) {
                    Log::error('Failed to create directory', [
                        'directory' => $directory,
                        'error' => $e->getMessage()
                    ]);
                }
            }
            
            // Simpan file dengan optimasi
            try {
                $startTime = microtime(true);
                $filePath = $file->storeAs('surat-persetujuan', $fileName, 'public');
                $storeTime = microtime(true) - $startTime;
                Log::info('File stored', [
                    'file_path' => $filePath,
                    'time_taken' => round($storeTime, 2) . 's',
                    'file_size' => $file->getSize()
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to store file', [
                    'error' => $e->getMessage(),
                    'file_name' => $fileName,
                    'directory' => $directory
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menyimpan file: ' . $e->getMessage()
                ], 500);
            }
            
            // Log sudah dipindahkan ke atas untuk tracking waktu

            // Pastikan file berhasil disimpan
            if (!$filePath) {
                Log::error('File path is null after store');
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menyimpan file. Path file kosong.'
                ], 500);
            }
            
            // Verifikasi file benar-benar ada
            $fullPath = storage_path('app/public/' . $filePath);
            if (!Storage::disk('public')->exists($filePath) && !file_exists($fullPath)) {
                Log::error('File does not exist after store', [
                    'file_path' => $filePath,
                    'full_path' => $fullPath,
                    'storage_exists' => Storage::disk('public')->exists($filePath),
                    'file_exists' => file_exists($fullPath)
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menyimpan file. File tidak ditemukan di storage setelah upload.'
                ], 500);
            }

            // Simpan ke database - langsung tanpa PRAGMA karena constraint sudah dihapus
            $suratPersetujuan = SuratPersetujuan::create([
                'jenis_surat' => $request->jenis_surat,
                'request_id' => $request->request_id,
                'nomor_surat' => $request->nomor_surat,
                'file_path' => $filePath,
                'file_name' => $file->getClientOriginalName(),
                'file_size' => (string) $file->getSize(), // Convert ke string sesuai database schema
                'keterangan' => $request->keterangan,
                'status' => 'approved',
                'uploaded_by' => Auth::id()
            ]);
            
            // Verifikasi data tersimpan dengan benar
            if (!$suratPersetujuan->id) {
                Log::error('Failed to create surat persetujuan record');
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menyimpan data surat persetujuan ke database.'
                ], 500);
            }
            
            // Log untuk debugging - simpan ke variabel untuk menghindari query berulang
            $fileExists = Storage::disk('public')->exists($suratPersetujuan->file_path);
            Log::info('Surat persetujuan berhasil disimpan', [
                'surat_id' => $suratPersetujuan->id,
                'jenis_surat' => $suratPersetujuan->jenis_surat,
                'request_id' => $suratPersetujuan->request_id,
                'file_path' => $suratPersetujuan->file_path,
                'file_exists' => $fileExists
            ]);

            // Dapatkan data request untuk notifikasi - lakukan di background untuk mempercepat response
            // Notifikasi tidak harus blocking upload process
            $requestData = $this->getRequestData($request->jenis_surat, $request->request_id);
            $user = $requestData->user ?? null;

            // Kirim notifikasi ke user jika ada - tidak blocking
            if ($user) {
                // Gunakan dispatch setelah response untuk mempercepat upload
                // Atau kirim di background
                try {
                    $this->sendNotificationToUser($user, $request->jenis_surat, $request->request_id, $suratPersetujuan->id, $request->nomor_surat);
                } catch (\Exception $e) {
                    // Log error notifikasi tapi tidak gagalkan proses upload
                    Log::warning('Gagal mengirim notifikasi setelah upload surat persetujuan', [
                        'error' => $e->getMessage(),
                        'surat_id' => $suratPersetujuan->id
                    ]);
                }
            }

            // Log aktivitas
            Log::info('Admin upload surat persetujuan', [
                'admin_id' => Auth::id(),
                'admin_name' => Auth::user()->nama_lengkap ?? Auth::user()->name ?? 'Unknown',
                'jenis_surat' => $request->jenis_surat,
                'request_id' => $request->request_id,
                'user_id' => $user ? $user->id : null,
                'user_name' => $user ? ($user->nama_lengkap ?? $user->name ?? 'Unknown') : null,
                'file_name' => $file->getClientOriginalName(),
                'nomor_surat' => $request->nomor_surat
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Surat persetujuan berhasil diupload!',
                'data' => [
                    'surat_id' => $suratPersetujuan->id,
                    'user_name' => $user ? ($user->nama_lengkap ?? $user->name ?? 'Unknown') : 'Unknown',
                    'jenis_surat' => $request->jenis_surat,
                    'nomor_surat' => $request->nomor_surat
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Validation error pada upload surat persetujuan', [
                'errors' => $e->errors(),
                'admin_id' => Auth::id(),
                'request_id' => $request->request_id,
                'jenis_surat' => $request->jenis_surat
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal. Silakan periksa data yang Anda masukkan.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error uploading surat persetujuan: ' . $e->getMessage(), [
                'exception' => $e,
                'exception_class' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'admin_id' => Auth::id(),
                'request_id' => $request->request_id ?? null,
                'jenis_surat' => $request->jenis_surat ?? null,
                'request_data' => $request->except(['file'])
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengupload surat persetujuan: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Validasi request_id berdasarkan jenis surat
     */
    private function validateRequestId($jenisSurat, $requestId)
    {
        switch ($jenisSurat) {
            case 'SPB':
                if (!RequestModel::find($requestId)) {
                    throw new \Exception('Request peminjaman barang tidak ditemukan!');
                }
                break;
            case 'SPP':
                if (!PermintaanPersediaan::find($requestId)) {
                    throw new \Exception('Request permintaan persediaan tidak ditemukan!');
                }
                break;
            case 'SPK':
                if (!PeminjamanKendaraan::find($requestId)) {
                    throw new \Exception('Request peminjaman kendaraan tidak ditemukan!');
                }
                break;
            case 'SPA':
                if (!PeminjamanAset::find($requestId)) {
                    throw new \Exception('Request peminjaman aset tidak ditemukan!');
                }
                break;
        }
    }

    /**
     * Mendapatkan data request berdasarkan jenis surat
     */
    private function getRequestData($jenisSurat, $requestId)
    {
        switch ($jenisSurat) {
            case 'SPB':
                return RequestModel::with('user')->find($requestId);
            case 'SPP':
                return PermintaanPersediaan::with('user')->find($requestId);
            case 'SPK':
                return PeminjamanKendaraan::with('user')->find($requestId);
            case 'SPA':
                return PeminjamanAset::with('user')->find($requestId);
            default:
                return null;
        }
    }

    /**
     * Download surat persetujuan
     */
    public function download($id)
    {
        try {
            $suratPersetujuan = SuratPersetujuan::findOrFail($id);
            
            Log::info('Download surat persetujuan attempt', [
                'id' => $id,
                'file_path' => $suratPersetujuan->file_path,
                'file_name' => $suratPersetujuan->file_name
            ]);
            
            // Cek apakah file_path ada
            if (!$suratPersetujuan->file_path) {
                Log::error('File path is empty', ['surat_id' => $id]);
                abort(404, 'File path tidak ditemukan');
            }
            
            // Cek apakah file ada di storage public
            $exists = Storage::disk('public')->exists($suratPersetujuan->file_path);
            Log::info('File exists check', [
                'file_path' => $suratPersetujuan->file_path,
                'exists' => $exists
            ]);
            
            if (!$exists) {
                // Coba cek dengan path lengkap
                $fullPath = storage_path('app/public/' . $suratPersetujuan->file_path);
                Log::info('Checking full path', ['full_path' => $fullPath, 'file_exists' => file_exists($fullPath)]);
                
                if (!file_exists($fullPath)) {
                    Log::error('File tidak ditemukan di storage', [
                        'file_path' => $suratPersetujuan->file_path,
                        'full_path' => $fullPath
                    ]);
                    abort(404, 'File tidak ditemukan di storage');
                }
                
                $filePath = $fullPath;
            } else {
                // Dapatkan path file yang benar
                $filePath = Storage::disk('public')->path($suratPersetujuan->file_path);
            }
            
            // Cek apakah file benar-benar ada di sistem
            if (!file_exists($filePath)) {
                Log::error('File tidak ada di sistem', ['file_path' => $filePath]);
                abort(404, 'File tidak ditemukan di sistem');
            }
            
            // Return file download response
            return response()->download($filePath, $suratPersetujuan->file_name ?? basename($filePath));
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Surat persetujuan tidak ditemukan', ['id' => $id]);
            abort(404, 'Surat persetujuan tidak ditemukan');
        } catch (\Exception $e) {
            Log::error('Error downloading surat persetujuan: ' . $e->getMessage(), [
                'id' => $id,
                'trace' => $e->getTraceAsString()
            ]);
            abort(404, 'File tidak dapat diunduh: ' . $e->getMessage());
        }
    }

    /**
     * Preview surat persetujuan
     */
    public function preview($id)
    {
        try {
            $suratPersetujuan = SuratPersetujuan::findOrFail($id);
            
            Log::info('Preview surat persetujuan attempt', [
                'id' => $id,
                'file_path' => $suratPersetujuan->file_path,
                'file_name' => $suratPersetujuan->file_name
            ]);
            
            // Cek apakah file_path ada
            if (!$suratPersetujuan->file_path) {
                Log::error('File path is empty', ['surat_id' => $id]);
                abort(404, 'File path tidak ditemukan');
            }
            
            // Cek apakah file ada di storage public
            $exists = Storage::disk('public')->exists($suratPersetujuan->file_path);
            Log::info('File exists check', [
                'file_path' => $suratPersetujuan->file_path,
                'exists' => $exists
            ]);
            
            if (!$exists) {
                // Coba cek dengan path lengkap
                $fullPath = storage_path('app/public/' . $suratPersetujuan->file_path);
                Log::info('Checking full path', ['full_path' => $fullPath, 'file_exists' => file_exists($fullPath)]);
                
                if (!file_exists($fullPath)) {
                    Log::error('File tidak ditemukan di storage', [
                        'file_path' => $suratPersetujuan->file_path,
                        'full_path' => $fullPath
                    ]);
                    abort(404, 'File tidak ditemukan di storage');
                }
                
                // Get file content dari path langsung
                $fileContent = file_get_contents($fullPath);
                $mimeType = mime_content_type($fullPath);
            } else {
                // Get file content dari storage
                $fileContent = Storage::disk('public')->get($suratPersetujuan->file_path);
                $mimeType = Storage::disk('public')->mimeType($suratPersetujuan->file_path);
            }
            
            // Fallback mime type jika tidak terdeteksi
            if (!$mimeType) {
                $extension = pathinfo($suratPersetujuan->file_path, PATHINFO_EXTENSION);
                $mimeTypes = [
                    'pdf' => 'application/pdf',
                    'doc' => 'application/msword',
                    'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                ];
                $mimeType = $mimeTypes[strtolower($extension)] ?? 'application/octet-stream';
            }
            
            Log::info('Returning file preview', [
                'mime_type' => $mimeType,
                'file_size' => strlen($fileContent)
            ]);
            
            // Return file response dengan proper headers
            return response($fileContent, 200)
                ->header('Content-Type', $mimeType)
                ->header('Content-Disposition', 'inline; filename="' . ($suratPersetujuan->file_name ?? 'surat.pdf') . '"')
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Surat persetujuan tidak ditemukan', ['id' => $id]);
            abort(404, 'Surat persetujuan tidak ditemukan');
        } catch (\Exception $e) {
            Log::error('Error previewing surat persetujuan: ' . $e->getMessage(), [
                'id' => $id,
                'trace' => $e->getTraceAsString()
            ]);
            abort(404, 'File tidak dapat ditampilkan: ' . $e->getMessage());
        }
    }

    /**
     * Kirim notifikasi ke user ketika surat persetujuan diupload
     */
    private function sendNotificationToUser($user, $jenisSurat, $requestId, $suratPersetujuanId, $nomorSurat)
    {
        try {
            Log::info('Starting notification creation', [
                'user_id' => $user->id,
                'user_name' => $user->nama_lengkap,
                'jenis_surat' => $jenisSurat,
                'request_id' => $requestId,
                'surat_persetujuan_id' => $suratPersetujuanId
            ]);

            $jenisSuratNames = [
                'SPB' => 'Peminjaman Barang',
                'SPP' => 'Permintaan Persediaan', 
                'SPK' => 'Peminjaman Kendaraan',
                'SPA' => 'Peminjaman Aset'
            ];

            $jenisSuratName = $jenisSuratNames[$jenisSurat] ?? $jenisSurat;
            
            $judul = "Surat Persetujuan {$jenisSuratName} Telah Tersedia";
            $pesan = "Surat persetujuan untuk permintaan {$jenisSuratName} Anda telah diupload oleh Admin. ";
            
            if ($nomorSurat) {
                $pesan .= "Nomor surat: {$nomorSurat}. ";
            }
            
            $pesan .= "Silakan login ke sistem untuk mengunduh surat persetujuan tersebut.";

            Log::info('Creating notification with data', [
                'user_id' => $user->id,
                'jenis_surat' => $jenisSurat,
                'request_id' => $requestId,
                'surat_persetujuan_id' => $suratPersetujuanId,
                'judul' => $judul,
                'pesan' => $pesan
            ]);

            $notifikasi = Notifikasi::createSuratPersetujuanNotification(
                $user->id,
                $jenisSurat,
                $requestId,
                $suratPersetujuanId,
                $judul,
                $pesan
            );

            Log::info('Notifikasi berhasil dikirim ke user', [
                'user_id' => $user->id,
                'user_name' => $user->nama_lengkap,
                'jenis_surat' => $jenisSurat,
                'request_id' => $requestId,
                'surat_persetujuan_id' => $suratPersetujuanId,
                'notifikasi_id' => $notifikasi->id
            ]);

        } catch (\Exception $e) {
            Log::error('Gagal mengirim notifikasi ke user: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'jenis_surat' => $jenisSurat,
                'request_id' => $requestId,
                'exception' => $e->getTraceAsString()
            ]);
        }
    }
}
