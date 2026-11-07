<?php

namespace App\Http\Controllers;

use App\Models\RequestModel;
use App\Models\PermintaanPersediaan;
use App\Models\PeminjamanKendaraan;
use App\Models\PeminjamanAset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

use Carbon\Carbon;

class SuratController extends Controller
{
    /**
     * Generate surat peminjaman barang untuk user
     */
    public function suratPeminjamanBarang($id)
    {
        $request = RequestModel::with(['user'])->findOrFail($id);
        // get tanda tangan peminjam
        $pathFromDb = $request->user->signature_image_path; // "signatures/signature_3_1761134229.png"
        $ttdBase64 = null;

        // PERBAIKAN: Cek dulu apakah $pathFromDb tidak kosong
        if (!empty($pathFromDb) && Storage::disk('public')->exists($pathFromDb)) {
            $fileContents = Storage::disk('public')->get($pathFromDb);
            $ttdBase64 = 'data:image/png;base64,' . base64_encode($fileContents);
        }

        // Check authorization - admin dapat akses semua, user hanya miliknya
        if (Auth::user()->role !== 'admin' && $request->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        Pdf::setOptions([
            'dpi' => 72,
            'defaultFont' => 'serif',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => false,
            'enable_php' => false,
        ]);

        $tglPinjam  = $request->tanggal_peminjaman ? \Carbon\Carbon::parse($request->tanggal_peminjaman)->translatedFormat('d F Y') : 'N/A';
        $tglKembali = $request->tanggal_pengembalian ? \Carbon\Carbon::parse($request->tanggal_pengembalian)->translatedFormat('d F Y') : 'N/A';

        $data = [
            'request' => $request,
            'title' => 'Surat Peminjaman Barang',
            'nomor_surat' => 'SPB/          /2025',
            'tanggal_surat' => now()->translatedFormat('d F Y'),
            'logoPath' => public_path('logo-bpmp-small.png'),
            'tanggalCetak' => now()->translatedFormat('d F Y, H:i'),
            'tglPinjam' => $tglPinjam,
            'tglKembali' => $tglKembali,
            'ttdBase64' => $ttdBase64,

        ];


        $pdf = Pdf::loadView('surat.peminjaman_barang_new', $data)->setPaper('A4', 'portrait');
        return $pdf->download('Surat_Peminjaman_Barang_' . $request->id . '.pdf');
    }

    /**
     * Generate surat permintaan persediaan untuk user
     */
    public function suratPermintaanPersediaan($id)
    {
        $request = PermintaanPersediaan::with('user')->findOrFail($id);

        $pathFromDb = $request->user->signature_image_path; // "signatures/signature_3_1761134229.png"
        $ttdBase64 = null;

        // PERBAIKAN: Cek dulu apakah $pathFromDb tidak kosong
        if (!empty($pathFromDb) && Storage::disk('public')->exists($pathFromDb)) {
            $fileContents = Storage::disk('public')->get($pathFromDb);
            $ttdBase64 = 'data:image/png;base64,' . base64_encode($fileContents);
        }
        // Check authorization
        if (Auth::user()->role !== 'admin' && $request->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        Pdf::setOptions([
            'dpi' => 72,
            'defaultFont' => 'serif',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => false,
            'enable_php' => false,
        ]);

        $tanggalCetak = now()->translatedFormat('d F Y, H:i');
        $tanggalSurat = now()->translatedFormat('d F Y');

        $data = [
            'request' => $request,
            'title' => 'Surat Permintaan Persediaan',
            'nomor_surat' => 'SPP/          /2025',
            'tanggal_surat' => $tanggalSurat,
            'logoPath' => public_path('logo-bpmp-small.png'),
            'tanggalCetak' => $tanggalCetak,
            'ttdBase64' => $ttdBase64,
        ];

        $pdf = Pdf::loadView('surat.permintaan_persediaan_new', $data)->setPaper('A4', 'portrait');

        return $pdf->download('Surat_Permintaan_Persediaan_' . $request->id . '.pdf');
    }

    /**
     * Generate surat peminjaman kendaraan untuk user
     */
    public function suratPeminjamanKendaraan($id)
    {
        $request = PeminjamanKendaraan::with('user')->findOrFail($id);

        $pathFromDb = $request->user->signature_image_path; // "signatures/signature_3_1761134229.png"
        $ttdBase64 = null;

        // PERBAIKAN: Cek dulu apakah $pathFromDb tidak kosong
        if (!empty($pathFromDb) && Storage::disk('public')->exists($pathFromDb)) {
            $fileContents = Storage::disk('public')->get($pathFromDb);
            $ttdBase64 = 'data:image/png;base64,' . base64_encode($fileContents);
        }
        // Check authorization
        if (Auth::user()->role !== 'admin' && $request->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        Pdf::setOptions([
            'dpi' => 72,
            'defaultFont' => 'serif',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => false,
            'enable_php' => false,
        ]);

        $tanggalCetak = now()->translatedFormat('d F Y, H:i');
        $tanggalSurat = now()->translatedFormat('d F Y');

        $data = [
            'request' => $request,
            'title' => 'Surat Peminjaman Kendaraan',
            'nomor_surat' => 'SPK/          /2025',
            'tanggal_surat' => $tanggalSurat,
            'logoPath' => public_path('logo-bpmp-small.png'),
            'tanggalCetak' => $tanggalCetak,
            'ttdBase64' => $ttdBase64,
        ];

        $pdf = Pdf::loadView('surat.peminjaman_kendaraan_new', $data)->setPaper('A4', 'portrait');

        return $pdf->download('Surat_Peminjaman_Kendaraan_' . $request->id . '.pdf');
    }

    /**
     * Generate surat peminjaman aset untuk tamu
     */
    public function suratPeminjamanAset($id)
    {
        $request = PeminjamanAset::with('user')->findOrFail($id);

        // Check authorization
        if (Auth::user()->role !== 'admin' && $request->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        Pdf::setOptions([
            'dpi' => 72,
            'defaultFont' => 'serif',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => false,
            'enable_php' => false,
        ]);

        $tglPinjam  = $request->tanggal_pinjaman ? \Carbon\Carbon::parse($request->tanggal_pinjaman)->translatedFormat('d F Y') : 'N/A';
        $tglSelesai = $request->tanggal_selesai ? \Carbon\Carbon::parse($request->tanggal_selesai)->translatedFormat('d F Y') : 'N/A';
        $jamMulai   = $request->jam_mulai ? \Carbon\Carbon::parse($request->jam_mulai)->format('H:i') : 'N/A';
        $jamSelesai = $request->jam_selesai ? \Carbon\Carbon::parse($request->jam_selesai)->format('H:i') : 'N/A';
        $durasiHari = ($request->tanggal_pinjaman && $request->tanggal_selesai)
            ? \Carbon\Carbon::parse($request->tanggal_pinjaman)->diffInDays(\Carbon\Carbon::parse($request->tanggal_selesai)) + 1
            : null;

        $data = [
            'request' => $request,
            'title' => 'Surat Peminjaman Aset',
            'nomor_surat' => 'SPA/          /2025',
            'tanggal_surat' => now()->translatedFormat('d F Y'),
            'logoPath' => public_path('logo-bpmp-small.png'),
            'tanggalCetak' => now()->translatedFormat('d F Y, H:i'),
            'tglPinjam' => $tglPinjam,
            'tglSelesai' => $tglSelesai,
            'jamMulai' => $jamMulai,
            'jamSelesai' => $jamSelesai,
            'durasiHari' => $durasiHari,
        ];

        $pdf = Pdf::loadView('surat.peminjaman_aset_new', $data)->setPaper('A4', 'portrait');

        return $pdf->download('Surat_Peminjaman_Aset_' . $request->id . '.pdf');
    }

    /**
     * Preview surat peminjaman barang
     */
    public function previewSuratPeminjamanBarang($id)
    {
        $request = RequestModel::with(['user'])->findOrFail($id);

        $pathFromDb = $request->user->signature_image_path; // "signatures/signature_3_1761134229.png"
        $ttdBase64 = null;

        // PERBAIKAN: Cek dulu apakah $pathFromDb tidak kosong
        if (!empty($pathFromDb) && Storage::disk('public')->exists($pathFromDb)) {
            $fileContents = Storage::disk('public')->get($pathFromDb);
            $ttdBase64 = 'data:image/png;base64,' . base64_encode($fileContents);
        }


        // Check authorization
        if (Auth::user()->role !== 'admin' && $request->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $data = [
            'request' => $request,
            'title' => 'Surat Peminjaman Barang',
            'nomor_surat' => 'SPB/          /2025',
            'tanggal_surat' => now()->translatedFormat('d F Y'),
            'logoPath' => asset('logo-bpmp-small.png'),
            'tanggalCetak' => now()->translatedFormat('d F Y, H:i'),
            'ttdBase64' => $ttdBase64,

        ];

        return view('surat.peminjaman_barang_new', $data);
    }

    /**
     * Preview surat permintaan persediaan
     */
    public function previewSuratPermintaanPersediaan($id)
    {
        $request = PermintaanPersediaan::with('user')->findOrFail($id);

        $pathFromDb = $request->user->signature_image_path; // "signatures/signature_3_1761134229.png"
        $ttdBase64 = null;

        // PERBAIKAN: Cek dulu apakah $pathFromDb tidak kosong
        if (!empty($pathFromDb) && Storage::disk('public')->exists($pathFromDb)) {
            $fileContents = Storage::disk('public')->get($pathFromDb);
            $ttdBase64 = 'data:image/png;base64,' . base64_encode($fileContents);
        }


        // Check authorization
        if (Auth::user()->role !== 'admin' && $request->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $data = [
            'request' => $request,
            'title' => 'Surat Permintaan Persediaan',
            'nomor_surat' => 'SPP/          /2025',
            'tanggal_surat' => now()->translatedFormat('d F Y'),
            'logoPath' => asset('logo-bpmp-small.png'),
            'tanggalCetak' => now()->translatedFormat('d F Y, H:i'),
            'ttdBase64' => $ttdBase64,
        ];

        return view('surat.permintaan_persediaan_new', $data);
    }

    /**
     * Preview surat peminjaman kendaraan
     */
    public function previewSuratPeminjamanKendaraan($id)
    {
        $request = PeminjamanKendaraan::with('user')->findOrFail($id);

        $pathFromDb = $request->user->signature_image_path; // "signatures/signature_3_1761134229.png"
        $ttdBase64 = null;

        // PERBAIKAN: Cek dulu apakah $pathFromDb tidak kosong
        if (!empty($pathFromDb) && Storage::disk('public')->exists($pathFromDb)) {
            $fileContents = Storage::disk('public')->get($pathFromDb);
            $ttdBase64 = 'data:image/png;base64,' . base64_encode($fileContents);
        }


        // Check authorization
        if (Auth::user()->role !== 'admin' && $request->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $data = [
            'request' => $request,
            'title' => 'Surat Peminjaman Kendaraan',
            'nomor_surat' => 'SPK/          /2025',
            'tanggal_surat' => now()->translatedFormat('d F Y'),
            'logoPath' => asset('logo-bpmp-small.png'),
            'ttdBase64' => $ttdBase64,
            'tanggalCetak' => now()->translatedFormat('d F Y, H:i'),
        ];

        return view('surat.peminjaman_kendaraan_new', $data);
    }

    /**
     * Preview surat peminjaman aset
     */
    public function previewSuratPeminjamanAset($id)
    {
        $request = PeminjamanAset::with('user')->findOrFail($id);

        // Debug: Log request data
        \Log::info('Preview Surat Peminjaman Aset', [
            'request_id' => $request->id,
            'user_id' => $request->user_id,
            'user_data' => $request->user ? [
                'id' => $request->user->id,
                'nama_lengkap' => $request->user->nama_lengkap,
                'nip' => $request->user->nip,
                'instansi' => $request->user->instansi
            ] : 'User not found'
        ]);

        // Check authorization
        if (Auth::user()->role !== 'admin' && $request->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $data = [
            'request' => $request,
            'title' => 'Surat Peminjaman Aset',
            'nomor_surat' => 'SPA/          /2025',
            'tanggal_surat' => now()->translatedFormat('d F Y'),
        ];

        return view('surat.peminjaman_aset_new', $data);
    }
}