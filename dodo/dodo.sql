-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Дек 13 2025 г., 12:42
-- Версия сервера: 10.4.32-MariaDB
-- Версия PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `dodo`
--

-- --------------------------------------------------------

--
-- Структура таблицы `action_type`
--

CREATE TABLE `action_type` (
  `id_action_type` int(1) NOT NULL,
  `name_action` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `action_type`
--

INSERT INTO `action_type` (`id_action_type`, `name_action`) VALUES
(1, 'разморозка'),
(2, 'списание');

-- --------------------------------------------------------

--
-- Структура таблицы `product`
--

CREATE TABLE `product` (
  `id_product` int(1) NOT NULL,
  `product_name` varchar(50) NOT NULL,
  `expiry_date` date NOT NULL,
  `weight` decimal(6,2) NOT NULL COMMENT 'в кг',
  `action_type_id` int(1) NOT NULL,
  `user_id` int(1) NOT NULL,
  `created_date` date NOT NULL,
  `status_id` int(1) NOT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `product`
--

INSERT INTO `product` (`id_product`, `product_name`, `expiry_date`, `weight`, `action_type_id`, `user_id`, `created_date`, `status_id`, `notes`) VALUES
(6, 'салат', '2025-12-14', 0.60, 2, 9, '2025-12-13', 2, 'остатки от салатов');

-- --------------------------------------------------------

--
-- Структура таблицы `status`
--

CREATE TABLE `status` (
  `id_status` int(1) NOT NULL,
  `name_status` varchar(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `status`
--

INSERT INTO `status` (`id_status`, `name_status`) VALUES
(1, 'Обработан'),
(2, 'В работе'),
(3, 'Отменено');

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE `user` (
  `id_user` int(1) NOT NULL,
  `user_type_id` int(1) NOT NULL,
  `surname` varchar(7) NOT NULL,
  `name` varchar(5) NOT NULL,
  `otchestvo` varchar(9) NOT NULL,
  `phone` varchar(11) NOT NULL,
  `email` varchar(28) NOT NULL,
  `username` varchar(8) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id_user`, `user_type_id`, `surname`, `name`, `otchestvo`, `phone`, `email`, `username`, `password`) VALUES
(1, 2, 'Елисеев', 'Артём', 'Игоревич', '79252723503', 'eliseevartem101@gmail.com', 'adminka', '5f4dcc3b5aa765d61d8327deb882cf99'),
(9, 1, 'test', 'test', 'test', '8 111 111 1', 'test@test', '12345', '827ccb0eea8a706c4c34a16891f84e7b');

-- --------------------------------------------------------

--
-- Структура таблицы `user_type`
--

CREATE TABLE `user_type` (
  `id_user_type` int(1) NOT NULL,
  `name_user` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `user_type`
--

INSERT INTO `user_type` (`id_user_type`, `name_user`) VALUES
(1, 'пользователь'),
(2, 'админ');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `action_type`
--
ALTER TABLE `action_type`
  ADD PRIMARY KEY (`id_action_type`);

--
-- Индексы таблицы `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id_product`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `action_type_id` (`action_type_id`),
  ADD KEY `status_id` (`status_id`);

--
-- Индексы таблицы `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`id_status`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `user_type_id` (`user_type_id`);

--
-- Индексы таблицы `user_type`
--
ALTER TABLE `user_type`
  ADD PRIMARY KEY (`id_user_type`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `product`
--
ALTER TABLE `product`
  MODIFY `id_product` int(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id_user`),
  ADD CONSTRAINT `product_ibfk_2` FOREIGN KEY (`action_type_id`) REFERENCES `action_type` (`id_action_type`),
  ADD CONSTRAINT `product_ibfk_3` FOREIGN KEY (`status_id`) REFERENCES `status` (`id_status`);

--
-- Ограничения внешнего ключа таблицы `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`user_type_id`) REFERENCES `user_type` (`id_user_type`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
