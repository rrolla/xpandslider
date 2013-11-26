-- phpMyAdmin SQL Dump
-- version 4.0.5deb1.precise~ppa.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 25, 2013 at 10:11 AM
-- Server version: 5.5.34-0ubuntu0.12.04.1
-- PHP Version: 5.3.10-1ubuntu3.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `edgars`
--

-- --------------------------------------------------------

--
-- Table structure for table `edgars_xpand_slider`
--

CREATE TABLE IF NOT EXISTS `edgars_xpand_slider` (
  `xpand_slider_id` int(11) NOT NULL AUTO_INCREMENT,
  `xpand_slider_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `xpand_slider_content` text COLLATE utf8_unicode_ci NOT NULL,
  `xpand_slider_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `xpand_slider_imgs` text COLLATE utf8_unicode_ci NOT NULL,
  `xpand_slider_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `xpand_slider_updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `xpand_slider_opts` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `xpand_slider_class` int(11) NOT NULL,
  `xpand_slider_order` int(11) NOT NULL,
  PRIMARY KEY (`xpand_slider_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
