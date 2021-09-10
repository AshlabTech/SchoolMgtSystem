-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 20, 2020 at 08:14 PM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pss_mokwa`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_info`
--

CREATE TABLE `admin_info` (
  `admin_info_id` int(11) NOT NULL,
  `first_name` varchar(222) NOT NULL,
  `last_name` varchar(222) NOT NULL,
  `other_name` varchar(222) NOT NULL,
  `picture_name` varchar(255) NOT NULL,
  `status` enum('0','1','2') NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin_info`
--

INSERT INTO `admin_info` (`admin_info_id`, `first_name`, `last_name`, `other_name`, `picture_name`, `status`) VALUES
(1, 'Abdulkarim', 'Abdullahi', '', '', '1');

-- --------------------------------------------------------

--
-- Table structure for table `admin_login_info`
--

CREATE TABLE `admin_login_info` (
  `admin_login_info_id` int(11) NOT NULL,
  `admin_info_id` int(11) NOT NULL,
  `login_id` varchar(222) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` enum('0','1','2') NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin_login_info`
--

INSERT INTO `admin_login_info` (`admin_login_info_id`, `admin_info_id`, `login_id`, `password`, `status`) VALUES
(1, 1, 'Admin', '81dc9bdb52d04dc20036dbd8313ed055', '1');

-- --------------------------------------------------------

--
-- Table structure for table `annual_sallary_increament`
--

CREATE TABLE `annual_sallary_increament` (
  `id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `annual_sallary_increament`
--

INSERT INTO `annual_sallary_increament` (`id`, `amount`, `status`) VALUES
(1, 1000, 1);

-- --------------------------------------------------------

--
-- Table structure for table `baisic_sallary`
--

CREATE TABLE `baisic_sallary` (
  `id` int(11) NOT NULL,
  `qualification_id` int(11) NOT NULL,
  `designation_id` int(11) NOT NULL,
  `basic_sallary` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `baisic_sallary`
--

INSERT INTO `baisic_sallary` (`id`, `qualification_id`, `designation_id`, `basic_sallary`, `status`) VALUES
(1, 7, 3, 15000, '1'),
(2, 1, 6, 14000, '1'),
(3, 2, 5, 17000, '1');

-- --------------------------------------------------------

--
-- Table structure for table `basic_allowance`
--

CREATE TABLE `basic_allowance` (
  `id` int(11) NOT NULL,
  `basic_allowance` varchar(200) NOT NULL,
  `designation_id` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `candidate_payment`
--

CREATE TABLE `candidate_payment` (
  `id` int(11) NOT NULL,
  `student_info_id` int(11) NOT NULL,
  `payment_definition_id` int(11) NOT NULL,
  `payment_id` int(11) NOT NULL,
  `day` varchar(20) NOT NULL,
  `month` varchar(20) NOT NULL,
  `year` varchar(20) NOT NULL,
  `session_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `ballance` int(11) NOT NULL,
  `session_paid` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `candidate_payment_definition`
--

CREATE TABLE `candidate_payment_definition` (
  `id` int(11) NOT NULL,
  `student_info_id` int(11) NOT NULL,
  `payment_id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `candidate_subjects`
--

CREATE TABLE `candidate_subjects` (
  `id` int(11) NOT NULL,
  `student_info_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `session_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `class_id` int(11) NOT NULL,
  `class_name` varchar(255) NOT NULL,
  `school_section_id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `class_student_adviser` varchar(255) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`class_id`, `class_name`, `school_section_id`, `description`, `class_student_adviser`, `status`) VALUES
(1, 'Pre - Nursery 1', 5, '', '', '1'),
(2, 'Pre - Nursery 2', 5, '', '', '1'),
(3, 'Pre - Nursery 3', 5, '', '', '1'),
(4, 'Nursery One', 1, '', '', '1'),
(5, 'Nursery Two', 1, '', '', '1'),
(6, 'Nursery Three', 1, '', '', '1'),
(7, 'Primary One', 2, '', '', '1'),
(8, 'Primary Two', 2, '', '', '1'),
(9, 'Primary Three', 2, '', '', '1'),
(10, 'Primary Four', 2, '', '', '1'),
(11, 'Primary Five', 2, '', '', '1'),
(12, 'Primary Six', 2, '', '', '1'),
(13, 'J.S.S One', 3, '', '', '1'),
(14, 'J.S.S Two', 3, '', '', '1'),
(15, 'J.S.S Three', 3, '', '', '1'),
(16, 'S.S.S One', 4, '', '', '1'),
(17, 'S.S.S Two', 4, '', '', '1'),
(18, 'S.S.S Three', 4, '', '', '1');

-- --------------------------------------------------------

--
-- Table structure for table `contineous_accessment`
--

CREATE TABLE `contineous_accessment` (
  `id` int(11) NOT NULL,
  `student_info_id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `term_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `ca1` int(11) NOT NULL,
  `ca2` int(11) NOT NULL,
  `ca3` int(11) NOT NULL,
  `exam` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  `grade` enum('A','B','C','D','E','F') NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `contineous_accessment`
--

INSERT INTO `contineous_accessment` (`id`, `student_info_id`, `session_id`, `class_id`, `term_id`, `subject_id`, `ca1`, `ca2`, `ca3`, `exam`, `total`, `grade`, `status`) VALUES
(1, 1, 7, 16, 3, 1, 8, 2, 15, 45, 70, 'A', '1'),
(2, 4, 7, 14, 1, 5, 10, 2, 10, 35, 57, 'C', '1'),
(3, 2, 7, 16, 1, 1, 2, 11, 4, 26, 43, 'D', '1'),
(4, 2, 7, 16, 1, 5, 10, 10, 8, 45, 73, 'A', '1');

-- --------------------------------------------------------

--
-- Table structure for table `designations`
--

CREATE TABLE `designations` (
  `id` int(11) NOT NULL,
  `designation` varchar(200) NOT NULL,
  `status` enum('0','1','3','4') NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `designations`
--

INSERT INTO `designations` (`id`, `designation`, `status`) VALUES
(1, 'ADMIN', '4'),
(2, 'PRINCIPAL', '4'),
(3, 'EXAM OFFICER', '4'),
(4, 'BURSAR', '4'),
(5, 'H.M', '4'),
(6, 'TEACHER', '1');

-- --------------------------------------------------------

--
-- Table structure for table `lga`
--

CREATE TABLE `lga` (
  `local_id` int(11) NOT NULL,
  `state_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `lga`
--

INSERT INTO `lga` (`local_id`, `state_id`, `title`, `status`) VALUES
(1, 1, 'Aba South', '1'),
(2, 1, 'Arochukwu', '1'),
(3, 1, 'Bende', '1'),
(4, 1, 'Ikwuano', '1'),
(5, 1, 'Isiala Ngwa North', '1'),
(6, 1, 'Isiala Ngwa South', '1'),
(7, 1, 'Isuikwuato', '1'),
(8, 1, 'Obi Ngwa', '1'),
(9, 1, 'Ohafia', '1'),
(10, 1, 'Osisioma', '1'),
(11, 1, 'Ugwunagbo', '1'),
(12, 1, 'Ukwa East', '1'),
(13, 1, 'Ukwa West', '1'),
(14, 1, 'Umuahia North', '1'),
(15, 1, 'Umuahia South', '1'),
(16, 1, 'Umu Nneochi', '1'),
(17, 2, 'Fufure', '1'),
(18, 2, 'Ganye', '1'),
(19, 2, 'Gayuk', '1'),
(20, 2, 'Gombi', '1'),
(21, 2, 'Grie', '1'),
(22, 2, 'Hong', '1'),
(23, 2, 'Jada', '1'),
(24, 2, 'Lamurde', '1'),
(25, 2, 'Madagali', '1'),
(26, 2, 'Maiha', '1'),
(27, 2, 'Mayo Belwa', '1'),
(28, 2, 'Michika', '1'),
(29, 2, 'Mubi North', '1'),
(30, 2, 'Mubi South', '1'),
(31, 2, 'Numan', '1'),
(32, 2, 'Shelleng', '1'),
(33, 2, 'Song', '1'),
(34, 2, 'Toungo', '1'),
(35, 2, 'Yola North', '1'),
(36, 2, 'Yola South', '1'),
(37, 3, 'Eastern Obolo', '1'),
(38, 3, 'Eket', '1'),
(39, 3, 'Esit Eket', '1'),
(40, 3, 'Essien Udim', '1'),
(41, 3, 'Etim Ekpo', '1'),
(42, 3, 'Etinan', '1'),
(43, 3, 'Ibeno', '1'),
(44, 3, 'Ibesikpo Asutan', '1'),
(45, 3, 'Ibiono-Ibom', '1'),
(46, 3, 'Ika', '1'),
(47, 3, 'Ikono', '1'),
(48, 3, 'Ikot Abasi', '1'),
(49, 3, 'Ikot Ekpene', '1'),
(50, 3, 'Ini', '1'),
(51, 3, 'Itu', '1'),
(52, 3, 'Mbo', '1'),
(53, 3, 'Mkpat-Enin', '1'),
(54, 3, 'Nsit-Atai', '1'),
(55, 3, 'Nsit-Ibom', '1'),
(56, 3, 'Nsit-Ubium', '1'),
(57, 3, 'Obot Akara', '1'),
(58, 3, 'Okobo', '1'),
(59, 3, 'Onna', '1'),
(60, 3, 'Oron', '1'),
(61, 3, 'Oruk Anam', '1'),
(62, 3, 'Udung-Uko', '1'),
(63, 3, 'Ukanafun', '1'),
(64, 3, 'Uruan', '1'),
(65, 3, 'Urue-Offong/Oruko', '1'),
(66, 3, 'Uyo', '1'),
(67, 4, 'Anambra East', '1'),
(68, 4, 'Anambra West', '1'),
(69, 4, 'Anaocha', '1'),
(70, 4, 'Awka North', '1'),
(71, 4, 'Awka South', '1'),
(72, 4, 'Ayamelum', '1'),
(73, 4, 'Dunukofia', '1'),
(74, 4, 'Ekwusigo', '1'),
(75, 4, 'Idemili North', '1'),
(76, 4, 'Idemili South', '1'),
(77, 4, 'Ihiala', '1'),
(78, 4, 'Njikoka', '1'),
(79, 4, 'Nnewi North', '1'),
(80, 4, 'Nnewi South', '1'),
(81, 4, 'Ogbaru', '1'),
(82, 4, 'Onitsha North', '1'),
(83, 4, 'Onitsha South', '1'),
(84, 4, 'Orumba North', '1'),
(85, 4, 'Orumba South', '1'),
(86, 4, 'Oyi', '1'),
(87, 5, 'Bauchi', '1'),
(88, 5, 'Bogoro', '1'),
(89, 5, 'Damban', '1'),
(90, 5, 'Darazo', '1'),
(91, 5, 'Dass', '1'),
(92, 5, 'Gamawa', '1'),
(93, 5, 'Ganjuwa', '1'),
(94, 5, 'Giade', '1'),
(95, 5, 'Itas/Gadau', '1'),
(96, 5, 'Jama\'are', '1'),
(97, 5, 'Katagum', '1'),
(98, 5, 'Kirfi', '1'),
(99, 5, 'Misau', '1'),
(100, 5, 'Ningi', '1'),
(101, 5, 'Shira', '1'),
(102, 5, 'Tafawa Balewa', '1'),
(103, 5, 'Toro', '1'),
(104, 5, 'Warji', '1'),
(105, 5, 'Zaki', '1'),
(106, 6, 'Ekeremor', '1'),
(107, 6, 'Kolokuma/Opokuma', '1'),
(108, 6, 'Nembe', '1'),
(109, 6, 'Ogbia', '1'),
(110, 6, 'Sagbama', '1'),
(111, 6, 'Southern Ijaw', '1'),
(112, 6, 'Yenagoa', '1'),
(113, 7, 'Apa', '1'),
(114, 7, 'Ado', '1'),
(115, 7, 'Buruku', '1'),
(116, 7, 'Gboko', '1'),
(117, 7, 'Guma', '1'),
(118, 7, 'Gwer East', '1'),
(119, 7, 'Gwer West', '1'),
(120, 7, 'Katsina-Ala', '1'),
(121, 7, 'Konshisha', '1'),
(122, 7, 'Kwande', '1'),
(123, 7, 'Logo', '1'),
(124, 7, 'Makurdi', '1'),
(125, 7, 'Obi', '1'),
(126, 7, 'Ogbadibo', '1'),
(127, 7, 'Ohimini', '1'),
(128, 7, 'Oju', '1'),
(129, 7, 'Okpokwu', '1'),
(130, 7, 'Oturkpo', '1'),
(131, 7, 'Tarka', '1'),
(132, 7, 'Ukum', '1'),
(133, 7, 'Ushongo', '1'),
(134, 7, 'Vandeikya', '1'),
(135, 8, 'Askira/Uba', '1'),
(136, 8, 'Bama', '1'),
(137, 8, 'Bayo', '1'),
(138, 8, 'Biu', '1'),
(139, 8, 'Chibok', '1'),
(140, 8, 'Damboa', '1'),
(141, 8, 'Dikwa', '1'),
(142, 8, 'Gubio', '1'),
(143, 8, 'Guzamala', '1'),
(144, 8, 'Gwoza', '1'),
(145, 8, 'Hawul', '1'),
(146, 8, 'Jere', '1'),
(147, 8, 'Kaga', '1'),
(148, 8, 'Kala/Balge', '1'),
(149, 8, 'Konduga', '1'),
(150, 8, 'Kukawa', '1'),
(151, 8, 'Kwaya Kusar', '1'),
(152, 8, 'Mafa', '1'),
(153, 8, 'Magumeri', '1'),
(154, 8, 'Maiduguri', '1'),
(155, 8, 'Marte', '1'),
(156, 8, 'Mobbar', '1'),
(157, 8, 'Monguno', '1'),
(158, 8, 'Ngala', '1'),
(159, 8, 'Nganzai', '1'),
(160, 8, 'Shani', '1'),
(161, 9, 'Akamkpa', '1'),
(162, 9, 'Akpabuyo', '1'),
(163, 9, 'Bakassi', '1'),
(164, 9, 'Bekwarra', '1'),
(165, 9, 'Biase', '1'),
(166, 9, 'Boki', '1'),
(167, 9, 'Calabar Municipal', '1'),
(168, 9, 'Calabar South', '1'),
(169, 9, 'Etung', '1'),
(170, 9, 'Ikom', '1'),
(171, 9, 'Obanliku', '1'),
(172, 9, 'Obubra', '1'),
(173, 9, 'Obudu', '1'),
(174, 9, 'Odukpani', '1'),
(175, 9, 'Ogoja', '1'),
(176, 9, 'Yakuur', '1'),
(177, 9, 'Yala', '1'),
(178, 10, 'Aniocha South', '1'),
(179, 10, 'Bomadi', '1'),
(180, 10, 'Burutu', '1'),
(181, 10, 'Ethiope East', '1'),
(182, 10, 'Ethiope West', '1'),
(183, 10, 'Ika North East', '1'),
(184, 10, 'Ika South', '1'),
(185, 10, 'Isoko North', '1'),
(186, 10, 'Isoko South', '1'),
(187, 10, 'Ndokwa East', '1'),
(188, 10, 'Ndokwa West', '1'),
(189, 10, 'Okpe', '1'),
(190, 10, 'Oshimili North', '1'),
(191, 10, 'Oshimili South', '1'),
(192, 10, 'Patani', '1'),
(193, 10, 'Sapele', '1'),
(194, 10, 'Udu', '1'),
(195, 10, 'Ughelli North', '1'),
(196, 10, 'Ughelli South', '1'),
(197, 10, 'Ukwuani', '1'),
(198, 10, 'Uvwie', '1'),
(199, 10, 'Warri North', '1'),
(200, 10, 'Warri South', '1'),
(201, 10, 'Warri South West', '1'),
(202, 11, 'Afikpo North', '1'),
(203, 11, 'Afikpo South', '1'),
(204, 11, 'Ebonyi', '1'),
(205, 11, 'Ezza North', '1'),
(206, 11, 'Ezza South', '1'),
(207, 11, 'Ikwo', '1'),
(208, 11, 'Ishielu', '1'),
(209, 11, 'Ivo', '1'),
(210, 11, 'Izzi', '1'),
(211, 11, 'Ohaozara', '1'),
(212, 11, 'Ohaukwu', '1'),
(213, 11, 'Onicha', '1'),
(214, 12, 'Egor', '1'),
(215, 12, 'Esan Central', '1'),
(216, 12, 'Esan North-East', '1'),
(217, 12, 'Esan South-East', '1'),
(218, 12, 'Esan West', '1'),
(219, 12, 'Etsako Central', '1'),
(220, 12, 'Etsako East', '1'),
(221, 12, 'Etsako West', '1'),
(222, 12, 'Igueben', '1'),
(223, 12, 'Ikpoba Okha', '1'),
(224, 12, 'Orhionmwon', '1'),
(225, 12, 'Oredo', '1'),
(226, 12, 'Ovia North-East', '1'),
(227, 12, 'Ovia South-West', '1'),
(228, 12, 'Owan East', '1'),
(229, 12, 'Owan West', '1'),
(230, 12, 'Uhunmwonde', '1'),
(231, 13, 'Efon', '1'),
(232, 13, 'Ekiti East', '1'),
(233, 13, 'Ekiti South-West', '1'),
(234, 13, 'Ekiti West', '1'),
(235, 13, 'Emure', '1'),
(236, 13, 'Gbonyin', '1'),
(237, 13, 'Ido Osi', '1'),
(238, 13, 'Ijero', '1'),
(239, 13, 'Ikere', '1'),
(240, 13, 'Ikole', '1'),
(241, 13, 'Ilejemeje', '1'),
(242, 13, 'Irepodun/Ifelodun', '1'),
(243, 13, 'Ise/Orun', '1'),
(244, 13, 'Moba', '1'),
(245, 13, 'Oye', '1'),
(246, 14, 'Awgu', '1'),
(247, 14, 'Enugu East', '1'),
(248, 14, 'Enugu North', '1'),
(249, 14, 'Enugu South', '1'),
(250, 14, 'Ezeagu', '1'),
(251, 14, 'Igbo Etiti', '1'),
(252, 14, 'Igbo Eze North', '1'),
(253, 14, 'Igbo Eze South', '1'),
(254, 14, 'Isi Uzo', '1'),
(255, 14, 'Nkanu East', '1'),
(256, 14, 'Nkanu West', '1'),
(257, 14, 'Nsukka', '1'),
(258, 14, 'Oji River', '1'),
(259, 14, 'Udenu', '1'),
(260, 14, 'Udi', '1'),
(261, 14, 'Uzo Uwani', '1'),
(262, 15, 'Bwari', '1'),
(263, 15, 'Gwagwalada', '1'),
(264, 15, 'Kuje', '1'),
(265, 15, 'Kwali', '1'),
(266, 15, 'Municipal Area Council', '1'),
(267, 16, 'Balanga', '1'),
(268, 16, 'Billiri', '1'),
(269, 16, 'Dukku', '1'),
(270, 16, 'Funakaye', '1'),
(271, 16, 'Gombe', '1'),
(272, 16, 'Kaltungo', '1'),
(273, 16, 'Kwami', '1'),
(274, 16, 'Nafada', '1'),
(275, 16, 'Shongom', '1'),
(276, 16, 'Yamaltu/Deba', '1'),
(277, 17, 'Ahiazu Mbaise', '1'),
(278, 17, 'Ehime Mbano', '1'),
(279, 17, 'Ezinihitte', '1'),
(280, 17, 'Ideato North', '1'),
(281, 17, 'Ideato South', '1'),
(282, 17, 'Ihitte/Uboma', '1'),
(283, 17, 'Ikeduru', '1'),
(284, 17, 'Isiala Mbano', '1'),
(285, 17, 'Isu', '1'),
(286, 17, 'Mbaitoli', '1'),
(287, 17, 'Ngor Okpala', '1'),
(288, 17, 'Njaba', '1'),
(289, 17, 'Nkwerre', '1'),
(290, 17, 'Nwangele', '1'),
(291, 17, 'Obowo', '1'),
(292, 17, 'Oguta', '1'),
(293, 17, 'Ohaji/Egbema', '1'),
(294, 17, 'Okigwe', '1'),
(295, 17, 'Orlu', '1'),
(296, 17, 'Orsu', '1'),
(297, 17, 'Oru East', '1'),
(298, 17, 'Oru West', '1'),
(299, 17, 'Owerri Municipal', '1'),
(300, 17, 'Owerri North', '1'),
(301, 17, 'Owerri West', '1'),
(302, 17, 'Unuimo', '1'),
(303, 18, 'Babura', '1'),
(304, 18, 'Biriniwa', '1'),
(305, 18, 'Birnin Kudu', '1'),
(306, 18, 'Buji', '1'),
(307, 18, 'Dutse', '1'),
(308, 18, 'Gagarawa', '1'),
(309, 18, 'Garki', '1'),
(310, 18, 'Gumel', '1'),
(311, 18, 'Guri', '1'),
(312, 18, 'Gwaram', '1'),
(313, 18, 'Gwiwa', '1'),
(314, 18, 'Hadejia', '1'),
(315, 18, 'Jahun', '1'),
(316, 18, 'Kafin Hausa', '1'),
(317, 18, 'Kazaure', '1'),
(318, 18, 'Kiri Kasama', '1'),
(319, 18, 'Kiyawa', '1'),
(320, 18, 'Kaugama', '1'),
(321, 18, 'Maigatari', '1'),
(322, 18, 'Malam Madori', '1'),
(323, 18, 'Miga', '1'),
(324, 18, 'Ringim', '1'),
(325, 18, 'Roni', '1'),
(326, 18, 'Sule Tankarkar', '1'),
(327, 18, 'Taura', '1'),
(328, 18, 'Yankwashi', '1'),
(329, 19, 'Chikun', '1'),
(330, 19, 'Giwa', '1'),
(331, 19, 'Igabi', '1'),
(332, 19, 'Ikara', '1'),
(333, 19, 'Jaba', '1'),
(334, 19, 'Jema\'a', '1'),
(335, 19, 'Kachia', '1'),
(336, 19, 'Kaduna North', '1'),
(337, 19, 'Kaduna South', '1'),
(338, 19, 'Kagarko', '1'),
(339, 19, 'Kajuru', '1'),
(340, 19, 'Kaura', '1'),
(341, 19, 'Kauru', '1'),
(342, 19, 'Kubau', '1'),
(343, 19, 'Kudan', '1'),
(344, 19, 'Lere', '1'),
(345, 19, 'Makarfi', '1'),
(346, 19, 'Sabon Gari', '1'),
(347, 19, 'Sanga', '1'),
(348, 19, 'Soba', '1'),
(349, 19, 'Zangon Kataf', '1'),
(350, 19, 'Zaria', '1'),
(351, 20, 'Albasu', '1'),
(352, 20, 'Bagwai', '1'),
(353, 20, 'Bebeji', '1'),
(354, 20, 'Bichi', '1'),
(355, 20, 'Bunkure', '1'),
(356, 20, 'Dala', '1'),
(357, 20, 'Dambatta', '1'),
(358, 20, 'Dawakin Kudu', '1'),
(359, 20, 'Dawakin Tofa', '1'),
(360, 20, 'Doguwa', '1'),
(361, 20, 'Fagge', '1'),
(362, 20, 'Gabasawa', '1'),
(363, 20, 'Garko', '1'),
(364, 20, 'Garun Mallam', '1'),
(365, 20, 'Gaya', '1'),
(366, 20, 'Gezawa', '1'),
(367, 20, 'Gwale', '1'),
(368, 20, 'Gwarzo', '1'),
(369, 20, 'Kabo', '1'),
(370, 20, 'Kano Municipal', '1'),
(371, 20, 'Karaye', '1'),
(372, 20, 'Kibiya', '1'),
(373, 20, 'Kiru', '1'),
(374, 20, 'Kumbotso', '1'),
(375, 20, 'Kunchi', '1'),
(376, 20, 'Kura', '1'),
(377, 20, 'Madobi', '1'),
(378, 20, 'Makoda', '1'),
(379, 20, 'Minjibir', '1'),
(380, 20, 'Nasarawa', '1'),
(381, 20, 'Rano', '1'),
(382, 20, 'Rimin Gado', '1'),
(383, 20, 'Rogo', '1'),
(384, 20, 'Shanono', '1'),
(385, 20, 'Sumaila', '1'),
(386, 20, 'Takai', '1'),
(387, 20, 'Tarauni', '1'),
(388, 20, 'Tofa', '1'),
(389, 20, 'Tsanyawa', '1'),
(390, 20, 'Tudun Wada', '1'),
(391, 20, 'Ungogo', '1'),
(392, 20, 'Warawa', '1'),
(393, 20, 'Wudil', '1'),
(394, 21, 'Batagarawa', '1'),
(395, 21, 'Batsari', '1'),
(396, 21, 'Baure', '1'),
(397, 21, 'Bindawa', '1'),
(398, 21, 'Charanchi', '1'),
(399, 21, 'Dandume', '1'),
(400, 21, 'Danja', '1'),
(401, 21, 'Dan Musa', '1'),
(402, 21, 'Daura', '1'),
(403, 21, 'Dutsi', '1'),
(404, 21, 'Dutsin Ma', '1'),
(405, 21, 'Faskari', '1'),
(406, 21, 'Funtua', '1'),
(407, 21, 'Ingawa', '1'),
(408, 21, 'Jibia', '1'),
(409, 21, 'Kafur', '1'),
(410, 21, 'Kaita', '1'),
(411, 21, 'Kankara', '1'),
(412, 21, 'Kankia', '1'),
(413, 21, 'Katsina', '1'),
(414, 21, 'Kurfi', '1'),
(415, 21, 'Kusada', '1'),
(416, 21, 'Mai\'Adua', '1'),
(417, 21, 'Malumfashi', '1'),
(418, 21, 'Mani', '1'),
(419, 21, 'Mashi', '1'),
(420, 21, 'Matazu', '1'),
(421, 21, 'Musawa', '1'),
(422, 21, 'Rimi', '1'),
(423, 21, 'Sabuwa', '1'),
(424, 21, 'Safana', '1'),
(425, 21, 'Sandamu', '1'),
(426, 21, 'Zango', '1'),
(427, 22, 'Arewa Dandi', '1'),
(428, 22, 'Argungu', '1'),
(429, 22, 'Augie', '1'),
(430, 22, 'Bagudo', '1'),
(431, 22, 'Birnin Kebbi', '1'),
(432, 22, 'Bunza', '1'),
(433, 22, 'Dandi', '1'),
(434, 22, 'Fakai', '1'),
(435, 22, 'Gwandu', '1'),
(436, 22, 'Jega', '1'),
(437, 22, 'Kalgo', '1'),
(438, 22, 'Koko/Besse', '1'),
(439, 22, 'Maiyama', '1'),
(440, 22, 'Ngaski', '1'),
(441, 22, 'Sakaba', '1'),
(442, 22, 'Shanga', '1'),
(443, 22, 'Suru', '1'),
(444, 22, 'Wasagu/Danko', '1'),
(445, 22, 'Yauri', '1'),
(446, 22, 'Zuru', '1'),
(447, 23, 'Ajaokuta', '1'),
(448, 23, 'Ankpa', '1'),
(449, 23, 'Bassa', '1'),
(450, 23, 'Dekina', '1'),
(451, 23, 'Ibaji', '1'),
(452, 23, 'Idah', '1'),
(453, 23, 'Igalamela Odolu', '1'),
(454, 23, 'Ijumu', '1'),
(455, 23, 'Kabba/Bunu', '1'),
(456, 23, 'Kogi', '1'),
(457, 23, 'Lokoja', '1'),
(458, 23, 'Mopa Muro', '1'),
(459, 23, 'Ofu', '1'),
(460, 23, 'Ogori/Magongo', '1'),
(461, 23, 'Okehi', '1'),
(462, 23, 'Okene', '1'),
(463, 23, 'Olamaboro', '1'),
(464, 23, 'Omala', '1'),
(465, 23, 'Yagba East', '1'),
(466, 23, 'Yagba West', '1'),
(467, 24, 'Baruten', '1'),
(468, 24, 'Edu', '1'),
(469, 24, 'Ekiti', '1'),
(470, 24, 'Ifelodun', '1'),
(471, 24, 'Ilorin East', '1'),
(472, 24, 'Ilorin South', '1'),
(473, 24, 'Ilorin West', '1'),
(474, 24, 'Irepodun', '1'),
(475, 24, 'Isin', '1'),
(476, 24, 'Kaiama', '1'),
(477, 24, 'Moro', '1'),
(478, 24, 'Offa', '1'),
(479, 24, 'Oke Ero', '1'),
(480, 24, 'Oyun', '1'),
(481, 24, 'Pategi', '1'),
(482, 25, 'Ajeromi-Ifelodun', '1'),
(483, 25, 'Alimosho', '1'),
(484, 25, 'Amuwo-Odofin', '1'),
(485, 25, 'Apapa', '1'),
(486, 25, 'Badagry', '1'),
(487, 25, 'Epe', '1'),
(488, 25, 'Eti Osa', '1'),
(489, 25, 'Ibeju-Lekki', '1'),
(490, 25, 'Ifako-Ijaiye', '1'),
(491, 25, 'Ikeja', '1'),
(492, 25, 'Ikorodu', '1'),
(493, 25, 'Kosofe', '1'),
(494, 25, 'Lagos Island', '1'),
(495, 25, 'Lagos Mainland', '1'),
(496, 25, 'Mushin', '1'),
(497, 25, 'Ojo', '1'),
(498, 25, 'Oshodi-Isolo', '1'),
(499, 25, 'Shomolu', '1'),
(500, 25, 'Surulere', '1'),
(501, 26, 'Awe', '1'),
(502, 26, 'Doma', '1'),
(503, 26, 'Karu', '1'),
(504, 26, 'Keana', '1'),
(505, 26, 'Keffi', '1'),
(506, 26, 'Kokona', '1'),
(507, 26, 'Lafia', '1'),
(508, 26, 'Nasarawa', '1'),
(509, 26, 'Nasarawa Egon', '1'),
(510, 26, 'Obi', '1'),
(511, 26, 'Toto', '1'),
(512, 26, 'Wamba', '1'),
(513, 27, 'Agwara', '1'),
(514, 27, 'Bida', '1'),
(515, 27, 'Borgu', '1'),
(516, 27, 'Bosso', '1'),
(517, 27, 'Chanchaga', '1'),
(518, 27, 'Edati', '1'),
(519, 27, 'Gbako', '1'),
(520, 27, 'Gurara', '1'),
(521, 27, 'Katcha', '1'),
(522, 27, 'Kontagora', '1'),
(523, 27, 'Lapai', '1'),
(524, 27, 'Lavun', '1'),
(525, 27, 'Magama', '1'),
(526, 27, 'Mariga', '1'),
(527, 27, 'Mashegu', '1'),
(528, 27, 'Mokwa', '1'),
(529, 27, 'Moya', '1'),
(530, 27, 'Paikoro', '1'),
(531, 27, 'Rafi', '1'),
(532, 27, 'Rijau', '1'),
(533, 27, 'Shiroro', '1'),
(534, 27, 'Suleja', '1'),
(535, 27, 'Tafa', '1'),
(536, 27, 'Wushishi', '1'),
(537, 28, 'Abeokuta South', '1'),
(538, 28, 'Ado-Odo/Ota', '1'),
(539, 28, 'Egbado North', '1'),
(540, 28, 'Egbado South', '1'),
(541, 28, 'Ewekoro', '1'),
(542, 28, 'Ifo', '1'),
(543, 28, 'Ijebu East', '1'),
(544, 28, 'Ijebu North', '1'),
(545, 28, 'Ijebu North East', '1'),
(546, 28, 'Ijebu Ode', '1'),
(547, 28, 'Ikenne', '1'),
(548, 28, 'Imeko Afon', '1'),
(549, 28, 'Ipokia', '1'),
(550, 28, 'Obafemi Owode', '1'),
(551, 28, 'Odeda', '1'),
(552, 28, 'Odogbolu', '1'),
(553, 28, 'Ogun Waterside', '1'),
(554, 28, 'Remo North', '1'),
(555, 28, 'Shagamu', '1'),
(556, 29, 'Akoko North-West', '1'),
(557, 29, 'Akoko South-West', '1'),
(558, 29, 'Akoko South-East', '1'),
(559, 29, 'Akure North', '1'),
(560, 29, 'Akure South', '1'),
(561, 29, 'Ese Odo', '1'),
(562, 29, 'Idanre', '1'),
(563, 29, 'Ifedore', '1'),
(564, 29, 'Ilaje', '1'),
(565, 29, 'Ile Oluji/Okeigbo', '1'),
(566, 29, 'Irele', '1'),
(567, 29, 'Odigbo', '1'),
(568, 29, 'Okitipupa', '1'),
(569, 29, 'Ondo East', '1'),
(570, 29, 'Ondo West', '1'),
(571, 29, 'Ose', '1'),
(572, 29, 'Owo', '1'),
(573, 30, 'Atakunmosa West', '1'),
(574, 30, 'Aiyedaade', '1'),
(575, 30, 'Aiyedire', '1'),
(576, 30, 'Boluwaduro', '1'),
(577, 30, 'Boripe', '1'),
(578, 30, 'Ede North', '1'),
(579, 30, 'Ede South', '1'),
(580, 30, 'Ife Central', '1'),
(581, 30, 'Ife East', '1'),
(582, 30, 'Ife North', '1'),
(583, 30, 'Ife South', '1'),
(584, 30, 'Egbedore', '1'),
(585, 30, 'Ejigbo', '1'),
(586, 30, 'Ifedayo', '1'),
(587, 30, 'Ifelodun', '1'),
(588, 30, 'Ila', '1'),
(589, 30, 'Ilesa East', '1'),
(590, 30, 'Ilesa West', '1'),
(591, 30, 'Irepodun', '1'),
(592, 30, 'Irewole', '1'),
(593, 30, 'Isokan', '1'),
(594, 30, 'Iwo', '1'),
(595, 30, 'Obokun', '1'),
(596, 30, 'Odo Otin', '1'),
(597, 30, 'Ola Oluwa', '1'),
(598, 30, 'Olorunda', '1'),
(599, 30, 'Oriade', '1'),
(600, 30, 'Orolu', '1'),
(601, 30, 'Osogbo', '1'),
(602, 31, 'Akinyele', '1'),
(603, 31, 'Atiba', '1'),
(604, 31, 'Atisbo', '1'),
(605, 31, 'Egbeda', '1'),
(606, 31, 'Ibadan North', '1'),
(607, 31, 'Ibadan North-East', '1'),
(608, 31, 'Ibadan North-West', '1'),
(609, 31, 'Ibadan South-East', '1'),
(610, 31, 'Ibadan South-West', '1'),
(611, 31, 'Ibarapa Central', '1'),
(612, 31, 'Ibarapa East', '1'),
(613, 31, 'Ibarapa North', '1'),
(614, 31, 'Ido', '1'),
(615, 31, 'Irepo', '1'),
(616, 31, 'Iseyin', '1'),
(617, 31, 'Itesiwaju', '1'),
(618, 31, 'Iwajowa', '1'),
(619, 31, 'Kajola', '1'),
(620, 31, 'Lagelu', '1'),
(621, 31, 'Ogbomosho North', '1'),
(622, 31, 'Ogbomosho South', '1'),
(623, 31, 'Ogo Oluwa', '1'),
(624, 31, 'Olorunsogo', '1'),
(625, 31, 'Oluyole', '1'),
(626, 31, 'Ona Ara', '1'),
(627, 31, 'Orelope', '1'),
(628, 31, 'Ori Ire', '1'),
(629, 31, 'Oyo', '1'),
(630, 31, 'Oyo East', '1'),
(631, 31, 'Saki East', '1'),
(632, 31, 'Saki West', '1'),
(633, 31, 'Surulere', '1'),
(634, 32, 'Barkin Ladi', '1'),
(635, 32, 'Bassa', '1'),
(636, 32, 'Jos East', '1'),
(637, 32, 'Jos North', '1'),
(638, 32, 'Jos South', '1'),
(639, 32, 'Kanam', '1'),
(640, 32, 'Kanke', '1'),
(641, 32, 'Langtang South', '1'),
(642, 32, 'Langtang North', '1'),
(643, 32, 'Mangu', '1'),
(644, 32, 'Mikang', '1'),
(645, 32, 'Pankshin', '1'),
(646, 32, 'Qua\'an Pan', '1'),
(647, 32, 'Riyom', '1'),
(648, 32, 'Shendam', '1'),
(649, 32, 'Wase', '1'),
(650, 33, 'Ahoada East', '1'),
(651, 33, 'Ahoada West', '1'),
(652, 33, 'Akuku-Toru', '1'),
(653, 33, 'Andoni', '1'),
(654, 33, 'Asari-Toru', '1'),
(655, 33, 'Bonny', '1'),
(656, 33, 'Degema', '1'),
(657, 33, 'Eleme', '1'),
(658, 33, 'Emuoha', '1'),
(659, 33, 'Etche', '1'),
(660, 33, 'Gokana', '1'),
(661, 33, 'Ikwerre', '1'),
(662, 33, 'Khana', '1'),
(663, 33, 'Obio/Akpor', '1'),
(664, 33, 'Ogba/Egbema/Ndoni', '1'),
(665, 33, 'Ogu/Bolo', '1'),
(666, 33, 'Okrika', '1'),
(667, 33, 'Omuma', '1'),
(668, 33, 'Opobo/Nkoro', '1'),
(669, 33, 'Oyigbo', '1'),
(670, 33, 'Port Harcourt', '1'),
(671, 33, 'Tai', '1'),
(672, 34, 'Bodinga', '1'),
(673, 34, 'Dange Shuni', '1'),
(674, 34, 'Gada', '1'),
(675, 34, 'Goronyo', '1'),
(676, 34, 'Gudu', '1'),
(677, 34, 'Gwadabawa', '1'),
(678, 34, 'Illela', '1'),
(679, 34, 'Isa', '1'),
(680, 34, 'Kebbe', '1'),
(681, 34, 'Kware', '1'),
(682, 34, 'Rabah', '1'),
(683, 34, 'Sabon Birni', '1'),
(684, 34, 'Shagari', '1'),
(685, 34, 'Silame', '1'),
(686, 34, 'Sokoto North', '1'),
(687, 34, 'Sokoto South', '1'),
(688, 34, 'Tambuwal', '1'),
(689, 34, 'Tangaza', '1'),
(690, 34, 'Tureta', '1'),
(691, 34, 'Wamako', '1'),
(692, 34, 'Wurno', '1'),
(693, 34, 'Yabo', '1'),
(694, 35, 'Bali', '1'),
(695, 35, 'Donga', '1'),
(696, 35, 'Gashaka', '1'),
(697, 35, 'Gassol', '1'),
(698, 35, 'Ibi', '1'),
(699, 35, 'Jalingo', '1'),
(700, 35, 'Karim Lamido', '1'),
(701, 35, 'Kumi', '1'),
(702, 35, 'Lau', '1'),
(703, 35, 'Sardauna', '1'),
(704, 35, 'Takum', '1'),
(705, 35, 'Ussa', '1'),
(706, 35, 'Wukari', '1'),
(707, 35, 'Yorro', '1'),
(708, 35, 'Zing', '1'),
(709, 36, 'Bursari', '1'),
(710, 36, 'Damaturu', '1'),
(711, 36, 'Fika', '1'),
(712, 36, 'Fune', '1'),
(713, 36, 'Geidam', '1'),
(714, 36, 'Gujba', '1'),
(715, 36, 'Gulani', '1'),
(716, 36, 'Jakusko', '1'),
(717, 36, 'Karasuwa', '1'),
(718, 36, 'Machina', '1'),
(719, 36, 'Nangere', '1'),
(720, 36, 'Nguru', '1'),
(721, 36, 'Potiskum', '1'),
(722, 36, 'Tarmuwa', '1'),
(723, 36, 'Yunusari', '1'),
(724, 36, 'Yusufari', '1'),
(725, 37, 'Bakura', '1'),
(726, 37, 'Birnin Magaji/Kiyaw', '1'),
(727, 37, 'Bukkuyum', '1'),
(728, 37, 'Bungudu', '1'),
(729, 37, 'Gummi', '1'),
(730, 37, 'Gusau', '1'),
(731, 37, 'Kaura Namoda', '1'),
(732, 37, 'Maradun', '1'),
(733, 37, 'Maru', '1'),
(734, 37, 'Shinkafi', '1'),
(735, 37, 'Talata Mafara', '1'),
(736, 37, 'Chafe', '1'),
(737, 37, 'Zurmi', '1');

-- --------------------------------------------------------

--
-- Table structure for table `months`
--

CREATE TABLE `months` (
  `month_id` int(11) NOT NULL,
  `month_full` varchar(255) NOT NULL,
  `month_abr` varchar(100) NOT NULL,
  `number_of_days` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `months`
--

INSERT INTO `months` (`month_id`, `month_full`, `month_abr`, `number_of_days`) VALUES
(1, 'January', 'Jan', 31),
(2, 'February', 'Feb', 28),
(3, 'March', 'Mar', 31),
(4, 'April', 'Apr', 30),
(5, 'May', 'May', 31),
(6, 'June', 'Jun', 30),
(7, 'July', 'July', 31),
(8, 'August', 'Aug', 31),
(9, 'September', 'Sep', 30),
(10, 'October', 'Oct', 31),
(11, 'November', 'Nov', 30),
(12, 'December', 'Dec', 31);

-- --------------------------------------------------------

--
-- Table structure for table `nav`
--

CREATE TABLE `nav` (
  `id` int(11) NOT NULL,
  `nav_tittle` varchar(222) NOT NULL,
  `nav_function` varchar(222) NOT NULL,
  `nav_icon` varchar(222) NOT NULL,
  `sort` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `nav`
--

INSERT INTO `nav` (`id`, `nav_tittle`, `nav_function`, `nav_icon`, `sort`, `status`) VALUES
(1, 'Manager Staff ', 'load_all_staff(1)', 'fa fa-users', 2, '1'),
(2, 'Manage Payment', 'laod_manage_payment_details()', 'fa fa-money', 3, '1'),
(3, 'School Fees', 'window.location.assign(\'load_manage_school_fee.php\')', 'fa fa-money', 4, '1'),
(4, 'Attendance', 'window.location.assign(\'attendance.php\')', 'glyphicon glyphicon-calendar', 5, '1'),
(5, 'System Setup', 'system_setup()', 'glyphicon glyphicon-cog', 6, '1'),
(6, 'Events & Days', 'alert()', 'glyphicon glyphicon-calendar', 8, '0'),
(7, 'Manager Students', 'load_all_student()', 'fa fa-users', 1, '1'),
(8, 'Manage Examination', 'manage_examination()', 'glyphicon glyphicon-list-alt', 7, '1'),
(10, 'Profile', 'load_staff_profile()', 'fa fa-users', 9, '1'),
(11, 'Compute Result', 'load_contineous_accessment()', 'fa fa-file', 10, '1'),
(12, 'Staff Payment', 'payment_voucher()', 'fa fa-money', 12, '1'),
(13, 'Message Center', 'alert()', 'glyphicon glyphicon-envelope', 13, '1'),
(14, 'Recycle Bin', 'load_recycle_bin()', 'glyphicon glyphicon-trash', 14, '0');

-- --------------------------------------------------------

--
-- Table structure for table `online_staffs`
--

CREATE TABLE `online_staffs` (
  `id` int(11) NOT NULL,
  `staff_info_id` int(11) NOT NULL,
  `status` enum('1','0','2') NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `online_staffs`
--

INSERT INTO `online_staffs` (`id`, `staff_info_id`, `status`) VALUES
(1, 1, '1'),
(2, 9, '1'),
(3, 14, '1');

-- --------------------------------------------------------

--
-- Table structure for table `payment_details`
--

CREATE TABLE `payment_details` (
  `payment_details_id` int(11) NOT NULL,
  `current_session_id` int(11) NOT NULL,
  `school_section_id` int(11) NOT NULL,
  `category` int(11) NOT NULL,
  `sex` varchar(222) NOT NULL,
  `payment_description` varchar(222) NOT NULL,
  `amount` int(11) NOT NULL,
  `status` enum('0','1','2') NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `payment_details`
--

INSERT INTO `payment_details` (`payment_details_id`, `current_session_id`, `school_section_id`, `category`, `sex`, `payment_description`, `amount`, `status`) VALUES
(1, 7, 4, 2, 'M', 'School Fees', 12000, '1'),
(2, 7, 4, 1, 'All', 'School Fees', 10000, '1'),
(3, 7, 4, 2, 'F', 'School Fees', 12500, '1'),
(4, 7, 2, 2, 'M', 'School Fees', 5600, '1'),
(5, 7, 2, 2, 'F', 'School Fees', 5700, '1'),
(6, 7, 2, 1, 'All', 'School Fees', 5000, '1'),
(7, 7, 3, 2, 'M', 'School Fees', 13000, '1'),
(8, 7, 3, 2, 'F', 'School Fees', 15000, '1'),
(9, 7, 3, 1, 'All', 'School Fees', 10000, '1');

-- --------------------------------------------------------

--
-- Table structure for table `qualifications`
--

CREATE TABLE `qualifications` (
  `id` int(11) NOT NULL,
  `qualification` varchar(200) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `qualifications`
--

INSERT INTO `qualifications` (`id`, `qualification`, `status`) VALUES
(1, 'HND', '1'),
(2, 'ND', '1'),
(3, 'NCE', '1'),
(4, 'PGDE', '1'),
(5, 'PRY. CERT', '1'),
(6, 'SEC. CERT', '1'),
(7, 'B.S.C', '1');

-- --------------------------------------------------------

--
-- Table structure for table `school_fees`
--

CREATE TABLE `school_fees` (
  `id` int(11) NOT NULL,
  `student_info_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `year` varchar(20) NOT NULL,
  `month` varchar(20) NOT NULL,
  `day` varchar(20) NOT NULL,
  `dateTime` datetime NOT NULL,
  `payment_type` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `payment_number` bigint(20) NOT NULL,
  `term_id` int(11) NOT NULL,
  `session_paid` int(11) NOT NULL,
  `term_paid` int(11) NOT NULL,
  `bursar_id` int(11) NOT NULL,
  `payment_madeBy` varchar(100) NOT NULL,
  `amount_paid` bigint(20) NOT NULL,
  `ballance` bigint(20) NOT NULL,
  `status` enum('0','1','2') NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `school_fees`
--

INSERT INTO `school_fees` (`id`, `student_info_id`, `class_id`, `year`, `month`, `day`, `dateTime`, `payment_type`, `session_id`, `payment_number`, `term_id`, `session_paid`, `term_paid`, `bursar_id`, `payment_madeBy`, `amount_paid`, `ballance`, `status`) VALUES
(1, 2, 16, '2020', '10', '31', '2020-10-31 06:46:23', 2, 7, 20103154623, 3, 0, 0, 0, 'SELFf', 1000, 9000, '1'),
(2, 1, 16, '2020', '10', '31', '2020-10-31 06:53:05', 2, 7, 20103155305, 3, 0, 0, 0, 'Mummy', 2000, 10000, '1'),
(3, 3, 12, '2020', '10', '31', '2020-10-31 07:24:44', 2, 7, 20103162444, 3, 0, 0, 0, 'self', 2000, 3600, '1'),
(4, 3, 12, '2020', '10', '31', '2020-10-31 07:24:51', 2, 7, 20103162451, 3, 0, 0, 0, 'self', 2000, 1600, '1'),
(5, 4, 14, '2020', '10', '31', '2020-10-31 09:21:38', 2, 7, 20103182138, 1, 0, 0, 0, 'SELF', 1000, 12000, '1'),
(6, 4, 14, '2020', '10', '31', '2020-10-31 09:22:31', 2, 7, 20103182231, 1, 0, 0, 0, 'Mummy', 11500, 500, '1'),
(7, 4, 14, '2020', '10', '31', '2020-10-31 09:23:22', 2, 7, 20103182322, 1, 0, 0, 0, 'Daddy', 500, 0, '2');

-- --------------------------------------------------------

--
-- Table structure for table `school_section`
--

CREATE TABLE `school_section` (
  `school_section_id` int(11) NOT NULL,
  `section_name` varchar(222) NOT NULL,
  `section_name_abr` varchar(222) NOT NULL,
  `abr` varchar(200) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `school_section`
--

INSERT INTO `school_section` (`school_section_id`, `section_name`, `section_name_abr`, `abr`, `status`) VALUES
(1, 'Nusery School', 'Nur Pupils', 'Nur', '1'),
(2, 'Primary School', 'Pry Pupils', 'Pry', '1'),
(3, 'Junior Secondary School', 'JSS Students', 'JSS', '1'),
(4, 'Senior Secondary School', 'SS Students', 'SS', '1'),
(5, 'KINDER-GATIN', 'KD PUPILS', 'KD', '1');

-- --------------------------------------------------------

--
-- Table structure for table `session`
--

CREATE TABLE `session` (
  `section_id` int(11) NOT NULL,
  `section` varchar(255) NOT NULL,
  `status` enum('0','1','2') NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `session`
--

INSERT INTO `session` (`section_id`, `section`, `status`) VALUES
(1, '2013/2014', '0'),
(2, '2014/2015', '0'),
(3, '2015/2016', '0'),
(4, '2016/2017', '0'),
(5, '2017/2018', '0'),
(6, '2018/2019', '0'),
(7, '2019/2020', '1'),
(8, '2020/2021', '0'),
(9, '2021/2022', '0'),
(10, '2022/2023', '0'),
(11, '2023/2024', '0'),
(12, '2024/2025', '0'),
(13, '2025/2026', '0');

-- --------------------------------------------------------

--
-- Table structure for table `staffs_payment_info`
--

CREATE TABLE `staffs_payment_info` (
  `id` int(11) NOT NULL,
  `staff_info_id` int(11) NOT NULL,
  `basic_sallary` int(11) NOT NULL,
  `designation_id` int(11) NOT NULL,
  `qual` int(11) NOT NULL,
  `bonus` int(11) NOT NULL,
  `a_i` int(11) NOT NULL,
  `tax` int(11) NOT NULL,
  `impress` int(11) NOT NULL,
  `medication` int(11) NOT NULL,
  `gen_tax` int(11) NOT NULL,
  `net_pay` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `month` int(11) NOT NULL,
  `status` enum('0','1','2') NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `staff_access`
--

CREATE TABLE `staff_access` (
  `id` int(11) NOT NULL,
  `staff_info_id` int(11) NOT NULL,
  `nav_id` int(11) NOT NULL,
  `status` enum('1','0','2') NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `staff_access`
--

INSERT INTO `staff_access` (`id`, `staff_info_id`, `nav_id`, `status`) VALUES
(1, 1, 1, '1'),
(2, 1, 2, '1'),
(3, 1, 3, '1'),
(4, 1, 4, '1'),
(5, 1, 5, '1'),
(6, 1, 6, '1'),
(7, 1, 7, '1'),
(8, 1, 8, '1'),
(9, 1, 4, '1'),
(10, 1, 3, '1'),
(11, 1, 2, '1'),
(12, 1, 6, '1'),
(13, 1, 11, '1'),
(14, 1, 10, '1'),
(15, 1, 7, '0'),
(16, 1, 12, '1'),
(17, 9, 3, '0'),
(18, 9, 12, '0'),
(19, 9, 11, '1'),
(20, 10, 11, '1'),
(21, 14, 11, '1'),
(22, 14, 3, '0'),
(23, 1, 14, '1'),
(24, 1, 13, '1');

-- --------------------------------------------------------

--
-- Table structure for table `staff_bank_details`
--

CREATE TABLE `staff_bank_details` (
  `id` int(11) NOT NULL,
  `staff_info_id` int(11) NOT NULL,
  `account_name` varchar(200) NOT NULL,
  `account_number` varchar(100) NOT NULL,
  `account_bvn` varchar(100) NOT NULL,
  `sort_code` varchar(30) NOT NULL,
  `account_type` varchar(30) NOT NULL,
  `bank` varchar(30) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `staff_bank_details`
--

INSERT INTO `staff_bank_details` (`id`, `staff_info_id`, `account_name`, `account_number`, `account_bvn`, `sort_code`, `account_type`, `bank`) VALUES
(1, 5, '', '', '', '', '', ''),
(2, 6, '', '', '', '', '', ''),
(3, 7, '', '', '', '', '', ''),
(4, 8, '', '', '', '', '', ''),
(5, 9, '', '', '', '', '', ''),
(6, 11, '', '', '', '', '', ''),
(7, 12, '', '', '', '', '', ''),
(8, 13, '', '', '', '', '', ''),
(9, 14, '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `staff_basic_allowance`
--

CREATE TABLE `staff_basic_allowance` (
  `id` int(11) NOT NULL,
  `staff_info_id` int(11) NOT NULL,
  `basic_allowance_id` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `staff_classes`
--

CREATE TABLE `staff_classes` (
  `id` int(11) NOT NULL,
  `staff_info_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `staff_classes`
--

INSERT INTO `staff_classes` (`id`, `staff_info_id`, `class_id`, `session_id`, `status`) VALUES
(1, 1, 13, 7, '0'),
(2, 9, 16, 7, '1'),
(3, 9, 17, 7, '1'),
(4, 9, 18, 7, '1'),
(5, 10, 14, 7, '1'),
(6, 10, 17, 7, '1'),
(7, 9, 14, 7, '1'),
(8, 14, 18, 7, '1'),
(9, 14, 16, 7, '1');

-- --------------------------------------------------------

--
-- Table structure for table `staff_info`
--

CREATE TABLE `staff_info` (
  `staff_info_id` int(11) NOT NULL,
  `staff_number` varchar(200) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `other_name` varchar(255) NOT NULL,
  `gender` enum('M','F') NOT NULL,
  `religion` varchar(100) NOT NULL,
  `marital_status` varchar(100) NOT NULL,
  `date_of_birth` date NOT NULL,
  `state_id` int(11) NOT NULL,
  `lga_id` int(11) NOT NULL,
  `tribe` varchar(100) NOT NULL,
  `email_address` varchar(255) NOT NULL,
  `phone_number` varchar(100) NOT NULL,
  `other_phone_number` varchar(100) NOT NULL,
  `residential_address` varchar(255) NOT NULL,
  `postal_code` varchar(255) NOT NULL,
  `next_of_kin` varchar(255) NOT NULL,
  `next_of_kin_phone_number` varchar(255) NOT NULL,
  `relationship_with_next_of_kin` varchar(255) NOT NULL,
  `next_of_kin_residential_address` varchar(255) NOT NULL,
  `next_of_kin_postal_code` varchar(255) NOT NULL,
  `highest_qualification` varchar(255) NOT NULL,
  `designation_id` int(11) NOT NULL,
  `basic_sallary` int(11) NOT NULL,
  `bonus` int(11) NOT NULL,
  `tax` int(11) NOT NULL,
  `medication` int(11) NOT NULL,
  `impress` int(11) NOT NULL,
  `staff_type` int(11) NOT NULL,
  `section` enum('1','2') NOT NULL DEFAULT '2',
  `school` varchar(255) NOT NULL,
  `date_obtained` date NOT NULL,
  `refree` varchar(255) NOT NULL,
  `refree_hone_number` varchar(100) NOT NULL,
  `date_staff_employed` date NOT NULL,
  `image_name` varchar(255) NOT NULL,
  `status` enum('1','0','2','3','4') NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `staff_info`
--

INSERT INTO `staff_info` (`staff_info_id`, `staff_number`, `first_name`, `last_name`, `other_name`, `gender`, `religion`, `marital_status`, `date_of_birth`, `state_id`, `lga_id`, `tribe`, `email_address`, `phone_number`, `other_phone_number`, `residential_address`, `postal_code`, `next_of_kin`, `next_of_kin_phone_number`, `relationship_with_next_of_kin`, `next_of_kin_residential_address`, `next_of_kin_postal_code`, `highest_qualification`, `designation_id`, `basic_sallary`, `bonus`, `tax`, `medication`, `impress`, `staff_type`, `section`, `school`, `date_obtained`, `refree`, `refree_hone_number`, `date_staff_employed`, `image_name`, `status`) VALUES
(1, 'PSS/ADMIN/0001', 'Abdulkarim', 'Abdullalhi', '', 'M', 'Islam', 'Single', '1994-11-13', 27, 258, 'Yoruba', 'abdulkarimabdullahi365@gmail.com', '08130051228', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0, 0, 0, 1, '2', '', '0000-00-00', '', '', '0000-00-00', 'staff_passport1.png', '1'),
(13, 'PSS/STAFF/0002', 'Abdulkarim', 'Abdullahi', '', 'M', '', 'single', '0000-00-00', 0, 0, '', 'herbdukareemh8@gmail.com', '787888', '', 'IBrahim Badamasi Babangida University, Lapai Niger State', '911101', '', '', '', '', '', 'B.S.C', 0, 0, 0, 0, 0, 0, 3, '2', '', '0000-00-00', '', '', '2020-11-13', '', '1'),
(14, 'PSS/STAFF/0003', 'MUSA', 'Abdullahi', '', 'M', 'Muslim', '', '0000-00-00', 0, 0, '', 'herbdukareem333h@gmail.com', '5555555555555', '', 'IBrahim Badamasi Babangida University, Lapai Niger State', '911101', '', '', '', '', '', 'B.S.C', 0, 0, 0, 0, 0, 0, 4, '2', 'IBB LAPAI', '0000-00-00', '', '', '2020-11-13', '', '1'),
(12, 'PSS/STAFF/0001', 'Abdulkarim', 'Abdullahi', '', 'M', 'Muslim', 'single', '2020-12-30', 1, 1, ',s,', 'herbdukareemh@gmail.com', '4736445555', '', 'IBrahim Badamasi Babangida University, Lapai Niger State', '911101', '', '', '', '', '', 'B.S.C', 0, 0, 0, 0, 0, 0, 4, '2', 'IBB LAPAI', '0000-00-00', '', '', '2020-11-13', '', '1');

-- --------------------------------------------------------

--
-- Table structure for table `staff_login_info`
--

CREATE TABLE `staff_login_info` (
  `staff_login_info_id` int(11) NOT NULL,
  `staff_info_id` int(11) NOT NULL,
  `staff_login_id` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `designation_id` int(11) NOT NULL,
  `type` int(11) DEFAULT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `staff_login_info`
--

INSERT INTO `staff_login_info` (`staff_login_info_id`, `staff_info_id`, `staff_login_id`, `password`, `designation_id`, `type`, `status`) VALUES
(1, 1, 'PSS/ADMIN/0001', '81dc9bdb52d04dc20036dbd8313ed055', 1, NULL, '1'),
(10, 14, 'PSS/STAFF/0003', '81dc9bdb52d04dc20036dbd8313ed055', 0, 4, '1'),
(9, 13, 'PSS/STAFF/0002', 'e7d65f2cdb0ddc66b549e83107b71fa0', 0, 3, '1'),
(8, 12, 'PSS/STAFF/0001', '3101021adedf45f45cbd8ba4a1d3583f', 0, 4, '1');

-- --------------------------------------------------------

--
-- Table structure for table `staff_socials`
--

CREATE TABLE `staff_socials` (
  `id` int(11) NOT NULL,
  `staff_info_id` int(11) NOT NULL,
  `face_book_name` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `staff_socials`
--

INSERT INTO `staff_socials` (`id`, `staff_info_id`, `face_book_name`) VALUES
(1, 5, ''),
(2, 6, ''),
(3, 7, ''),
(4, 8, ''),
(5, 9, ''),
(6, 11, ''),
(7, 12, ''),
(8, 13, ''),
(9, 14, '');

-- --------------------------------------------------------

--
-- Table structure for table `staff_subjects`
--

CREATE TABLE `staff_subjects` (
  `id` int(11) NOT NULL,
  `staff_info_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `staff_subjects`
--

INSERT INTO `staff_subjects` (`id`, `staff_info_id`, `subject_id`, `section_id`, `status`) VALUES
(1, 14, 1, 4, '1'),
(2, 14, 5, 4, '1');

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

CREATE TABLE `states` (
  `state_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COMMENT='States in Nigeria.';

--
-- Dumping data for table `states`
--

INSERT INTO `states` (`state_id`, `name`) VALUES
(1, 'Abia'),
(2, 'Adamawa'),
(3, 'Akwa Ibom'),
(4, 'Anambra'),
(5, 'Bauchi'),
(6, 'Bayelsa'),
(7, 'Benue'),
(8, 'Borno'),
(9, 'Cross River'),
(10, 'Delta'),
(11, 'Ebonyi'),
(12, 'Edo'),
(13, 'Ekiti'),
(14, 'Enugu'),
(15, 'FCT'),
(16, 'Gombe'),
(17, 'Imo'),
(18, 'Jigawa'),
(19, 'Kaduna'),
(20, 'Kano'),
(21, 'Katsina'),
(22, 'Kebbi'),
(23, 'Kogi'),
(24, 'Kwara'),
(25, 'Lagos'),
(26, 'Nasarawa'),
(27, 'Niger'),
(28, 'Ogun'),
(29, 'Ondo'),
(30, 'Osun'),
(31, 'Oyo'),
(32, 'Plateau'),
(33, 'Rivers'),
(34, 'Sokoto'),
(35, 'Taraba'),
(36, 'Yobe'),
(37, 'Zamfara');

-- --------------------------------------------------------

--
-- Table structure for table `state_of_origin`
--

CREATE TABLE `state_of_origin` (
  `state_id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='States in Nigeria.';

--
-- Dumping data for table `state_of_origin`
--

INSERT INTO `state_of_origin` (`state_id`, `name`) VALUES
(1, 'Abia State'),
(2, 'Adamawa State'),
(3, 'Akwa Ibom State'),
(4, 'Anambra State'),
(5, 'Bauchi State'),
(6, 'Bayelsa State'),
(7, 'Benue State'),
(8, 'Borno State'),
(9, 'Cross River State'),
(10, 'Delta State'),
(11, 'Ebonyi State'),
(12, 'Edo State'),
(13, 'Ekiti State'),
(14, 'Enugu State'),
(15, 'FCT'),
(16, 'Gombe State'),
(17, 'Imo State'),
(18, 'Jigawa State'),
(19, 'Kaduna State'),
(20, 'Kano State'),
(21, 'Katsina State'),
(22, 'Kebbi State'),
(23, 'Kogi State'),
(24, 'Kwara State'),
(25, 'Lagos State'),
(26, 'Nasarawa State'),
(27, 'Niger State'),
(28, 'Ogun State'),
(29, 'Ondo State'),
(30, 'Osun State'),
(31, 'Oyo State'),
(32, 'Plateau State'),
(33, 'Rivers State'),
(34, 'Sokoto State'),
(35, 'Taraba State'),
(36, 'Yobe State'),
(37, 'Zamfara State');

-- --------------------------------------------------------

--
-- Table structure for table `student_attendance`
--

CREATE TABLE `student_attendance` (
  `attendance_id` int(11) NOT NULL,
  `student_info_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `term_id` int(11) NOT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `session_id` int(11) NOT NULL,
  `year` year(4) DEFAULT NULL,
  `month` int(11) NOT NULL,
  `day` int(11) NOT NULL,
  `status` enum('0','1','2') NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `student_classes`
--

CREATE TABLE `student_classes` (
  `student_class_id` int(11) NOT NULL,
  `student_info_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `term_id` int(11) DEFAULT NULL,
  `school_fees` int(11) NOT NULL,
  `date_promoted_enrolled` date NOT NULL,
  `gender` varchar(20) NOT NULL,
  `status` enum('1','0','2','3') NOT NULL DEFAULT '2',
  `last_date_modified` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `student_classes`
--

INSERT INTO `student_classes` (`student_class_id`, `student_info_id`, `class_id`, `session_id`, `term_id`, `school_fees`, `date_promoted_enrolled`, `gender`, `status`, `last_date_modified`) VALUES
(1, 1, 16, 7, 3, 12000, '2020-10-30', '', '2', '0000-00-00'),
(2, 2, 16, 7, 3, 10000, '2020-10-31', '', '1', '0000-00-00'),
(3, 3, 12, 7, 3, 5600, '2020-10-31', '', '2', '0000-00-00'),
(4, 4, 14, 7, 1, 0, '2020-10-31', '', '2', '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `student_class_status`
--

CREATE TABLE `student_class_status` (
  `id` int(11) NOT NULL,
  `student_info_id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `term_id` int(11) NOT NULL,
  `school_fees` int(11) NOT NULL,
  `student_status` enum('1','2') NOT NULL,
  `status` enum('0','1','3') NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `student_info`
--

CREATE TABLE `student_info` (
  `student_info_id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `other_name` varchar(255) NOT NULL,
  `gender` enum('M','F') NOT NULL,
  `religion` varchar(100) NOT NULL,
  `date_of_birth` date NOT NULL,
  `state_id` int(11) NOT NULL,
  `lga_id` int(11) NOT NULL,
  `email_address` varchar(255) NOT NULL,
  `tribe` varchar(100) NOT NULL,
  `house` varchar(100) NOT NULL,
  `phone_number` varchar(100) NOT NULL,
  `guidian_other_phone_number` varchar(100) NOT NULL,
  `residential_address` varchar(255) NOT NULL,
  `postal_code` varchar(255) NOT NULL,
  `guidian_name` varchar(255) NOT NULL,
  `guidian_phone_number` varchar(255) NOT NULL,
  `guadian_relationship` varchar(255) NOT NULL,
  `guidian_address` varchar(255) NOT NULL,
  `guidain_occupation` varchar(255) NOT NULL,
  `previous_school` varchar(255) NOT NULL,
  `reason_for_leaving_the_school` varchar(255) NOT NULL DEFAULT '1',
  `date_enrolled` date NOT NULL,
  `image_name` varchar(255) NOT NULL,
  `admitted_year` year(4) DEFAULT NULL,
  `status` enum('1','0','2','3','4','5') NOT NULL DEFAULT '2'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `student_info`
--

INSERT INTO `student_info` (`student_info_id`, `first_name`, `last_name`, `other_name`, `gender`, `religion`, `date_of_birth`, `state_id`, `lga_id`, `email_address`, `tribe`, `house`, `phone_number`, `guidian_other_phone_number`, `residential_address`, `postal_code`, `guidian_name`, `guidian_phone_number`, `guadian_relationship`, `guidian_address`, `guidain_occupation`, `previous_school`, `reason_for_leaving_the_school`, `date_enrolled`, `image_name`, `admitted_year`, `status`) VALUES
(1, 'Abdulkarim', 'Abdullahi', '', 'M', 'Muslim', '1993-11-30', 27, 528, 'herbdukareemh@gmail.com', 'Yoruba', '', '', '', 'IBrahim Badamasi Babangida University, Lapai Niger State', '911101', 'll', '99', 'Father', '', '', 'lll', 'kllllkll', '2020-10-30', '', NULL, '2'),
(2, 'HABIBA', 'MUSA', '', 'F', 'Muslim', '2000-12-31', 4, 68, '', 'NUPE', '', '', '', '', '', 'DLDLLD', 'DLLD', 'Father', '', '', 'DKLLKLL', 'LDLDD', '2020-10-31', '', NULL, '1'),
(3, 'Sani', 'Busu', '', 'M', 'Muslim', '2009-08-29', 27, 524, 'jjjjj', 'skskks', '', '5768790', '5768798', '', '', 'yrtuyiuoi', 'iy7687980', 'Father', '', '', 'jwjj', 'jjjj', '2020-10-31', '', NULL, '2'),
(4, 'MUHAMMED', 'BABA', '', 'M', 'Muslim', '2020-12-31', 2, 23, 'WEJEJE', 'KKDKDK', '', '999393', '929292929', 'WEKKWKWK', 'KKWK', 'KWKWKK', 'KKKKW', 'Father', '', '', 'KSKSKK', 'KKSKSK', '2020-10-31', '', NULL, '1');

-- --------------------------------------------------------

--
-- Table structure for table `student_login_info`
--

CREATE TABLE `student_login_info` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `student_no` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `student_login_info`
--

INSERT INTO `student_login_info` (`id`, `student_id`, `student_no`, `password`, `last_login`, `status`) VALUES
(11, 2, 'PSS/20/0001', 'OGM1ZTQ1OWU3ZWIzYWI2NDkyYjg5OTk2YjgxMDBmMjk4MWRjOWJkYjUyZDA0ZGMyMDAzNmRiZDgzMTNlZDA1NQ==', NULL, '1');

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE `subject` (
  `id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `subject_code` varchar(255) NOT NULL,
  `school_section` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `subject`
--

INSERT INTO `subject` (`id`, `subject`, `subject_code`, `school_section`, `status`) VALUES
(1, 'MATHEMATICS', 'MTH004', 4, '1'),
(2, 'ENGLISH LANGUAGE', 'ENG004', 4, '1'),
(3, 'GEOGRAPHY', 'GEO004', 4, '1'),
(4, 'CHEMISTRY', 'CHM004', 4, '1'),
(5, 'BIOLOGY', 'BIO004', 4, '1'),
(6, 'Mathematice', 'MTH002', 2, '1');

-- --------------------------------------------------------

--
-- Table structure for table `tax_on_payment`
--

CREATE TABLE `tax_on_payment` (
  `id` int(11) NOT NULL,
  `tax` int(11) NOT NULL,
  `unit` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tax_on_payment`
--

INSERT INTO `tax_on_payment` (`id`, `tax`, `unit`) VALUES
(1, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `term`
--

CREATE TABLE `term` (
  `id` int(11) NOT NULL,
  `term` int(11) NOT NULL,
  `description` varchar(20) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `term`
--

INSERT INTO `term` (`id`, `term`, `description`, `status`) VALUES
(1, 1, 'First Term', '1'),
(2, 2, 'Second Term', '0'),
(3, 3, 'Third Term', '0');

-- --------------------------------------------------------

--
-- Table structure for table `term_result`
--

CREATE TABLE `term_result` (
  `id` int(11) NOT NULL,
  `student_info_id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `term_id` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  `position` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `term_result`
--

INSERT INTO `term_result` (`id`, `student_info_id`, `session_id`, `class_id`, `term_id`, `total`, `position`, `status`) VALUES
(1, 1, 7, 16, 3, 70, 1, '1'),
(2, 0, 7, 16, 1, 0, 2, '1'),
(3, 4, 7, 14, 1, 57, 1, '1'),
(4, 2, 7, 16, 1, 116, 1, '1');

-- --------------------------------------------------------

--
-- Table structure for table `waec_neco_candidates`
--

CREATE TABLE `waec_neco_candidates` (
  `id` int(11) NOT NULL,
  `student_info_id` int(11) NOT NULL,
  `exam_reg_for` enum('0','1','2') NOT NULL DEFAULT '0',
  `student_status` enum('1','2') NOT NULL,
  `waec_reg_no` varchar(225) NOT NULL,
  `neco_reg_no` varchar(225) NOT NULL,
  `session_id` int(11) NOT NULL,
  `gender` varchar(20) NOT NULL,
  `date_modified` datetime NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `year`
--

CREATE TABLE `year` (
  `id` int(11) NOT NULL,
  `year` year(4) NOT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `year`
--

INSERT INTO `year` (`id`, `year`, `status`) VALUES
(1, 2017, '0'),
(2, 2016, '0'),
(3, 2018, '0'),
(4, 2019, '0'),
(5, 2015, '0'),
(6, 2014, '0'),
(7, 2013, '0'),
(8, 2012, '0'),
(9, 2020, '1'),
(10, 2021, '0'),
(11, 2022, '0');

-- --------------------------------------------------------

--
-- Table structure for table `yearly_result`
--

CREATE TABLE `yearly_result` (
  `id` int(11) NOT NULL,
  `student_info_id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `average_score` int(11) NOT NULL,
  `total_score` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `yearly_result`
--

INSERT INTO `yearly_result` (`id`, `student_info_id`, `session_id`, `class_id`, `average_score`, `total_score`, `status`) VALUES
(1, 1, 7, 16, 23, 70, '1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_info`
--
ALTER TABLE `admin_info`
  ADD PRIMARY KEY (`admin_info_id`);

--
-- Indexes for table `admin_login_info`
--
ALTER TABLE `admin_login_info`
  ADD PRIMARY KEY (`admin_login_info_id`);

--
-- Indexes for table `annual_sallary_increament`
--
ALTER TABLE `annual_sallary_increament`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `baisic_sallary`
--
ALTER TABLE `baisic_sallary`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `basic_allowance`
--
ALTER TABLE `basic_allowance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `candidate_payment`
--
ALTER TABLE `candidate_payment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `candidate_payment_definition`
--
ALTER TABLE `candidate_payment_definition`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `candidate_subjects`
--
ALTER TABLE `candidate_subjects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`class_id`);

--
-- Indexes for table `contineous_accessment`
--
ALTER TABLE `contineous_accessment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `designations`
--
ALTER TABLE `designations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lga`
--
ALTER TABLE `lga`
  ADD PRIMARY KEY (`local_id`);

--
-- Indexes for table `months`
--
ALTER TABLE `months`
  ADD PRIMARY KEY (`month_id`);

--
-- Indexes for table `nav`
--
ALTER TABLE `nav`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `online_staffs`
--
ALTER TABLE `online_staffs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_details`
--
ALTER TABLE `payment_details`
  ADD PRIMARY KEY (`payment_details_id`);

--
-- Indexes for table `qualifications`
--
ALTER TABLE `qualifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `school_fees`
--
ALTER TABLE `school_fees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payment_number` (`payment_number`);

--
-- Indexes for table `school_section`
--
ALTER TABLE `school_section`
  ADD PRIMARY KEY (`school_section_id`);

--
-- Indexes for table `session`
--
ALTER TABLE `session`
  ADD PRIMARY KEY (`section_id`);

--
-- Indexes for table `staffs_payment_info`
--
ALTER TABLE `staffs_payment_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff_access`
--
ALTER TABLE `staff_access`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff_bank_details`
--
ALTER TABLE `staff_bank_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff_basic_allowance`
--
ALTER TABLE `staff_basic_allowance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff_classes`
--
ALTER TABLE `staff_classes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff_info`
--
ALTER TABLE `staff_info`
  ADD PRIMARY KEY (`staff_info_id`);

--
-- Indexes for table `staff_login_info`
--
ALTER TABLE `staff_login_info`
  ADD PRIMARY KEY (`staff_login_info_id`),
  ADD UNIQUE KEY `staff_login_id` (`staff_login_id`),
  ADD UNIQUE KEY `staff_login_id_2` (`staff_login_id`);

--
-- Indexes for table `staff_socials`
--
ALTER TABLE `staff_socials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff_subjects`
--
ALTER TABLE `staff_subjects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `states`
--
ALTER TABLE `states`
  ADD PRIMARY KEY (`state_id`);

--
-- Indexes for table `state_of_origin`
--
ALTER TABLE `state_of_origin`
  ADD PRIMARY KEY (`state_id`);

--
-- Indexes for table `student_attendance`
--
ALTER TABLE `student_attendance`
  ADD PRIMARY KEY (`attendance_id`);

--
-- Indexes for table `student_classes`
--
ALTER TABLE `student_classes`
  ADD PRIMARY KEY (`student_class_id`);

--
-- Indexes for table `student_class_status`
--
ALTER TABLE `student_class_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_info`
--
ALTER TABLE `student_info`
  ADD PRIMARY KEY (`student_info_id`);
ALTER TABLE `student_info` ADD FULLTEXT KEY `first_name` (`first_name`);
ALTER TABLE `student_info` ADD FULLTEXT KEY `last_name` (`last_name`);

--
-- Indexes for table `student_login_info`
--
ALTER TABLE `student_login_info`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_no` (`student_no`);

--
-- Indexes for table `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subject_code` (`subject_code`);

--
-- Indexes for table `tax_on_payment`
--
ALTER TABLE `tax_on_payment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `term`
--
ALTER TABLE `term`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `term_result`
--
ALTER TABLE `term_result`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `waec_neco_candidates`
--
ALTER TABLE `waec_neco_candidates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `year`
--
ALTER TABLE `year`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `yearly_result`
--
ALTER TABLE `yearly_result`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_info`
--
ALTER TABLE `admin_info`
  MODIFY `admin_info_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `admin_login_info`
--
ALTER TABLE `admin_login_info`
  MODIFY `admin_login_info_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `annual_sallary_increament`
--
ALTER TABLE `annual_sallary_increament`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `baisic_sallary`
--
ALTER TABLE `baisic_sallary`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `basic_allowance`
--
ALTER TABLE `basic_allowance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `candidate_payment`
--
ALTER TABLE `candidate_payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `candidate_payment_definition`
--
ALTER TABLE `candidate_payment_definition`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `candidate_subjects`
--
ALTER TABLE `candidate_subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `class_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `contineous_accessment`
--
ALTER TABLE `contineous_accessment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `designations`
--
ALTER TABLE `designations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `lga`
--
ALTER TABLE `lga`
  MODIFY `local_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=738;

--
-- AUTO_INCREMENT for table `months`
--
ALTER TABLE `months`
  MODIFY `month_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `nav`
--
ALTER TABLE `nav`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `online_staffs`
--
ALTER TABLE `online_staffs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `payment_details`
--
ALTER TABLE `payment_details`
  MODIFY `payment_details_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `qualifications`
--
ALTER TABLE `qualifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `school_fees`
--
ALTER TABLE `school_fees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `school_section`
--
ALTER TABLE `school_section`
  MODIFY `school_section_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `session`
--
ALTER TABLE `session`
  MODIFY `section_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `staffs_payment_info`
--
ALTER TABLE `staffs_payment_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staff_access`
--
ALTER TABLE `staff_access`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `staff_bank_details`
--
ALTER TABLE `staff_bank_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `staff_basic_allowance`
--
ALTER TABLE `staff_basic_allowance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staff_classes`
--
ALTER TABLE `staff_classes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `staff_info`
--
ALTER TABLE `staff_info`
  MODIFY `staff_info_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `staff_login_info`
--
ALTER TABLE `staff_login_info`
  MODIFY `staff_login_info_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `staff_socials`
--
ALTER TABLE `staff_socials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `staff_subjects`
--
ALTER TABLE `staff_subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `state_of_origin`
--
ALTER TABLE `state_of_origin`
  MODIFY `state_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `student_attendance`
--
ALTER TABLE `student_attendance`
  MODIFY `attendance_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_classes`
--
ALTER TABLE `student_classes`
  MODIFY `student_class_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `student_class_status`
--
ALTER TABLE `student_class_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_info`
--
ALTER TABLE `student_info`
  MODIFY `student_info_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `student_login_info`
--
ALTER TABLE `student_login_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `subject`
--
ALTER TABLE `subject`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tax_on_payment`
--
ALTER TABLE `tax_on_payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `term`
--
ALTER TABLE `term`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `term_result`
--
ALTER TABLE `term_result`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `waec_neco_candidates`
--
ALTER TABLE `waec_neco_candidates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `year`
--
ALTER TABLE `year`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `yearly_result`
--
ALTER TABLE `yearly_result`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
