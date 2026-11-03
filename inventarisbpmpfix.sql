-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 03 Nov 2026 pada 11.10
-- Versi server: 8.0.30
-- Versi PHP: 8.4.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Basis data: `inventarisbpmpfix`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `aset_tetap`
--

CREATE TABLE `aset_tetap` (
  `id` bigint UNSIGNED NOT NULL,
  `kode_kategori` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kategori` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kode_barang` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nup` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_barang` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `merek` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_peroleh` date NOT NULL,
  `nilai_perolehan` decimal(15,2) NOT NULL,
  `kondisi` enum('baik','rusak_ringan','rusak_berat') COLLATE utf8mb4_unicode_ci NOT NULL,
  `lokasi` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah` int NOT NULL DEFAULT '1',
  `stok` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `aset_tetap`
--

INSERT INTO `aset_tetap` (`id`, `kode_kategori`, `kategori`, `kode_barang`, `nup`, `nama_barang`, `merek`, `tanggal_peroleh`, `nilai_perolehan`, `kondisi`, `lokasi`, `jumlah`, `stok`, `created_at`, `updated_at`) VALUES
(2, '3020102', 'Kendaraan bermotor penumpang', '3020102003', '1', 'Mini Bus (Penumpang 14 orang kebawah)', 'Suzuki APV (Putih) (DM 1783 ED)', '2019-05-19', 225300000.00, 'baik', 'BPMP Gorontalo', 1, 1, '2025-12-03 19:00:46', '2025-12-03 19:40:26'),
(3, '3020102', 'Kendaraan Bermotor Penumpang', '3020102003', '2', 'Mini Bus (Penumpang 14 orang kebawah)', 'Suzuki APV (Hitam) (DM 1073 P)', '2013-11-21', 197500000.00, 'baik', 'BPMP Gorontalo', 1, 1, '2025-12-03 19:08:47', '2025-12-03 19:19:41'),
(4, '3020101', 'Kendaraan Dinas Bermotor Perorangan', '3020101003', '1', 'Station Wagon', 'Toyota Fortuner (DM 73)', '2020-09-29', 547000000.00, 'baik', 'BPMP Gorontalo', 1, 1, '2025-12-03 19:14:30', '2025-12-03 19:18:51'),
(5, '3020104', 'Kendaraan Bermotor Beroda Dua', '3020104001', '1', 'Sepeda Motor', 'Yamaha Mio (DM 6945 ER)', '2012-05-03', 15400000.00, 'baik', 'BPMP Gorontalo', 1, 1, '2025-12-03 19:18:13', '2025-12-03 19:18:13'),
(6, '3020104', 'Kendaraan bermotor beroda dua', '3020104001', '2', 'Sepeda Motor', 'Yamaha Mio (DM 6944 ER)', '2012-05-03', 15400000.00, 'baik', 'BPMP Gorontalo', 1, 1, '2025-12-03 19:21:43', '2025-12-03 19:22:05'),
(7, '3020104', 'Kendaraan bermotor beroda dua', '3020104001', '3', 'Sepeda Motor', 'Yamaha Fino (DM 3070 P)', '2018-08-10', 20110000.00, 'baik', 'BPMP Gorontalo', 1, 1, '2025-12-03 19:33:07', '2025-12-03 19:33:07'),
(8, '3020104', 'Kendaraan bermotor beroda dua', '3020104001', '4', 'Sepeda Motor', 'Yamaha Fino (DM 3080 P)', '2018-08-10', 20110000.00, 'baik', 'Perpustakaan BPMP', 1, 1, '2025-12-03 19:34:52', '2025-12-07 17:06:07'),
(9, '3020104', 'Kendaraan bermotor beroda dua', '3020104001', '5', 'Sepeda Motor', 'Yamaha Fino (DM 3081 P)', '2018-08-10', 20110000.00, 'baik', 'BPMP Gorontalo', 1, 1, '2025-12-03 19:36:24', '2025-12-03 19:37:44');

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `Menunggu_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000001_create_cache_table', 1),
(2, '0001_01_01_000002_create_jobs_table', 1),
(3, '2025_09_14_075011_create_users_table', 1),
(4, '2025_09_14_080000_create_request_models_table', 1),
(5, '2025_09_24_001555_create_peminjaman_kendaraans_table', 1),
(6, '2025_09_24_001616_create_permintaan_persediaans_table', 1),
(7, '2025_09_30_010847_add_nomor_urut_to_users_table', 1),
(8, '2025_09_30_064752_add_nama_barang_to_request_models_table', 1),
(9, '2025_09_30_065506_drop_foreign_key_from_request_models_table', 1),
(10, '2025_09_30_071614_create_peminjaman_asets_table', 1),
(11, '2025_09_30_072531_add_tamu_role_to_users_table', 1),
(12, '2025_09_30_110805_add_tanggal_selesai_to_peminjaman_asets_table', 1),
(13, '2025_10_02_141521_add_deskripsi_peruntukan_to_request_models_table', 1),
(14, '2025_10_02_160505_add_jabatan_to_users_table', 1),
(15, '2025_10_03_233007_update_status_enum_all_tables', 1),
(16, '2025_10_04_000255_add_kasubag_role_to_users_table', 1),
(17, '2025_10_10_023421_create_persediaan_table', 1),
(18, '2025_10_10_023434_create_aset_tetap_table', 1),
(19, '2025_10_11_011309_create_transaksi_masuk_persediaan_table', 1),
(20, '2025_10_11_013244_create_transaksi_masuk_aset_tetap_table', 1),
(21, '2025_10_11_013614_add_stok_to_persediaan_and_aset_tetap_tables', 1),
(22, '2025_10_11_020836_create_transaksi_keluar_persediaan_table', 1),
(23, '2025_10_11_020848_create_transaksi_keluar_aset_tetap_table', 1),
(24, '2025_10_11_022712_create_mutasi_barang_table', 1),
(25, '2025_10_11_024935_add_kategori_to_aset_tetap_table', 1),
(26, '2025_10_11_071741_add_kode_kategori_to_transaksi_masuk_tables', 1),
(27, '2025_10_16_235957_create_surat_persetujuan_table', 1),
(28, '2025_10_17_031029_create_notifikasis_table', 1),
(29, '2025_10_20_005915_add_jumlah_to_aset_tetap_table', 1),
(30, '2025_10_20_005918_add_jumlah_to_persediaan_table', 1),
(31, '2025_10_20_014321_fix_status_enum_in_request_models_table', 1),
(32, '2025_10_20_014457_fix_status_enum_in_supply_and_vehicle_tables', 1),
(33, '2025_10_20_014711_force_fix_status_enum_all_tables', 1),
(34, '2025_10_20_014817_fix_kategori_enum_in_peminjaman_kendaraans_table', 1),
(35, '2025_10_20_024418_update_existing_ruangan_to_deskripsi_peruntukan', 1),
(36, '2025_10_20_051209_add_tanggal_peminjaman_pengembalian_to_request_models_table', 1),
(37, '2025_10_21_000000_fix_status_enum_peminjaman_kendaraans', 1),
(38, '2025_10_21_002149_create_sessions_table', 1),
(39, '2025_10_21_105234_fix_superadmin_role_request_data', 1),
(40, '2025_10_21_111122_fix_kepala_bpmp_role_request_data', 1),
(41, '2025_10_21_234125_add_user_id_to_mutasi_barang_table', 1),
(42, '2025_10_21_fix_peminjaman_kendaraans_schema', 1),
(43, '2025_10_28_020400_add_nup_column_to_mutasi_barang_table', 1),
(44, '2025_10_28_020548_add_lokasi_columns_to_mutasi_barang_table', 1),
(45, '2025_10_28_020752_add_all_missing_columns_to_mutasi_barang_table', 1),
(46, '2025_10_28_102344_add_surat_path_to_peminjaman_asets_table', 1),
(47, '2025_10_28_105804_add_surat_path_to_peminjaman_asets_mysql', 1),
(48, '2025_10_29_add_user_id_to_transaksi_masuk_persediaan', 1),
(49, '2025_10_29_fix_persediaan_jumlah_stok_relation', 1),
(50, '2025_10_30_000001_migrate_to_mysql_all_tables', 1),
(51, '2025_11_03_023859_remove_invalid_foreign_key_from_surat_persetujuan', 1),
(52, '2025_11_04_044152_fix_aset_tetap_jumlah_stok_relation', 1),
(53, '2025_11_08_020136_add_kepala_bpmp_role_to_users_table', 1),
(54, '2025_11_11_000001_update_users_add_nomor_urut_and_tamu', 1),
(55, '2025_11_11_005205_add_user_id_to_transaksi_keluar_persediaan', 1),
(56, '2025_11_11_005206_add_user_id_to_transaksi_keluar_aset_tetap', 1),
(57, '2025_11_11_005207_add_user_id_to_transaksi_masuk_aset_tetap', 1),
(58, '2025_11_11_005208_add_nilai_perolehan_to_transaksi_keluar_aset_tetap', 1),
(59, '2025_11_11_005209_add_missing_columns_to_mutasi_barang_table', 1),
(60, '2025_12_16_000000_add_jenis_to_persediaan_table', 2),
(61, '2025_12_16_010000_add_jenis_to_transaksi_persediaan_tables', 3);

-- --------------------------------------------------------

--
-- Struktur dari tabel `mutasi_barang`
--

CREATE TABLE `mutasi_barang` (
  `id` bigint UNSIGNED NOT NULL,
  `kode_barang` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_barang` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nup` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lokasi_awal` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lokasi_akhir` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_mutasi` date NOT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `aset_tetap_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `dari_ruangan` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ke_ruangan` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jumlah` int NOT NULL DEFAULT '1',
  `alasan` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `mutasi_barang`
--

INSERT INTO `mutasi_barang` (`id`, `kode_barang`, `nama_barang`, `nup`, `lokasi_awal`, `lokasi_akhir`, `tanggal_mutasi`, `keterangan`, `aset_tetap_id`, `created_at`, `updated_at`, `user_id`, `dari_ruangan`, `ke_ruangan`, `jumlah`, `alasan`) VALUES
(2, '3020104001', 'Sepeda Motor', '4', 'BPMP Gorontalo', 'Perpustakaan BPMP', '2025-12-08', 'Kepentingan Kantor', 8, '2025-12-07 17:06:07', '2025-12-07 17:06:07', 24, 'BPMP Gorontalo', 'Perpustakaan BPMP', 1, 'Kepentingan Kantor');

-- --------------------------------------------------------

--
-- Struktur dari tabel `notifikasis`
--

CREATE TABLE `notifikasis` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `jenis_notifikasi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `judul` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pesan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_surat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `request_id` bigint UNSIGNED DEFAULT NULL,
  `surat_persetujuan_id` bigint UNSIGNED DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `notifikasis`
--

INSERT INTO `notifikasis` (`id`, `user_id`, `jenis_notifikasi`, `judul`, `pesan`, `jenis_surat`, `request_id`, `surat_persetujuan_id`, `is_read`, `read_at`, `created_at`, `updated_at`) VALUES
(1, 19, 'surat_persetujuan', 'Surat Persetujuan Permintaan Persediaan Telah Tersedia', 'Surat persetujuan untuk permintaan Permintaan Persediaan Anda telah diupload oleh Admin. Nomor surat: SPP/001/2025. Silakan login ke sistem untuk mengunduh surat persetujuan tersebut.', 'SPP', 1, 1, 0, NULL, '2025-12-03 02:00:25', '2025-12-03 02:00:25'),
(2, 19, 'surat_persetujuan', 'Surat Persetujuan Peminjaman Barang Telah Tersedia', 'Surat persetujuan untuk permintaan Peminjaman Barang Anda telah diupload oleh Admin. Nomor surat: SPB/001/2025. Silakan login ke sistem untuk mengunduh surat persetujuan tersebut.', 'SPB', 2, 2, 0, NULL, '2025-12-03 02:01:05', '2025-12-03 02:01:05'),
(3, 19, 'surat_persetujuan', 'Surat Persetujuan Peminjaman Kendaraan Telah Tersedia', 'Surat persetujuan untuk permintaan Peminjaman Kendaraan Anda telah diupload oleh Admin. Nomor surat: SPK/001/2025. Silakan login ke sistem untuk mengunduh surat persetujuan tersebut.', 'SPK', 1, 3, 0, NULL, '2025-12-03 02:03:23', '2025-12-03 02:03:23'),
(4, 18, 'surat_persetujuan', 'Surat Persetujuan Peminjaman Aset Telah Tersedia', 'Surat persetujuan untuk permintaan Peminjaman Aset Anda telah diupload oleh Admin. Nomor surat: SPA/001/2025. Silakan login ke sistem untuk mengunduh surat persetujuan tersebut.', 'SPA', 1, 4, 0, NULL, '2025-12-03 18:35:28', '2025-12-03 18:35:28'),
(5, 18, 'surat_persetujuan', 'Surat Persetujuan Peminjaman Aset Telah Tersedia', 'Surat persetujuan untuk permintaan Peminjaman Aset Anda telah diupload oleh Admin. Nomor surat: SPA/001/2025. Silakan login ke sistem untuk mengunduh surat persetujuan tersebut.', 'SPA', 1, 5, 0, NULL, '2025-12-03 18:35:28', '2025-12-03 18:35:28'),
(6, 30, 'surat_persetujuan', 'Surat Persetujuan Peminjaman Aset Telah Tersedia', 'Surat persetujuan untuk permintaan Peminjaman Aset Anda telah diupload oleh Admin. Nomor surat: SPP/001/2025. Silakan login ke sistem untuk mengunduh surat persetujuan tersebut.', 'SPA', 4, 6, 0, NULL, '2025-12-16 18:10:17', '2025-12-16 18:10:17'),
(7, 30, 'surat_persetujuan', 'Surat Persetujuan Peminjaman Aset Telah Tersedia', 'Surat persetujuan untuk permintaan Peminjaman Aset Anda telah diupload oleh Admin. Nomor surat: SPP/001/2025. Silakan login ke sistem untuk mengunduh surat persetujuan tersebut.', 'SPA', 4, 7, 0, NULL, '2025-12-16 18:10:17', '2025-12-16 18:10:17');

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `peminjaman_asets`
--

CREATE TABLE `peminjaman_asets` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `nama_instansi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_peminjam` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nip` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kabupaten_kota` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_barang` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `surat_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_pinjaman` date NOT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `status` enum('Menunggu','direview','diteruskan_ke_kasubag','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL,
  `komentar` text COLLATE utf8mb4_unicode_ci,
  `tanggal_approval` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `reviewed_by_admin_id` bigint UNSIGNED DEFAULT NULL,
  `diteruskan_ke_kasubag_date` timestamp NULL DEFAULT NULL,
  `approved_by_kasubag_id` bigint UNSIGNED DEFAULT NULL,
  `approved_by_kasubag_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `peminjaman_asets`
--

INSERT INTO `peminjaman_asets` (`id`, `user_id`, `nama_instansi`, `nama_peminjam`, `nip`, `kabupaten_kota`, `nama_barang`, `jenis`, `surat_path`, `tanggal_pinjaman`, `tanggal_selesai`, `jam_mulai`, `jam_selesai`, `status`, `komentar`, `tanggal_approval`, `created_at`, `updated_at`, `reviewed_by_admin_id`, `diteruskan_ke_kasubag_date`, `approved_by_kasubag_id`, `approved_by_kasubag_date`) VALUES
(1, 18, 'bpmp', 'lintang', '1234556789', 'Velit recusandae Am', 'Gedung/Ruang', 'Mess Bandayo Kiki 3,4', 'peminjaman_aset/surat/1764236675_proposal_1764163503_9.docx', '2025-11-27', '2025-11-30', '05:05:00', '23:11:00', 'approved', 'tidak serius', NULL, '2025-11-27 01:44:35', '2025-11-27 01:47:23', NULL, '2025-11-27 01:46:18', 3, '2025-11-27 01:47:23'),
(2, 30, 'UNG', 'Alifia Zalda Mamonto', '531422042', 'Gorontalo', 'Gedung/Ruang', 'Mess Bandayo Daa', 'peminjaman_aset/surat/1764753184_Template PKS.docx', '2025-12-03', '2025-12-06', '08:12:00', '23:18:00', 'approved', NULL, NULL, '2025-12-03 01:13:04', '2025-12-03 18:40:08', 24, '2025-12-03 01:15:56', 3, '2025-12-03 18:40:08'),
(3, 30, 'Universitas Negeri Gorontalo', 'Alifia Zalda Mamonto', '631422005', 'Gorontalo', 'Gedung/Ruang', 'Ruang Belajar Tinelo 1,2,3,4,5', 'peminjaman_aset/surat/1765121445_Surat_Peminjaman_Kendaraan_1.pdf', '2025-12-07', '2025-12-08', '08:00:00', '16:30:00', 'Menunggu', NULL, NULL, '2025-12-07 07:30:45', '2025-12-07 07:30:45', NULL, NULL, NULL, NULL),
(4, 30, 'bpmp', 'Alifia Zalda Mamonto', '531422005', 'Gorontalo', 'Gedung/Ruang', 'Ruang Pertemuan Aula Dulohupa', 'peminjaman_aset/surat/1765935412_Template MOU (1).docx', '2025-12-17', '2025-12-19', '09:36:00', '15:42:00', 'Menunggu', NULL, NULL, '2025-12-16 17:36:53', '2025-12-16 17:36:53', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `peminjaman_kendaraans`
--

CREATE TABLE `peminjaman_kendaraans` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `nama_kendaraan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `merek` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah` int NOT NULL,
  `deskripsi_peruntukan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('Menunggu','direview','diteruskan_ke_kasubag','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Menunggu',
  `komentar` text COLLATE utf8mb4_unicode_ci,
  `tanggal_peminjaman` timestamp NULL DEFAULT NULL,
  `tanggal_pengembalian` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `reviewed_by_admin_id` bigint UNSIGNED DEFAULT NULL,
  `diteruskan_ke_kasubag_date` timestamp NULL DEFAULT NULL,
  `approved_by_kasubag_id` bigint UNSIGNED DEFAULT NULL,
  `approved_by_kasubag_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `peminjaman_kendaraans`
--

INSERT INTO `peminjaman_kendaraans` (`id`, `user_id`, `nama_kendaraan`, `kategori`, `merek`, `jumlah`, `deskripsi_peruntukan`, `status`, `komentar`, `tanggal_peminjaman`, `tanggal_pengembalian`, `created_at`, `updated_at`, `reviewed_by_admin_id`, `diteruskan_ke_kasubag_date`, `approved_by_kasubag_id`, `approved_by_kasubag_date`) VALUES
(1, 19, 'Honda Vario', 'Kenderaan', 'Honda', 1, 'dinas luar', 'approved', 'kkjj', '2025-12-02 16:00:00', '2025-12-04 16:00:00', '2025-12-03 01:10:05', '2025-12-03 02:16:17', 24, '2025-12-03 01:54:08', 3, '2025-12-03 02:16:17'),
(2, 19, 'Mini Bus (Penumpang 14 orang kebawah)', 'Kendaraan bermotor penumpang', 'Suzuki APV (Putih) (DM 1783 ED)', 1, 'Perjalanan Dinas ke Pohuwato', 'approved', NULL, '2025-12-03 16:00:00', '2025-12-30 16:00:00', '2025-12-03 19:41:16', '2025-12-07 08:22:18', 24, '2025-12-07 08:19:19', 3, '2025-12-07 08:22:18'),
(3, 19, 'Station Wagon', 'Kendaraan Dinas Bermotor Perorangan', 'Toyota Fortuner (DM 73)', 1, 'Perjalanan dinas luar', 'Menunggu', NULL, '2025-12-11 16:00:00', '2025-12-18 16:00:00', '2025-12-07 08:18:17', '2025-12-07 08:18:17', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `permintaan_persediaans`
--

CREATE TABLE `permintaan_persediaans` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `nama_barang` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `merek` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah` int NOT NULL,
  `deskripsi_peruntukan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('Menunggu','direview','diteruskan_ke_kasubag','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Menunggu',
  `komentar` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `reviewed_by_admin_id` bigint UNSIGNED DEFAULT NULL,
  `diteruskan_ke_kasubag_date` timestamp NULL DEFAULT NULL,
  `approved_by_kasubag_id` bigint UNSIGNED DEFAULT NULL,
  `confirmed_by_kasubag_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `permintaan_persediaans`
--

INSERT INTO `permintaan_persediaans` (`id`, `user_id`, `nama_barang`, `merek`, `jumlah`, `deskripsi_peruntukan`, `status`, `komentar`, `created_at`, `updated_at`, `reviewed_by_admin_id`, `diteruskan_ke_kasubag_date`, `approved_by_kasubag_id`, `confirmed_by_kasubag_date`) VALUES
(1, 19, 'Harum ut quae sunt', 'Tidak Diketahui', 1, 'keperluan kantor', 'approved', 'mm', '2025-12-03 01:09:41', '2025-12-03 02:15:46', 24, '2025-12-03 01:59:04', 3, NULL),
(2, 19, 'HVS A4', 'Tidak Diketahui', 2, 'keperluan administrasi', 'approved', NULL, '2025-12-07 05:29:12', '2025-12-16 17:22:51', 24, '2025-12-16 17:20:13', 3, NULL),
(3, 19, 'HVS A4', 'Tidak Diketahui', 1, 'administrasi', 'direview', NULL, '2025-12-16 17:18:56', '2025-12-16 17:19:31', 24, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `persediaan`
--

CREATE TABLE `persediaan` (
  `id` bigint UNSIGNED NOT NULL,
  `kode_kategori` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_barang` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_barang` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis` enum('habis_pakai','tidak_habis_pakai') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'habis_pakai',
  `harga` decimal(15,2) NOT NULL,
  `stok` int NOT NULL DEFAULT '0',
  `tanggal_masuk` date NOT NULL,
  `jumlah` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `persediaan`
--

INSERT INTO `persediaan` (`id`, `kode_kategori`, `kategori`, `kode_barang`, `nama_barang`, `jenis`, `harga`, `stok`, `tanggal_masuk`, `jumlah`, `created_at`, `updated_at`) VALUES
(1, '1', 'elektronik', '32', 'Harum ut quae sunt', 'tidak_habis_pakai', 5000000.00, 49, '2025-11-27', 49, '2025-11-27 01:53:10', '2026-02-24 18:57:01'),
(2, '1.01.011', 'Alat Tulis Menulis', '1.01.001', 'HVS A4', 'habis_pakai', 50000.00, 8, '2025-12-04', 8, '2025-12-03 18:04:48', '2025-12-03 18:30:03'),
(3, '1.01.03.01', 'Alat Tulis Kantor', '1.01.03.001', 'Alat Tulis', 'habis_pakai', 5000.00, 80, '2025-12-07', 80, '2025-12-07 13:42:21', '2026-02-20 22:33:31'),
(4, '1.01.03.02', 'Kertas dan Cover', '1.01.03.02.004', 'Amplop', 'habis_pakai', 25000.00, 10, '2025-12-07', 10, '2025-12-07 13:43:52', '2025-12-07 13:43:52'),
(5, '011.1.22', 'Elektronik', '011.1.22.001', 'PRINTER EPSON', 'tidak_habis_pakai', 10000000.00, 2, '2025-12-16', 2, '2025-12-16 05:46:29', '2025-12-16 05:46:41');

-- --------------------------------------------------------

--
-- Struktur dari tabel `request_models`
--

CREATE TABLE `request_models` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `nama_barang` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_barang` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `merek` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ruangan` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah` int NOT NULL,
  `deskripsi_peruntukan` text COLLATE utf8mb4_unicode_ci,
  `status` enum('Menunggu','direview','diteruskan_ke_kasubag','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Menunggu',
  `request_date` date NOT NULL,
  `tanggal_peminjaman` timestamp NULL DEFAULT NULL,
  `tanggal_pengembalian` timestamp NULL DEFAULT NULL,
  `approved_by_admin_id` bigint UNSIGNED DEFAULT NULL,
  `reviewed_by_admin_id` bigint UNSIGNED DEFAULT NULL,
  `diteruskan_ke_kasubag_date` timestamp NULL DEFAULT NULL,
  `approved_by_kasubag_id` bigint UNSIGNED DEFAULT NULL,
  `approved_by_kasubag_date` timestamp NULL DEFAULT NULL,
  `komentar` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `request_models`
--

INSERT INTO `request_models` (`id`, `user_id`, `nama_barang`, `kode_barang`, `kategori`, `merek`, `ruangan`, `jumlah`, `deskripsi_peruntukan`, `status`, `request_date`, `tanggal_peminjaman`, `tanggal_pengembalian`, `approved_by_admin_id`, `reviewed_by_admin_id`, `diteruskan_ke_kasubag_date`, `approved_by_kasubag_id`, `approved_by_kasubag_date`, `komentar`, `created_at`, `updated_at`) VALUES
(2, 19, 'Harum ut quae sunt', '32', 'elektronik', 'Tidak Diketahui', 'fgjfhfd', 18, 'fgjfhfd', 'approved', '2025-11-27', '2025-11-26 16:00:00', '2025-11-28 16:00:00', NULL, 24, '2025-12-03 01:51:04', 3, '2025-12-03 02:14:41', 'aaa', '2025-11-27 02:10:48', '2025-12-03 02:14:41'),
(4, 19, 'Harum ut quae sunt', '32', 'elektronik', 'Tidak Diketahui', 'hshsh', 1, 'hshsh', 'approved', '2025-12-04', '2025-12-03 16:00:00', '2025-12-04 16:00:00', NULL, 24, '2025-12-03 21:04:21', 3, '2025-12-03 21:16:00', 'aha', '2025-12-03 21:03:46', '2025-12-03 21:16:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('5baGe2gGw2ZOORkGMUPDT8fkWIpNIqOkwqj4IMWm', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidnlGZVVYOUhTNUszcDdCajlJM0pUdHdFRG81RFJEdDgxS1RnenE2ayI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1771940924),
('DjwOZKGFN5T93LlFndUKO8QWh2pW439wy0v3KRlt', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVDlncm9YVjluSWo1ZU8xQndHdGg3YllBZ0JBUG5Sdzlyc1IyYjBMVCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1771941113),
('fkiQ2ActhExbKDstQ1CtJDfkZRYvMLlXILm35tcu', NULL, '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Mobile Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoia3ZoR2pNQlRFNFdTZTZMZW51Y0R0dlNEaktSTWt1d0VhTk9DMmk1eSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1771941199),
('RVOGW1vlovYVpGC3RrxCNQcmvwJ0EvhmdYoYEHAM', 24, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiOWFQc016cnU3Q29NTWk4VDZUb3IxWHVKdjZyZXEzdDhsS282V01CWiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sYXBvcmFuIjtzOjU6InJvdXRlIjtzOjEzOiJhZG1pbi5sYXBvcmFuIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MjQ7fQ==', 1771655622),
('z4BTT0ro0JGb71PbSoWy9YynYAH3zfMiDk6iEHsB', 24, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiOUN3bXJjbUVWbklobEd1U0hmSkdJNHFrRk1XTWxvaFpsM3NkUkYyRSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sYXBvcmFuIjtzOjU6InJvdXRlIjtzOjEzOiJhZG1pbi5sYXBvcmFuIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MjQ7fQ==', 1771988227),
('z7sYXsA03u2si4LHWVQbdCyXoadY0jhV437y2iEk', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUlNQcWJWMmJ0b1c1WDlUSjZFZlhIemhkeUJhZ1BFbVozelpvMFVHOCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1771941109);

-- --------------------------------------------------------

--
-- Struktur dari tabel `surat_persetujuan`
--

CREATE TABLE `surat_persetujuan` (
  `id` bigint UNSIGNED NOT NULL,
  `jenis_surat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `request_id` bigint UNSIGNED NOT NULL,
  `nomor_surat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_size` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `status` enum('Menunggu','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Menunggu',
  `uploaded_by` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `surat_persetujuan`
--

INSERT INTO `surat_persetujuan` (`id`, `jenis_surat`, `request_id`, `nomor_surat`, `file_path`, `file_name`, `file_size`, `keterangan`, `status`, `uploaded_by`, `created_at`, `updated_at`) VALUES
(1, 'SPP', 1, 'SPP/001/2025', 'surat-persetujuan/1764756025_surat-permintaan-persediaan-amelia-eka-safitri.pdf', 'Surat Permintaan Persediaan - amelia eka safitri.pdf', '216557', NULL, 'approved', 24, '2025-12-03 02:00:25', '2025-12-03 02:00:25'),
(2, 'SPB', 2, 'SPB/001/2025', 'surat-persetujuan/1764756065_surat-permintaan-persediaan-amelia-eka-safitri.pdf', 'Surat Permintaan Persediaan - amelia eka safitri.pdf', '216557', NULL, 'approved', 24, '2025-12-03 02:01:05', '2025-12-03 02:01:05'),
(3, 'SPK', 1, 'SPK/001/2025', 'surat-persetujuan/1764756202_surat-peminjaman-kendaraan-1.pdf', 'Surat_Peminjaman_Kendaraan_1.pdf', '53105', 'SUDAH DISETUJUI', 'approved', 24, '2025-12-03 02:03:22', '2025-12-03 02:03:22'),
(4, 'SPA', 1, 'SPA/001/2025', 'surat-persetujuan/1764815728_surat-peminjaman-kendaraan-1.pdf', 'Surat_Peminjaman_Kendaraan_1.pdf', '53105', 'PERMINTAAN PEMINJAMAN DI TERIMA', 'approved', 33, '2025-12-03 18:35:28', '2025-12-03 18:35:28'),
(5, 'SPA', 1, 'SPA/001/2025', 'surat-persetujuan/1764815728_surat-peminjaman-kendaraan-1.pdf', 'Surat_Peminjaman_Kendaraan_1.pdf', '53105', 'PERMINTAAN PEMINJAMAN DI TERIMA', 'approved', 33, '2025-12-03 18:35:28', '2025-12-03 18:35:28'),
(6, 'SPA', 4, 'SPP/001/2025', 'surat-persetujuan/1765937417_surat-peminjaman-kendaraan-1.pdf', 'Surat_Peminjaman_Kendaraan_1.pdf', '53105', NULL, 'approved', 24, '2025-12-16 18:10:17', '2025-12-16 18:10:17'),
(7, 'SPA', 4, 'SPP/001/2025', 'surat-persetujuan/1765937417_surat-peminjaman-kendaraan-1.pdf', 'Surat_Peminjaman_Kendaraan_1.pdf', '53105', NULL, 'approved', 24, '2025-12-16 18:10:17', '2025-12-16 18:10:17');

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi_keluar_aset_tetap`
--

CREATE TABLE `transaksi_keluar_aset_tetap` (
  `id` bigint UNSIGNED NOT NULL,
  `tanggal_input` date NOT NULL,
  `kode_kategori` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_barang` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_barang` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah_keluar` int NOT NULL,
  `nilai_perolehan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `aset_tetap_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi_keluar_persediaan`
--

CREATE TABLE `transaksi_keluar_persediaan` (
  `id` bigint UNSIGNED NOT NULL,
  `tanggal_input` date NOT NULL,
  `kode_kategori` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_barang` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_barang` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis` enum('habis_pakai','tidak_habis_pakai') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'habis_pakai',
  `jumlah_keluar` int NOT NULL,
  `harga` decimal(15,2) NOT NULL,
  `persediaan_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `transaksi_keluar_persediaan`
--

INSERT INTO `transaksi_keluar_persediaan` (`id`, `tanggal_input`, `kode_kategori`, `kategori`, `kode_barang`, `nama_barang`, `jenis`, `jumlah_keluar`, `harga`, `persediaan_id`, `created_at`, `updated_at`, `user_id`) VALUES
(1, '2025-11-27', '1', 'elektronik', '32', 'Harum ut quae sunt', 'habis_pakai', 12, 5000000.00, 1, '2025-11-27 02:21:03', '2025-11-27 02:21:03', NULL),
(2, '2025-12-04', '1.01.011', 'Alat Tulis Menulis', '1.01.001', 'HVS A4', 'habis_pakai', 4, 50000.00, 2, '2025-12-03 18:30:03', '2025-12-03 18:30:03', 33);

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi_masuk_aset_tetap`
--

CREATE TABLE `transaksi_masuk_aset_tetap` (
  `id` bigint UNSIGNED NOT NULL,
  `tanggal_input` date NOT NULL,
  `kode_kategori` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kategori` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_barang` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_barang` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah_masuk` int NOT NULL,
  `harga` decimal(15,2) NOT NULL,
  `aset_tetap_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi_masuk_persediaan`
--

CREATE TABLE `transaksi_masuk_persediaan` (
  `id` bigint UNSIGNED NOT NULL,
  `tanggal_input` date NOT NULL,
  `kode_kategori` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kategori` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_barang` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_barang` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis` enum('habis_pakai','tidak_habis_pakai') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'habis_pakai',
  `jumlah_masuk` int NOT NULL,
  `harga` decimal(15,2) NOT NULL,
  `persediaan_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `transaksi_masuk_persediaan`
--

INSERT INTO `transaksi_masuk_persediaan` (`id`, `tanggal_input`, `kode_kategori`, `kategori`, `kode_barang`, `nama_barang`, `jenis`, `jumlah_masuk`, `harga`, `persediaan_id`, `created_at`, `updated_at`, `user_id`) VALUES
(1, '2025-11-27', '1', 'elektronik', '32', 'Harum ut quae sunt', 'habis_pakai', 2, 5000000.00, 1, '2025-11-27 02:20:01', '2025-11-27 02:20:01', NULL),
(2, '2025-12-04', '1.01.011', 'Alat Tulis Menulis', '1.01.001', 'HVS A4', 'habis_pakai', 2, 50000.00, 2, '2025-12-03 18:28:27', '2025-12-03 18:28:27', 33),
(3, '2026-02-21', '1.01.03.01', 'Alat Tulis Kantor', '1.01.03.001', 'Alat Tulis', 'habis_pakai', 70, 5000.00, 3, '2026-02-20 22:33:31', '2026-02-20 22:33:31', 24),
(4, '2026-02-25', '1', 'elektronik', '32', 'Harum ut quae sunt', 'tidak_habis_pakai', 9, 5000000.00, 1, '2026-02-24 18:57:01', '2026-02-24 18:57:01', 24);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `nomor_urut` int DEFAULT NULL,
  `nama_lengkap` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `instansi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nip` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('super_admin','admin','user','tamu','kasubag','kepala_bpmp') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `is_suspend` tinyint(1) NOT NULL DEFAULT '0',
  `role_request` enum('admin','user','tamu','kasubag','kepala_bpmp') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `is_approved_by_admin` tinyint(1) NOT NULL DEFAULT '0',
  `signature_image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `nomor_urut`, `nama_lengkap`, `instansi`, `nip`, `username`, `email`, `email_verified_at`, `password`, `role`, `status`, `is_suspend`, `role_request`, `is_approved_by_admin`, `signature_image_path`, `remember_token`, `created_at`, `updated_at`) VALUES
(2, 1, 'superadmin', 'BPMP Provinsi Gorontalo', '1234567890', 'superadmin', 'superadmin@bpmp.go.id', NULL, '$2y$12$eXazspZKXfp5GlTrL7qDeuXNEN93L3hjsi20rmgoIN5JbgBt8TSAy', 'super_admin', 'active', 0, 'admin', 1, NULL, NULL, '2025-11-27 01:13:01', '2025-12-02 23:06:03'),
(3, 2, 'Ardin S.E, M.E', 'BPMP Provinsi Gorontalo', '197810242002121001', 'kasubag1', 'kasubag@bpmp.go.id', NULL, '$2y$12$ItfCZI3nHPgxtWn5N.jbbeDsS.hljI6H9D4OcnpuoQzNNAtrPpcEC', 'kasubag', 'active', 0, 'kasubag', 1, NULL, NULL, '2025-11-27 01:13:01', '2025-12-07 05:25:21'),
(18, 3, 'Rudi syaifullah', 'bpmp', '1235677', 'kepala_bpmp', 'kepala@gmail.com', NULL, '$2y$12$vR/T8icJJYOBAJd7lHIAaOQraQdoSVwd4reYLxVPd.YOfNQUDhvhe', 'kepala_bpmp', 'active', 0, 'kepala_bpmp', 1, NULL, NULL, '2025-11-27 01:40:55', '2025-12-03 18:21:57'),
(19, 4, 'amelia eka safitri', 'Veritatis culpa nihi', 'Minus dolorem hic co', 'amel', 'nibusyw@mailinator.com', NULL, '$2y$12$AeS.BvhgWDH2d6TbLvPRVehdQvlSNJqGv9uJ8xNMfZhuY6te2msmq', 'user', 'active', 0, 'user', 1, 'signatures/signature_19_1764238701.png', NULL, '2025-11-27 01:50:31', '2025-12-03 18:21:57'),
(24, 5, 'Admin', 'BPMP Provinsi Gorontalo', '09876543251', 'admin', 'bpmp@bpmp.go.id', NULL, '$2y$12$Q/Dt4EzWGRKqgRWSMzjgAujY/5BnFVFOUZmUOVB1t01Ji.ve189MK', 'admin', 'active', 0, 'admin', 1, NULL, NULL, '2025-11-30 17:20:22', '2025-12-03 18:21:57'),
(26, 6, 'satria', 'instan1', '124366780192', 'althair', 'al@gmail.com', NULL, '$2y$12$MgC4sZJWO3E4gk4GMr30BONvpDzz/6Wok5eHVWimOohOBJdR.X8MK', 'tamu', 'active', 0, 'tamu', 1, NULL, NULL, '2025-11-30 17:22:46', '2025-12-03 18:21:57'),
(30, 7, 'alifia zalda', 'UNG', '531422042', 'alifia', 'alifia@gmail.com', NULL, '$2y$12$EikIwJwTSXYVwHWsrQFI6uhx0GYtlfrMt8vSSQ8y2gbyuhVvDyOye', 'tamu', 'active', 0, 'tamu', 1, NULL, NULL, '2025-12-03 01:11:49', '2025-12-03 18:21:57'),
(31, 8, 'lintang cahyani', 'UNG', '5314222005', 'lintang', 'lintangcahyani131@gmail.com', NULL, '$2y$12$zP1sONgAWnsnOLPIFMw5r.B0KIhaDdvJ/xdKIOz1FgX9wJyyg25Ma', 'user', 'active', 0, 'user', 1, NULL, NULL, '2025-12-03 18:21:20', '2025-12-03 18:21:57'),
(32, 9, 'imelda', 'BPMP', '09876543', 'imelda', 'imelda10@gmail.com', NULL, '$2y$12$Liz8iA4YsFGcLdY6wvy7ae1s23zH4lzhdaKvsK2DBm8XyasrTNWvC', 'tamu', 'active', 0, 'tamu', 1, NULL, NULL, '2025-12-03 18:23:30', '2025-12-03 18:23:30'),
(33, 10, 'udin', 'BPMP', '5678901', 'udin', 'udin@gmail.com', NULL, '$2y$12$xG15FRvpUz/dRDt2MkoGwO3QehmzeN/0nqkiLN5sGsrW0wbJqdMDK', 'admin', 'active', 0, 'admin', 1, NULL, NULL, '2025-12-03 18:25:03', '2025-12-03 18:25:48'),
(34, 11, 'putri', 'UNG', '455689892', 'putri', 'putri@gmail.com', NULL, '$2y$12$Gg0zrTYzBmLg9IknlNx4Hue5q8w11bgOegimDKNOhQahtf/IGaOGa', 'tamu', 'active', 0, 'tamu', 1, NULL, NULL, '2025-12-16 17:49:33', '2025-12-16 17:49:33');

--
-- Indeks untuk tabel yang dibuang
--

--
-- Indeks untuk tabel `aset_tetap`
--
ALTER TABLE `aset_tetap`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `mutasi_barang`
--
ALTER TABLE `mutasi_barang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mutasi_barang_aset_tetap_id_foreign` (`aset_tetap_id`),
  ADD KEY `mutasi_barang_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `notifikasis`
--
ALTER TABLE `notifikasis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifikasis_surat_persetujuan_id_foreign` (`surat_persetujuan_id`),
  ADD KEY `notifikasis_user_id_is_read_index` (`user_id`,`is_read`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `peminjaman_asets`
--
ALTER TABLE `peminjaman_asets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `peminjaman_asets_user_id_foreign` (`user_id`),
  ADD KEY `peminjaman_asets_reviewed_by_admin_id_foreign` (`reviewed_by_admin_id`),
  ADD KEY `peminjaman_asets_approved_by_kasubag_id_foreign` (`approved_by_kasubag_id`);

--
-- Indeks untuk tabel `peminjaman_kendaraans`
--
ALTER TABLE `peminjaman_kendaraans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `peminjaman_kendaraans_user_id_foreign` (`user_id`),
  ADD KEY `peminjaman_kendaraans_reviewed_by_admin_id_foreign` (`reviewed_by_admin_id`),
  ADD KEY `peminjaman_kendaraans_approved_by_kasubag_id_foreign` (`approved_by_kasubag_id`);

--
-- Indeks untuk tabel `permintaan_persediaans`
--
ALTER TABLE `permintaan_persediaans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `permintaan_persediaans_user_id_foreign` (`user_id`),
  ADD KEY `permintaan_persediaans_reviewed_by_admin_id_foreign` (`reviewed_by_admin_id`),
  ADD KEY `permintaan_persediaans_approved_by_kasubag_id_foreign` (`approved_by_kasubag_id`);

--
-- Indeks untuk tabel `persediaan`
--
ALTER TABLE `persediaan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `request_models`
--
ALTER TABLE `request_models`
  ADD PRIMARY KEY (`id`),
  ADD KEY `request_models_user_id_foreign` (`user_id`),
  ADD KEY `request_models_approved_by_admin_id_foreign` (`approved_by_admin_id`),
  ADD KEY `request_models_reviewed_by_admin_id_foreign` (`reviewed_by_admin_id`),
  ADD KEY `request_models_approved_by_kasubag_id_foreign` (`approved_by_kasubag_id`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `surat_persetujuan`
--
ALTER TABLE `surat_persetujuan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `surat_persetujuan_uploaded_by_foreign` (`uploaded_by`);

--
-- Indeks untuk tabel `transaksi_keluar_aset_tetap`
--
ALTER TABLE `transaksi_keluar_aset_tetap`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaksi_keluar_aset_tetap_aset_tetap_id_foreign` (`aset_tetap_id`),
  ADD KEY `transaksi_keluar_aset_tetap_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `transaksi_keluar_persediaan`
--
ALTER TABLE `transaksi_keluar_persediaan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaksi_keluar_persediaan_persediaan_id_foreign` (`persediaan_id`),
  ADD KEY `transaksi_keluar_persediaan_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `transaksi_masuk_aset_tetap`
--
ALTER TABLE `transaksi_masuk_aset_tetap`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaksi_masuk_aset_tetap_aset_tetap_id_foreign` (`aset_tetap_id`),
  ADD KEY `transaksi_masuk_aset_tetap_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `transaksi_masuk_persediaan`
--
ALTER TABLE `transaksi_masuk_persediaan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaksi_masuk_persediaan_persediaan_id_foreign` (`persediaan_id`),
  ADD KEY `transaksi_masuk_persediaan_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_nip_unique` (`nip`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `aset_tetap`
--
ALTER TABLE `aset_tetap`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT untuk tabel `mutasi_barang`
--
ALTER TABLE `mutasi_barang`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `notifikasis`
--
ALTER TABLE `notifikasis`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `peminjaman_asets`
--
ALTER TABLE `peminjaman_asets`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `peminjaman_kendaraans`
--
ALTER TABLE `peminjaman_kendaraans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `permintaan_persediaans`
--
ALTER TABLE `permintaan_persediaans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `persediaan`
--
ALTER TABLE `persediaan`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `request_models`
--
ALTER TABLE `request_models`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `surat_persetujuan`
--
ALTER TABLE `surat_persetujuan`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `transaksi_keluar_aset_tetap`
--
ALTER TABLE `transaksi_keluar_aset_tetap`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `transaksi_keluar_persediaan`
--
ALTER TABLE `transaksi_keluar_persediaan`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `transaksi_masuk_aset_tetap`
--
ALTER TABLE `transaksi_masuk_aset_tetap`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `transaksi_masuk_persediaan`
--
ALTER TABLE `transaksi_masuk_persediaan`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `mutasi_barang`
--
ALTER TABLE `mutasi_barang`
  ADD CONSTRAINT `mutasi_barang_aset_tetap_id_foreign` FOREIGN KEY (`aset_tetap_id`) REFERENCES `aset_tetap` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `mutasi_barang_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `notifikasis`
--
ALTER TABLE `notifikasis`
  ADD CONSTRAINT `notifikasis_surat_persetujuan_id_foreign` FOREIGN KEY (`surat_persetujuan_id`) REFERENCES `surat_persetujuan` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notifikasis_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `peminjaman_asets`
--
ALTER TABLE `peminjaman_asets`
  ADD CONSTRAINT `peminjaman_asets_approved_by_kasubag_id_foreign` FOREIGN KEY (`approved_by_kasubag_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `peminjaman_asets_reviewed_by_admin_id_foreign` FOREIGN KEY (`reviewed_by_admin_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `peminjaman_asets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `peminjaman_kendaraans`
--
ALTER TABLE `peminjaman_kendaraans`
  ADD CONSTRAINT `peminjaman_kendaraans_approved_by_kasubag_id_foreign` FOREIGN KEY (`approved_by_kasubag_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `peminjaman_kendaraans_reviewed_by_admin_id_foreign` FOREIGN KEY (`reviewed_by_admin_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `peminjaman_kendaraans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `permintaan_persediaans`
--
ALTER TABLE `permintaan_persediaans`
  ADD CONSTRAINT `permintaan_persediaans_approved_by_kasubag_id_foreign` FOREIGN KEY (`approved_by_kasubag_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `permintaan_persediaans_reviewed_by_admin_id_foreign` FOREIGN KEY (`reviewed_by_admin_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `permintaan_persediaans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `request_models`
--
ALTER TABLE `request_models`
  ADD CONSTRAINT `request_models_approved_by_admin_id_foreign` FOREIGN KEY (`approved_by_admin_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `request_models_approved_by_kasubag_id_foreign` FOREIGN KEY (`approved_by_kasubag_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `request_models_reviewed_by_admin_id_foreign` FOREIGN KEY (`reviewed_by_admin_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `request_models_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `surat_persetujuan`
--
ALTER TABLE `surat_persetujuan`
  ADD CONSTRAINT `surat_persetujuan_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `transaksi_keluar_aset_tetap`
--
ALTER TABLE `transaksi_keluar_aset_tetap`
  ADD CONSTRAINT `transaksi_keluar_aset_tetap_aset_tetap_id_foreign` FOREIGN KEY (`aset_tetap_id`) REFERENCES `aset_tetap` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transaksi_keluar_aset_tetap_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `transaksi_keluar_persediaan`
--
ALTER TABLE `transaksi_keluar_persediaan`
  ADD CONSTRAINT `transaksi_keluar_persediaan_persediaan_id_foreign` FOREIGN KEY (`persediaan_id`) REFERENCES `persediaan` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transaksi_keluar_persediaan_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `transaksi_masuk_aset_tetap`
--
ALTER TABLE `transaksi_masuk_aset_tetap`
  ADD CONSTRAINT `transaksi_masuk_aset_tetap_aset_tetap_id_foreign` FOREIGN KEY (`aset_tetap_id`) REFERENCES `aset_tetap` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transaksi_masuk_aset_tetap_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `transaksi_masuk_persediaan`
--
ALTER TABLE `transaksi_masuk_persediaan`
  ADD CONSTRAINT `transaksi_masuk_persediaan_persediaan_id_foreign` FOREIGN KEY (`persediaan_id`) REFERENCES `persediaan` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transaksi_masuk_persediaan_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
