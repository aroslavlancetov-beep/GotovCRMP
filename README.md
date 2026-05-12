[______________________ (2).sql](https://github.com/user-attachments/files/27623122/______________________.2.sql)
-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Май 12 2026 г., 09:11
-- Версия сервера: 5.7.39
-- Версия PHP: 8.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `Кунтскамера`
--

-- --------------------------------------------------------

--
-- Структура таблицы `dangers`
--

CREATE TABLE `dangers` (
  `id` int(11) NOT NULL,
  `class_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `dangers`
--

INSERT INTO `dangers` (`id`, `class_name`, `description`) VALUES
(1, '1. Новичок игрок', 'ничего не делает'),
(2, '2. Нормальный', 'обычный человек'),
(3, '3. Почти злодей', 'может сильно нахулиганить'),
(4, '4. Злодей', 'почти как лютый оффник'),
(5, '5. Лютый оффник', 'тотальное уничтожение вселенной'),
(6, '6. Зависимый', 'совершит плохие поступки ради своей слабости'),
(7, '7. Отдельная каста', 'занимается только пакостями на сервере'),
(8, '8. Хороший дядя полицейский', 'обычный коп, он очень добрый');

-- --------------------------------------------------------

--
-- Структура таблицы `names`
--

CREATE TABLE `names` (
  `id` int(11) NOT NULL,
  `object_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alias` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discovery_date` date DEFAULT NULL,
  `descriptions` int(11) DEFAULT NULL COMMENT 'Ссылка на id в таблице dangers (уровень опасности)',
  `skill_id` int(11) DEFAULT NULL COMMENT 'Ссылка на id в таблице skills (навык персонажа)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `names`
--

INSERT INTO `names` (`id`, `object_name`, `alias`, `discovery_date`, `descriptions`, `skill_id`) VALUES
(1, 'Volodya_Hokage', 'adamKilla', '2012-05-20', 1, 1),
(2, 'Мент_Бенджамин', 'Боевыые_малыши', '2025-05-20', 8, 7),
(3, 'Oper_Maga', 'ⓈⒺⓋⒶⓈⓉⓄⓅⓄⓁ', '2021-12-21', 4, 5);

-- --------------------------------------------------------

--
-- Структура таблицы `positions`
--

CREATE TABLE `positions` (
  `id` int(11) NOT NULL,
  `position` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `positions`
--

INSERT INTO `positions` (`id`, `position`, `description`) VALUES
(1, 'Рядовой', 'Самый обычный сотрудник');

-- --------------------------------------------------------

--
-- Структура таблицы `skills`
--

CREATE TABLE `skills` (
  `id` int(11) NOT NULL,
  `skill_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `effect_description` text COLLATE utf8mb4_unicode_ci,
  `activation_conditions` text COLLATE utf8mb4_unicode_ci,
  `countermeasures` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `skills`
--

INSERT INTO `skills` (`id`, `skill_name`, `effect_description`, `activation_conditions`, `countermeasures`) VALUES
(1, 'лень', 'просто ленивый человек', 'лень действует на протяжении всей жини', 'попробуйте его развеселить, лень отступит мгновенно'),
(2, 'образование', 'у человека развит интеллект', 'во время повседневного разговора или спора двух сторон', 'физическая сила'),
(3, 'бой без правил', 'высоко развита способность боя', 'очень коротковременно, но может длиться в зависимости от соперника', 'против него можно задействовать метод любой грубой силы'),
(4, 'доступ к вооружению', 'при малейшем намеке на угрозу использует свои связи', 'длится около месяца', 'вам помогут только более влиятельные люди'),
(5, 'угрозы и хулиганство', 'у него нет разума но есть сила', 'до первой заявы и пожизненного срока', 'попробуйте написать заявление в полицию'),
(6, 'зависимость', 'такое нельзя обсуждать', 'на протяжении всей жизни', 'рехаб может помочь (это не толчная инфомация)'),
(7, 'борьба с преступностью', 'уничтожает плохих парней', 'с момента его рождения', 'против него могут бать только лютые оффники');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `dangers`
--
ALTER TABLE `dangers`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `names`
--
ALTER TABLE `names`
  ADD PRIMARY KEY (`id`),
  ADD KEY `descriptions` (`descriptions`),
  ADD KEY `skill_id` (`skill_id`);

--
-- Индексы таблицы `positions`
--
ALTER TABLE `positions`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`id`);

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `names`
--
ALTER TABLE `names`
  ADD CONSTRAINT `names_ibfk_1` FOREIGN KEY (`descriptions`) REFERENCES `dangers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `names_ibfk_2` FOREIGN KEY (`skill_id`) REFERENCES `skills` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
