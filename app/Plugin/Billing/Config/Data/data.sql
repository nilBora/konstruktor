-- phpMyAdmin SQL Dump
-- version 4.3.8deb0.1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Час створення: Вер 07 2015 р., 15:45
-- Версія сервера: 5.5.44-0ubuntu0.14.04.1
-- Версія PHP: 5.5.9-1ubuntu4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- База даних: `struct`
--

--
-- Truncate table before insert `billing_customers`
--

TRUNCATE TABLE `billing_customers`;
--
-- Truncate table before insert `billing_groups`
--

TRUNCATE TABLE `billing_groups`;
--
-- Дамп даних таблиці `billing_groups`
--

INSERT INTO `billing_groups` (`id`, `title`, `slug`, `limit_units`, `active`, `created`, `modified`) VALUES
(1, 'Disc space', 'disc-space', 'bytes', 1, '2015-07-31 12:38:14', '2015-08-16 17:53:35'),
(2, 'Members', 'members', 'members', 1, '2015-08-16 10:41:55', '2015-08-16 17:53:43');

--
-- Truncate table before insert `billing_plans`
--

TRUNCATE TABLE `billing_plans`;
--
-- Дамп даних таблиці `billing_plans`
--

INSERT INTO `billing_plans` (`id`, `group_id`, `title`, `slug`, `description`, `limit_value`, `remote_plans`, `free`, `created`, `modified`) VALUES
(1, 1, '2 Gb', '2-gb', '', 2147483648, '', 1, '2015-07-30 13:21:45', '2015-08-16 18:28:06'),
(2, 1, '10 Gb', '10-gb', '', 10737418240, '["disk-10-monthly","disk-10-yearly"]', 0, '2015-07-31 13:04:39', '2015-08-16 18:05:36'),
(3, 1, '100 Gb', '100-gb', '', 107374182400, '["disk-100-monthly","disk-100-yearly"]', 0, '2015-07-31 13:05:05', '2015-08-16 18:06:08'),
(4, 1, '1 Tb', '1-tb', '', 1099511627776, '["disk-1000-monthly","disk-1000-yearly"]', 0, '2015-07-31 13:05:53', '2015-08-16 18:10:28'),
(5, 2, 'Members', 'members', '', 0, '["members-monthly","members-yearly"]', 0, '2015-08-16 10:58:03', '2015-08-16 10:59:45');

--
-- Truncate table before insert `billing_subscriptions`
--

TRUNCATE TABLE `billing_subscriptions`;
