-- phpMyAdmin SQL Dump
-- version 4.0.6
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Янв 10 2014 г., 09:48
-- Версия сервера: 5.1.70
-- Версия PHP: 5.4.20-pl0-gentoo

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `itbase`
--

-- --------------------------------------------------------

--
-- Структура таблицы `alarms`
--

CREATE TABLE IF NOT EXISTS `alarms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `act` varchar(256) NOT NULL,
  `deadline` date NOT NULL,
  `startalarm` date NOT NULL,
  `email` varchar(128) NOT NULL,
  `message` varchar(512) NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `alarms`
--

INSERT INTO `alarms` (`id`, `act`, `deadline`, `startalarm`, `email`, `message`, `active`) VALUES
(1, 'Прогул', '2014-01-31', '2014-01-16', 'tarkvsg@gmail.com', 'test1', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
