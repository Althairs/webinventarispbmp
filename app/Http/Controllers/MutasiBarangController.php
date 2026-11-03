<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MutasiBarang;
use App\Models\AsetTetap;
use Illuminate\Support\Facades\DB;

class MutasiBarangController extends Controller
{
    public function index()
    {
        $historiMutasi = MutasiBarang::with('asetTetap')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $totalMutasi = MutasiBarang::count();
        $totalMutasiHariIni = MutasiBarang::whereDate('created_at', today())->count();
        $totalAsetTetap = AsetTetap::count();

        return view('mutasi-barang.index', compact(
            'historiMutasi',
            'totalMutasi',
            'totalMutasiHariIni',
            'totalAsetTetap'
        ));
    }

    public function data()
    {
        $mutasi = MutasiBarang::with('asetTetap')
            ->orderBy('created_at', 'desc')
            ->paginate();
        return view('mutasi-barang.data', compact('mutasi'));
    }

    public function create()
    {
        $asetTetap = AsetTetap::orderBy('nama_barang')->get();
        return view('mutasi-barang.create', compact('asetTetap'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_mutasi' => 'required|date',
            'aset_tetap_id' => 'required|exists:aset_tetap,id',
            'lokasi_awal' => 'required|string|max:100',
            'lokasi_akhir' => 'required|string|max:100',
            'keterangan' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            $asetTetap = AsetTetap::findOrFail($request->aset_tetap_id);

            // Buat mutasi barang
            $mutasi = MutasiBarang::create([
                'kode_barang' => $asetTetap->kode_barang,
                'nama_barang' => $asetTetap->nama_barang,
                'nup' => $asetTetap->nup,
                'lokasi_awal' => $request->lokasi_awal,
                'lokasi_akhir' => $request->lokasi_akhir,
                'tanggal_mutasi' => $request->tanggal_mutasi,
                'keterangan' => $request->keterangan,
                'aset_tetap_id' => $asetTetap->id,
                'user_id' => auth()->id(),
                // Mengisi kolom yang sudah ada di tabel untuk menghindari NOT NULL constraint
                'dari_ruangan' => $request->lokasi_awal,
                'ke_ruangan' => $request->lokasi_akhir,
                'jumlah' => 1, // Default jumlah untuk mutasi aset tetap
                'alasan' => $request->keterangan
            ]);

            // Update lokasi di aset tetap
            $asetTetap->update([
                'lokasi' => $request->lokasi_akhir
            ]);

            DB::commit();

            return redirect()->route('admin.mutasi-barang.data')
                ->with('success', 'Mutasi barang berhasil disimpan!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function histori()
    {
        $mutasi = MutasiBarang::with('asetTetap')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        return view('mutasi-barang.histori', compact('mutasi'));
    }

    public function edit($id)
    {
        $mutasi = MutasiBarang::findOrFail($id);
        $asetTetap = AsetTetap::orderBy('nama_barang')->get();
        return view('mutasi-barang.edit', compact('mutasi', 'asetTetap'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal_mutasi' => 'required|date',
            'aset_tetap_id' => 'required|exists:aset_tetap,id',
            'lokasi_awal' => 'required|string|max:100',
            'lokasi_akhir' => 'required|string|max:100',
            'keterangan' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            $mutasi = MutasiBarang::findOrFail($id);
            $asetTetap = AsetTetap::findOrFail($request->aset_tetap_id);

            // Update mutasi barang
            $mutasi->update([
                'kode_barang' => $asetTetap->kode_barang,
                'nama_barang' => $asetTetap->nama_barang,
                'nup' => $asetTetap->nup,
                'lokasi_awal' => $request->lokasi_awal,
                'lokasi_akhir' => $request->lokasi_akhir,
                'tanggal_mutasi' => $request->tanggal_mutasi,
                'keterangan' => $request->keterangan,
                'aset_tetap_id' => $asetTetap->id,
                // Update kolom yang sudah ada di tabel
                'dari_ruangan' => $request->lokasi_awal,
                'ke_ruangan' => $request->lokasi_akhir,
                'alasan' => $request->keterangan
            ]);

            // Update lokasi di aset tetap
            $asetTetap->update([
                'lokasi' => $request->lokasi_akhir
            ]);

            DB::commit();

            return redirect()->route('admin.mutasi-barang.data')
                ->with('success', 'Mutasi barang berhasil diupdate!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $mutasi = MutasiBarang::findOrFail($id);
            $mutasi->delete();

            return response()->json([
                'success' => true,
                'message' => 'Mutasi barang berhasil dihapus!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
