<?php

namespace App\Imports;

use App\Models\AsetTetap;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * Simple Import untuk Excel/CSV
 * Menggunakan native PHP functions
 */
class SimpleImport
{
    protected $errors = [];
    protected $successCount = 0;
    protected $failCount = 0;

    public function import($filePath)
    {
        try {
            // Pastikan file ada
            if (!file_exists($filePath)) {
                return [
                    'success' => false,
                    'message' => 'File tidak ditemukan: ' . $filePath,
                    'success_count' => 0,
                    'fail_count' => 0,
                    'errors' => []
                ];
            }

            // Baca file sebagai CSV
            $rows = [];
            if (($handle = fopen($filePath, "r")) !== FALSE) {
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $rows[] = $data;
                }
                fclose($handle);
            }
            
            if (empty($rows)) {
                return [
                    'success' => false,
                    'message' => 'File kosong atau tidak dapat dibaca',
                    'success_count' => 0,
                    'fail_count' => 0,
                    'errors' => []
                ];
            }
            
            // Skip header row
            $dataRows = array_slice($rows, 1);
            
            DB::beginTransaction();
            
            foreach ($dataRows as $index => $row) {
                $rowNumber = $index + 2; // +2 because we skip header and array is 0-indexed
                
                // Skip empty rows
                if (empty(array_filter($row))) {
                    continue;
                }
                
                try {
                    // Prepare data dengan mapping manual
                    $data = $this->prepareData($row, $rowNumber);
                    
                    // Validate data
                    $validator = $this->validateData($data);
                    
                    if ($validator->fails()) {
                        $this->errors[] = [
                            'row' => $rowNumber,
                            'errors' => $validator->errors()->all()
                        ];
                        $this->failCount++;
                        continue;
                    }
                    
                    // Create AsetTetap record
                    AsetTetap::create($data);
                    $this->successCount++;
                    
                } catch (\Exception $e) {
                    $this->errors[] = [
                        'row' => $rowNumber,
                        'errors' => ['Error: ' . $e->getMessage()]
                    ];
                    $this->failCount++;
                }
            }
            
            DB::commit();
            
            return [
                'success' => true,
                'success_count' => $this->successCount,
                'fail_count' => $this->failCount,
                'errors' => $this->errors
            ];
            
        } catch (\Exception $e) {
            DB::rollback();
            
            return [
                'success' => false,
                'message' => 'Error loading file: ' . $e->getMessage(),
                'success_count' => $this->successCount,
                'fail_count' => $this->failCount,
                'errors' => $this->errors
            ];
        }
    }
    
    protected function prepareData($row, $rowNumber)
    {
        // Map array columns to database fields
        // Expected order: kode_barang, nama_barang, merek, kategori, kondisi, lokasi, nilai_perolehan, tanggal_peroleh, jumlah, nup
        $data = [
            'kode_barang' => $this->getValue($row, 0),
            'nama_barang' => $this->getValue($row, 1),
            'merek' => $this->getValue($row, 2),
            'kategori' => $this->getValue($row, 3),
            'kondisi' => $this->getValue($row, 4),
            'lokasi' => $this->getValue($row, 5),
            'nilai_perolehan' => $this->getNumericValue($row, 6),
            'tanggal_peroleh' => $this->getDateValue($row, 7),
            'jumlah' => $this->getNumericValue($row, 8, 1),
            'nup' => $this->getValue($row, 9),
        ];
        
        return $data;
    }
    
    protected function getValue($row, $index, $default = null)
    {
        return isset($row[$index]) && $row[$index] !== '' ? trim($row[$index]) : $default;
    }
    
    protected function getNumericValue($row, $index, $default = null)
    {
        $value = $this->getValue($row, $index);
        if ($value === null || $value === '') {
            return $default;
        }
        
        // Convert to numeric, handle various formats
        $numericValue = is_numeric($value) ? (float)$value : null;
        return $numericValue;
    }
    
    protected function getDateValue($row, $index)
    {
        $value = $this->getValue($row, $index);
        if ($value === null || $value === '') {
            return null;
        }
        
        try {
            // Try to parse as string date
            $date = Carbon::parse($value);
            return $date->format('Y-m-d');
            
        } catch (\Exception $e) {
            return null;
        }
    }
    
    protected function validateData($data)
    {
        return Validator::make($data, [
            'kode_barang' => 'required|string|max:50',
            'nama_barang' => 'required|string|max:100',
            'merek' => 'required|string|max:100',
            'kategori' => 'required|string|max:100',
            'kondisi' => 'required|in:baik,rusak_ringan,rusak_berat',
            'lokasi' => 'required|string|max:100',
            'nilai_perolehan' => 'nullable|numeric|min:0',
            'tanggal_peroleh' => 'nullable|date',
            'jumlah' => 'required|integer|min:1',
            'nup' => 'nullable|string|max:50',
        ], [
            'kode_barang.required' => 'Kode barang wajib diisi',
            'nama_barang.required' => 'Nama barang wajib diisi',
            'merek.required' => 'Merek wajib diisi',
            'kategori.required' => 'Kategori wajib diisi',
            'kondisi.required' => 'Kondisi wajib diisi',
            'kondisi.in' => 'Kondisi harus salah satu dari: baik, rusak_ringan, rusak_berat',
            'lokasi.required' => 'Lokasi wajib diisi',
            'nilai_perolehan.numeric' => 'Nilai perolehan harus berupa angka',
            'nilai_perolehan.min' => 'Nilai perolehan tidak boleh negatif',
            'tanggal_peroleh.date' => 'Format tanggal tidak valid',
            'jumlah.required' => 'Jumlah wajib diisi',
            'jumlah.integer' => 'Jumlah harus berupa angka bulat',
            'jumlah.min' => 'Jumlah minimal 1',
        ]);
    }
}

























