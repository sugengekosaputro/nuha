-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 07, 2018 at 03:33 AM
-- Server version: 10.1.36-MariaDB
-- PHP Version: 7.2.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nuha`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_absensi`
--

CREATE TABLE `tb_absensi` (
  `id_absensi` int(5) NOT NULL,
  `id_sesi` int(8) NOT NULL,
  `nis_lokal` varchar(20) NOT NULL,
  `status` varchar(10) NOT NULL,
  `keterangan` text,
  `hardware_id` varchar(50) DEFAULT NULL,
  `log_time` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_absensi`
--

INSERT INTO `tb_absensi` (`id_absensi`, `id_sesi`, `nis_lokal`, `status`, `keterangan`, `hardware_id`, `log_time`) VALUES
(79, 41, '10.01.05', 'pergi', NULL, '109090', NULL),
(80, 41, '10:02:06', 'ada', NULL, NULL, NULL),
(81, 41, '11.10.080', 'ada', NULL, NULL, NULL),
(82, 41, '111', 'ada', NULL, NULL, NULL),
(83, 41, '123', 'ada', NULL, NULL, NULL),
(84, 42, '10.01.05', 'ada', NULL, NULL, NULL),
(85, 42, '10:02:06', 'ada', NULL, NULL, NULL),
(86, 42, '11.10.080', 'ada', NULL, '109099', NULL),
(87, 42, '111', 'ada', NULL, NULL, NULL),
(88, 42, '123', 'ada', NULL, NULL, NULL),
(94, 44, '10.01.05', 'ada', NULL, '109090', '03:04:36'),
(95, 44, '10:02:06', 'ada', NULL, NULL, NULL),
(96, 44, '11.10.080', 'pergi', NULL, '109099', '03:11:45'),
(97, 44, '111', 'ada', NULL, '109000', '03:44:25'),
(98, 44, '123', 'ada', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tb_absensi_detail`
--

CREATE TABLE `tb_absensi_detail` (
  `id_detail` int(8) NOT NULL,
  `id_absensi` int(8) NOT NULL,
  `pergi` time DEFAULT NULL,
  `kembali` time DEFAULT NULL,
  `tanggal` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_absensi_detail`
--

INSERT INTO `tb_absensi_detail` (`id_detail`, `id_absensi`, `pergi`, `kembali`, `tanggal`) VALUES
(6, 86, '14:13:49', NULL, '2018-12-05'),
(9, 94, '02:52:47', NULL, '2018-12-06'),
(10, 94, '02:54:27', '03:04:36', '2018-12-06'),
(11, 96, '03:05:51', '03:09:40', '2018-12-06'),
(12, 96, '03:11:45', NULL, '2018-12-06'),
(13, 97, '03:38:47', '03:44:25', '2018-12-06');

-- --------------------------------------------------------

--
-- Table structure for table `tb_santri`
--

CREATE TABLE `tb_santri` (
  `nis_lokal` varchar(15) NOT NULL,
  `nis_lokal_ernis` varchar(50) NOT NULL,
  `nik` varchar(50) DEFAULT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `tempat_lahir` varchar(20) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `jenis_kelamin` varchar(10) DEFAULT NULL,
  `agama` varchar(10) DEFAULT NULL,
  `hobi` varchar(50) DEFAULT NULL,
  `cita_cita` varchar(50) DEFAULT NULL,
  `jumlah_sdr` int(5) DEFAULT NULL,
  `tanggal_masuk` date DEFAULT NULL,
  `kls_madrasah_diniyah` int(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_santri`
--

INSERT INTO `tb_santri` (`nis_lokal`, `nis_lokal_ernis`, `nik`, `nama_lengkap`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `hobi`, `cita_cita`, `jumlah_sdr`, `tanggal_masuk`, `kls_madrasah_diniyah`) VALUES
('10.01.05', '510035730009110075', '8888234219', 'Dewi Robiatul Mubaroro', 'Blitar', '1995-04-13', 'p', 'islam', 'Menyanyi', 'Guru', 4, '2010-07-22', 0),
('10:02:06', '510035730009110080', '8904838381', 'Mar’atus Sholihah', 'Nganjuk', '1992-02-25', 'p', 'islam', 'Membaca', 'Guru', 2, '2010-08-07', 0),
('11.10.080', '510035730009110093', '3573026005960002', 'Sarah Dewi Nur Faricha', 'Malang', '1992-05-20', 'p', 'islam', 'Traveling', 'Guru', 2, '2011-07-10', 6),
('111', '222', '77478383', 'saputro', 'malang', '1996-05-14', 'p', 'islam', 'masak', 'renang', 2, '2018-11-10', 1),
('123', '123123', '321321', 'sugeng', 'malang', '2018-12-05', 'p', 'islam', 'ngoding', 'ngoding', 2, '2018-12-02', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tb_sesi`
--

CREATE TABLE `tb_sesi` (
  `id_sesi` int(5) NOT NULL,
  `tanggal` date NOT NULL,
  `qr_path` varchar(50) NOT NULL,
  `random` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_sesi`
--

INSERT INTO `tb_sesi` (`id_sesi`, `tanggal`, `qr_path`, `random`) VALUES
(28, '2018-12-01', 'qr/2018-12-02.png', 'x0EQy'),
(29, '2018-12-02', 'qr/2018-12-02.png', 'c9zed'),
(38, '2018-12-02', 'qr/2018-12-03.png', 'B8smJ'),
(39, '2018-12-03', 'qr/2018-12-03.png', 'G7nzk'),
(41, '2018-12-04', 'qr/2018-12-04.png', 'lCYRU'),
(42, '2018-12-05', 'qr/2018-12-05.png', 'uywnJ'),
(44, '2018-12-06', 'qr/2018-12-06.png', 'ueLsX');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_absensi`
--
ALTER TABLE `tb_absensi`
  ADD PRIMARY KEY (`id_absensi`);

--
-- Indexes for table `tb_absensi_detail`
--
ALTER TABLE `tb_absensi_detail`
  ADD PRIMARY KEY (`id_detail`);

--
-- Indexes for table `tb_santri`
--
ALTER TABLE `tb_santri`
  ADD PRIMARY KEY (`nis_lokal`);

--
-- Indexes for table `tb_sesi`
--
ALTER TABLE `tb_sesi`
  ADD PRIMARY KEY (`id_sesi`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_absensi`
--
ALTER TABLE `tb_absensi`
  MODIFY `id_absensi` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;

--
-- AUTO_INCREMENT for table `tb_absensi_detail`
--
ALTER TABLE `tb_absensi_detail`
  MODIFY `id_detail` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tb_sesi`
--
ALTER TABLE `tb_sesi`
  MODIFY `id_sesi` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
