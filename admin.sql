-- phpMyAdmin SQL Dump
-- version 4.0.6
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Дек 24 2013 г., 17:53
-- Версия сервера: 5.1.70
-- Версия PHP: 5.4.20-pl0-gentoo

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `admin`
--

-- --------------------------------------------------------

--
-- Структура таблицы `auth`
--

CREATE TABLE IF NOT EXISTS `auth` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `note` varchar(255) NOT NULL,
  `login` varchar(255) NOT NULL,
  `passwd` varchar(255) NOT NULL,
  `role_id` int(11) NOT NULL,
  `active` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `auth`
--

INSERT INTO `auth` (`id`, `note`, `login`, `passwd`, `role_id`, `active`) VALUES
(1, 'Владимир Cергеевич Гордюнин', 'vsg', '644964c54cf9aec12745f8501eeef440:138720914529d7e52b9743', 15, 1),
(2, 'Гайдуков Константин Владимирович', 'gkv', 'd3f427fbda2ec2b435b119c022bec1f2', 16, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `controllers`
--

CREATE TABLE IF NOT EXISTS `controllers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `class` varchar(36) NOT NULL,
  `section_id` varchar(45) DEFAULT NULL,
  `order` int(11) NOT NULL,
  `active` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=38 ;

--
-- Дамп данных таблицы `controllers`
--

INSERT INTO `controllers` (`id`, `name`, `class`, `section_id`, `order`, `active`) VALUES
(8, 'Пользователи', 'users', '1', 0, 1),
(9, 'Алиасы', 'aliases', '1', 1, 1),
(10, 'Домены', 'domains', '1', 3, 1),
(13, 'Разделы сайта', 'admin', '4', 0, 1),
(14, 'Роли', 'roles', '4', 1, 1),
(15, 'Пользователи', 'auth', '4', 2, 1),
(16, 'Сеть', 'badm', '7', 0, 1),
(17, 'Шаблоны(ред.)', 'btmpl', '7', 2, 1),
(30, 'Группы', 'groups', '1', 2, 1),
(31, 'Логи', 'logs', '1', 4, 1),
(34, 'Сервер имен', 'dns', '6', 0, 1),
(36, 'Архивинование', 'backup', '10', 0, 1),
(37, 'Контакты', 'bcont', '7', 1, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `rights`
--

CREATE TABLE IF NOT EXISTS `rights` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `control_id` int(11) NOT NULL,
  `slevel_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ctrlrole` (`role_id`,`control_id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=36 ;

--
-- Дамп данных таблицы `rights`
--

INSERT INTO `rights` (`id`, `role_id`, `control_id`, `slevel_id`) VALUES
(1, 15, 15, 3),
(2, 15, 13, 3),
(3, 15, 16, 3),
(4, 15, 17, 3),
(6, 15, 9, 3),
(7, 15, 10, 3),
(8, 15, 31, 3),
(9, 15, 14, 3),
(10, 15, 8, 3),
(11, 14, 8, 2),
(12, 14, 31, 3),
(13, 14, 10, 2),
(14, 14, 9, 2),
(15, 14, 16, 2),
(16, 14, 17, 3),
(17, 17, 16, 3),
(18, 17, 17, 3),
(19, 17, 10, 2),
(20, 17, 31, 2),
(21, 17, 8, 2),
(22, 17, 9, 2),
(24, 15, 30, 3),
(29, 15, 34, 3),
(31, 16, 14, 3),
(33, 15, 36, 2),
(34, 18, 14, 2),
(35, 15, 37, 3);

-- --------------------------------------------------------

--
-- Структура таблицы `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `note` varchar(128) NOT NULL,
  `active` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

--
-- Дамп данных таблицы `roles`
--

INSERT INTO `roles` (`id`, `name`, `note`, `active`) VALUES
(14, 'Default', 'Дефолтный профиль', 1),
(15, 'Администратор', 'Папа-2', 1),
(16, 'Читатель', 'Те, кто умеет читать', 1),
(17, 'Оператор', 'Доступ к логам', 1),
(18, 'Редактор', 'Тестовый', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `sections`
--

CREATE TABLE IF NOT EXISTS `sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(16) NOT NULL,
  `slevel_id` int(11) NOT NULL,
  `note` varchar(255) DEFAULT NULL,
  `active` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `control_name_UNIQUE` (`note`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Дамп данных таблицы `sections`
--

INSERT INTO `sections` (`id`, `name`, `slevel_id`, `note`, `active`) VALUES
(1, 'MAIL', 3, 'Адреса, домены,алиасы, логи', 1),
(3, 'SQUID', 1, 'Группы squid', 1),
(4, 'ADMIN', 3, 'Права пользователей', 1),
(5, 'RADIUS', 1, 'Пользователи WI-FI', 1),
(6, 'DNS', 1, 'ДНС сервер', 1),
(7, 'BASE', 0, 'Кой-чего', 1),
(10, 'BACKUP', 0, 'Сервер архивирования', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `slevels`
--

CREATE TABLE IF NOT EXISTS `slevels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `slevel` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `slevels`
--

INSERT INTO `slevels` (`id`, `name`, `slevel`) VALUES
(1, 'NONE', '0'),
(2, 'READ', '1'),
(3, 'WRITE', '2');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
