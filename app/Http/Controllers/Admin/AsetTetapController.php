<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AsetTetap;
use App\Imports\SimpleImport;
use App\Imports\CsvImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AsetTetapController extends Controller
{
    public function index()
    {
        $asetTetap = AsetTetap::orderBy('created_at', 'desc')->paginate(10);
        return view('Admin.aset_tetap', compact('asetTetap'));
    }

    public function create()
    {
        return view('Admin.create-aset-tetap');
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'kode_barang' => 'required|string|max:50',
                'nup' => 'required|string|max:50',
                'nama_barang' => 'required|string|max:100',
                'merek' => 'required|string|max:100',
                'tanggal_peroleh' => 'required|date',
                'nilai_perolehan' => 'required|numeric|min:0',
                'kondisi' => 'required|in:baik,rusak_ringan,rusak_berat',
                'lokasi' => 'required|string|max:100',
                'jumlah' => 'required|integer|min:1',
                'kode_kategori' => 'nullable|string|max:50',
                'kategori' => 'nullable|string|max:100',
            ]);

            // Inisialisasi stok sama dengan jumlah
            $data['stok'] = $data['jumlah'];
            
            AsetTetap::create($data);

            return redirect()->route('admin.aset-tetap')->with('success', 'Data aset tetap berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan data aset tetap: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        try {
            $asetTetap = AsetTetap::findOrFail($id);
            return view('Admin.edit-aset-tetap', compact('asetTetap'));
        } catch (\Exception $e) {
            return redirect()->route('admin.aset-tetap')->with('error', 'Gagal mengambil data aset tetap: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $asetTetap = AsetTetap::findOrFail($id);
            
            $data = $request->validate([
                'kode_barang' => 'required|string|max:50',
                'nup' => 'required|string|max:50',
                'nama_barang' => 'required|string|max:100',
                'merek' => 'required|string|max:100',
                'tanggal_peroleh' => 'required|date',
                'nilai_perolehan' => 'required|numeric|min:0',
                'kondisi' => 'required|in:baik,rusak_ringan,rusak_berat',
                'lokasi' => 'required|string|max:100',
                'jumlah' => 'required|integer|min:1',
                'kode_kategori' => 'nullable|string|max:50',
                'kategori' => 'nullable|string|max:100',
            ]);

            $asetTetap->update($data);

            return redirect()->route('admin.aset-tetap')->with('success', 'Data aset tetap berhasil diupdate!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengupdate data aset tetap: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $asetTetap = AsetTetap::findOrFail($id);
            $asetTetap->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data aset tetap berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data aset tetap: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menampilkan halaman import Excel
     */
    public function showImport()
    {
        return view('Admin.import-aset-tetap');
    }

    /**
     * Menangani import data dari file Excel
     */
    public function import(Request $request)
    {
        try {
            // Validasi file - tambahkan CSV sebagai fallback
            $request->validate([
                'file' => 'required|mimes:xlsx,xls,csv|max:10240', // Max 10MB
            ], [
                'file.required' => 'File Excel/CSV wajib diupload',
                'file.mimes' => 'File harus berupa Excel (.xlsx, .xls) atau CSV (.csv)',
                'file.max' => 'Ukuran file maksimal 10MB'
            ]);

            // Gunakan file upload langsung tanpa menyimpan ke storage
            $file = $request->file('file');
            
            // Debug: Log file info
            \Log::info('File original name: ' . $file->getClientOriginalName());
            \Log::info('File path: ' . $file->getPathname());
            \Log::info('File exists: ' . (file_exists($file->getPathname()) ? 'Yes' : 'No'));
            \Log::info('File size: ' . $file->getSize());
            
            // Proses import dengan SimpleImport yang lebih reliable
            $import = new SimpleImport();
            $result = $import->import($file->getPathname());

            if ($result['success']) {
                $message = "Import berhasil! ";
                $message .= "Data berhasil diimport: {$result['success_count']} baris. ";
                
                if ($result['fail_count'] > 0) {
                    $message .= "Gagal diimport: {$result['fail_count']} baris.";
                }

                return redirect()->route('admin.aset-tetap')
                    ->with('success', $message)
                    ->with('import_errors', $result['errors']);
            } else {
                return redirect()->back()
                    ->with('error', $result['message'])
                    ->withInput();
            }

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat import: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Download template CSV untuk import
     */
    public function downloadTemplate()
    {
        $templatePath = storage_path('app/templates/template_aset_tetap.csv');
        
        if (!file_exists($templatePath)) {
            // Buat template jika belum ada
            $this->createTemplate();
        }

        return response()->download($templatePath, 'template_aset_tetap.csv');
    }

    /**
     * Membuat template CSV
     */
    private function createTemplate()
    {
        // Buat template CSV sederhana
        $templateDir = storage_path('app/templates');
        if (!file_exists($templateDir)) {
            mkdir($templateDir, 0755, true);
        }

        $templatePath = storage_path('app/templates/template_aset_tetap.csv');
        
        // Header CSV
        $headers = [
            'kode_barang',
            'nama_barang',
            'merek',
            'kategori',
            'kondisi',
            'lokasi',
            'nilai_perolehan',
            'tanggal_peroleh',
            'jumlah',
            'nup'
        ];

        // Contoh data
        $exampleData = [
            ['AST001', 'Komputer Desktop', 'Dell', 'Elektronik', 'baik', 'Ruang Admin', '5000000', '2024-01-15', '1', 'NUP001'],
            ['AST002', 'Meja Kantor', 'IKEA', 'Furniture', 'baik', 'Ruang Meeting', '1500000', '2024-01-20', '2', 'NUP002']
        ];

        // Tulis ke file CSV
        $file = fopen($templatePath, 'w');
        
        // Tulis header
        fputcsv($file, $headers);
        
        // Tulis contoh data
        foreach ($exampleData as $row) {
            fputcsv($file, $row);
        }
        
        fclose($file);
    }
}
