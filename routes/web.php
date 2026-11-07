<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\KasubagController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SuratController;
use App\Http\Controllers\TransaksiMasukController;
use App\Http\Controllers\TransaksiKeluarController;
use App\Http\Controllers\MutasiBarangController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\KepalaBpmpController;
use App\Http\Controllers\HomeController;


// Rute untuk halaman beranda
Route::get('/', [HomeController::class, 'index'])->name('home');


// AuthController 
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login_process');
// Route::post('/login', [AuthController::class, 'login_process'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rute untuk registrasi
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register');
// Route::get('/register/google', [AuthController::class, 'redirectToGoogle'])->name('register.google');

// Test route untuk debug registrasi
Route::get('/test-register', function () {
    return view('test_register');
})->name('test.register');

Route::middleware(['auth'])->group(function () {
    // Rute untuk Admin
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/index', [AdminController::class, 'dashboard'])->name('dashboard');
        
        // Routes untuk Persediaan
        Route::get('/persediaan', [App\Http\Controllers\Admin\PersediaanController::class, 'index'])->name('persediaan');
        Route::get('/persediaan/create', [App\Http\Controllers\Admin\PersediaanController::class, 'create'])->name('persediaan.create');
        Route::post('/persediaan', [App\Http\Controllers\Admin\PersediaanController::class, 'store'])->name('persediaan.store');
        Route::get('/persediaan/{id}/edit', [App\Http\Controllers\Admin\PersediaanController::class, 'edit'])->name('persediaan.edit');
        Route::put('/persediaan/{id}', [App\Http\Controllers\Admin\PersediaanController::class, 'update'])->name('persediaan.update');
        Route::delete('/persediaan/{id}', [App\Http\Controllers\Admin\PersediaanController::class, 'destroy'])->name('persediaan.destroy');
        
        // Routes untuk Aset Tetap
        Route::get('/aset-tetap', [App\Http\Controllers\Admin\AsetTetapController::class, 'index'])->name('aset-tetap');
        Route::get('/aset-tetap/create', [App\Http\Controllers\Admin\AsetTetapController::class, 'create'])->name('aset-tetap.create');
        Route::post('/aset-tetap', [App\Http\Controllers\Admin\AsetTetapController::class, 'store'])->name('aset-tetap.store');
        Route::get('/aset-tetap/{id}/edit', [App\Http\Controllers\Admin\AsetTetapController::class, 'edit'])->name('aset-tetap.edit');
        Route::put('/aset-tetap/{id}', [App\Http\Controllers\Admin\AsetTetapController::class, 'update'])->name('aset-tetap.update');
        Route::delete('/aset-tetap/{id}', [App\Http\Controllers\Admin\AsetTetapController::class, 'destroy'])->name('aset-tetap.destroy');
        
        // Routes untuk Import Excel Aset Tetap
        Route::get('/aset-tetap/import', [App\Http\Controllers\Admin\AsetTetapController::class, 'showImport'])->name('aset-tetap.import');
        Route::post('/aset-tetap/import', [App\Http\Controllers\Admin\AsetTetapController::class, 'import'])->name('aset-tetap.import.process');
        Route::get('/aset-tetap/template', [App\Http\Controllers\Admin\AsetTetapController::class, 'downloadTemplate'])->name('aset-tetap.template');

        // Routes untuk Transaksi Barang Masuk
        Route::get('/transaksi-masuk', [TransaksiMasukController::class, 'index'])->name('transaksi-masuk.index');
        Route::get('/transaksi-masuk/persediaan', [TransaksiMasukController::class, 'persediaan'])->name('transaksi-masuk.persediaan');
        Route::get('/transaksi-masuk/aset-tetap', [TransaksiMasukController::class, 'asetTetap'])->name('transaksi-masuk.aset-tetap');
        Route::get('/transaksi-masuk/persediaan/create', [TransaksiMasukController::class, 'createPersediaan'])->name('transaksi-masuk.create-persediaan');
        Route::get('/transaksi-masuk/aset-tetap/create', [TransaksiMasukController::class, 'createAsetTetap'])->name('transaksi-masuk.create-aset-tetap');
        Route::post('/transaksi-masuk/persediaan', [TransaksiMasukController::class, 'storePersediaan'])->name('transaksi-masuk.store-persediaan');
        Route::post('/transaksi-masuk/aset-tetap', [TransaksiMasukController::class, 'storeAsetTetap'])->name('transaksi-masuk.store-aset-tetap');
        
        // Routes untuk Edit dan Delete Transaksi Masuk Persediaan
        Route::get('/transaksi-masuk/persediaan/{id}/edit', [TransaksiMasukController::class, 'editPersediaan'])->name('transaksi-masuk.edit-persediaan');
        Route::put('/transaksi-masuk/persediaan/{id}', [TransaksiMasukController::class, 'updatePersediaan'])->name('transaksi-masuk.update-persediaan');
        Route::delete('/transaksi-masuk/persediaan/{id}', [TransaksiMasukController::class, 'destroyPersediaan'])->name('transaksi-masuk.destroy-persediaan');
        
        // Routes untuk Edit dan Delete Transaksi Masuk Aset Tetap
        Route::get('/transaksi-masuk/aset-tetap/{id}/edit', [TransaksiMasukController::class, 'editAsetTetap'])->name('transaksi-masuk.edit-aset-tetap');
        Route::put('/transaksi-masuk/aset-tetap/{id}', [TransaksiMasukController::class, 'updateAsetTetap'])->name('transaksi-masuk.update-aset-tetap');
        Route::delete('/transaksi-masuk/aset-tetap/{id}', [TransaksiMasukController::class, 'destroyAsetTetap'])->name('transaksi-masuk.destroy-aset-tetap');
        
        // Routes untuk Histori Transaksi
        Route::get('/transaksi-masuk/histori-persediaan', [TransaksiMasukController::class, 'historiPersediaan'])->name('transaksi-masuk.histori-persediaan');
        Route::get('/transaksi-masuk/histori-aset-tetap', [TransaksiMasukController::class, 'historiAsetTetap'])->name('transaksi-masuk.histori-aset-tetap');

        // Routes untuk Transaksi Barang Keluar
        Route::get('/transaksi-keluar', [TransaksiKeluarController::class, 'index'])->name('transaksi-keluar.index');
        Route::get('/transaksi-keluar/persediaan', [TransaksiKeluarController::class, 'persediaan'])->name('transaksi-keluar.persediaan');
        Route::get('/transaksi-keluar/aset-tetap', [TransaksiKeluarController::class, 'asetTetap'])->name('transaksi-keluar.aset-tetap');
        Route::get('/transaksi-keluar/persediaan/create', [TransaksiKeluarController::class, 'createPersediaan'])->name('transaksi-keluar.create-persediaan');
        Route::get('/transaksi-keluar/aset-tetap/create', [TransaksiKeluarController::class, 'createAsetTetap'])->name('transaksi-keluar.create-aset-tetap');
        Route::post('/transaksi-keluar/persediaan', [TransaksiKeluarController::class, 'storePersediaan'])->name('transaksi-keluar.store-persediaan');
        Route::post('/transaksi-keluar/aset-tetap', [TransaksiKeluarController::class, 'storeAsetTetap'])->name('transaksi-keluar.store-aset-tetap');
        
        // Routes untuk Edit dan Delete Transaksi Keluar Persediaan
        Route::get('/transaksi-keluar/persediaan/{id}/edit', [TransaksiKeluarController::class, 'editPersediaan'])->name('transaksi-keluar.edit-persediaan');
        Route::put('/transaksi-keluar/persediaan/{id}', [TransaksiKeluarController::class, 'updatePersediaan'])->name('transaksi-keluar.update-persediaan');
        Route::delete('/transaksi-keluar/persediaan/{id}', [TransaksiKeluarController::class, 'destroyPersediaan'])->name('transaksi-keluar.destroy-persediaan');
        
        // Routes untuk Edit dan Delete Transaksi Keluar Aset Tetap
        Route::get('/transaksi-keluar/aset-tetap/{id}/edit', [TransaksiKeluarController::class, 'editAsetTetap'])->name('transaksi-keluar.edit-aset-tetap');
        Route::put('/transaksi-keluar/aset-tetap/{id}', [TransaksiKeluarController::class, 'updateAsetTetap'])->name('transaksi-keluar.update-aset-tetap');
        Route::delete('/transaksi-keluar/aset-tetap/{id}', [TransaksiKeluarController::class, 'destroyAsetTetap'])->name('transaksi-keluar.destroy-aset-tetap');
        
        // Routes untuk Histori Transaksi Keluar
        Route::get('/transaksi-keluar/histori-persediaan', [TransaksiKeluarController::class, 'historiPersediaan'])->name('transaksi-keluar.histori-persediaan');
        Route::get('/transaksi-keluar/histori-aset-tetap', [TransaksiKeluarController::class, 'historiAsetTetap'])->name('transaksi-keluar.histori-aset-tetap');

        // Routes untuk Mutasi Barang
        Route::get('/mutasi-barang', [MutasiBarangController::class, 'index'])->name('mutasi-barang.index');
        Route::get('/mutasi-barang/data', [MutasiBarangController::class, 'data'])->name('mutasi-barang.data');
        Route::get('/mutasi-barang/create', [MutasiBarangController::class, 'create'])->name('mutasi-barang.create');
        Route::post('/mutasi-barang', [MutasiBarangController::class, 'store'])->name('mutasi-barang.store');
        Route::get('/mutasi-barang/histori', [MutasiBarangController::class, 'histori'])->name('mutasi-barang.histori');
        Route::get('/mutasi-barang/{id}/edit', [MutasiBarangController::class, 'edit'])->name('mutasi-barang.edit');
        Route::put('/mutasi-barang/{id}', [MutasiBarangController::class, 'update'])->name('mutasi-barang.update');
        Route::delete('/mutasi-barang/{id}', [MutasiBarangController::class, 'destroy'])->name('mutasi-barang.destroy');
        

        // Routes untuk Request Barang
        Route::get('/request-barang', [AdminController::class, 'request_barang'])->name('request');
        Route::post('/request-barang/{id}/approve', [AdminController::class, 'approveRequest'])->name('request.approve');
        Route::post('/request-barang/{id}/reject', [AdminController::class, 'rejectRequest'])->name('request.reject');

        // Routes untuk Manajemen Pengajuan
        Route::get('/peminjaman-barang', [AdminController::class, 'peminjamanBarang'])->name('peminjaman-barang');
        Route::get('/peminjaman-barang/{id}/detail', [AdminController::class, 'getDetailPeminjamanBarang'])->name('peminjaman-barang.detail');
        Route::post('/peminjaman-barang/{id}/approve', [AdminController::class, 'approvePeminjamanBarang'])->name('peminjaman-barang.approve');
        Route::post('/peminjaman-barang/{id}/reject', [AdminController::class, 'rejectPeminjamanBarang'])->name('peminjaman-barang.reject');
        
        Route::get('/permintaan-persediaan', [AdminController::class, 'permintaanPersediaan'])->name('permintaan-persediaan');
        Route::get('/permintaan-persediaan/{id}/detail', [AdminController::class, 'getDetailPermintaanPersediaan'])->name('permintaan-persediaan.detail');
        Route::post('/permintaan-persediaan/{id}/approve', [AdminController::class, 'approvePermintaanPersediaan'])->name('permintaan-persediaan.approve');
        Route::post('/permintaan-persediaan/{id}/reject', [AdminController::class, 'rejectPermintaanPersediaan'])->name('permintaan-persediaan.reject');
        
        Route::get('/peminjaman-kendaraan', [AdminController::class, 'peminjamanKendaraan'])->name('peminjaman-kendaraan');
        Route::get('/peminjaman-kendaraan/{id}/detail', [AdminController::class, 'getDetailPeminjamanKendaraan'])->name('peminjaman-kendaraan.detail');
        Route::post('/peminjaman-kendaraan/{id}/approve', [AdminController::class, 'approvePeminjamanKendaraan'])->name('peminjaman-kendaraan.approve');
        Route::post('/peminjaman-kendaraan/{id}/reject', [AdminController::class, 'rejectPeminjamanKendaraan'])->name('peminjaman-kendaraan.reject');
        
        Route::get('/peminjaman-aset', [AdminController::class, 'peminjamanAset'])->name('peminjaman-aset');
        Route::get('/peminjaman-aset/{id}/detail', [AdminController::class, 'getDetailPeminjamanAset'])->name('peminjaman-aset.detail');
        Route::get('/peminjaman-aset/{id}/view-surat', [AdminController::class, 'viewSuratPeminjamanAset'])->name('peminjaman-aset.view-surat');
        Route::get('/peminjaman-aset/{id}/download-surat', [AdminController::class, 'downloadSuratPeminjamanAset'])->name('peminjaman-aset.download-surat');
        Route::post('/peminjaman-aset/{id}/approve', [AdminController::class, 'approvePeminjamanAset'])->name('peminjaman-aset.approve');
        Route::post('/peminjaman-aset/{id}/reject', [AdminController::class, 'rejectPeminjamanAset'])->name('peminjaman-aset.reject');

        // Routes untuk Review dan Forward Admin
        Route::post('/peminjaman-barang/{id}/review', [AdminController::class, 'reviewPeminjamanBarang'])->name('peminjaman-barang.review');
        Route::post('/peminjaman-barang/{id}/forward', [AdminController::class, 'forwardPeminjamanBarang'])->name('peminjaman-barang.forward');
        
        Route::post('/permintaan-persediaan/{id}/review', [AdminController::class, 'reviewPermintaanPersediaan'])->name('permintaan-persediaan.review');
        Route::post('/permintaan-persediaan/{id}/forward', [AdminController::class, 'forwardPermintaanPersediaan'])->name('permintaan-persediaan.forward');
        
        Route::post('/peminjaman-kendaraan/{id}/review', [AdminController::class, 'reviewPeminjamanKendaraan'])->name('peminjaman-kendaraan.review');
        Route::post('/peminjaman-kendaraan/{id}/forward', [AdminController::class, 'forwardPeminjamanKendaraan'])->name('peminjaman-kendaraan.forward');
        
        Route::post('/peminjaman-aset/{id}/review', [AdminController::class, 'reviewPeminjamanAset'])->name('peminjaman-aset.review');
        Route::post('/peminjaman-aset/{id}/forward', [AdminController::class, 'forwardPeminjamanAset'])->name('peminjaman-aset.forward');
        
        // Routes untuk generate surat
        Route::get('/surat/peminjaman-barang/{id}', [SuratController::class, 'suratPeminjamanBarang'])->name('surat.peminjaman-barang');
        Route::get('/surat/permintaan-persediaan/{id}', [SuratController::class, 'suratPermintaanPersediaan'])->name('surat.permintaan-persediaan');
        Route::get('/surat/peminjaman-kendaraan/{id}', [SuratController::class, 'suratPeminjamanKendaraan'])->name('surat.peminjaman-kendaraan');
        Route::get('/surat/peminjaman-aset/{id}', [SuratController::class, 'suratPeminjamanAset'])->name('surat.peminjaman-aset');
        
        // Routes untuk preview surat
        Route::get('/surat/preview/peminjaman-barang/{id}', [SuratController::class, 'previewSuratPeminjamanBarang'])->name('surat.preview.peminjaman-barang');
        Route::get('/surat/preview/permintaan-persediaan/{id}', [SuratController::class, 'previewSuratPermintaanPersediaan'])->name('surat.preview.permintaan-persediaan');
        Route::get('/surat/preview/peminjaman-kendaraan/{id}', [SuratController::class, 'previewSuratPeminjamanKendaraan'])->name('surat.preview.peminjaman-kendaraan');
        Route::get('/surat/preview/peminjaman-aset/{id}', [SuratController::class, 'previewSuratPeminjamanAset'])->name('surat.preview.peminjaman-aset');
        
        // Routes untuk upload surat persetujuan admin
        Route::post('/surat-persetujuan', [App\Http\Controllers\Admin\SuratPersetujuanController::class, 'store'])->name('surat-persetujuan.store');

        Route::get('/laporan', [AdminController::class, 'laporan'])->name('laporan');
        // Laporan terpisah: transaksi masuk & keluar
        Route::get('/laporan/masuk', [\App\Http\Controllers\LaporanController::class, 'masuk'])->name('laporan.masuk');
        Route::get('/laporan/keluar', [\App\Http\Controllers\LaporanController::class, 'keluar'])->name('laporan.keluar');
        // Export to Excel (CSV) for transaksi masuk/keluar
        Route::get('/laporan/masuk/export', [\App\Http\Controllers\LaporanController::class, 'exportMasuk'])
            ->name('laporan.masuk.export');
        Route::get('/laporan/keluar/export', [\App\Http\Controllers\LaporanController::class, 'exportKeluar'])
            ->name('laporan.keluar.export');

        //rute untuk download laporan (existing)
        Route::get('/laporan/download-bulanan', [AdminController::class, 'downloadBulanan'])->name('laporan.download_bulanan');
        // Export laporan pengajuan & mutasi (Excel CSV)
        Route::get('/laporan/pengajuan/export', [AdminController::class, 'exportPengajuan'])->name('laporan.pengajuan.export');
        Route::get('/laporan/mutasi/export', [AdminController::class, 'exportMutasi'])->name('laporan.mutasi.export');
        // Backward compatibility: URL lama
        Route::get('/laporan/download-pengajuan', [AdminController::class, 'exportPengajuan'])->name('laporan.download_pengajuan');
        Route::get('/laporan/download-mutasi', [AdminController::class, 'downloadMutasi'])->name('laporan.download_mutasi');
        
    });

    // Rute untuk Kasubag
    Route::middleware(['role:kasubag'])->prefix('kasubag')->name('kasubag.')->group(function () {
        Route::get('/dashboard', [KasubagController::class, 'dashboard'])->name('dashboard');
        
        // Routes untuk peminjaman barang
        Route::get('/peminjaman-barang', [KasubagController::class, 'peminjamanBarang'])->name('peminjaman-barang');
        Route::get('/peminjaman-barang/{id}/detail', [KasubagController::class, 'detailPeminjamanBarang'])->name('peminjaman-barang.detail');
        Route::post('/peminjaman-barang/{id}/approve', [KasubagController::class, 'approvePeminjamanBarang'])->name('peminjaman-barang.approve');
        
        // Routes untuk permintaan persediaan
        Route::get('/permintaan-persediaan', [KasubagController::class, 'permintaanPersediaan'])->name('permintaan-persediaan');
        Route::get('/permintaan-persediaan/{id}/detail', [KasubagController::class, 'detailPermintaanPersediaan'])->name('permintaan-persediaan.detail');
        Route::post('/permintaan-persediaan/{id}/approve', [KasubagController::class, 'approvePermintaanPersediaan'])->name('permintaan-persediaan.approve');
        
        // Routes untuk peminjaman kendaraan
        Route::get('/peminjaman-kendaraan', [KasubagController::class, 'peminjamanKendaraan'])->name('peminjaman-kendaraan');
        Route::get('/peminjaman-kendaraan/{id}/detail', [KasubagController::class, 'detailPeminjamanKendaraan'])->name('peminjaman-kendaraan.detail');
        Route::post('/peminjaman-kendaraan/{id}/approve', [KasubagController::class, 'approvePeminjamanKendaraan'])->name('peminjaman-kendaraan.approve');
        
        // Routes untuk peminjaman aset
        Route::get('/peminjaman-aset', [KasubagController::class, 'peminjamanAset'])->name('peminjaman-aset');
        Route::get('/peminjaman-aset/{id}/detail', [KasubagController::class, 'detailPeminjamanAset'])->name('peminjaman-aset.detail');
        Route::post('/peminjaman-aset/{id}/approve', [KasubagController::class, 'approvePeminjamanAset'])->name('peminjaman-aset.approve');
        Route::get('/peminjaman-aset/{id}/view-surat', [KasubagController::class, 'viewSuratPeminjamanAset'])->name('peminjaman-aset.view-surat');
        Route::get('/peminjaman-aset/{id}/download-surat', [KasubagController::class, 'downloadSuratPeminjamanAset'])->name('peminjaman-aset.download-surat');
    });

    // Rute untuk user
    Route::middleware(['role:user'])->prefix('user')->name('user.')->group(function () {
        Route::get('/index', [UserController::class, 'dashboard'])->name('dashboard');
        Route::get('/request', [UserController::class, 'requestBarang'])->name('request');

        // Rute untuk menampilkan form pembuatan request
        Route::get('/request/create', [UserController::class, 'createRequest'])->name('request.create');

        // Rute untuk menyimpan request yang baru
        Route::post('/request', [UserController::class, 'storeRequest'])->name('request.store');
        
        // Rute untuk pengaturan akun
        Route::get('/settings', [UserController::class, 'settings'])->name('settings');
        Route::put('/update-profile', [UserController::class, 'updateProfile'])->name('update-profile');
        Route::delete('/delete-signature', [UserController::class, 'deleteSignature'])->name('delete-signature');
        
        // Rute untuk permintaan persediaan
        Route::get('/permintaan-persediaan', [UserController::class, 'permintaanPersediaan'])->name('permintaan-persediaan');
        Route::post('/supply-request', [UserController::class, 'storeSupplyRequest'])->name('supply-request.store');
        
        // Rute untuk peminjaman kendaraan
        Route::get('/peminjaman-kendaraan', [UserController::class, 'peminjamanKendaraan'])->name('peminjaman-kendaraan');
        Route::post('/vehicle-request', [UserController::class, 'storeVehicleRequest'])->name('vehicle-request.store');
        
        // Routes untuk detail histori
        Route::get('/peminjaman-barang/{id}/detail', [UserController::class, 'getDetailPeminjamanBarang'])->name('user.peminjaman-barang.detail');
        Route::get('/permintaan-persediaan/{id}/detail', [UserController::class, 'getDetailPermintaanPersediaan'])->name('user.permintaan-persediaan.detail');
        Route::get('/peminjaman-kendaraan/{id}/detail', [UserController::class, 'getDetailPeminjamanKendaraan'])->name('user.peminjaman-kendaraan.detail');
        
        // Routes untuk notifikasi
        Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');
        Route::get('/notifikasi/{id}', [NotifikasiController::class, 'show'])->name('notifikasi.show');
        Route::post('/notifikasi/{id}/mark-read', [NotifikasiController::class, 'markAsRead'])->name('notifikasi.mark-read');
        Route::post('/notifikasi/mark-all-read', [NotifikasiController::class, 'markAllAsRead'])->name('notifikasi.mark-all-read');
        Route::delete('/notifikasi/{id}', [NotifikasiController::class, 'destroy'])->name('notifikasi.destroy');
        Route::get('/notifikasi/{id}/download-surat', [NotifikasiController::class, 'downloadSuratPersetujuan'])->name('notifikasi.download-surat');
        Route::get('/api/notifikasi/unread', [NotifikasiController::class, 'getUnreadNotifications'])->name('notifikasi.unread');
        
        // Routes untuk generate surat user
        Route::get('/surat/peminjaman-barang/{id}', [SuratController::class, 'suratPeminjamanBarang'])->name('surat.peminjaman-barang');
        Route::get('/surat/permintaan-persediaan/{id}', [SuratController::class, 'suratPermintaanPersediaan'])->name('surat.permintaan-persediaan');
        Route::get('/surat/peminjaman-kendaraan/{id}', [SuratController::class, 'suratPeminjamanKendaraan'])->name('surat.peminjaman-kendaraan');
        
        // Routes untuk preview surat user
        Route::get('/surat/preview/peminjaman-barang/{id}', [SuratController::class, 'previewSuratPeminjamanBarang'])->name('surat.preview.peminjaman-barang');
        Route::get('/surat/preview/permintaan-persediaan/{id}', [SuratController::class, 'previewSuratPermintaanPersediaan'])->name('surat.preview.permintaan-persediaan');
        Route::get('/surat/preview/peminjaman-kendaraan/{id}', [SuratController::class, 'previewSuratPeminjamanKendaraan'])->name('surat.preview.peminjaman-kendaraan');
        
        // Route untuk histori permintaan dengan surat persetujuan
        Route::get('/histori-permintaan', [UserController::class, 'historiPermintaan'])->name('histori-permintaan');
        
    });

    // Route untuk download surat persetujuan (akses publik tapi aman)
    Route::get('/download-surat/{id}', [App\Http\Controllers\Admin\SuratPersetujuanController::class, 'download'])->name('surat-persetujuan.download');
    
    // Route untuk preview surat persetujuan
    Route::get('/preview-surat/{id}', [App\Http\Controllers\Admin\SuratPersetujuanController::class, 'preview'])->name('surat-persetujuan.preview');

    // Group untuk Tamu
    Route::middleware(['role:tamu'])->prefix('guest')->name('guest.')->group(function () {
        Route::get('/dashboard', [GuestController::class, 'dashboard'])->name('dashboard');
        Route::get('/peminjaman-aset', [GuestController::class, 'peminjamanAset'])->name('peminjaman-aset');
        Route::get('/histori-peminjaman-aset', [GuestController::class, 'historiPeminjamanAset'])->name('histori-peminjaman-aset');
        Route::post('/asset-request', [GuestController::class, 'storeAssetRequest'])->name('asset-request.store');
        Route::get('/peminjaman-aset/{id}/detail', [GuestController::class, 'detailPeminjamanAset'])->name('peminjaman-aset.detail');
        Route::get('/peminjaman-aset/{id}/ajax-detail', [GuestController::class, 'getDetailPeminjamanAset'])->name('peminjaman-aset.ajax-detail');
        Route::get('/peminjaman-aset/{id}/view-surat', [GuestController::class, 'viewSuratPeminjamanAset'])->name('peminjaman-aset.view-surat');
        Route::get('/peminjaman-aset/{id}/download-surat', [GuestController::class, 'downloadSuratPeminjamanAset'])->name('peminjaman-aset.download-surat');
        
        // Routes untuk generate surat tamu
        Route::get('/surat/peminjaman-aset/{id}', [SuratController::class, 'suratPeminjamanAset'])->name('surat.peminjaman-aset');
        
        // Routes untuk preview surat tamu
        Route::get('/surat/preview/peminjaman-aset/{id}', [SuratController::class, 'previewSuratPeminjamanAset'])->name('surat.preview.peminjaman-aset');
        
        // Routes untuk pengaturan akun tamu
        Route::get('/settings', [GuestController::class, 'settings'])->name('settings');
        Route::put('/update-profile', [GuestController::class, 'updateProfile'])->name('update-profile');
        Route::delete('/delete-signature', [GuestController::class, 'deleteSignature'])->name('delete-signature');
        
        // Routes untuk notifikasi tamu
        Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');
        Route::get('/notifikasi/{id}', [NotifikasiController::class, 'show'])->name('notifikasi.show');
        Route::post('/notifikasi/{id}/mark-read', [NotifikasiController::class, 'markAsRead'])->name('notifikasi.mark-read');
        Route::post('/notifikasi/mark-all-read', [NotifikasiController::class, 'markAllAsRead'])->name('notifikasi.mark-all-read');
        Route::delete('/notifikasi/{id}', [NotifikasiController::class, 'destroy'])->name('notifikasi.destroy');
        Route::get('/notifikasi/{id}/download-surat', [NotifikasiController::class, 'downloadSuratPersetujuan'])->name('notifikasi.download-surat');
        Route::get('/api/notifikasi/unread', [NotifikasiController::class, 'getUnreadNotifications'])->name('notifikasi.unread');
    });

    // Routes untuk Kepala BPMP
    Route::middleware(['role:kepala_bpmp'])->prefix('kepala-bpmp')->name('kepala_bpmp.')->group(function () {
        Route::get('/dashboard', [KepalaBpmpController::class, 'dashboard'])->name('dashboard');
        Route::get('/laporan-aset-tetap', [KepalaBpmpController::class, 'laporanAsetTetap'])->name('laporan-aset-tetap');
        Route::get('/laporan-persediaan', [KepalaBpmpController::class, 'laporanPersediaan'])->name('laporan-persediaan');
        Route::get('/laporan-transaksi-masuk', [KepalaBpmpController::class, 'laporanTransaksiMasuk'])->name('laporan-transaksi-masuk');
        Route::get('/laporan-transaksi-keluar', [KepalaBpmpController::class, 'laporanTransaksiKeluar'])->name('laporan-transaksi-keluar');
        Route::get('/laporan-mutasi', [KepalaBpmpController::class, 'laporanMutasi'])->name('laporan-mutasi');
        Route::get('/laporan-rekapitulasi', [KepalaBpmpController::class, 'laporanRekapitulasi'])->name('laporan-rekapitulasi');

        Route::get('/peminjaman-barang', [KepalaBpmpController::class, 'peminjamanBarang'])->name('peminjaman-barang');
        Route::get('/peminjaman-barang/{id}/detail', [KepalaBpmpController::class, 'getDetailPeminjamanBarang'])->name('peminjaman-barang.detail');
        Route::get('/permintaan-persediaan', [KepalaBpmpController::class, 'permintaanPersediaan'])->name('permintaan-persediaan');
        Route::get('/permintaan-persediaan/{id}/detail', [KepalaBpmpController::class, 'getDetailPermintaanPersediaan'])->name('permintaan-persediaan.detail');
        Route::get('/peminjaman-kendaraan', [KepalaBpmpController::class, 'peminjamanKendaraan'])->name('peminjaman-kendaraan');
        Route::get('/peminjaman-kendaraan/{id}/detail', [KepalaBpmpController::class, 'getDetailPeminjamanKendaraan'])->name('peminjaman-kendaraan.detail');
        Route::get('/peminjaman-aset', [KepalaBpmpController::class, 'peminjamanAset'])->name('peminjaman-aset');
        Route::get('/peminjaman-aset/{id}/detail', [KepalaBpmpController::class, 'getDetailPeminjamanAset'])->name('peminjaman-aset.detail');
    });

    // Group untuk Super Admin
    Route::middleware(['role:super_admin'])->prefix('super-admin')->name('super_admin.')->group(function () {
        Route::get('/index', [SuperAdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/users', [SuperAdminController::class, 'users'])->name('users');
        Route::get('/super_admin/pending-admins', [SuperAdminController::class, 'showPendingAdminRequests'])->name('super_admin.pending.admins');
        Route::post('/super_admin/users/{user}/approve-admin', [SuperAdminController::class, 'approveAdmin'])->name('super_admin.users.approve-admin');
        Route::post('/super_admin/users/{user}/approve-user', [SuperAdminController::class, 'approveUser'])->name('super_admin.users.approve-user');
        // Rute untuk menambahkan user
        Route::get('/super_admin/users/create', [SuperAdminController::class, 'createUser'])->name('users.create');
        Route::post('/super_admin/users/add', [SuperAdminController::class, 'addUser'])->name('users.add');
        // Rute untuk menampilkan data user yang akan diedit (AJAX)
        Route::get('/super_admin/users/{user}/edit', [SuperAdminController::class, 'editUserForm']);
        // Rute untuk memperbarui user
        Route::post('/super_admin/users/{user}/update', [SuperAdminController::class, 'updateUser'])->name('super_admin.users.update');
        // Rute untuk mengubah status user
        Route::post('/super_admin/users/{user}/toggle-status', [SuperAdminController::class, 'toggleStatus'])->name('super_admin.users.toggleStatus');
        // Rute untuk menghapus user
        Route::delete('/super_admin/users/{user}/delete', [SuperAdminController::class, 'deleteUser'])->name('super_admin.users.delete');
    });


});