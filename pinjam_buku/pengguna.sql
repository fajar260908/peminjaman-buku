-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 03, 2026 at 08:07 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `peminjaman_buku`
--

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--

CREATE TABLE `pengguna` (
  `id_pengguna` int NOT NULL,
  `nama_pengguna` varchar(100) NOT NULL,
  `jenis_kelamin` enum('laki-laki','perempuan') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `alamat` varchar(150) NOT NULL,
  `role` enum('admin','user') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `email` varchar(150) NOT NULL,
  `tanggal_lahir_pengguna` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`id_pengguna`, `nama_pengguna`, `jenis_kelamin`, `alamat`, `role`, `password`, `email`, `tanggal_lahir_pengguna`) VALUES
(1, 'arifkhy', 'laki-laki', 'kp pamucatan', 'admin', '$2y$10$cmROel6JHbWhB3JUVJygVOzEKicRzQZ29/XLvnvCBLAeyRtzVqc7.', 'arifkhy@gmail.com', '2026-01-07'),
(2, 'adikss', 'perempuan', 'ngamprah gaje', 'admin', '$2y$10$nqnIkOmt9Xb46I.6n7QnA.YQHSVFxjvCEKN7mjsQyi7exyhX9Wgnq', 'ngampr@gmail.com', '2026-01-01'),
(3, 'fjra', 'perempuan', 'sangkuriang cimahi', 'user', '$2y$10$WxL7osr8viAYSyHXBRPlCeJRQvut8YqVtmKk314/iczxZPhLlVnRK', 'fjar@gmail.com', '2026-01-23'),
(4, 'yapsa11', 'perempuan', 'bandung barat', 'user', '$2y$10$P5ZgIQdomjwj/v..kP8Zhu3FMf955Ow0UZwFxSICniQflbAtfExHK', 'yapsa@gmail.com', '2026-01-13'),
(6, 'babang', 'laki-laki', 'padalarang rt03 rw09', 'admin', '$2y$10$EbNy7m7zEe0OLvaLtzhd0uNLtZrwgpBZfj05mvsuCs1rym.82/BYq', 'babangss@gmail.com', '2026-01-07'),
(10, 'rsv', 'laki-laki', 'bsgsv', 'user', '$2y$10$PerQ.olKPJ2D8YRUwZv9LeDz5cfmGv05aaP3Zno.gqQVhrG8eUie2', 'rsvhelmet@gmail.com', '2026-03-13'),
(12, 'rsvhhh', 'perempuan', 'bgffff', 'user', '$2y$10$WUprh88jse5UGjUf/8CH1uw2oIlftFdhCSTlN6ANaqi7CTlzZN6zi', 'rsvh@gmail.com', '2026-03-31');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id_pengguna`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id_pengguna` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
