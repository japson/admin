-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Сен 05 2017 г., 00:34
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
-- Структура таблицы `editors`
--

CREATE TABLE IF NOT EXISTS `editors` (
  `kod` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(255) NOT NULL,
  `pssw` varchar(255) NOT NULL,
  `rol` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `vyvod` int(2) NOT NULL,
  `sort` int(11) NOT NULL,
  `activehex` varchar(255) NOT NULL,
  `aunt` varchar(255) NOT NULL,
  `kodmenu` int(2) NOT NULL,
  `kodrasdel` int(2) NOT NULL,
  PRIMARY KEY (`kod`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `editors`
--

INSERT INTO `editors` (`kod`, `login`, `pssw`, `rol`, `email`, `vyvod`, `sort`, `activehex`, `aunt`, `kodmenu`, `kodrasdel`) VALUES
(1, 'admin', '202cb962ac59075b964b07152d234b70', 1, '', 1, 1, '', '4f9f6b92f17ad82cc88568d32490a750', 0, 0),
(2, 'user', '202cb962ac59075b964b07152d234b70', 3, '', 0, 2, '', '', 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `init`
--

CREATE TABLE IF NOT EXISTS `init` (
  `kod` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `sort` int(11) NOT NULL,
  `vyvod` int(2) NOT NULL,
  PRIMARY KEY (`kod`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `init`
--

INSERT INTO `init` (`kod`, `name`, `sort`, `vyvod`) VALUES
(1, 'Menu', 1, 0),
(2, 'Users', 2, 0),
(3, 'Settings', 3, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `mainmenu`
--

CREATE TABLE IF NOT EXISTS `mainmenu` (
  `kod` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `nameurl` varchar(255) NOT NULL,
  `title` varchar(256) NOT NULL,
  `href` varchar(255) NOT NULL,
  `sort` int(11) NOT NULL,
  `vyvod` int(2) NOT NULL,
  `rol` int(2) NOT NULL,
  `kodmenu` int(2) NOT NULL,
  `kodrasdel` int(2) NOT NULL,
  PRIMARY KEY (`kod`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `mainmenu`
--

INSERT INTO `mainmenu` (`kod`, `name`, `nameurl`, `title`, `href`, `sort`, `vyvod`, `rol`, `kodmenu`, `kodrasdel`) VALUES
(1, 'Музыка', 'music', 'Каталог музыкальных записей', '', 1, 0, 4, 0, 0),
(2, 'Стихи', 'poems', 'Собрание стихотворений', '', 2, 0, 3, 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `kod` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `nameurl` varchar(255) NOT NULL,
  `post` text NOT NULL,
  `keywords` text NOT NULL,
  `description` text NOT NULL,
  `sort` int(11) NOT NULL,
  `vyvod` int(2) NOT NULL,
  `pictur` int(2) NOT NULL,
  `kodmenu` int(11) NOT NULL,
  `kodrasdel` int(11) NOT NULL,
  PRIMARY KEY (`kod`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Дамп данных таблицы `news`
--

INSERT INTO `news` (`kod`, `name`, `nameurl`, `post`, `keywords`, `description`, `sort`, `vyvod`, `pictur`, `kodmenu`, `kodrasdel`) VALUES
(1, 'Первая', 'firsterrter', '&lt;p style=&quot;text-align:justify&quot;&gt;&lt;img alt=&quot;&quot; src=&quot;http://proba.ru//catalog/imgposts/Casotto_1977-1-20-14-331.jpg&quot; style=&quot;float:right; height:160px; margin-left:3px; margin-right:3px; width:300px&quot; /&gt;sdfsdf dsfsdf рпрапрапр6 75675 6567&lt;/p&gt;\n\n&lt;p&gt;аывафвав нкен56889823242&lt;/p&gt;\n\n&lt;p&gt;;:%;*?:*(*?Элорлпр&lt;/p&gt;\n\n&lt;p&gt;Bloody heart-beat music that distills anger, anxiety and energy into short form songs. This is the last of a musical breed that adheres to a focused lineage. A lineage that goes all the way back, throughout underground music culture, to a point where history and fact evaporate into sacred myth. For a special breed, this is the only thing that has true meaning and is the battle command that serves as one&amp;rsquo;s occult guide through the ruins of modern life.&lt;br /&gt;\nThe first recordings of this 3-piece project that features: M. del Rio (Bone Awl, Raspberry Bulbs, etc.), N. Alcantara, and D. Gwanaabi. 6 songs recorded on tape 4-track&lt;/p&gt;\n\n&lt;p&gt;[_page]&lt;/p&gt;\n\n&lt;p&gt;Svordom is a four piece band from Turku, Finland who formed in 2014.&amp;nbsp; Svordom play a killer style of music that incorporates elements of hardcore, punk, crust, and d-beat within their sound.&amp;nbsp; Musically, Svordom can be loosely compared to bands such as Infest, Chokehold, Cursed, Tragedy, and His Hero Is Gone.&amp;nbsp; Since forming in 2014, Svordom have released a three song EP titled Svett, Spott &amp;amp; Svordomar in November of 2014 and six song EP titled Forstord Varld in December of 2015.&amp;nbsp; F&amp;ouml;r Dom Som &amp;auml;r Ensamma is the band&amp;#39;s latest seven song EP, which was released on March 1st, 2017.&amp;nbsp; On F&amp;ouml;r Dom Som &amp;auml;r Ensamma, Svordom offer up seven tracks of ugly, chaotic, and vicious sounding hardcore, punk, crust, and d-beat.&amp;nbsp; Overall, F&amp;ouml;r Dom Som &amp;auml;r Ensamma makes for a killer listen and definitely should not be missed.&amp;nbsp; Highly recommended!&amp;nbsp; Enjoy!&lt;/p&gt;\n\n&lt;p&gt;[_page]&lt;/p&gt;\n\n&lt;p&gt;the music from those movies makes you feel good. makes you smile. you cant help but smile when listening to &amp;quot;mr. blue sky&amp;quot; by electric light orchestra and think about baby groot dancing around and mr. magoo-like avoiding the chaos behind him. 2 days of this happy sunshine good time music. time to come back down to earth. enter dark habits. dark habits is not happy. dark habits will not make you smile. and furthermore I&amp;#39;m pretty convinced that dark habits hates you and wants to see you bleed. norman greenbaum and blue suede want you sing along and snap your fingers. dark habits want you to scream in pain and snap your bones. dark habits is pure fury. pure rage. you like full of hell? you like nails? you like the secret? you like column of heaven? you will like dark habits.&lt;/p&gt;\n', 'укеукеукеук, епукеукеук', 'аываыв ываыва\n654\nеукеук\n', 1, 0, 0, 2, 2),
(3, 'hgfhfghfg', 'ghfghfg', '', '', '', 1, 0, 0, 2, 3),
(5, 'hfghfg', '', '', '', '', 1, 0, 0, 2, 12);

-- --------------------------------------------------------

--
-- Структура таблицы `newsimg`
--

CREATE TABLE IF NOT EXISTS `newsimg` (
  `kod` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `name_small` varchar(255) NOT NULL,
  `kodrasdel` int(11) NOT NULL,
  `note` varchar(255) NOT NULL,
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`kod`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `newsimg`
--

INSERT INTO `newsimg` (`kod`, `name`, `name_small`, `kodrasdel`, `note`, `sort`) VALUES
(3, '2-1-1-qweqweqweqw-1705080227_big.jpg', '2-1-1-qweqweqweqw-1705080227_small.jpg', 1, '534534534', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `proba`
--

CREATE TABLE IF NOT EXISTS `proba` (
  `kod` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `sort` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `proba`
--

INSERT INTO `proba` (`kod`, `name`, `sort`) VALUES
(NULL, 'dasdas', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `rasdel`
--

CREATE TABLE IF NOT EXISTS `rasdel` (
  `kod` int(11) NOT NULL AUTO_INCREMENT,
  `kodmenu` int(11) NOT NULL,
  `kodrasdel` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `nameurl` varchar(255) NOT NULL,
  `sort` int(11) NOT NULL,
  `vyvod` int(2) NOT NULL,
  `pictur` int(2) NOT NULL,
  PRIMARY KEY (`kod`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Дамп данных таблицы `rasdel`
--

INSERT INTO `rasdel` (`kod`, `kodmenu`, `kodrasdel`, `name`, `nameurl`, `sort`, `vyvod`, `pictur`) VALUES
(1, 1, 0, 'новый', 'pipec', 1, 0, 0),
(2, 2, 0, 'раздел', 'ertre', 1, 0, 0),
(3, 2, 2, 'фывывфыв', '54345fgfefdgdfg', 1, 0, 0),
(4, 2, 2, 'hkjhkh', 'fgdf', 2, 0, 0),
(5, 2, 4, 'khjqweq', '1gfhfg', 1, 0, 0),
(6, 2, 0, 'апрап', 'апрапр', 2, 0, 0),
(9, 2, 4, 'rtyrt', '', 2, 0, 0),
(12, 2, 9, 'ghfghg', 'fghfg', 1, 0, 0),
(13, 2, 12, 'fghfghfg', 'fghfg', 1, 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `rasdelimg`
--

CREATE TABLE IF NOT EXISTS `rasdelimg` (
  `kod` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `name_small` varchar(255) NOT NULL,
  `kodrasdel` int(11) NOT NULL,
  `note` varchar(255) NOT NULL,
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`kod`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=40 ;

--
-- Дамп данных таблицы `rasdelimg`
--

INSERT INTO `rasdelimg` (`kod`, `name`, `name_small`, `kodrasdel`, `note`, `sort`) VALUES
(19, '2-4-1-asdasda-dfgdf-fsdfsd-1704270120_big.jpg', '2-4-1-asdasda-dfgdf-fsdfsd-1704270120_small.jpg', 4, 'sdasdas', 6),
(24, '2-4-6-qweqw-1704270905_big.jpg', '2-4-6-qweqw-1704270905_small.jpg', 4, 'qweqw trtryrt56456456456', 4),
(25, '2-4-7-dfsdfsdfsdfs-1704270906_big.jpg', '2-4-7-dfsdfsdfsdfs-1704270906_small.jpg', 4, 'asdas', 5),
(26, '2-4-8-dfsdfsdfsdfs-1704270907_big.jpg', '2-4-8-dfsdfsdfsdfs-1704270907_small.jpg', 4, 'werwe', 7),
(27, '2-4-9-dfsdfsdfsdfs-1704270909_big.jpg', '2-4-9-dfsdfsdfsdfs-1704270909_small.jpg', 4, 'rgsedgaga', 1),
(28, '2-4-10-dfsdfsdfsdfs-1704270911_big.jpg', '2-4-10-dfsdfsdfsdfs-1704270911_small.jpg', 4, 'rgsedgaga', 8),
(29, '2-4-11-dfsdfsdfsdfs-1704270914_big.jpg', '2-4-11-dfsdfsdfsdfs-1704270914_small.jpg', 4, 'dfsdfsdfsdfs', 9),
(30, '2-4-12-dfsdfsdfsdfs-1704270915_big.jpg', '2-4-12-dfsdfsdfsdfs-1704270915_small.jpg', 4, 'dfsdfsdfsdfs', 10),
(31, '2-3-1-fsdfsdfsd-1704270916_big.jpg', '2-3-1-fsdfsdfsd-1704270916_small.jpg', 3, 'master_kungfu', 1),
(35, '2-3-5-d-1704270926_big.jpg', '2-3-5-d-1704270926_small.jpg', 3, 'gdf', 4),
(38, '2-4-9-sleep-1705070300_big.jpg', '2-4-9-sleep-1705070300_small.jpg', 4, 'Спящие', 3),
(39, '2-3-3-wqeqwe-1705080223_big.jpg', '2-3-3-wqeqwe-1705080223_small.jpg', 3, '53456345', 5);

-- --------------------------------------------------------

--
-- Структура таблицы `sets`
--

CREATE TABLE IF NOT EXISTS `sets` (
  `kod` int(11) NOT NULL AUTO_INCREMENT,
  `parametr` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `info` varchar(255) NOT NULL,
  `sort` int(11) NOT NULL,
  `vyvod` int(2) NOT NULL,
  `kodmenu` int(2) NOT NULL,
  `kodrasdel` int(2) NOT NULL,
  PRIMARY KEY (`kod`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `sets`
--

INSERT INTO `sets` (`kod`, `parametr`, `value`, `info`, `sort`, `vyvod`, `kodmenu`, `kodrasdel`) VALUES
(1, 'heightpicturmax', '1000', 'макс высота картинки в пикселах', 1, 1, 0, 0),
(3, 'ываыв', '', 'ываыв', 2, 0, 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `typmenu`
--

CREATE TABLE IF NOT EXISTS `typmenu` (
  `kod` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  PRIMARY KEY (`kod`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `typmenu`
--

INSERT INTO `typmenu` (`kod`, `type`) VALUES
(1, 'Разделы'),
(2, 'Установки'),
(3, 'Статьи'),
(4, 'Каталог');

-- --------------------------------------------------------

--
-- Структура таблицы `typuser`
--

CREATE TABLE IF NOT EXISTS `typuser` (
  `kod` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  PRIMARY KEY (`kod`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `typuser`
--

INSERT INTO `typuser` (`kod`, `type`) VALUES
(1, 'Admin'),
(2, 'Editor'),
(3, 'User');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
