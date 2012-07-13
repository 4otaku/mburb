

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `mburb`
--

-- --------------------------------------------------------

--
-- Структура таблицы `file`
--

CREATE TABLE IF NOT EXISTS `file` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL,
  `ext` varchar(8) NOT NULL,
  `md5` char(32) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Структура таблицы `strip`
--

CREATE TABLE IF NOT EXISTS `strip` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `text` longtext NOT NULL,
  `published` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `order` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
ALTER TABLE  `strip` ADD  `date` DATE NULL;

-- --------------------------------------------------------

--
-- Структура таблицы `strip_file`
--

CREATE TABLE IF NOT EXISTS `strip_file` (
  `id_strip` int(10) unsigned NOT NULL,
  `id_file` int(10) unsigned NOT NULL,
  `order` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id_strip`,`id_file`,`order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(40) NOT NULL,
  `password` varchar(32) NOT NULL,
  `cookie` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`,`cookie`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
