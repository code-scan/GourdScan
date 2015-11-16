-- phpMyAdmin SQL Dump
-- version 3.4.9
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2015 年 11 月 16 日 02:57
-- 服务器版本: 5.5.20
-- PHP 版本: 5.3.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `pscan`
--

-- --------------------------------------------------------

--
-- 表的结构 `apiconfig`
--

CREATE TABLE IF NOT EXISTS `apiconfig` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(255) CHARACTER SET gbk DEFAULT 'none',
  `contents` varchar(255) CHARACTER SET gb2312 DEFAULT NULL,
  `userid` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `apiconfig`
--

INSERT INTO `apiconfig` (`id`, `ip`, `contents`, `userid`) VALUES
(1, 'http://127.0.0.1:8775', 'local', '111'),
(3, 'http://127.0.0.1:8775', 'linux', NULL),
(4, 'http://10.1.25.52:8775', 'my pc', NULL);

-- --------------------------------------------------------

--
-- 表的结构 `config`
--

CREATE TABLE IF NOT EXISTS `config` (
  `key` varchar(255) DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `value` text CHARACTER SET gb2312,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `config`
--

INSERT INTO `config` (`key`, `id`, `value`) VALUES
('blackexts', 1, 'ico,flv,.js,css,jpg,png,jpeg,gif,pdf,ss3,txt,rar,zip,avi,mp4,swf,wmi,exe,mpeg'),
('blackdomains', 2, 'ditu.google.cn,doubleclick,cnzz.com,baidu.com,40017.cn,google-analytics.com,googlesyndication,gstatic.com,bing.com,google.com'),
('whiteext', 3, 'php,jsp,jspx,ion,aspx,asp');

-- --------------------------------------------------------

--
-- 表的结构 `dirscan`
--

CREATE TABLE IF NOT EXISTS `dirscan` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Id`),
  FULLTEXT KEY `url` (`url`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk AUTO_INCREMENT=80 ;

-- --------------------------------------------------------

--
-- 表的结构 `sqlmap`
--

CREATE TABLE IF NOT EXISTS `sqlmap` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(255) DEFAULT '',
  `status` varchar(255) DEFAULT NULL,
  `data` text,
  `request` text,
  `url` text,
  `hash` varchar(255) DEFAULT NULL,
  `pr` varchar(255) DEFAULT '-2',
  `dirscan` int(1) DEFAULT '0',
  `dbtype` varchar(255) DEFAULT 'UnKnow',
  `apiserver` varchar(255) DEFAULT NULL,
  `userhash` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk AUTO_INCREMENT=6959 ;

--
-- 转存表中的数据 `sqlmap`
--


-- --------------------------------------------------------

--
-- 表的结构 `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `userhash` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
