-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 11, 2016 at 03:24 PM
-- Server version: 5.6.11
-- PHP Version: 5.5.1

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `mrg`
--
CREATE DATABASE IF NOT EXISTS `mrg` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `mrg`;

-- --------------------------------------------------------

--
-- Table structure for table `bookmarks`
--

CREATE TABLE IF NOT EXISTS `bookmarks` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Índice de la tabla',
  `url` varchar(555) DEFAULT NULL COMMENT 'Contiene la dirección URL del bookmark',
  `fecha` datetime DEFAULT NULL COMMENT 'Fecha en que se guardo el bookmark',
  `orden` int(4) DEFAULT NULL COMMENT 'Orden en que se va a mostrar el registro',
  `estado` varchar(1) DEFAULT NULL COMMENT '1: Activo 0: Inactivo',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `bookmarks`
--

INSERT INTO `bookmarks` (`id`, `url`, `fecha`, `orden`, `estado`) VALUES
(3, 'http://php.net/manual/en/function.glob.php', '2016-04-05 05:43:18', 1, '1'),
(4, 'http://stackoverflow.com/questions/15633341/jquery-ui-sortable-then-write-order-into-a-database', '2016-04-05 05:43:26', 2, '1'),
(5, 'https://github.com/luisnicg/jQuery-Sortable-and-PHP', '2016-04-05 05:43:32', 4, '1'),
(6, 'http://www.hongkiat.com/blog/jquery-ui-sortable/', '2016-04-05 05:44:13', 6, '1'),
(7, 'http://demo.hongkiat.com/jquery-ui-sortable/', '2016-04-05 05:44:20', 5, '1'),
(8, 'https://www.google.co.in/', '2016-04-05 05:45:05', 3, '1');

-- --------------------------------------------------------

--
-- Table structure for table `options`
--

CREATE TABLE IF NOT EXISTS `options` (
  `option_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `question_id` bigint(20) NOT NULL,
  `segment_id` bigint(20) NOT NULL,
  `option_text` text,
  PRIMARY KEY (`option_id`),
  KEY `fk_options_segment_id` (`segment_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

--
-- Dumping data for table `options`
--

INSERT INTO `options` (`option_id`, `question_id`, `segment_id`, `option_text`) VALUES
(1, 1, 1, 'col 0 opt 1'),
(2, 1, 1, 'col 0 opt 2'),
(3, 1, 1, 'col 0 opt 3'),
(4, 1, 2, 'col 1 opt 1'),
(5, 1, 2, 'col 1 opt 2'),
(6, 1, 2, 'col 1 opt 3');

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE IF NOT EXISTS `questions` (
  `question_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `question_type` smallint(1) NOT NULL,
  `statement` text NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `correct_option` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`question_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`question_id`, `question_type`, `statement`, `is_active`, `correct_option`) VALUES
(1, 2, 'Match the following for the equation <p >`\\int_a^b f(x) \\ dx = \\int_a^b frac((x-a)(b-a)f(x))   ((x-a)(b-a))   \\ dx  `</p>', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `question_correct_matches`
--

CREATE TABLE IF NOT EXISTS `question_correct_matches` (
  `correct_match_lookup_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `question_id` bigint(20) DEFAULT NULL,
  `match_id` bigint(20) DEFAULT NULL,
  `option_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`correct_match_lookup_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

--
-- Dumping data for table `question_correct_matches`
--

INSERT INTO `question_correct_matches` (`correct_match_lookup_id`, `question_id`, `match_id`, `option_id`) VALUES
(1, 1, 1, 3),
(2, 1, 2, 4),
(3, 1, 3, 5),
(4, 2, 4, 17),
(5, 2, 5, 18),
(6, 2, 6, 19),
(7, 3, 7, 8),
(8, 3, 8, 9),
(9, 3, 9, 10);

-- --------------------------------------------------------

--
-- Table structure for table `question_segments`
--

CREATE TABLE IF NOT EXISTS `question_segments` (
  `segment_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `question_id` bigint(20) NOT NULL,
  `segment_text` varchar(255) NOT NULL,
  `correct_options` varchar(255) DEFAULT NULL,
  `is_active` smallint(1) DEFAULT '1',
  PRIMARY KEY (`segment_id`),
  KEY `fk_que_segments_question_id` (`question_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

--
-- Dumping data for table `question_segments`
--

INSERT INTO `question_segments` (`segment_id`, `question_id`, `segment_text`, `correct_options`, `is_active`) VALUES
(1, 1, 'This is true', 'abc', 1),
(2, 1, 'This is false', 'xyz', 1);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `options`
--
ALTER TABLE `options`
  ADD CONSTRAINT `fk_options_segment_id` FOREIGN KEY (`segment_id`) REFERENCES `question_segments` (`segment_id`);

--
-- Constraints for table `question_segments`
--
ALTER TABLE `question_segments`
  ADD CONSTRAINT `fk_que_segments_question_id` FOREIGN KEY (`question_id`) REFERENCES `questions` (`question_id`);
SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
