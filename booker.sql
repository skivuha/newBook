-- phpMyAdmin SQL Dump
-- version 4.0.10.6
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3306
-- Время создания: Апр 23 2015 г., 10:45
-- Версия сервера: 5.5.41-log
-- Версия PHP: 5.3.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `booker`
--

-- --------------------------------------------------------

--
-- Структура таблицы `appointments`
--

CREATE TABLE IF NOT EXISTS `appointments` (
  `id_appointment` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(255) NOT NULL,
  `id_employee` int(11) NOT NULL,
  `start` varchar(255) NOT NULL,
  `end` varchar(255) NOT NULL,
  `id_room` int(11) NOT NULL,
  `recursion` int(11) NOT NULL,
  `submitted` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_appointment`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=229 ;

--
-- Дамп данных таблицы `appointments`
--

INSERT INTO `appointments` (`id_appointment`, `description`, `id_employee`, `start`, `end`, `id_room`, `recursion`, `submitted`) VALUES
(186, '', 1, '1430865000', '1430870400', 1, 184, '2015-04-22 07:31:15'),
(187, '', 1, '1431466200', '1431471600', 1, 184, '2015-04-21 21:10:14'),
(190, '', 0, '1429750800', '1429765200', 1, 0, '2015-04-21 21:28:12'),
(191, '', 1, '1429664400', '1429671600', 1, 191, '2015-04-21 21:34:51'),
(192, 'aasdfasdf', 6, '1430262000', '1430298000', 1, 191, '2015-04-22 20:51:16'),
(193, '', 1, '1430874000', '1430881200', 1, 191, '2015-04-21 21:34:51'),
(194, '', 1, '1431478800', '1431486000', 1, 191, '2015-04-21 21:34:51'),
(195, '', 1, '1432083600', '1432090800', 1, 191, '2015-04-21 21:34:51'),
(196, '', 1, '1429653600', '1429655400', 1, 0, '2015-04-21 21:18:34'),
(197, 'adf', 4, '1430089200', '1430096400', 1, 0, '2015-04-21 21:24:48'),
(204, '', 1, '1434924000', '1434927600', 2, 204, '2015-04-21 21:43:38'),
(205, '', 1, '1435528800', '1435532400', 2, 204, '2015-04-21 21:43:38'),
(206, '', 1, '1436133600', '1436137200', 2, 204, '2015-04-21 21:43:38'),
(207, '', 1, '1436738400', '1436742000', 2, 204, '2015-04-21 21:43:38'),
(208, '', 1, '1437343200', '1437346800', 2, 204, '2015-04-21 21:43:38'),
(209, '', 1, '1430110800', '1430114400', 1, 0, '2015-04-22 05:56:34'),
(210, '', 1, '1430089200', '1430092800', 2, 0, '2015-04-22 05:56:52'),
(211, '', 1, '1429689600', '1429693200', 2, 211, '2015-04-22 06:10:18'),
(212, '', 1, '1430294400', '1430298000', 2, 211, '2015-04-22 06:10:18'),
(213, '', 1, '1430899200', '1430902800', 2, 211, '2015-04-22 06:10:18'),
(214, '', 1, '1431504000', '1431507600', 2, 211, '2015-04-22 06:10:18'),
(215, '', 4, '1429689600', '1429693200', 1, 215, '2015-04-22 07:01:58'),
(216, '', 1, '1430899200', '1430902800', 1, 215, '2015-04-22 06:10:54'),
(217, '', 1, '1432108800', '1432112400', 1, 215, '2015-04-22 06:10:54'),
(218, '', 1, '1429700400', '1429704000', 2, 218, '2015-04-22 06:11:23'),
(219, '', 1, '1430910000', '1430913600', 2, 218, '2015-04-22 06:11:23'),
(220, '', 1, '1430168400', '1430170200', 2, 220, '2015-04-22 06:44:46'),
(221, '', 1, '1430773200', '1430775000', 2, 220, '2015-04-22 06:44:46'),
(222, '', 1, '1431378000', '1431379800', 2, 220, '2015-04-22 06:44:46'),
(223, '', 1, '1431982800', '1431984600', 2, 220, '2015-04-22 06:44:46'),
(224, '', 1, '1432587600', '1432589400', 2, 220, '2015-04-22 06:44:46'),
(225, '', 1, '1430175600', '1430181000', 1, 225, '2015-04-22 19:42:12'),
(226, '', 1, '1431378000', '1431379800', 1, 225, '2015-04-22 06:46:40'),
(227, '', 1, '1429736400', '1429738200', 3, 227, '2015-04-22 06:53:11');

-- --------------------------------------------------------

--
-- Структура таблицы `employee`
--

CREATE TABLE IF NOT EXISTS `employee` (
  `id_employee` int(10) NOT NULL AUTO_INCREMENT,
  `name_employee` varchar(60) NOT NULL,
  `passwd_employee` varchar(255) NOT NULL,
  `key_employee` varchar(10) NOT NULL,
  `code_employee` varchar(10) NOT NULL,
  `mail_employee` varchar(255) NOT NULL,
  `role` enum('0','1') NOT NULL,
  PRIMARY KEY (`id_employee`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Дамп данных таблицы `employee`
--

INSERT INTO `employee` (`id_employee`, `name_employee`, `passwd_employee`, `key_employee`, `code_employee`, `mail_employee`, `role`) VALUES
(1, 'igor', 'adcb9bd51f5596d1f0f71e816356b386', '1111111111', '7f1b9816ia', 'igor@hotmail.com', '0'),
(2, 'test', '40b599578ad05afc27ceaac3dcaa83d2', 'eee9648d38', '', 'test@test.com', '0'),
(3, 'root', '8fff62860c81f63c43422def2aa40ced', '6853ieaf81', 'bff4945895', 'root@root.com', '1'),
(4, 'JJAbrams', '3554d7d2a8bf0bffa5e39ab10596ef92', '3bcm3c3i42', '', 'abrams@hotmail.com', '0'),
(5, 'newUser', '68fe98853a31f57bec1b4cafcc26a1d2', '44e14ae540', '', 'lala@yahoo.com', '0');

-- --------------------------------------------------------

--
-- Структура таблицы `rooms`
--

CREATE TABLE IF NOT EXISTS `rooms` (
  `id_room` int(11) NOT NULL AUTO_INCREMENT,
  `name_room` varchar(255) NOT NULL,
  PRIMARY KEY (`id_room`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `rooms`
--

INSERT INTO `rooms` (`id_room`, `name_room`) VALUES
(1, 'Boardroom #1'),
(2, 'Boardroom #2'),
(3, 'Boardroom #3');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
