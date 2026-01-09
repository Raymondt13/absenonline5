-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 09 Jan 2026 pada 04.07
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `absenonline5`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `building`
--

CREATE TABLE `building` (
  `building_id` int(11) NOT NULL,
  `id_city` int(11) DEFAULT NULL,
  `code` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `address` varchar(100) NOT NULL,
  `building_scanner` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `building`
--

INSERT INTO `building` (`building_id`, `id_city`, `code`, `name`, `address`, `building_scanner`) VALUES
(1, 2, 'SWUKZ/2021', 'Jakarta Timur', 'Jl. Jatinegara Barat 187 Jakarta Timur 13310', ''),
(6, 3, 'SWRIU/2025', 'Jujutsu Kaisen', 'jln. kenangan', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `city`
--

CREATE TABLE `city` (
  `city_id` int(11) NOT NULL,
  `namakota` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `city`
--

INSERT INTO `city` (`city_id`, `namakota`) VALUES
(2, 'Palembang'),
(3, 'Medan');

-- --------------------------------------------------------

--
-- Struktur dari tabel `cuty`
--

CREATE TABLE `cuty` (
  `cuty_id` int(11) NOT NULL,
  `employees_id` int(11) NOT NULL,
  `cuty_start` date NOT NULL,
  `cuty_end` date NOT NULL,
  `date_work` date NOT NULL,
  `cuty_total` int(11) NOT NULL,
  `cuty_description` varchar(100) NOT NULL,
  `cuty_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `cuty`
--

INSERT INTO `cuty` (`cuty_id`, `employees_id`, `cuty_start`, `cuty_end`, `date_work`, `cuty_total`, `cuty_description`, `cuty_status`) VALUES
(3, 14, '2025-12-27', '2025-12-29', '2025-12-30', 3, 'jalan jalan', 1),
(4, 14, '2025-12-27', '2025-12-29', '2025-12-30', 3, 'jalan jalan', 1),
(5, 14, '2026-01-01', '2026-01-10', '2026-01-11', 10, 'healing', 2),
(6, 14, '2025-12-28', '2025-12-29', '2025-12-30', 2, 'healing', 3),
(7, 14, '2025-12-28', '2025-12-29', '2025-12-29', 2, 'istirahat', 3),
(8, 17, '2025-12-29', '2026-01-01', '2026-01-02', 4, 'Pulkam', 2),
(9, 17, '2025-12-30', '2026-01-01', '2026-01-02', 3, 'Pulkam', 1),
(10, 17, '2026-01-09', '2026-01-10', '2026-01-11', 2, 'Sakit', 1),
(11, 17, '2026-01-09', '2026-01-17', '2026-01-12', 9, 'pulkam beli beras', 3);

-- --------------------------------------------------------

--
-- Struktur dari tabel `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `employees_code` varchar(20) NOT NULL,
  `employees_email` varchar(30) NOT NULL,
  `employees_password` varchar(100) NOT NULL,
  `employees_name` varchar(50) NOT NULL,
  `position_id` int(11) NOT NULL,
  `shift_id` int(11) NOT NULL,
  `building_id` int(11) NOT NULL,
  `photo` varchar(100) NOT NULL,
  `created_login` datetime NOT NULL,
  `created_cookies` varchar(70) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `employees`
--

INSERT INTO `employees` (`id`, `employees_code`, `employees_email`, `employees_password`, `employees_name`, `position_id`, `shift_id`, `building_id`, `photo`, `created_login`, `created_cookies`) VALUES
(14, '123456789', 'intan@gmail.com', 'cd3338fcce8e72320630c8edb389726245d2cfe43873003cf45aa3733362f53d', 'Intan', 1, 1, 1, '', '2026-01-09 09:18:17', 'e65eb5904a144efc847afdc128765079'),
(15, '1234', 'siskaeee@gmail.com', '2d84d6531bae6e93abb5b9fb62ca3b3b0f02f89e9a7ac3ceae69e9a4e1fd35ad', 'Siskaeee', 1, 1, 1, '2021-12-11f3320b83e714a0f6280c01a1444c74ef.jpg', '2025-12-28 13:32:23', '6baf05e5de14becf64ed2a919923babc'),
(16, '0422097103', 'drbejo2020@gmail.com', '73c255ac91248cc1395378b14d762fde1a3e1ae40c7c9e816a540396a21ab333', 'Bejo Sutrisno', 1, 5, 6, '0422097103-42a7a19bf5cfe98491131bec6ab50cff-083840-.jpeg', '2022-12-20 06:52:22', 'be6971e9568cac4e97057d89602d4142'),
(17, '123456', 'intan1@gmail.com', 'cd3338fcce8e72320630c8edb389726245d2cfe43873003cf45aa3733362f53d', 'intan1', 1, 1, 1, '', '2026-01-09 09:18:17', 'e65eb5904a144efc847afdc128765079');

-- --------------------------------------------------------

--
-- Struktur dari tabel `gaji`
--

CREATE TABLE `gaji` (
  `id` int(11) NOT NULL,
  `employees_id` int(11) DEFAULT NULL,
  `gaji` int(11) NOT NULL,
  `potongan_uang_hadir` int(11) NOT NULL,
  `potongan_keterlambatan` int(11) NOT NULL,
  `perhitungan_mangkir` int(11) NOT NULL,
  `konversi_alpa` int(11) NOT NULL,
  `konversi_izin` int(11) NOT NULL,
  `konversi_sakit_surat` int(11) NOT NULL,
  `konversi_sakit_tanpa_surat` int(11) NOT NULL,
  `bulan` varchar(20) NOT NULL,
  `tahun` varchar(20) NOT NULL,
  `tanggal` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `gaji`
--

INSERT INTO `gaji` (`id`, `employees_id`, `gaji`, `potongan_uang_hadir`, `potongan_keterlambatan`, `perhitungan_mangkir`, `konversi_alpa`, `konversi_izin`, `konversi_sakit_surat`, `konversi_sakit_tanpa_surat`, `bulan`, `tahun`, `tanggal`) VALUES
(1, 14, 10000000, 1000000, 100000, 100000, 10, 10, 10, 10, '12', '2025', '2025-12-28');

-- --------------------------------------------------------

--
-- Struktur dari tabel `position`
--

CREATE TABLE `position` (
  `position_id` int(11) NOT NULL,
  `position_name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `position`
--

INSERT INTO `position` (`position_id`, `position_name`) VALUES
(1, 'Pegawai');

-- --------------------------------------------------------

--
-- Struktur dari tabel `presence`
--

CREATE TABLE `presence` (
  `presence_id` int(11) NOT NULL,
  `employees_id` int(11) NOT NULL,
  `presence_date` date NOT NULL,
  `time_in` time NOT NULL,
  `time_out` time NOT NULL,
  `picture_in` text NOT NULL,
  `picture_out` varchar(150) NOT NULL,
  `present_id` int(11) NOT NULL,
  `latitude_longtitude_in` varchar(100) NOT NULL,
  `latitude_longtitude_out` varchar(100) NOT NULL,
  `information` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `presence`
--

INSERT INTO `presence` (`presence_id`, `employees_id`, `presence_date`, `time_in`, `time_out`, `picture_in`, `picture_out`, `present_id`, `latitude_longtitude_in`, `latitude_longtitude_out`, `information`) VALUES
(1, 6, '2021-08-10', '21:48:19', '22:45:54', '2021-08-10-in-1628606899-6.jpeg', '2021-08-10-out-1628610354-6.jpeg', 1, '-4.5585849,105.40680789999999', '-4.5585849,105.40680789999999', ''),
(2, 6, '2021-08-11', '00:19:18', '00:22:11', '2021-08-11-in-1628615958-6.jpeg', '2021-08-11-out-1628616131-6.jpeg', 1, '-4.5585849,105.40680789999999', '-4.5585849,105.40680789999999', ''),
(3, 15, '2021-12-11', '14:56:02', '00:00:00', '2021-12-11-in-1639209363-15.jpeg', '', 4, '-6.3264155,107.3096539', '', ''),
(4, 14, '2025-12-24', '12:17:12', '16:17:30', '2025-12-24-in-1766575032-14.jpeg', '2025-12-24-out-1766575050-14.jpeg', 1, '3.5946496,98.697216', '3.5946496,98.697216', ''),
(5, 15, '2025-12-24', '21:53:49', '00:00:00', '2025-12-24-in-1766588029-15.jpeg', '', 2, '4.0139114,98.2839731', '', ''),
(6, 14, '2025-12-27', '16:37:28', '00:00:00', '2025-12-27-in-1766828248-14.jpeg', '', 5, '3.588096,98.6742784', '', ''),
(7, 17, '2026-01-05', '16:44:32', '16:44:51', '2026-01-05-in-1767606272-17.jpeg', '2026-01-05-out-1767606292-17.jpeg', 1, '1.4645,124.823', '1.4645,124.823', ''),
(8, 14, '2026-01-05', '16:48:06', '16:48:17', '2026-01-05-in-1767606487-14.jpeg', '2026-01-05-out-1767606497-14.jpeg', 1, '1.4645,124.823', '1.4645,124.823', ''),
(9, 17, '2026-01-09', '09:20:32', '09:57:15', '2026-01-09-in-1767925232-17.jpeg', '2026-01-09-out-1767927435-17.jpeg', 1, '-7.273,112.7445', '-7.273,112.7445', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `present_status`
--

CREATE TABLE `present_status` (
  `present_id` int(11) NOT NULL,
  `present_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `present_status`
--

INSERT INTO `present_status` (`present_id`, `present_name`) VALUES
(1, 'Hadir'),
(2, 'Sakit dengan surat'),
(3, 'Izin'),
(4, 'Sakit tanpa surat'),
(5, 'Izin Sesuai Undang Undang');

-- --------------------------------------------------------

--
-- Struktur dari tabel `shift`
--

CREATE TABLE `shift` (
  `shift_id` int(11) NOT NULL,
  `shift_name` varchar(20) NOT NULL,
  `time_in` time NOT NULL,
  `time_out` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `shift`
--

INSERT INTO `shift` (`shift_id`, `shift_name`, `time_in`, `time_out`) VALUES
(1, 'FULL TIME', '08:30:00', '17:00:00'),
(5, 'PAGI', '09:00:00', '15:00:00'),
(6, 'SORE', '14:00:00', '20:00:00'),
(7, 'REG-P', '08:00:00', '11:15:00'),
(8, 'REG-M', '18:15:00', '20:45:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sw_site`
--

CREATE TABLE `sw_site` (
  `site_id` int(11) NOT NULL,
  `site_url` varchar(100) NOT NULL,
  `site_name` varchar(50) NOT NULL,
  `site_company` varchar(30) NOT NULL,
  `site_manager` varchar(30) NOT NULL,
  `site_director` varchar(30) NOT NULL,
  `site_phone` char(12) NOT NULL,
  `site_address` text NOT NULL,
  `site_description` text NOT NULL,
  `site_logo` varchar(50) NOT NULL,
  `site_email` varchar(30) NOT NULL,
  `site_email_domain` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `sw_site`
--

INSERT INTO `sw_site` (`site_id`, `site_url`, `site_name`, `site_company`, `site_manager`, `site_director`, `site_phone`, `site_address`, `site_description`, `site_logo`, `site_email`, `site_email_domain`) VALUES
(1, 'http://localhost/KERJAAN/absenonline5/', 'EABSEN', 'STIBA IEC JAKARTA', 'Bejo Sutrisno', 'Dr. Bejo Sutrisno, M.Pd.', '021-8518685', 'Jl. Jatinegara Barat 187 Jakarta Timur', 'Presensi Online dengan Foto pengguna', 'images-4png.png', 'admin@gmail.com', 'admin@gmail.com');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `username` varchar(40) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `fullname` varchar(40) NOT NULL,
  `registered` datetime NOT NULL,
  `created_login` datetime NOT NULL,
  `last_login` datetime NOT NULL,
  `session` varchar(100) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `browser` varchar(30) NOT NULL,
  `level` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`user_id`, `username`, `email`, `password`, `fullname`, `registered`, `created_login`, `last_login`, `session`, `ip`, `browser`, `level`) VALUES
(1, 'admin', 'admin@gmail.com', '88222999e01f1910a5ac39fa37d3b8b704973d503d0ff7c84d46305b92cfa0f6', 'Administrator', '2021-02-03 10:22:00', '2026-01-09 09:14:29', '2026-01-09 09:56:21', '-', '1', 'Google Crome', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_level`
--

CREATE TABLE `user_level` (
  `level_id` int(11) NOT NULL,
  `level_name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user_level`
--

INSERT INTO `user_level` (`level_id`, `level_name`) VALUES
(1, 'Administrator'),
(2, 'Operator');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `building`
--
ALTER TABLE `building`
  ADD PRIMARY KEY (`building_id`),
  ADD KEY `id_city` (`id_city`);

--
-- Indeks untuk tabel `city`
--
ALTER TABLE `city`
  ADD PRIMARY KEY (`city_id`);

--
-- Indeks untuk tabel `cuty`
--
ALTER TABLE `cuty`
  ADD PRIMARY KEY (`cuty_id`);

--
-- Indeks untuk tabel `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `gaji`
--
ALTER TABLE `gaji`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `position`
--
ALTER TABLE `position`
  ADD PRIMARY KEY (`position_id`);

--
-- Indeks untuk tabel `presence`
--
ALTER TABLE `presence`
  ADD PRIMARY KEY (`presence_id`);

--
-- Indeks untuk tabel `present_status`
--
ALTER TABLE `present_status`
  ADD PRIMARY KEY (`present_id`);

--
-- Indeks untuk tabel `shift`
--
ALTER TABLE `shift`
  ADD PRIMARY KEY (`shift_id`);

--
-- Indeks untuk tabel `sw_site`
--
ALTER TABLE `sw_site`
  ADD PRIMARY KEY (`site_id`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- Indeks untuk tabel `user_level`
--
ALTER TABLE `user_level`
  ADD PRIMARY KEY (`level_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `building`
--
ALTER TABLE `building`
  MODIFY `building_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `city`
--
ALTER TABLE `city`
  MODIFY `city_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `cuty`
--
ALTER TABLE `cuty`
  MODIFY `cuty_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT untuk tabel `gaji`
--
ALTER TABLE `gaji`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `position`
--
ALTER TABLE `position`
  MODIFY `position_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `presence`
--
ALTER TABLE `presence`
  MODIFY `presence_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `present_status`
--
ALTER TABLE `present_status`
  MODIFY `present_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `shift`
--
ALTER TABLE `shift`
  MODIFY `shift_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `sw_site`
--
ALTER TABLE `sw_site`
  MODIFY `site_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `user_level`
--
ALTER TABLE `user_level`
  MODIFY `level_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `building`
--
ALTER TABLE `building`
  ADD CONSTRAINT `building_ibfk_1` FOREIGN KEY (`id_city`) REFERENCES `city` (`city_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
