-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 28 Jan 2025 pada 13.07
-- Versi server: 8.0.30
-- Versi PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `e-arsip`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `dokumen`
--

CREATE TABLE `dokumen` (
  `id_dokumen` int NOT NULL,
  `no_dokumen` varchar(999) NOT NULL,
  `nama_dokumen` varchar(999) NOT NULL,
  `unit_kerja` int NOT NULL,
  `status` varchar(999) NOT NULL,
  `file` varchar(999) NOT NULL,
  `jenis_file` int NOT NULL,
  `nama_pengunggah_dokumen` int NOT NULL,
  `tanggal_upload_dokumen` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `dokumen`
--

INSERT INTO `dokumen` (`id_dokumen`, `no_dokumen`, `nama_dokumen`, `unit_kerja`, `status`, `file`, `jenis_file`, `nama_pengunggah_dokumen`, `tanggal_upload_dokumen`) VALUES
(6, '001/IX/NOV/IBIK', 'Surat Lamaran', 1, '2', 'uploads/2025-01-28-03-44-20-8504-harmoninetwork.pdf', 1, 1, '2025-01-28 14:34:58'),
(8, '001/90/12/IBI-K57/2025', 'Test Dokumen 1', 1, '1', 'uploads/2025-01-28-06-39-33-5403-L2000251 RISE Incident Report - January.pdf', 1, 1, '2025-01-28 14:34:58'),
(9, 'uuuuu', 'uuuuu', 1, '1', 'uploads/2025-01-28-07-45-04-5046-L2000251 - RISE Incident Report 12-28-2024 (1).pdf', 1, 1, '2025-01-28 14:45:04');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jabatan`
--

CREATE TABLE `jabatan` (
  `id_jabatan` int NOT NULL,
  `nama_jabatan` varchar(999) NOT NULL,
  `hak_akses` int NOT NULL,
  `keterangan` varchar(999) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `jabatan`
--

INSERT INTO `jabatan` (`id_jabatan`, `nama_jabatan`, `hak_akses`, `keterangan`) VALUES
(1, 'Admin PT', 1, 'Role Tertinggi');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jenis_file`
--

CREATE TABLE `jenis_file` (
  `id_jenis_file` int NOT NULL,
  `nama_jenis_file` varchar(999) NOT NULL,
  `keterangan` varchar(999) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `jenis_file`
--

INSERT INTO `jenis_file` (`id_jenis_file`, `nama_jenis_file`, `keterangan`) VALUES
(1, 'Pengumuman', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `status`
--

CREATE TABLE `status` (
  `id_status` int NOT NULL,
  `nama_status` varchar(255) NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `warna` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `status`
--

INSERT INTO `status` (`id_status`, `nama_status`, `keterangan`, `warna`) VALUES
(1, 'Berlaku', 'Masih Berlaku', '#2bba51'),
(2, 'Tidak Berlaku', 'Sudah tidak berlaku', '#e61212'),
(3, 'Tahap Review', 'Masih Tahap Review', '#215cdb');

-- --------------------------------------------------------

--
-- Struktur dari tabel `unit_kerja`
--

CREATE TABLE `unit_kerja` (
  `id_unit` int NOT NULL,
  `nama_unit` varchar(999) NOT NULL,
  `keterangan` varchar(999) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `unit_kerja`
--

INSERT INTO `unit_kerja` (`id_unit`, `nama_unit`, `keterangan`) VALUES
(1, 'Pusat Komputer dan Sistem Informasi', 'PUSKOM'),
(2, 'BAUK', 'Biro Administrasi Umum dan Keuangan'),
(3, 'Rektorat', 'Rektorat');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id_user` int NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `nama_lengkap` varchar(255) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `id_jabatan` int DEFAULT NULL,
  `id_unit_kerja` int DEFAULT NULL,
  `foto_profil` varchar(255) DEFAULT NULL,
  `hak_akses` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id_user`, `email`, `password`, `nama_lengkap`, `tanggal_lahir`, `alamat`, `id_jabatan`, `id_unit_kerja`, `foto_profil`, `hak_akses`) VALUES
(1, 'rasyiedfahmi@gmail.com', '5617', 'Fahmi Rasyied', '2001-01-15', 'Jl. asem ii', 1, 1, NULL, 1);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `dokumen`
--
ALTER TABLE `dokumen`
  ADD PRIMARY KEY (`id_dokumen`);

--
-- Indeks untuk tabel `jabatan`
--
ALTER TABLE `jabatan`
  ADD PRIMARY KEY (`id_jabatan`);

--
-- Indeks untuk tabel `jenis_file`
--
ALTER TABLE `jenis_file`
  ADD PRIMARY KEY (`id_jenis_file`);

--
-- Indeks untuk tabel `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`id_status`);

--
-- Indeks untuk tabel `unit_kerja`
--
ALTER TABLE `unit_kerja`
  ADD PRIMARY KEY (`id_unit`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `dokumen`
--
ALTER TABLE `dokumen`
  MODIFY `id_dokumen` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `jabatan`
--
ALTER TABLE `jabatan`
  MODIFY `id_jabatan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `jenis_file`
--
ALTER TABLE `jenis_file`
  MODIFY `id_jenis_file` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `status`
--
ALTER TABLE `status`
  MODIFY `id_status` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `unit_kerja`
--
ALTER TABLE `unit_kerja`
  MODIFY `id_unit` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
