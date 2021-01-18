-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Янв 18 2021 г., 18:09
-- Версия сервера: 5.7.30-33
-- Версия PHP: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `ci17950_vk`
--

-- --------------------------------------------------------

--
-- Структура таблицы `Games`
--

CREATE TABLE IF NOT EXISTS `Games` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playerOne` varchar(320) NOT NULL,
  `playerTwo` varchar(320) NOT NULL,
  `area` json NOT NULL,
  `log` json NOT NULL,
  `turn` int(1) NOT NULL DEFAULT '1',
  `update_date` int(11) UNSIGNED NOT NULL,
  `create_date` int(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
