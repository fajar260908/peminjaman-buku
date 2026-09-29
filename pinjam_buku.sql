-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 23, 2026 at 04:14 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pinjam_buku`
--

-- --------------------------------------------------------

--
-- Table structure for table `buku`
--

CREATE TABLE `buku` (
  `kode_buku` int(11) NOT NULL,
  `gambar` varchar(250) NOT NULL,
  `nama_buku` varchar(100) NOT NULL,
  `tanggal_rilis` date NOT NULL,
  `pencipta` varchar(150) NOT NULL,
  `stok` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `buku`
--

INSERT INTO `buku` (`kode_buku`, `gambar`, `nama_buku`, `tanggal_rilis`, `pencipta`, `stok`) VALUES
(21, 'https://images.tokopedia.net/img/cache/700/VqbcmM/2023/12/26/8ba52200-ed26-4384-9e02-09d74e149e8b.png', 'crypto principiles', '2026-03-18', 'timoty ronald', '101'),
(23, 'https://bukukita.com/babacms/displaybuku/118036_f.jpg', 'belajar bahasa jepang', '2026-03-28', 'muryani j semita', '60'),
(24, 'https://cdn.gramedia.com/uploads/items/9786025320040.jpg', 'buku bahasa inggris', '2025-11-06', 'orang inggris', '50'),
(25, 'https://bukukita.com/babacms/displaybuku/110940_f.jpg', 'buku ngoding', '2026-03-28', 'arifkhy', '70'),
(26, 'https://www.static-src.com/wcsstore/Indraprastha/images/catalog/full/catalog-image/105/MTA-176638910/no-brand_no-brand_full01.jpg', 'belajar prudok digidaw', '2026-03-21', 'arip', '78'),
(27, 'https://m.media-amazon.com/images/I/51dd5msvITL.jpg', 'buku membuat kripik bonteng', '2026-03-07', 'shef', '80'),
(28, 'https://down-id.img.susercontent.com/file/id-11134207-7r98w-lzobkn7qc9niea.webp', 'cara solat', '2024-09-25', 'ustadz', '5'),
(29, 'https://down-id.img.susercontent.com/file/id-11134207-7rbk3-m98f18n2ppfxfd.webp', 'cara wudhu', '2026-03-20', 'ustadz', '130'),
(30, 'https://openlibrary.telkomuniversity.ac.id/uploads/book/cover/Dasar_Fotografi_Cara_Cepat_Memahami_Fotografi.jpg.jpg', 'belajar fotograpy dasar', '2023-02-05', 'fotograper', '100'),
(31, 'https://down-id.img.susercontent.com/file/id-11134207-7r98u-lzm1uuyme2lp59@resize_w900_nl.webp', 'sejarah sepak bola', '2020-06-22', 'budi utama', '60');

-- --------------------------------------------------------

--
-- Table structure for table `denda`
--

CREATE TABLE `denda` (
  `id_denda` int(10) NOT NULL,
  `id_pinjam` int(10) NOT NULL,
  `tanggal_pengembalian` date NOT NULL,
  `jenis_denda` enum('Terlambat Mengembalikan Buku','Kerusakan','Hilang') NOT NULL,
  `jumlah_hari_keterlambatan` int(10) NOT NULL,
  `status_denda` enum('Sudah Dibayar','Belum Dibayar') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `denda`
--

INSERT INTO `denda` (`id_denda`, `id_pinjam`, `tanggal_pengembalian`, `jenis_denda`, `jumlah_hari_keterlambatan`, `status_denda`) VALUES
(1, 1, '2026-04-24', 'Terlambat Mengembalikan Buku', 1, 'Sudah Dibayar'),
(2, 6, '2026-09-08', 'Terlambat Mengembalikan Buku', 2, 'Sudah Dibayar'),
(3, 8, '2026-09-08', 'Kerusakan', 0, 'Belum Dibayar'),
(4, 9, '2026-09-08', 'Terlambat Mengembalikan Buku', 2, 'Sudah Dibayar'),
(5, 10, '2026-09-08', 'Hilang', 2, 'Belum Dibayar'),
(6, 11, '2026-09-08', 'Kerusakan', 0, 'Belum Dibayar'),
(7, 12, '2026-09-08', 'Terlambat Mengembalikan Buku', 2, 'Sudah Dibayar'),
(8, 13, '2026-09-08', 'Kerusakan', 2, 'Sudah Dibayar'),
(9, 14, '2026-09-08', 'Kerusakan', 0, 'Sudah Dibayar'),
(10, 15, '2026-09-08', 'Terlambat Mengembalikan Buku', 2, 'Sudah Dibayar');

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--

CREATE TABLE `pengguna` (
  `id_pengguna` int(11) NOT NULL,
  `nama_pengguna` varchar(100) NOT NULL,
  `jenis_kelamin` enum('laki-laki','perempuan') NOT NULL,
  `alamat` varchar(150) NOT NULL,
  `role` enum('admin','user') NOT NULL,
  `password` varchar(250) NOT NULL,
  `email` varchar(150) NOT NULL,
  `tanggal_lahir_pengguna` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`id_pengguna`, `nama_pengguna`, `jenis_kelamin`, `alamat`, `role`, `password`, `email`, `tanggal_lahir_pengguna`) VALUES
(1, 'arifkhy', 'laki-laki', 'kp pamucatan', 'user', '$2y$10$5qckAMBIofonJT8Z0IvGw.O4EwH7vA.fKcUCpzwofqiMRxWLjlG1m', 'arifkhy@gmail.com', '2026-01-07'),
(2, 'adikss', 'perempuan', 'ngamprah gaje', 'admin', '$2y$10$nqnIkOmt9Xb46I.6n7QnA.YQHSVFxjvCEKN7mjsQyi7exyhX9Wgnq', 'ngampr@gmail.com', '2026-01-01'),
(3, 'fajar', 'laki-laki', 'sangkuriang cimahi', 'admin', '$2y$10$UOEiXnMaZYwcaLUp849JbOZeU8hfk2gZ6prDgU10FTFIXTgBugR8K', 'fjar@gmail.com', '2026-01-23'),
(4, 'eka dragon', 'perempuan', 'bandung barat', 'user', '$2y$10$VY4LY8bd1xRTzxBqH7xLOeetzh6JhBQCStZ6z.KEYhiSPNjRoYrb2', 'yapsa@gmail.com', '2026-01-13'),
(6, 'babang', 'laki-laki', 'padalarang rt03 rw09', 'admin', '$2y$10$EbNy7m7zEe0OLvaLtzhd0uNLtZrwgpBZfj05mvsuCs1rym.82/BYq', 'babangss@gmail.com', '2026-01-07'),
(10, 'rsv', 'laki-laki', 'bsgsv', 'user', '$2y$10$PerQ.olKPJ2D8YRUwZv9LeDz5cfmGv05aaP3Zno.gqQVhrG8eUie2', 'rsvhelmet@gmail.com', '2026-03-13'),
(12, 'rsvhhh', 'perempuan', 'bgffff', 'user', '$2y$10$WUprh88jse5UGjUf/8CH1uw2oIlftFdhCSTlN6ANaqi7CTlzZN6zi', 'rsvh@gmail.com', '2026-03-31'),
(15, 'alim', 'laki-laki', 'manggahang', 'user', '$2y$10$R7oDSc76G31lpfniuE/e4uz557laZNG2vNK1.V/3WSV8C7h5vemkm', 'alim@gmail.com', '2026-03-04'),
(20, 'adi mullet', 'laki-laki', 'padalarang', 'user', '$2y$10$WUQwSpkQf1Obj7n9rTS4UuiYZVFyE1eiusFqbCgwexW2swbe7IchO', 'adialamin@gmail.com', '2026-04-11'),
(22, 'putam', 'laki-laki', 'cipagerann', 'user', '$2y$10$/55TstUhG/JlGodwkoLrNuQvwtDZGwtyzsz7M6ifEzBLFO5ILOBtC', 'putam11@gmail.com', '2026-04-10'),
(23, 'epul', 'laki-laki', 'padalarang', 'user', '$2y$10$.rI3c8FCMCUC8DnqWjLfTerIxBYdeKwj3EwkK0U0gEHSMzDu8Km0a', 'epulo@gmail.com', '2026-04-04'),
(24, 'mario', 'laki-laki', 'tegalkawung', 'user', '$2y$10$QuT4v2U5mVZ8IJZtQh6GdOrPUYNimBiz9LCZZpjXCC2fQYsvY7yt6', 'rio33@gmail.com', '2026-04-18'),
(25, 'Rezka kasep', 'laki-laki', 'cidamar', 'user', '$2y$10$xZ4dnoc32qiHZS1ch3lU5.d22mcrAz4Zv58myvfysKh3bA6wLHc6O', 'rezka@gmail.com', '2026-04-07'),
(27, 'resky', 'laki-laki', 'batujajar', 'user', '$2y$10$9vZtpLf2GzQSK0UijyFlseeT6XjpeASUWoX83EU0K46jYZstR6O6S', 'resky56@tgmail.com', '2026-05-13');

-- --------------------------------------------------------

--
-- Table structure for table `pinjam`
--

CREATE TABLE `pinjam` (
  `id_pinjam` int(11) NOT NULL,
  `kode_buku` int(11) NOT NULL,
  `id_pengguna` int(11) NOT NULL,
  `tanggal_pinjam` date NOT NULL,
  `tanggal_kembali` date DEFAULT NULL,
  `status` enum('pending','dipinjam','menunggu_verifikasi','dikembalikan') NOT NULL,
  `jumlah_pinjam` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pinjam`
--

INSERT INTO `pinjam` (`id_pinjam`, `kode_buku`, `id_pengguna`, `tanggal_pinjam`, `tanggal_kembali`, `status`, `jumlah_pinjam`) VALUES
(1, 28, 1, '2026-04-01', '2026-04-14', 'dikembalikan', '5'),
(2, 28, 1, '2026-04-07', '2026-04-09', 'dipinjam', '2'),
(3, 28, 1, '2026-04-09', '2026-04-24', 'menunggu_verifikasi', '15'),
(4, 28, 1, '2026-04-09', '2026-04-24', 'menunggu_verifikasi', '15'),
(5, 28, 1, '2026-04-09', '2026-04-24', 'menunggu_verifikasi', '15'),
(6, 27, 1, '2026-04-15', '2026-05-02', 'dikembalikan', '10'),
(7, 23, 1, '2026-04-15', '2026-04-09', 'menunggu_verifikasi', '5'),
(8, 25, 1, '2026-04-15', '2026-04-08', 'dikembalikan', '10'),
(9, 21, 1, '2026-04-15', '2026-04-09', 'dikembalikan', '20'),
(10, 29, 3, '2026-04-08', '2026-04-24', 'dikembalikan', '10'),
(11, 29, 3, '2026-04-09', '2026-04-24', 'dikembalikan', '20'),
(12, 29, 3, '2026-04-08', '2026-04-12', 'dikembalikan', '20'),
(13, 29, 3, '2026-04-21', '2026-04-12', 'dikembalikan', '10'),
(14, 29, 3, '2026-04-10', '2026-04-24', 'dikembalikan', '10'),
(15, 29, 1, '2026-04-17', '2026-04-20', 'dikembalikan', '20'),
(16, 29, 3, '2026-04-17', '2026-04-14', 'dikembalikan', '10'),
(17, 29, 3, '2026-04-10', '2026-04-24', 'dikembalikan', '10'),
(18, 31, 1, '2026-04-09', '2026-09-22', 'dikembalikan', '20'),
(19, 31, 20, '2026-04-24', '2026-05-16', 'dikembalikan', '20'),
(20, 23, 1, '2026-04-16', '2026-05-30', 'menunggu_verifikasi', '10'),
(21, 31, 1, '2026-04-18', '2026-04-16', 'menunggu_verifikasi', '20'),
(22, 29, 20, '2026-04-17', '2026-04-24', 'dikembalikan', '10'),
(23, 23, 20, '2026-04-16', '2026-05-13', 'dikembalikan', '2'),
(24, 30, 20, '2026-04-03', NULL, 'pending', '10'),
(25, 24, 20, '2026-04-24', NULL, 'pending', '5'),
(26, 25, 20, '2026-04-09', '2026-04-24', 'dikembalikan', '10'),
(27, 29, 3, '2026-04-17', NULL, 'pending', '4'),
(28, 31, 3, '2026-04-10', NULL, 'pending', '7'),
(29, 24, 3, '2026-04-18', NULL, 'pending', '8'),
(30, 21, 3, '2026-04-10', NULL, 'pending', '30'),
(31, 24, 3, '2026-04-10', NULL, 'pending', '7'),
(32, 29, 24, '2026-04-18', NULL, 'pending', '10'),
(33, 30, 24, '2026-04-04', '2026-04-25', 'menunggu_verifikasi', '10'),
(34, 26, 24, '2026-04-02', '0000-00-00', 'dipinjam', '2'),
(35, 31, 20, '2026-04-28', '2026-04-24', 'dikembalikan', '10'),
(36, 21, 25, '2026-04-24', NULL, 'pending', '11'),
(37, 31, 20, '2026-05-13', '2026-05-13', 'dikembalikan', '10'),
(38, 30, 20, '2026-05-13', '2026-05-13', 'dikembalikan', '50'),
(39, 31, 24, '2026-09-22', '0000-00-00', 'dipinjam', '20');

-- --------------------------------------------------------

--
-- Table structure for table `riwayat_peminjaman`
--

CREATE TABLE `riwayat_peminjaman` (
  `id_riwayat` int(11) NOT NULL,
  `nama_buku` varchar(100) NOT NULL,
  `nama_peminjam` varchar(250) NOT NULL,
  `tanggal_peminjaman` date NOT NULL,
  `tanggal_pengembalian` date NOT NULL,
  `kode_buku` int(11) NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `tanggal_rilis` date DEFAULT NULL,
  `pencipta` varchar(100) DEFAULT NULL,
  `jumlah_pinjam` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `riwayat_peminjaman`
--

INSERT INTO `riwayat_peminjaman` (`id_riwayat`, `nama_buku`, `nama_peminjam`, `tanggal_peminjaman`, `tanggal_pengembalian`, `kode_buku`, `gambar`, `tanggal_rilis`, `pencipta`, `jumlah_pinjam`) VALUES
(36, 'belajar prudok digidaw', 'nizar syg ryan', '2026-04-01', '2026-04-03', 26, NULL, NULL, NULL, ''),
(37, 'cara solat', 'arifkhy', '2026-04-09', '2026-04-24', 28, NULL, NULL, NULL, '100'),
(38, 'cara solat', 'arifkhy', '2026-04-09', '2026-04-24', 28, NULL, NULL, NULL, '100'),
(39, 'buku membuat kripik bonteng', 'arifkhy', '2026-04-15', '2026-05-02', 27, NULL, NULL, NULL, '10'),
(40, 'belajar bahasa syumur', 'arifkhy', '2026-04-15', '2026-04-09', 23, NULL, NULL, NULL, '5'),
(41, 'buku ngoding', 'arifkhy', '2026-04-15', '2026-04-08', 25, NULL, NULL, NULL, '10'),
(45, 'buku ngoding', 'dewi', '2026-04-09', '2026-04-24', 25, NULL, NULL, NULL, '10'),
(47, 'belajar bahasa jepang', 'dewi', '2026-04-16', '2026-05-13', 23, NULL, NULL, NULL, '2'),
(48, 'sejarah sepak bola', 'dewi', '2026-05-13', '2026-05-13', 31, NULL, NULL, NULL, '10'),
(49, 'belajar fotograpy dasar', 'dewi', '2026-05-13', '2026-05-13', 30, NULL, NULL, NULL, '50'),
(50, 'sejarah sepak bola', 'dewi', '2026-04-24', '2026-05-16', 31, NULL, NULL, NULL, '20'),
(51, 'sejarah sepak bola', 'arifkhy', '2026-04-09', '2026-09-22', 31, NULL, NULL, NULL, '20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `buku`
--
ALTER TABLE `buku`
  ADD PRIMARY KEY (`kode_buku`);

--
-- Indexes for table `denda`
--
ALTER TABLE `denda`
  ADD PRIMARY KEY (`id_denda`),
  ADD UNIQUE KEY `id_pinjam` (`id_pinjam`);

--
-- Indexes for table `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id_pengguna`);

--
-- Indexes for table `pinjam`
--
ALTER TABLE `pinjam`
  ADD PRIMARY KEY (`id_pinjam`),
  ADD KEY `kode_buku` (`kode_buku`),
  ADD KEY `id_pengguna` (`id_pengguna`);

--
-- Indexes for table `riwayat_peminjaman`
--
ALTER TABLE `riwayat_peminjaman`
  ADD PRIMARY KEY (`id_riwayat`),
  ADD KEY `kode_buku` (`kode_buku`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `buku`
--
ALTER TABLE `buku`
  MODIFY `kode_buku` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `denda`
--
ALTER TABLE `denda`
  MODIFY `id_denda` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id_pengguna` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `pinjam`
--
ALTER TABLE `pinjam`
  MODIFY `id_pinjam` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `riwayat_peminjaman`
--
ALTER TABLE `riwayat_peminjaman`
  MODIFY `id_riwayat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `denda`
--
ALTER TABLE `denda`
  ADD CONSTRAINT `denda_ibfk_1` FOREIGN KEY (`id_pinjam`) REFERENCES `pinjam` (`id_pinjam`);

--
-- Constraints for table `pinjam`
--
ALTER TABLE `pinjam`
  ADD CONSTRAINT `pinjam_ibfk_1` FOREIGN KEY (`kode_buku`) REFERENCES `buku` (`kode_buku`),
  ADD CONSTRAINT `pinjam_ibfk_2` FOREIGN KEY (`id_pengguna`) REFERENCES `pengguna` (`id_pengguna`);

--
-- Constraints for table `riwayat_peminjaman`
--
ALTER TABLE `riwayat_peminjaman`
  ADD CONSTRAINT `riwayat_peminjaman_ibfk_1` FOREIGN KEY (`kode_buku`) REFERENCES `buku` (`kode_buku`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
