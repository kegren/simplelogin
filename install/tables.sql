-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 04, 2014 at 02:10 PM
-- Server version: 5.5.35
-- PHP Version: 5.5.9-1+sury.org~precise+1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `test`
--

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(70) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `name`) VALUES
(1, 'superadmin'),
(2, 'admin'),
(3, 'user');

-- --------------------------------------------------------

--
-- Table structure for table `group_permission`
--

CREATE TABLE IF NOT EXISTS `group_permission` (
  `group_id` int(16) unsigned NOT NULL,
  `permission_id` int(16) unsigned NOT NULL,
  KEY `group_id` (`group_id`),
  KEY `permission_id` (`permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `group_permission`
--

INSERT INTO `group_permission` (`group_id`, `permission_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(2, 1),
(2, 3),
(1, 5),
(1, 6),
(1, 7),
(1, 7),
(1, 7),
(1, 7),
(1, 7),
(1, 7),
(2, 5),
(2, 5),
(3, 3);

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(140) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`) VALUES
(1, 'user_edit'),
(2, 'user_delete'),
(3, 'user_update'),
(4, 'user_ban'),
(5, 'group_change'),
(6, 'permission_add'),
(7, 'permission_update');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(60) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `banned` int(16) DEFAULT '0',
  `created_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `username`, `password`, `first_name`, `last_name`, `banned`, `created_at`) VALUES
(3, 'admin@demomail.com', 'superadmin', '$2y$12$SokeAKjdaw12AmSjDEolM.fflSfECe1dMxEItP/Mrirc.C49mcX7S', 'Erik', 'Larsson', 0, 1393884219),
(4, 'user@demomail.com', 'admin', '$2y$12$SokeAKjdaw12AmSjDEolM.kP0cJ0naGodlMB/ief9ijOxgJOlgYsO', 'Niklas', 'Lundin', 0, 1393929556),
(5, 'user@demomail.com', 'user', '$2y$12$SokeAKjdaw12AmSjDEolM.cQYI07n0tSOvWct1ohYjZ4EV6fy/i3S', 'Elin', 'Svensson', 0, 232323232);

-- --------------------------------------------------------

--
-- Table structure for table `user_group`
--

CREATE TABLE IF NOT EXISTS `user_group` (
  `user_id` int(16) unsigned NOT NULL,
  `group_id` int(16) unsigned NOT NULL,
  KEY `user_id` (`user_id`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_group`
--

INSERT INTO `user_group` (`user_id`, `group_id`) VALUES
(3, 1),
(4, 2),
(5, 3);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `group_permission`
--
ALTER TABLE `group_permission`
  ADD CONSTRAINT `group_permission_ibfk_4` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `group_permission_ibfk_3` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_group`
--
ALTER TABLE `user_group`
  ADD CONSTRAINT `user_group_ibfk_4` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_group_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
