-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Сен 06 2017 г., 12:56
-- Версия сервера: 5.5.25
-- Версия PHP: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `jap`
--

-- --------------------------------------------------------

--
-- Структура таблицы `punkt`
--

CREATE TABLE IF NOT EXISTS `punkt` (
  `kod` int(11) NOT NULL AUTO_INCREMENT,
  `kodmenu` int(11) NOT NULL,
  `kodrasdel` int(11) NOT NULL,
  `artist` varchar(255) NOT NULL,
  `song` varchar(255) NOT NULL,
  `link` text NOT NULL,
  `length` int(11) NOT NULL,
  `sort` int(11) NOT NULL,
  `vyvod` int(2) NOT NULL,
  `pictur` int(2) NOT NULL,
  `note` text NOT NULL,
  PRIMARY KEY (`kod`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
