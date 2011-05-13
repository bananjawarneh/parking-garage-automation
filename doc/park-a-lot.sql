-- phpMyAdmin SQL Dump
-- version 3.3.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 13, 2011 at 08:01 AM
-- Server version: 5.1.54
-- PHP Version: 5.3.5-1ubuntu7.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `park-a-lot`
--

-- --------------------------------------------------------

--
-- Table structure for table `garage`
--

CREATE TABLE IF NOT EXISTS `garage` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parking_id` int(10) unsigned DEFAULT NULL,
  `level_num` tinyint(3) unsigned NOT NULL,
  `row_num` tinyint(3) unsigned NOT NULL,
  `col_num` int(11) NOT NULL,
  `license_plate` varchar(10) DEFAULT NULL,
  `state` char(2) DEFAULT NULL,
  `open` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `spot` (`level_num`,`row_num`,`col_num`),
  UNIQUE KEY `license_plate` (`license_plate`,`state`),
  KEY `open` (`open`),
  KEY `parking_id` (`parking_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=301 ;

--
-- Dumping data for table `garage`
--

INSERT INTO `garage` (`id`, `parking_id`, `level_num`, `row_num`, `col_num`, `license_plate`, `state`, `open`) VALUES
(1, NULL, 1, 1, 1, NULL, NULL, 1),
(2, NULL, 1, 1, 2, NULL, NULL, 1),
(3, NULL, 1, 1, 3, NULL, NULL, 1),
(4, NULL, 1, 1, 4, NULL, NULL, 1),
(5, NULL, 1, 1, 5, NULL, NULL, 1),
(6, NULL, 1, 1, 6, NULL, NULL, 1),
(7, NULL, 1, 1, 7, NULL, NULL, 1),
(8, NULL, 1, 1, 8, NULL, NULL, 1),
(9, NULL, 1, 1, 9, NULL, NULL, 1),
(10, NULL, 1, 1, 10, NULL, NULL, 1),
(11, NULL, 1, 2, 1, NULL, NULL, 1),
(12, NULL, 1, 2, 2, NULL, NULL, 1),
(13, NULL, 1, 2, 3, NULL, NULL, 1),
(14, NULL, 1, 2, 4, NULL, NULL, 1),
(15, NULL, 1, 2, 5, NULL, NULL, 1),
(16, NULL, 1, 2, 6, NULL, NULL, 1),
(17, NULL, 1, 2, 7, NULL, NULL, 1),
(18, NULL, 1, 2, 8, NULL, NULL, 1),
(19, NULL, 1, 2, 9, NULL, NULL, 1),
(20, NULL, 1, 2, 10, NULL, NULL, 1),
(21, NULL, 1, 3, 1, NULL, NULL, 1),
(22, NULL, 1, 3, 2, NULL, NULL, 1),
(23, NULL, 1, 3, 3, NULL, NULL, 1),
(24, NULL, 1, 3, 4, NULL, NULL, 1),
(25, NULL, 1, 3, 5, NULL, NULL, 1),
(26, NULL, 1, 3, 6, NULL, NULL, 1),
(27, NULL, 1, 3, 7, NULL, NULL, 1),
(28, NULL, 1, 3, 8, NULL, NULL, 1),
(29, NULL, 1, 3, 9, NULL, NULL, 1),
(30, NULL, 1, 3, 10, NULL, NULL, 1),
(31, NULL, 1, 4, 1, NULL, NULL, 1),
(32, NULL, 1, 4, 2, NULL, NULL, 1),
(33, NULL, 1, 4, 3, NULL, NULL, 1),
(34, NULL, 1, 4, 4, NULL, NULL, 1),
(35, NULL, 1, 4, 5, NULL, NULL, 1),
(36, NULL, 1, 4, 6, NULL, NULL, 1),
(37, NULL, 1, 4, 7, NULL, NULL, 1),
(38, NULL, 1, 4, 8, NULL, NULL, 1),
(39, NULL, 1, 4, 9, NULL, NULL, 1),
(40, NULL, 1, 4, 10, NULL, NULL, 1),
(41, NULL, 1, 5, 1, NULL, NULL, 1),
(42, NULL, 1, 5, 2, NULL, NULL, 1),
(43, NULL, 1, 5, 3, NULL, NULL, 1),
(44, NULL, 1, 5, 4, NULL, NULL, 1),
(45, NULL, 1, 5, 5, NULL, NULL, 1),
(46, NULL, 1, 5, 6, NULL, NULL, 1),
(47, NULL, 1, 5, 7, NULL, NULL, 1),
(48, NULL, 1, 5, 8, NULL, NULL, 1),
(49, NULL, 1, 5, 9, NULL, NULL, 1),
(50, NULL, 1, 5, 10, NULL, NULL, 1),
(51, NULL, 1, 6, 1, NULL, NULL, 1),
(52, NULL, 1, 6, 2, NULL, NULL, 1),
(53, NULL, 1, 6, 3, NULL, NULL, 1),
(54, NULL, 1, 6, 4, NULL, NULL, 1),
(55, NULL, 1, 6, 5, NULL, NULL, 1),
(56, NULL, 1, 6, 6, NULL, NULL, 1),
(57, NULL, 1, 6, 7, NULL, NULL, 1),
(58, NULL, 1, 6, 8, NULL, NULL, 1),
(59, NULL, 1, 6, 9, NULL, NULL, 1),
(60, NULL, 1, 6, 10, NULL, NULL, 1),
(61, NULL, 1, 7, 1, NULL, NULL, 1),
(62, NULL, 1, 7, 2, NULL, NULL, 1),
(63, NULL, 1, 7, 3, NULL, NULL, 1),
(64, NULL, 1, 7, 4, NULL, NULL, 1),
(65, NULL, 1, 7, 5, NULL, NULL, 1),
(66, NULL, 1, 7, 6, NULL, NULL, 1),
(67, NULL, 1, 7, 7, NULL, NULL, 1),
(68, NULL, 1, 7, 8, NULL, NULL, 1),
(69, NULL, 1, 7, 9, NULL, NULL, 1),
(70, NULL, 1, 7, 10, NULL, NULL, 1),
(71, NULL, 1, 8, 1, NULL, NULL, 1),
(72, NULL, 1, 8, 2, NULL, NULL, 1),
(73, NULL, 1, 8, 3, NULL, NULL, 1),
(74, NULL, 1, 8, 4, NULL, NULL, 1),
(75, NULL, 1, 8, 5, NULL, NULL, 1),
(76, NULL, 1, 8, 6, NULL, NULL, 1),
(77, NULL, 1, 8, 7, NULL, NULL, 1),
(78, NULL, 1, 8, 8, NULL, NULL, 1),
(79, NULL, 1, 8, 9, NULL, NULL, 1),
(80, NULL, 1, 8, 10, NULL, NULL, 1),
(81, NULL, 1, 9, 1, NULL, NULL, 1),
(82, NULL, 1, 9, 2, NULL, NULL, 1),
(83, NULL, 1, 9, 3, NULL, NULL, 1),
(84, NULL, 1, 9, 4, NULL, NULL, 1),
(85, NULL, 1, 9, 5, NULL, NULL, 1),
(86, NULL, 1, 9, 6, NULL, NULL, 1),
(87, NULL, 1, 9, 7, NULL, NULL, 1),
(88, NULL, 1, 9, 8, NULL, NULL, 1),
(89, NULL, 1, 9, 9, NULL, NULL, 1),
(90, NULL, 1, 9, 10, NULL, NULL, 1),
(91, NULL, 1, 10, 1, NULL, NULL, 1),
(92, NULL, 1, 10, 2, NULL, NULL, 1),
(93, NULL, 1, 10, 3, NULL, NULL, 1),
(94, NULL, 1, 10, 4, NULL, NULL, 1),
(95, NULL, 1, 10, 5, NULL, NULL, 1),
(96, NULL, 1, 10, 6, NULL, NULL, 1),
(97, NULL, 1, 10, 7, NULL, NULL, 1),
(98, NULL, 1, 10, 8, NULL, NULL, 1),
(99, NULL, 1, 10, 9, NULL, NULL, 1),
(100, NULL, 1, 10, 10, NULL, NULL, 1),
(101, NULL, 2, 1, 1, NULL, NULL, 1),
(102, NULL, 2, 1, 2, NULL, NULL, 1),
(103, NULL, 2, 1, 3, NULL, NULL, 1),
(104, NULL, 2, 1, 4, NULL, NULL, 1),
(105, NULL, 2, 1, 5, NULL, NULL, 1),
(106, NULL, 2, 1, 6, NULL, NULL, 1),
(107, NULL, 2, 1, 7, NULL, NULL, 1),
(108, NULL, 2, 1, 8, NULL, NULL, 1),
(109, NULL, 2, 1, 9, NULL, NULL, 1),
(110, NULL, 2, 1, 10, NULL, NULL, 1),
(111, NULL, 2, 2, 1, NULL, NULL, 1),
(112, NULL, 2, 2, 2, NULL, NULL, 1),
(113, NULL, 2, 2, 3, NULL, NULL, 1),
(114, NULL, 2, 2, 4, NULL, NULL, 1),
(115, NULL, 2, 2, 5, NULL, NULL, 1),
(116, NULL, 2, 2, 6, NULL, NULL, 1),
(117, NULL, 2, 2, 7, NULL, NULL, 1),
(118, NULL, 2, 2, 8, NULL, NULL, 1),
(119, NULL, 2, 2, 9, NULL, NULL, 1),
(120, NULL, 2, 2, 10, NULL, NULL, 1),
(121, NULL, 2, 3, 1, NULL, NULL, 1),
(122, NULL, 2, 3, 2, NULL, NULL, 1),
(123, NULL, 2, 3, 3, NULL, NULL, 1),
(124, NULL, 2, 3, 4, NULL, NULL, 1),
(125, NULL, 2, 3, 5, NULL, NULL, 1),
(126, NULL, 2, 3, 6, NULL, NULL, 1),
(127, NULL, 2, 3, 7, NULL, NULL, 1),
(128, NULL, 2, 3, 8, NULL, NULL, 1),
(129, NULL, 2, 3, 9, NULL, NULL, 1),
(130, NULL, 2, 3, 10, NULL, NULL, 1),
(131, NULL, 2, 4, 1, NULL, NULL, 1),
(132, NULL, 2, 4, 2, NULL, NULL, 1),
(133, NULL, 2, 4, 3, NULL, NULL, 1),
(134, NULL, 2, 4, 4, NULL, NULL, 1),
(135, NULL, 2, 4, 5, NULL, NULL, 1),
(136, NULL, 2, 4, 6, NULL, NULL, 1),
(137, NULL, 2, 4, 7, NULL, NULL, 1),
(138, NULL, 2, 4, 8, NULL, NULL, 1),
(139, NULL, 2, 4, 9, NULL, NULL, 1),
(140, NULL, 2, 4, 10, NULL, NULL, 1),
(141, NULL, 2, 5, 1, NULL, NULL, 1),
(142, NULL, 2, 5, 2, NULL, NULL, 1),
(143, NULL, 2, 5, 3, NULL, NULL, 1),
(144, NULL, 2, 5, 4, NULL, NULL, 1),
(145, NULL, 2, 5, 5, NULL, NULL, 1),
(146, NULL, 2, 5, 6, NULL, NULL, 1),
(147, NULL, 2, 5, 7, NULL, NULL, 1),
(148, NULL, 2, 5, 8, NULL, NULL, 1),
(149, NULL, 2, 5, 9, NULL, NULL, 1),
(150, NULL, 2, 5, 10, NULL, NULL, 1),
(151, NULL, 2, 6, 1, NULL, NULL, 1),
(152, NULL, 2, 6, 2, NULL, NULL, 1),
(153, NULL, 2, 6, 3, NULL, NULL, 1),
(154, NULL, 2, 6, 4, NULL, NULL, 1),
(155, NULL, 2, 6, 5, NULL, NULL, 1),
(156, NULL, 2, 6, 6, NULL, NULL, 1),
(157, NULL, 2, 6, 7, NULL, NULL, 1),
(158, NULL, 2, 6, 8, NULL, NULL, 1),
(159, NULL, 2, 6, 9, NULL, NULL, 1),
(160, NULL, 2, 6, 10, NULL, NULL, 1),
(161, NULL, 2, 7, 1, NULL, NULL, 1),
(162, NULL, 2, 7, 2, NULL, NULL, 1),
(163, NULL, 2, 7, 3, NULL, NULL, 1),
(164, NULL, 2, 7, 4, NULL, NULL, 1),
(165, NULL, 2, 7, 5, NULL, NULL, 1),
(166, NULL, 2, 7, 6, NULL, NULL, 1),
(167, NULL, 2, 7, 7, NULL, NULL, 1),
(168, NULL, 2, 7, 8, NULL, NULL, 1),
(169, NULL, 2, 7, 9, NULL, NULL, 1),
(170, NULL, 2, 7, 10, NULL, NULL, 1),
(171, NULL, 2, 8, 1, NULL, NULL, 1),
(172, NULL, 2, 8, 2, NULL, NULL, 1),
(173, NULL, 2, 8, 3, NULL, NULL, 1),
(174, NULL, 2, 8, 4, NULL, NULL, 1),
(175, NULL, 2, 8, 5, NULL, NULL, 1),
(176, NULL, 2, 8, 6, NULL, NULL, 1),
(177, NULL, 2, 8, 7, NULL, NULL, 1),
(178, NULL, 2, 8, 8, NULL, NULL, 1),
(179, NULL, 2, 8, 9, NULL, NULL, 1),
(180, NULL, 2, 8, 10, NULL, NULL, 1),
(181, NULL, 2, 9, 1, NULL, NULL, 1),
(182, NULL, 2, 9, 2, NULL, NULL, 1),
(183, NULL, 2, 9, 3, NULL, NULL, 1),
(184, NULL, 2, 9, 4, NULL, NULL, 1),
(185, NULL, 2, 9, 5, NULL, NULL, 1),
(186, NULL, 2, 9, 6, NULL, NULL, 1),
(187, NULL, 2, 9, 7, NULL, NULL, 1),
(188, NULL, 2, 9, 8, NULL, NULL, 1),
(189, NULL, 2, 9, 9, NULL, NULL, 1),
(190, NULL, 2, 9, 10, NULL, NULL, 1),
(191, NULL, 2, 10, 1, NULL, NULL, 1),
(192, NULL, 2, 10, 2, NULL, NULL, 1),
(193, NULL, 2, 10, 3, NULL, NULL, 1),
(194, NULL, 2, 10, 4, NULL, NULL, 1),
(195, NULL, 2, 10, 5, NULL, NULL, 1),
(196, NULL, 2, 10, 6, NULL, NULL, 1),
(197, NULL, 2, 10, 7, NULL, NULL, 1),
(198, NULL, 2, 10, 8, NULL, NULL, 1),
(199, NULL, 2, 10, 9, NULL, NULL, 1),
(200, NULL, 2, 10, 10, NULL, NULL, 1),
(201, NULL, 3, 1, 1, NULL, NULL, 1),
(202, NULL, 3, 1, 2, NULL, NULL, 1),
(203, NULL, 3, 1, 3, NULL, NULL, 1),
(204, NULL, 3, 1, 4, NULL, NULL, 1),
(205, NULL, 3, 1, 5, NULL, NULL, 1),
(206, NULL, 3, 1, 6, NULL, NULL, 1),
(207, NULL, 3, 1, 7, NULL, NULL, 1),
(208, NULL, 3, 1, 8, NULL, NULL, 1),
(209, NULL, 3, 1, 9, NULL, NULL, 1),
(210, NULL, 3, 1, 10, NULL, NULL, 1),
(211, NULL, 3, 2, 1, NULL, NULL, 1),
(212, NULL, 3, 2, 2, NULL, NULL, 1),
(213, NULL, 3, 2, 3, NULL, NULL, 1),
(214, NULL, 3, 2, 4, NULL, NULL, 1),
(215, NULL, 3, 2, 5, NULL, NULL, 1),
(216, NULL, 3, 2, 6, NULL, NULL, 1),
(217, NULL, 3, 2, 7, NULL, NULL, 1),
(218, NULL, 3, 2, 8, NULL, NULL, 1),
(219, NULL, 3, 2, 9, NULL, NULL, 1),
(220, NULL, 3, 2, 10, NULL, NULL, 1),
(221, NULL, 3, 3, 1, NULL, NULL, 1),
(222, NULL, 3, 3, 2, NULL, NULL, 1),
(223, NULL, 3, 3, 3, NULL, NULL, 1),
(224, NULL, 3, 3, 4, NULL, NULL, 1),
(225, NULL, 3, 3, 5, NULL, NULL, 1),
(226, NULL, 3, 3, 6, NULL, NULL, 1),
(227, NULL, 3, 3, 7, NULL, NULL, 1),
(228, NULL, 3, 3, 8, NULL, NULL, 1),
(229, NULL, 3, 3, 9, NULL, NULL, 1),
(230, NULL, 3, 3, 10, NULL, NULL, 1),
(231, NULL, 3, 4, 1, NULL, NULL, 1),
(232, NULL, 3, 4, 2, NULL, NULL, 1),
(233, NULL, 3, 4, 3, NULL, NULL, 1),
(234, NULL, 3, 4, 4, NULL, NULL, 1),
(235, NULL, 3, 4, 5, NULL, NULL, 1),
(236, NULL, 3, 4, 6, NULL, NULL, 1),
(237, NULL, 3, 4, 7, NULL, NULL, 1),
(238, NULL, 3, 4, 8, NULL, NULL, 1),
(239, NULL, 3, 4, 9, NULL, NULL, 1),
(240, NULL, 3, 4, 10, NULL, NULL, 1),
(241, NULL, 3, 5, 1, NULL, NULL, 1),
(242, NULL, 3, 5, 2, NULL, NULL, 1),
(243, NULL, 3, 5, 3, NULL, NULL, 1),
(244, NULL, 3, 5, 4, NULL, NULL, 1),
(245, NULL, 3, 5, 5, NULL, NULL, 1),
(246, NULL, 3, 5, 6, NULL, NULL, 1),
(247, NULL, 3, 5, 7, NULL, NULL, 1),
(248, NULL, 3, 5, 8, NULL, NULL, 1),
(249, NULL, 3, 5, 9, NULL, NULL, 1),
(250, NULL, 3, 5, 10, NULL, NULL, 1),
(251, NULL, 3, 6, 1, NULL, NULL, 1),
(252, NULL, 3, 6, 2, NULL, NULL, 1),
(253, NULL, 3, 6, 3, NULL, NULL, 1),
(254, NULL, 3, 6, 4, NULL, NULL, 1),
(255, NULL, 3, 6, 5, NULL, NULL, 1),
(256, NULL, 3, 6, 6, NULL, NULL, 1),
(257, NULL, 3, 6, 7, NULL, NULL, 1),
(258, NULL, 3, 6, 8, NULL, NULL, 1),
(259, NULL, 3, 6, 9, NULL, NULL, 1),
(260, NULL, 3, 6, 10, NULL, NULL, 1),
(261, NULL, 3, 7, 1, NULL, NULL, 1),
(262, NULL, 3, 7, 2, NULL, NULL, 1),
(263, NULL, 3, 7, 3, NULL, NULL, 1),
(264, NULL, 3, 7, 4, NULL, NULL, 1),
(265, NULL, 3, 7, 5, NULL, NULL, 1),
(266, NULL, 3, 7, 6, NULL, NULL, 1),
(267, NULL, 3, 7, 7, NULL, NULL, 1),
(268, NULL, 3, 7, 8, NULL, NULL, 1),
(269, NULL, 3, 7, 9, NULL, NULL, 1),
(270, NULL, 3, 7, 10, NULL, NULL, 1),
(271, NULL, 3, 8, 1, NULL, NULL, 1),
(272, NULL, 3, 8, 2, NULL, NULL, 1),
(273, NULL, 3, 8, 3, NULL, NULL, 1),
(274, NULL, 3, 8, 4, NULL, NULL, 1),
(275, NULL, 3, 8, 5, NULL, NULL, 1),
(276, NULL, 3, 8, 6, NULL, NULL, 1),
(277, NULL, 3, 8, 7, NULL, NULL, 1),
(278, NULL, 3, 8, 8, NULL, NULL, 1),
(279, NULL, 3, 8, 9, NULL, NULL, 1),
(280, NULL, 3, 8, 10, NULL, NULL, 1),
(281, NULL, 3, 9, 1, NULL, NULL, 1),
(282, NULL, 3, 9, 2, NULL, NULL, 1),
(283, NULL, 3, 9, 3, NULL, NULL, 1),
(284, NULL, 3, 9, 4, NULL, NULL, 1),
(285, NULL, 3, 9, 5, NULL, NULL, 1),
(286, NULL, 3, 9, 6, NULL, NULL, 1),
(287, NULL, 3, 9, 7, NULL, NULL, 1),
(288, NULL, 3, 9, 8, NULL, NULL, 1),
(289, NULL, 3, 9, 9, NULL, NULL, 1),
(290, NULL, 3, 9, 10, NULL, NULL, 1),
(291, NULL, 3, 10, 1, NULL, NULL, 1),
(292, NULL, 3, 10, 2, NULL, NULL, 1),
(293, NULL, 3, 10, 3, NULL, NULL, 1),
(294, NULL, 3, 10, 4, NULL, NULL, 1),
(295, NULL, 3, 10, 5, NULL, NULL, 1),
(296, NULL, 3, 10, 6, NULL, NULL, 1),
(297, NULL, 3, 10, 7, NULL, NULL, 1),
(298, NULL, 3, 10, 8, NULL, NULL, 1),
(299, NULL, 3, 10, 9, NULL, NULL, 1),
(300, NULL, 3, 10, 10, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `parking`
--

CREATE TABLE IF NOT EXISTS `parking` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  `reservation_id` int(10) unsigned DEFAULT NULL,
  `price_plan_id` int(10) unsigned NOT NULL,
  `vehicle_id` int(10) unsigned DEFAULT NULL,
  `arrival_time` int(10) unsigned NOT NULL,
  `departure_time` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reservation_id` (`reservation_id`),
  KEY `vehicle_id` (`vehicle_id`),
  KEY `user_id` (`user_id`),
  KEY `arrival_time` (`arrival_time`),
  KEY `departure_time` (`departure_time`),
  KEY `price_plan_id` (`price_plan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `parking`
--


-- --------------------------------------------------------

--
-- Table structure for table `price_plans`
--

CREATE TABLE IF NOT EXISTS `price_plans` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `member_price` float(4,2) unsigned NOT NULL COMMENT 'hourly price for registered customers',
  `guest_price` float(4,2) unsigned NOT NULL COMMENT 'hourly price for walk ins',
  `discount_rate` float(2,2) unsigned NOT NULL COMMENT 'discount rate given to members for good attendance',
  `min_price` float(4,2) unsigned NOT NULL COMMENT 'minimum allowable price, even with discounts',
  `date_added` int(10) unsigned NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'only one price plan can be active at a time',
  PRIMARY KEY (`id`),
  KEY `active` (`active`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `price_plans`
--

INSERT INTO `price_plans` (`id`, `member_price`, `guest_price`, `discount_rate`, `min_price`, `date_added`, `active`) VALUES
(1, 15.00, 18.00, 0.00, 0.00, 1304503188, 1);

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE IF NOT EXISTS `reservations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `start_time` int(10) unsigned NOT NULL,
  `end_time` int(10) unsigned NOT NULL,
  `recurring` tinyint(1) NOT NULL DEFAULT '0',
  `previous_id` int(10) unsigned DEFAULT NULL,
  `date_added` int(10) unsigned NOT NULL,
  `last_edited` int(10) unsigned DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `starttime` (`start_time`),
  KEY `endtime` (`end_time`),
  KEY `recurring` (`recurring`),
  KEY `active` (`active`),
  KEY `previous_id` (`previous_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`id`, `user_id`, `start_time`, `end_time`, `recurring`, `previous_id`, `date_added`, `last_edited`, `active`) VALUES
(1, 1, 1304528400, 1304539200, 0, 0, 1304518686, 1304519394, 0),
(2, 1, 1304528400, 1304533800, 0, 0, 1304519065, 1304519196, 0);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`) VALUES
(1, 'login', 'Login privileges, granted after account confirmation'),
(2, 'admin', 'Administrative user, has access to everything.'),
(3, 'confirmed', 'Confirmed user, granted after email confirmation.');

-- --------------------------------------------------------

--
-- Table structure for table `roles_users`
--

CREATE TABLE IF NOT EXISTS `roles_users` (
  `user_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `fk_role_id` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `roles_users`
--

INSERT INTO `roles_users` (`user_id`, `role_id`) VALUES
(1, 1),
(1, 2),
(1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(30) NOT NULL,
  `last_name` varchar(40) NOT NULL,
  `email` varchar(127) NOT NULL,
  `password` char(64) NOT NULL,
  `registration_date` int(10) unsigned NOT NULL,
  `logins` int(10) NOT NULL DEFAULT '0',
  `last_login` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password`, `registration_date`, `logins`, `last_login`) VALUES
(1, 'Abdul', 'Hassan', 'megaman732@msn.com', 'cddef7a87fcd7ec7df217b5cd6fc53b84322e69b48b0a959ec9067ee97c26ff6', 1304503131, 0, 1304518677);

-- --------------------------------------------------------

--
-- Table structure for table `user_tokens`
--

CREATE TABLE IF NOT EXISTS `user_tokens` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `user_agent` varchar(40) NOT NULL,
  `token` varchar(40) NOT NULL,
  `type` varchar(100) NOT NULL,
  `created` int(10) unsigned NOT NULL,
  `expires` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_token` (`token`),
  KEY `fk_user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `user_tokens`
--

INSERT INTO `user_tokens` (`id`, `user_id`, `user_agent`, `token`, `type`, `created`, `expires`) VALUES
(1, 1, '9fe1d434762cf4ac057833518979290540fcab3d', '9cc661ccd1ba015d3f9796a14b572093c5610f6e', '', 0, 1305728277);

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE IF NOT EXISTS `vehicles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `license_plate` varchar(10) NOT NULL,
  `state` char(2) NOT NULL,
  `date_added` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `license_plate` (`license_plate`),
  KEY `state` (`state`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `vehicles`
--


--
-- Constraints for dumped tables
--

--
-- Constraints for table `garage`
--
ALTER TABLE `garage`
  ADD CONSTRAINT `garage_ibfk_1` FOREIGN KEY (`parking_id`) REFERENCES `parking` (`id`);

--
-- Constraints for table `parking`
--
ALTER TABLE `parking`
  ADD CONSTRAINT `parking_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `parking_ibfk_2` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`id`),
  ADD CONSTRAINT `parking_ibfk_3` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`),
  ADD CONSTRAINT `parking_ibfk_4` FOREIGN KEY (`price_plan_id`) REFERENCES `price_plans` (`id`);

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `roles_users`
--
ALTER TABLE `roles_users`
  ADD CONSTRAINT `roles_users_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `roles_users_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_tokens`
--
ALTER TABLE `user_tokens`
  ADD CONSTRAINT `user_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD CONSTRAINT `vehicles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
