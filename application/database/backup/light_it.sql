-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Авг 17 2017 г., 00:31
-- Версия сервера: 5.6.34-log
-- Версия PHP: 5.6.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `light_it`
--
CREATE DATABASE IF NOT EXISTS `light_it` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `light_it`;

-- --------------------------------------------------------

--
-- Структура таблицы `message`
--

DROP TABLE IF EXISTS `message`;
CREATE TABLE `message` (
  `id_message` int(11) NOT NULL,
  `id_user` int(10) UNSIGNED NOT NULL,
  `message` varchar(512) DEFAULT NULL,
  `time` int(11) DEFAULT NULL,
  `parent_id_message` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Очистить таблицу перед добавлением данных `message`
--

TRUNCATE TABLE `message`;
--
-- Дамп данных таблицы `message`
--

INSERT INTO `message` (`id_message`, `id_user`, `message`, `time`, `parent_id_message`) VALUES
(5, 0, 'Это сообщение', 1502710838, NULL),
(6, 11, 'Первый уровень', 1502710914, 5),
(9, 11, 'Ещё один комментарий первого уровня', 1502710961, 5),
(10, 11, 'Комментарий второго уровня', 1502711122, 6),
(11, 0, 'Ещё одно сообщение', 1502823793, NULL),
(12, 0, 'Комментарий третьего уровня', 1502823869, 10),
(13, 0, 'Ещё один комментарий второго уровня', 1502829742, 6),
(14, 11, 'Ещё один комментарий первого уровня', 1502829857, 5);

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id_user` int(10) UNSIGNED NOT NULL,
  `id` varchar(32) NOT NULL,
  `email` varchar(64) NOT NULL,
  `given_name` varchar(32) NOT NULL,
  `family_name` varchar(32) NOT NULL,
  `link` varchar(256) NOT NULL,
  `picture` varchar(512) NOT NULL,
  `locale` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Очистить таблицу перед добавлением данных `user`
--

TRUNCATE TABLE `user`;
--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id_user`, `id`, `email`, `given_name`, `family_name`, `link`, `picture`, `locale`) VALUES
(0, '106575566978556706819', 'alovel2ru@gmail.com', 'Роман', 'Лелюк', 'https://plus.google.com/106575566978556706819', 'https://lh5.googleusercontent.com/-eb5fiXASj1k/AAAAAAAAAAI/AAAAAAAAABY/GCcUWEalcXE/photo.jpg', 'ru'),
(11, '118098036232960336815', 'ormuswater@gmail.com', 'Ormus', 'Water', 'https://plus.google.com/118098036232960336815', 'https://lh3.googleusercontent.com/-XdUIqdMkCWA/AAAAAAAAAAI/AAAAAAAAAAA/4252rscbv5M/photo.jpg', 'ru');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id_message`),
  ADD KEY `fk_message_message_idx` (`parent_id_message`),
  ADD KEY `fk_id_user` (`id_user`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `message`
--
ALTER TABLE `message`
  MODIFY `id_message` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `fk_id_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_message_message` FOREIGN KEY (`parent_id_message`) REFERENCES `message` (`id_message`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
