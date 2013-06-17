-- phpMyAdmin SQL Dump
-- version 4.0.1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Июн 17 2013 г., 17:19
-- Версия сервера: 5.1.40-community
-- Версия PHP: 5.3.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `ms`
--

-- --------------------------------------------------------

--
-- Структура таблицы `aliases`
--

CREATE TABLE IF NOT EXISTS `aliases` (
  `alias_id` int(11) NOT NULL AUTO_INCREMENT,
  `alias_name` varchar(100) NOT NULL,
  `delivery_to` varchar(150) NOT NULL,
  `alias_notes` varchar(250) DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`alias_id`),
  KEY `alias_name` (`alias_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=102 ;

--
-- Дамп данных таблицы `aliases`
--

INSERT INTO `aliases` (`alias_id`, `alias_name`, `delivery_to`, `alias_notes`, `active`) VALUES
(2, 'test@t1.gmpro.ru', 'test@t1.gmpro.ru', NULL, 0),
(3, 'gkv@t221.gmpro.ru', 'gkv@gmpro.ru', NULL, 1),
(4, 'oivaschenko@gmpro.ru', 'oivaschenko@zimbra.gmp.ru', NULL, 1),
(5, 'mvb@gmpros.ru', 'amt@gmpro.ru', NULL, 1),
(6, 'alex.teplitsky@gmpro.ru', 'amt@zimbra.gmp.ru', NULL, 1),
(7, 'amt@gmpro.ru', 'amt@zimbra.gmp.ru', NULL, 1),
(8, 'amt@gmpro.ru', 'oivaschenko@zimbra.gmp.ru', NULL, 1),
(9, 'amt@gmpro.ru', 'ann@gmpro.ru', NULL, 1),
(10, 'semenovykh@gmpro.ru', 'amt@zimbra.gmp.ru', NULL, 1),
(11, 'semenovykh@gmpro.ru', 'oivaschenko@zimbra.gmp.ru', NULL, 1),
(12, 'corpfin@gmpro.ru', 'ann@gmpro.ru', NULL, 1),
(13, 'ann@gmpro.ru', 'ann@gmpro.ru', NULL, 1),
(14, 'ann@gmpro.ru', 'oivaschenko@zimbra.gmp.ru', NULL, 1),
(15, 'gurgenj@gmpro.ru', 'gurgenj@gmpro.ru', NULL, 1),
(16, 'gurgen.j@gmpro.ru', 'gurgenj@gmpro.ru', NULL, 1),
(17, 'gkv@gmpro.ru', 'gkv@gmpro.ru', NULL, 1),
(18, 'gkv@gmpro.ru', 'gkv@zimbra.gmp.ru', NULL, 1),
(19, 'dleo@gmpro.ru', 'dleo@zimbra.gmp.ru', NULL, 1),
(20, 'maxx@gmpro.ru', 'maxx@zimbra.gmp.ru', NULL, 1),
(21, 'maria@gmpro.ru', 'maria@zimbra.gmp.ru', NULL, 1),
(22, 'yurovsky@gmpro.ru', 'yurovsky@zimbra.gmp.ru', NULL, 1),
(23, 'ruslan@gmpro.ru', 'ruslan@zimbra.gmp.ru', NULL, 1),
(24, 'alex@gmpro.ru', 'alex@zimbra.gmp.ru', NULL, 1),
(25, 'dar@gmpro.ru', 'dar@zimbra.gmp.ru', NULL, 1),
(26, 'samarin@gmpro.ru', 'samarin@zimbra.gmp.ru', NULL, 1),
(27, 'bma@gmpro.ru', 'bma@zimbra.gmp.ru', NULL, 1),
(28, 'zaslavskaya@gmpro.ru', 'zaslavskaya@zimbra.gmp.ru', NULL, 1),
(29, 'zvereva@gmpro.ru', 'zaslavskaya@zimbra.gmp.ru', NULL, 1),
(30, 'sav@gmpro.ru', 'sav@zimbra.gmp.ru', NULL, 1),
(31, 'dfm@gmpro.ru', 'dfm@zimbra.gmp.ru', NULL, 1),
(32, 'm.baikova@gmpro.ru', 'mbaikova@gmpro.ru', NULL, 1),
(33, 'mbaikova@gmpro.ru', 'mbaikova@zimbra.gmp.ru', NULL, 1),
(34, 'mbaikova@gmpro.ru', 'maria@zimbra.gmp.ru', NULL, 1),
(35, 'v.vorobev@gmpro.ru', 'vorobev@gmpro.ru', NULL, 1),
(36, 'ksenia.mikhailina@gmpro.ru', 'kseny@gmpro.ru', NULL, 1),
(37, 'fd@gmpro.ru', 'safinata@gmpro.ru', NULL, 1),
(38, 'pismo100@gmpro.ru', 'subd@gmpro.ru', NULL, 1),
(39, 'lilia@gmpro.ru', 'norkina@gmpro.ru', NULL, 1),
(40, 'vn@gmpro.ru', 'vinogradova@gmpro.ru', NULL, 1),
(41, 'v.kvetnoy@gmpro.ru', 'kvv@gmpro.ru', NULL, 1),
(42, 'kvv@gmpro.ru', 'kvv@gmpro.ru', NULL, 1),
(43, 'kvv@gmpro.ru', 'amt@zimbra.gmp.ru', NULL, 1),
(44, 'o.remizova@gmpro.ru', 'remizova@gmpro.ru', NULL, 1),
(45, 'remizova@gmpro.ru', 'remizova@gmpro.ru', NULL, 1),
(46, 'remizova@gmpro.ru', 'oivaschenko@zimbra.gmp.ru', NULL, 1),
(47, 'gureev@gmpro.ru', 'egur@gmpro.ru', NULL, 1),
(48, 'kazarinov@gmpro.ru', 'akazarin@gmpro.ru', NULL, 1),
(49, 'kostina.tatiana@gmpro.ru', 'kts@gmpro.ru', NULL, 1),
(50, 'kts@gmpro.ru', 'kts@gmpro.ru', NULL, 1),
(51, 'kts@gmpro.ru', 'oivaschenko@zimbra.gmp.ru', NULL, 1),
(52, 'vgf@gmpro.ru', 'jerry@gmpro.ru', NULL, 1),
(53, 'sheremet@gmpro.ru', 'sheremet@gmpro.ru', NULL, 1),
(54, 'sheremet@gmpro.ru', 'sh505@bk.ru', NULL, 1),
(55, 'alexschek@gmpro.ru', 'alexschek@zimbra.gmp.ru', NULL, 1),
(56, 'star@gmpro.ru', 'star@gmpro.ru', NULL, 1),
(57, 'star@gmpro.ru', 'estar53@ya.ru', NULL, 1),
(58, '2000750@gmpro.ru', 'kononova@gmpro.ru', NULL, 1),
(59, 'gmpro@gmpro.ru', 'office3fl@gmpro.ru', NULL, 1),
(60, 'e.syroegin@gmpro.ru', 'jon@gmpro.ru', NULL, 1),
(61, 'ann@gmpro.ru', 'amt@zimbra.gmp.ru', NULL, 0),
(62, 'gurgenj@gmpro.ru', 'amt@zimbra.gmp.ru', NULL, 1),
(63, 'dmf@gmpro.ru', 'dmf@zimbra.gmp.ru', NULL, 1),
(64, 'bi-info@gmpro.ru', 'azharkov@gmpro.ru', NULL, 1),
(65, 'bi-info@gmpro.ru', 'bi-robot@gmpro.ru', NULL, 1),
(66, 'bi-info@gmpro.ru', 'bi-robot2@gmpro.ru', NULL, 1),
(67, 'bi-info@gmpro.ru', 'bi-info@zimbra.gmp.ru', NULL, 1),
(68, 'eruvil@eruvil.ru', 'eruvil@gmpro.ru', NULL, 1),
(69, 'marina@eruvil.ru', 'marina@gmpro.ru', NULL, 1),
(91, 'p1@gmpro.ru', 'admin@t1.gmpro.ru', NULL, 1),
(92, 'test@eruvil.ru', 'admin@t1.gmpro.ru', NULL, 0),
(93, 'admin@t1.gmpro.ru', 'tarkvsg123@gmail.com', NULL, 1),
(94, 'murz@gmpro.ru', 'test@t1.gmpro.ru', NULL, 1),
(96, 'manyak@gmpro.ru', 'manyakov@gmpro.ru', NULL, 1),
(99, 'i.pinochet@bgmt.ru', 'pinochet@gmpro.ru', NULL, 1),
(100, 'vasya@bgmt.ru', 'pioneer@gmpro.ru', NULL, 1),
(101, 'admin@t1.gmpro.ru', 'test111@gmpro.ru', NULL, 1);

-- --------------------------------------------------------

--
-- Дублирующая структура для представления `alias_domains`
--
CREATE TABLE IF NOT EXISTS `alias_domains` (
`domain_name` varchar(100)
,`delivery_to` varchar(150)
);
-- --------------------------------------------------------

--
-- Структура таблицы `anyone_shares`
--

CREATE TABLE IF NOT EXISTS `anyone_shares` (
  `from_user` varchar(100) NOT NULL,
  `dummy` char(1) DEFAULT '1',
  PRIMARY KEY (`from_user`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Структура таблицы `domains`
--

CREATE TABLE IF NOT EXISTS `domains` (
  `domain_id` int(11) NOT NULL AUTO_INCREMENT,
  `domain_name` varchar(100) NOT NULL,
  `delivery_to` varchar(150) DEFAULT NULL,
  `domain_type` int(11) NOT NULL DEFAULT '0' COMMENT 'domain_type = 0 - local (virutal)\ndomain_type = 1 - alias\ndomain_type = 2 - transport_map\n',
  `domain_notes` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '0' COMMENT 'active = 1 - active\nactive = 0 - not active',
  PRIMARY KEY (`domain_id`),
  KEY `domain_name` (`domain_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Дамп данных таблицы `domains`
--

INSERT INTO `domains` (`domain_id`, `domain_name`, `delivery_to`, `domain_type`, `domain_notes`, `active`) VALUES
(1, 't1.gmpro.ru', 'virtual', 0, 'Test GMPRO.RU Mail Subdomain', 1),
(2, 'gmpro.ru', 'virtual', 0, 'Основной домен', 1),
(4, 'gmpro.ru', 'smtp:[192.168.0.3]', 2, 'Domain Split Config', 0),
(5, 'eruvil.ru', 'virtual', 0, 'Домен для ООО "Эрувиль" (на ЗВИ)', 1),
(6, 'novoroscement.ru', 'smtp:[10.1.100.64]', 2, 'Почтовый сервер ОАО "Новоросцемент" (через VPN)', 1),
(7, 'zimbra.gmp.ru', 'smtp:[192.168.0.252]', 2, NULL, 1),
(8, 'bgmt.ru', 'smtp:[192.168.0.252]', 2, NULL, 1),
(9, 'nv-tr.ru', 'smtp:[192.168.0.252]', 2, NULL, 1);

-- --------------------------------------------------------

--
-- Дублирующая структура для представления `transport_map`
--
CREATE TABLE IF NOT EXISTS `transport_map` (
`domain_name` varchar(100)
,`delivery_to` varchar(150)
);
-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `mailbox` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `md5password` varchar(100) NOT NULL,
  `path` varchar(250) DEFAULT NULL COMMENT 'Путь к maildir. Если null - default path',
  `imap_enable` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Разрешать подключение по imap',
  `allow_nets` varchar(200) NOT NULL DEFAULT '192.168.0.0/24' COMMENT 'Подсети, откуда возможен доступ к imap/pop',
  `active` tinyint(1) NOT NULL DEFAULT '1' COMMENT '=1/0 - вкл/выкл',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `mailbox` (`mailbox`),
  KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=189 ;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`user_id`, `username`, `mailbox`, `password`, `md5password`, `path`, `imap_enable`, `allow_nets`, `active`) VALUES
(1, 'Testov Test Testovich', 'test@t1.gmpro.ru', '12', 'c20ad4d76fe97759aa27a0c99bff6710', '123451234', 1, '192.168.0.0/24', 1),
(2, 'Admin Account', 'admin@t1.gmpro.ru', 'kucorasa', '7ded130528bd116707438140d608d931', NULL, 3, '192.168.0.0/24', 0),
(3, 'Костя112', 'gkv@gmpro.ru', '12364', '905669063311d8a17bd6958cd353eedd', '/var/log', 3, '192.168.0.0/24, 127.0.0.1/32, 192.168.109.10/32', 0),
(4, 'Andrey V. Kazarinov', 'akazarin@gmpro.ru', 'Gu2sPqm', '95f0455facefa784ea7a14ff0e9c5bf8', NULL, 1, '192.168.0.0/24,192.168.0.0', 1),
(5, 'Alexey D. Zharkov', 'azharkov@gmpro.ru', 'Dim_Alex87', '7d8e9c0d1c264986912c154be951cfe2', '', 1, '192.168.0.0/24', 1),
(6, 'Anastasia A. Birukova', 'baa@gmpro.ru', 'Giv7cho', '5d3c86556ef1655a0a2c5494e35c8fb6', 'tmp', 1, '192.168.0.0/24', 1),
(7, 'Vladimir V. Belousov', 'belousov@gmpro.ru', 'suim6Be', '9813d070a9c49af0b321f5d6db8aa906', '', 1, '192.168.0.0/24', 1),
(8, 'BI_ROBOT', 'bi-robot2@gmpro.ru', 'Mbx456alsdjf', 'ec1a10313b52b99f1d36fccec2418ebc', '', 1, '192.168.0.0/24', 1),
(9, 'BI_ROBOT', 'bi-robot@gmpro.ru', 'Mbx456alsdjf', 'ec1a10313b52b99f1d36fccec2418ebc', '', 1, '192.168.0.0/24', 1),
(10, 'Sergey A. Boronin', 'boronin@gmpro.ru', 'Zio7lae', '2fe353d5f73ae3a2a1d64d71909bbe0a', NULL, 0, '192.168.0.0/24', 1),
(11, 'Konstantin V. Milukov', 'cairo@gmpro.ru', 'Choo7th', 'ac5012438c659f894541f1195e8d5886', NULL, 0, '192.168.0.0/24', 1),
(12, 'Nataliya V. Chvaguerva', 'ch@gmpro.ru', 's8lLMTi', 'b87c6050f0c5a6b129ab8f2493246e0a', NULL, 0, '192.168.0.0/24', 1),
(13, 'Roman D. Chumarin', 'chumarin@gmpro.ru', 'xoo4Toa', 'c8abd19fa2ee49cdc9e29973295978b0', '', 1, '192.168.0.0/24', 1),
(14, 'Andronik A. Dzhangiryan', 'dar@gmpro.ru', 'piCh1ph', '9bd4e32d68f2674d543ec5d197f16c25', '', 1, '192.168.0.0/24', 1),
(15, 'Denis M. Fedotov', 'dmf@gmpro.ru', 'chee4Ph', 'ad1635a1adf6f3793f0f9ac6b223dfb4', NULL, 0, '192.168.0.0/24', 0),
(16, 'Dmitry V. Smetanich', 'dv@gmpro.ru', 'Pfhe2nv', '97baa83fca74eb3f2fcd10c6197663e3', NULL, 0, '192.168.0.0/24', 1),
(17, 'Eduard A. Gureev (GMP-Leasing)', 'egur@gmpro.ru', 'EGUr1972', 'adf95b0774855f16b61364e0684d1ff1', NULL, 0, '192.168.0.0/24', 1),
(18, 'Valeri M. Kulikovskiy', 'energo@gmpro.ru', 'ovoc6xM', '77715f0dd02f4c2b256503df54426424', NULL, 0, '192.168.0.0/24', 1),
(19, 'Evgenia F. Petukhova', 'epetukho@gmpro.ru', 'gaeFie8', 'e06f0d8d161df1a72bfc485eb3cda52b', NULL, 0, '192.168.0.0/24', 1),
(20, 'Mikhail B. Eremin', 'eremin@gmpro.ru', '1amONlA', '471ee655bf710049c1628ef32b98018e', NULL, 0, '192.168.0.0/24', 1),
(21, 'Anna Y. Ghiltsova', 'ghiltsova@gmpro.ru', 'le0Bieb', '48e9c19071cc35267de9e208187a1674', '', 0, '192.168.0.0/24', 1),
(22, 'Anastasia E. Grachova', 'gracha@gmpro.ru', 'w3nfYC9', 'c039a07f4aa7fbc10aaa9322fea832b2', NULL, 0, '192.168.0.0/24', 1),
(23, 'Gurgen R. Jangiryan', 'gurgenj@gmpro.ru', 'ra5vahY', 'fc9f01a689a149a9f62b8d0f90e7d5c0', NULL, 0, '192.168.0.0/24', 1),
(24, 'Tonya V. Geraskina', 'in10@gmpro.ru', 'PWBufCV', 'db19cf9048fb4b92b81843fe8fff1411', '', 0, '192.168.0.0/24', 1),
(25, 'Irina V. Uspenkaya', 'irina@gmpro.ru', 'najwyKA', '6c569b369f037ead7b503e24631bd495', NULL, 0, '192.168.0.0/24', 1),
(26, 'Jerry A. Soznik', 'jerry@gmpro.ru', '8HB2rx4', 'c2b3f851414b4340a6bba4929841b600', NULL, 0, '192.168.0.0/24', 1),
(27, 'Evgeniy V. Syroegin', 'jon@gmpro.ru', 'KDEqEuy', 'b6f726454851cc570cc68a2302111f9e', NULL, 1, '192.168.0.0/24, 10.0.0.0/8, 127.0.0.1/32', 1),
(28, 'Julia I. Veremiy', 'julia@gmpro.ru', 'jaec5Zi', '3157c8ff8791e7fa544419c8a92b7171', NULL, 0, '192.168.0.0/24', 1),
(29, 'Arman V. Kasymov', 'kasymov@gmpro.ru', 'waeg0Pe', '271a6485fcf2e88176faa54d95ad42a6', NULL, 0, '192.168.0.0/24', 1),
(30, 'Olga V. Korneeva (LEASING)', 'korneeva@gmpro.ru', 'olga275K', '994abcb4c76d09f8f97d3b2ce3e7e064', NULL, 0, '192.168.0.0/24', 1),
(31, 'Julia V. Korzunova', 'korzunova@gmpro.ru', 'Saz7nee', 'b42590c11de629d64538399647410283', NULL, 0, '192.168.0.0/24', 1),
(32, 'Ekaterina V. Sidorova', 'ksidorova@gmpro.ru', 'heL7mah', '378815e6e97bd8f6c50dbe4b031fc6c1', NULL, 0, '192.168.0.0/24', 1),
(33, 'Sergey V. Kyunttsel', 'ksv@gmpro.ru', 'Riezee0', '745444018368cbe476f1dec5d125eb8f', NULL, 0, '192.168.0.0/24', 1),
(34, 'Tatiana S. Kostina', 'kts@gmpro.ru', 'Gaeg3he', '216244844eb2473551096e3952e44feb', NULL, 0, '192.168.0.0/24', 1),
(35, 'Svetlana V. Kuzina', 'kuzina@gmpro.ru', 'Pr6Y4sP', '80d9c7b48b26ce29974a912be15c6676', NULL, 0, '192.168.0.0/24', 1),
(36, 'Vladimir V. Kvetnoy', 'kvv@gmpro.ru', 'Jeejej6', 'e2f0fb26b4e273d7bd8581474dcaad9e', NULL, 0, '192.168.0.0/24', 1),
(37, 'Ksenia N. Zvyagintseva', 'kzvyagintseva@gmpro.ru', 'xeij5Ka', '2f805818a33705c272a35cb4602105c0', NULL, 0, '192.168.0.0/24', 1),
(38, 'Lada U. Mironova', 'lada@gmpro.ru', '0AcFXZ0', 'dae6002ab08e25c73d73dfa2517dab86', NULL, 0, '192.168.0.0/24', 1),
(39, 'Aleksey N. Lapin (oskmet-ag)', 'lapin@gmpro.ru', 'icxBYrXZ', '7c578421528be920fa8749df65f950cb', NULL, 0, '192.168.0.0/24', 1),
(40, 'New Fin User', 'luhtern@gmpro.ru', '4465403', '02f19badd6a1c473bd7bcd2321482af8', NULL, 0, '192.168.0.0/24', 1),
(41, 'Account for mail backup from relay', 'mailcopy@gmpro.ru', 'iaibRvQ1945lfj', 'dfd49c9254bf101a9f1289fc9b0d3fa2', NULL, 0, '192.168.0.0/24', 1),
(42, 'Evgeny V. Manyakov', 'manyakov@gmpro.ru', 'fohTu7r', 'd9d7e2f8c8ea64107c0b748f7e5cf9be', NULL, 0, '192.168.0.0/24', 1),
(43, 'Maria P. Yurovskaya', 'maria@gmpro.ru', 'Dc9VXc3', 'a9e3e380131394391d70d874cc59f31b', NULL, 0, '192.168.0.0/24', 1),
(44, 'Marina V. Baikova', 'mbaikova@gmpro.ru', 'luoVa7s', 'f3b707ba627288c2afc0bcc351986496', NULL, 0, '192.168.0.0/24', 1),
(45, 'Galina A. Mischenko (GMP-Leasing)', 'mischgal@gmpro.ru', 'S9xB4nH', '68dd2557cfa2e22a806ec43a728d309d', NULL, 0, '192.168.0.0/24', 1),
(46, 'Nikolai B. Vorobiov', 'nvorob@gmpro.ru', 'rNVtTN3', 'd1a290c0e3b79e3b7fd1e4dbe16cd5f9', NULL, 0, '192.168.0.0/24', 1),
(47, 'Alexander V. Oslopov', 'oav@gmpro.ru', 'so1Xomi', '78b0488b177b1ef50d6f26d94ef78f55', NULL, 0, '192.168.0.0/24', 1),
(48, 'Emin R. Oganyan', 'ogem@gmpro.ru', 'Yai5rei', 'c0474e06c976d1f6a8270a11b27b3504', NULL, 0, '192.168.0.0/24', 1),
(49, 'Olga G. Frolova', 'ogfrolova@gmpro.ru', 'cla9SyQ', '50b60d58567de8a73b098dec3543836c', NULL, 0, '192.168.0.0/24', 1),
(50, 'Oksana G. Frolova', 'oksana@gmpro.ru', 'ghlKpnS', 'f84c63fadf263b83169276cbcf835cd4', NULL, 0, '192.168.0.0/24', 1),
(51, 'Pavel Trofilov', 'pavel@gmpro.ru', 'EtGOSzP', '29561fb2ca22d649d39950ab6f4220ad', NULL, 0, '192.168.0.0/24', 1),
(52, 'Ekaterina A. Potapova', 'pek@gmpro.ru', 'LL6kNu3', 'e29c62035a4dcfdfe2556230c15984bd', NULL, 0, '192.168.0.0/24', 1),
(53, 'Evgenia A. Podkolzina', 'podkolzina_ea@gmpro.ru', 'Sai2dah', '1b0beb6bf81bca127bf8ceb9f9da3ba3', NULL, 0, '192.168.0.0/24', 1),
(54, 'Oksana Yu. Rassolova', 'rassolova@gmpro.ru', 'GJZipZj', '90d69fd44e1942e33364dac5830c3d16', NULL, 0, '192.168.0.0/24', 1),
(55, 'Olga U. Remizova', 'remizova@gmpro.ru', 'WfnvJLm', 'd1c8c3d58ee0df8b5ff2074aafc5928f', NULL, 0, '192.168.0.0/24', 1),
(56, 'Reseption Common Account', 'reseption@gmpro.ru', 'OD9xCqq', '7ac0dd7a57c13218c4b5597b2aa6d55c', NULL, 0, '192.168.0.0/24', 1),
(57, 'Maksim S. Reznik', 'rms@gmpro.ru', 'pheGos3', '7c6549d31f699c2c1330d1450d0acb37', NULL, 0, '192.168.0.0/24', 1),
(58, 'Robert G. Dzhangiryan', 'robert@gmpro.ru', 'je5AYZO', '449aaced989d2ee5a4115eff0392d98c', NULL, 0, '192.168.0.0/24', 1),
(59, 'Ruslan A. Chernyshev', 'ruslan@gmpro.ru', 'Wuu5rae', 'dd29903f2a02189503b48bc049286be3', NULL, 0, '192.168.0.0/24', 1),
(60, 'Tatiana A. Safina', 'safinata@gmpro.ru', 'vaVi6sh', '3622d243a54f40d6771ab96bdd70b283', NULL, 0, '192.168.0.0/24', 1),
(61, 'Victoria A. Sidorova', 'sidorova@gmpro.ru', 'riV5nei', '4da1ae8aa0972077f7a083cb0d21f1c3', NULL, 0, '192.168.0.0/24', 1),
(62, 'Maria I. Burdanova', 'subd@gmpro.ru', 'Saequa3', 'b1c56f585602878356945e7bdbcaaae2', NULL, 0, '192.168.0.0/24', 1),
(63, 'Ekaterina V. Udalova', 'udalova@gmpro.ru', 'm4halTu', 'f387d20f9f06dd5a01efd7827fb12fce', NULL, 0, '192.168.0.0/24', 1),
(64, 'Bufet on Voznesenka', 'vbufet@gmpro.ru', 'Zoo8see', 'f2d8e9b4164ce3694cda76612b52dc7f', NULL, 0, '192.168.0.0/24', 1),
(65, 'Vitaly J. Vorobev', 'vorobev@gmpro.ru', 'Mei7buj', '5e7cedbf39c9336841af2036ad50a276', NULL, 0, '192.168.0.0/24', 1),
(66, 'Vladimir V. Alekseev', 'alex@gmpro.ru', 'ItySUaZ', 'a06ffc67756eefe5f923fcb45e76c1a2', NULL, 0, '192.168.0.0/24', 1),
(67, 'Alexey M. Teplitsky', 'amt@gmpro.ru', 'pgnSkv8', '8a3aa827a8922915c1da029f1a27ac83', NULL, 0, '192.168.0.0/24', 1),
(68, 'Anna I. Mogucheva', 'ann@gmpro.ru', 'XmrnT9a', 'a5fce062abd8be5106f8737fd2ec0c86', NULL, 0, '192.168.0.0/24', 1),
(69, 'Olga N. Arapova', 'arapova@gmpro.ru', 'qkjE9m4', 'b0dbd2593217b14101a72bececde6035', NULL, 0, '192.168.0.0/24', 1),
(70, 'Pavel A. Korolev', 'bufet@gmpro.ru', 'u8T6e9x', 'e416e55a92cb8bacd151e9429585ae68', NULL, 0, '192.168.0.0/24', 1),
(71, 'Dmitry L. Zharkov', 'dleo@gmpro.ru', 'ywPrzqW', '25d09240f9cd65078a2701cf0066034d', NULL, 1, '192.168.0.0/24, 127.0.0.1/32', 1),
(72, 'Vladimir R. Gafurov', 'gafurov@gmpro.ru', 'I1u98AF', 'ea4a3d0f33c4ddaa7c6c83b912423e2b', NULL, 0, '192.168.0.0/24', 1),
(73, 'Lubov N. Veshchikova', 'gm@gmpro.ru', '3hDn1Ep', 'b0badaf9d05f7f9a5ccce84878f17a56', NULL, 0, '192.168.0.0/24', 1),
(74, 'Olga I. Egorova', 'kadry@gmpro.ru', 'RMqZkHc', 'a6b48eee18082c0213c806ebac8fad61', NULL, 0, '192.168.0.0/24', 1),
(75, 'Juliya N. Kononova', 'kononova@gmpro.ru', 'ceing2P', 'c2f6701a61921ca5af6a86657dc39432', NULL, 0, '192.168.0.0/24', 1),
(76, 'Ksenia A. Mikhailina', 'kseny@gmpro.ru', 'kDsFjm5', '2d89278a3cca90bf629c22d6726c25bd', NULL, 0, '192.168.0.0/24', 1),
(77, 'Aleksandr V. Matuhin', 'maxx@gmpro.ru', '9564371', '9091fd80834695b8c696adf3b6f20d55', NULL, 0, '192.168.0.0/24', 1),
(78, 'Mikhail N. Teplitsky', 'mnt@gmpro.ru', 'qb2Irwb', '6b93ca7f494a8f4e3416a2921e15e97f', NULL, 0, '192.168.0.0/24', 1),
(79, 'Nadejda D. Morozova', 'morozova@gmpro.ru', 'KwIRig9', '0fdd82456ec0c082fef1ad6f69d10115', NULL, 0, '192.168.0.0/24', 1),
(80, 'Nikolay N. Khaustov', 'nik@gmpro.ru', 'U2d6qzW', '6a3de8645e3f2988b12e9e5952a813ac', NULL, 0, '192.168.0.0/24', 1),
(81, 'Lilia N. Norkina', 'norkina@gmpro.ru', 'MRV9HEH', 'abf0c7d503ed2b44733c4aff51a13bb5', NULL, 0, '192.168.0.0/24', 1),
(82, 'Public Account', 'office3fl@gmpro.ru', 'OvIr7jQ', '21d4d01d2b7a1c1369b6729e549b679c', NULL, 0, '192.168.0.0/24', 1),
(83, 'Oleg I. Ivaschenko', 'oivaschenko@gmpro.ru', 'oCqVMrj', '6b561d1b379cb3c8b56de7227ab42256', NULL, 0, '192.168.0.0/24', 1),
(84, 'Olga V. Pustueva', 'olga@gmpro.ru', 'tkMSeCC', '19a29d7f4a58fd00372a90d1e0c39264', NULL, 0, '192.168.0.0/24', 1),
(85, 'Yury Yu. Safonov', 'propusk@gmpro.ru', 'I9ur7yw', '8b1e6b82362081bc876fcf6250bebb2a', NULL, 0, '192.168.0.0/24', 1),
(86, 'Vladimir V. Samarin', 'samarin@gmpro.ru', 'VFdNfX7', '300e3b684774ea9cf721efec86b371a7', NULL, 0, '192.168.0.0/24', 1),
(87, 'Vladimir V. Sheremet', 'sheremet@gmpro.ru', 'vs', 'f4842dcb685d490e2a43212b8072a6fe', NULL, 0, '192.168.0.0/24', 1),
(88, 'Sergey P. Sidorov', 'ssp@gmpro.ru', 'Yu58dfr', '37c7d2784a59f09b66cbe74dccdc3597', NULL, 0, '192.168.0.0/24', 1),
(89, 'Efim A. Starikov', 'star@gmpro.ru', 'lVXdjap', '4a516cbf1042e5cd39f56082bac6ef7a', NULL, 0, '192.168.0.0/24', 1),
(90, 'Tatiana M. Trusova', 'trusova@gmpro.ru', 'vdvCWGx', '9971cf1d13b0fca5c07479f7315de40f', NULL, 0, '192.168.0.0/24', 1),
(91, 'Valery I. Pislar', 'vip@gmpro.ru', '7WQkzMa', 'bd5b41318f7256faa963ba1cf97d4ff4', NULL, 0, '192.168.0.0/24', 1),
(92, 'Nataliya V. Vlasova', 'vlasova@gmpro.ru', 'XTINNry', 'ce59c36be1e9ec726e09b4a07dfd8dc6', NULL, 0, '192.168.0.0/24', 1),
(93, 'Valery A. Taralov', 'vtar@gmpro.ru', 'bdlFqqm', '5c9ecefd87833f8481191461bf781bfa', NULL, 0, '192.168.0.0/24', 1),
(94, 'Ludmila Yu. Zolotih', 'zolot@gmpro.ru', 'Scy5xrW', '73a77e148f708cedca88f615a0beeb11', NULL, 0, '192.168.0.0/24', 1),
(95, 'Marina (ERUVIL.RU)', 'marina@gmpro.ru', 'abc154HPx', 'd2cf55626b0f8819644640bcd7056bac', NULL, 0, '192.168.0.0/24, 192.168.12.0/24', 1),
(96, '(ERUVIL.RU)', 'eruvil@gmpro.ru', 'abc154HPx', 'd2cf55626b0f8819644640bcd7056bac', NULL, 0, '192.168.0.0/24, 192.168.12.0/24', 1),
(97, 'Гордюнин Владимир Сергеевич', 'vsg@gmpro.ru', '', 'd3f427fbda2ec2b435b119c022bec1f2', NULL, 1, '192.168.0.0/24', 1),
(184, 'Пиночет Иван', 'pinochet@gmpro.ru', 'Pinochet', '1a353cd808ca3c2d153e92e49599a3ad', NULL, 3, '192.168.0.0/24', 1),
(188, 'Пионер Вася', 'pioneer@gmpro.ru', 'qaz', '4eae18cf9e54a0f62b44176d074cbe2f', NULL, 3, '192.168.0.0/24', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `user_shares`
--

CREATE TABLE IF NOT EXISTS `user_shares` (
  `from_user` varchar(100) NOT NULL,
  `to_user` varchar(100) NOT NULL,
  `dummy` char(1) DEFAULT '1',
  PRIMARY KEY (`from_user`,`to_user`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Структура таблицы `virtual`
--

CREATE TABLE IF NOT EXISTS `virtual` (
  `virtual_id` int(11) NOT NULL AUTO_INCREMENT,
  `virtual` varchar(100) NOT NULL,
  `delivery_to` varchar(250) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`virtual_id`),
  UNIQUE KEY `virtual` (`virtual`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Дублирующая структура для представления `virtual_domains`
--
CREATE TABLE IF NOT EXISTS `virtual_domains` (
`domain_name` varchar(100)
,`delivery_to` varchar(150)
);
-- --------------------------------------------------------

--
-- Структура для представления `alias_domains`
--
DROP TABLE IF EXISTS `alias_domains`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `alias_domains` AS select `domains`.`domain_name` AS `domain_name`,`domains`.`delivery_to` AS `delivery_to` from `domains` where ((`domains`.`domain_type` = 1) and (`domains`.`active` = 1));

-- --------------------------------------------------------

--
-- Структура для представления `transport_map`
--
DROP TABLE IF EXISTS `transport_map`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `transport_map` AS select `domains`.`domain_name` AS `domain_name`,`domains`.`delivery_to` AS `delivery_to` from `domains` where ((`domains`.`domain_type` = 2) and (`domains`.`active` = 1));

-- --------------------------------------------------------

--
-- Структура для представления `virtual_domains`
--
DROP TABLE IF EXISTS `virtual_domains`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `virtual_domains` AS select `domains`.`domain_name` AS `domain_name`,`domains`.`delivery_to` AS `delivery_to` from `domains` where (((`domains`.`domain_type` = 0) or (`domains`.`domain_type` = 1)) and (`domains`.`active` = 1));

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;