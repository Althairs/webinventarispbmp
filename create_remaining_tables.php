<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

echo "Creating remaining required tables...\n";

// 1. Create aset_tetap table
if (!Schema::hasTable('aset_tetap')) {
    Schema::create('aset_tetap', function (Blueprint $table) {
        $table->id();
        $table->string('kode_barang')->unique();
        $table->string('nama_barang');
        $table->string('kategori');
        $table->string('merek');
        $table->string('ruangan');
        $table->integer('jumlah');
        $table->decimal('nilai_perolehan', 15, 2);
        $table->date('tanggal_perolehan');
        $table->string('kondisi');
        $table->text('deskripsi')->nullable();
        $table->timestamps();
    });
    echo "✓ aset_tetap table created\n";
}

// 2. Create persediaan table
if (!Schema::hasTable('persediaan')) {
    Schema::create('persediaan', function (Blueprint $table) {
        $table->id();
        $table->string('kode_barang')->unique();
        $table->string('nama_barang');
        $table->string('kategori');
        $table->string('merek');
        $table->string('ruangan');
        $table->integer('jumlah');
        $table->decimal('harga', 15, 2);
        $table->date('tanggal_masuk');
        $table->string('kondisi');
        $table->text('deskripsi')->nullable();
        $table->timestamps();
    });
    echo "✓ persediaan table created\n";
}

// 3. Create transaksi_masuk_persediaan table
if (!Schema::hasTable('transaksi_masuk_persediaan')) {
    Schema::create('transaksi_masuk_persediaan', function (Blueprint $table) {
        $table->id();
        $table->string('kode_kategori');
        $table->string('kategori');
        $table->string('kode_barang');
        $table->string('nama_barang');
        $table->integer('jumlah_masuk');
        $table->decimal('harga', 15, 2);
        $table->date('tanggal_input');
        $table->text('keterangan')->nullable();
        $table->timestamps();
    });
    echo "✓ transaksi_masuk_persediaan table created\n";
}

// 4. Create transaksi_masuk_aset_tetap table
if (!Schema::hasTable('transaksi_masuk_aset_tetap')) {
    Schema::create('transaksi_masuk_aset_tetap', function (Blueprint $table) {
        $table->id();
        $table->string('kode_kategori');
        $table->string('kategori');
        $table->string('kode_barang');
        $table->string('nama_barang');
        $table->integer('jumlah_masuk');
        $table->decimal('harga', 15, 2);
        $table->date('tanggal_input');
        $table->text('keterangan')->nullable();
        $table->timestamps();
    });
    echo "✓ transaksi_masuk_aset_tetap table created\n";
}

// 5. Create transaksi_keluar_persediaan table
if (!Schema::hasTable('transaksi_keluar_persediaan')) {
    Schema::create('transaksi_keluar_persediaan', function (Blueprint $table) {
        $table->id();
        $table->string('kode_kategori');
        $table->string('kategori');
        $table->string('kode_barang');
        $table->string('nama_barang');
        $table->integer('jumlah_keluar');
        $table->decimal('harga', 15, 2);
        $table->date('tanggal_input');
        $table->text('keterangan')->nullable();
        $table->timestamps();
    });
    echo "✓ transaksi_keluar_persediaan table created\n";
}

// 6. Create transaksi_keluar_aset_tetap table
if (!Schema::hasTable('transaksi_keluar_aset_tetap')) {
    Schema::create('transaksi_keluar_aset_tetap', function (Blueprint $table) {
        $table->id();
        $table->string('kode_kategori');
        $table->string('kategori');
        $table->string('kode_barang');
        $table->string('nama_barang');
        $table->integer('jumlah_keluar');
        $table->decimal('harga', 15, 2);
        $table->date('tanggal_input');
        $table->text('keterangan')->nullable();
        $table->timestamps();
    });
    echo "✓ transaksi_keluar_aset_tetap table created\n";
}

// 7. Create mutasi_barang table
if (!Schema::hasTable('mutasi_barang')) {
    Schema::create('mutasi_barang', function (Blueprint $table) {
        $table->id();
        $table->string('kode_barang');
        $table->string('nama_barang');
        $table->string('kategori');
        $table->string('ruangan_asal');
        $table->string('ruangan_tujuan');
        $table->integer('jumlah');
        $table->date('tanggal_mutasi');
        $table->text('keterangan')->nullable();
        $table->timestamps();
    });
    echo "✓ mutasi_barang table created\n";
}

echo "All remaining tables created successfully!\n";

