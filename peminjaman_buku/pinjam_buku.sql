-- Database bersih untuk instalasi baru aplikasi Peminjaman Buku Mata Pelajaran.
-- Import file ini ke database kosong yang sudah dibuat di phpMyAdmin.
-- Login awal: username admin | password BukuAdmin-7Q4m29
-- Segera ubah password tersebut setelah login pertama.
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
SET NAMES utf8mb4;
START TRANSACTION;

CREATE TABLE `buku` (
  `kode_buku` int(11) NOT NULL,
  `gambar` varchar(250) NOT NULL,
  `nama_buku` varchar(100) NOT NULL,
  `tanggal_rilis` date NOT NULL,
  `pencipta` varchar(150) NOT NULL,
  `stok` int(100) NOT NULL,
  `mata_pelajaran` varchar(100) NOT NULL DEFAULT 'Umum',
  `kelas` varchar(20) NOT NULL DEFAULT 'Umum',
  `semester` varchar(10) NOT NULL DEFAULT 'Umum'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `denda` (
  `id_denda` int(10) NOT NULL,
  `id_pinjam` int(10) NOT NULL,
  `tanggal_pengembalian` date NOT NULL,
  `jenis_denda` enum('Terlambat Mengembalikan Buku','Kerusakan','Hilang') NOT NULL,
  `jumlah_hari_keterlambatan` int(10) NOT NULL,
  `jumlah_denda` decimal(10,2) NOT NULL DEFAULT 0,
  `status_denda` enum('Sudah Dibayar','Belum Dibayar') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

CREATE TABLE `pinjam` (
  `id_pinjam` int(11) NOT NULL,
  `kode_buku` int(11) NOT NULL,
  `id_pengguna` int(11) NOT NULL,
  `tanggal_pinjam` date NOT NULL,
  `tanggal_jatuh_tempo` date DEFAULT NULL,
  `tanggal_kembali` date DEFAULT NULL,
  `status` enum('pending','dipinjam','menunggu_verifikasi','dikembalikan') NOT NULL,
  `jumlah_pinjam` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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


-- Contoh buku mata pelajaran. Sampul/pencipta/tanggal rilis disisakan untuk kompatibilitas tabel lama.
INSERT INTO `buku` (`kode_buku`, `gambar`, `nama_buku`, `tanggal_rilis`, `pencipta`, `stok`, `mata_pelajaran`, `kelas`, `semester`) VALUES
(1, '', 'Buku Matematika Kelas VII', '2000-01-01', '', '10', 'Matematika', 'VII', '1'),
(2, '', 'Buku Bahasa Indonesia Kelas VII', '2000-01-01', '', '10', 'Bahasa Indonesia', 'VII', '1'),
(3, '', 'Buku Bahasa Inggris Kelas VII', '2000-01-01', '', '10', 'Bahasa Inggris', 'VII', '1'),
(4, '', 'Buku IPA Kelas VII', '2000-01-01', '', '10', 'IPA', 'VII', '1'),
(5, '', 'Buku IPS Kelas VII', '2000-01-01', '', '10', 'IPS', 'VII', '1'),
(6, '', 'Buku Informatika Kelas VII', '2000-01-01', '', '10', 'Informatika', 'VII', '1'),
(7, '', 'Buku Pendidikan Agama Islam Kelas VII', '2000-01-01', '', '10', 'Pendidikan Agama Islam', 'VII', '1'),
(8, '', 'Buku PJOK Kelas VII', '2000-01-01', '', '10', 'PJOK', 'VII', '1');

-- Admin awal. Password tersimpan sebagai hash PHP password_hash(), bukan teks biasa.
INSERT INTO `pengguna` (`id_pengguna`, `nama_pengguna`, `jenis_kelamin`, `alamat`, `role`, `password`, `email`, `tanggal_lahir_pengguna`) VALUES
(1, 'admin', 'laki-laki', 'Administrator', 'admin', '$2y$12$edPrVz/r/g3h8IL30JA67OIG5k0F6X8mc58PJDeI9kt.7JtPfLiTe', 'admin@perpustakaan.test', '2000-01-01');

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
  ADD KEY `idx_denda_id_pinjam` (`id_pinjam`),
  ADD UNIQUE KEY `uniq_denda_pinjam_jenis` (`id_pinjam`,`jenis_denda`);

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
  MODIFY `kode_buku` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `denda`
--
ALTER TABLE `denda`
  MODIFY `id_denda` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id_pengguna` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pinjam`
--
ALTER TABLE `pinjam`
  MODIFY `id_pinjam` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `riwayat_peminjaman`
--
ALTER TABLE `riwayat_peminjaman`
  MODIFY `id_riwayat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

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
