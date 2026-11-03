<?php

namespace App\Http\Controllers;

use App\Models\RequestModel;
use App\Models\User;
use App\Models\PermintaanPersediaan;
use App\Models\PeminjamanKendaraan;
use App\Models\PeminjamanAset;
use App\Models\Persediaan;
use App\Models\AsetTetap;
use App\Models\TransaksiMasukPersediaan;
use App\Models\TransaksiMasukAsetTetap;
use App\Models\TransaksiKeluarPersediaan;
use App\Models\TransaksiKeluarAsetTetap;
use App\Models\MutasiBarang;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    //     $this->middleware('role:admin');
    // }

    public function dashboard()
    {
        // Statistik Utama
        $stats = [
            'totalPersediaan' => Persediaan::count(),
            'totalAsetTetap' => AsetTetap::count(),
            'MenungguRequests' => RequestModel::where('status', 'Menunggu')->count(),
            'totalUsers' => User::count(),
            'transaksiMasukHariIni' => TransaksiMasukPersediaan::whereDate('created_at', today())->count() + 
                                      TransaksiMasukAsetTetap::whereDate('created_at', today())->count(),
            'transaksiKeluarHariIni' => TransaksiKeluarPersediaan::whereDate('created_at', today())->count() + 
                                       TransaksiKeluarAsetTetap::whereDate('created_at', today())->count(),
            'mutasiHariIni' => MutasiBarang::whereDate('created_at', today())->count(),
            'totalPengajuan' => RequestModel::count() + PermintaanPersediaan::count() + 
                              PeminjamanKendaraan::count() + PeminjamanAset::count(),
        ];

        // Aktivitas Terbaru
        $recentActivities = collect();
        
        // Ambil 5 transaksi masuk terbaru
        $recentTransaksiMasuk = TransaksiMasukPersediaan::with('persediaan')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();
            
        foreach($recentTransaksiMasuk as $transaksi) {
            $recentActivities->push([
                'icon' => 'fa-arrow-down',
                'description' => "Transaksi masuk: {$transaksi->nama_barang} (+{$transaksi->jumlah_masuk})",
                'time' => $transaksi->created_at->diffForHumans()
            ]);
        }

        // Ambil 5 transaksi keluar terbaru
        $recentTransaksiKeluar = TransaksiKeluarPersediaan::with('persediaan')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();
            
        foreach($recentTransaksiKeluar as $transaksi) {
            $recentActivities->push([
                'icon' => 'fa-arrow-up',
                'description' => "Transaksi keluar: {$transaksi->nama_barang} (-{$transaksi->jumlah_keluar})",
                'time' => $transaksi->created_at->diffForHumans()
            ]);
        }

        // Ambil 5 mutasi terbaru
        $recentMutasi = MutasiBarang::with('asetTetap')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();
            
        foreach($recentMutasi as $mutasi) {
            $recentActivities->push([
                'icon' => 'fa-exchange-alt',
                'description' => "Mutasi: {$mutasi->nama_barang} ({$mutasi->lokasi_awal} → {$mutasi->lokasi_akhir})",
                'time' => $mutasi->created_at->diffForHumans()
            ]);
        }

        // Ambil 5 request terbaru
        $recentRequests = RequestModel::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();
            
        foreach($recentRequests as $request) {
            $recentActivities->push([
                'icon' => 'fa-clipboard-list',
                'description' => "Request baru: {$request->nama_barang} oleh {$request->user->name}",
                'time' => $request->created_at->diffForHumans()
            ]);
        }

        // Sort by created_at desc and take first 5
        $stats['recentActivities'] = $recentActivities->sortByDesc(function($activity) {
            return $activity['time'];
        })->take(5);

        return view('Admin.index', compact('stats'));
    }







    public function request_barang()
    {
        $requests = RequestModel::with('user')->get();
        return view('Admin.request_barang', compact('requests'));
    }

    public function laporan()
    {
        // Statistik Utama
        $stats = [
            'totalPersediaan' => Persediaan::count(),
            'totalAsetTetap' => AsetTetap::count(),
            'totalTransaksi' => TransaksiMasukPersediaan::count() + TransaksiMasukAsetTetap::count() + 
                              TransaksiKeluarPersediaan::count() + TransaksiKeluarAsetTetap::count(),
            'totalMutasi' => MutasiBarang::count(),
            'transaksiMasuk' => TransaksiMasukPersediaan::count() + TransaksiMasukAsetTetap::count(),
            'transaksiKeluar' => TransaksiKeluarPersediaan::count() + TransaksiKeluarAsetTetap::count(),
            'transaksiMasukHariIni' => TransaksiMasukPersediaan::whereDate('created_at', today())->count() + 
                                      TransaksiMasukAsetTetap::whereDate('created_at', today())->count(),
            'transaksiKeluarHariIni' => TransaksiKeluarPersediaan::whereDate('created_at', today())->count() + 
                                       TransaksiKeluarAsetTetap::whereDate('created_at', today())->count(),
            'mutasiHariIni' => MutasiBarang::whereDate('created_at', today())->count(),
            'totalPengajuan' => RequestModel::count() + PermintaanPersediaan::count() + 
                              PeminjamanKendaraan::count() + PeminjamanAset::count(),
            'MenungguRequests' => RequestModel::where('status', 'Menunggu')->count(),
        ];

        // Data untuk grafik
        $chartData = [
            'monthlyLabels' => $this->getMonthlyLabels(),
            'monthlyMasuk' => $this->getMonthlyTransactionData('masuk'),
            'monthlyKeluar' => $this->getMonthlyTransactionData('keluar'),
            'categoryLabels' => $this->getCategoryLabels(),
            'categoryData' => $this->getCategoryData(),
            'mutationLabels' => $this->getMonthlyLabels(),
            'mutationData' => $this->getMonthlyMutationData(),
            'stockLabels' => $this->getMonthlyLabels(),
            'stockPersediaan' => $this->getMonthlyStockData('persediaan'),
            'stockAsetTetap' => $this->getMonthlyStockData('aset_tetap'),
        ];

        // Summary kategori
        $categorySummary = $this->getCategorySummary();

        // Aktivitas terbaru
        $recentActivities = $this->getRecentActivities();

        return view('Admin.laporan', compact('stats', 'categorySummary', 'chartData', 'recentActivities'));
    }

    private function getMonthlyLabels()
    {
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $months[] = now()->subMonths($i)->format('M');
        }
        return $months;
    }

    private function getMonthlyTransactionData($type)
    {
        $data = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();
            
            if ($type === 'masuk') {
                $count = TransaksiMasukPersediaan::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count() +
                        TransaksiMasukAsetTetap::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
            } else {
                $count = TransaksiKeluarPersediaan::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count() +
                        TransaksiKeluarAsetTetap::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
            }
            
            $data[] = $count;
        }
        return $data;
    }

    private function getCategoryLabels()
    {
        $categories = Persediaan::select('kategori')->distinct()->pluck('kategori')->toArray();
        
        // Cek apakah kolom kategori ada di tabel aset_tetap
        try {
            $asetCategories = AsetTetap::select('kategori')->distinct()->pluck('kategori')->toArray();
        } catch (\Exception $e) {
            // Jika kolom kategori belum ada, gunakan array kosong
            $asetCategories = [];
        }
        
        return array_unique(array_merge($categories, $asetCategories));
    }

    private function getCategoryData()
    {
        $categories = $this->getCategoryLabels();
        $data = [];
        
        foreach ($categories as $category) {
            $count = Persediaan::where('kategori', $category)->count();
            
            // Cek apakah kolom kategori ada di tabel aset_tetap
            try {
                $count += AsetTetap::where('kategori', $category)->count();
            } catch (\Exception $e) {
                // Jika kolom kategori belum ada, skip
            }
            
            $data[] = $count;
        }
        
        return $data;
    }

    private function getMonthlyMutationData()
    {
        $data = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();
            
            $count = MutasiBarang::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
            $data[] = $count;
        }
        return $data;
    }

    private function getMonthlyStockData($type)
    {
        $data = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();
            
            if ($type === 'persediaan') {
                $stock = Persediaan::whereBetween('created_at', [$startOfMonth, $endOfMonth])->sum('stok');
            } else {
                $stock = AsetTetap::whereBetween('created_at', [$startOfMonth, $endOfMonth])->sum('stok');
            }
            
            $data[] = $stock;
        }
        return $data;
    }

    private function getCategorySummary()
    {
        $categories = $this->getCategoryLabels();
        $summary = collect();
        
        foreach ($categories as $category) {
            $persediaan = Persediaan::where('kategori', $category)->get();
            
            $total = $persediaan->count();
            $stok = $persediaan->sum('stok');
            $nilai = $persediaan->sum('harga');
            
            // Cek apakah kolom kategori ada di tabel aset_tetap
            try {
                $asetTetap = AsetTetap::where('kategori', $category)->get();
                $total += $asetTetap->count();
                $stok += $asetTetap->sum('stok');
                $nilai += $asetTetap->sum('nilai_perolehan');
            } catch (\Exception $e) {
                // Jika kolom kategori belum ada, skip
            }
            
            $summary->put($category, [
                'total' => $total,
                'stok' => $stok,
                'nilai' => $nilai
            ]);
        }
        
        return $summary;
    }

    private function getRecentActivities()
    {
        $activities = collect();
        
        // Transaksi masuk terbaru
        $recentMasuk = TransaksiMasukPersediaan::with('persediaan')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();
            
        foreach($recentMasuk as $transaksi) {
            $activities->push([
                'icon' => 'fa-arrow-down',
                'description' => "Transaksi masuk: {$transaksi->nama_barang} (+{$transaksi->jumlah_masuk})",
                'time' => $transaksi->created_at->diffForHumans()
            ]);
        }

        // Transaksi keluar terbaru
        $recentKeluar = TransaksiKeluarPersediaan::with('persediaan')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();
            
        foreach($recentKeluar as $transaksi) {
            $activities->push([
                'icon' => 'fa-arrow-up',
                'description' => "Transaksi keluar: {$transaksi->nama_barang} (-{$transaksi->jumlah_keluar})",
                'time' => $transaksi->created_at->diffForHumans()
            ]);
        }

        // Mutasi terbaru
        $recentMutasi = MutasiBarang::with('asetTetap')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();
            
        foreach($recentMutasi as $mutasi) {
            $activities->push([
                'icon' => 'fa-exchange-alt',
                'description' => "Mutasi: {$mutasi->nama_barang} ({$mutasi->lokasi_awal} → {$mutasi->lokasi_akhir})",
                'time' => $mutasi->created_at->diffForHumans()
            ]);
        }

        return $activities->sortByDesc(function($activity) {
            return $activity['time'];
        })->take(5);
    }

    public function downloadBulanan()
    {
        // Logika untuk mengambil data laporan bulanan
        // Misalnya, mengambil data dari database
        $data = DB::table('laporan_bulanan')->get();

        // Di sini, Anda bisa membuat file CSV, PDF, atau format lain
        // Contoh sederhana untuk file CSV
        $filename = 'laporan_bulanan_' . date('Y-m-d') . '.csv';
        $handle = fopen($filename, 'w+');
        fputcsv($handle, ['Kolom 1', 'Kolom 2', 'Kolom 3']); // Header CSV
        
        foreach($data as $row) {
            fputcsv($handle, [$row->kolom1, $row->kolom2, $row->kolom3]); // Isi data
        }
        
        fclose($handle);

        return Response::download($filename, $filename, ['Content-Type' => 'text/csv']);
    }

    public function exportPengajuan()
    {
        // Ambil semua pengajuan dan gabungkan
        $peminjamanBarang = RequestModel::with('user')->orderBy('created_at','desc')->get(['id','kode_barang','nama_barang','kategori','merek','jumlah','status','created_at']);
        $permintaanPersediaan = PermintaanPersediaan::with('user')->orderBy('created_at','desc')->get(['id','nama_barang','merek','jumlah','status','created_at']);
        $peminjamanKendaraan = PeminjamanKendaraan::with('user')->orderBy('created_at','desc')->get(['id','nama_kendaraan as nama_barang','merek','jumlah','status','created_at']);
        // Tabel peminjaman_asets tidak memiliki kolom 'jumlah', jadi jangan pilih 'jumlah'
        $peminjamanAset = PeminjamanAset::with('user')->orderBy('created_at','desc')->get(['id','nama_barang','jenis as kategori','status','created_at']);

        $dir = storage_path('app/exports');
        if (!is_dir($dir)) { @mkdir($dir, 0775, true); }
        $filename = 'laporan_pengajuan_' . date('Y-m-d_His') . '.xls';
        $path = $dir . DIRECTORY_SEPARATOR . $filename;
        
        // Prepare data
        $rows = [];
        $rows[] = ['Sumber Pengajuan','ID','Nama Barang/Kendaraan','Kategori/Jenis','Merek','Jumlah','Status','Tanggal Pengajuan'];

        foreach ($peminjamanBarang as $row) {
            $rows[] = ['Peminjaman Barang', $row->id, $row->nama_barang, $row->kategori ?? '-', $row->merek ?? '-', $row->jumlah ?? 1, $row->status, $row->created_at];
        }
        foreach ($permintaanPersediaan as $row) {
            $rows[] = ['Permintaan Persediaan', $row->id, $row->nama_barang, '-', $row->merek ?? '-', $row->jumlah ?? 1, $row->status, $row->created_at];
        }
        foreach ($peminjamanKendaraan as $row) {
            $rows[] = ['Peminjaman Kendaraan', $row->id, $row->nama_barang, '-', $row->merek ?? '-', $row->jumlah ?? 1, $row->status, $row->created_at];
        }
        foreach ($peminjamanAset as $row) {
            $rows[] = ['Peminjaman Aset', $row->id, $row->nama_barang, $row->kategori ?? '-', '-', 1, $row->status, $row->created_at];
        }

        $this->writeExcelFile($path, $rows);

        return Response::download($path, $filename, ['Content-Type' => 'application/vnd.ms-excel'])
            ->deleteFileAfterSend(true);
    }

    public function exportMutasi()
    {
        $start = request('start');
        $end = request('end');
        if (request()->filled('month')) {
            try { $m = \Carbon\Carbon::createFromFormat('Y-m', request('month')); $start = $m->startOfMonth()->toDateString(); $end = $m->endOfMonth()->toDateString(); } catch (\Exception $e) {}
        }
        $q = MutasiBarang::with('asetTetap')->orderBy('created_at', 'desc');
        if ($start) { $q->whereDate('created_at','>=',$start); }
        if ($end) { $q->whereDate('created_at','<=',$end); }
        $mutasi = $q->get();

        $dir = storage_path('app/exports');
        if (!is_dir($dir)) { @mkdir($dir, 0775, true); }
        $filename = 'laporan_mutasi_' . date('Y-m-d_His') . '.xls';
        $path = $dir . DIRECTORY_SEPARATOR . $filename;
        
        // Prepare data
        $rows = [];
        $rows[] = ['Tanggal Mutasi','Kode Barang','Nama Barang','NUP','Lokasi Awal','Lokasi Akhir','Keterangan'];
        
        foreach ($mutasi as $item) {
            $rows[] = [
                $item->tanggal_mutasi,
                $item->kode_barang,
                $item->nama_barang,
                $item->nup,
                $item->lokasi_awal,
                $item->lokasi_akhir,
                $item->keterangan
            ];
        }
        
        $this->writeExcelFile($path, $rows);
        return Response::download($path, $filename, ['Content-Type' => 'application/vnd.ms-excel'])
            ->deleteFileAfterSend(true);
    }

    public function downloadMutasi()
    {
        // Ambil data mutasi dari database
        $mutasi = MutasiBarang::with('asetTetap')->orderBy('created_at', 'desc')->get();
        
        // Buat file CSV
        $filename = 'laporan_mutasi_' . date('Y-m-d') . '.csv';
        $handle = fopen($filename, 'w+');
        
        // Header CSV
        fputcsv($handle, [
            'Tanggal Mutasi',
            'Kode Barang', 
            'Nama Barang',
            'NUP',
            'Lokasi Awal',
            'Lokasi Akhir',
            'Keterangan'
        ]);
        
        // Data CSV
        foreach($mutasi as $item) {
            fputcsv($handle, [
                $item->tanggal_mutasi,
                $item->kode_barang,
                $item->nama_barang,
                $item->nup,
                $item->lokasi_awal,
                $item->lokasi_akhir,
                $item->keterangan
            ]);
        }
        
        fclose($handle);
        
        return Response::download($filename, $filename, ['Content-Type' => 'text/csv']);
    }

    // Method untuk Manajemen Pengajuan - Peminjaman Barang
    public function peminjamanBarang(Request $request)
    {
        $search = $request->get('search');
        $perPage = $request->get('per_page', 10);
        
        $query = RequestModel::with(['user', 'suratPersetujuan'])->orderBy('created_at', 'desc');
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('kode_barang', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%")
                  ->orWhere('merek', 'like', "%{$search}%")
                  ->orWhere('deskripsi_peruntukan', 'like', "%{$search}%")
                  ->orWhere('ruangan', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('nama_lengkap', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%")
                               ->orWhere('username', 'like', "%{$search}%");
                  });
            });
        }
        
        $requests = $query->paginate($perPage);
        $requests->appends($request->query());
        
        return view('Admin.peminjaman_barang', compact('requests'));
    }

    // Method untuk approve/reject peminjaman barang
    public function approvePeminjamanBarang(Request $request, $id)
    {
        $requestModel = RequestModel::findOrFail($id);
        $requestModel->update([
            'status' => 'approved',
            'approved_by_admin_id' => Auth::id(),
            'komentar' => $request->comment ?? null
        ]);
        return response()->json(['message' => 'Peminjaman barang berhasil diterima.']);
    }

    public function rejectPeminjamanBarang(Request $request, $id)
    {
        $requestModel = RequestModel::findOrFail($id);
        $requestModel->update([
            'status' => 'rejected',
            'approved_by_admin_id' => Auth::id(),
            'komentar' => $request->comment ?? null
        ]);
        return response()->json(['message' => 'Peminjaman barang berhasil ditolak.']);
    }

    // Method untuk Manajemen Pengajuan - Permintaan Persediaan
    public function permintaanPersediaan(Request $request)
    {
        $search = $request->get('search');
        $perPage = $request->get('per_page', 10);
        
        $query = PermintaanPersediaan::with(['user', 'suratPersetujuan'])->orderBy('created_at', 'desc');
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                  ->orWhere('merek', 'like', "%{$search}%")
                  ->orWhere('deskripsi_peruntukan', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('nama_lengkap', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%")
                               ->orWhere('username', 'like', "%{$search}%");
                  });
            });
        }
        
        $requests = $query->paginate($perPage);
        $requests->appends($request->query());
        
        return view('Admin.permintaan_persediaan', compact('requests'));
    }

    // Method untuk Manajemen Pengajuan - Peminjaman Kendaraan
    public function peminjamanKendaraan(Request $request)
    {
        $search = $request->get('search');
        $perPage = $request->get('per_page', 10);
        
        $query = PeminjamanKendaraan::with(['user', 'suratPersetujuan'])->orderBy('created_at', 'desc');
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_kendaraan', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%")
                  ->orWhere('merek', 'like', "%{$search}%")
                  ->orWhere('deskripsi_peruntukan', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('nama_lengkap', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%")
                               ->orWhere('username', 'like', "%{$search}%");
                  });
            });
        }
        
        $requests = $query->paginate($perPage);
        $requests->appends($request->query());
        
        return view('Admin.peminjaman_kendaraan', compact('requests'));
    }

    // Method untuk Manajemen Pengajuan - Peminjaman Aset
    public function peminjamanAset(Request $request)
    {
        $search = $request->get('search');
        $perPage = $request->get('per_page', 10);
        
        // Eager load dengan relasi suratPersetujuan - sama seperti peminjaman kendaraan
        // Relasi suratPersetujuan di model sudah otomatis filter SPA dan latest
        $query = PeminjamanAset::with(['user', 'suratPersetujuan'])->orderBy('created_at', 'desc');
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_instansi', 'like', "%{$search}%")
                  ->orWhere('nama_peminjam', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%")
                  ->orWhere('nama_barang', 'like', "%{$search}%")
                  ->orWhere('jenis', 'like', "%{$search}%")
                  ->orWhere('kabupaten_kota', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('nama_lengkap', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%")
                               ->orWhere('username', 'like', "%{$search}%");
                  });
            });
        }
        
        $requests = $query->paginate($perPage);
        $requests->appends($request->query());
        
        return view('Admin.peminjaman_aset', compact('requests'));
    }

    // Method untuk approve/reject permintaan persediaan
    public function approvePermintaanPersediaan($id)
    {
        $request = PermintaanPersediaan::findOrFail($id);
        $request->update(['status' => 'approved']);
        return redirect()->back()->with('success', 'Permintaan persediaan berhasil diterima.');
    }

    public function rejectPermintaanPersediaan($id)
    {
        $request = PermintaanPersediaan::findOrFail($id);
        $request->update(['status' => 'rejected']);
        return redirect()->back()->with('success', 'Permintaan persediaan berhasil ditolak.');
    }

    // Method untuk approve/reject peminjaman kendaraan
    public function approvePeminjamanKendaraan($id)
    {
        $request = PeminjamanKendaraan::findOrFail($id);
        $request->update(['status' => 'approved']);
        return redirect()->back()->with('success', 'Peminjaman kendaraan berhasil diterima.');
    }

    public function rejectPeminjamanKendaraan($id)
    {
        $request = PeminjamanKendaraan::findOrFail($id);
        $request->update(['status' => 'rejected']);
        return redirect()->back()->with('success', 'Peminjaman kendaraan berhasil ditolak.');
    }

    // Method untuk approve/reject peminjaman aset
    public function approvePeminjamanAset($id)
    {
        $request = PeminjamanAset::findOrFail($id);
        $request->update(['status' => 'approved']);
        return redirect()->back()->with('success', 'Peminjaman aset berhasil diterima.');
    }

    public function rejectPeminjamanAset($id)
    {
        $request = PeminjamanAset::findOrFail($id);
        $request->update(['status' => 'rejected']);
        return redirect()->back()->with('success', 'Peminjaman aset berhasil ditolak.');
    }

    // Method untuk mengambil detail data via AJAX
    public function getDetailPeminjamanBarang($id)
    {
        try {
            $request = RequestModel::with(['user', 'suratPersetujuan'])->findOrFail($id);
            return response()->json($request);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Permintaan tidak ditemukan.'], 404);
        }
    }

    public function getDetailPermintaanPersediaan($id)
    {
        try {
            $request = PermintaanPersediaan::with(['user', 'suratPersetujuan'])->findOrFail($id);
            return response()->json($request);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Permintaan tidak ditemukan.'], 404);
        }
    }

    public function getDetailPeminjamanKendaraan($id)
    {
        try {
            $request = PeminjamanKendaraan::with(['user', 'suratPersetujuan'])->findOrFail($id);
            return response()->json($request);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Permintaan tidak ditemukan.'], 404);
        }
    }

    public function getDetailPeminjamanAset($id)
    {
        $request = PeminjamanAset::with('user')->findOrFail($id);
        return response()->json($request);
    }

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

    // ===== FUNGSI REVIEW =====
    
    /**
     * Mengubah status pengajuan menjadi "direview"
     */
    public function reviewPeminjamanBarang(Request $request, $id)
    {
        try {
            $peminjaman = RequestModel::findOrFail($id);
            
            // Check current status
            if ($peminjaman->status === 'direview') {
                return response()->json([
                    'message' => 'Pengajuan sudah dalam status direview.',
                    'status' => 'already_reviewed'
                ], 400);
            }
            
            if ($peminjaman->status !== 'Menunggu') {
                return response()->json([
                    'message' => 'Hanya pengajuan dengan status Menunggu yang dapat direview. Status saat ini: ' . $peminjaman->status,
                    'status' => 'invalid_status'
                ], 400);
            }
            
            $peminjaman->update([
                'status' => 'direview',
                'reviewed_by_admin_id' => Auth::id()
            ]);
            
            return response()->json([
                'message' => 'Pengajuan peminjaman barang telah direview.',
                'status' => 'success'
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Error in reviewPeminjamanBarang: ' . $e->getMessage(), [
                'id' => $id,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'message' => 'Gagal mereview pengajuan: ' . $e->getMessage(),
                'status' => 'error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function reviewPermintaanPersediaan(Request $request, $id)
    {
        try {
            $permintaan = PermintaanPersediaan::findOrFail($id);
            
            // Check current status
            if ($permintaan->status === 'direview') {
                return response()->json([
                    'message' => 'Pengajuan sudah dalam status direview.',
                    'status' => 'already_reviewed'
                ], 400);
            }
            
            if ($permintaan->status !== 'Menunggu') {
                return response()->json([
                    'message' => 'Hanya pengajuan dengan status Menunggu yang dapat direview. Status saat ini: ' . $permintaan->status,
                    'status' => 'invalid_status'
                ], 400);
            }
            
            $permintaan->update([
                'status' => 'direview',
                'reviewed_by_admin_id' => Auth::id()
            ]);
            
            return response()->json([
                'message' => 'Pengajuan permintaan persediaan telah direview.',
                'status' => 'success'
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Error in reviewPermintaanPersediaan: ' . $e->getMessage(), [
                'id' => $id,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'message' => 'Gagal mereview pengajuan: ' . $e->getMessage(),
                'status' => 'error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function reviewPeminjamanKendaraan(Request $request, $id)
    {
        try {
            $kendaraan = PeminjamanKendaraan::findOrFail($id);
            
            // Check current status
            if ($kendaraan->status === 'direview') {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['message' => 'Pengajuan sudah dalam status direview.'], 400);
                }
                return back()->with('error', 'Pengajuan sudah dalam status direview.');
            }
            
            if ($kendaraan->status !== 'Menunggu') {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['message' => 'Hanya pengajuan dengan status Menunggu yang dapat direview. Status saat ini: ' . $kendaraan->status], 400);
                }
                return back()->with('error', 'Hanya pengajuan dengan status Menunggu yang dapat direview. Status saat ini: ' . $kendaraan->status);
            }
            
            $kendaraan->update([
                'status' => 'direview',
                'reviewed_by_admin_id' => Auth::id()
            ]);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['message' => 'Pengajuan peminjaman kendaraan telah direview.']);
            }
            return back()->with('success', 'Pengajuan peminjaman kendaraan telah direview.');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['message' => 'Gagal mereview pengajuan: ' . $e->getMessage()], 500);
            }
            return back()->with('error', 'Gagal mereview pengajuan: ' . $e->getMessage());
        }
    }

    public function reviewPeminjamanAset($id)
    {
        $request = PeminjamanAset::findOrFail($id);
        if ($request->status !== 'Menunggu') {
            return back()->with('error', 'Hanya pengajuan dengan status Menunggu yang dapat direview.');
        }
        
        $request->update([
            'status' => 'direview',
            'reviewed_by_admin_id' => Auth::id()
        ]);
        
        return back()->with('success', 'Pengajuan peminjaman aset telah direview.');
    }

    // ===== FUNGSI FORWARD =====

    /**
     * Mengubah status pengajuan menjadi "diteruskan_ke_kasubag" atau "rejected"
     */
    public function forwardPeminjamanBarang(Request $request1, $id)
    {
        try {
            $request = RequestModel::findOrFail($id);
            
            // Check current status and provide appropriate message
            if ($request->status === 'diteruskan_ke_kasubag') {
                return response()->json([
                    'message' => 'Pengajuan sudah diteruskan ke Kasubag sebelumnya.',
                    'status' => 'already_forwarded'
                ], 400);
            }
            
            if ($request->status !== 'direview') {
                return response()->json([
                    'message' => 'Hanya pengajuan dengan status direview yang dapat diteruskan. Status saat ini: ' . $request->status,
                    'status' => 'invalid_status'
                ], 400);
            }

            $validated = $request1->validate([
                'action' => 'required|in:forward,reject',
            ]);

            if ($validated['action'] === 'forward') {
                $request->update([
                    'status' => 'diteruskan_ke_kasubag',
                    'diteruskan_ke_kasubag_date' => now(),
                ]);
                $message = 'Pengajuan peminjaman barang telah diteruskan ke Kasubag.';
            } else {
                $request->update([
                    'status' => 'rejected',
                ]);
                $message = 'Pengajuan peminjaman barang telah ditolak.';
            }

            return response()->json(['message' => $message, 'status' => 'success'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal memproses pengajuan: ' . $e->getMessage()], 500);
        }
    }

    public function forwardPermintaanPersediaan(Request $request1, $id)
    {
        try {
            $request = PermintaanPersediaan::findOrFail($id);
            $isAjax = $request1->ajax() 
                || $request1->wantsJson() 
                || $request1->header('X-Requested-With') === 'XMLHttpRequest'
                || $request1->expectsJson();

            if ($request->status === 'diteruskan_ke_kasubag') {
                if ($isAjax) {
                    return response()->json([
                        'message' => 'Pengajuan sudah diteruskan ke Kasubag sebelumnya.',
                        'status' => 'already_forwarded'
                    ], 400);
                }
                return back()->with('error', 'Pengajuan sudah diteruskan ke Kasubag sebelumnya.');
            }

            if ($request->status !== 'direview') {
                if ($isAjax) {
                    return response()->json([
                        'message' => 'Hanya pengajuan dengan status direview yang dapat diteruskan. Status saat ini: ' . $request->status,
                        'status' => 'invalid_status'
                    ], 400);
                }
                return back()->with('error', 'Hanya pengajuan dengan status direview yang dapat diteruskan.');
            }

            $validated = $request1->validate([
                'action' => 'required|in:forward,reject',
                'komentar' => 'required|string',
            ]);

            if ($validated['action'] === 'forward') {
                $request->update([
                    'status' => 'diteruskan_ke_kasubag',
                    'diteruskan_ke_kasubag_date' => now(),
                    'komentar' => $validated['komentar']
                ]);
                $message = 'Pengajuan permintaan persediaan telah diteruskan ke Kasubag.';
            } else {
                $request->update([
                    'status' => 'rejected',
                    'komentar' => $validated['komentar']
                ]);
                $message = 'Pengajuan permintaan persediaan telah ditolak.';
            }

            if ($isAjax) {
                return response()->json(['message' => $message, 'status' => 'success'], 200);
            }
            return back()->with('success', $message);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request1->ajax() || $request1->wantsJson()) {
                return response()->json([
                    'message' => 'Validasi gagal',
                    'status' => 'validation_error',
                    'errors' => $e->errors(),
                ], 422);
            }
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            if ($request1->ajax() || $request1->wantsJson()) {
                return response()->json(['message' => 'Gagal memproses pengajuan: ' . $e->getMessage()], 500);
            }
            return back()->with('error', 'Gagal memproses pengajuan: ' . $e->getMessage());
        }
    }

    public function forwardPeminjamanKendaraan(Request $request1, $id)
    {
        try {
            $request = PeminjamanKendaraan::findOrFail($id);
            
            // Check current status and provide appropriate message
            if ($request->status === 'diteruskan_ke_kasubag') {
                if ($request1->ajax() || $request1->wantsJson()) {
                    return response()->json([
                        'message' => 'Pengajuan sudah diteruskan ke Kasubag sebelumnya.',
                        'status' => 'already_forwarded'
                    ], 400);
                }
                return back()->with('error', 'Pengajuan sudah diteruskan ke Kasubag sebelumnya.');
            }
            
            if ($request->status !== 'direview') {
                if ($request1->ajax() || $request1->wantsJson()) {
                    return response()->json([
                        'message' => 'Hanya pengajuan dengan status direview yang dapat diteruskan. Status saat ini: ' . $request->status,
                        'status' => 'invalid_status'
                    ], 400);
                }
                return back()->with('error', 'Hanya pengajuan dengan status direview yang dapat diteruskan. Status saat ini: ' . $request->status);
            }

            $validated = $request1->validate([
                'action' => 'required|in:forward,reject',
                'komentar' => 'required|string',
            ]);

            if ($validated['action'] === 'forward') {
                $request->update([
                    'status' => 'diteruskan_ke_kasubag',
                    'diteruskan_ke_kasubag_date' => now(),
                    'komentar' => $validated['komentar']
                ]);
                $message = 'Pengajuan peminjaman kendaraan telah diteruskan ke Kasubag.';
            } else {
                $request->update([
                    'status' => 'rejected',
                    'komentar' => $validated['komentar']
                ]);
                $message = 'Pengajuan peminjaman kendaraan telah ditolak.';
            }

            if ($request1->ajax() || $request1->wantsJson()) {
                return response()->json(['message' => $message, 'status' => 'success'], 200);
            }
            return back()->with('success', $message);
        } catch (\Exception $e) {
            if ($request1->ajax() || $request1->wantsJson()) {
                return response()->json(['message' => 'Gagal memproses pengajuan: ' . $e->getMessage()], 500);
            }
            return back()->with('error', 'Gagal memproses pengajuan: ' . $e->getMessage());
        }
    }

    public function forwardPeminjamanAset(Request $request1, $id)
    {
        try {
            $request = PeminjamanAset::findOrFail($id);
            
            // Check if request is AJAX - use multiple methods for better detection
            $isAjax = $request1->ajax() 
                || $request1->wantsJson() 
                || $request1->header('X-Requested-With') === 'XMLHttpRequest'
                || $request1->expectsJson();
            
            // Check current status and provide appropriate message
            if ($request->status === 'diteruskan_ke_kasubag') {
                if ($isAjax) {
                    return response()->json([
                        'message' => 'Pengajuan sudah diteruskan ke Kasubag sebelumnya.',
                        'status' => 'already_forwarded'
                    ], 400);
                }
                return back()->with('error', 'Pengajuan sudah diteruskan ke Kasubag sebelumnya.');
            }
            
            if ($request->status !== 'direview') {
                if ($isAjax) {
                    return response()->json([
                        'message' => 'Hanya pengajuan dengan status direview yang dapat diteruskan. Status saat ini: ' . $request->status,
                        'status' => 'invalid_status'
                    ], 400);
                }
                return back()->with('error', 'Hanya pengajuan dengan status direview yang dapat diteruskan.');
            }

            $validated = $request1->validate([
                'action' => 'required|in:forward,reject',
                'komentar' => 'required|string',
            ]);

            if ($validated['action'] === 'forward') {
                $request->update([
                    'status' => 'diteruskan_ke_kasubag',
                    'diteruskan_ke_kasubag_date' => now(),
                    'komentar' => $validated['komentar']
                ]);
                $message = 'Pengajuan peminjaman aset telah diteruskan ke Kasubag.';
            } else {
                $request->update([
                    'status' => 'rejected',
                    'komentar' => $validated['komentar']
                ]);
                $message = 'Pengajuan peminjaman aset telah ditolak.';
            }

            // Always return JSON for consistency, but check Accept header
            if ($isAjax || $request1->header('Accept') === 'application/json') {
                return response()->json(['message' => $message, 'status' => 'success'], 200);
            }
            return back()->with('success', $message);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors
            $isAjax = $request1->ajax() 
                || $request1->wantsJson() 
                || $request1->header('X-Requested-With') === 'XMLHttpRequest'
                || $request1->expectsJson();
                
            if ($isAjax) {
                return response()->json([
                    'message' => 'Validasi gagal.',
                    'errors' => $e->errors(),
                    'status' => 'validation_error'
                ], 422);
            }
            return back()->withErrors($e->errors());
        } catch (\Exception $e) {
            $isAjax = $request1->ajax() 
                || $request1->wantsJson() 
                || $request1->header('X-Requested-With') === 'XMLHttpRequest'
                || $request1->expectsJson();
                
            if ($isAjax) {
                return response()->json([
                    'message' => 'Gagal memproses pengajuan: ' . $e->getMessage(),
                    'status' => 'error'
                ], 500);
            }
            return back()->with('error', 'Gagal memproses pengajuan: ' . $e->getMessage());
        }
    }

    /**
     * Write Excel file using HTML format (Excel-compatible)
     */
    private function writeExcelFile($path, $data)
    {
        $handle = fopen($path, 'w+');
        
        // Write Excel HTML format
        fwrite($handle, '<!DOCTYPE html>' . "\n");
        fwrite($handle, '<html xmlns:v="urn:schemas-microsoft-com:vml"' . "\n");
        fwrite($handle, ' xmlns:o="urn:schemas-microsoft-com:office:office"' . "\n");
        fwrite($handle, ' xmlns:x="urn:schemas-microsoft-com:office:excel"' . "\n");
        fwrite($handle, ' xmlns="http://www.w3.org/TR/REC-html40">' . "\n");
        fwrite($handle, '<head>' . "\n");
        fwrite($handle, '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' . "\n");
        fwrite($handle, '<!--[if gte mso 9]><xml>' . "\n");
        fwrite($handle, '<x:ExcelWorkbook>' . "\n");
        fwrite($handle, '<x:ExcelWorksheets>' . "\n");
        fwrite($handle, '<x:ExcelWorksheet>' . "\n");
        fwrite($handle, '<x:Name>Sheet1</x:Name>' . "\n");
        fwrite($handle, '</x:ExcelWorksheet>' . "\n");
        fwrite($handle, '</x:ExcelWorksheets>' . "\n");
        fwrite($handle, '</x:ExcelWorkbook>' . "\n");
        fwrite($handle, '</xml><![endif]-->' . "\n");
        fwrite($handle, '<style>' . "\n");
        fwrite($handle, 'table { border-collapse: collapse; font-family: Calibri, Arial; }' . "\n");
        fwrite($handle, 'th { background-color: #4472C4; color: white; font-weight: bold; padding: 8px; border: 1px solid #000; text-align: center; }' . "\n");
        fwrite($handle, 'td { padding: 6px; border: 1px solid #000; }' . "\n");
        fwrite($handle, '</style>' . "\n");
        fwrite($handle, '</head>' . "\n");
        fwrite($handle, '<body>' . "\n");
        fwrite($handle, '<table>' . "\n");
        
        $isFirstRow = true;
        foreach ($data as $row) {
            // Skip empty rows (used as separators)
            if (empty(array_filter($row))) {
                fwrite($handle, '<tr><td colspan="100">&nbsp;</td></tr>' . "\n");
                continue;
            }
            
            fwrite($handle, '<tr>' . "\n");
            foreach ($row as $cell) {
                $value = htmlspecialchars($cell ?? '', ENT_COMPAT, 'UTF-8');
                if ($isFirstRow) {
                    fwrite($handle, '<th>' . $value . '</th>' . "\n");
                } else {
                    fwrite($handle, '<td>' . $value . '</td>' . "\n");
                }
            }
            fwrite($handle, '</tr>' . "\n");
            $isFirstRow = false;
        }
        
        fwrite($handle, '</table>' . "\n");
        fwrite($handle, '</body>' . "\n");
        fwrite($handle, '</html>' . "\n");
        
        fclose($handle);
    }

}
