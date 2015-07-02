-- phpMyAdmin SQL Dump
-- version 3.5.8.2
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Июл 02 2015 г., 11:09
-- Версия сервера: 5.1.73
-- Версия PHP: 5.3.28

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: ``
--

-- --------------------------------------------------------

--
-- Структура таблицы `items`
--

CREATE TABLE IF NOT EXISTS `items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `date` bigint(20) unsigned NOT NULL,
  `full_name` text CHARACTER SET utf8 NOT NULL,
  `name` text CHARACTER SET utf8 NOT NULL,
  `cost` bigint(20) unsigned NOT NULL,
  `views` bigint(20) unsigned NOT NULL,
  `distributed` bigint(20) unsigned NOT NULL,
  `short_description` text CHARACTER SET utf8 NOT NULL,
  `description` text CHARACTER SET utf8 NOT NULL,
  `category_id` bigint(20) unsigned NOT NULL,
  `image` text CHARACTER SET utf8 NOT NULL,
  `group_tasks_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=34 ;

--
-- Дамп данных таблицы `items`
--

INSERT INTO `items` (`id`, `date`, `full_name`, `name`, `cost`, `views`, `distributed`, `short_description`, `description`, `category_id`, `image`, `group_tasks_id`) VALUES
(24, 1434193726, 'The Crew', 'the_crew', 100, 19, 0, 'The Crew — онлайн-видеоигра в жанре автосимулятор, разработанная Ivory Tower совместно с Ubisoft Reflections.', 'The Crew — онлайн-видеоигра в жанре автосимулятор, разработанная Ivory Tower совместно с Ubisoft Reflections. 13 августа 2014 года было объявлено о выходе на Xbox 360. 2 декабря игра поступила в продажу для Xbox One, PlayStation 4 и PC.', 7, 'http://cdn.akamai.steamstatic.com/steam/apps/241560/header.jpg?t=1420668551', 0),
(25, 1434289058, 'Team Fortress 2', 'team_fortress_2', 200, 32, 0, 'Девять принципиально различающихся классов дают простор для любых тактик и способностей', 'Девять принципиально различающихся классов дают простор для любых тактик и способностей. В игру постоянно добавляются новые игровые режимы, карты, снаряжение и, самое главное, шляпы! ', 7, 'http://cdn.akamai.steamstatic.com/steam/apps/440/header.jpg?t=1423092232', 0),
(26, 1434289356, 'Counter-Strike: Global Offensive', 'cs_go', 150, 16, 0, 'CS: GO включает в себя новые карты, персонажей и оружие.', 'Counter-Strike: Global Offensive (CS: GO) возродит тот ураганный командный игровой процесс, впервые представленный еще 12 лет назад. CS: GO включает в себя новые карты, персонажей и оружие, а также улучшенную версию классической составляющей CS (de_dust и т.п.). ', 7, 'http://cdn.akamai.steamstatic.com/steam/apps/730/header.jpg?t=1432930508', 0),
(27, 1434289994, 'Middle-earth: Shadow of Mordor', 'middle_earth', 100, 15, 0, 'Выясните всю правду о том, кто стремится подчинить вас своей воле.', 'Выясните всю правду о том, кто стремится подчинить вас своей воле. Разузнайте о кольцах власти и бросьте вызов Саурону в новой главе летописи Средиземья. ', 7, 'http://cdn.akamai.steamstatic.com/steam/apps/241930/header_russian.jpg?t=1433959719', 0),
(28, 1434290096, 'Portal 2', 'portal_2', 20, 67, 12, 'Программа вечного тестирования была расширена для создания совместных головоломок для вас и ваших друзей! ', 'Программа вечного тестирования была расширена для создания совместных головоломок для вас и ваших друзей! ', 7, 'http://cdn.akamai.steamstatic.com/steam/apps/620/header.jpg?t=1412276258', 0),
(29, 1434290234, 'Grand Theft Auto V', 'gta_5', 1500, 16, 0, 'Мультиплатформенная видеоигра в жанре Action и открытый мир.', 'Игра уже вышла! Чтобы провести серию дерзких ограблений и выжить в большом неприветливом городе, уличному ловчиле, вышедшему на пенсию грабителю банков и вселяющему ужас психопату приходится иметь дело с самыми опасными и безумными преступниками.', 7, 'http://cdn.akamai.steamstatic.com/steam/apps/271590/header.jpg?t=1434048749', 0),
(30, 1434290359, 'ARK: Survival Evolved', 'ark', 150, 13, 0, 'As a man or woman stranded naked, freezing...', 'As a man or woman stranded naked, freezing & starving on a mysterious island, you must hunt, harvest, craft items, grow crops, & build shelters to survive. Use skill and cunning to kill or tame & ride the Dinosaurs & primeval creatures roaming the land, & team up with hundreds of players or play locally! ', 7, 'http://cdn.akamai.steamstatic.com/steam/apps/346110/header.jpg?t=1433454769', 0),
(31, 1434290440, '7 Days to Die', '7_days_to_die', 80, 13, 0, 'Building on survivalist and horror themes...', 'Building on survivalist and horror themes, players in 7 Days to Die can scavenge the abandoned cities of the buildable and destructible voxel world for supplies or explore the wilderness to gather raw materials to build their own tools, weapons, traps, fortifications and shelters. ', 7, 'http://cdn.akamai.steamstatic.com/steam/apps/251570/header.jpg?t=1428116605', 0),
(32, 1434290555, 'Left 4 Dead 2', 'l4d2', 50, 226, 27, 'Left 4 Dead 2 — это высокооцененный сиквел к обладательнице многих наград.', 'Настоящий зомби-апокалипсис, Left 4 Dead 2 (L4D2) — это высокооцененный сиквел к обладательнице многих наград, Left 4 Dead — лучшей кооперативной игре 2008 года. В этом кооперативном хоррор-шутере с видом от первого лица вы и ваши друзья пройдут в пяти больших...', 7, 'http://cdn.akamai.steamstatic.com/steam/apps/550/header.jpg?t=1416966406', 2),
(33, 1434459077, 'Treasure Key', 'treasure_key', 40, 180, 66, 'Отпирает Treasure Chest.', 'Отпирает Treasure Chest. Нажмите два раза или кликните правой кнопкой мыши на ключ в вашем арсенале и выберите сокровищницу, которую вы хотите открыть.', 8, 'https://market.dota2.net/cdn/item_57939770_57939888.png', 2);

-- --------------------------------------------------------

--
-- Структура таблицы `items_categories`
--

CREATE TABLE IF NOT EXISTS `items_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `date` bigint(20) unsigned NOT NULL,
  `full_name` text CHARACTER SET utf8 NOT NULL,
  `name` text CHARACTER SET utf8 NOT NULL,
  `description` text CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=9 ;

--
-- Дамп данных таблицы `items_categories`
--

INSERT INTO `items_categories` (`id`, `date`, `full_name`, `name`, `description`) VALUES
(7, 1434279032, 'Steam', 'steam', ''),
(8, 1434459016, 'Dota 2 items', 'dota_2_items', '');

-- --------------------------------------------------------

--
-- Структура таблицы `items_group_tasks`
--

CREATE TABLE IF NOT EXISTS `items_group_tasks` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `full_name` text CHARACTER SET utf8 NOT NULL,
  `description` text CHARACTER SET utf8 NOT NULL,
  `list` text CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `items_group_tasks`
--

INSERT INTO `items_group_tasks` (`id`, `full_name`, `description`, `list`) VALUES
(2, 'Стандартные задания', '', '[3, 4]');

-- --------------------------------------------------------

--
-- Структура таблицы `items_history_balance`
--

CREATE TABLE IF NOT EXISTS `items_history_balance` (
  `id` bigint(20) unsigned NOT NULL,
  `type` tinyint(1) NOT NULL,
  ` description` text CHARACTER SET utf8 NOT NULL,
  `ip` text CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Структура таблицы `items_keys`
--

CREATE TABLE IF NOT EXISTS `items_keys` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `date` bigint(20) unsigned NOT NULL,
  `key` text CHARACTER SET utf8 NOT NULL,
  `help` text CHARACTER SET utf8 NOT NULL,
  `instruction` text CHARACTER SET utf8 NOT NULL,
  `inform` tinyint(1) NOT NULL,
  `item_id` bigint(20) unsigned NOT NULL,
  `number` int(20) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `items_keys`
--

INSERT INTO `items_keys` (`id`, `date`, `key`, `help`, `instruction`, `inform`, `item_id`, `number`) VALUES
(1, 1434534357, '1111-2222-3333-4444', 'information', ' instruction', 1, 33, 322),
(2, 1434719279, 'XCH5P-WK51Q-P2W7T-K9DB1-ET3DF', 'Бери и играй!', '', 0, 28, 967),
(3, 1435315025, 'QWER-TYUI-ASDF-GHJK', '', '', 0, 32, 973);

-- --------------------------------------------------------

--
-- Структура таблицы `items_referrals`
--

CREATE TABLE IF NOT EXISTS `items_referrals` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `date` bigint(20) unsigned NOT NULL,
  `ip` text NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=120 ;

-- --------------------------------------------------------

--
-- Структура таблицы `items_tasks`
--

CREATE TABLE IF NOT EXISTS `items_tasks` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `full_name` text CHARACTER SET utf8 NOT NULL,
  `instruction` text CHARACTER SET utf8 NOT NULL,
  `type` text CHARACTER SET utf8 NOT NULL,
  `parametrs` text CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `items_tasks`
--

INSERT INTO `items_tasks` (`id`, `full_name`, `instruction`, `type`, `parametrs`) VALUES
(3, '\\''Мне нравится\\'' - ВКонтакте', 'Лайкнуть <a href=\\''http://vk.com/wall1_45651\\'' target=\\''_blank\\''>http://vk.com/wall1_45651</a>', 'vk_likes', '{"type": "post", "owner_id": "1", "item_id": "45651", "filter": "likes"}'),
(4, '', 'Вступить в группу <a href=\\''http://vk.com/nexus_5\\''>http://vk.com/nexus_5</a>', 'vk_groups_is', '{\\"group_id\\": 57569441}');

-- --------------------------------------------------------

--
-- Структура таблицы `logs`
--

CREATE TABLE IF NOT EXISTS `logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `date` bigint(20) unsigned NOT NULL,
  `tag` varchar(255) CHARACTER SET utf8 NOT NULL,
  `text` text CHARACTER SET utf8 NOT NULL,
  `ip` text CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=122 ;

-- --------------------------------------------------------

--
-- Структура таблицы `messages`
--

CREATE TABLE IF NOT EXISTS `messages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `sender_id` bigint(20) unsigned NOT NULL,
  `subject` text CHARACTER SET utf8 NOT NULL,
  `text` text CHARACTER SET utf8 NOT NULL,
  `date` bigint(20) unsigned NOT NULL,
  `fresh` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=59 ;

--
-- Дамп данных таблицы `messages`
--

INSERT INTO `messages` (`id`, `user_id`, `sender_id`, `subject`, `text`, `date`, `fresh`) VALUES
(36, 1, 1, 'Вы получили "Left 4 Dead 2"!', 'Ключ: QWER-TYUI-ASDF-GHJK\n\nДополнительная информация: \n\nИнструкция: ', 1435521119, 1),
(37, 0, 1, 'Key', 'User ID: 1\n\nItem:\nArray\n(\n    [id] =&gt; 33\n    [date] =&gt; 1434459077\n    [full_name] =&gt; Treasure Key\n    [name] =&gt; treasure_key\n    [cost] =&gt; 40\n    [views] =&gt; 144\n    [distributed] =&gt; 59\n    [short_description] =&gt; Отпирает Treasure Chest.\n    [description] =&gt; Отпирает Treasure Chest. Нажмите два раза или кликните правой кнопкой мыши на ключ в вашем арсенале и выберите сокровищницу, которую вы хотите открыть.\n    [category_id] =&gt; 8\n    [image] =&gt; https://market.dota2.net/cdn/item_57939770_57939888.png\n    [group_tasks_id] =&gt; 2\n    [list_jobs_id] =&gt; \n)\n\n\nKey:\nArray\n(\n    [id] =&gt; 1\n    [date] =&gt; 1434534357\n    [key] =&gt; 1111-2222-3333-4444\n    [help] =&gt; information\n    [instruction] =&gt;  instruction\n    [inform] =&gt; 1\n    [item_id] =&gt; 33\n    [number] =&gt; 329\n)\n', 1435521995, 1),
(38, 1, 1, 'Вы получили "Treasure Key"!', 'Ключ: 1111-2222-3333-4444\n\nДополнительная информация: information\n\nИнструкция:  instruction', 1435521995, 1),
(39, 0, 1, 'Key', 'User ID: 1\n\nItem:\nArray\n(\n    [id] =&gt; 33\n    [date] =&gt; 1434459077\n    [full_name] =&gt; Treasure Key\n    [name] =&gt; treasure_key\n    [cost] =&gt; 40\n    [views] =&gt; 145\n    [distributed] =&gt; 60\n    [short_description] =&gt; Отпирает Treasure Chest.\n    [description] =&gt; Отпирает Treasure Chest. Нажмите два раза или кликните правой кнопкой мыши на ключ в вашем арсенале и выберите сокровищницу, которую вы хотите открыть.\n    [category_id] =&gt; 8\n    [image] =&gt; https://market.dota2.net/cdn/item_57939770_57939888.png\n    [group_tasks_id] =&gt; 2\n    [list_jobs_id] =&gt; \n)\n\n\nKey:\nArray\n(\n    [id] =&gt; 1\n    [date] =&gt; 1434534357\n    [key] =&gt; 1111-2222-3333-4444\n    [help] =&gt; information\n    [instruction] =&gt;  instruction\n    [inform] =&gt; 1\n    [item_id] =&gt; 33\n    [number] =&gt; 328\n)\n', 1435522204, 1),
(40, 1, 1, 'Вы получили "Treasure Key"!', 'Ключ: 1111-2222-3333-4444\n\nДополнительная информация: information\n\nИнструкция:  instruction', 1435522204, 1),
(41, 1, 1, 'Вы получили "Left 4 Dead 2"!', 'Ключ: QWER-TYUI-ASDF-GHJK\n\nДополнительная информация: \n\nИнструкция: ', 1435522429, 1),
(42, 0, 1, 'Key', 'User ID: 1\n\nItem:\nArray\n(\n    [id] =&gt; 33\n    [date] =&gt; 1434459077\n    [full_name] =&gt; Treasure Key\n    [name] =&gt; treasure_key\n    [cost] =&gt; 40\n    [views] =&gt; 146\n    [distributed] =&gt; 61\n    [short_description] =&gt; Отпирает Treasure Chest.\n    [description] =&gt; Отпирает Treasure Chest. Нажмите два раза или кликните правой кнопкой мыши на ключ в вашем арсенале и выберите сокровищницу, которую вы хотите открыть.\n    [category_id] =&gt; 8\n    [image] =&gt; https://market.dota2.net/cdn/item_57939770_57939888.png\n    [group_tasks_id] =&gt; 2\n    [list_jobs_id] =&gt; \n)\n\n\nKey:\nArray\n(\n    [id] =&gt; 1\n    [date] =&gt; 1434534357\n    [key] =&gt; 1111-2222-3333-4444\n    [help] =&gt; information\n    [instruction] =&gt;  instruction\n    [inform] =&gt; 1\n    [item_id] =&gt; 33\n    [number] =&gt; 327\n)\n', 1435522448, 1),
(43, 1, 1, 'Вы получили "Treasure Key"!', 'Ключ: 1111-2222-3333-4444\n\nДополнительная информация: information\n\nИнструкция:  instruction', 1435522448, 1),
(44, 0, 0, 'Key', 'User ID: 1\n\nItem:\nArray\n(\n    [id] =&gt; 33\n    [date] =&gt; 1434459077\n    [full_name] =&gt; Treasure Key\n    [name] =&gt; treasure_key\n    [cost] =&gt; 40\n    [views] =&gt; 147\n    [distributed] =&gt; 62\n    [short_description] =&gt; Отпирает Treasure Chest.\n    [description] =&gt; Отпирает Treasure Chest. Нажмите два раза или кликните правой кнопкой мыши на ключ в вашем арсенале и выберите сокровищницу, которую вы хотите открыть.\n    [category_id] =&gt; 8\n    [image] =&gt; https://market.dota2.net/cdn/item_57939770_57939888.png\n    [group_tasks_id] =&gt; 2\n    [list_jobs_id] =&gt; \n)\n\n\nKey:\nArray\n(\n    [id] =&gt; 1\n    [date] =&gt; 1434534357\n    [key] =&gt; 1111-2222-3333-4444\n    [help] =&gt; information\n    [instruction] =&gt;  instruction\n    [inform] =&gt; 1\n    [item_id] =&gt; 33\n    [number] =&gt; 326\n)\n', 1435522630, 1),
(45, 1, 0, 'Вы получили "Treasure Key"!', 'Ключ: 1111-2222-3333-4444\n\nДополнительная информация: information\n\nИнструкция:  instruction', 1435522630, 1),
(46, 1, 0, 'Вы получили "Portal 2"!', 'Ключ: XCH5P-WK51Q-P2W7T-K9DB1-ET3DF\n\nДополнительная информация: Бери и играй!\n\nИнструкция: ', 1435524084, 1),
(47, 0, 0, 'Key', 'User ID: 21\n\nItem:\nArray\n(\n    [id] =&gt; 33\n    [date] =&gt; 1434459077\n    [full_name] =&gt; Treasure Key\n    [name] =&gt; treasure_key\n    [cost] =&gt; 40\n    [views] =&gt; 163\n    [distributed] =&gt; 63\n    [short_description] =&gt; Отпирает Treasure Chest.\n    [description] =&gt; Отпирает Treasure Chest. Нажмите два раза или кликните правой кнопкой мыши на ключ в вашем арсенале и выберите сокровищницу, которую вы хотите открыть.\n    [category_id] =&gt; 8\n    [image] =&gt; https://market.dota2.net/cdn/item_57939770_57939888.png\n    [group_tasks_id] =&gt; 2\n    [list_jobs_id] =&gt; \n)\n\n\nKey:\nArray\n(\n    [id] =&gt; 1\n    [date] =&gt; 1434534357\n    [key] =&gt; 1111-2222-3333-4444\n    [help] =&gt; information\n    [instruction] =&gt;  instruction\n    [inform] =&gt; 1\n    [item_id] =&gt; 33\n    [number] =&gt; 325\n)\n', 1435525000, 1),
(48, 21, 0, 'Вы получили "Treasure Key"!', 'Ключ: 1111-2222-3333-4444\n\nДополнительная информация: information\n\nИнструкция:  instruction', 1435525000, 1),
(49, 21, 0, 'Вы получили "Portal 2"!', 'Ключ: XCH5P-WK51Q-P2W7T-K9DB1-ET3DF\n\nДополнительная информация: Бери и играй!\n\nИнструкция: ', 1435525039, 1),
(50, 21, 0, 'Вы получили "Portal 2"!', 'Ключ: XCH5P-WK51Q-P2W7T-K9DB1-ET3DF\n\nДополнительная информация: Бери и играй!\n\nИнструкция: ', 1435525083, 1),
(51, 22, 0, 'Вы получили "Portal 2"!', 'Ключ: XCH5P-WK51Q-P2W7T-K9DB1-ET3DF\n\nДополнительная информация: Бери и играй!\n\nИнструкция: ', 1435525567, 1),
(52, 22, 0, 'Вы получили "Portal 2"!', 'Ключ: XCH5P-WK51Q-P2W7T-K9DB1-ET3DF\n\nДополнительная информация: Бери и играй!\n\nИнструкция: ', 1435525584, 1),
(53, 0, 0, 'Key', 'User ID: 1\n\nItem:\nArray\n(\n    [id] =&gt; 33\n    [date] =&gt; 1434459077\n    [full_name] =&gt; Treasure Key\n    [name] =&gt; treasure_key\n    [cost] =&gt; 40\n    [views] =&gt; 171\n    [distributed] =&gt; 64\n    [short_description] =&gt; Отпирает Treasure Chest.\n    [description] =&gt; Отпирает Treasure Chest. Нажмите два раза или кликните правой кнопкой мыши на ключ в вашем арсенале и выберите сокровищницу, которую вы хотите открыть.\n    [category_id] =&gt; 8\n    [image] =&gt; https://market.dota2.net/cdn/item_57939770_57939888.png\n    [group_tasks_id] =&gt; 2\n    [list_jobs_id] =&gt; \n)\n\n\nKey:\nArray\n(\n    [id] =&gt; 1\n    [date] =&gt; 1434534357\n    [key] =&gt; 1111-2222-3333-4444\n    [help] =&gt; information\n    [instruction] =&gt;  instruction\n    [inform] =&gt; 1\n    [item_id] =&gt; 33\n    [number] =&gt; 324\n)\n', 1435525901, 1),
(54, 1, 0, 'Вы получили "Treasure Key"!', 'Ключ: 1111-2222-3333-4444\n\nДополнительная информация: information\n\nИнструкция:  instruction', 1435525901, 1),
(55, 0, 0, 'Key', 'User ID: 1\n\nItem:\nArray\n(\n    [id] =&gt; 33\n    [date] =&gt; 1434459077\n    [full_name] =&gt; Treasure Key\n    [name] =&gt; treasure_key\n    [cost] =&gt; 40\n    [views] =&gt; 177\n    [distributed] =&gt; 65\n    [short_description] =&gt; Отпирает Treasure Chest.\n    [description] =&gt; Отпирает Treasure Chest. Нажмите два раза или кликните правой кнопкой мыши на ключ в вашем арсенале и выберите сокровищницу, которую вы хотите открыть.\n    [category_id] =&gt; 8\n    [image] =&gt; https://market.dota2.net/cdn/item_57939770_57939888.png\n    [group_tasks_id] =&gt; 2\n    [list_jobs_id] =&gt; \n)\n\n\nKey:\nArray\n(\n    [id] =&gt; 1\n    [date] =&gt; 1434534357\n    [key] =&gt; 1111-2222-3333-4444\n    [help] =&gt; information\n    [instruction] =&gt;  instruction\n    [inform] =&gt; 1\n    [item_id] =&gt; 33\n    [number] =&gt; 323\n)\n', 1435668987, 1),
(56, 1, 0, 'Вы получили "Treasure Key"!', 'Ключ: 1111-2222-3333-4444\n\nДополнительная информация: information\n\nИнструкция:  instruction', 1435668987, 1),
(57, 1, 0, 'Вы получили "Left 4 Dead 2"!', 'Ключ: QWER-TYUI-ASDF-GHJK\n\nДополнительная информация: \n\nИнструкция: ', 1435687712, 1),
(58, 1, 0, 'Вы получили "Left 4 Dead 2"!', 'Ключ: QWER-TYUI-ASDF-GHJK\n\nДополнительная информация: \n\nИнструкция: ', 1435759059, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `usermeta`
--

CREATE TABLE IF NOT EXISTS `usermeta` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `meta_key` varchar(255) CHARACTER SET utf8 NOT NULL,
  `meta_value` longtext CHARACTER SET utf8 NOT NULL,
  `user_ip` text CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `date` bigint(20) unsigned NOT NULL,
  `login` varchar(60) NOT NULL,
  `email` text NOT NULL,
  `password` varchar(64) NOT NULL,
  `last_visit` bigint(20) unsigned NOT NULL,
  `user_level` int(11) NOT NULL,
  `points` bigint(20) NOT NULL,
  `vk_id` bigint(20) unsigned NOT NULL,
  `facebook_id` bigint(20) unsigned NOT NULL,
  `steam_trade_url` text NOT NULL,
  `logged_ip` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26 ;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `date`, `login`, `email`, `password`, `last_visit`, `user_level`, `points`, `vk_id`, `facebook_id`, `steam_trade_url`, `logged_ip`) VALUES
(1, 0, 'admin', '', 'b59c67bf196a4758191e42f76670ceba', 1435762400, 10, 20001, 0, 0, '', '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
