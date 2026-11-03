<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AsetTetap;
use App\Models\Persediaan;
use App\Models\TransaksiMasukAsetTetap;
use App\Models\TransaksiMasukPersediaan;
use App\Models\TransaksiKeluarAsetTetap;
use App\Models\TransaksiKeluarPersediaan;
use App\Models\MutasiBarang;
use App\Models\User;
use App\Models\RequestModel;
use App\Models\PermintaanPersediaan;
use App\Models\PeminjamanKendaraan;
use App\Models\PeminjamanAset;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KepalaBpmpController extends Controller
{
    public function dashboard()
    {
        // Statistik Aset Tetap
        $totalAsetTetap = AsetTetap::count();
        $totalNilaiAsetTetap = AsetTetap::sum('nilai_perolehan');

        // Statistik Persediaan
        $totalPersediaan = Persediaan::count();
        $totalNilaiPersediaan = Persediaan::sum('harga');

        // Statistik Transaksi Masuk
        $transaksiMasukAset = TransaksiMasukAsetTetap::count();
        $transaksiMasukPersediaan = TransaksiMasukPersediaan::count();
        $totalTransaksiMasuk = $transaksiMasukAset + $transaksiMasukPersediaan;

        // Statistik Transaksi Keluar
        $transaksiKeluarAset = TransaksiKeluarAsetTetap::count();
        $transaksiKeluarPersediaan = TransaksiKeluarPersediaan::count();
        $totalTransaksiKeluar = $transaksiKeluarAset + $transaksiKeluarPersediaan;

        // Statistik Mutasi
        $totalMutasi = MutasiBarang::count();

        // Data untuk grafik (transaksi per bulan) - menggunakan MySQL DATE_FORMAT
        $transaksiPerBulan = DB::table('transaksi_masuk_aset_tetap')
            ->select(DB::raw('MONTH(created_at) as bulan'), DB::raw('COUNT(*) as total'))
            ->whereYear('created_at', date('Y'))
            ->groupBy('bulan')
            ->get();

        // Data terbaru
        $asetTerbaru = AsetTetap::orderBy('created_at', 'desc')->limit(5)->get();
        $persediaanTerbaru = Persediaan::orderBy('created_at', 'desc')->limit(5)->get();
        $mutasiTerbaru = MutasiBarang::with('user')->orderBy('created_at', 'desc')->limit(5)->get();

        $statusConfigs = $this->getStatusConfigs();

        $pengajuanSummary = [
            array_merge([
                'label' => 'Peminjaman Barang',
                'icon' => 'fas fa-box',
                'accent' => 'bg-blue-100 text-blue-600'
            ], $this->summarizePengajuan(RequestModel::class, $statusConfigs)),
            array_merge([
                'label' => 'Permintaan Persediaan',
                'icon' => 'fas fa-clipboard-list',
                'accent' => 'bg-green-100 text-green-600'
            ], $this->summarizePengajuan(PermintaanPersediaan::class, $statusConfigs)),
            array_merge([
                'label' => 'Peminjaman Kendaraan',
                'icon' => 'fas fa-car',
                'accent' => 'bg-yellow-100 text-yellow-600'
            ], $this->summarizePengajuan(PeminjamanKendaraan::class, $statusConfigs)),
            array_merge([
                'label' => 'Peminjaman Aset',
                'icon' => 'fas fa-building',
                'accent' => 'bg-purple-100 text-purple-600'
            ], $this->summarizePengajuan(PeminjamanAset::class, $statusConfigs)),
        ];

        $recentPengajuan = $this->getRecentPengajuan();

        return view('KepalaBpmp.dashboard', compact(
            'totalAsetTetap',
            'totalNilaiAsetTetap',
            'totalPersediaan',
            'totalNilaiPersediaan',
            'totalTransaksiMasuk',
            'totalTransaksiKeluar',
            'totalMutasi',
            'transaksiPerBulan',
            'asetTerbaru',
            'persediaanTerbaru',
            'mutasiTerbaru',
            'pengajuanSummary',
            'recentPengajuan'
        ));
    }

    public function laporanAsetTetap()
    {
        $asetTetap = AsetTetap::orderBy('created_at', 'desc')->paginate(20);
        return view('KepalaBpmp.laporan-aset-tetap', compact('asetTetap'));
    }

    public function laporanPersediaan()
    {
        $persediaan = Persediaan::orderBy('created_at', 'desc')->paginate(20);
        return view('KepalaBpmp.laporan-persediaan', compact('persediaan'));
    }

    public function laporanTransaksiMasuk()
    {
        $transaksiAset = TransaksiMasukAsetTetap::with('asetTetap')->orderBy('created_at', 'desc')->get();
        $transaksiPersediaan = TransaksiMasukPersediaan::with('persediaan')->orderBy('created_at', 'desc')->get();

        return view('KepalaBpmp.laporan-transaksi-masuk', compact('transaksiAset', 'transaksiPersediaan'));
    }

    public function laporanTransaksiKeluar()
    {
        $transaksiAset = TransaksiKeluarAsetTetap::with('asetTetap')->orderBy('created_at', 'desc')->get();
        $transaksiPersediaan = TransaksiKeluarPersediaan::with('persediaan')->orderBy('created_at', 'desc')->get();

        return view('KepalaBpmp.laporan-transaksi-keluar', compact('transaksiAset', 'transaksiPersediaan'));
    }

    public function laporanMutasi()
    {
        $mutasi = MutasiBarang::with('user')->orderBy('created_at', 'desc')->paginate(20);
        return view('KepalaBpmp.laporan-mutasi', compact('mutasi'));
    }

    public function laporanRekapitulasi()
    {
        // Data untuk rekap
        $totalAsetTetap = AsetTetap::count();
        $totalNilaiAsetTetap = AsetTetap::sum('nilai_perolehan');
        $totalPersediaan = Persediaan::count();
        $totalNilaiPersediaan = Persediaan::sum('harga');

        // Transaksi per kategori
        $transaksiPerKategori = DB::table('aset_tetap')
            ->select('kategori', DB::raw('COUNT(*) as total'))
            ->whereNotNull('kategori')
            ->where('kategori', '!=', '')
            ->groupBy('kategori')
            ->get();

        // Mutasi per bulan - menggunakan MySQL DATE_FORMAT
        $mutasiPerBulan = DB::table('mutasi_barang')
            ->select(DB::raw('MONTH(created_at) as bulan'), DB::raw('COUNT(*) as total'))
            ->whereYear('created_at', date('Y'))
            ->groupBy('bulan')
            ->get();

        return view('KepalaBpmp.laporan-rekapitulasi', compact(
            'totalAsetTetap',
            'totalNilaiAsetTetap',
            'totalPersediaan',
            'totalNilaiPersediaan',
            'transaksiPerKategori',
            'mutasiPerBulan'
        ));
    }

    private function summarizePengajuan(string $modelClass, array $statusConfigs): array
    {
        $total = $modelClass::count();
        $countsByStatus = $modelClass::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $counts = [];
        foreach ($statusConfigs as $config) {
            $counts[] = [
                'label' => $config['label'],
                'count' => $countsByStatus[$config['db']] ?? 0,
                'style' => $this->getStatusStyle($config['db']),
            ];
        }

        return [
            'total' => $total,
            'counts' => $counts,
        ];
    }

    private function getStatusConfigs(): array
    {
        return [
            ['label' => 'Menunggu', 'db' => 'Menunggu'],
            ['label' => 'Direview', 'db' => 'direview'],
            ['label' => 'Diteruskan', 'db' => 'diteruskan_ke_kasubag'],
            ['label' => 'Disetujui', 'db' => 'approved'],
            ['label' => 'Ditolak', 'db' => 'rejected'],
        ];
    }

    private function getStatusStyle(?string $status): string
    {
        $status = $status ?? '';

        switch ($status) {
            case 'Menunggu':
                return 'bg-yellow-100 text-yellow-800';
            case 'direview':
                return 'bg-blue-100 text-blue-800';
            case 'diteruskan_ke_kasubag':
                return 'bg-purple-100 text-purple-800';
            case 'approved':
                return 'bg-green-100 text-green-800';
            case 'rejected':
                return 'bg-red-100 text-red-800';
            default:
                return 'bg-gray-100 text-gray-800';
        }
    }

    private function formatStatusLabel(?string $status): string
    {
        if (!$status) {
            return 'Tidak diketahui';
        }

        switch ($status) {
            case 'Menunggu':
                return 'Menunggu';
            case 'direview':
                return 'Direview Admin';
            case 'diteruskan_ke_kasubag':
                return 'Diteruskan ke Kasubag';
            case 'approved':
                return 'Disetujui';
            case 'rejected':
                return 'Ditolak';
            default:
                return ucfirst(str_replace('_', ' ', $status));
        }
    }

    private function getRecentPengajuan()
    {
        $items = collect();

        RequestModel::with('user')->orderBy('created_at', 'desc')->limit(5)->get()->each(function ($item) use ($items) {
            $items->push([
                'sumber' => 'Peminjaman Barang',
                'nama' => $item->nama_barang ?? '-',
                'pemohon' => $item->user->nama_lengkap ?? '-',
                'status' => $item->status,
                'status_label' => $this->formatStatusLabel($item->status),
                'status_style' => $this->getStatusStyle($item->status),
                'tanggal' => $item->created_at,
            ]);
        });

        PermintaanPersediaan::with('user')->orderBy('created_at', 'desc')->limit(5)->get()->each(function ($item) use ($items) {
            $items->push([
                'sumber' => 'Permintaan Persediaan',
                'nama' => $item->nama_barang ?? '-',
                'pemohon' => $item->user->nama_lengkap ?? '-',
                'status' => $item->status,
                'status_label' => $this->formatStatusLabel($item->status),
                'status_style' => $this->getStatusStyle($item->status),
                'tanggal' => $item->created_at,
            ]);
        });

        PeminjamanKendaraan::with('user')->orderBy('created_at', 'desc')->limit(5)->get()->each(function ($item) use ($items) {
            $items->push([
                'sumber' => 'Peminjaman Kendaraan',
                'nama' => $item->nama_kendaraan ?? '-',
                'pemohon' => $item->user->nama_lengkap ?? '-',
                'status' => $item->status,
                'status_label' => $this->formatStatusLabel($item->status),
                'status_style' => $this->getStatusStyle($item->status),
                'tanggal' => $item->created_at,
            ]);
        });

        PeminjamanAset::with('user')->orderBy('created_at', 'desc')->limit(5)->get()->each(function ($item) use ($items) {
            $items->push([
                'sumber' => 'Peminjaman Aset',
                'nama' => $item->nama_barang ?? '-',
                'pemohon' => $item->nama_peminjam ?? ($item->user->nama_lengkap ?? '-'),
                'status' => $item->status,
                'status_label' => $this->formatStatusLabel($item->status),
                'status_style' => $this->getStatusStyle($item->status),
                'tanggal' => $item->created_at,
            ]);
        });

        return $items->sortByDesc('tanggal')->take(8)->values();
    }

    // Method untuk Manajemen Pengajuan - Read Only
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

        return view('KepalaBpmp.peminjaman-barang', compact('requests'));
    }

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

        return view('KepalaBpmp.permintaan-persediaan', compact('requests'));
    }

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

        return view('KepalaBpmp.peminjaman-kendaraan', compact('requests'));
    }

    public function peminjamanAset(Request $request)
    {
        $search = $request->get('search');
        $perPage = $request->get('per_page', 10);

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

        return view('KepalaBpmp.peminjaman-aset', compact('requests'));
    }

    // Method untuk mengambil detail data via AJAX (Read Only)
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
        try {
            $request = PeminjamanAset::with(['user', 'suratPersetujuan'])->findOrFail($id);
            return response()->json($request);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Permintaan tidak ditemukan.'], 404);
        }
    }
}
