<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransaksiMasukPersediaan;
use App\Models\TransaksiMasukAsetTetap;
use App\Models\TransaksiKeluarPersediaan;
use App\Models\TransaksiKeluarAsetTetap;

class LaporanController extends Controller
{
    // Laporan Transaksi Masuk (persediaan & aset tetap)
    public function masuk(Request $request)
    {
        $request->validate([
            'start' => 'nullable|date',
            'end' => 'nullable|date|after_or_equal:start',
            'tipe' => 'nullable|in:semua,persediaan,aset',
        ]);

        $start = $request->input('start');
        $end = $request->input('end');
        $tipe = $request->input('tipe', 'semua');
        // Filter per-bulan: jika month=YYYY-MM dikirim, override start/end
        if ($request->filled('month')) {
            try {
                $month = \Carbon\Carbon::createFromFormat('Y-m', $request->input('month'));
                $start = $month->copy()->startOfMonth()->toDateString();
                $end = $month->copy()->endOfMonth()->toDateString();
            } catch (\Exception $e) {
                // abaikan jika format salah
            }
        }

        $qPersediaan = TransaksiMasukPersediaan::with('persediaan')->orderBy('tanggal_input','desc');
        $qAset = TransaksiMasukAsetTetap::with('asetTetap')->orderBy('tanggal_input','desc');

        if ($start) {
            $qPersediaan->whereDate('tanggal_input', '>=', $start);
            $qAset->whereDate('tanggal_input', '>=', $start);
        }
        if ($end) {
            $qPersediaan->whereDate('tanggal_input', '<=', $end);
            $qAset->whereDate('tanggal_input', '<=', $end);
        }

        $dataPersediaan = $tipe === 'aset' ? collect([]) : $qPersediaan->paginate(15, ['*'], 'persediaan_page');
        $dataAset = $tipe === 'persediaan' ? collect([]) : $qAset->paginate(15, ['*'], 'aset_page');

        // Analytics
        $totalMasukPersediaan = ($tipe === 'aset') ? 0 : (clone $qPersediaan)->sum('jumlah_masuk');
        $totalMasukAset = ($tipe === 'persediaan') ? 0 : (clone $qAset)->sum('jumlah_masuk');

        return view('laporan.masuk', [
            'start' => $start,
            'end' => $end,
            'tipe' => $tipe,
            'persediaan' => $dataPersediaan,
            'aset' => $dataAset,
            'totalMasukPersediaan' => $totalMasukPersediaan,
            'totalMasukAset' => $totalMasukAset,
        ]);
    }

    // Export CSV (Excel-friendly) transaksi masuk
    public function exportMasuk(Request $request)
    {
        $start = $request->input('start');
        $end = $request->input('end');
        $tipe = $request->input('tipe', 'semua');
        if ($request->filled('month')) {
            try {
                $month = \Carbon\Carbon::createFromFormat('Y-m', $request->input('month'));
                $start = $month->copy()->startOfMonth()->toDateString();
                $end = $month->copy()->endOfMonth()->toDateString();
            } catch (\Exception $e) {}
        }

        $qPersediaan = TransaksiMasukPersediaan::orderBy('tanggal_input','desc');
        $qAset = TransaksiMasukAsetTetap::orderBy('tanggal_input','desc');
        if ($start) { $qPersediaan->whereDate('tanggal_input','>=',$start); $qAset->whereDate('tanggal_input','>=',$start);} 
        if ($end) { $qPersediaan->whereDate('tanggal_input','<=',$end); $qAset->whereDate('tanggal_input','<=',$end);} 

        // Siapkan folder dan file di storage agar dapat diunduh dengan aman
        $dir = storage_path('app/exports');
        if (!is_dir($dir)) { @mkdir($dir, 0775, true); }
        $filename = 'laporan_transaksi_masuk_' . date('Y-m-d_His') . '.xls';
        $path = $dir . DIRECTORY_SEPARATOR . $filename;
        
        // Prepare data
        $rows = [];
        $rows[] = ['No','Tanggal Input','Kode Kategori','Kategori','Kode Barang','Nama Barang','Jumlah Masuk','Harga','Total','Keterangan'];

        $totalPersediaan = 0;
        $totalAset = 0;
        $totalHargaPersediaan = 0;
        $totalHargaAset = 0;
        $totalKeseluruhan = 0;
        $no = 1;

        if ($tipe !== 'aset') {
            $dataPersediaan = $qPersediaan->get();
            foreach ($dataPersediaan as $row) {
                $total = $row->jumlah_masuk * $row->harga;
                $tanggalFormatted = \Carbon\Carbon::parse($row->tanggal_input)->format('d/m/Y');
                $hargaFormatted = 'Rp ' . number_format($row->harga, 0, ',', '.');
                $totalFormatted = 'Rp ' . number_format($total, 0, ',', '.');
                
                $rows[] = [
                    $no++,
                    $tanggalFormatted,
                    $row->kode_kategori,
                    $row->kategori,
                    $row->kode_barang,
                    $row->nama_barang,
                    $row->jumlah_masuk,
                    $hargaFormatted,
                    $totalFormatted,
                    'Persediaan'
                ];
                $totalPersediaan += $row->jumlah_masuk;
                $totalHargaPersediaan += $total;
                $totalKeseluruhan += $total;
            }
        }
        if ($tipe !== 'persediaan') {
            $dataAset = $qAset->get();
            foreach ($dataAset as $row) {
                $nilai = $row->harga ?? 0;
                $total = $row->jumlah_masuk * $nilai;
                $tanggalFormatted = \Carbon\Carbon::parse($row->tanggal_input)->format('d/m/Y');
                $hargaFormatted = 'Rp ' . number_format($nilai, 0, ',', '.');
                $totalFormatted = 'Rp ' . number_format($total, 0, ',', '.');
                
                $rows[] = [
                    $no++,
                    $tanggalFormatted,
                    $row->kode_kategori,
                    $row->kategori,
                    $row->kode_barang,
                    $row->nama_barang,
                    $row->jumlah_masuk,
                    $hargaFormatted,
                    $totalFormatted,
                    'Aset Tetap'
                ];
                $totalAset += $row->jumlah_masuk;
                $totalHargaAset += $total;
                $totalKeseluruhan += $total;
            }
        }

        // Tambahkan baris total
        $rows[] = []; // Baris kosong
        $rows[] = ['TOTAL', '', '', '', $totalPersediaan + $totalAset, '', '', 'Rp ' . number_format($totalKeseluruhan, 0, ',', '.'), ''];

        $this->writeExcelFile($path, $rows);
        return response()->download($path, $filename, [
            'Content-Type' => 'application/vnd.ms-excel',
        ])->deleteFileAfterSend(true);
    }

    // Export CSV (Excel-friendly) transaksi keluar
    public function exportKeluar(Request $request)
    {
        $start = $request->input('start');
        $end = $request->input('end');
        $tipe = $request->input('tipe', 'semua');
        if ($request->filled('month')) {
            try { $m = \Carbon\Carbon::createFromFormat('Y-m', $request->input('month')); $start = $m->startOfMonth()->toDateString(); $end = $m->endOfMonth()->toDateString(); } catch (\Exception $e) {}
        }

        $qPersediaan = TransaksiKeluarPersediaan::orderBy('tanggal_input','desc');
        $qAset = TransaksiKeluarAsetTetap::with('asetTetap')->orderBy('tanggal_input','desc');
        if ($start) { $qPersediaan->whereDate('tanggal_input','>=',$start); $qAset->whereDate('tanggal_input','>=',$start);} 
        if ($end) { $qPersediaan->whereDate('tanggal_input','<=',$end); $qAset->whereDate('tanggal_input','<=',$end);} 

        $dir = storage_path('app/exports');
        if (!is_dir($dir)) { @mkdir($dir, 0775, true); }
        $filename = 'laporan_transaksi_keluar_' . date('Y-m-d_His') . '.xls';
        $path = $dir . DIRECTORY_SEPARATOR . $filename;
        
        // Prepare data
        $rows = [];
        $rows[] = ['No','Tanggal Input','Kode Kategori','Kategori','Kode Barang','Nama Barang','Jumlah Keluar','Harga','Total','Keterangan'];

        $totalPersediaan = 0;
        $totalAset = 0;
        $totalHargaPersediaan = 0;
        $totalHargaAset = 0;
        $totalKeseluruhan = 0;
        $no = 1;

        if ($tipe !== 'aset') {
            $dataPersediaan = $qPersediaan->get();
            foreach ($dataPersediaan as $row) {
                $total = $row->jumlah_keluar * $row->harga;
                $tanggalFormatted = \Carbon\Carbon::parse($row->tanggal_input)->format('d/m/Y');
                $hargaFormatted = 'Rp ' . number_format($row->harga, 0, ',', '.');
                $totalFormatted = 'Rp ' . number_format($total, 0, ',', '.');
                
                $rows[] = [
                    $no++,
                    $tanggalFormatted,
                    $row->kode_kategori,
                    $row->kategori,
                    $row->kode_barang,
                    $row->nama_barang,
                    $row->jumlah_keluar,
                    $hargaFormatted,
                    $totalFormatted,
                    'Persediaan'
                ];
                $totalPersediaan += $row->jumlah_keluar;
                $totalHargaPersediaan += $total;
                $totalKeseluruhan += $total;
            }
        }
        if ($tipe !== 'persediaan') {
            $dataAset = $qAset->with('asetTetap')->get();
            foreach ($dataAset as $row) {
                $nilai = $row->asetTetap->nilai_perolehan ?? 0;
                $total = $row->jumlah_keluar * $nilai;
                $tanggalFormatted = \Carbon\Carbon::parse($row->tanggal_input)->format('d/m/Y');
                $hargaFormatted = 'Rp ' . number_format($nilai, 0, ',', '.');
                $totalFormatted = 'Rp ' . number_format($total, 0, ',', '.');
                
                $rows[] = [
                    $no++,
                    $tanggalFormatted,
                    $row->kode_kategori,
                    $row->kategori,
                    $row->kode_barang,
                    $row->nama_barang,
                    $row->jumlah_keluar,
                    $hargaFormatted,
                    $totalFormatted,
                    'Aset Tetap'
                ];
                $totalAset += $row->jumlah_keluar;
                $totalHargaAset += $total;
                $totalKeseluruhan += $total;
            }
        }

        // Tambahkan baris total
        $rows[] = []; // Baris kosong
        $rows[] = ['TOTAL', '', '', '', $totalPersediaan + $totalAset, '', 'Rp ' . number_format($totalKeseluruhan, 0, ',', '.'), '', ''];

        $this->writeExcelFile($path, $rows);
        return response()->download($path, $filename, [
            'Content-Type' => 'application/vnd.ms-excel',
        ])->deleteFileAfterSend(true);
    }
    // Laporan Transaksi Keluar (persediaan & aset tetap)
    public function keluar(Request $request)
    {
        $request->validate([
            'start' => 'nullable|date',
            'end' => 'nullable|date|after_or_equal:start',
            'tipe' => 'nullable|in:semua,persediaan,aset',
        ]);

        $start = $request->input('start');
        $end = $request->input('end');
        $tipe = $request->input('tipe', 'semua');
        if ($request->filled('month')) {
            try { $m = \Carbon\Carbon::createFromFormat('Y-m', $request->input('month')); $start = $m->startOfMonth()->toDateString(); $end = $m->endOfMonth()->toDateString(); } catch (\Exception $e) {}
        }

        $qPersediaan = TransaksiKeluarPersediaan::with('persediaan')->orderBy('tanggal_input','desc');
        $qAset = TransaksiKeluarAsetTetap::with('asetTetap')->orderBy('tanggal_input','desc');

        if ($start) {
            $qPersediaan->whereDate('tanggal_input', '>=', $start);
            $qAset->whereDate('tanggal_input', '>=', $start);
        }
        if ($end) {
            $qPersediaan->whereDate('tanggal_input', '<=', $end);
            $qAset->whereDate('tanggal_input', '<=', $end);
        }

        $dataPersediaan = $tipe === 'aset' ? collect([]) : $qPersediaan->paginate(15, ['*'], 'persediaan_page');
        $dataAset = $tipe === 'persediaan' ? collect([]) : $qAset->paginate(15, ['*'], 'aset_page');

        // Analytics
        $totalKeluarPersediaan = ($tipe === 'aset') ? 0 : (clone $qPersediaan)->sum('jumlah_keluar');
        $totalKeluarAset = ($tipe === 'persediaan') ? 0 : (clone $qAset)->sum('jumlah_keluar');

        return view('laporan.keluar', [
            'start' => $start,
            'end' => $end,
            'tipe' => $tipe,
            'persediaan' => $dataPersediaan,
            'aset' => $dataAset,
            'totalKeluarPersediaan' => $totalKeluarPersediaan,
            'totalKeluarAset' => $totalKeluarAset,
        ]);
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


