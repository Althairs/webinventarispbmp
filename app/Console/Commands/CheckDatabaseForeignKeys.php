<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CheckDatabaseForeignKeys extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:foreign-keys {table?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Memeriksa struktur foreign key di database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $table = $this->argument('table');
        
        if ($table) {
            $this->checkTableForeignKeys($table);
        } else {
            $this->checkAllForeignKeys();
        }
        
        return 0;
    }

    private function checkTableForeignKeys($tableName)
    {
        $this->info("Memeriksa Foreign Keys untuk tabel: {$tableName}");
        $this->newLine();
        
        $connection = DB::connection()->getDriverName();
        
        if ($connection === 'mysql') {
            $this->checkMySQLForeignKeys($tableName);
        } elseif ($connection === 'sqlite') {
            $this->checkSQLiteForeignKeys($tableName);
        } else {
            $this->error("Driver database tidak didukung: {$connection}");
        }
    }

    private function checkAllForeignKeys()
    {
        $this->info("Memeriksa Foreign Keys untuk semua tabel");
        $this->newLine();
        
        // Tabel yang penting untuk diperiksa
        $importantTables = [
            'surat_persetujuan',
            'peminjaman_asets',
            'peminjaman_kendaraans',
            'request_models',
            'permintaan_persediaans'
        ];
        
        foreach ($importantTables as $table) {
            if (Schema::hasTable($table)) {
                $this->info("=== {$table} ===");
                $this->checkTableForeignKeys($table);
                $this->newLine();
            }
        }
    }

    private function checkMySQLForeignKeys($tableName)
    {
        try {
            $database = DB::connection()->getDatabaseName();
            
            $foreignKeys = DB::select("
                SELECT 
                    CONSTRAINT_NAME,
                    COLUMN_NAME,
                    REFERENCED_TABLE_NAME,
                    REFERENCED_COLUMN_NAME,
                    UPDATE_RULE,
                    DELETE_RULE
                FROM 
                    INFORMATION_SCHEMA.KEY_COLUMN_USAGE
                WHERE 
                    TABLE_SCHEMA = ?
                    AND TABLE_NAME = ?
                    AND REFERENCED_TABLE_NAME IS NOT NULL
            ", [$database, $tableName]);
            
            if (count($foreignKeys) > 0) {
                $this->table(
                    ['Constraint', 'Column', 'References Table', 'References Column', 'Update Rule', 'Delete Rule'],
                    collect($foreignKeys)->map(function($fk) {
                        return [
                            $fk->CONSTRAINT_NAME,
                            $fk->COLUMN_NAME,
                            $fk->REFERENCED_TABLE_NAME,
                            $fk->REFERENCED_COLUMN_NAME,
                            $fk->UPDATE_RULE,
                            $fk->DELETE_RULE
                        ];
                    })->toArray()
                );
            } else {
                $this->warn("Tidak ada foreign key constraint untuk tabel {$tableName}");
            }
        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
        }
    }

    private function checkSQLiteForeignKeys($tableName)
    {
        try {
            // SQLite tidak mendukung query INFORMATION_SCHEMA dengan baik
            // Kita cek dari schema tabel
            $schema = DB::select("PRAGMA foreign_key_list({$tableName})");
            
            if (count($schema) > 0) {
                $this->table(
                    ['ID', 'Seq', 'Table', 'From', 'To', 'On Update', 'On Delete'],
                    collect($schema)->map(function($fk) {
                        return [
                            $fk->id ?? '',
                            $fk->seq ?? '',
                            $fk->table ?? '',
                            $fk->from ?? '',
                            $fk->to ?? '',
                            $fk->on_update ?? '',
                            $fk->on_delete ?? ''
                        ];
                    })->toArray()
                );
            } else {
                $this->warn("Tidak ada foreign key constraint untuk tabel {$tableName}");
                $this->info("Note: SQLite mungkin tidak mengaktifkan foreign key checking");
            }
        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
            $this->info("Mencoba cara alternatif...");
            
            // Coba dengan cara lain
            $tableInfo = DB::select("PRAGMA table_info({$tableName})");
            $this->info("Kolom di tabel {$tableName}:");
            foreach ($tableInfo as $column) {
                $this->line("  - {$column->name} ({$column->type})");
            }
        }
    }
}

