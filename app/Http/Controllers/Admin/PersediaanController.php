<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Persediaan;
use Illuminate\Http\Request;

class PersediaanController extends Controller
{
    public function index()
    {
        $persediaan = Persediaan::orderBy('created_at', 'desc')->paginate(10);
        return view('Admin.persediaan', compact('persediaan'));
    }

    public function create()
    {
        return view('Admin.create-persediaan');
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'kode_kategori' => 'required|string|max:50',
                'kategori' => 'required|string|max:100',
                'kode_barang' => 'required|string|max:50',
                'nama_barang' => 'required|string|max:100',
                'jenis' => 'required|in:habis_pakai,tidak_habis_pakai',
                'harga' => 'required|numeric|min:0',
                'tanggal_masuk' => 'required|date',
                'jumlah' => 'required|integer|min:1',
            ]);

            // Set stok sama dengan jumlah saat pertama kali input
            $data['stok'] = $data['jumlah'];

            Persediaan::create($data);

            return redirect()->route('admin.persediaan')->with('success', 'Data persediaan berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan data persediaan: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        try {
            $persediaan = Persediaan::findOrFail($id);
            return view('Admin.edit-persediaan', compact('persediaan'));
        } catch (\Exception $e) {
            return redirect()->route('admin.persediaan')->with('error', 'Gagal mengambil data persediaan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $persediaan = Persediaan::findOrFail($id);
            
            $data = $request->validate([
                'kode_kategori' => 'required|string|max:50',
                'kategori' => 'required|string|max:100',
                'kode_barang' => 'required|string|max:50',
                'nama_barang' => 'required|string|max:100',
                'jenis' => 'required|in:habis_pakai,tidak_habis_pakai',
                'harga' => 'required|numeric|min:0',
                'tanggal_masuk' => 'required|date',
                'jumlah' => 'required|integer|min:1',
            ]);

            // Hitung selisih jumlah
            $oldJumlah = $persediaan->jumlah;
            $newJumlah = $data['jumlah'];
            $diff = $newJumlah - $oldJumlah;

            // Update stok sesuai dengan selisih jumlah
            $data['stok'] = $persediaan->stok + $diff;

            $persediaan->update($data);

            return redirect()->route('admin.persediaan')->with('success', 'Data persediaan berhasil diupdate!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengupdate data persediaan: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $persediaan = Persediaan::findOrFail($id);
            $persediaan->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data persediaan berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data persediaan: ' . $e->getMessage()
            ], 500);
        }
    }
}
