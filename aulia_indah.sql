-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 16, 2017 at 07:17 AM
-- Server version: 10.1.16-MariaDB
-- PHP Version: 5.6.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `aulia_indah`
--

-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

CREATE TABLE `barang` (
  `kode` varchar(25) NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `harga_jual` int(11) NOT NULL,
  `harga_modal` int(11) NOT NULL,
  `last_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `markup` int(11) NOT NULL,
  `diskon` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`kode`, `nama_barang`, `harga_jual`, `harga_modal`, `last_modified`, `markup`, `diskon`) VALUES
('212', 'TES WIRO SABLENG', 30000, 20000, '2016-12-26 12:12:56', 50, 20),
('BK32', 'Baki 32x22', 0, 9166, '2016-09-11 10:19:56', 0, 0),
('BK36', 'Baki 36x27', 0, 11333, '2016-09-11 10:19:19', 0, 0),
('DKL-SL', 'Dingklik SL', 9500, 7000, '2016-09-11 04:37:33', 0, 0),
('ESKTG', 'Eskan Tinggi', 10000, 7500, '2016-09-11 05:18:19', 0, 0),
('FLDAPL24', 'Folding 24 Apolo', 27000, 20000, '2016-09-11 05:13:31', 0, 0),
('FLDSLY20', 'Folding 20 Sly', 21500, 16000, '2016-09-11 05:14:42', 0, 0),
('FLDSLY30', 'Folding 30 Sly', 33500, 25000, '2016-09-11 05:12:30', 0, 0),
('GAPL', 'Gelas Plastik', 1500, 1000, '2016-09-11 04:43:33', 0, 0),
('HGRANK', 'Hanger Anak 1 Lsn', 7000, 5000, '2016-09-11 05:16:43', 0, 0),
('IRSSMK', 'Irus SM. K', 0, 3500, '2016-09-11 10:21:00', 0, 0),
('KIT-123', 'Kitchent Set 123', 28000, 21000, '2016-09-11 04:34:55', 0, 0),
('KPST13', 'Kapstok Still 13', 0, 8750, '2016-09-11 10:23:53', 0, 0),
('KPST19', 'Kapstok Still 19', 0, 5500, '2016-09-11 10:24:35', 0, 0),
('KSBK', 'Kursi Bakso HSP', 25000, 18750, '2016-09-11 04:40:03', 0, 0),
('KSTJBL', 'Keset Jambul', 7500, 5500, '2016-09-11 10:15:20', 0, 0),
('MCTR-C3', 'Mini Container C-3', 29000, 22000, '2016-09-11 04:33:51', 0, 0),
('MCTR-C4', 'Mini Container C-4', 36000, 27000, '2016-09-11 04:33:18', 0, 0),
('MCTR-C5', 'Mini Container C-5', 40000, 30000, '2016-09-11 04:24:12', 0, 0),
('RKSPT', 'Rak Sepatu Gantung', 0, 40000, '2016-09-11 10:21:31', 0, 0),
('RKSPT-S3', 'Rak Sepatu Cleo S3', 44000, 33000, '2016-09-11 04:45:50', 0, 0),
('RKSPT-S4', 'Rak Sepatu Cleo S4', 52000, 39000, '2016-09-11 04:46:56', 0, 0),
('RKSPT-S5', 'Rak Sepatu Cleo S5', 64000, 48000, '2016-09-11 04:47:46', 0, 0),
('RKTS', 'Rak Tas Gantung', 0, 50000, '2016-09-11 10:22:01', 0, 0),
('RTGSL-S2', 'Rantang SL S2', 23000, 17500, '2016-09-11 04:52:04', 0, 0),
('SBT2MNR', 'Serbet Dua Menara', 0, 3000, '2016-09-11 10:22:35', 0, 0),
('SBTDLR', 'Serbet Dolar', 0, 2000, '2016-09-11 10:23:08', 0, 0),
('SLTKY', 'Solet Kayu', 0, 1000, '2016-09-11 10:17:51', 0, 0),
('SPDK', 'Sapu Dok Super', 0, 9000, '2016-09-11 10:32:52', 0, 0),
('SPLD', 'Sapu Lidi', 0, 6500, '2016-09-11 10:32:11', 0, 0),
('SPSBTA', 'Sapu Sabut A', 0, 6500, '2016-09-11 10:30:24', 0, 0),
('SPSBTC', 'Sapu Sabut C', 0, 5000, '2016-09-11 10:29:59', 0, 0),
('SPSBTN1', 'Sapu Sabut Anyam No1', 0, 10000, '2016-09-11 10:30:51', 0, 0),
('SPSNR', 'Sapu Senar', 0, 6500, '2016-09-11 10:32:34', 0, 0),
('STLPJG', 'Sotil Box Kayu Panjang', 0, 5000, '2016-09-11 10:28:48', 0, 0),
('STLT9', 'Sotil Box Kayu T9', 0, 2700, '2016-09-11 10:28:14', 0, 0),
('T4CAL', 'Tempat Makan Calista', 8000, 5000, '2016-09-11 05:21:33', 0, 0),
('T4SDK', 'Tempat Makan + Sendok', 6000, 3750, '2016-09-11 05:20:27', 0, 0),
('TLNKY', 'Telenan Kayu', 0, 4700, '2016-09-11 10:17:07', 0, 0),
('TRM-SNP', 'Termos Shinpo 0,7', 40000, 30000, '2016-09-11 05:10:53', 0, 0),
('TS-SNP', 'Tutup Saji Shinpo', 33500, 25000, '2016-09-11 04:44:56', 0, 0),
('TSDKSNP', 'Tempat Sendok Tutup Shinpo', 20000, 15000, '2016-09-11 04:54:08', 0, 0),
('TTPGLN', 'Sprei Tutup Galon', 0, 17000, '2016-09-11 10:26:52', 0, 0),
('TTPGLS', 'Tutup Gelas 1 Lsn', 3000, 2000, '2016-09-11 04:50:38', 0, 0),
('TTPMGCM', 'Sampul Tutup Magiccom', 0, 17000, '2016-09-11 10:27:32', 0, 0),
('TTPSJ', 'Samput Tutup Saji', 0, 17000, '2016-09-11 10:27:14', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `barang_masuk`
--

CREATE TABLE `barang_masuk` (
  `no_nota` varchar(25) NOT NULL,
  `tgl_masuk` date NOT NULL,
  `username` varchar(10) NOT NULL,
  `total` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `kode_suplier` varchar(20) DEFAULT NULL,
  `dibayar` int(11) NOT NULL,
  `kekurangan` int(11) NOT NULL,
  `tgl_tempo` date DEFAULT NULL,
  `last_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `barang_masuk_detail`
--

CREATE TABLE `barang_masuk_detail` (
  `id_detail` int(11) NOT NULL,
  `kode` varchar(25) NOT NULL,
  `qty` int(11) NOT NULL,
  `no_nota` varchar(25) NOT NULL,
  `harga` int(11) NOT NULL,
  `last_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cabang`
--

CREATE TABLE `cabang` (
  `kode_cabang` varchar(255) NOT NULL,
  `nama_cabang` varchar(255) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `jenis` enum('cabang','pusat','pantura') NOT NULL DEFAULT 'cabang'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cabang`
--

INSERT INTO `cabang` (`kode_cabang`, `nama_cabang`, `alamat`, `owner`, `jenis`) VALUES
('1', 'ZIDANE CELL 2', 'SOLO', 'SUGENG', 'cabang'),
('2', 'ZIDANE CELL 3', 'JOGJA', 'HENDRO', 'cabang'),
('3', 'ZIDANE CELL 4', 'PEKALONGAN', 'BAYU YULI ARIMANTOKO', 'cabang'),
('4', 'ZIDANE CELL 5', 'CIREBON', 'HUMAEDI', 'cabang'),
('6', 'SEMARANG (PUSAT)', 'SEMARANG', 'FITRI A', 'pusat'),
('7', 'pantura solo', 'Solo', 'si owner solo', 'cabang');

-- --------------------------------------------------------

--
-- Table structure for table `history_harga`
--

CREATE TABLE `history_harga` (
  `id_history` int(11) NOT NULL,
  `tgl_perubahan` datetime NOT NULL,
  `kode` varchar(255) NOT NULL,
  `harga` int(11) NOT NULL,
  `harga_jual` int(11) NOT NULL,
  `last_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `markup` int(11) NOT NULL,
  `diskon` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `history_harga`
--

INSERT INTO `history_harga` (`id_history`, `tgl_perubahan`, `kode`, `harga`, `harga_jual`, `last_modified`, `markup`, `diskon`) VALUES
(2589, '2016-09-11 11:24:12', 'MCTR-C5', 30000, 40000, '2016-09-11 04:24:12', 0, 0),
(2590, '2016-09-11 11:33:18', 'MCTR-C4', 27000, 36000, '2016-09-11 04:33:18', 0, 0),
(2591, '2016-09-11 11:33:51', 'MCTR-C3', 22000, 29000, '2016-09-11 04:33:51', 0, 0),
(2592, '2016-09-11 11:34:55', 'KIT-123', 21000, 28000, '2016-09-11 04:34:55', 0, 0),
(2593, '2016-09-11 11:37:33', 'DKL-SL', 7000, 9500, '2016-09-11 04:37:33', 0, 0),
(2594, '2016-09-11 11:40:03', 'KSBK', 18750, 25000, '2016-09-11 04:40:03', 0, 0),
(2595, '2016-09-11 11:43:33', 'GAPL', 1000, 1500, '2016-09-11 04:43:33', 0, 0),
(2596, '2016-09-11 11:44:56', 'TS-SNP', 25000, 33500, '2016-09-11 04:44:56', 0, 0),
(2597, '2016-09-11 11:45:50', 'RKSPT-S3', 33000, 44000, '2016-09-11 04:45:50', 0, 0),
(2598, '2016-09-11 11:46:56', 'RKSPT-S4', 39000, 52000, '2016-09-11 04:46:56', 0, 0),
(2599, '2016-09-11 11:47:46', 'RKSPT-S5', 48000, 64000, '2016-09-11 04:47:46', 0, 0),
(2600, '2016-09-11 11:50:38', 'TTPGLS', 2000, 3000, '2016-09-11 04:50:38', 0, 0),
(2601, '2016-09-11 11:52:04', 'RTGSL-S2', 17500, 23000, '2016-09-11 04:52:04', 0, 0),
(2602, '2016-09-11 11:54:08', 'TSDKSNP', 15000, 20000, '2016-09-11 04:54:08', 0, 0),
(2603, '2016-09-11 12:10:53', 'TRM-SNP', 30000, 40000, '2016-09-11 05:10:53', 0, 0),
(2604, '2016-09-11 12:12:30', 'FLDSLY30', 25000, 33500, '2016-09-11 05:12:30', 0, 0),
(2605, '2016-09-11 12:13:31', 'FLDAPL24', 20000, 27000, '2016-09-11 05:13:31', 0, 0),
(2606, '2016-09-11 12:14:42', 'FLDSLY20', 16000, 21500, '2016-09-11 05:14:42', 0, 0),
(2607, '2016-09-11 12:16:44', 'HGRANK', 5000, 7000, '2016-09-11 05:16:44', 0, 0),
(2608, '2016-09-11 12:18:19', 'ESKTG', 7500, 10000, '2016-09-11 05:18:19', 0, 0),
(2609, '2016-09-11 12:20:27', 'T4SDK', 3750, 6000, '2016-09-11 05:20:27', 0, 0),
(2610, '2016-09-11 12:21:33', 'T4CAL', 5000, 8000, '2016-09-11 05:21:33', 0, 0),
(2611, '2016-09-11 17:15:20', 'KSTJBL', 5500, 7500, '2016-09-11 10:15:20', 0, 0),
(2612, '2016-09-11 17:17:07', 'TLNKY', 4700, 0, '2016-09-11 10:17:07', 0, 0),
(2613, '2016-09-11 17:17:51', 'SLTKY', 1000, 0, '2016-09-11 10:17:51', 0, 0),
(2614, '2016-09-11 17:19:19', 'BK36', 11333, 0, '2016-09-11 10:19:19', 0, 0),
(2615, '2016-09-11 17:19:56', 'BK32', 9166, 0, '2016-09-11 10:19:56', 0, 0),
(2616, '2016-09-11 17:21:00', 'IRSSMK', 3500, 0, '2016-09-11 10:21:00', 0, 0),
(2617, '2016-09-11 17:21:31', 'RKSPT', 40000, 0, '2016-09-11 10:21:31', 0, 0),
(2618, '2016-09-11 17:22:01', 'RKTS', 50000, 0, '2016-09-11 10:22:01', 0, 0),
(2619, '2016-09-11 17:22:35', 'SBT2MNR', 3000, 0, '2016-09-11 10:22:35', 0, 0),
(2620, '2016-09-11 17:23:08', 'SBTDLR', 2000, 0, '2016-09-11 10:23:08', 0, 0),
(2621, '2016-09-11 17:23:53', 'KPST13', 8750, 0, '2016-09-11 10:23:53', 0, 0),
(2622, '2016-09-11 17:24:35', 'KPST19', 5500, 0, '2016-09-11 10:24:35', 0, 0),
(2623, '2016-09-11 17:26:52', 'TTPGLN', 17000, 0, '2016-09-11 10:26:52', 0, 0),
(2624, '2016-09-11 17:27:14', 'TTPSJ', 17000, 0, '2016-09-11 10:27:14', 0, 0),
(2625, '2016-09-11 17:27:32', 'TTPMGCM', 17000, 0, '2016-09-11 10:27:32', 0, 0),
(2626, '2016-09-11 17:28:14', 'STLT9', 2700, 0, '2016-09-11 10:28:14', 0, 0),
(2627, '2016-09-11 17:28:48', 'STLPJG', 5000, 0, '2016-09-11 10:28:48', 0, 0),
(2628, '2016-09-11 17:29:59', 'SPSBTC', 5000, 0, '2016-09-11 10:29:59', 0, 0),
(2629, '2016-09-11 17:30:24', 'SPSBTA', 6500, 0, '2016-09-11 10:30:24', 0, 0),
(2630, '2016-09-11 17:30:51', 'SPSBTN1', 10000, 0, '2016-09-11 10:30:51', 0, 0),
(2631, '2016-09-11 17:31:19', 'SPLD', 10000, 0, '2016-09-11 10:31:19', 0, 0),
(2632, '2016-09-11 17:32:11', 'SPLD', 6500, 0, '2016-09-11 10:32:11', 0, 0),
(2633, '2016-09-11 17:32:34', 'SPSNR', 6500, 0, '2016-09-11 10:32:34', 0, 0),
(2634, '2016-09-11 17:32:52', 'SPDK', 9000, 0, '2016-09-11 10:32:52', 0, 0),
(2635, '2016-12-26 19:04:29', '212', 20000, 30000, '2016-12-26 12:04:29', 50, 50),
(2636, '2016-12-26 19:12:56', '212', 20000, 30000, '2016-12-26 12:12:56', 50, 20);

-- --------------------------------------------------------

--
-- Table structure for table `history_pembayaran_penjualan`
--

CREATE TABLE `history_pembayaran_penjualan` (
  `id_pembayaran` int(11) NOT NULL,
  `tgl_bayar` datetime NOT NULL,
  `jumlah_cicil` int(11) NOT NULL,
  `kekurangan_cicil` int(11) NOT NULL,
  `no_nota` varchar(25) NOT NULL,
  `last_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `history_pembayaran_suplier`
--

CREATE TABLE `history_pembayaran_suplier` (
  `id_pembayaran` int(11) NOT NULL,
  `tgl_bayar` datetime NOT NULL,
  `jumlah_cicil` int(11) NOT NULL,
  `kekurangan_cicil` int(11) NOT NULL,
  `no_nota` varchar(25) NOT NULL,
  `last_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `history_saldo`
--

CREATE TABLE `history_saldo` (
  `id_history` int(11) NOT NULL,
  `kode` varchar(25) NOT NULL,
  `tanggal` datetime NOT NULL,
  `saldo` int(11) NOT NULL,
  `last_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `history_saldo`
--

INSERT INTO `history_saldo` (`id_history`, `kode`, `tanggal`, `saldo`, `last_modified`) VALUES
(1275, 'MCTR-C5', '2016-09-11 11:24:12', 3, '2016-09-11 04:24:12'),
(1276, 'MCTR-C4', '2016-09-11 11:33:18', 3, '2016-09-11 04:33:18'),
(1277, 'MCTR-C3', '2016-09-11 11:33:51', 3, '2016-09-11 04:33:51'),
(1278, 'KIT-123', '2016-09-11 11:34:55', 1, '2016-09-11 04:34:55'),
(1279, 'DKL-SL', '2016-09-11 11:37:33', 11, '2016-09-11 04:37:33'),
(1280, 'KSBK', '2016-09-11 11:40:03', 6, '2016-09-11 04:40:03'),
(1281, 'GAPL', '2016-09-11 11:43:33', 22, '2016-09-11 04:43:33'),
(1282, 'TS-SNP', '2016-09-11 11:44:57', 4, '2016-09-11 04:44:57'),
(1283, 'RKSPT-S3', '2016-09-11 11:45:50', 3, '2016-09-11 04:45:50'),
(1284, 'RKSPT-S4', '2016-09-11 11:46:56', 2, '2016-09-11 04:46:56'),
(1285, 'RKSPT-S5', '2016-09-11 11:47:46', 2, '2016-09-11 04:47:46'),
(1286, 'TTPGLS', '2016-09-11 11:50:38', 8, '2016-09-11 04:50:38'),
(1287, 'RTGSL-S2', '2016-09-11 11:52:04', 6, '2016-09-11 04:52:04'),
(1288, 'TSDKSNP', '2016-09-11 11:54:08', 4, '2016-09-11 04:54:08'),
(1289, 'TRM-SNP', '2016-09-11 12:10:53', 5, '2016-09-11 05:10:53'),
(1290, 'FLDSLY30', '2016-09-11 12:12:30', 1, '2016-09-11 05:12:30'),
(1291, 'FLDAPL24', '2016-09-11 12:13:31', 2, '2016-09-11 05:13:31'),
(1292, 'FLDSLY20', '2016-09-11 12:14:42', 3, '2016-09-11 05:14:42'),
(1293, 'HGRANK', '2016-09-11 12:16:44', 7, '2016-09-11 05:16:44'),
(1294, 'ESKTG', '2016-09-11 12:18:20', 5, '2016-09-11 05:18:20'),
(1295, 'T4SDK', '2016-09-11 12:20:27', 6, '2016-09-11 05:20:27'),
(1296, 'T4CAL', '2016-09-11 12:21:33', 3, '2016-09-11 05:21:33'),
(1297, 'KSTJBL', '2016-09-11 17:15:20', 0, '2016-09-11 10:15:20'),
(1298, 'TLNKY', '2016-09-11 17:17:07', 0, '2016-09-11 10:17:07'),
(1299, 'SLTKY', '2016-09-11 17:17:51', 0, '2016-09-11 10:17:51'),
(1300, 'BK36', '2016-09-11 17:19:19', 0, '2016-09-11 10:19:19'),
(1301, 'BK32', '2016-09-11 17:19:56', 0, '2016-09-11 10:19:56'),
(1302, 'IRSSMK', '2016-09-11 17:21:00', 0, '2016-09-11 10:21:00'),
(1303, 'RKSPT', '2016-09-11 17:21:32', 2, '2016-09-11 10:21:32'),
(1304, 'RKTS', '2016-09-11 17:22:01', 2, '2016-09-11 10:22:01'),
(1305, 'SBT2MNR', '2016-09-11 17:22:35', 0, '2016-09-11 10:22:35'),
(1306, 'SBTDLR', '2016-09-11 17:23:08', 0, '2016-09-11 10:23:08'),
(1307, 'KPST13', '2016-09-11 17:23:53', 0, '2016-09-11 10:23:53'),
(1308, 'KPST19', '2016-09-11 17:24:35', 0, '2016-09-11 10:24:35'),
(1309, 'TTPGLN', '2016-09-11 17:26:52', 0, '2016-09-11 10:26:52'),
(1310, 'TTPSJ', '2016-09-11 17:27:14', 0, '2016-09-11 10:27:14'),
(1311, 'TTPMGCM', '2016-09-11 17:27:32', 0, '2016-09-11 10:27:32'),
(1312, 'STLT9', '2016-09-11 17:28:14', 0, '2016-09-11 10:28:14'),
(1313, 'STLPJG', '2016-09-11 17:28:48', 0, '2016-09-11 10:28:48'),
(1314, 'SPSBTC', '2016-09-11 17:29:59', 0, '2016-09-11 10:29:59'),
(1315, 'SPSBTA', '2016-09-11 17:30:24', 0, '2016-09-11 10:30:24'),
(1316, 'SPSBTN1', '2016-09-11 17:30:51', 0, '2016-09-11 10:30:51'),
(1317, 'SPLD', '2016-09-11 17:31:19', 0, '2016-09-11 10:31:19'),
(1318, 'SPLD', '2016-09-11 17:32:11', 0, '2016-09-11 10:32:11'),
(1319, 'SPSNR', '2016-09-11 17:32:34', 0, '2016-09-11 10:32:34'),
(1320, 'SPDK', '2016-09-11 17:32:52', 0, '2016-09-11 10:32:52'),
(1321, '212', '2016-12-26 19:04:29', 0, '2016-12-26 12:04:29'),
(1322, '212', '2016-12-26 19:12:57', 0, '2016-12-26 12:12:57');

-- --------------------------------------------------------

--
-- Table structure for table `karyawan`
--

CREATE TABLE `karyawan` (
  `username` varchar(10) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `no_telp` varchar(15) NOT NULL,
  `password` varchar(10) NOT NULL,
  `level` enum('master','user') NOT NULL,
  `status` enum('aktif','non aktif') NOT NULL,
  `last_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `karyawan`
--

INSERT INTO `karyawan` (`username`, `nama`, `no_telp`, `password`, `level`, `status`, `last_modified`) VALUES
('alwi', 'Mochammad Alwi', '087687', '?!cba123', 'master', 'aktif', '2016-09-11 04:20:58'),
('lita', 'Noormalita Sari Dewi', '0987766', 'lolita', 'user', 'aktif', '2016-09-11 04:21:06');

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

CREATE TABLE `member` (
  `kode_member` varchar(25) NOT NULL,
  `nama_member` varchar(50) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `no_tlp` varchar(15) NOT NULL,
  `last_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `modal_harian`
--

CREATE TABLE `modal_harian` (
  `id_modal` int(11) NOT NULL,
  `tgl_modal` date NOT NULL,
  `total_modal` int(11) NOT NULL,
  `last_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pengaturan`
--

CREATE TABLE `pengaturan` (
  `nama_toko` text NOT NULL,
  `alamat` text NOT NULL,
  `kontak` text NOT NULL,
  `logo` text NOT NULL,
  `panjang_logo` double NOT NULL,
  `lebar_logo` double NOT NULL,
  `posisi` text NOT NULL,
  `id` int(11) NOT NULL,
  `font_toko` int(11) NOT NULL,
  `font_alamat` int(11) NOT NULL,
  `font_kontak` int(11) NOT NULL,
  `weight_toko` text NOT NULL,
  `weight_alamat` text NOT NULL,
  `weight_kontak` text NOT NULL,
  `panjang_nota` int(11) NOT NULL,
  `ukuran_font` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pengaturan`
--

INSERT INTO `pengaturan` (`nama_toko`, `alamat`, `kontak`, `logo`, `panjang_logo`, `lebar_logo`, `posisi`, `id`, `font_toko`, `font_alamat`, `font_kontak`, `weight_toko`, `weight_alamat`, `weight_kontak`, `panjang_nota`, `ukuran_font`) VALUES
('AULIA INDAH', 'jl. kesejahteraan sosial no 75, sonosewu', 'Telp : 087838837240', 'LOGO.png', 0, 0, '', 1, 12, 10, 10, 'bold', 'italic', 'italic', 8, 10);

-- --------------------------------------------------------

--
-- Table structure for table `penjualan`
--

CREATE TABLE `penjualan` (
  `no_nota` varchar(25) NOT NULL,
  `tgl_keluar` date NOT NULL,
  `username` varchar(25) NOT NULL,
  `total` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `dibayar` int(11) NOT NULL,
  `kembali` int(11) NOT NULL,
  `kode_member` varchar(25) DEFAULT NULL,
  `kekurangan` int(11) NOT NULL,
  `tgl_tempo` date DEFAULT NULL,
  `last_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `penjualan_detail`
--

CREATE TABLE `penjualan_detail` (
  `id_detail` int(11) NOT NULL,
  `no_nota` varchar(25) NOT NULL,
  `kode` varchar(25) NOT NULL,
  `qty` int(11) NOT NULL,
  `harga_modal` int(11) NOT NULL,
  `harga` int(11) NOT NULL,
  `last_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `suplier`
--

CREATE TABLE `suplier` (
  `kode_suplier` varchar(25) NOT NULL,
  `nama_suplier` varchar(50) NOT NULL,
  `alamat` text NOT NULL,
  `no_tlp` varchar(15) NOT NULL,
  `last_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`kode`);

--
-- Indexes for table `barang_masuk`
--
ALTER TABLE `barang_masuk`
  ADD PRIMARY KEY (`no_nota`),
  ADD KEY `username` (`username`),
  ADD KEY `kode_suplier` (`kode_suplier`);

--
-- Indexes for table `barang_masuk_detail`
--
ALTER TABLE `barang_masuk_detail`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `kode` (`kode`),
  ADD KEY `no_nota` (`no_nota`);

--
-- Indexes for table `cabang`
--
ALTER TABLE `cabang`
  ADD PRIMARY KEY (`kode_cabang`);

--
-- Indexes for table `history_harga`
--
ALTER TABLE `history_harga`
  ADD PRIMARY KEY (`id_history`),
  ADD KEY `kode` (`kode`);

--
-- Indexes for table `history_pembayaran_penjualan`
--
ALTER TABLE `history_pembayaran_penjualan`
  ADD PRIMARY KEY (`id_pembayaran`),
  ADD KEY `no_nota` (`no_nota`);

--
-- Indexes for table `history_pembayaran_suplier`
--
ALTER TABLE `history_pembayaran_suplier`
  ADD PRIMARY KEY (`id_pembayaran`),
  ADD KEY `no_nota` (`no_nota`);

--
-- Indexes for table `history_saldo`
--
ALTER TABLE `history_saldo`
  ADD PRIMARY KEY (`id_history`),
  ADD KEY `kode` (`kode`);

--
-- Indexes for table `karyawan`
--
ALTER TABLE `karyawan`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`kode_member`);

--
-- Indexes for table `modal_harian`
--
ALTER TABLE `modal_harian`
  ADD PRIMARY KEY (`id_modal`),
  ADD UNIQUE KEY `tgl_modal` (`tgl_modal`);

--
-- Indexes for table `pengaturan`
--
ALTER TABLE `pengaturan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `penjualan`
--
ALTER TABLE `penjualan`
  ADD PRIMARY KEY (`no_nota`),
  ADD KEY `username` (`username`);

--
-- Indexes for table `penjualan_detail`
--
ALTER TABLE `penjualan_detail`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `no_nota` (`no_nota`),
  ADD KEY `kode` (`kode`);

--
-- Indexes for table `suplier`
--
ALTER TABLE `suplier`
  ADD PRIMARY KEY (`kode_suplier`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barang_masuk_detail`
--
ALTER TABLE `barang_masuk_detail`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `history_harga`
--
ALTER TABLE `history_harga`
  MODIFY `id_history` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2637;
--
-- AUTO_INCREMENT for table `history_pembayaran_penjualan`
--
ALTER TABLE `history_pembayaran_penjualan`
  MODIFY `id_pembayaran` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `history_pembayaran_suplier`
--
ALTER TABLE `history_pembayaran_suplier`
  MODIFY `id_pembayaran` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `history_saldo`
--
ALTER TABLE `history_saldo`
  MODIFY `id_history` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1323;
--
-- AUTO_INCREMENT for table `modal_harian`
--
ALTER TABLE `modal_harian`
  MODIFY `id_modal` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pengaturan`
--
ALTER TABLE `pengaturan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `penjualan_detail`
--
ALTER TABLE `penjualan_detail`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `barang_masuk`
--
ALTER TABLE `barang_masuk`
  ADD CONSTRAINT `barang_masuk_ibfk_1` FOREIGN KEY (`username`) REFERENCES `karyawan` (`username`) ON UPDATE CASCADE,
  ADD CONSTRAINT `barang_masuk_ibfk_2` FOREIGN KEY (`kode_suplier`) REFERENCES `suplier` (`kode_suplier`) ON UPDATE CASCADE;

--
-- Constraints for table `barang_masuk_detail`
--
ALTER TABLE `barang_masuk_detail`
  ADD CONSTRAINT `barang_masuk_detail_ibfk_1` FOREIGN KEY (`kode`) REFERENCES `barang` (`kode`) ON UPDATE CASCADE,
  ADD CONSTRAINT `barang_masuk_detail_ibfk_2` FOREIGN KEY (`no_nota`) REFERENCES `barang_masuk` (`no_nota`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `history_harga`
--
ALTER TABLE `history_harga`
  ADD CONSTRAINT `history_harga_ibfk_1` FOREIGN KEY (`kode`) REFERENCES `barang` (`kode`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `history_pembayaran_penjualan`
--
ALTER TABLE `history_pembayaran_penjualan`
  ADD CONSTRAINT `history_pembayaran_penjualan_ibfk_1` FOREIGN KEY (`no_nota`) REFERENCES `penjualan` (`no_nota`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `history_pembayaran_suplier`
--
ALTER TABLE `history_pembayaran_suplier`
  ADD CONSTRAINT `history_pembayaran_suplier_ibfk_1` FOREIGN KEY (`no_nota`) REFERENCES `barang_masuk` (`no_nota`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `history_saldo`
--
ALTER TABLE `history_saldo`
  ADD CONSTRAINT `history_saldo_ibfk_1` FOREIGN KEY (`kode`) REFERENCES `barang` (`kode`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `penjualan`
--
ALTER TABLE `penjualan`
  ADD CONSTRAINT `penjualan_ibfk_1` FOREIGN KEY (`username`) REFERENCES `karyawan` (`username`) ON UPDATE CASCADE;

--
-- Constraints for table `penjualan_detail`
--
ALTER TABLE `penjualan_detail`
  ADD CONSTRAINT `penjualan_detail_ibfk_1` FOREIGN KEY (`no_nota`) REFERENCES `penjualan` (`no_nota`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `penjualan_detail_ibfk_2` FOREIGN KEY (`kode`) REFERENCES `barang` (`kode`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
