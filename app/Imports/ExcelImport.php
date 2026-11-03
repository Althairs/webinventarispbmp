<?php

namespace App\Imports;

use App\Models\AsetTetap;
use League\Csv\Reader;
use League\Csv\Statement;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

/**
 * Excel Import menggunakan League CSV
 * Tidak memerlukan ZipArchive extension
 */
class ExcelImport
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

            // Coba baca file dengan League CSV
            try {
                $csv = Reader::createFromPath($filePath, 'r');
                $csv->setHeaderOffset(0); // Set header row
                
            // Debug: Log headers
            $headers = $csv->getHeader();
            Log::info('CSV Headers: ' . json_encode($headers));
            
            $records = Statement::create()->process($csv);
            
            // Debug: Log jumlah records
            Log::info('Total records found: ' . count($records));
                
                if (empty($records)) {
                    return [
                        'success' => false,
                        'message' => 'File kosong atau tidak dapat dibaca',
                        'success_count' => 0,
                        'fail_count' => 0,
                        'errors' => []
                    ];
                }
            } catch (\Exception $e) {
                // Fallback: baca file sebagai CSV biasa
                Log::warning('League CSV failed, using fallback: ' . $e->getMessage());
                return $this->readFileAsCsv($filePath);
            }

            DB::beginTransaction();
            
            foreach ($records as $index => $record) {
                $rowNumber = $index + 2; // +2 because we have header and array is 0-indexed
                
                // Skip empty rows
                if (empty(array_filter($record))) {
                    continue;
                }
                
                try {
                    // Prepare data
                    $data = $this->prepareData($record, $rowNumber);
                    
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
            Log::error('Excel import error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Error loading file: ' . $e->getMessage(),
                'success_count' => $this->successCount,
                'fail_count' => $this->failCount,
                'errors' => $this->errors
            ];
        }
    }
    
    protected function prepareData($record, $rowNumber)
    {
        // Debug: Log record data
        Log::info('Processing record: ' . json_encode($record));
        
        // Map CSV columns to database fields dengan fallback untuk berbagai format header
        $data = [
            'kode_barang' => $this->getValue($record, 'kode_barang') ?: $this->getValue($record, 'Kode Barang') ?: $this->getValue($record, 'kode'),
            'nama_barang' => $this->getValue($record, 'nama_barang') ?: $this->getValue($record, 'Nama Barang') ?: $this->getValue($record, 'nama'),
            'merek' => $this->getValue($record, 'merek') ?: $this->getValue($record, 'Merek') ?: $this->getValue($record, 'brand'),
            'kategori' => $this->getValue($record, 'kategori') ?: $this->getValue($record, 'Kategori') ?: $this->getValue($record, 'category'),
            'kondisi' => $this->getValue($record, 'kondisi') ?: $this->getValue($record, 'Kondisi') ?: $this->getValue($record, 'condition'),
            'lokasi' => $this->getValue($record, 'lokasi') ?: $this->getValue($record, 'Lokasi') ?: $this->getValue($record, 'location'),
            'nilai_perolehan' => $this->getNumericValue($record, 'nilai_perolehan') ?: $this->getNumericValue($record, 'Nilai Perolehan') ?: $this->getNumericValue($record, 'nilai'),
            'tanggal_peroleh' => $this->getDateValue($record, 'tanggal_peroleh') ?: $this->getDateValue($record, 'Tanggal Peroleh') ?: $this->getDateValue($record, 'tanggal'),
            'jumlah' => $this->getNumericValue($record, 'jumlah', 1) ?: $this->getNumericValue($record, 'Jumlah', 1) ?: $this->getNumericValue($record, 'qty', 1),
            'nup' => $this->getValue($record, 'nup') ?: $this->getValue($record, 'NUP') ?: $this->getValue($record, 'nup_code'),
        ];
        
        // Debug: Log prepared data
        Log::info('Prepared data: ' . json_encode($data));
        
        return $data;
    }
    
    protected function readFileAsCsv($filePath)
    {
        try {
            // Baca file sebagai CSV biasa
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
                    $data = $this->prepareDataFromArray($row, $rowNumber);
                    
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
            Log::error('CSV fallback error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Error loading file: ' . $e->getMessage(),
                'success_count' => $this->successCount,
                'fail_count' => $this->failCount,
                'errors' => $this->errors
            ];
        }
    }
    
    protected function prepareDataFromArray($row, $rowNumber)
    {
        // Debug: Log row data
        Log::info('Processing array row: ' . json_encode($row));
        
        // Map array columns to database fields
        // Expected order: kode_barang, nama_barang, merek, kategori, kondisi, lokasi, nilai_perolehan, tanggal_peroleh, jumlah, nup
        $data = [
            'kode_barang' => $this->getValueFromArray($row, 0),
            'nama_barang' => $this->getValueFromArray($row, 1),
            'merek' => $this->getValueFromArray($row, 2),
            'kategori' => $this->getValueFromArray($row, 3),
            'kondisi' => $this->getValueFromArray($row, 4),
            'lokasi' => $this->getValueFromArray($row, 5),
            'nilai_perolehan' => $this->getNumericValueFromArray($row, 6),
            'tanggal_peroleh' => $this->getDateValueFromArray($row, 7),
            'jumlah' => $this->getNumericValueFromArray($row, 8, 1),
            'nup' => $this->getValueFromArray($row, 9),
        ];
        
        // Debug: Log prepared data
        Log::info('Prepared array data: ' . json_encode($data));
        
        return $data;
    }
    
    protected function getValueFromArray($row, $index, $default = null)
    {
        return isset($row[$index]) && $row[$index] !== '' ? trim($row[$index]) : $default;
    }
    
    protected function getNumericValueFromArray($row, $index, $default = null)
    {
        $value = $this->getValueFromArray($row, $index);
        if ($value === null || $value === '') {
            return $default;
        }
        
        // Convert to numeric, handle various formats
        $numericValue = is_numeric($value) ? (float)$value : null;
        return $numericValue;
    }
    
    protected function getDateValueFromArray($row, $index)
    {
        $value = $this->getValueFromArray($row, $index);
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
    
    protected function getValue($record, $key, $default = null)
    {
        return isset($record[$key]) && $record[$key] !== '' ? trim($record[$key]) : $default;
    }
    
    protected function getNumericValue($record, $key, $default = null)
    {
        $value = $this->getValue($record, $key);
        if ($value === null || $value === '') {
            return $default;
        }
        
        // Convert to numeric, handle various formats
        $numericValue = is_numeric($value) ? (float)$value : null;
        return $numericValue;
    }
    
    protected function getDateValue($record, $key)
    {
        $value = $this->getValue($record, $key);
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
