-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 30, 2019 at 03:47 PM
-- Server version: 10.4.10-MariaDB
-- PHP Version: 7.3.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `peiling4u`
--

-- --------------------------------------------------------

--
-- Table structure for table `antwoorden`
--

CREATE TABLE `antwoorden` (
  `peilingnr` int(11) NOT NULL,
  `vraagnr` int(11) NOT NULL,
  `antwoordnr` int(11) NOT NULL,
  `antwoord` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `gebruikers`
--

CREATE TABLE `gebruikers` (
  `gebruikersnr` int(11) NOT NULL,
  `gebruikersnaam` varchar(20) NOT NULL,
  `wachtwoord` varchar(60) NOT NULL,
  `email` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `peilingen`
--

CREATE TABLE `peilingen` (
  `peilingnr` int(11) NOT NULL,
  `gebruikersnr` int(11) NOT NULL,
  `titel` varchar(30) NOT NULL,
  `openbaar` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `resultaten`
--

CREATE TABLE `resultaten` (
  `peilingnr` int(11) NOT NULL,
  `vraagnr` int(11) NOT NULL,
  `gebruikersnr` int(11) NOT NULL,
  `antwoord` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vragen`
--

CREATE TABLE `vragen` (
  `peilingnr` int(11) NOT NULL,
  `vraagnr` int(11) NOT NULL,
  `vraag` text DEFAULT NULL,
  `meerdere_antwoorden` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `antwoorden`
--
ALTER TABLE `antwoorden`
  ADD PRIMARY KEY (`peilingnr`,`vraagnr`,`antwoordnr`);

--
-- Indexes for table `gebruikers`
--
ALTER TABLE `gebruikers`
  ADD PRIMARY KEY (`gebruikersnr`);

--
-- Indexes for table `peilingen`
--
ALTER TABLE `peilingen`
  ADD PRIMARY KEY (`peilingnr`),
  ADD KEY `gebruikersnr` (`gebruikersnr`);

--
-- Indexes for table `resultaten`
--
ALTER TABLE `resultaten`
  ADD KEY `peilingnr` (`peilingnr`,`vraagnr`),
  ADD KEY `gebruikersnr` (`gebruikersnr`);

--
-- Indexes for table `vragen`
--
ALTER TABLE `vragen`
  ADD PRIMARY KEY (`peilingnr`,`vraagnr`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `gebruikers`
--
ALTER TABLE `gebruikers`
  MODIFY `gebruikersnr` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `peilingen`
--
ALTER TABLE `peilingen`
  MODIFY `peilingnr` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `antwoorden`
--
ALTER TABLE `antwoorden`
  ADD CONSTRAINT `antwoorden_ibfk_1` FOREIGN KEY (`peilingnr`,`vraagnr`) REFERENCES `vragen` (`peilingnr`, `vraagnr`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `peilingen`
--
ALTER TABLE `peilingen`
  ADD CONSTRAINT `peilingen_ibfk_1` FOREIGN KEY (`gebruikersnr`) REFERENCES `gebruikers` (`gebruikersnr`);

--
-- Constraints for table `resultaten`
--
ALTER TABLE `resultaten`
  ADD CONSTRAINT `resultaten_ibfk_1` FOREIGN KEY (`peilingnr`,`vraagnr`) REFERENCES `vragen` (`peilingnr`, `vraagnr`),
  ADD CONSTRAINT `resultaten_ibfk_2` FOREIGN KEY (`gebruikersnr`) REFERENCES `gebruikers` (`gebruikersnr`);

--
-- Constraints for table `vragen`
--
ALTER TABLE `vragen`
  ADD CONSTRAINT `vragen_ibfk_1` FOREIGN KEY (`peilingnr`) REFERENCES `peilingen` (`peilingnr`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
