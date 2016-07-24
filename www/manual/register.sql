-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Июл 24 2016 г., 22:58
-- Версия сервера: 5.5.25
-- Версия PHP: 5.5.37

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- База данных: `register`
--

-- --------------------------------------------------------

--
-- Структура таблицы `block`
--

CREATE TABLE IF NOT EXISTS `block` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name_block` varchar(255) NOT NULL,
  `added` int(11) NOT NULL DEFAULT '0',
  `last_update` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Структура таблицы `cache_stats`
--

CREATE TABLE IF NOT EXISTS `cache_stats` (
  `cache_name` varchar(255) NOT NULL,
  `cache_value` text,
  PRIMARY KEY (`cache_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `changelog`
--

CREATE TABLE IF NOT EXISTS `changelog` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `added` int(10) NOT NULL,
  `date` int(10) NOT NULL,
  `rev` varchar(10) NOT NULL,
  `text` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Структура таблицы `department`
--

CREATE TABLE IF NOT EXISTS `department` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name_department` varchar(255) NOT NULL,
  `id_parent` int(10) NOT NULL DEFAULT '0',
  `id_child` int(10) NOT NULL DEFAULT '0',
  `added` int(10) NOT NULL DEFAULT '0',
  `last_update` int(10) NOT NULL DEFAULT '0',
  `id_type_office` smallint(3) NOT NULL DEFAULT '0',
  `level` int(10) NOT NULL DEFAULT '0',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=191 ;

-- --------------------------------------------------------

--
-- Структура таблицы `direction`
--

CREATE TABLE IF NOT EXISTS `direction` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name_direction` varchar(255) NOT NULL,
  `id_employee` int(10) NOT NULL DEFAULT '0',
  `added` int(10) NOT NULL DEFAULT '0',
  `last_update` int(10) NOT NULL DEFAULT '0',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Структура таблицы `employee`
--

CREATE TABLE IF NOT EXISTS `employee` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name_employee` varchar(255) NOT NULL,
  `id_uid_post` int(10) NOT NULL DEFAULT '0',
  `id_location_place` int(10) NOT NULL DEFAULT '0',
  `email` varchar(255) NOT NULL,
  `date_employment` int(10) NOT NULL DEFAULT '0',
  `date_transfer` int(10) NOT NULL DEFAULT '0',
  `id_functionality` varchar(20) NOT NULL DEFAULT '0',
  `added` int(10) NOT NULL,
  `last_update` int(10) NOT NULL,
  `fte` float NOT NULL DEFAULT '0',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `id_strategic_project` int(10) NOT NULL DEFAULT '0',
  `id_employee_model` int(10) NOT NULL DEFAULT '0',
  `id_parent_ee` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1546 ;

-- --------------------------------------------------------

--
-- Структура таблицы `employee_model`
--

CREATE TABLE IF NOT EXISTS `employee_model` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name_model` varchar(255) NOT NULL,
  `added` int(10) NOT NULL,
  `last_update` int(10) NOT NULL DEFAULT '0',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Структура таблицы `established_post`
--

CREATE TABLE IF NOT EXISTS `established_post` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid_post` int(10) NOT NULL DEFAULT '0',
  `id_position` int(10) NOT NULL DEFAULT '0',
  `id_block` tinyint(2) NOT NULL DEFAULT '0',
  `id_department` varchar(15) NOT NULL DEFAULT '0',
  `id_direction` smallint(3) NOT NULL DEFAULT '0',
  `id_rck` tinyint(3) NOT NULL DEFAULT '0',
  `id_mvz` smallint(3) NOT NULL DEFAULT '0',
  `date_entry` int(10) NOT NULL DEFAULT '0',
  `added` int(10) NOT NULL DEFAULT '0',
  `last_update` int(10) DEFAULT '0',
  `id_location_city` int(10) NOT NULL DEFAULT '0',
  `id_functional_manager` int(10) NOT NULL DEFAULT '0',
  `id_administrative_manager` int(10) NOT NULL DEFAULT '0',
  `draft` tinyint(1) DEFAULT '0',
  `transfer` tinyint(1) NOT NULL DEFAULT '0',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `id_parent_ep` int(10) NOT NULL DEFAULT '0' COMMENT 'Нужно для поиска истории и исходной записи (ту что изменяли самой первой)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1533 ;

-- --------------------------------------------------------

--
-- Структура таблицы `functionality`
--

CREATE TABLE IF NOT EXISTS `functionality` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name_functionality` varchar(266) NOT NULL,
  `id_parent` smallint(3) NOT NULL DEFAULT '0',
  `added` int(10) NOT NULL DEFAULT '0',
  `last_update` int(10) NOT NULL DEFAULT '0',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=87 ;

-- --------------------------------------------------------

--
-- Структура таблицы `location_address`
--

CREATE TABLE IF NOT EXISTS `location_address` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `id_city` int(10) NOT NULL DEFAULT '0',
  `name_address` varchar(255) NOT NULL,
  `added` int(10) DEFAULT NULL,
  `last_update` int(10) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Структура таблицы `location_city`
--

CREATE TABLE IF NOT EXISTS `location_city` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name_city` varchar(255) NOT NULL,
  `added` int(10) DEFAULT NULL,
  `last_update` int(10) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `co` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=62 ;

-- --------------------------------------------------------

--
-- Структура таблицы `location_place`
--

CREATE TABLE IF NOT EXISTS `location_place` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `id_address` int(10) NOT NULL DEFAULT '0',
  `floor` tinyint(2) NOT NULL DEFAULT '0',
  `room` varchar(15) NOT NULL,
  `place` varchar(15) NOT NULL,
  `ready` tinyint(1) NOT NULL DEFAULT '0',
  `date_ready` int(10) NOT NULL DEFAULT '0',
  `reservation` tinyint(1) NOT NULL DEFAULT '0',
  `date_reservation` int(10) NOT NULL DEFAULT '0',
  `occupy` tinyint(1) NOT NULL DEFAULT '0',
  `date_occupy` int(10) NOT NULL DEFAULT '0',
  `added` int(10) NOT NULL DEFAULT '0',
  `last_update` int(10) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=178 ;

-- --------------------------------------------------------

--
-- Структура таблицы `mvz`
--

CREATE TABLE IF NOT EXISTS `mvz` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `id_rck` tinyint(2) NOT NULL DEFAULT '0',
  `name_mvz` varchar(255) NOT NULL,
  `added` int(10) NOT NULL DEFAULT '0',
  `last_update` int(10) NOT NULL DEFAULT '0',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=44 ;

-- --------------------------------------------------------

--
-- Структура таблицы `orbital_blocks`
--

CREATE TABLE IF NOT EXISTS `orbital_blocks` (
  `bid` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(60) NOT NULL,
  `content` text NOT NULL,
  `bposition` char(1) NOT NULL,
  `weight` int(10) NOT NULL DEFAULT '1',
  `active` int(1) NOT NULL DEFAULT '1',
  `blockfile` varchar(255) NOT NULL,
  `view` varchar(20) DEFAULT NULL,
  `expire` int(10) NOT NULL DEFAULT '0',
  `which` varchar(255) NOT NULL,
  `custom_tpl` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`bid`),
  KEY `title` (`title`),
  KEY `weight` (`weight`),
  KEY `active` (`active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `position`
--

CREATE TABLE IF NOT EXISTS `position` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name_position` varchar(255) NOT NULL,
  `is_head` tinyint(1) NOT NULL DEFAULT '0',
  `2` int(10) DEFAULT NULL,
  `added` int(10) NOT NULL DEFAULT '0',
  `last_update` int(10) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=81 ;

-- --------------------------------------------------------

--
-- Структура таблицы `rck`
--

CREATE TABLE IF NOT EXISTS `rck` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name_rck` varchar(255) NOT NULL,
  `added` int(10) NOT NULL DEFAULT '0',
  `last_update` int(10) NOT NULL DEFAULT '0',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sessions`
--

CREATE TABLE IF NOT EXISTS `sessions` (
  `sid` varchar(32) NOT NULL,
  `uid` int(10) NOT NULL DEFAULT '0',
  `username` varchar(40) NOT NULL,
  `class` tinyint(4) NOT NULL DEFAULT '0',
  `ip` varchar(40) NOT NULL,
  `time` bigint(30) NOT NULL DEFAULT '0',
  `url` varchar(150) NOT NULL,
  `useragent` text,
  PRIMARY KEY (`sid`),
  KEY `time` (`time`),
  KEY `uid` (`uid`),
  KEY `url` (`url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `strategic_project`
--

CREATE TABLE IF NOT EXISTS `strategic_project` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name_project` varchar(255) NOT NULL,
  `added` int(10) NOT NULL DEFAULT '0',
  `last_update` int(10) NOT NULL DEFAULT '0',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Структура таблицы `type_office`
--

CREATE TABLE IF NOT EXISTS `type_office` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name_office` varchar(255) NOT NULL,
  `added` int(10) NOT NULL DEFAULT '0',
  `last_update` int(10) NOT NULL DEFAULT '0',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(9) NOT NULL,
  `name` varchar(255) NOT NULL,
  `passhash` varchar(32) NOT NULL,
  `secret` varchar(20) NOT NULL,
  `added` int(10) NOT NULL DEFAULT '0',
  `class` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `ip` varchar(15) NOT NULL,
  `enable` tinyint(1) NOT NULL DEFAULT '1',
  `banned` tinyint(1) NOT NULL DEFAULT '0',
  `dis_reason` text NOT NULL,
  `last_access` int(10) NOT NULL DEFAULT '0',
  `last_login` int(10) NOT NULL,
  `last_update` int(10) NOT NULL,
  `register_user` varchar(255) NOT NULL,
  `notifs` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`login`),
  KEY `added` (`added`),
  KEY `ip` (`ip`),
  KEY `user` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=2 ;
