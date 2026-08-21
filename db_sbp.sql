-- phpMyAdmin SQL Dump
-- Database: `sbp`
-- Sistem Pakar Diagnosa Kerusakan Smartphone (Kumaphone)
-- --------------------------------------------------------

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
CREATE DATABASE IF NOT EXISTS `sbp` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `sbp`;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `smartphones`;
DROP TABLE IF EXISTS `basispengetahuan`;
DROP TABLE IF EXISTS `gejala`;
DROP TABLE IF EXISTS `kerusakan`;
DROP TABLE IF EXISTS `repair_history`;
DROP TABLE IF EXISTS `admin`;

CREATE TABLE `admin` (
  `iduser` varchar(20) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama` varchar(50) NOT NULL,
  PRIMARY KEY (`iduser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`iduser`, `username`, `password`, `nama`) VALUES
('U001', 'admin', 'admin', 'Administrator'),
('U002', 'edojahat123', 'edojahat123', 'Edo Kurniawan'),
('U003', 'teknisi', 'teknisi123', 'Budi Santoso (Teknisi)'),
('U004', 'supervisor', 'super123', 'Dewi Lestari');

-- --------------------------------------------------------

--
-- Table structure for table `kerusakan`
--

CREATE TABLE `kerusakan` (
  `idkerusakan` varchar(20) NOT NULL,
  `namakerusakan` varchar(255) NOT NULL,
  `jenishp` varchar(50) NOT NULL,
  `caramengatasi` text NOT NULL,
  PRIMARY KEY (`idkerusakan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `kerusakan`
--

INSERT INTO `kerusakan` (`idkerusakan`, `namakerusakan`, `jenishp`, `caramengatasi`) VALUES
('K1', 'Sistem Operasi Rusak', 'Android', '1. Masuk ke Recovery Mode lalu lakukan Factory Reset\r\n2. Lakukan Wipe Cache Partition\r\n3. Wipe Dalvik Cache\r\n4. Lakukan Flashing ulang ROM Official via PC'),
('K2', 'Bootloop', 'Android', '1. Lakukan Wipe Data & Cache via Recovery Mode\r\n2. Flash ulang Firmware/Stock ROM dengan software resmi\r\n3. Jika tetap bootloop, periksa atau ganti chip eMMC / UFS'),
('K3', 'Kerusakan LCD', 'Android', '1. Periksa konektor dan kabel flexible LCD dari korosi / longgar\r\n2. Bersihkan soket LCD\r\n3. Lakukan pergantian modul LCD Fullset jika retak/blank/shadow'),
('K4', 'Kerusakan Processor', 'Android', '1. Periksa tegangan output IC Power\r\n2. Cek jalur RAM dan Processor menggunakan multimeter\r\n3. Lakukan proses Reballing CPU / IC Power\r\n4. Ganti motherboard jika SoC rusak total'),
('K5', 'Simcard Rusak', 'Android', '1. Bersihkan kuningan SIM Card menggunakan penghapus karet\r\n2. Periksa pin konektor pada tray / slot SIM Card\r\n3. Ganti modul slot SIM Card jika pin patah\r\n4. Periksa IC Baseband / RF jika sinyal tetap tidak muncul'),
('K6', 'IC Charger Rusak', 'Android', '1. Periksa port USB Type-C / Micro USB dari kotoran atau pin bengkok\r\n2. Ukur tegangan masuk VBUS (5V - 9V) menuju modul charging\r\n3. Ganti board sub-charger fleksibel\r\n4. Ganti IC Charging / PMIC jika tegangan tidak masuk ke baterai'),
('K7', 'Power Supply Rusak', 'Android', '1. Ukur tegangan baterai (minimal 3.7V)\r\n2. Cek apakah terjadi short-circuit pada jalur VPH_PWR / VBAT menggunakan power supply digital\r\n3. Bersihkan korosi pada kapasitor jalur utama\r\n4. Ganti IC Power utama'),
('K8', 'Kerusakan Kamera', 'Android', '1. Bersihkan modul lensa kamera dari debu/jamur\r\n2. Periksa soket dan fleksibel kamera ke motherboard\r\n3. Hapus cache aplikasi kamera atau factory reset\r\n4. Ganti modul kamera belakang / depan yang rusak'),
('K9', 'Kerusakan Speaker / Audio', 'Android', '1. Bersihkan grill speaker dari kotoran dan debu\r\n2. Periksa jalur buzzer speaker dengan multitester\r\n3. Ganti modul loudspeaker / ear-piece speaker\r\n4. Periksa IC Audio Codec jika tidak ada suara sama sekali');

-- --------------------------------------------------------

--
-- Table structure for table `gejala`
--

CREATE TABLE `gejala` (
  `idgejala` varchar(10) NOT NULL,
  `gejala` varchar(255) NOT NULL,
  `jenishp` varchar(50) NOT NULL,
  PRIMARY KEY (`idgejala`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `gejala`
--

INSERT INTO `gejala` (`idgejala`, `gejala`, `jenishp`) VALUES
('C01', 'Sinyal Hilang', 'Android'),
('C02', 'Saat melakukan panggilan, suara tidak ada', 'Android'),
('C03', 'Kinerja CPU, Memori RAM dan GPU Overload', 'Android'),
('C04', 'Pernah memodifikasi Sistem', 'Android'),
('C05', 'Melakukan Overlock Android', 'Android'),
('C06', 'LCD Blank', 'Android'),
('C07', 'Layar Buram', 'Android'),
('C08', 'Layar Tidak Responsif', 'Android'),
('C09', 'Mati total', 'Android'),
('C10', 'Memori Bermasalah', 'Android'),
('C11', 'Kuningan pada simcard rusak', 'Android'),
('C12', 'Slotcard rusak', 'Android'),
('C13', 'Smartphone pernah kemasukan air', 'Android'),
('C14', 'Tidak bisa Charge', 'Android'),
('C15', 'Status Charging error', 'Android'),
('C16', 'Baterai over heat', 'Android'),
('C17', 'Baterai Drop', 'Android'),
('C18', 'Kamera Buram atau Gelap', 'Android'),
('C19', 'Aplikasi Kamera Keluar Sendiri / Force Close', 'Android'),
('C20', 'Suara Speaker Pecah atau Tidak Keluar', 'Android');

-- --------------------------------------------------------

--
-- Table structure for table `basispengetahuan`
--

CREATE TABLE `basispengetahuan` (
  `namakerusakan` varchar(255) NOT NULL,
  `gejala` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `basispengetahuan`
--

INSERT INTO `basispengetahuan` (`namakerusakan`, `gejala`) VALUES
('Sistem Operasi Rusak', 'Sinyal Hilang'),
('Sistem Operasi Rusak', 'Saat melakukan panggilan, suara tidak ada'),
('Bootloop', 'Kinerja CPU, Memori RAM dan GPU Overload'),
('Bootloop', 'Pernah memodifikasi Sistem'),
('Bootloop', 'Melakukan Overlock Android'),
('Kerusakan LCD', 'LCD Blank'),
('Kerusakan LCD', 'Layar Buram'),
('Kerusakan LCD', 'Layar Tidak Responsif'),
('Kerusakan Processor', 'Mati total'),
('Kerusakan Processor', 'Memori Bermasalah'),
('Simcard Rusak', 'Kuningan pada simcard rusak'),
('Simcard Rusak', 'Slotcard rusak'),
('Simcard Rusak', 'Smartphone pernah kemasukan air'),
('IC Charger Rusak', 'Tidak bisa Charge'),
('IC Charger Rusak', 'Status Charging error'),
('Power Supply Rusak', 'Mati total'),
('Power Supply Rusak', 'Tidak bisa Charge'),
('Power Supply Rusak', 'Baterai over heat'),
('Power Supply Rusak', 'Baterai Drop'),
('Kerusakan Kamera', 'Kamera Buram atau Gelap'),
('Kerusakan Kamera', 'Aplikasi Kamera Keluar Sendiri / Force Close'),
('Kerusakan Speaker / Audio', 'Suara Speaker Pecah atau Tidak Keluar'),
('Kerusakan Speaker / Audio', 'Saat melakukan panggilan, suara tidak ada');

-- --------------------------------------------------------

--
-- Table structure for table `smartphones`
--

CREATE TABLE `smartphones` (
  `id` int NOT NULL AUTO_INCREMENT,
  `no_registrasi` varchar(30) DEFAULT NULL,
  `brand` varchar(255) NOT NULL,
  `model` varchar(255) NOT NULL,
  `imei` varchar(255) NOT NULL,
  `jenishp` varchar(255) NOT NULL,
  `idkerusakan` varchar(20) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_no_registrasi` (`no_registrasi`),
  KEY `fk_idkerusakan` (`idkerusakan`),
  CONSTRAINT `fk_idkerusakan` FOREIGN KEY (`idkerusakan`) REFERENCES `kerusakan` (`idkerusakan`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `smartphones` (Dummy Data Riwayat Pelanggan)
--

INSERT INTO `smartphones` (`id`, `no_registrasi`, `brand`, `model`, `imei`, `jenishp`, `idkerusakan`, `created_at`) VALUES
(1, 'KMP-20240910-0001', 'Dimas Setiawan', 'Samsung Galaxy A52', '354892104829104', 'Android', 'K3', '2024-09-10 09:30:00'),
(2, 'KMP-20240915-0002', 'Siti Nurhaliza', 'Xiaomi Redmi Note 11 Pro', '862910492817401', 'Android', 'K2', '2024-09-15 11:15:00'),
(3, 'KMP-20240922-0003', 'Ahmad Fauzi', 'Oppo Reno 7 5G', '358291048172901', 'Android', 'K6', '2024-09-22 14:00:00'),
(4, 'KMP-20241001-0004', 'Rian Hidayat', 'Poco X3 Pro', '863920194827103', 'Android', 'K4', '2024-10-01 10:20:00'),
(5, 'KMP-20241018-0005', 'Nurul Aini', 'Vivo Y21s', '352910482719204', 'Android', 'K5', '2024-10-18 16:45:00'),
(6, 'KMP-20241025-0006', 'Bayu Pratama', 'Realme GT Master Edition', '861920482910482', 'Android', 'K7', '2024-10-25 13:10:00'),
(7, 'KMP-20241105-0007', 'Mega Utami', 'Samsung Galaxy S21 Ultra', '359102948201948', 'Android', 'K1', '2024-11-05 15:30:00'),
(8, 'KMP-20241112-0008', 'Hendro Wijaya', 'Infinix Note 12', '864920194820192', 'Android', 'K8', '2024-11-12 09:50:00'),
(9, 'KMP-20241120-0009', 'Fitri Handayani', 'Xiaomi Poco F4', '863019284910294', 'Android', 'K9', '2024-11-20 17:05:00'),
(10, 'KMP-20241201-0010', 'Fajar Nugraha', 'Oppo A74', '358192048102948', 'Android', 'K6', '2024-12-01 11:40:00');

-- --------------------------------------------------------

--
-- Table structure for table `repair_history`
--

CREATE TABLE `repair_history` (
  `id` int NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `brand` varchar(255) NOT NULL,
  `device_type` varchar(255) NOT NULL,
  `symptoms` text NOT NULL,
  `diagnosis` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `repair_history`
--

INSERT INTO `repair_history` (`id`, `date`, `brand`, `device_type`, `symptoms`, `diagnosis`) VALUES
(1, '2024-09-10', 'Samsung', 'Galaxy A52', 'Layar blank hitam, sentuhan tidak responsif', 'Kerusakan LCD'),
(2, '2024-09-15', 'Xiaomi', 'Redmi Note 11 Pro', 'Stuck di logo saat dinyalakan', 'Bootloop'),
(3, '2024-09-22', 'Oppo', 'Reno 7 5G', 'Tidak bisa dicas, indikator charger kedip-kedip', 'IC Charger Rusak'),
(4, '2024-10-01', 'Poco', 'X3 Pro', 'Mati total saat main game, tidak ada getar', 'Kerusakan Processor / Reball CPU'),
(5, '2024-10-18', 'Realme', 'GT Master', 'Baterai cepat panas dan mati mendadak', 'Power Supply Rusak');

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
