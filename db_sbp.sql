-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 22, 2024 at 11:12 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sbp`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `iduser` varchar(20) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  `nama` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`iduser`, `username`, `password`, `nama`) VALUES
('U001', 'admin', 'admin', 'admin'),
('12', 'edojahat123', 'edojahat123', 'edo');

-- --------------------------------------------------------

--
-- Table structure for table `basispengetahuan`
--

CREATE TABLE `basispengetahuan` (
  `namakerusakan` varchar(100) NOT NULL,
  `gejala` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `basispengetahuan`
--

INSERT INTO `basispengetahuan` (`namakerusakan`, `gejala`) VALUES
('Kerusakan', 'smarthphone pernah jatuh'),
('kamera tidak berfungsi', 'smarthphone pernah jatuh'),
('kamera tidak berfungsi', 'smarthphone pernah jatuh'),
('Android Lambat', 'Hp sakit'),
('kamera tertawa', 'Hp sakit'),
('kamera tertawa', 'Mual'),
('Kamera Rusak', 'Apakah Smartphone Pernah Jatuh ?'),
('Kamera Rusak', 'Apakah Smartphone Pernah Kemasukan Air ?'),
('Kamera Rusak', 'Apakah Handphone terserang virus ?'),
('Boot Restart', 'Apakah Handphone terserang virus ?'),
('Boot Restart', 'Apakah terdapat File Sampah yang menumpuk pada sis'),
('Boot Restart', 'Apakah kinerja CPU, Memori RAM dan GPU Overload  ?'),
('Boot Restart', 'Apakah anda menginstal aplikasi yang masih beta di'),
('Boot Restart', 'Apakah Smartphone Anda mengalami panas berlebih ?'),
('Tidak bisa Charger', 'Apakah Kabel usb rusak ?'),
('Tidak bisa Charger', 'Apakah Adapter charger rusak ?'),
('Tidak bisa Charger', 'Apakah port usb rusak ?'),
('Hardbrick', 'Apakah Smartphone Pernah Kemasukan Air ?'),
('Hardbrick', 'Apakah Anda memodifikasi Sistem ?'),
('Hardbrick', 'Apakah terjadi Kegagal saat install software ?'),
('Lcd Rusak', 'Apakah layar Android terdapat shadow ?'),
('Lcd Rusak', 'Apakah layar sentuh tidak Sensitif'),
('Battery Rusak', 'Apakah battery cepat habis ?'),
('Lcd Rusak', 'Apakah layar smartphone terdapat goresan ?'),
('Sim Card Rusak', 'Kuningan pada SIM CARD rusak'),
('Sim Card Rusak', 'Slot Card Rusak'),
('Sim Card Rusak', 'Smartphone Pernah Kemasukan Air'),
('Sistem Operasi Rusak ', 'Sinyal Hilang'),
('Sistem Operasi Rusak ', 'Saat melakukan panggilan, suara tidak ada'),
('Bootloop', 'Kinerja CPU, Memori RAM dan GPU Overload'),
('Bootloop', 'Pernah memodifikasi Sistem'),
('Bootloop', 'Melakukan Overlock Android'),
('Kerusakan LCD', 'LCD Blank   '),
('Kerusakan LCD', 'Layar Buram'),
('Kerusakan LCD', 'Layar Tidak Responsif'),
('Kerusakan Processor', 'Mati total'),
('Kerusakan Processor', 'Memori Bermasalah '),
('Simcard Rusak', 'Kuningan pada simcard rusak  '),
('Simcard Rusak', 'Slotcard rusak  '),
('Simcard Rusak', 'Smartphone pernah kemasukan air  '),
('IC charger rusak', 'Tidak bisa Charge  '),
('IC charger rusak', 'Status Charging error'),
('Power Supply Rusak', 'Mati total'),
('Power Supply Rusak', 'Tidak bisa Charge  '),
('Power Supply Rusak', 'Baterai over heat  '),
('Power Supply Rusak', 'Baterai Drop');

-- --------------------------------------------------------

--
-- Table structure for table `gejala`
--

CREATE TABLE `gejala` (
  `idgejala` varchar(10) NOT NULL,
  `gejala` varchar(1000) NOT NULL,
  `jenishp` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `gejala`
--

INSERT INTO `gejala` (`idgejala`, `gejala`, `jenishp`) VALUES
('C01', 'Sinyal Hilang', 'Android'),
('C02', 'Saat melakukan panggilan, suara tidak ada', 'Android'),
('C03', 'Kinerja CPU, Memori RAM dan GPU Overload', 'Android'),
('C04', 'Pernah memodifikasi Sistem', 'Android'),
('C05', 'Melakukan Overlock Android', 'Android'),
('C06', 'LCD Blank   ', 'Android'),
('C07', 'Layar Buram', 'Android'),
('C08', 'Layar Tidak Responsif', 'Android'),
('C09', 'Mati total', 'Android'),
('C10', 'Memori Bermasalah ', 'Android'),
('C11', 'Kuningan pada simcard rusak  ', 'Android'),
('C12', 'Slotcard rusak  ', 'Android'),
('C13', 'Smartphone pernah kemasukan air  ', 'Android'),
('C14', 'Tidak bisa Charge  ', 'Android'),
('C15', 'Status Charging error', 'Android'),
('C16', 'Baterai over heat  ', 'Android'),
('C17', 'Baterai Drop', 'Android');

-- --------------------------------------------------------

--
-- Table structure for table `kerusakan`
--

CREATE TABLE `kerusakan` (
  `idkerusakan` varchar(20) NOT NULL,
  `namakerusakan` varchar(1000) NOT NULL,
  `jenishp` varchar(20) NOT NULL,
  `caramengatasi` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kerusakan`
--

INSERT INTO `kerusakan` (`idkerusakan`, `namakerusakan`, `jenishp`, `caramengatasi`) VALUES
('K1', 'Sistem Operasi Rusak ', 'Android', '1.Reset factory dari recovery mode \r\n2.wipe cache\r\n3.wipe dalvik cache\r\n4.wipe data\r\n5.Flashing ulang\r\n'),
('K2', 'Bootloop', 'Android', '1.Flash Ulang\r\n2.Bila parah ganti EMMC\r\n\r\n'),
('K3', 'Kerusakan LCD', 'Android', '1.Check kabel flexi\r\n2.ganti lcd\r\n\r\n'),
('K4', 'Kerusakan Processor', 'Android', '1. Cek ic power\r\n2. Cek Memory\r\n3.Cek Processor\r\n4.Cek blok radio dan blok ui.\r\n'),
('K5', 'Simcard Rusak', 'Android', '1.Reboot Smartphone\r\n2.Pastikan SIM CARD terpasang dengan benar\r\n3.Gunakan Mode manual jaringan\r\n4.periksa Kondisi SIM CARD\r\n5.Ganti SIM CARD\r\n6.Lepas dan pasang kemnali SIM CARD\r\n7.Tes SIM CARD pada Smartphone lain\r\n8.bersihkan Cahce dan data di Android\r\n9.Pindah SLOT SIM CARD\r\n10.Merestart Jaringan\r\n11.Update Android\r\n12.Factory Reset\r\n\r\n'),
('K6', 'IC charger rusak', 'Android', '1.	Cek Fuse dan IC (Control Carging)\r\n2.	Cek tegangan charger\r\n3.	Cek charger dan IC Charger\r\n'),
('K7', 'Power Supply Rusak', 'Android', '1. Cek konektor baterai\r\n2.Cek Baterai\r\n3.Cek IC PA\r\n4.Cek konektor SIM\r\n5.Cek IC Power dan IC Charge\r\n');

-- --------------------------------------------------------

--
-- Table structure for table `repair_history`
--

CREATE TABLE `repair_history` (
  `id` int NOT NULL,
  `date` date NOT NULL,
  `brand` varchar(255) NOT NULL,
  `device_type` varchar(255) NOT NULL,
  `symptoms` text NOT NULL,
  `diagnosis` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `smartphones`
--

CREATE TABLE `smartphones` (
  `id` int NOT NULL,
  `brand` varchar(255) NOT NULL,
  `model` varchar(255) NOT NULL,
  `imei` varchar(255) NOT NULL,
  `jenishp` varchar(255) NOT NULL,
  `idkerusakan` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `gejala`
--
ALTER TABLE `gejala`
  ADD PRIMARY KEY (`idgejala`);

--
-- Indexes for table `kerusakan`
--
ALTER TABLE `kerusakan`
  ADD PRIMARY KEY (`idkerusakan`);

--
-- Indexes for table `repair_history`
--
ALTER TABLE `repair_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `smartphones`
--
ALTER TABLE `smartphones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_idkerusakan` (`idkerusakan`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `repair_history`
--
ALTER TABLE `repair_history`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `smartphones`
--
ALTER TABLE `smartphones`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `smartphones`
--
ALTER TABLE `smartphones`
  ADD CONSTRAINT `fk_idkerusakan` FOREIGN KEY (`idkerusakan`) REFERENCES `kerusakan` (`idkerusakan`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
