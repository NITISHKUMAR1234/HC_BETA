-- phpMyAdmin SQL Dump
-- version 4.9.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 18, 2023 at 05:24 PM
-- Server version: 5.6.51
-- PHP Version: 7.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `simusic_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `sitemap`
--

CREATE TABLE `tb_sitemap` (
  `id` int(10) NOT NULL,
  `lastmod` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `changefreq` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `priority` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `limits` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `datemode` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'D',
  `pingmode` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `timezone` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tb_sitemap`
--

INSERT INTO `tb_sitemap` (`id`, `lastmod`, `changefreq`, `priority`, `limits`, `datemode`, `type`, `pingmode`, `timezone`) VALUES
(1, 'ON', 'daily', '0.8', '6000', '', '', '', 'Asia/Kolkata');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_sitemap`
--
ALTER TABLE `tb_sitemap`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_sitemap`
--
ALTER TABLE `tb_sitemap`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
