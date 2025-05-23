-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 20, 2025 at 03:56 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `smpn4tangsel`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_buku`
--

CREATE TABLE `tb_buku` (
  `id_buku` varchar(20) NOT NULL,
  `judul` varchar(100) DEFAULT NULL,
  `pengarang` varchar(100) DEFAULT NULL,
  `penerbit` varchar(100) DEFAULT NULL,
  `tahun_terbit` int(11) DEFAULT NULL,
  `kategori` varchar(50) DEFAULT NULL,
  `stok` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_buku`
--

INSERT INTO `tb_buku` (`id_buku`, `judul`, `pengarang`, `penerbit`, `tahun_terbit`, `kategori`, `stok`) VALUES
('BKA001', 'Metodologi Penelitian', 'H. Abdurrahmat Fathoni', 'Rineka Cipta', 2006, 'Akademik', 7),
('BKB001', 'Habibie & Ainun', 'B.J. Habibie', 'THC Mandiri', 2010, 'Biografi', 8),
('BKDR001', 'Dear Nathan', 'Erisca Febriani', 'Best Media', 2016, 'Drama Romantis', 15),
('BKDR002', 'Dilan 1990', 'Pidi Baiq', 'Pastel Books', 2014, 'Romantis', 11),
('BKE001', 'Agar Menulis Gampang', 'Andrias Harefa', 'Gramedia Pustaka Utama', 2002, 'Edukasi', 5),
('BKF001', 'Laut Bercerita', 'Leila S. Chudori', 'KPG', 2017, 'Fiksi', 12),
('BKF002', 'Sang Pemimpi', 'Andrea Hirata', 'Bentang Pustaka', 2011, 'Fiksi', 9),
('BKF003', 'Negeri 5 Menara', 'Ahmad Fuadi', 'Gramedia Pustaka Utama', 2009, 'Fiksi', 10),
('BKF004', 'Laskar Pelangi', 'Andrea Hirata', 'Bentang Pustaka', 2005, 'Fiksi', 14),
('BKF005', 'Rantau 1 Muara', 'Ahmad Fuadi', 'Gramedia Pustaka Utama', 2013, 'Fiksi', 6),
('BKF006', 'Bumi', 'Tere Liye', 'Gramedia Pustaka Utama', 2014, 'Fiksi', 13),
('BKF007', 'Bulan', 'Tere Liye', 'Gramedia Pustaka Utama', 2015, 'Fiksi', 12),
('BKF008', 'Matahari', 'Tere Liye', 'Gramedia Pustaka Utama', 2016, 'Fiksi', 11),
('BKF009', 'Bintang', 'Tere Liye', 'Gramedia Pustaka Utama', 2017, 'Fiksi', 9),
('BKF010', 'Hujan', 'Tere Liye', 'Gramedia Pustaka Utama', 2016, 'Fiksi', 8),
('BKF011', 'Pulang', 'Tere Liye', 'Gramedia Pustaka Utama', 2015, 'Fiksi', 7),
('BKF012', 'Pergi', 'Tere Liye', 'Gramedia Pustaka Utama', 2018, 'Fiksi', 10),
('BKF013', 'Segala-galanya Ambyar', 'Mark Manson', 'Gramedia Pustaka Utama', 2023, 'Fiksi', 10),
('BKF014', 'Sebuah Seni untuk Bersikap Bodo Amat', 'Mark Manson', 'Gramedia Pustaka Utama', 2023, 'Fiksi', 9),
('BKF015', 'Guru Aini', 'Andrea Hirata', 'Bentang Pustaka', 2023, 'Fiksi', 11),
('BKF016', 'Metropop: The Architecture of Love', 'Ika Natassa', 'Gramedia Pustaka Utama', 2021, 'Fiksi', 7),
('BKF017', 'Catatan Kronik', 'Natasha Rizky', 'Gramedia Pustaka Utama', 2023, 'Fiksi', 6),
('BKF018', 'Senja di Pelupuk Mata', 'Adhitya Mulya', 'Gramedia Pustaka Utama', 2023, 'Fiksi', 8),
('BKF019', 'Jejak Hujan', 'Ayu Utami', 'Gramedia Pustaka Utama', 2023, 'Fiksi', 9),
('BKF020', 'Aroma Karsa', 'Dee Lestari', 'Bentang Pustaka', 2023, 'Fiksi', 10),
('BKF021', 'Buku Harian Nadira', 'Ika Natassa', 'Gramedia Pustaka Utama', 2023, 'Fiksi', 7),
('BKF022', 'Layangan Putus', 'Mommy ASF', 'Gramedia Pustaka Utama', 2023, 'Fiksi', 8),
('BKF023', 'Kita Pergi Hari Ini', 'Ilana Tan', 'Gramedia Pustaka Utama', 2023, 'Fiksi', 9),
('BKF024', 'Namaku Alam', 'Leila S. Chudori', 'Gramedia Pustaka Utama', 2023, 'Fiksi', 6),
('BKF025', 'Segala yang Diisap Langit', 'Pinto Anugerah', 'Gramedia Pustaka Utama', 2023, 'Fiksi', 7),
('BKF026', 'Terlalu Tampan', 'Raditya Dika', 'Gagas Media', 2023, 'Komedi', 10),
('BKF027', 'Timun Jelita Volume 2', 'Tere Liye', 'Gramedia Pustaka Utama', 2025, 'Fiksi', 12),
('BKF028', 'Saudade', 'Tere Liye', 'Gramedia Pustaka Utama', 2025, 'Fiksi', 11),
('BKF029', 'Santri Pilihan Bunda', 'N/A', 'Gramedia Pustaka Utama', 2025, 'Religi', 9),
('BKF030', 'Teruslah Bodoh Jangan Pintar', 'N/A', 'Gramedia Pustaka Utama', 2025, 'Fiksi', 8),
('BKF031', 'Rumah untuk Alie', 'N/A', 'Gramedia Pustaka Utama', 2025, 'Fiksi', 10),
('BKF032', 'Sampaikanlah Walau Satu Konten', 'N/A', 'Gramedia Pustaka Utama', 2025, 'Edukasi', 7),
('BKF033', 'The Star And I', 'N/A', 'Gramedia Pustaka Utama', 2023, 'Fiksi', 9),
('BKF034', 'Home Sweet Loan', 'N/A', 'Gramedia Pustaka Utama', 2023, 'Fiksi', 8),
('BKF035', 'Gadis Kretek', 'N/A', 'Gramedia Pustaka Utama', 2023, 'Fiksi', 10),
('BKF036', 'The Seven Husbands of Evelyn Hugo', 'Taylor Jenkins Reid', 'Gramedia Pustaka Utama', 2023, 'Fiksi', 9),
('BKF037', 'Dona Dona', 'N/A', 'Gramedia Pustaka Utama', 2023, 'Fiksi', 7),
('BKF038', 'Yellow Face', 'R.F. Kuang', 'Gramedia Pustaka Utama', 2023, 'Fiksi', 8),
('BKF039', 'The Housemaid\'s Secret', 'Freida McFadden', 'Gramedia Pustaka Utama', 2023, 'Fiksi', 9),
('BKFB001', 'Dongeng Si Kancil', 'MB. Rahimsyah AR', 'Lingkar Media', 2013, 'Fabel', 7),
('BKK001', 'Koala Kumal', 'Raditya Dika', 'Gagas Media', 2015, 'Komedi', 10),
('BKK002', 'Cinta Brontosaurus', 'Raditya Dika', 'Gagas Media', 2011, 'Komedi', 9),
('BKK003', 'Manusia Setengah Salmon', 'Raditya Dika', 'Gagas Media', 2011, 'Komedi', 7),
('BKK004', 'Marmut Merah Jambu', 'Raditya Dika', 'Gagas Media', 2010, 'Komedi', 10),
('BKK005', 'Kambing Jantan', 'Raditya Dika', 'Gagas Media', 2005, 'Komedi', 5),
('BKKR001', 'Saya Pengen Jadi Creative Director', 'Budiman Hakim', 'Galangpress', 2016, 'Karier', 7),
('BKM001', 'Cewek Smart', 'Ria Fariana', 'Gema Insani', 2008, 'Motivasi', 11),
('BKNF001', 'Remaja Membangun Kepribadian', 'Anna Windyartini', 'Nobel Edumedia', 2008, 'Nonfiksi', 6),
('BKNF002', 'Atomic Habits', 'James Clear', 'Gramedia Pustaka Utama', 2023, 'Nonfiksi', 12),
('BKNF003', 'How to Win Friends and Influence People', 'Dale Carnegie', 'Gramedia Pustaka Utama', 2023, 'Nonfiksi', 8),
('BKNF004', 'Rich Dad, Poor Dad', 'Robert T. Kiyosaki', 'Gramedia Pustaka Utama', 2023, 'Nonfiksi', 10),
('BKPD001', 'Pengantar Filsafat Pendidikan', 'Drs. Uyoh Sadulloh', 'Alfabeta', 2004, 'Pendidikan', 13),
('BKPT001', '5 CM', 'Donny Dhirgantoro', 'Grasindo', 2005, 'Petualangan', 8),
('BKR001', 'Ayat-Ayat Cinta', 'Habiburrahman El Shirazy', 'Republika', 2004, 'Religi', 12),
('BKS001', 'Memahami Film', 'Himawan Pratista', 'Homerian Pustaka', 2008, 'Seni', 4);

-- --------------------------------------------------------

--
-- Table structure for table `tb_peminjaman`
--

CREATE TABLE `tb_peminjaman` (
  `no_peminjaman` varchar(10) NOT NULL,
  `nis` varchar(10) DEFAULT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `id_buku` varchar(20) DEFAULT NULL,
  `judul_buku` varchar(100) DEFAULT NULL,
  `tgl_pinjam` date DEFAULT NULL,
  `tgl_kembali` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_peminjaman`
--

INSERT INTO `tb_peminjaman` (`no_peminjaman`, `nis`, `nama`, `id_buku`, `judul_buku`, `tgl_pinjam`, `tgl_kembali`) VALUES
('PB001', '10005', 'Eka Putri', 'BKF001', 'Laut Bercerita', '2025-02-18', '2025-02-25'),
('PB002', '10002', 'Budi Santoso', 'BKF010', 'Hujan', '2025-02-25', '2025-03-04'),
('PB003', '10030', 'Dimas Saputra', 'BKK002', 'Cinta Brontosaurus', '2025-03-04', '2025-03-11'),
('PB004', '10010', 'Joko Susilo', 'BKB001', 'Habibie & Ainun', '2025-03-17', '2025-03-24'),
('PB005', '10004', 'Dedi Prasetyo', 'BKF010', 'Hujan', '2025-03-31', '2025-04-07'),
('PB006', '10006', 'Fajar Nugroho', 'BKPT001', '5 CM', '2025-04-11', '2025-04-18'),
('PB007', '10004', 'Dedi Prasetyo', 'BKA001', 'Metodologi Penelitian', '2025-05-18', '2025-05-25'),
('PB008', '10024', 'Xenia Putra', 'BKNF001', 'Remaja Membangun Kepribadian', '2025-05-19', '2025-05-26');

-- --------------------------------------------------------

--
-- Table structure for table `tb_pengembalian`
--

CREATE TABLE `tb_pengembalian` (
  `no_pengembalian` varchar(10) NOT NULL,
  `tgl_kembali` date NOT NULL,
  `tgl_dikembalikan` date NOT NULL,
  `no_peminjaman` varchar(10) DEFAULT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `id_buku` varchar(20) DEFAULT NULL,
  `judul_buku` varchar(100) DEFAULT NULL,
  `denda_per_hari` int(11) DEFAULT 500,
  `keterlambatan` int(11) DEFAULT 0,
  `total_denda` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_pengembalian`
--

INSERT INTO `tb_pengembalian` (`no_pengembalian`, `tgl_kembali`, `tgl_dikembalikan`, `no_peminjaman`, `nama`, `id_buku`, `judul_buku`, `denda_per_hari`, `keterlambatan`, `total_denda`) VALUES
('KBP001', '2025-02-25', '2025-02-26', 'PB001', 'Eka Putri', 'BKF001', 'Laut Bercerita', 500, 1, 500),
('KBP002', '2025-03-04', '2025-03-04', 'PB002', 'Budi Santoso', 'BKF010', 'Hujan', 500, 0, 0),
('KBP003', '2025-03-11', '2025-04-01', 'PB003', 'Dimas Saputra', 'BKK002', 'Cinta Brontosaurus', 500, 21, 10500),
('KBP004', '2025-03-24', '2025-03-25', 'PB004', 'Joko Susilo', 'BKB001', 'Habibie & Ainun', 500, 1, 500),
('KBP005', '2025-04-07', '2025-04-25', 'PB005', 'Dedi Prasetyo', 'BKF010', 'Hujan', 500, 18, 9000),
('KBP006', '2025-04-18', '2025-05-10', 'PB006', 'Fajar Nugroho', 'BKPT001', '5 CM', 500, 22, 11000),
('KBP007', '2025-05-25', '2025-05-25', 'PB007', 'Dedi Prasetyo', 'BKA001', 'Metodologi Penelitian', 500, 0, 0),
('KBP008', '2025-05-26', '2025-05-26', 'PB008', 'Xenia Putra', 'BKNF001', 'Remaja Membangun Kepribadian', 500, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tb_siswa`
--

CREATE TABLE `tb_siswa` (
  `nis` varchar(10) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `kelas` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_siswa`
--

INSERT INTO `tb_siswa` (`nis`, `nama`, `kelas`) VALUES
('10001', 'Ahmad Fauzi', '7A'),
('10002', 'Budi Santoso', '7A'),
('10003', 'Citra Lestari', '7A'),
('10004', 'Dedi Prasetyo', '7A'),
('10005', 'Eka Putri', '7A'),
('10006', 'Fajar Nugroho', '7A'),
('10007', 'Gita Sari', '7A'),
('10008', 'Hendra Wijaya', '7A'),
('10009', 'Intan Permata', '7A'),
('10010', 'Joko Susilo', '7A'),
('10011', 'Kartika Dewi', '7B'),
('10012', 'Lestari Ayu', '7B'),
('10013', 'Muhammad Rizky', '7B'),
('10014', 'Nurul Huda', '7B'),
('10015', 'Oki Setiawan', '7B'),
('10016', 'Putri Andini', '7B'),
('10017', 'Qori Maulana', '7B'),
('10018', 'Rina Kurniawati', '7B'),
('10019', 'Satria Pratama', '7B'),
('10020', 'Tania Rahma', '7B'),
('10021', 'Umar Faruq', '7C'),
('10022', 'Vina Melati', '7C'),
('10023', 'Wahyu Hidayat', '7C'),
('10024', 'Xenia Putra', '7C'),
('10025', 'Yulia Safitri', '7C'),
('10026', 'Zaki Ahmad', '7C'),
('10027', 'Anita Sari', '7C'),
('10028', 'Bambang Irawan', '7C'),
('10029', 'Cici Amelia', '7C'),
('10030', 'Dimas Saputra', '7C'),
('10031', 'Erlangga Putra', '7A'),
('10032', 'Fikri Maulana', '7A'),
('10033', 'Gilang Ramadhan', '7A'),
('10034', 'Hani Nurlela', '7A'),
('10035', 'Indra Saputra', '7B'),
('10036', 'Jihan Pratiwi', '7B'),
('10037', 'Kevin Ardian', '7B'),
('10038', 'Lina Marlina', '7B'),
('10039', 'Maya Sari', '7B'),
('10040', 'Nadia Putri', '7B'),
('10041', 'Omar Rizki', '7C'),
('10042', 'Putra Wijaya', '7C'),
('10043', 'Qiana Febri', '7C'),
('10044', 'Rendi Saputra', '7C'),
('10045', 'Sinta Dewi', '7C'),
('10046', 'Taufik Hidayat', '7C'),
('10047', 'Umi Salma', '7A'),
('10048', 'Vito Adi', '7A'),
('10049', 'Wulan Sari', '7A'),
('10050', 'Xavier Pratama', '7A'),
('10051', 'Yanti Lestari', '7B'),
('10052', 'Zulfikar Ahmad', '7B'),
('10053', 'Alfian Rahman', '7B'),
('10054', 'Bella Novita', '7B'),
('10055', 'Cahyo Nugroho', '7C'),
('10056', 'Dewi Anggraini', '7C'),
('10057', 'Eko Prasetyo', '7C'),
('10058', 'Fani Rahayu', '7C'),
('10059', 'Galih Setiawan', '7C'),
('10060', 'Hilda Sari', '7C');

-- --------------------------------------------------------

--
-- Table structure for table `tb_user`
--

CREATE TABLE `tb_user` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `nama` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_user`
--

INSERT INTO `tb_user` (`id`, `username`, `password`, `nama`) VALUES
(1, 'Admin', '#450074', 'Ris Naia Natasya');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_buku`
--
ALTER TABLE `tb_buku`
  ADD PRIMARY KEY (`id_buku`);

--
-- Indexes for table `tb_peminjaman`
--
ALTER TABLE `tb_peminjaman`
  ADD PRIMARY KEY (`no_peminjaman`);

--
-- Indexes for table `tb_pengembalian`
--
ALTER TABLE `tb_pengembalian`
  ADD PRIMARY KEY (`no_pengembalian`),
  ADD KEY `no_peminjaman` (`no_peminjaman`),
  ADD KEY `id_buku` (`id_buku`);

--
-- Indexes for table `tb_siswa`
--
ALTER TABLE `tb_siswa`
  ADD PRIMARY KEY (`nis`);

--
-- Indexes for table `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_pengembalian`
--
ALTER TABLE `tb_pengembalian`
  ADD CONSTRAINT `tb_pengembalian_ibfk_1` FOREIGN KEY (`no_peminjaman`) REFERENCES `tb_peminjaman` (`no_peminjaman`),
  ADD CONSTRAINT `tb_pengembalian_ibfk_2` FOREIGN KEY (`id_buku`) REFERENCES `tb_buku` (`id_buku`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
