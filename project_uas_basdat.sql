-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 26, 2024 at 11:33 AM
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
-- Database: `project_uas_basdat`
--

-- --------------------------------------------------------

--
-- Table structure for table `mahasiswa`
--

CREATE TABLE `mahasiswa` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `nim` varchar(20) NOT NULL,
  `alamat` text NOT NULL,
  `prodi` varchar(50) NOT NULL,
  `ukt` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mahasiswa`
--

INSERT INTO `mahasiswa` (`id`, `nama`, `nim`, `alamat`, `prodi`, `ukt`) VALUES
(1, 'Naufal Bisma', '1313623029', 'Jl. Mawar No. 12, Jakarta', 'Ilmu Komputer', 5000000),
(2, 'Syahrul Dwi', '1313623000', 'Jl. Anggrek No. 34, Bandung', 'Ilmu Komputer', 4500000),
(3, 'Hafizh Rifan', '1313623001', 'Jl. Melati No. 56, Yogyakarta', 'Ilmu Komputer', 5500000),
(4, 'dandy arya akbar', '1313623028', 'planet mars', 'Ilmu Komputer', 6000000),
(5, 'test test', '1313666666666', 'apa gek', 'ilmu komputer', 5500000),
(6, 'Test Test', '1313623929', 'planet mars', 'Ilmu Komputer', 1000000),
(7, 'Test outlier', '1319891849218938132', 'apa gek', 'Ilmu Komputer', 12000000),
(8, 'test test outlier upper', '13136230302031231', 'planet mars', 'Ilmu Komputer', 120000000),
(9, 'test test test test', '1313623028', 'apa gek', 'Ilmu Komputer', 20000000),
(11, 'Dalta Kahfi Kustiawan', '1313623037', 'test test', 'Ilmu Komputer', 100000),
(12, 'seorang mahasiswa', '1313623030', 'suatu alamat', 'teknik elektro', 3000000);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
