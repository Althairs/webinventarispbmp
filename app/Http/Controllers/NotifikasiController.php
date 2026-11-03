<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use App\Models\SuratPersetujuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class NotifikasiController extends Controller
{
    /**
     * Menampilkan daftar notifikasi user
     */
    public function index()
    {
        $user = Auth::user();
        
        $notifikasis = $user->notifikasis()
            ->with(['suratPersetujuan'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('User.notifikasi', compact('notifikasis'));
    }

    /**
     * Menampilkan detail notifikasi
     */
    public function show($id)
    {
        $notifikasi = Auth::user()->notifikasis()->findOrFail($id);
        
        // Mark as read jika belum dibaca
        if (!$notifikasi->is_read) {
            $notifikasi->markAsRead();
        }

        return view('User.detail_notifikasi', compact('notifikasi'));
    }

    /**
     * Mark notifikasi sebagai sudah dibaca
     */
    public function markAsRead($id)
    {
        $notifikasi = Auth::user()->notifikasis()->findOrFail($id);
        $notifikasi->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi telah ditandai sebagai dibaca'
        ]);
    }

    /**
     * Mark semua notifikasi sebagai sudah dibaca
     */
    public function markAllAsRead()
    {
        Auth::user()->notifikasis()->unread()->update([
            'is_read' => true,
            'read_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Semua notifikasi telah ditandai sebagai dibaca'
        ]);
    }

    /**
     * Hapus notifikasi
     */
    public function destroy($id)
    {
        $notifikasi = Auth::user()->notifikasis()->findOrFail($id);
        $notifikasi->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi berhasil dihapus'
        ]);
    }

    /**
     * API untuk mendapatkan notifikasi yang belum dibaca (untuk AJAX)
     */
    public function getUnreadNotifications()
    {
        $user = Auth::user();
        $unreadCount = $user->getUnreadNotifikasisCount();
        $recentNotifications = $user->unreadNotifikasis()
            ->with(['suratPersetujuan'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'unread_count' => $unreadCount,
            'notifications' => $recentNotifications->map(function($notif) {
                return [
                    'id' => $notif->id,
                    'judul' => $notif->judul,
                    'pesan' => $notif->pesan,
                    'jenis_surat' => $notif->jenis_surat,
                    'created_at' => $notif->created_at->diffForHumans(),
                    'surat_persetujuan_id' => $notif->surat_persetujuan_id
                ];
            })
        ]);
    }

    /**
     * Download surat persetujuan dari notifikasi
     */
    public function downloadSuratPersetujuan($notifikasiId)
    {
        $notifikasi = Auth::user()->notifikasis()->findOrFail($notifikasiId);
        
        if (!$notifikasi->surat_persetujuan_id) {
            abort(404, 'Surat persetujuan tidak ditemukan');
        }

        $suratPersetujuan = SuratPersetujuan::findOrFail($notifikasi->surat_persetujuan_id);
        
        // Mark notifikasi as read
        $notifikasi->markAsRead();

        // Redirect ke download surat persetujuan
        return redirect()->route('admin.surat-persetujuan.download', $suratPersetujuan->id);
    }
}
