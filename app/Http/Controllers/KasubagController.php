<?php

namespace App\Http\Controllers;

use App\Models\RequestModel;
use App\Models\PermintaanPersediaan;
use App\Models\PeminjamanKendaraan;
use App\Models\PeminjamanAset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KasubagController extends Controller
{
    public function __construct()
    {
        // Middleware akan ditangani di routes/web.php
        // Tidak perlu menggunakan $this->middleware() di sini
    }

    /**
     * Dashboard Kasubag - Menampilkan semua pengajuan yang diteruskan oleh admin
     */
    public function dashboard()
    {
        $peminjamanBarang = RequestModel::with(['user', 'reviewedBy'])
            ->whereIn('status', ['diteruskan_ke_kasubag', 'approved', 'rejected'])
            ->orderBy('created_at', 'desc')
            ->get();

        $permintaanPersediaan = PermintaanPersediaan::with(['user', 'reviewedBy'])
            ->whereIn('status', ['diteruskan_ke_kasubag', 'approved', 'rejected'])
            ->orderBy('created_at', 'desc')
            ->get();

        $peminjamanKendaraan = PeminjamanKendaraan::with(['user', 'reviewedBy'])
            ->whereIn('status', ['diteruskan_ke_kasubag', 'approved', 'rejected'])
            ->orderBy('created_at', 'desc')
            ->get();

        $peminjamanAset = PeminjamanAset::with(['user', 'reviewedBy'])
            ->whereIn('status', ['diteruskan_ke_kasubag', 'approved', 'rejected'])
            ->orderBy('created_at', 'desc')
            ->get();

        $totalMenunggu = $peminjamanBarang->where('status', 'diteruskan_ke_kasubag')->count() + 
                       $permintaanPersediaan->where('status', 'diteruskan_ke_kasubag')->count() + 
                       $peminjamanKendaraan->where('status', 'diteruskan_ke_kasubag')->count() + 
                       $peminjamanAset->where('status', 'diteruskan_ke_kasubag')->count();

        return view('kasubag.dashboard', compact(
            'peminjamanBarang',
            'permintaanPersediaan', 
            'peminjamanKendaraan',
            'peminjamanAset',
            'totalMenunggu'
        ));
    }

    /**
     * Halaman khusus permintaan peminjaman barang
     */
    public function peminjamanBarang()
    {
        $requests = RequestModel::with(['user', 'reviewedBy'])
            ->whereIn('status', ['diteruskan_ke_kasubag', 'approved', 'rejected'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('kasubag.peminjaman_barang', compact('requests'));
    }

    /**
     * Halaman khusus permintaan persediaan
     */
    public function permintaanPersediaan()
    {
        $requests = PermintaanPersediaan::with(['user', 'reviewedBy'])
            ->whereIn('status', ['diteruskan_ke_kasubag', 'approved', 'rejected'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('kasubag.permintaan_persediaan', compact('requests'));
    }

    /**
     * Halaman khusus peminjaman kendaraan
     */
    public function peminjamanKendaraan()
    {
        $requests = PeminjamanKendaraan::with(['user', 'reviewedBy'])
            ->whereIn('status', ['diteruskan_ke_kasubag', 'approved', 'rejected'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('kasubag.peminjaman_kendaraan', compact('requests'));
    }

    /**
     * Halaman khusus peminjaman aset
     */
    public function peminjamanAset()
    {
        $requests = PeminjamanAset::with(['user', 'reviewedBy'])
            ->whereIn('status', ['diteruskan_ke_kasubag', 'approved', 'rejected'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('kasubag.peminjaman_aset', compact('requests'));
    }

    /**
     * Approval peminjaman barang
     */
    public function approvePeminjamanBarang(Request $request, $id)
    {
        $requestData = RequestModel::findOrFail($id);
        
        if ($requestData->status !== 'diteruskan_ke_kasubag') {
            return back()->with('error', 'Status pengajuan tidak valid.');
        }

        $validated = $request->validate([
            'action' => 'required|in:approve,reject',
            'komentar' => 'nullable|string',
        ]);

        if ($validated['action'] === 'approve') {
            $requestData->update([
                'status' => 'approved',
                'approved_by_kasubag_id' => Auth::id(),
                'approved_by_kasubag_date' => now(),
                'komentar' => $validated['komentar']
            ]);
            
            $message = 'Peminjaman barang berhasil diterima.';
        } else {
            $requestData->update([
                'status' => 'rejected',
                'komentar' => $validated['komentar']
            ]);
            
            $message = 'Peminjaman barang telah ditolak.';
        }

        return back()->with('success', $message);
    }

    /**
     * Approval permintaan persediaan
     */
    public function approvePermintaanPersediaan(Request $request, $id)
    {
        $requestData = PermintaanPersediaan::findOrFail($id);
        
        if ($requestData->status !== 'diteruskan_ke_kasubag') {
            return back()->with('error', 'Status pengajuan tidak valid.');
        }

        $validated = $request->validate([
            'action' => 'required|in:approve,reject',
            'komentar' => 'nullable|string',
        ]);

        if ($validated['action'] === 'approve') {
            $requestData->update([
                'status' => 'approved',
                'approved_by_kasubag_id' => Auth::id(),
                'approved_by_kasubag_date' => now(),
                'komentar' => $validated['komentar']
            ]);
            
            $message = 'Permintaan persediaan berhasil diterima.';
        } else {
            $requestData->update([
                'status' => 'rejected',
                'komentar' => $validated['komentar']
            ]);
            
            $message = 'Permintaan persediaan telah ditolak.';
        }

        return back()->with('success', $message);
    }

    /**
     * Approval peminjaman kendaraan
     */
    public function approvePeminjamanKendaraan(Request $request, $id)
    {
        $requestData = PeminjamanKendaraan::findOrFail($id);
        
        if ($requestData->status !== 'diteruskan_ke_kasubag') {
            return back()->with('error', 'Status pengajuan tidak valid.');
        }

        $validated = $request->validate([
            'action' => 'required|in:approve,reject',
            'komentar' => 'nullable|string',
        ]);

        if ($validated['action'] === 'approve') {
            $requestData->update([
                'status' => 'approved',
                'approved_by_kasubag_id' => Auth::id(),
                'approved_by_kasubag_date' => now(),
                'komentar' => $validated['komentar']
            ]);
            
            $message = 'Peminjaman kendaraan berhasil diterima.';
        } else {
            $requestData->update([
                'status' => 'rejected',
                'komentar' => $validated['komentar']
            ]);
            
            $message = 'Peminjaman kendaraan telah ditolak.';
        }

        return back()->with('success', $message);
    }

    /**
     * Approval peminjaman aset
     */
    public function approvePeminjamanAset(Request $request, $id)
    {
        $requestData = PeminjamanAset::findOrFail($id);
        
        if ($requestData->status !== 'diteruskan_ke_kasubag') {
            return back()->with('error', 'Status pengajuan tidak valid.');
        }

        $validated = $request->validate([
            'action' => 'required|in:approve,reject',
            'komentar' => 'nullable|string',
        ]);

        if ($validated['action'] === 'approve') {
            $requestData->update([
                'status' => 'approved',
                'approved_by_kasubag_id' => Auth::id(),
                'approved_by_kasubag_date' => now(),
                'komentar' => $validated['komentar']
            ]);
            
            $message = 'Peminjaman aset berhasil diterima.';
        } else {
            $requestData->update([
                'status' => 'rejected',
                'komentar' => $validated['komentar']
            ]);
            
            $message = 'Peminjaman aset telah ditolak.';
        }

        return back()->with('success', $message);
    }

    /**
     * Detail pengajuan khusus untuk kasubag
     */
    public function detailPeminjamanBarang($id)
    {
        $requestData = RequestModel::with(['user', 'reviewedBy'])
            ->where('id', $id)
            ->whereIn('status', ['diteruskan_ke_kasubag', 'approved', 'rejected'])
            ->firstOrFail();

        return view('kasubag.detail_peminjaman_barang', compact('requestData'));
    }

    public function detailPermintaanPersediaan($id)
    {
        $requestData = PermintaanPersediaan::with(['user', 'reviewedBy'])
            ->where('id', $id)
            ->whereIn('status', ['diteruskan_ke_kasubag', 'approved', 'rejected'])
            ->firstOrFail();

        return view('kasubag.detail_permintaan_persediaan', compact('requestData'));
    }

    public function detailPeminjamanKendaraan($id)
    {
        $requestData = PeminjamanKendaraan::with(['user', 'reviewedBy'])
            ->where('id', $id)
            ->whereIn('status', ['diteruskan_ke_kasubag', 'approved', 'rejected'])
            ->firstOrFail();

        return view('kasubag.detail_peminjaman_kendaraan', compact('requestData'));
    }

    public function detailPeminjamanAset($id)
    {
        $requestData = PeminjamanAset::with(['user', 'reviewedBy', 'approvedByKasubag'])
            ->where('id', $id)
            ->whereIn('status', ['diteruskan_ke_kasubag', 'approved', 'rejected'])
            ->firstOrFail();

        return view('kasubag.detail_peminjaman_aset', compact('requestData'));
    }

    /**
     * View surat peminjaman aset untuk kasubag
     */
    public function viewSuratPeminjamanAset($id)
    {
        $request = PeminjamanAset::findOrFail($id);
        
        if (!$request->surat_path) {
            abort(404, 'Surat tidak ditemukan');
        }
        
        $filePath = storage_path('app/public/' . $request->surat_path);
        
        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan');
        }
        
        return response()->file($filePath);
    }

    /**
     * Download surat peminjaman aset untuk kasubag
     */
    public function downloadSuratPeminjamanAset($id)
    {
        $request = PeminjamanAset::findOrFail($id);
        
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
}
