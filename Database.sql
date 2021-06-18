-- phpMyAdmin SQL Dump
-- version 4.9.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 13 Apr 2020 pada 14.32
-- Versi server: 10.3.22-MariaDB-log-cll-lve
-- Versi PHP: 7.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mostpane_db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `deposit`
--

CREATE TABLE `deposit` (
  `id` int(11) NOT NULL,
  `id_deposit` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `jumlah_deposit` int(11) NOT NULL,
  `saldo_didapat` int(11) NOT NULL,
  `pengirim` varchar(210) NOT NULL,
  `tipe_deposit` varchar(200) NOT NULL,
  `metode_deposit` varchar(210) NOT NULL,
  `tujuan_deposit` varchar(200) NOT NULL,
  `status` enum('Sukses','Gagal','Menunggu') NOT NULL,
  `tanggal` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `id_ticket`
--

CREATE TABLE `id_ticket` (
  `id` int(11) NOT NULL,
  `username` varchar(250) NOT NULL,
  `ticket_id` varchar(55) NOT NULL,
  `judul` varchar(1000) NOT NULL,
  `status` varchar(55) NOT NULL,
  `tanggal` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `informasi`
--

CREATE TABLE `informasi` (
  `id` int(11) NOT NULL,
  `tipe` varchar(250) NOT NULL,
  `isi` varchar(1000) NOT NULL,
  `tanggal` varchar(24) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kontak`
--

CREATE TABLE `kontak` (
  `id` int(11) NOT NULL,
  `nama` varchar(55) NOT NULL,
  `fb` varchar(55) NOT NULL,
  `link_fb` varchar(500) NOT NULL,
  `wa` varchar(24) NOT NULL,
  `ig` varchar(30) NOT NULL,
  `jabatan` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `metode`
--

CREATE TABLE `metode` (
  `id` int(11) NOT NULL,
  `tipe` enum('Pulsa Transfer','Pulsa MKIOS','BANK','Lainnya') NOT NULL,
  `metode` varchar(250) NOT NULL,
  `rate` varchar(100) NOT NULL,
  `tujuan` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pesan_ticket`
--

CREATE TABLE `pesan_ticket` (
  `id` int(11) NOT NULL,
  `username` varchar(250) NOT NULL,
  `ticket_id` varchar(250) NOT NULL,
  `pesan` varchar(1000) NOT NULL,
  `tanggal` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `riwayat`
--

CREATE TABLE `riwayat` (
  `id` int(11) NOT NULL,
  `username` varchar(250) NOT NULL,
  `service` varchar(550) NOT NULL,
  `jumlah` varchar(250) NOT NULL,
  `harga` varchar(250) NOT NULL,
  `start_count` varchar(250) NOT NULL,
  `remains` varchar(250) NOT NULL,
  `order_id` varchar(250) NOT NULL,
  `status` varchar(250) NOT NULL,
  `target` varchar(550) NOT NULL,
  `tanggal` varchar(250) NOT NULL,
  `waktu` varchar(250) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `saldo`
--

CREATE TABLE `saldo` (
  `id` int(11) NOT NULL,
  `username` varchar(120) NOT NULL,
  `aksi` varchar(120) NOT NULL,
  `saldo_aktifity` varchar(120) NOT NULL,
  `tanggal` varchar(120) NOT NULL,
  `efek` varchar(120) NOT NULL,
  `saldo_awal` int(150) NOT NULL,
  `saldo_jadi` int(150) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `service`
--

CREATE TABLE `service` (
  `id` int(11) NOT NULL,
  `service` varchar(350) NOT NULL,
  `harga` varchar(150) NOT NULL,
  `min` varchar(150) NOT NULL,
  `max` varchar(150) NOT NULL,
  `category` varchar(350) NOT NULL,
  `catatan` varchar(350) NOT NULL,
  `provider_id` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(24) NOT NULL,
  `password` varchar(200) NOT NULL,
  `saldo` bigint(20) NOT NULL,
  `saldo_terpakai` bigint(20) NOT NULL,
  `email` varchar(55) NOT NULL,
  `level` enum('Admin','Member') NOT NULL,
  `status` enum('On','Off') NOT NULL,
  `tanggal_reg` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `saldo`, `saldo_terpakai`, `email`, `level`, `status`, `tanggal_reg`) VALUES
(1, 'admin', '$2y$10$ckyzi9kV8/qBrHVQyPmVoOA6uizkU/1/CD9S914n7IMwZ5Ez2WmaO', 1000000, 0, 'admin@admin.com', 'Admin', 'On', 'sekarang'),
(2, 'member', '$2y$10$pBoXp7B8ElpzCGDMfE49ieav1Wx.TGY.ZE.eFHLIA0Dy3LckY7oRm', 0, 0, 'member@member.com', 'Member', 'On', 'sekarang');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `deposit`
--
ALTER TABLE `deposit`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `id_ticket`
--
ALTER TABLE `id_ticket`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `informasi`
--
ALTER TABLE `informasi`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kontak`
--
ALTER TABLE `kontak`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `metode`
--
ALTER TABLE `metode`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pesan_ticket`
--
ALTER TABLE `pesan_ticket`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `riwayat`
--
ALTER TABLE `riwayat`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `saldo`
--
ALTER TABLE `saldo`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `deposit`
--
ALTER TABLE `deposit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `id_ticket`
--
ALTER TABLE `id_ticket`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `informasi`
--
ALTER TABLE `informasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `kontak`
--
ALTER TABLE `kontak`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `metode`
--
ALTER TABLE `metode`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pesan_ticket`
--
ALTER TABLE `pesan_ticket`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `riwayat`
--
ALTER TABLE `riwayat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `saldo`
--
ALTER TABLE `saldo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `service`
--
ALTER TABLE `service`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
