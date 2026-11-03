<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransaksiMasukPersediaan;
use App\Models\TransaksiMasukAsetTetap;
use App\Models\Persediaan;
use App\Models\AsetTetap;
use Illuminate\Support\Facades\DB;

class TransaksiMasukController extends Controller
{
    public function index()
    {
        // Ambil data histori transaksi persediaan (5 terakhir)
        $historiPersediaan = TransaksiMasukPersediaan::with('persediaan')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Ambil data histori transaksi aset tetap (5 terakhir)
        $historiAsetTetap = TransaksiMasukAsetTetap::with('asetTetap')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Statistik
        $totalTransaksiPersediaan = TransaksiMasukPersediaan::count();
        $totalTransaksiAsetTetap = TransaksiMasukAsetTetap::count();
        $totalTransaksiHariIni = TransaksiMasukPersediaan::whereDate('created_at', today())->count() + 
                                TransaksiMasukAsetTetap::whereDate('created_at', today())->count();

        return view('transaksi-masuk.index', compact(
            'historiPersediaan', 
            'historiAsetTetap', 
            'totalTransaksiPersediaan', 
            'totalTransaksiAsetTetap', 
            'totalTransaksiHariIni'
        ));
    }

    public function persediaan()
    {
        $transaksi = TransaksiMasukPersediaan::with('persediaan')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('transaksi-masuk.persediaan', compact('transaksi'));
    }

    public function asetTetap()
    {
        $transaksi = TransaksiMasukAsetTetap::with('asetTetap')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('transaksi-masuk.aset-tetap', compact('transaksi'));
    }

    public function createPersediaan()
    {
        $persediaan = Persediaan::all();
        return view('transaksi-masuk.create-persediaan', compact('persediaan'));
    }

    public function createAsetTetap()
    {
        $asetTetap = AsetTetap::all();
        return view('transaksi-masuk.create-aset-tetap', compact('asetTetap'));
    }

    public function storePersediaan(Request $request)
    {
        $request->validate([
            'tanggal_input' => 'required|date',
            'kode_kategori' => 'required|string|max:50',
            'kategori' => 'required|string|max:50',
            'kode_barang' => 'required|string|max:50',
            'nama_barang' => 'required|string|max:100',
            'jumlah_masuk' => 'required|integer|min:1',
            'harga' => 'required|numeric|min:0',
            'persediaan_id' => 'required|exists:persediaan,id'
        ]);

        DB::beginTransaction();
        try {
            // Ambil persediaan untuk melengkapi data (termasuk jenis)
            $persediaan = Persediaan::findOrFail($request->persediaan_id);

            // Buat transaksi masuk
            $data = $request->all();
            $data['user_id'] = auth()->id();
            $data['jenis'] = $persediaan->jenis ?? 'habis_pakai';
            TransaksiMasukPersediaan::create($data);

            // Update stok di tabel persediaan
            $persediaan->increment('stok', $request->jumlah_masuk);
            // Update jumlah di data master untuk sinkronisasi
            $persediaan->increment('jumlah', $request->jumlah_masuk);

            DB::commit();
            return redirect()->route('admin.transaksi-masuk.persediaan')->with('success', 'Transaksi barang masuk persediaan berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function storeAsetTetap(Request $request)
    {
        $request->validate([
            'tanggal_input' => 'required|date',
            'kode_barang' => 'required|string|max:50',
            'nama_barang' => 'required|string|max:100',
            'jumlah_masuk' => 'required|integer|min:1',
            'harga' => 'nullable|numeric|min:0',
            'aset_tetap_id' => 'required|exists:aset_tetap,id'
        ]);

        DB::beginTransaction();
        try {
            // Ambil aset untuk melengkapi field otomatis
            $asetTetap = AsetTetap::findOrFail($request->aset_tetap_id);

            // Buat transaksi masuk - ambil kategori dari database
            $data = [
                'user_id' => auth()->id(),
                'tanggal_input' => $request->tanggal_input,
                'kode_kategori' => !empty($asetTetap->kode_kategori) ? $asetTetap->kode_kategori : ($asetTetap->kode_barang ?? ''),
                'kategori' => $asetTetap->kategori ?? '',
                'kode_barang' => $request->kode_barang,
                'nama_barang' => $request->nama_barang,
                'jumlah_masuk' => $request->jumlah_masuk,
                'harga' => $request->harga ?? $asetTetap->nilai_perolehan ?? 0,
                'aset_tetap_id' => $request->aset_tetap_id
            ];
            TransaksiMasukAsetTetap::create($data);

            // Update jumlah dan stok di tabel aset tetap
            $asetTetap->increment('jumlah', $request->jumlah_masuk);
            $asetTetap->increment('stok', $request->jumlah_masuk);

            DB::commit();
            return redirect()->route('admin.transaksi-masuk.aset-tetap')->with('success', 'Transaksi barang masuk aset tetap berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function historiPersediaan()
    {
        $transaksi = TransaksiMasukPersediaan::with('persediaan')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        return view('transaksi-masuk.histori-persediaan', compact('transaksi'));
    }

    public function historiAsetTetap()
    {
        $transaksi = TransaksiMasukAsetTetap::with('asetTetap')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        return view('transaksi-masuk.histori-aset-tetap', compact('transaksi'));
    }

    public function editAsetTetap($id)
    {
        $transaksi = TransaksiMasukAsetTetap::findOrFail($id);
        $asetTetap = AsetTetap::all();
        return view('transaksi-masuk.edit-aset-tetap', compact('transaksi', 'asetTetap'));
    }

    public function updateAsetTetap(Request $request, $id)
    {
        $transaksi = TransaksiMasukAsetTetap::findOrFail($id);
        
        $request->validate([
            'tanggal_input' => 'required|date',
            'kode_barang' => 'required|string|max:50',
            'nama_barang' => 'required|string|max:100',
            'jumlah_masuk' => 'required|integer|min:1',
            'harga' => 'nullable|numeric|min:0',
            'aset_tetap_id' => 'required|exists:aset_tetap,id'
        ]);

        DB::beginTransaction();
        try {
            // Ambil aset tetap terlebih dahulu
            $asetTetap = AsetTetap::findOrFail($request->aset_tetap_id);
            
            // Simpan jumlah lama untuk rollback
            $jumlahLama = $transaksi->jumlah_masuk;
            $jumlahBaru = $request->jumlah_masuk;
            
            // Update transaksi - ambil kategori dari database
            $data = [
                'user_id' => auth()->id(),
                'tanggal_input' => $request->tanggal_input,
                'kode_kategori' => !empty($asetTetap->kode_kategori) ? $asetTetap->kode_kategori : ($asetTetap->kode_barang ?? ''),
                'kategori' => $asetTetap->kategori ?? '',
                'kode_barang' => $request->kode_barang,
                'nama_barang' => $request->nama_barang,
                'jumlah_masuk' => $request->jumlah_masuk,
                'harga' => $request->harga ?? $asetTetap->nilai_perolehan ?? 0,
                'aset_tetap_id' => $request->aset_tetap_id
            ];
            $transaksi->update($data);
            
            // Update jumlah dan stok aset tetap jika jumlah berubah
            if ($jumlahLama != $jumlahBaru) {
                // Rollback jumlah dan stok lama
                $asetTetap->decrement('jumlah', $jumlahLama);
                $asetTetap->decrement('stok', $jumlahLama);
                // Tambahkan jumlah dan stok baru
                $asetTetap->increment('jumlah', $jumlahBaru);
                $asetTetap->increment('stok', $jumlahBaru);
            }

            DB::commit();
            return redirect()->route('admin.transaksi-masuk.aset-tetap')->with('success', 'Transaksi aset tetap berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroyAsetTetap($id)
    {
        DB::beginTransaction();
        try {
            $transaksi = TransaksiMasukAsetTetap::findOrFail($id);
            
            // Rollback jumlah dan stok
            $asetTetap = AsetTetap::find($transaksi->aset_tetap_id);
            if ($asetTetap) {
                $asetTetap->decrement('jumlah', $transaksi->jumlah_masuk);
                $asetTetap->decrement('stok', $transaksi->jumlah_masuk);
            }
            
            // Hapus transaksi
            $transaksi->delete();

            DB::commit();
            return redirect()->route('admin.transaksi-masuk.aset-tetap')->with('success', 'Transaksi aset tetap berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Fungsi edit dan hapus untuk transaksi masuk persediaan
    public function editPersediaan($id)
    {
        $transaksi = TransaksiMasukPersediaan::findOrFail($id);
        $persediaan = Persediaan::all();
        return view('transaksi-masuk.edit-persediaan', compact('transaksi', 'persediaan'));
    }

    public function updatePersediaan(Request $request, $id)
    {
        $transaksi = TransaksiMasukPersediaan::findOrFail($id);
        
        $request->validate([
            'tanggal_input' => 'required|date',
            'kode_kategori' => 'required|string|max:50',
            'kategori' => 'required|string|max:50',
            'kode_barang' => 'required|string|max:50',
            'nama_barang' => 'required|string|max:100',
            'jumlah_masuk' => 'required|integer|min:1',
            'harga' => 'required|numeric|min:0',
            'persediaan_id' => 'required|exists:persediaan,id'
        ]);

        DB::beginTransaction();
        try {
            // Simpan jumlah lama untuk rollback
            $jumlahLama = $transaksi->jumlah_masuk;
            $jumlahBaru = $request->jumlah_masuk;
            
            // Ambil persediaan untuk melengkapi data (termasuk jenis)
            $persediaan = Persediaan::findOrFail($request->persediaan_id);

            // Update transaksi
            $data = $request->all();
            $data['user_id'] = auth()->id();
            $data['jenis'] = $persediaan->jenis ?? 'habis_pakai';
            $transaksi->update($data);
            
            // Update stok dan jumlah persediaan
            if ($jumlahLama != $jumlahBaru) {
                // Rollback jumlah lama
                $persediaan->decrement('stok', $jumlahLama);
                $persediaan->decrement('jumlah', $jumlahLama);
                // Tambahkan jumlah baru
                $persediaan->increment('stok', $jumlahBaru);
                $persediaan->increment('jumlah', $jumlahBaru);
            }

            DB::commit();
            return redirect()->route('admin.transaksi-masuk.persediaan')->with('success', 'Transaksi persediaan berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroyPersediaan($id)
    {
        try {
            DB::beginTransaction();
            
            $transaksi = TransaksiMasukPersediaan::findOrFail($id);
            
            // Rollback stok dan jumlah
            $persediaan = Persediaan::find($transaksi->persediaan_id);
            if ($persediaan) {
                $persediaan->decrement('stok', $transaksi->jumlah_masuk);
                $persediaan->decrement('jumlah', $transaksi->jumlah_masuk);
            }
            
            // Hapus transaksi
            $transaksi->delete();

            DB::commit();
            return redirect()->route('admin.transaksi-masuk.persediaan')->with('success', 'Transaksi persediaan berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
