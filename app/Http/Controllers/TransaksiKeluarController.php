<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransaksiKeluarPersediaan;
use App\Models\TransaksiKeluarAsetTetap;
use App\Models\Persediaan;
use App\Models\AsetTetap;
use Illuminate\Support\Facades\DB;

class TransaksiKeluarController extends Controller
{
    public function index()
    {
        $historiPersediaan = TransaksiKeluarPersediaan::with('persediaan')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        $historiAsetTetap = TransaksiKeluarAsetTetap::with('asetTetap')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        $totalTransaksiPersediaan = TransaksiKeluarPersediaan::count();
        $totalTransaksiAsetTetap = TransaksiKeluarAsetTetap::count();
        $totalTransaksiHariIni = TransaksiKeluarPersediaan::whereDate('created_at', today())->count() + 
                                TransaksiKeluarAsetTetap::whereDate('created_at', today())->count();

        return view('transaksi-keluar.index', compact(
            'historiPersediaan', 
            'historiAsetTetap', 
            'totalTransaksiPersediaan', 
            'totalTransaksiAsetTetap', 
            'totalTransaksiHariIni'
        ));
    }

    public function persediaan()
    {
        $transaksi = TransaksiKeluarPersediaan::with('persediaan')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('transaksi-keluar.persediaan', compact('transaksi'));
    }

    public function asetTetap()
    {
        $transaksi = TransaksiKeluarAsetTetap::with('asetTetap')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('transaksi-keluar.aset-tetap', compact('transaksi'));
    }

    public function createPersediaan()
    {
        $persediaan = Persediaan::orderBy('nama_barang')->get();
        return view('transaksi-keluar.create-persediaan', compact('persediaan'));
    }

    public function createAsetTetap()
    {
        $asetTetap = AsetTetap::orderBy('nama_barang')->get();
        return view('transaksi-keluar.create-aset-tetap', compact('asetTetap'));
    }

    public function storePersediaan(Request $request)
    {
        $request->validate([
            'tanggal_input' => 'required|date',
            'persediaan_id' => 'required|exists:persediaan,id',
            'jumlah_keluar' => 'required|integer|min:1'
        ]);

        try {
            DB::beginTransaction();

            $persediaan = Persediaan::findOrFail($request->persediaan_id);
            
            // Validasi stok tersedia
            if ($persediaan->stok < $request->jumlah_keluar) {
                return back()->with('error', 'Stok tidak mencukupi. Stok tersedia: ' . $persediaan->stok);
            }

            // Buat transaksi keluar (sertakan jenis dari master persediaan)
            TransaksiKeluarPersediaan::create([
                'user_id' => auth()->id(),
                'tanggal_input' => $request->tanggal_input,
                'kode_kategori' => $persediaan->kode_kategori,
                'kategori' => $persediaan->kategori,
                'kode_barang' => $persediaan->kode_barang,
                'nama_barang' => $persediaan->nama_barang,
                'jenis' => $persediaan->jenis ?? 'habis_pakai',
                'jumlah_keluar' => $request->jumlah_keluar,
                'harga' => $persediaan->harga,
                'persediaan_id' => $persediaan->id
            ]);

            // Kurangi stok dan jumlah di data master
            $persediaan->decrement('stok', $request->jumlah_keluar);
            $persediaan->decrement('jumlah', $request->jumlah_keluar);

            DB::commit();

            return redirect()->route('admin.transaksi-keluar.persediaan')
                ->with('success', 'Transaksi keluar persediaan berhasil disimpan!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function storeAsetTetap(Request $request)
    {
        $request->validate([
            'tanggal_input' => 'required|date',
            'aset_tetap_id' => 'required|exists:aset_tetap,id',
            'jumlah_keluar' => 'required|integer|min:1'
        ]);

        try {
            DB::beginTransaction();

            $asetTetap = AsetTetap::findOrFail($request->aset_tetap_id);
            
            // Validasi stok tersedia
            if ($asetTetap->stok < $request->jumlah_keluar) {
                return back()->with('error', 'Stok tidak mencukupi. Stok tersedia: ' . $asetTetap->stok);
            }

            // Buat transaksi keluar
            TransaksiKeluarAsetTetap::create([
                'user_id' => auth()->id(),
                'tanggal_input' => $request->tanggal_input,
                'kode_kategori' => !empty($asetTetap->kode_kategori) ? $asetTetap->kode_kategori : ($asetTetap->kode_barang ?? ''),
                'kategori' => $asetTetap->kategori ?? 'Aset Tetap',
                'kode_barang' => $asetTetap->kode_barang,
                'nama_barang' => $asetTetap->nama_barang,
                'jumlah_keluar' => $request->jumlah_keluar,
                'nilai_perolehan' => $asetTetap->nilai_perolehan ?? 0,
                'aset_tetap_id' => $asetTetap->id
            ]);

            // Kurangi jumlah dan stok
            $asetTetap->decrement('jumlah', $request->jumlah_keluar);
            $asetTetap->decrement('stok', $request->jumlah_keluar);

            DB::commit();

            return redirect()->route('admin.transaksi-keluar.aset-tetap')
                ->with('success', 'Transaksi keluar aset tetap berhasil disimpan!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function historiPersediaan()
    {
        $transaksi = TransaksiKeluarPersediaan::with('persediaan')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        return view('transaksi-keluar.histori-persediaan', compact('transaksi'));
    }

    public function historiAsetTetap()
    {
        $transaksi = TransaksiKeluarAsetTetap::with('asetTetap')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        return view('transaksi-keluar.histori-aset-tetap', compact('transaksi'));
    }

    // Fungsi edit dan hapus untuk transaksi keluar persediaan
    public function editPersediaan($id)
    {
        $transaksi = TransaksiKeluarPersediaan::findOrFail($id);
        $persediaan = Persediaan::orderBy('nama_barang')->get();
        return view('transaksi-keluar.edit-persediaan', compact('transaksi', 'persediaan'));
    }

    public function updatePersediaan(Request $request, $id)
    {
        $transaksi = TransaksiKeluarPersediaan::findOrFail($id);
        
        $request->validate([
            'tanggal_input' => 'required|date',
            'persediaan_id' => 'required|exists:persediaan,id',
            'jumlah_keluar' => 'required|integer|min:1'
        ]);

        try {
            DB::beginTransaction();

            $persediaan = Persediaan::findOrFail($request->persediaan_id);
            
            // Simpan jumlah lama untuk rollback
            $jumlahLama = $transaksi->jumlah_keluar;
            $jumlahBaru = $request->jumlah_keluar;
            
            // Validasi stok tersedia (untuk perubahan jumlah)
            $stokTersedia = $persediaan->stok + $jumlahLama; // Kembalikan dulu stok lama
            if ($stokTersedia < $jumlahBaru) {
                DB::rollback();
                return back()->with('error', 'Stok tidak mencukupi untuk perubahan ini.');
            }

            // Rollback jumlah lama
            $persediaan->increment('stok', $jumlahLama);
            $persediaan->increment('jumlah', $jumlahLama);
            
            // Kurangi dengan jumlah baru
            $persediaan->decrement('stok', $jumlahBaru);
            $persediaan->decrement('jumlah', $jumlahBaru);

            // Update transaksi keluar (sertakan jenis)
            $transaksi->update([
                'tanggal_input' => $request->tanggal_input,
                'kode_kategori' => $persediaan->kode_kategori,
                'kategori' => $persediaan->kategori,
                'kode_barang' => $persediaan->kode_barang,
                'nama_barang' => $persediaan->nama_barang,
                'jenis' => $persediaan->jenis ?? 'habis_pakai',
                'jumlah_keluar' => $jumlahBaru,
                'harga' => $persediaan->harga,
                'persediaan_id' => $persediaan->id
            ]);

            DB::commit();

            return redirect()->route('admin.transaksi-keluar.persediaan')
                ->with('success', 'Transaksi keluar persediaan berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroyPersediaan($id)
    {
        try {
            DB::beginTransaction();

            $transaksi = TransaksiKeluarPersediaan::findOrFail($id);
            
            // Rollback stok dan jumlah
            $persediaan = Persediaan::find($transaksi->persediaan_id);
            if ($persediaan) {
                $persediaan->increment('stok', $transaksi->jumlah_keluar);
                $persediaan->increment('jumlah', $transaksi->jumlah_keluar);
            }
            
            // Hapus transaksi
            $transaksi->delete();

            DB::commit();

            return redirect()->route('admin.transaksi-keluar.persediaan')
                ->with('success', 'Transaksi keluar persediaan berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Fungsi edit dan hapus untuk transaksi keluar aset tetap
    public function editAsetTetap($id)
    {
        $transaksi = TransaksiKeluarAsetTetap::findOrFail($id);
        $asetTetap = AsetTetap::orderBy('nama_barang')->get();
        return view('transaksi-keluar.edit-persediaan', compact('transaksi', 'asetTetap'));
    }

    public function updateAsetTetap(Request $request, $id)
    {
        $transaksi = TransaksiKeluarAsetTetap::findOrFail($id);
        
        $request->validate([
            'tanggal_input' => 'required|date',
            'aset_tetap_id' => 'required|exists:aset_tetap,id',
            'jumlah_keluar' => 'required|integer|min:1'
        ]);

        try {
            DB::beginTransaction();

            $asetTetap = AsetTetap::findOrFail($request->aset_tetap_id);
            
            // Simpan jumlah lama untuk rollback
            $jumlahLama = $transaksi->jumlah_keluar;
            $jumlahBaru = $request->jumlah_keluar;
            
            // Validasi stok tersedia (untuk perubahan jumlah)
            $stokTersedia = $asetTetap->stok + $jumlahLama; // Kembalikan dulu stok lama
            if ($stokTersedia < $jumlahBaru) {
                DB::rollback();
                return back()->with('error', 'Stok tidak mencukupi untuk perubahan ini.');
            }

            // Rollback jumlah dan stok lama
            $asetTetap->increment('jumlah', $jumlahLama);
            $asetTetap->increment('stok', $jumlahLama);
            
            // Kurangi dengan jumlah dan stok baru
            $asetTetap->decrement('jumlah', $jumlahBaru);
            $asetTetap->decrement('stok', $jumlahBaru);

            // Update transaksi keluar
            $transaksi->update([
                'tanggal_input' => $request->tanggal_input,
                'kode_kategori' => !empty($asetTetap->kode_kategori) ? $asetTetap->kode_kategori : ($asetTetap->kode_barang ?? ''),
                'kategori' => $asetTetap->kategori ?? 'Aset Tetap',
                'kode_barang' => $asetTetap->kode_barang,
                'nama_barang' => $asetTetap->nama_barang,
                'jumlah_keluar' => $jumlahBaru,
                'nilai_perolehan' => $asetTetap->nilai_perolehan ?? 0,
                'aset_tetap_id' => $asetTetap->id
            ]);

            DB::commit();

            return redirect()->route('admin.transaksi-keluar.aset-tetap')
                ->with('success', 'Transaksi keluar aset tetap berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroyAsetTetap($id)
    {
        try {
            DB::beginTransaction();

            $transaksi = TransaksiKeluarAsetTetap::findOrFail($id);
            
            // Rollback jumlah dan stok
            $asetTetap = AsetTetap::find($transaksi->aset_tetap_id);
            if ($asetTetap) {
                $asetTetap->increment('jumlah', $transaksi->jumlah_keluar);
                $asetTetap->increment('stok', $transaksi->jumlah_keluar);
            }
            
            // Hapus transaksi
            $transaksi->delete();

            DB::commit();

            return redirect()->route('admin.transaksi-keluar.aset-tetap')
                ->with('success', 'Transaksi keluar aset tetap berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
