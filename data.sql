-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 30, 2024 at 10:26 AM
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
-- Database: `data`
--

-- --------------------------------------------------------

--
-- Table structure for table `it_8_datastructures_dsdjs_test3`
--

CREATE TABLE `it_8_datastructures_dsdjs_test3` (
  `id` int(11) NOT NULL,
  `Div` varchar(10) DEFAULT NULL,
  `RollNo` varchar(30) DEFAULT NULL,
  `SAPID` varchar(30) DEFAULT NULL,
  `Name` varchar(30) DEFAULT NULL,
  `Q1` int(3) DEFAULT NULL,
  `Q2` int(3) DEFAULT NULL,
  `Q3` int(3) DEFAULT NULL,
  `Q4` int(3) DEFAULT NULL,
  `Q5` int(3) DEFAULT NULL,
  `TotalMarks` int(3) DEFAULT NULL,
  `CO1` int(3) DEFAULT NULL,
  `CO2` int(3) DEFAULT NULL,
  `CO3` int(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `it_8_datastructures_dsdjs_test3`
--

INSERT INTO `it_8_datastructures_dsdjs_test3` (`id`, `Div`, `RollNo`, `SAPID`, `Name`, `Q1`, `Q2`, `Q3`, `Q4`, `Q5`, `TotalMarks`, `CO1`, `CO2`, `CO3`) VALUES
(1, 'I2', 'I065', '60003220264', 'MALI JANYA', 2, 1, 2, 3, 5, 13, 3, 5, 5),
(2, 'I2', 'I066', '60003220230', 'SUCHAK JASH', 2, 4, 2, 0, 5, 13, 6, 2, 5),
(3, 'I2', 'I067', '60003220072', 'PATEL JHEEL', 4, 4, 7, 2, 5, 22, 8, 9, 5),
(4, 'I2', 'I068', '60003220117', 'KARIA JIHAN', 4, 4, 6, 2, 5, 21, 8, 8, 5),
(5, 'I2', 'I069', '60003220290', 'GURIA JITESH', 2, 4, 5, 2, 5, 18, 6, 7, 5),
(6, 'I2', 'I070', '60003220239', 'PANDAV JUGAL', 2, 4, 2, 5, 5, 18, 6, 7, 5),
(7, 'I2', 'I071', '60003220060', 'SHAH KAIVAN', 3, 4, 0, 2, 5, 14, 7, 2, 5),
(8, 'I2', 'I072', '60003220283', 'RAUL KANCHAN', 3, 1, 7, 5, 5, 21, 4, 12, 5),
(9, 'I2', 'I073', '60003220250', 'MAHESHWARI KANISHK', 4, 4, 2, 5, 5, 20, 8, 7, 5),
(10, 'I2', 'I074', '60003220122', 'MAHETALIYA KASHVI', 3, 4, 6, 4, 5, 22, 7, 10, 5),
(11, 'I2', 'I075', '60003220173', 'RITA KASHVI', 2, 4, 6, 4, 5, 21, 6, 10, 5),
(12, 'I2', 'I076', '60003220272', 'JAIN KRISH', 3, 0, 7, 3, 5, 18, 3, 10, 5),
(13, 'I2', 'I077', '60003220149', 'CHAVDA KRISHNA', 4, 4, 6, 5, 5, 24, 8, 11, 5),
(14, 'I2', 'I078', '60003220093', 'CHAURASHIYA \nKRISHNAKUMAR', 2, 4, 2, 0, 5, 13, 6, 2, 5),
(15, 'I2', 'I079', '60003220165', 'PARMAR KRIYA', 3, 1, 7, 3, 5, 19, 4, 10, 5),
(16, 'I2', 'I080', '60003220294', 'CHOUDHARY KULDEEP', 4, 4, 6, 3, 5, 22, 8, 9, 5),
(17, 'I2', 'I081', '60003220243', 'CHAUHAN KUSHAGRA', 1, 4, 5, 4, 5, 19, 5, 9, 5),
(18, 'I2', 'I082', '60003220069', 'MEHTA KUSHAL', 2, 4, 5, 4, 5, 20, 6, 9, 5),
(19, 'I2', 'I083', '60003220247', 'RAMPURIA KUSHAL', 4, 2, 5, 4, 5, 20, 6, 9, 5),
(20, 'I2', 'I084', '60003220197', 'CHELLARAMANI LAKSHITAA', 3, 4, 1, 2, 5, 15, 7, 3, 5),
(21, 'I2', 'I085', '60003220104', 'JOSHI LAKSHYA', 4, 4, 7, 4, 5, 24, 8, 11, 5),
(22, 'I2', 'I086', '60003220238', 'VIRA MAHEK', 4, 4, 5, 5, 5, 23, 8, 10, 5),
(23, 'I2', 'I087', '60003220292', 'PATEL MAHEK', 4, 4, 7, 4, 5, 24, 8, 11, 5),
(24, 'I2', 'I088', '60003220274', 'DIVECHA MAITHILI', 3, 4, 7, 4, 5, 23, 7, 11, 5),
(25, 'I2', 'I089', '60003220076', 'GOGRI MALLANK', 3, 4, 7, 3, 5, 22, 7, 10, 5),
(26, 'I2', 'I090', '60003220279', 'DESAI MANAN', 3, 4, 4, 3, 5, 19, 7, 7, 5),
(27, 'I2', 'I091', '60003220275', 'BHATT MANAV', 2, 4, 7, 4, 5, 22, 6, 11, 5),
(28, 'I2', 'I092', '60003220170', 'GUPTA MANISHKUMAR', 3, 4, 7, 4, 5, 23, 7, 11, 5),
(29, 'I2', 'I093', '60003220231', 'SAPARIYA MANN', 1, 0, 2, 2, 5, 10, 1, 4, 5),
(30, 'I2', 'I094', '60003220106', 'PATEL MAYANK', 4, 4, 4, 2, 5, 19, 8, 6, 5),
(31, 'I2', 'I095', '60003220095', 'MEHTA MEER', 2, 3, 3, 5, 5, 18, 5, 8, 5),
(32, 'I2', 'I096', '60003220079', 'JAIN MEET', 3, 2, 7, 5, 5, 22, 5, 12, 5),
(33, 'I2', 'I097', '60003220232', 'KANABAR MEET', 3, 4, 6, 4, 5, 22, 7, 10, 5),
(34, 'I2', 'I098', '60003220041', 'PITHADIA MILIND', 2, 4, 7, 4, 5, 22, 6, 11, 5),
(35, 'I2', 'I099', '60003220282', 'SHAH MITUL', 4, 4, 7, 5, 5, 25, 8, 12, 5),
(36, 'I2', 'I100', '60003220031', 'PARKAR MOHAMMED', 4, 4, 4, 0, 5, 17, 8, 4, 5),
(37, 'I2', 'I101', '60003220273', 'SHAH MOKSHA', 2, 2, 5, 2, 5, 16, 4, 7, 5),
(38, 'I2', 'I102', '60003220144', 'SASTE MRINMAYEE', 3, 4, 6, 5, 5, 23, 7, 11, 5),
(39, 'I2', 'I103', '60003220141', 'SURANA NAMAN', 4, 4, 7, 3, 5, 23, 8, 10, 5),
(40, 'I2', 'I104', '60003220047', 'KASMANI NAWAZ', 2, 0, 2, 3, 5, 12, 2, 5, 5),
(41, 'I2', 'I105', '60003220126', 'KORADIA NEEL', 2, 4, 7, 5, 5, 23, 6, 12, 5),
(42, 'I2', 'I106', '60003220229', 'PAREKH NEEL', 2, 4, 0, 2, 5, 13, 6, 2, 5),
(43, 'I2', 'I107', '60003220260', 'RAHATE NEIL', 2, 4, 6, 3, 5, 20, 6, 9, 5),
(44, 'I2', 'I108', '60003220119', 'RAIKUNDLIYA NIKUNJ', 2, 4, 4, 0, 5, 15, 6, 4, 5),
(45, 'I2', 'I109', '60003220255', 'NAIK NIRANJAN', 2, 4, 7, 2, 5, 20, 6, 9, 5),
(46, 'I2', 'I110', '60003220174', 'SHAH NISARG', 3, 4, 5, 5, 5, 22, 7, 10, 5),
(47, 'I2', 'I111', '60003220206', 'BOKADIA NIVI', 2, 4, 7, 5, 5, 23, 6, 12, 5),
(48, 'I2', 'I112', '60003220148', 'RAWAL NUPUR', 4, 4, 6, 5, 5, 24, 8, 11, 5),
(49, 'I2', 'I113', '60003220261', 'GEET OJAS', 3, 4, 7, 2, 5, 21, 7, 9, 5),
(50, 'I2', 'I114', '60003220208', 'KULKARNI PRATHMESH', 3, 3, 6, 2, 5, 19, 6, 8, 5),
(51, 'I2', 'I115', '60003220257', 'BORKAR PRATYUSH', 3, 4, 6, 4, 5, 22, 7, 10, 5),
(52, 'I2', 'I116', '60003220070', 'DESAI PRISHA', 3, 1, 4, 2, 5, 15, 4, 6, 5),
(53, 'I2', 'I117', '60003220289', 'GUPTA PRIYA', 4, 0, 3, 4, 5, 16, 4, 7, 5),
(54, 'I2', 'I118', '60003220151', 'SHAH PRIYANSH', 3, 4, 0, 3, 5, 15, 7, 3, 5),
(55, 'I2', 'I119', '60003220091', 'SINGH PRIYANSHU', 2, 1, 2, 2, 5, 12, 3, 4, 5),
(56, 'I2', 'I120', '60003220258', 'TODI PULKIT', 3, 4, 1, 4, 5, 17, 7, 5, 5),
(57, 'I2', 'I121', '60003220219', 'GARG RACHIT', 1, 4, 3, 1, 5, 14, 5, 4, 5),
(58, 'I2', 'I122', '60003220127', 'PATEL RADHIKA', 3, 4, 4, 2, 5, 18, 7, 6, 5),
(59, 'I2', 'I123', '60003220159', 'DABHI RAHIL', 4, 3, 6, 2, 5, 20, 7, 8, 5),
(60, 'I2', 'I124', '60003220082', 'SHAH RAJ', 4, 4, 7, 1, 5, 21, 8, 8, 5),
(61, 'I2', 'I125', '60003220270', 'RIZVI RAJ', 3, 1, 3, 3, 5, 15, 4, 6, 5),
(62, 'I2', 'I126', '60003220040', 'JADEJA RAJDEEPSINH', 2, 4, 0, 4, 5, 15, 6, 4, 5),
(63, 'I2', 'I127', '60003220277', 'PALAN RAM', 0, 2, 0, 2, 5, 9, 2, 2, 5),
(64, 'I2', 'I128', '60003220221', 'GALA RIYA', 3, 3, 6, 4, 5, 21, 6, 10, 5),
(65, 'I2', 'I193', '60003230300', 'PATEL ISHA', 2, 4, 6, 1, 5, 18, 6, 7, 5),
(66, 'I2', 'I194', '60003230301', 'GOKHALE MOHIT', 3, 4, 2, 3, 5, 17, 7, 5, 5),
(67, 'I2', 'I199', '60003230306', 'PANCHAL MEET', 2, 4, 7, 5, 5, 23, 6, 12, 5),
(68, 'I2', 'I200', '60003230307', 'NAEEMAHMED ANSARI', 2, 1, 6, 3, 5, 17, 3, 9, 5),
(69, 'I2', 'I202', '60003230309', 'GUPTA VIPUL', 1, 1, 6, 1, 5, 14, 2, 7, 5),
(70, 'I2', 'I203', '60003230310', 'CHESANIYA NIRAV', 3, 4, 6, 5, 5, 23, 7, 11, 5);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `it_8_datastructures_dsdjs_test3`
--
ALTER TABLE `it_8_datastructures_dsdjs_test3`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `it_8_datastructures_dsdjs_test3`
--
ALTER TABLE `it_8_datastructures_dsdjs_test3`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
