# ************************************************************
# Sequel Pro SQL dump
# Version 4499
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.6.22-log)
# Database: struct_dev
# Generation Time: 2015-11-10 13:43:44 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table api_accesses
# ------------------------------------------------------------

DROP TABLE IF EXISTS `api_accesses`;

CREATE TABLE `api_accesses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table app_sessions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_sessions`;

CREATE TABLE `app_sessions` (
  `id` varchar(255) NOT NULL,
  `data` text,
  `expires` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `app_sessions` WRITE;
/*!40000 ALTER TABLE `app_sessions` DISABLE KEYS */;

INSERT INTO `app_sessions` (`id`, `data`, `expires`)
VALUES
	('07090denhc8ik0delge7npd0s4','Config|a:3:{s:9:\"userAgent\";s:32:\"bf5e323501b836aeb55513fb5a6ee76c\";s:4:\"time\";i:1452346399;s:9:\"countdown\";i:10;}Message|a:1:{s:4:\"auth\";a:3:{s:7:\"message\";s:35:\"You must log in to access this page\";s:7:\"element\";s:7:\"default\";s:6:\"params\";a:0:{}}}Auth|a:1:{s:8:\"redirect\";s:24:\"/js/settings?1447162399=\";}',1452346399),
	('0cku6q3r2jddn6gog9coaflcg5','Config|a:3:{s:9:\"userAgent\";s:32:\"7cf3d724c6ae13d5cdd57e5e77f794e3\";s:4:\"time\";i:1452272999;s:9:\"countdown\";i:10;}Message|a:0:{}Auth|a:1:{s:4:\"User\";a:33:{s:2:\"id\";s:1:\"2\";s:4:\"fbid\";N;s:7:\"created\";s:19:\"2015-11-09 16:18:58\";s:8:\"modified\";s:19:\"2015-11-09 16:18:58\";s:8:\"is_admin\";s:1:\"0\";s:12:\"is_confirmed\";b:0;s:10:\"is_deleted\";b:0;s:8:\"username\";s:16:\"metalliova@ua.fm\";s:9:\"full_name\";s:15:\"Yuriy Levytskyy\";s:11:\"profile_url\";N;s:9:\"video_url\";N;s:8:\"video_id\";N;s:6:\"skills\";N;s:9:\"interests\";N;s:8:\"birthday\";N;s:4:\"lang\";s:3:\"eng\";s:5:\"phone\";N;s:10:\"live_place\";s:5:\"L\'viv\";s:12:\"live_address\";s:26:\"Hetmana Mazepy Street, 5А\";s:10:\"university\";N;s:10:\"speciality\";N;s:12:\"live_country\";s:2:\"UA\";s:8:\"timezone\";s:15:\"Europe/Helsinki\";s:3:\"lat\";s:11:\"49.87098900\";s:3:\"lng\";s:11:\"24.02852400\";s:7:\"balance\";s:4:\"0.00\";s:11:\"news_update\";s:19:\"0000-00-00 00:00:00\";s:11:\"last_update\";s:19:\"0000-00-00 00:00:00\";s:5:\"karma\";s:1:\"3\";s:6:\"rating\";s:1:\"0\";s:10:\"GroupLimit\";a:4:{s:2:\"id\";s:1:\"2\";s:8:\"owner_id\";s:1:\"2\";s:12:\"members_used\";s:1:\"0\";s:13:\"members_limit\";s:1:\"0\";}s:9:\"UserMedia\";a:9:{s:2:\"id\";N;s:11:\"object_type\";N;s:9:\"object_id\";N;s:10:\"media_type\";N;s:3:\"ext\";s:0:\"\";s:10:\"orig_fsize\";N;s:10:\"orig_fname\";N;s:7:\"url_img\";s:17:\"/img/no-photo.jpg\";s:12:\"url_download\";s:0:\"\";}s:15:\"UniversityMedia\";a:9:{s:2:\"id\";N;s:11:\"object_type\";N;s:9:\"object_id\";N;s:10:\"media_type\";N;s:3:\"ext\";s:0:\"\";s:10:\"orig_fsize\";N;s:10:\"orig_fname\";N;s:7:\"url_img\";s:17:\"/img/no-photo.jpg\";s:12:\"url_download\";s:0:\"\";}}}Cloud|a:1:{s:4:\"sort\";N;}',1452272999),
	('17f4rru31bo3vgllqevv0t5tr2','Config|a:3:{s:9:\"userAgent\";s:32:\"bf5e323501b836aeb55513fb5a6ee76c\";s:4:\"time\";i:1452346443;s:9:\"countdown\";i:10;}Message|a:1:{s:4:\"auth\";a:3:{s:7:\"message\";s:35:\"You must log in to access this page\";s:7:\"element\";s:7:\"default\";s:6:\"params\";a:0:{}}}Auth|a:1:{s:8:\"redirect\";s:24:\"/js/settings?1447162443=\";}',1452346443),
	('1i7tq7q76ops277uh4r87rrvf0','Config|a:3:{s:9:\"userAgent\";s:32:\"bf5e323501b836aeb55513fb5a6ee76c\";s:4:\"time\";i:1452270057;s:9:\"countdown\";i:10;}Message|a:1:{s:4:\"auth\";a:3:{s:7:\"message\";s:35:\"You must log in to access this page\";s:7:\"element\";s:7:\"default\";s:6:\"params\";a:0:{}}}Auth|a:1:{s:8:\"redirect\";s:24:\"/js/settings?1447086057=\";}',1452270057),
	('2esqc1cmgd1h7d2u6rgevcv2h2','Config|a:3:{s:9:\"userAgent\";s:32:\"bf5e323501b836aeb55513fb5a6ee76c\";s:4:\"time\";i:1452346496;s:9:\"countdown\";i:10;}Message|a:1:{s:4:\"auth\";a:3:{s:7:\"message\";s:35:\"You must log in to access this page\";s:7:\"element\";s:7:\"default\";s:6:\"params\";a:0:{}}}Auth|a:1:{s:8:\"redirect\";s:24:\"/js/settings?1447162495=\";}',1452346496),
	('2gjeodavggh56uq657vf38fdq6','Config|a:3:{s:9:\"userAgent\";s:32:\"bf5e323501b836aeb55513fb5a6ee76c\";s:4:\"time\";i:1452346277;s:9:\"countdown\";i:10;}Message|a:1:{s:4:\"auth\";a:3:{s:7:\"message\";s:35:\"You must log in to access this page\";s:7:\"element\";s:7:\"default\";s:6:\"params\";a:0:{}}}Auth|a:1:{s:8:\"redirect\";s:24:\"/js/settings?1447162277=\";}',1452346277),
	('3gcmhb0occn71u5f61pfsgi2i7','Config|a:3:{s:9:\"userAgent\";s:32:\"bf5e323501b836aeb55513fb5a6ee76c\";s:4:\"time\";i:1452343653;s:9:\"countdown\";i:10;}Message|a:1:{s:4:\"auth\";a:3:{s:7:\"message\";s:35:\"You must log in to access this page\";s:7:\"element\";s:7:\"default\";s:6:\"params\";a:0:{}}}Auth|a:1:{s:8:\"redirect\";s:24:\"/js/settings?1447159653=\";}',1452343653),
	('4942mhobhrqo0p50s92svao7g4','Config|a:3:{s:9:\"userAgent\";s:32:\"bf5e323501b836aeb55513fb5a6ee76c\";s:4:\"time\";i:1452342319;s:9:\"countdown\";i:10;}Message|a:1:{s:4:\"auth\";a:3:{s:7:\"message\";s:35:\"You must log in to access this page\";s:7:\"element\";s:7:\"default\";s:6:\"params\";a:0:{}}}Auth|a:1:{s:8:\"redirect\";s:24:\"/js/settings?1447158318=\";}',1452342319),
	('51ndsqm54gaqv83n5a0132o213','Config|a:3:{s:9:\"userAgent\";s:32:\"bf5e323501b836aeb55513fb5a6ee76c\";s:4:\"time\";i:1452272037;s:9:\"countdown\";i:10;}Message|a:1:{s:4:\"auth\";a:3:{s:7:\"message\";s:35:\"You must log in to access this page\";s:7:\"element\";s:7:\"default\";s:6:\"params\";a:0:{}}}Auth|a:1:{s:8:\"redirect\";s:24:\"/js/settings?1447088036=\";}',1452272037),
	('53irqf8q37vhq8o71jr7jhnpj0','Config|a:3:{s:9:\"userAgent\";s:32:\"bf5e323501b836aeb55513fb5a6ee76c\";s:4:\"time\";i:1452342903;s:9:\"countdown\";i:10;}Message|a:1:{s:4:\"auth\";a:3:{s:7:\"message\";s:35:\"You must log in to access this page\";s:7:\"element\";s:7:\"default\";s:6:\"params\";a:0:{}}}Auth|a:1:{s:8:\"redirect\";s:24:\"/js/settings?1447158903=\";}',1452342903),
	('5b5gk70ko4rnfefd0qved69al5','Config|a:3:{s:9:\"userAgent\";s:32:\"bf5e323501b836aeb55513fb5a6ee76c\";s:4:\"time\";i:1452270053;s:9:\"countdown\";i:10;}Message|a:1:{s:4:\"auth\";a:3:{s:7:\"message\";s:35:\"You must log in to access this page\";s:7:\"element\";s:7:\"default\";s:6:\"params\";a:0:{}}}Auth|a:1:{s:8:\"redirect\";s:24:\"/js/settings?1447086053=\";}',1452270053),
	('6oh3m146j5as78r5ef7md5ch92','Config|a:3:{s:9:\"userAgent\";s:32:\"bf5e323501b836aeb55513fb5a6ee76c\";s:4:\"time\";i:1452346393;s:9:\"countdown\";i:10;}Message|a:1:{s:4:\"auth\";a:3:{s:7:\"message\";s:35:\"You must log in to access this page\";s:7:\"element\";s:7:\"default\";s:6:\"params\";a:0:{}}}Auth|a:1:{s:8:\"redirect\";s:24:\"/js/settings?1447162393=\";}',1452346393),
	('7upgpetundgc4500nh1l2rqmh3','Config|a:3:{s:9:\"userAgent\";s:32:\"bf5e323501b836aeb55513fb5a6ee76c\";s:4:\"time\";i:1452270197;s:9:\"countdown\";i:10;}Message|a:1:{s:4:\"auth\";a:3:{s:7:\"message\";s:35:\"You must log in to access this page\";s:7:\"element\";s:7:\"default\";s:6:\"params\";a:0:{}}}Auth|a:1:{s:8:\"redirect\";s:24:\"/js/settings?1447086197=\";}',1452270197),
	('81gsr41o2engq3ntou6fh6ll93','Config|a:3:{s:9:\"userAgent\";s:32:\"bf5e323501b836aeb55513fb5a6ee76c\";s:4:\"time\";i:1452343134;s:9:\"countdown\";i:10;}Message|a:1:{s:4:\"auth\";a:3:{s:7:\"message\";s:35:\"You must log in to access this page\";s:7:\"element\";s:7:\"default\";s:6:\"params\";a:0:{}}}Auth|a:1:{s:8:\"redirect\";s:24:\"/js/settings?1447159133=\";}',1452343134),
	('8us7ucgv8ajgjis8g1ovfb6um4','Config|a:3:{s:9:\"userAgent\";s:32:\"bf5e323501b836aeb55513fb5a6ee76c\";s:4:\"time\";i:1452270183;s:9:\"countdown\";i:10;}Message|a:1:{s:4:\"auth\";a:3:{s:7:\"message\";s:35:\"You must log in to access this page\";s:7:\"element\";s:7:\"default\";s:6:\"params\";a:0:{}}}Auth|a:1:{s:8:\"redirect\";s:24:\"/js/settings?1447086183=\";}',1452270184),
	('96qndtutfd9o3c58u83cajum96','Config|a:3:{s:9:\"userAgent\";s:32:\"cb5c03682c32cf96813e5182ae3b3731\";s:4:\"time\";i:1452345670;s:9:\"countdown\";i:10;}Message|a:0:{}',1452345670),
	('9pcla4p1uij9rc34s46s3246i1','Config|a:3:{s:9:\"userAgent\";s:32:\"bf5e323501b836aeb55513fb5a6ee76c\";s:4:\"time\";i:1452271227;s:9:\"countdown\";i:10;}Message|a:1:{s:4:\"auth\";a:3:{s:7:\"message\";s:35:\"You must log in to access this page\";s:7:\"element\";s:7:\"default\";s:6:\"params\";a:0:{}}}Auth|a:1:{s:8:\"redirect\";s:24:\"/js/settings?1447087226=\";}',1452271227),
	('a3v2bdf37hbumh6ko11r6qcrq2','Config|a:3:{s:9:\"userAgent\";s:32:\"bf5e323501b836aeb55513fb5a6ee76c\";s:4:\"time\";i:1452342307;s:9:\"countdown\";i:10;}Message|a:1:{s:4:\"auth\";a:3:{s:7:\"message\";s:35:\"You must log in to access this page\";s:7:\"element\";s:7:\"default\";s:6:\"params\";a:0:{}}}Auth|a:1:{s:8:\"redirect\";s:24:\"/js/settings?1447158307=\";}',1452342307),
	('a4cd8hnkivde4pl4j7rb6n72q5','Config|a:3:{s:9:\"userAgent\";s:32:\"bf5e323501b836aeb55513fb5a6ee76c\";s:4:\"time\";i:1452343230;s:9:\"countdown\";i:10;}Message|a:1:{s:4:\"auth\";a:3:{s:7:\"message\";s:35:\"You must log in to access this page\";s:7:\"element\";s:7:\"default\";s:6:\"params\";a:0:{}}}Auth|a:1:{s:8:\"redirect\";s:24:\"/js/settings?1447159230=\";}',1452343230),
	('avb1mhm5g9usm0hjkpsshokrm1','Config|a:3:{s:9:\"userAgent\";s:32:\"bf5e323501b836aeb55513fb5a6ee76c\";s:4:\"time\";i:1452343648;s:9:\"countdown\";i:10;}Message|a:1:{s:4:\"auth\";a:3:{s:7:\"message\";s:35:\"You must log in to access this page\";s:7:\"element\";s:7:\"default\";s:6:\"params\";a:0:{}}}Auth|a:1:{s:8:\"redirect\";s:24:\"/js/settings?1447159648=\";}',1452343648),
	('bjqm6m5lqflu10l1965i777dm2','Config|a:3:{s:9:\"userAgent\";s:32:\"bf5e323501b836aeb55513fb5a6ee76c\";s:4:\"time\";i:1452270020;s:9:\"countdown\";i:10;}Message|a:1:{s:4:\"auth\";a:3:{s:7:\"message\";s:35:\"You must log in to access this page\";s:7:\"element\";s:7:\"default\";s:6:\"params\";a:0:{}}}Auth|a:1:{s:8:\"redirect\";s:24:\"/js/settings?1447086019=\";}',1452270020),
	('c5s6b0cfdnc88m44rok3309ml5','Config|a:3:{s:9:\"userAgent\";s:32:\"bf5e323501b836aeb55513fb5a6ee76c\";s:4:\"time\";i:1452272188;s:9:\"countdown\";i:10;}Message|a:1:{s:4:\"auth\";a:3:{s:7:\"message\";s:35:\"You must log in to access this page\";s:7:\"element\";s:7:\"default\";s:6:\"params\";a:0:{}}}Auth|a:1:{s:8:\"redirect\";s:24:\"/js/settings?1447088188=\";}',1452272188),
	('c8aetcpg63i53hpk15ajmj3qa6','Config|a:3:{s:9:\"userAgent\";s:32:\"bf5e323501b836aeb55513fb5a6ee76c\";s:4:\"time\";i:1452343513;s:9:\"countdown\";i:10;}Message|a:1:{s:4:\"auth\";a:3:{s:7:\"message\";s:35:\"You must log in to access this page\";s:7:\"element\";s:7:\"default\";s:6:\"params\";a:0:{}}}Auth|a:1:{s:8:\"redirect\";s:24:\"/js/settings?1447159513=\";}',1452343513),
	('fajgbc7cvpl3j28uj967atj0s3','Config|a:3:{s:9:\"userAgent\";s:32:\"bf5e323501b836aeb55513fb5a6ee76c\";s:4:\"time\";i:1452343140;s:9:\"countdown\";i:10;}Message|a:1:{s:4:\"auth\";a:3:{s:7:\"message\";s:35:\"You must log in to access this page\";s:7:\"element\";s:7:\"default\";s:6:\"params\";a:0:{}}}Auth|a:1:{s:8:\"redirect\";s:24:\"/js/settings?1447159140=\";}',1452343140),
	('fms1ipj1j1r9fstnaoddd622k1','Config|a:3:{s:9:\"userAgent\";s:32:\"bf5e323501b836aeb55513fb5a6ee76c\";s:4:\"time\";i:1452342719;s:9:\"countdown\";i:10;}Message|a:1:{s:4:\"auth\";a:3:{s:7:\"message\";s:35:\"You must log in to access this page\";s:7:\"element\";s:7:\"default\";s:6:\"params\";a:0:{}}}Auth|a:1:{s:8:\"redirect\";s:24:\"/js/settings?1447158719=\";}',1452342719),
	('fqmo68h1elfvtt0uat73pc7tr3','Config|a:3:{s:9:\"userAgent\";s:32:\"bf5e323501b836aeb55513fb5a6ee76c\";s:4:\"time\";i:1452342888;s:9:\"countdown\";i:10;}Message|a:1:{s:4:\"auth\";a:3:{s:7:\"message\";s:35:\"You must log in to access this page\";s:7:\"element\";s:7:\"default\";s:6:\"params\";a:0:{}}}Auth|a:1:{s:8:\"redirect\";s:24:\"/js/settings?1447158888=\";}',1452342888),
	('hlsvdcbu0skfbvhc81h5qec631','Config|a:3:{s:9:\"userAgent\";s:32:\"bf5e323501b836aeb55513fb5a6ee76c\";s:4:\"time\";i:1452346455;s:9:\"countdown\";i:10;}Message|a:1:{s:4:\"auth\";a:3:{s:7:\"message\";s:35:\"You must log in to access this page\";s:7:\"element\";s:7:\"default\";s:6:\"params\";a:0:{}}}Auth|a:1:{s:8:\"redirect\";s:24:\"/js/settings?1447162455=\";}',1452346455),
	('hvi1l5f9kk7sri9b2683epvoh1','Config|a:3:{s:9:\"userAgent\";s:32:\"bf5e323501b836aeb55513fb5a6ee76c\";s:4:\"time\";i:1452342962;s:9:\"countdown\";i:10;}Message|a:1:{s:4:\"auth\";a:3:{s:7:\"message\";s:35:\"You must log in to access this page\";s:7:\"element\";s:7:\"default\";s:6:\"params\";a:0:{}}}Auth|a:1:{s:8:\"redirect\";s:24:\"/js/settings?1447158962=\";}',1452342962),
	('icinaee1k86kntsa5p4s75pbq2','Config|a:3:{s:9:\"userAgent\";s:32:\"bf5e323501b836aeb55513fb5a6ee76c\";s:4:\"time\";i:1452346336;s:9:\"countdown\";i:10;}Message|a:1:{s:4:\"auth\";a:3:{s:7:\"message\";s:35:\"You must log in to access this page\";s:7:\"element\";s:7:\"default\";s:6:\"params\";a:0:{}}}Auth|a:1:{s:8:\"redirect\";s:24:\"/js/settings?1447162335=\";}',1452346336),
	('ijrnn1jn0krf5orgk40c0ne3a5','Config|a:3:{s:9:\"userAgent\";s:32:\"bf5e323501b836aeb55513fb5a6ee76c\";s:4:\"time\";i:1452346437;s:9:\"countdown\";i:10;}Message|a:1:{s:4:\"auth\";a:3:{s:7:\"message\";s:35:\"You must log in to access this page\";s:7:\"element\";s:7:\"default\";s:6:\"params\";a:0:{}}}Auth|a:1:{s:8:\"redirect\";s:24:\"/js/settings?1447162437=\";}',1452346437),
	('irfu4igkfsg9seoumdpmjcr1a1','Config|a:3:{s:9:\"userAgent\";s:32:\"bf5e323501b836aeb55513fb5a6ee76c\";s:4:\"time\";i:1452342439;s:9:\"countdown\";i:10;}Message|a:1:{s:4:\"auth\";a:3:{s:7:\"message\";s:35:\"You must log in to access this page\";s:7:\"element\";s:7:\"default\";s:6:\"params\";a:0:{}}}Auth|a:1:{s:8:\"redirect\";s:24:\"/js/settings?1447158439=\";}',1452342439),
	('j46h820glg7u8bjnp9cuc9f7a3','Config|a:3:{s:9:\"userAgent\";s:32:\"bf5e323501b836aeb55513fb5a6ee76c\";s:4:\"time\";i:1452342556;s:9:\"countdown\";i:10;}Message|a:1:{s:4:\"auth\";a:3:{s:7:\"message\";s:35:\"You must log in to access this page\";s:7:\"element\";s:7:\"default\";s:6:\"params\";a:0:{}}}Auth|a:1:{s:8:\"redirect\";s:24:\"/js/settings?1447158555=\";}',1452342556),
	('j7m61pqftkri4d2kaij2dg7uh6','Config|a:3:{s:9:\"userAgent\";s:32:\"bf5e323501b836aeb55513fb5a6ee76c\";s:4:\"time\";i:1452270013;s:9:\"countdown\";i:10;}Message|a:1:{s:4:\"auth\";a:3:{s:7:\"message\";s:35:\"You must log in to access this page\";s:7:\"element\";s:7:\"default\";s:6:\"params\";a:0:{}}}Auth|a:1:{s:8:\"redirect\";s:24:\"/js/settings?1447086013=\";}',1452270013),
	('ljjb0qgj1op034desgkt5gm3m5','Config|a:3:{s:9:\"userAgent\";s:32:\"bf5e323501b836aeb55513fb5a6ee76c\";s:4:\"time\";i:1452343272;s:9:\"countdown\";i:10;}Message|a:1:{s:4:\"auth\";a:3:{s:7:\"message\";s:35:\"You must log in to access this page\";s:7:\"element\";s:7:\"default\";s:6:\"params\";a:0:{}}}Auth|a:1:{s:8:\"redirect\";s:24:\"/js/settings?1447159272=\";}',1452343272),
	('mc8vrjgcuqp55i26ord2i4cei4','Config|a:3:{s:9:\"userAgent\";s:32:\"d835d6ed195a04d210cd0a7e06e07250\";s:4:\"time\";i:1452347026;s:9:\"countdown\";i:10;}Auth|a:1:{s:4:\"User\";a:31:{s:2:\"id\";s:1:\"1\";s:4:\"fbid\";N;s:7:\"created\";s:19:\"2015-11-09 18:15:14\";s:8:\"modified\";s:19:\"2015-11-09 18:40:26\";s:8:\"is_admin\";s:1:\"0\";s:12:\"is_confirmed\";b:0;s:10:\"is_deleted\";b:0;s:8:\"username\";s:20:\"alexpers49@gmail.com\";s:8:\"password\";s:40:\"af6459e1b31b0f1af860cc73624a8b9113ae7360\";s:9:\"full_name\";s:9:\"Alex Pers\";s:11:\"profile_url\";N;s:9:\"video_url\";N;s:8:\"video_id\";N;s:6:\"skills\";s:33:\"IT specialist, Manager, Economist\";s:9:\"interests\";s:23:\"Astronomy, Finances, IT\";s:8:\"birthday\";N;s:4:\"lang\";s:3:\"eng\";s:5:\"phone\";N;s:10:\"live_place\";s:4:\"Kyiv\";s:12:\"live_address\";s:25:\"Khreschatyk Street, 20-22\";s:10:\"university\";N;s:10:\"speciality\";N;s:12:\"live_country\";s:2:\"UA\";s:8:\"timezone\";s:15:\"Europe/Helsinki\";s:3:\"lat\";s:11:\"50.45020900\";s:3:\"lng\";s:11:\"30.52253690\";s:7:\"balance\";s:4:\"0.00\";s:11:\"news_update\";s:19:\"0000-00-00 00:00:00\";s:11:\"last_update\";s:19:\"2015-11-09 18:40:26\";s:5:\"karma\";s:1:\"3\";s:6:\"rating\";s:1:\"0\";}}',1452347027),
	('mcckj2d03vqct0bvs9a8a0fi57','Config|a:3:{s:9:\"userAgent\";s:32:\"bf5e323501b836aeb55513fb5a6ee76c\";s:4:\"time\";i:1452342769;s:9:\"countdown\";i:10;}Message|a:1:{s:4:\"auth\";a:3:{s:7:\"message\";s:35:\"You must log in to access this page\";s:7:\"element\";s:7:\"default\";s:6:\"params\";a:0:{}}}Auth|a:1:{s:8:\"redirect\";s:24:\"/js/settings?1447158769=\";}',1452342769),
	('mg4ktch1e6c8lo5h01ger67v51','Config|a:3:{s:9:\"userAgent\";s:32:\"bf5e323501b836aeb55513fb5a6ee76c\";s:4:\"time\";i:1452342182;s:9:\"countdown\";i:10;}Message|a:1:{s:4:\"auth\";a:3:{s:7:\"message\";s:35:\"You must log in to access this page\";s:7:\"element\";s:7:\"default\";s:6:\"params\";a:0:{}}}Auth|a:1:{s:8:\"redirect\";s:24:\"/js/settings?1447158181=\";}',1452342182),
	('mveq9kddjvsjbnap1piq30j0m0','Config|a:3:{s:9:\"userAgent\";s:32:\"bf5e323501b836aeb55513fb5a6ee76c\";s:4:\"time\";i:1452346474;s:9:\"countdown\";i:10;}Message|a:1:{s:4:\"auth\";a:3:{s:7:\"message\";s:35:\"You must log in to access this page\";s:7:\"element\";s:7:\"default\";s:6:\"params\";a:0:{}}}Auth|a:1:{s:8:\"redirect\";s:24:\"/js/settings?1447162474=\";}',1452346474),
	('njcq7eahufd2ap4rg2kvjfg1t4','Config|a:3:{s:9:\"userAgent\";s:32:\"bf5e323501b836aeb55513fb5a6ee76c\";s:4:\"time\";i:1452346383;s:9:\"countdown\";i:10;}Message|a:1:{s:4:\"auth\";a:3:{s:7:\"message\";s:35:\"You must log in to access this page\";s:7:\"element\";s:7:\"default\";s:6:\"params\";a:0:{}}}Auth|a:1:{s:8:\"redirect\";s:24:\"/js/settings?1447162382=\";}',1452346383),
	('o8ioc0djpi9dt8v76q1lohp872','Config|a:3:{s:9:\"userAgent\";s:32:\"bf5e323501b836aeb55513fb5a6ee76c\";s:4:\"time\";i:1452269994;s:9:\"countdown\";i:10;}Message|a:1:{s:4:\"auth\";a:3:{s:7:\"message\";s:35:\"You must log in to access this page\";s:7:\"element\";s:7:\"default\";s:6:\"params\";a:0:{}}}Auth|a:1:{s:8:\"redirect\";s:24:\"/js/settings?1447085994=\";}',1452269994),
	('qeglrp99ofqioqoie2bp6neg85','Config|a:3:{s:9:\"userAgent\";s:32:\"41439bf1f0a8a820ca7478bfb42cff7e\";s:4:\"time\";i:1452346572;s:9:\"countdown\";i:10;}Auth|a:1:{s:4:\"User\";a:33:{s:2:\"id\";s:2:\"13\";s:4:\"fbid\";N;s:7:\"created\";s:19:\"2015-11-10 13:30:36\";s:8:\"modified\";s:19:\"2015-11-10 13:30:36\";s:8:\"is_admin\";s:1:\"0\";s:12:\"is_confirmed\";b:0;s:10:\"is_deleted\";b:0;s:8:\"username\";s:18:\"begemokot@lenta.ru\";s:9:\"full_name\";s:11:\"Kot Begemot\";s:11:\"profile_url\";N;s:9:\"video_url\";N;s:8:\"video_id\";N;s:6:\"skills\";N;s:9:\"interests\";N;s:8:\"birthday\";N;s:4:\"lang\";s:3:\"rus\";s:5:\"phone\";N;s:10:\"live_place\";s:4:\"Kiev\";s:12:\"live_address\";N;s:10:\"university\";N;s:10:\"speciality\";N;s:12:\"live_country\";s:2:\"UA\";s:8:\"timezone\";s:15:\"Europe/Helsinki\";s:3:\"lat\";s:11:\"50.43330000\";s:3:\"lng\";s:11:\"30.51670000\";s:7:\"balance\";s:4:\"0.00\";s:11:\"news_update\";s:19:\"0000-00-00 00:00:00\";s:11:\"last_update\";s:19:\"0000-00-00 00:00:00\";s:5:\"karma\";s:1:\"3\";s:6:\"rating\";s:1:\"0\";s:10:\"GroupLimit\";a:4:{s:2:\"id\";s:2:\"13\";s:8:\"owner_id\";s:2:\"13\";s:12:\"members_used\";s:1:\"0\";s:13:\"members_limit\";s:1:\"0\";}s:9:\"UserMedia\";a:9:{s:2:\"id\";N;s:11:\"object_type\";N;s:9:\"object_id\";N;s:10:\"media_type\";N;s:3:\"ext\";s:0:\"\";s:10:\"orig_fsize\";N;s:10:\"orig_fname\";N;s:7:\"url_img\";s:17:\"/img/no-photo.jpg\";s:12:\"url_download\";s:0:\"\";}s:15:\"UniversityMedia\";a:9:{s:2:\"id\";N;s:11:\"object_type\";N;s:9:\"object_id\";N;s:10:\"media_type\";N;s:3:\"ext\";s:0:\"\";s:10:\"orig_fsize\";N;s:10:\"orig_fname\";N;s:7:\"url_img\";s:17:\"/img/no-photo.jpg\";s:12:\"url_download\";s:0:\"\";}}}',1452346573),
	('rldcqao6labe3ime7691vtolq3','Config|a:3:{s:9:\"userAgent\";s:32:\"bf5e323501b836aeb55513fb5a6ee76c\";s:4:\"time\";i:1452342470;s:9:\"countdown\";i:10;}Message|a:1:{s:4:\"auth\";a:3:{s:7:\"message\";s:35:\"You must log in to access this page\";s:7:\"element\";s:7:\"default\";s:6:\"params\";a:0:{}}}Auth|a:1:{s:8:\"redirect\";s:24:\"/js/settings?1447158470=\";}',1452342470),
	('sauo092rrm5dgjhsl8g5frf6s4','Config|a:3:{s:9:\"userAgent\";s:32:\"bf5e323501b836aeb55513fb5a6ee76c\";s:4:\"time\";i:1452342597;s:9:\"countdown\";i:10;}Message|a:1:{s:4:\"auth\";a:3:{s:7:\"message\";s:35:\"You must log in to access this page\";s:7:\"element\";s:7:\"default\";s:6:\"params\";a:0:{}}}Auth|a:1:{s:8:\"redirect\";s:24:\"/js/settings?1447158597=\";}',1452342597),
	('u39rcbffljoru5b9jg6nfsso52','Config|a:3:{s:9:\"userAgent\";s:32:\"bf5e323501b836aeb55513fb5a6ee76c\";s:4:\"time\";i:1452343523;s:9:\"countdown\";i:10;}Message|a:1:{s:4:\"auth\";a:3:{s:7:\"message\";s:35:\"You must log in to access this page\";s:7:\"element\";s:7:\"default\";s:6:\"params\";a:0:{}}}Auth|a:1:{s:8:\"redirect\";s:24:\"/js/settings?1447159523=\";}',1452343523),
	('u8u609tro52sq018aplih0qsb6','Config|a:3:{s:9:\"userAgent\";s:32:\"bf5e323501b836aeb55513fb5a6ee76c\";s:4:\"time\";i:1452269770;s:9:\"countdown\";i:10;}Message|a:1:{s:4:\"auth\";a:3:{s:7:\"message\";s:35:\"You must log in to access this page\";s:7:\"element\";s:7:\"default\";s:6:\"params\";a:0:{}}}Auth|a:1:{s:8:\"redirect\";s:24:\"/js/settings?1447085769=\";}',1452269770),
	('vbu40rm9kb6o140p58cqcuagb1','Config|a:3:{s:9:\"userAgent\";s:32:\"bf5e323501b836aeb55513fb5a6ee76c\";s:4:\"time\";i:1452269756;s:9:\"countdown\";i:10;}Message|a:1:{s:4:\"auth\";a:3:{s:7:\"message\";s:35:\"You must log in to access this page\";s:7:\"element\";s:7:\"default\";s:6:\"params\";a:0:{}}}Auth|a:1:{s:8:\"redirect\";s:24:\"/js/settings?1447085756=\";}',1452269756),
	('vdf86di73mn8k4m63rpngrvsk7','Config|a:3:{s:9:\"userAgent\";s:32:\"bf5e323501b836aeb55513fb5a6ee76c\";s:4:\"time\";i:1452343033;s:9:\"countdown\";i:10;}Message|a:1:{s:4:\"auth\";a:3:{s:7:\"message\";s:35:\"You must log in to access this page\";s:7:\"element\";s:7:\"default\";s:6:\"params\";a:0:{}}}Auth|a:1:{s:8:\"redirect\";s:24:\"/js/settings?1447159032=\";}',1452343033),
	('vmngaa8ge3t34l4cdbbbtogo16','Config|a:3:{s:9:\"userAgent\";s:32:\"bf5e323501b836aeb55513fb5a6ee76c\";s:4:\"time\";i:1452346372;s:9:\"countdown\";i:10;}Message|a:1:{s:4:\"auth\";a:3:{s:7:\"message\";s:35:\"You must log in to access this page\";s:7:\"element\";s:7:\"default\";s:6:\"params\";a:0:{}}}Auth|a:1:{s:8:\"redirect\";s:24:\"/js/settings?1447162371=\";}',1452346372),
	('vv43lu71gtnovnupdbm5ogqbb4','Config|a:3:{s:9:\"userAgent\";s:32:\"bf5e323501b836aeb55513fb5a6ee76c\";s:4:\"time\";i:1452272053;s:9:\"countdown\";i:10;}Message|a:1:{s:4:\"auth\";a:3:{s:7:\"message\";s:35:\"You must log in to access this page\";s:7:\"element\";s:7:\"default\";s:6:\"params\";a:0:{}}}Auth|a:1:{s:8:\"redirect\";s:24:\"/js/settings?1447088052=\";}',1452272053);

/*!40000 ALTER TABLE `app_sessions` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table article_events
# ------------------------------------------------------------

DROP TABLE IF EXISTS `article_events`;

CREATE TABLE `article_events` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `article_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `msg_id` bigint(20) NOT NULL,
  `parent_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table articles
# ------------------------------------------------------------

DROP TABLE IF EXISTS `articles`;

CREATE TABLE `articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `owner_id` int(11) NOT NULL,
  `group_id` int(11) DEFAULT NULL,
  `title` varchar(1023) DEFAULT NULL,
  `body` text,
  `video_url` varchar(1023) DEFAULT NULL,
  `type` varchar(10) NOT NULL DEFAULT 'text',
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `cat_id` int(11) unsigned NOT NULL,
  `deleted` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `cat_id` (`cat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `articles` WRITE;
/*!40000 ALTER TABLE `articles` DISABLE KEYS */;

INSERT INTO `articles` (`id`, `owner_id`, `group_id`, `title`, `body`, `video_url`, `type`, `published`, `created`, `modified`, `cat_id`, `deleted`)
VALUES
	(1,2,NULL,'Lorem ipsum dolor sit amet, ad nisl magna oportere ius, cu usu.','<p>Lorem ipsum dolor sit amet, cum in latine pericula, qui ad molestiae elaboraret. Partem recteque in sit, habeo doming probatus in eos. At nemore omnium corrumpit sed, per ut agam laudem, vim essent persequeris no. Vis an quas molestiae, audire quaestio pri no, ne cum eros facilis. In nec aperiam tincidunt.</p><p>Detracto nominati duo ne. Id duo nominati adolescens, vidisse forensibus nec ut. Ei mundi tacimates ius, congue fabellas reformidans pro ne, ad usu munere consetetur. Vide habeo ceteros nam id. Et eam ridens vidisse deseruisse, aperiri tamquam impedit pro in. Porro indoctum quo cu, ex qui rebum consectetuer, cu duo decore definitionem interpretaris. Per ex lorem singulis euripidis, alterum pericula ei mea.</p><p>Ei partem graeco repudiare vel, ad mea inani detraxit, eu malorum deleniti vel. Case definiebas vel te, mei zril iuvaret in, erat iuvaret fabulas ei mea. Qui ad debitis percipitur. Mei no eros audiam sanctus, ex nec quaeque deterruisset. No his brute dolores.</p><p>Ne cibo euismod antiopam duo. Facilisi consectetuer vix ut. Ea dicunt admodum cotidieque vix, usu apeirian maluisset ad, fugit libris omnesque no est. Habeo movet deterruisset at his, sea noster apeirian ad.</p><p>Aliquam dissentiunt eu eam. Vel ad erant veritus, mei te nibh idque harum. Semper albucius scaevola mea ad, posse alterum mea ea. Sea lorem facete eu. Per assum docendi abhorreant no, eam ad graecis accommodare, ei eos exerci iriure facilis.</p>',NULL,'text',1,'2015-11-09 16:20:57','2015-11-09 16:23:03',8,0);

/*!40000 ALTER TABLE `articles` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table balance_operations
# ------------------------------------------------------------

DROP TABLE IF EXISTS `balance_operations`;

CREATE TABLE `balance_operations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `oper_type` int(1) DEFAULT NULL,
  `sum` float NOT NULL,
  `descr` text,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `balance_operations_user_id_idx` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table billing_customers
# ------------------------------------------------------------

DROP TABLE IF EXISTS `billing_customers`;

CREATE TABLE `billing_customers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `customer_id` varchar(255) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `billing_customers_user_id_customer_id_idx` (`user_id`,`customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table billing_groups
# ------------------------------------------------------------

DROP TABLE IF EXISTS `billing_groups`;

CREATE TABLE `billing_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `limit_units` varchar(60) DEFAULT NULL,
  `active` tinyint(1) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `billing_groups` WRITE;
/*!40000 ALTER TABLE `billing_groups` DISABLE KEYS */;

INSERT INTO `billing_groups` (`id`, `title`, `slug`, `limit_units`, `active`, `created`, `modified`)
VALUES
	(1,'Disc space','disc-space','bytes',1,'2015-07-31 12:38:14','2015-08-16 17:53:35'),
	(2,'Members','members','members',1,'2015-08-16 10:41:55','2015-08-16 17:53:43');

/*!40000 ALTER TABLE `billing_groups` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table billing_plans
# ------------------------------------------------------------

DROP TABLE IF EXISTS `billing_plans`;

CREATE TABLE `billing_plans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text,
  `limit_value` bigint(20) DEFAULT NULL,
  `remote_plans` text,
  `free` tinyint(1) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `billing_plans_group_id_idx` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `billing_plans` WRITE;
/*!40000 ALTER TABLE `billing_plans` DISABLE KEYS */;

INSERT INTO `billing_plans` (`id`, `group_id`, `title`, `slug`, `description`, `limit_value`, `remote_plans`, `free`, `created`, `modified`)
VALUES
	(1,1,'2 Gb','2-gb','',2147483648,'',1,'2015-07-30 13:21:45','2015-08-16 18:28:06'),
	(2,1,'10 Gb','10-gb','',10737418240,'[\"disk-10-monthly\",\"disk-10-yearly\"]',0,'2015-07-31 13:04:39','2015-08-16 18:05:36'),
	(3,1,'100 Gb','100-gb','',107374182400,'[\"disk-100-monthly\",\"disk-100-yearly\"]',0,'2015-07-31 13:05:05','2015-08-16 18:06:08'),
	(4,1,'1 Tb','1-tb','',1099511627776,'[\"disk-1000-monthly\",\"disk-1000-yearly\"]',0,'2015-07-31 13:05:53','2015-08-16 18:10:28'),
	(5,2,'Members','members','',0,'[\"members-monthly\",\"members-yearly\"]',0,'2015-08-16 10:58:03','2015-08-16 10:59:45');

/*!40000 ALTER TABLE `billing_plans` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table billing_subscriptions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `billing_subscriptions`;

CREATE TABLE `billing_subscriptions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `remote_subscription_id` varchar(255) DEFAULT NULL,
  `remote_plan_id` varchar(255) DEFAULT NULL,
  `limit_value` bigint(20) NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `status` varchar(60) DEFAULT NULL,
  `expires` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `billing_subscriptions_plan_id_idx` (`plan_id`),
  KEY `billing_subscriptions_group_id_idx` (`group_id`),
  KEY `billing_subscriptions_user_id_idx` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table chat_contacts
# ------------------------------------------------------------

DROP TABLE IF EXISTS `chat_contacts`;

CREATE TABLE `chat_contacts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NULL DEFAULT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `initiator_id` int(11) unsigned NOT NULL,
  `active_count` int(11) unsigned NOT NULL DEFAULT '0',
  `msg` text,
  `room_id` int(11) unsigned NOT NULL,
  `chat_event_id` int(11) unsigned NOT NULL,
  `group_id` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id_room_id` (`user_id`,`room_id`),
  KEY `user_id_modified` (`user_id`,`modified`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table chat_events
# ------------------------------------------------------------

DROP TABLE IF EXISTS `chat_events`;

CREATE TABLE `chat_events` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `active` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `room_id` bigint(20) unsigned NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `event_type` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `initiator_id` int(11) unsigned NOT NULL,
  `recipient_id` int(11) unsigned DEFAULT NULL,
  `msg_id` bigint(20) unsigned DEFAULT NULL,
  `file_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_events` (`user_id`,`active`,`room_id`,`created`),
  KEY `created` (`created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table chat_members
# ------------------------------------------------------------

DROP TABLE IF EXISTS `chat_members`;

CREATE TABLE `chat_members` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `room_id` bigint(20) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `is_deleted` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `room_id` (`room_id`,`user_id`),
  KEY `user_id` (`user_id`,`room_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table chat_messages
# ------------------------------------------------------------

DROP TABLE IF EXISTS `chat_messages`;

CREATE TABLE `chat_messages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `message` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table chat_rooms
# ------------------------------------------------------------

DROP TABLE IF EXISTS `chat_rooms`;

CREATE TABLE `chat_rooms` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `initiator_id` int(11) unsigned NOT NULL,
  `recipient_id` int(11) unsigned NOT NULL,
  `group_id` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `initiator_id` (`initiator_id`,`recipient_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table cloud
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cloud`;

CREATE TABLE `cloud` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `created` timestamp NULL DEFAULT NULL,
  `modified` timestamp NULL DEFAULT NULL,
  `user_id` int(11) unsigned DEFAULT NULL,
  `parent_id` int(11) unsigned DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `media_id` int(11) unsigned NOT NULL,
  `lft` int(11) DEFAULT NULL,
  `rght` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `parent_id` (`parent_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table contractors
# ------------------------------------------------------------

DROP TABLE IF EXISTS `contractors`;

CREATE TABLE `contractors` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `title` varchar(511) NOT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `details` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table countries
# ------------------------------------------------------------

DROP TABLE IF EXISTS `countries`;

CREATE TABLE `countries` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `country_code` char(2) NOT NULL DEFAULT '',
  `country_name` varchar(45) NOT NULL DEFAULT '',
  `show_main` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `countries` WRITE;
/*!40000 ALTER TABLE `countries` DISABLE KEYS */;

INSERT INTO `countries` (`id`, `country_code`, `country_name`, `show_main`)
VALUES
	(1,'AD','Andorra',0),
	(2,'AE','United Arab Emirates',1),
	(3,'AF','Afghanistan',0),
	(4,'AG','Antigua and Barbuda',0),
	(5,'AI','Anguilla',0),
	(6,'AL','Albania',0),
	(7,'AM','Armenia',0),
	(8,'AO','Angola',0),
	(9,'AQ','Antarctica',0),
	(10,'AR','Argentina',1),
	(11,'AS','American Samoa',0),
	(12,'AT','Austria',1),
	(13,'AU','Australia',1),
	(14,'AW','Aruba',0),
	(16,'AZ','Azerbaijan',0),
	(17,'BA','Bosnia and Herzegovina',0),
	(18,'BB','Barbados',0),
	(19,'BD','Bangladesh',0),
	(20,'BE','Belgium',1),
	(21,'BF','Burkina Faso',0),
	(22,'BG','Bulgaria',1),
	(23,'BH','Bahrain',0),
	(24,'BI','Burundi',0),
	(25,'BJ','Benin',0),
	(26,'BL','Saint Barthélemy',0),
	(27,'BM','Bermuda',0),
	(28,'BN','Brunei',0),
	(29,'BO','Bolivia',0),
	(30,'BQ','Bonaire',0),
	(31,'BR','Brazil',1),
	(32,'BS','Bahamas',0),
	(33,'BT','Bhutan',0),
	(34,'BV','Bouvet Island',0),
	(35,'BW','Botswana',0),
	(36,'BY','Belarus',1),
	(37,'BZ','Belize',0),
	(38,'CA','Canada',1),
	(39,'CC','Cocos [Keeling] Islands',0),
	(40,'CD','Democratic Republic of the Congo',0),
	(41,'CF','Central African Republic',0),
	(42,'CG','Republic of the Congo',0),
	(43,'CH','Switzerland',1),
	(44,'CI','Ivory Coast',0),
	(45,'CK','Cook Islands',0),
	(46,'CL','Chile',1),
	(47,'CM','Cameroon',0),
	(48,'CN','China',0),
	(49,'CO','Colombia',0),
	(50,'CR','Costa Rica',0),
	(51,'CU','Cuba',0),
	(52,'CV','Cape Verde',0),
	(53,'CW','Curacao',0),
	(54,'CX','Christmas Island',0),
	(55,'CY','Cyprus',0),
	(56,'CZ','Czech Republic',0),
	(57,'DE','Germany',1),
	(58,'DJ','Djibouti',0),
	(59,'DK','Denmark',1),
	(60,'DM','Dominica',0),
	(61,'DO','Dominican Republic',0),
	(62,'DZ','Algeria',0),
	(63,'EC','Ecuador',0),
	(64,'EE','Estonia',0),
	(65,'EG','Egypt',0),
	(66,'EH','Western Sahara',0),
	(67,'ER','Eritrea',0),
	(68,'ES','Spain',1),
	(69,'ET','Ethiopia',0),
	(70,'FI','Finland',0),
	(71,'FJ','Fiji',0),
	(72,'FK','Falkland Islands',0),
	(73,'FM','Micronesia',0),
	(74,'FO','Faroe Islands',0),
	(75,'FR','France',1),
	(76,'GA','Gabon',0),
	(77,'UK','United Kingdom',1),
	(78,'GD','Grenada',0),
	(79,'GE','Georgia',0),
	(80,'GF','French Guiana',0),
	(81,'GG','Guernsey',0),
	(82,'GH','Ghana',0),
	(83,'GI','Gibraltar',0),
	(84,'GL','Greenland',0),
	(85,'GM','Gambia',0),
	(86,'GN','Guinea',0),
	(87,'GP','Guadeloupe',0),
	(88,'GQ','Equatorial Guinea',0),
	(89,'GR','Greece',1),
	(91,'GT','Guatemala',0),
	(92,'GU','Guam',0),
	(93,'GW','Guinea-Bissau',0),
	(94,'GY','Guyana',0),
	(95,'HK','Hong Kong',0),
	(96,'HM','Heard Island and McDonald Islands',0),
	(97,'HN','Honduras',0),
	(98,'HR','Croatia',0),
	(99,'HT','Haiti',0),
	(100,'HU','Hungary',1),
	(101,'ID','Indonesia',0),
	(102,'IE','Ireland',1),
	(103,'IL','Israel',1),
	(104,'IM','Isle of Man',0),
	(105,'IN','India',0),
	(106,'IO','British Indian Ocean Territory',0),
	(107,'IQ','Iraq',0),
	(108,'IR','Iran',0),
	(109,'IS','Iceland',1),
	(110,'IT','Italy',1),
	(111,'JE','Jersey',0),
	(112,'JM','Jamaica',0),
	(113,'JO','Jordan',0),
	(114,'JP','Japan',1),
	(115,'KE','Kenya',0),
	(116,'KG','Kyrgyzstan',0),
	(117,'KH','Cambodia',0),
	(118,'KI','Kiribati',0),
	(119,'KM','Comoros',0),
	(120,'KN','Saint Kitts and Nevis',0),
	(121,'KP','North Korea',0),
	(122,'KR','South Korea',1),
	(123,'KW','Kuwait',0),
	(124,'KY','Cayman Islands',0),
	(125,'KZ','Kazakhstan',0),
	(126,'LA','Laos',0),
	(127,'LB','Lebanon',0),
	(128,'LC','Saint Lucia',0),
	(129,'LI','Liechtenstein',0),
	(130,'LK','Sri Lanka',0),
	(131,'LR','Liberia',0),
	(132,'LS','Lesotho',0),
	(133,'LT','Lithuania',0),
	(134,'LU','Luxembourg',0),
	(135,'LV','Latvia',0),
	(136,'LY','Libya',0),
	(137,'MA','Morocco',0),
	(138,'MC','Monaco',0),
	(139,'MD','Moldova',0),
	(140,'ME','Montenegro',0),
	(141,'MF','Saint Martin',0),
	(142,'MG','Madagascar',0),
	(143,'MH','Marshall Islands',0),
	(144,'MK','Macedonia',0),
	(145,'ML','Mali',0),
	(146,'MM','Myanmar [Burma]',0),
	(147,'MN','Mongolia',0),
	(148,'MO','Macao',0),
	(149,'MP','Northern Mariana Islands',0),
	(150,'MQ','Martinique',0),
	(151,'MR','Mauritania',0),
	(152,'MS','Montserrat',0),
	(153,'MT','Malta',0),
	(154,'MU','Mauritius',0),
	(155,'MV','Maldives',0),
	(156,'MW','Malawi',0),
	(157,'MX','Mexico',1),
	(158,'MY','Malaysia',0),
	(159,'MZ','Mozambique',0),
	(160,'NA','Namibia',0),
	(161,'NC','New Caledonia',0),
	(162,'NE','Niger',0),
	(163,'NF','Norfolk Island',0),
	(164,'NG','Nigeria',0),
	(165,'NI','Nicaragua',0),
	(166,'NL','Netherlands',1),
	(167,'NO','Norway',1),
	(168,'NP','Nepal',0),
	(169,'NR','Nauru',0),
	(170,'NU','Niue',0),
	(171,'NZ','New Zealand',1),
	(172,'OM','Oman',0),
	(173,'PA','Panama',0),
	(174,'PE','Peru',0),
	(175,'PF','French Polynesia',0),
	(176,'PG','Papua New Guinea',0),
	(177,'PH','Philippines',0),
	(178,'PK','Pakistan',0),
	(179,'PL','Poland',1),
	(180,'PM','Saint Pierre and Miquelon',0),
	(181,'PN','Pitcairn Islands',0),
	(182,'PR','Puerto Rico',0),
	(183,'PS','Palestine',0),
	(184,'PT','Portugal',1),
	(185,'PW','Palau',0),
	(186,'PY','Paraguay',0),
	(187,'QA','Qatar',0),
	(188,'RE','Réunion',0),
	(189,'RO','Romania',0),
	(190,'RS','Serbia',0),
	(191,'RU','Russia',1),
	(192,'RW','Rwanda',0),
	(193,'SA','Saudi Arabia',0),
	(194,'SB','Solomon Islands',0),
	(195,'SC','Seychelles',0),
	(196,'SD','Sudan',0),
	(197,'SE','Sweden',1),
	(198,'SG','Singapore',0),
	(199,'SH','Saint Helena',0),
	(200,'SI','Slovenia',0),
	(201,'SJ','Svalbard and Jan Mayen',0),
	(202,'SK','Slovakia',1),
	(203,'SL','Sierra Leone',0),
	(204,'SM','San Marino',0),
	(205,'SN','Senegal',0),
	(206,'SO','Somalia',0),
	(207,'SR','Suriname',0),
	(208,'SS','South Sudan',0),
	(209,'ST','São Tomé and Príncipe',0),
	(210,'SV','El Salvador',0),
	(211,'SX','Sint Maarten',0),
	(212,'SY','Syria',0),
	(213,'SZ','Swaziland',0),
	(214,'TC','Turks and Caicos Islands',0),
	(215,'TD','Chad',0),
	(216,'TF','French Southern Territories',0),
	(217,'TG','Togo',0),
	(218,'TH','Thailand',0),
	(219,'TJ','Tajikistan',0),
	(220,'TK','Tokelau',0),
	(221,'TL','East Timor',0),
	(222,'TM','Turkmenistan',0),
	(223,'TN','Tunisia',0),
	(224,'TO','Tonga',0),
	(225,'TR','Turkey',1),
	(226,'TT','Trinidad and Tobago',0),
	(227,'TV','Tuvalu',0),
	(228,'TW','Taiwan',0),
	(229,'TZ','Tanzania',0),
	(230,'UA','Ukraine',1),
	(231,'UG','Uganda',0),
	(232,'UM','U.S. Minor Outlying Islands',0),
	(233,'US','United States',1),
	(234,'UY','Uruguay',0),
	(235,'UZ','Uzbekistan',0),
	(236,'VA','Vatican City',0),
	(237,'VC','Saint Vincent and the Grenadines',0),
	(238,'VE','Venezuela',0),
	(239,'VG','British Virgin Islands',0),
	(240,'VI','U.S. Virgin Islands',0),
	(241,'VN','Vietnam',0),
	(242,'VU','Vanuatu',0),
	(243,'WF','Wallis and Futuna',0),
	(244,'WS','Samoa',0),
	(245,'XK','Kosovo',0),
	(246,'YE','Yemen',0),
	(247,'YT','Mayotte',0),
	(248,'ZA','South Africa',1),
	(249,'ZM','Zambia',0),
	(250,'ZW','Zimbabwe',0);

/*!40000 ALTER TABLE `countries` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table crm_tasks
# ------------------------------------------------------------

DROP TABLE IF EXISTS `crm_tasks`;

CREATE TABLE `crm_tasks` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `task_id` int(11) unsigned NOT NULL,
  `contractor_id` int(11) unsigned DEFAULT NULL,
  `money` decimal(15,2) NOT NULL DEFAULT '0.00',
  `currency` varchar(4) NOT NULL,
  `account_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table document_versions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `document_versions`;

CREATE TABLE `document_versions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `doc_id` int(11) NOT NULL,
  `body` text,
  `title` varchar(2048) NOT NULL,
  `modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table faqs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `faqs`;

CREATE TABLE `faqs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `question` varchar(1023) NOT NULL,
  `answer` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `faqs` WRITE;
/*!40000 ALTER TABLE `faqs` DISABLE KEYS */;

INSERT INTO `faqs` (`id`, `question`, `answer`)
VALUES
	(1,'DataSources are the link between models and the source of data that models represent. In many cases, the data is retrieved from a relational database such as MySQL, PostgreSQL or MSSQL. What is the DataSource?','<p><strong>DataSources</strong> are the link between models and the source of data that models represent. In many cases, the data is retrieved from a relational database such as MySQL, PostgreSQL or MSSQL.</p>\r\n\r\n<p>CakePHP is distributed with several database-specific datasources (see the class files in lib/Cake/Model/Datasource/Database), a summary of which is listed here for your convenience</p>\r\n'),
	(2,'When specifying a database connection configuration?','<p>When specifying a database connection configuration in app/Config/database.php, CakePHP transparently uses the corresponding database datasource for all model operations. So, even though you might not have known about datasources, you&rsquo;ve been using them all along.</p>\r\n\r\n<p>All of the above sources derive from a base DboSource class, which aggregates some logic that is common to most relational databases. If you decide to write a RDBMS datasource, working from one of these (e.g. Mysql, or Sqlite is your best bet.)</p>\r\n\r\n<p>Most people, however, are interested in writing datasources for external sources of data, such as remote REST APIs or even an LDAP server. So that&rsquo;s what we&rsquo;re going to look at now.</p>\r\n');

/*!40000 ALTER TABLE `faqs` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table favourite_lists
# ------------------------------------------------------------

DROP TABLE IF EXISTS `favourite_lists`;

CREATE TABLE `favourite_lists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(1023) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table favourite_users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `favourite_users`;

CREATE TABLE `favourite_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `fav_user_id` int(11) NOT NULL,
  `favourite_list_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table finance_account
# ------------------------------------------------------------

DROP TABLE IF EXISTS `finance_account`;

CREATE TABLE `finance_account` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int(11) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `type` varchar(20) NOT NULL,
  `currency` varchar(4) NOT NULL,
  PRIMARY KEY (`id`,`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table finance_budget
# ------------------------------------------------------------

DROP TABLE IF EXISTS `finance_budget`;

CREATE TABLE `finance_budget` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int(11) unsigned NOT NULL,
  `category_id` int(11) unsigned NOT NULL,
  `account_id` int(11) unsigned NOT NULL,
  `plan` decimal(15,2) NOT NULL,
  `is_repeat` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table finance_calendar
# ------------------------------------------------------------

DROP TABLE IF EXISTS `finance_calendar`;

CREATE TABLE `finance_calendar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table finance_category
# ------------------------------------------------------------

DROP TABLE IF EXISTS `finance_category`;

CREATE TABLE `finance_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int(11) unsigned NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`,`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `finance_category` WRITE;
/*!40000 ALTER TABLE `finance_category` DISABLE KEYS */;

INSERT INTO `finance_category` (`id`, `project_id`, `name`, `type`)
VALUES
	(1,14,'Тест доход',0),
	(2,14,'Тест расход',1),
	(3,14,'Тест налог',1),
	(4,14,'Тест процент',1);

/*!40000 ALTER TABLE `finance_category` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table finance_goals
# ------------------------------------------------------------

DROP TABLE IF EXISTS `finance_goals`;

CREATE TABLE `finance_goals` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int(11) unsigned NOT NULL,
  `account_id` int(11) unsigned NOT NULL,
  `name` varchar(100) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `finish` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `final_sum` decimal(15,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table finance_operation
# ------------------------------------------------------------

DROP TABLE IF EXISTS `finance_operation`;

CREATE TABLE `finance_operation` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int(11) unsigned NOT NULL,
  `account_id` int(11) unsigned NOT NULL,
  `link_id` int(11) unsigned DEFAULT NULL,
  `created` timestamp NULL DEFAULT NULL,
  `type` tinyint(4) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `balance_after` decimal(15,2) NOT NULL,
  `currency` varchar(4) NOT NULL,
  `is_planned` tinyint(1) NOT NULL DEFAULT '0',
  `comment` varchar(255) NOT NULL,
  PRIMARY KEY (`id`,`project_id`,`account_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table finance_operation_has_category
# ------------------------------------------------------------

DROP TABLE IF EXISTS `finance_operation_has_category`;

CREATE TABLE `finance_operation_has_category` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `operation_id` int(11) unsigned NOT NULL,
  `category_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`,`operation_id`,`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table finance_project
# ------------------------------------------------------------

DROP TABLE IF EXISTS `finance_project`;

CREATE TABLE `finance_project` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `hidden` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `finance_project` WRITE;
/*!40000 ALTER TABLE `finance_project` DISABLE KEYS */;

INSERT INTO `finance_project` (`id`, `user_id`, `name`, `hidden`)
VALUES
	(1,1,'My Group',1),
	(2,2,'My Group',1),
	(3,3,'My Group',1),
	(4,4,'My Group',1),
	(5,5,'My Group',1),
	(6,6,'My Group',1),
	(7,7,'My Group',1),
	(8,8,'My Group',1),
	(9,9,'My Group',1),
	(10,10,'My Group',1),
	(11,11,'My Group',1),
	(12,12,'My Group',1),
	(13,13,'My Group',1),
	(14,13,'E korp',0);

/*!40000 ALTER TABLE `finance_project` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table finance_share
# ------------------------------------------------------------

DROP TABLE IF EXISTS `finance_share`;

CREATE TABLE `finance_share` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `state` tinyint(2) unsigned NOT NULL,
  `full_access` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `accounts` varchar(2048) NOT NULL,
  `operations` varchar(2048) NOT NULL,
  `categories` varchar(2048) NOT NULL,
  `budgets` varchar(2048) NOT NULL,
  `goals` varchar(2048) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table group_achievements
# ------------------------------------------------------------

DROP TABLE IF EXISTS `group_achievements`;

CREATE TABLE `group_achievements` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `group_id` int(11) unsigned NOT NULL,
  `title` text,
  `url` varchar(1023) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table group_addresses
# ------------------------------------------------------------

DROP TABLE IF EXISTS `group_addresses`;

CREATE TABLE `group_addresses` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` int(11) unsigned NOT NULL,
  `address` text,
  `phone` varchar(1023) DEFAULT NULL,
  `email` varchar(1023) DEFAULT NULL,
  `url` varchar(1023) DEFAULT NULL,
  `fax` varchar(1023) DEFAULT NULL,
  `country` varchar(1023) DEFAULT NULL,
  `zip_code` varchar(255) DEFAULT NULL,
  `head_office` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table group_categories
# ------------------------------------------------------------

DROP TABLE IF EXISTS `group_categories`;

CREATE TABLE `group_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(63) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `group_categories` WRITE;
/*!40000 ALTER TABLE `group_categories` DISABLE KEYS */;

INSERT INTO `group_categories` (`id`, `name`)
VALUES
	(1,'Active life'),
	(2,'Security'),
	(3,'Business'),
	(4,'Graphics and design'),
	(5,'Home and family'),
	(6,'Pets'),
	(7,'Healt'),
	(8,'Communications'),
	(9,'Games'),
	(10,'IT'),
	(11,'Cinema'),
	(12,'Fashion'),
	(13,'Cooking'),
	(14,'Arts and culture'),
	(15,'Literature'),
	(16,'Mobile communications and internet'),
	(17,'Music'),
	(18,'Sciense and techs'),
	(19,'Real Estate'),
	(20,'News and Media'),
	(21,'Education'),
	(22,'Construction and repair'),
	(23,'Society'),
	(24,'Politics'),
	(25,'Industry'),
	(26,'Travel'),
	(27,'Entertainment'),
	(28,'Religion'),
	(29,'Sport'),
	(30,'Insurance'),
	(31,'Television'),
	(32,'Products and service'),
	(33,'Interests'),
	(34,'Finances'),
	(35,'Photo'),
	(36,'Electronics'),
	(37,'Humor');

/*!40000 ALTER TABLE `group_categories` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table group_limits
# ------------------------------------------------------------

DROP TABLE IF EXISTS `group_limits`;

CREATE TABLE `group_limits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `owner_id` int(11) NOT NULL,
  `members_used` int(11) NOT NULL DEFAULT '0',
  `members_limit` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `group_limits_owner_id_idx` (`owner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `group_limits` WRITE;
/*!40000 ALTER TABLE `group_limits` DISABLE KEYS */;

INSERT INTO `group_limits` (`id`, `owner_id`, `members_used`, `members_limit`)
VALUES
	(1,1,0,0),
	(2,2,0,0),
	(3,3,0,0),
	(4,4,0,0),
	(5,5,0,0),
	(6,6,0,0),
	(7,7,0,0),
	(8,8,0,0),
	(9,9,0,0),
	(10,10,0,0),
	(11,11,0,0),
	(12,12,0,0),
	(13,13,0,0);

/*!40000 ALTER TABLE `group_limits` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table group_members
# ------------------------------------------------------------

DROP TABLE IF EXISTS `group_members`;

CREATE TABLE `group_members` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `group_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `role` varchar(1023) DEFAULT NULL,
  `approved` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `approve_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `wages` int(4) NOT NULL DEFAULT '0',
  `sort_order` int(11) unsigned NOT NULL DEFAULT '1',
  `show_main` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_invited` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_deleted` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `group_user` (`group_id`,`user_id`),
  KEY `user_group` (`user_id`,`group_id`),
  KEY `group_created` (`group_id`,`created`),
  KEY `group_sort_order` (`group_id`,`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `group_members` WRITE;
/*!40000 ALTER TABLE `group_members` DISABLE KEYS */;

INSERT INTO `group_members` (`id`, `created`, `group_id`, `user_id`, `role`, `approved`, `approve_date`, `wages`, `sort_order`, `show_main`, `is_invited`, `is_deleted`)
VALUES
	(1,'2015-11-09 16:15:15',1,0,'Administrator',1,'2015-11-09 16:15:15',0,0,1,0,0),
	(2,'2015-11-09 14:18:58',2,0,'Administrator',1,'2015-11-09 14:18:58',0,0,1,0,0),
	(3,'2015-11-10 10:24:11',3,0,'Администратор',1,'2015-11-10 10:24:11',0,0,1,0,0),
	(4,'2015-11-10 10:26:49',4,0,'Администратор',1,'2015-11-10 10:26:49',0,0,1,0,0),
	(5,'2015-11-10 10:28:51',5,0,'Администратор',1,'2015-11-10 10:28:51',0,0,1,0,0),
	(6,'2015-11-10 10:31:20',6,0,'Администратор',1,'2015-11-10 10:31:19',0,0,1,0,0),
	(7,'2015-11-10 10:34:21',7,0,'Администратор',1,'2015-11-10 10:34:21',0,0,1,0,0),
	(8,'2015-11-10 10:35:47',8,0,'Администратор',1,'2015-11-10 10:35:47',0,0,1,0,0),
	(9,'2015-11-10 10:38:16',9,0,'Администратор',1,'2015-11-10 10:38:16',0,0,1,0,0),
	(10,'2015-11-10 10:39:53',10,0,'Администратор',1,'2015-11-10 10:39:53',0,0,1,0,0),
	(11,'2015-11-10 10:42:24',11,0,'Администратор',1,'2015-11-10 10:42:24',0,0,1,0,0),
	(12,'2015-11-10 10:46:59',12,0,'Администратор',1,'2015-11-10 10:46:59',0,0,1,0,0),
	(13,'2015-11-10 11:30:36',13,0,'Администратор',1,'2015-11-10 11:30:36',0,0,1,0,0),
	(14,'2015-11-10 13:32:51',14,13,'Администратор',1,'2015-11-10 13:32:51',0,0,1,0,0),
	(15,'2015-11-10 13:36:00',14,11,'Подчиненный к',0,'0000-00-00 00:00:00',0,1,0,1,0);

/*!40000 ALTER TABLE `group_members` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table group_vacancy
# ------------------------------------------------------------

DROP TABLE IF EXISTS `group_vacancy`;

CREATE TABLE `group_vacancy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `descr` varchar(2047) NOT NULL,
  `country` varchar(15) NOT NULL,
  `city` varchar(1023) NOT NULL,
  `wage` int(10) unsigned NOT NULL DEFAULT '0',
  `currency` varchar(12) NOT NULL DEFAULT 'USD',
  `employment` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `shedule` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `experience` double NOT NULL DEFAULT '0',
  `open` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table group_videos
# ------------------------------------------------------------

DROP TABLE IF EXISTS `group_videos`;

CREATE TABLE `group_videos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `group_id` int(11) unsigned NOT NULL,
  `url` varchar(1023) DEFAULT NULL,
  `video_id` varchar(1023) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table groups
# ------------------------------------------------------------

DROP TABLE IF EXISTS `groups`;

CREATE TABLE `groups` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `owner_id` int(11) unsigned NOT NULL,
  `responsible_id` int(11) DEFAULT NULL,
  `title` varchar(1023) NOT NULL,
  `descr` text,
  `finance_project_id` int(11) DEFAULT NULL,
  `hidden` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `group_url` varchar(32) DEFAULT NULL,
  `video_url` varchar(1023) DEFAULT NULL,
  `cat_id` int(11) NOT NULL DEFAULT '0',
  `active_members` int(11) NOT NULL DEFAULT '0',
  `karma` int(11) NOT NULL DEFAULT '0',
  `rating` float NOT NULL DEFAULT '0',
  `is_dream` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `owner_created` (`owner_id`,`created`),
  KEY `title` (`title`(255))
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `groups` WRITE;
/*!40000 ALTER TABLE `groups` DISABLE KEYS */;

INSERT INTO `groups` (`id`, `created`, `owner_id`, `responsible_id`, `title`, `descr`, `finance_project_id`, `hidden`, `group_url`, `video_url`, `cat_id`, `active_members`, `karma`, `rating`, `is_dream`)
VALUES
	(1,'2015-11-09 16:15:15',1,NULL,'My Group',NULL,1,1,NULL,NULL,0,1,3,0,NULL),
	(2,'2015-11-09 14:18:58',2,NULL,'My Group',NULL,2,1,NULL,NULL,0,1,3,0,NULL),
	(3,'2015-11-10 10:24:11',3,NULL,'My Group',NULL,3,1,NULL,NULL,0,1,3,0,NULL),
	(4,'2015-11-10 10:26:49',4,NULL,'My Group',NULL,4,1,NULL,NULL,0,1,3,0,NULL),
	(5,'2015-11-10 10:28:51',5,NULL,'My Group',NULL,5,1,NULL,NULL,0,1,3,0,NULL),
	(6,'2015-11-10 10:31:19',6,NULL,'My Group',NULL,6,1,NULL,NULL,0,1,3,0,NULL),
	(7,'2015-11-10 10:34:21',7,NULL,'My Group',NULL,7,1,NULL,NULL,0,1,3,0,NULL),
	(8,'2015-11-10 10:35:47',8,NULL,'My Group',NULL,8,1,NULL,NULL,0,1,3,0,NULL),
	(9,'2015-11-10 10:38:16',9,NULL,'My Group',NULL,9,1,NULL,NULL,0,1,3,0,NULL),
	(10,'2015-11-10 10:39:53',10,NULL,'My Group',NULL,10,1,NULL,NULL,0,1,3,0,NULL),
	(11,'2015-11-10 10:42:24',11,NULL,'My Group',NULL,11,1,NULL,NULL,0,1,3,0,NULL),
	(12,'2015-11-10 10:46:59',12,NULL,'My Group',NULL,12,1,NULL,NULL,0,1,3,0,NULL),
	(13,'2015-11-10 11:30:36',13,NULL,'My Group',NULL,13,1,NULL,NULL,0,1,3,0,NULL),
	(14,'2015-11-10 13:32:51',13,NULL,'E korp','Корпорация бегемота',14,0,'','',2,1,4,0,NULL);

/*!40000 ALTER TABLE `groups` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table invest_category
# ------------------------------------------------------------

DROP TABLE IF EXISTS `invest_category`;

CREATE TABLE `invest_category` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `sort_order` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `invest_category` WRITE;
/*!40000 ALTER TABLE `invest_category` DISABLE KEYS */;

INSERT INTO `invest_category` (`id`, `title`, `sort_order`)
VALUES
	(1,'Design',0),
	(2,'Food',0),
	(3,'Journalism',0),
	(4,'Games',0),
	(5,'Art',0),
	(6,'Comics',0),
	(7,'Fashion',0),
	(8,'Music',0),
	(9,'Crafts',0),
	(10,'Publishing',0),
	(11,'Dance',0),
	(12,'Theater',0),
	(13,'Technology',0),
	(14,'Film & Video',0),
	(15,'Photography',0);

/*!40000 ALTER TABLE `invest_category` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table invest_project
# ------------------------------------------------------------

DROP TABLE IF EXISTS `invest_project`;

CREATE TABLE `invest_project` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `note` text NOT NULL,
  `total` decimal(15,2) DEFAULT NULL,
  `duration` tinyint(4) NOT NULL,
  `body` text NOT NULL,
  `video` varchar(1023) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `currency` varchar(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table invest_reward
# ------------------------------------------------------------

DROP TABLE IF EXISTS `invest_reward`;

CREATE TABLE `invest_reward` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `project_id` int(11) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `total` decimal(15,2) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table invest_sponsor
# ------------------------------------------------------------

DROP TABLE IF EXISTS `invest_sponsor`;

CREATE TABLE `invest_sponsor` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `reward_id` int(11) unsigned NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `currency` varchar(4) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table invest_video
# ------------------------------------------------------------

DROP TABLE IF EXISTS `invest_video`;

CREATE TABLE `invest_video` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int(11) unsigned NOT NULL,
  `youtube_id` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table invitations
# ------------------------------------------------------------

DROP TABLE IF EXISTS `invitations`;

CREATE TABLE `invitations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL,
  `object_type` bigint(20) NOT NULL DEFAULT '1',
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `SECONDARY` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table login_attempts
# ------------------------------------------------------------

DROP TABLE IF EXISTS `login_attempts`;

CREATE TABLE `login_attempts` (
  `ip` varchar(64) DEFAULT NULL,
  `action` varchar(64) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `expires` datetime DEFAULT NULL,
  KEY `ip` (`ip`,`action`),
  KEY `expires` (`expires`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `login_attempts` WRITE;
/*!40000 ALTER TABLE `login_attempts` DISABLE KEYS */;

INSERT INTO `login_attempts` (`ip`, `action`, `created`, `expires`)
VALUES
	('46.71.3.170','login','2015-11-10 13:12:04','2015-11-10 13:27:04'),
	('46.71.3.170','login','2015-11-10 13:21:10','2015-11-10 13:36:10');

/*!40000 ALTER TABLE `login_attempts` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table media
# ------------------------------------------------------------

DROP TABLE IF EXISTS `media`;

CREATE TABLE `media` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `media_type` varchar(10) NOT NULL DEFAULT 'bin_file',
  `object_type` varchar(50) NOT NULL DEFAULT 'Article',
  `object_id` int(11) unsigned DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  `ext` varchar(10) DEFAULT NULL,
  `main` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `orig_fname` varchar(255) DEFAULT NULL,
  `orig_fsize` bigint(20) unsigned DEFAULT NULL,
  `orig_w` int(11) unsigned DEFAULT NULL,
  `orig_h` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `media_type` (`media_type`,`object_type`,`object_id`,`main`),
  KEY `object_type_id` (`object_type`,`object_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `media` WRITE;
/*!40000 ALTER TABLE `media` DISABLE KEYS */;

INSERT INTO `media` (`id`, `created`, `media_type`, `object_type`, `object_id`, `file`, `ext`, `main`, `orig_fname`, `orig_fsize`, `orig_w`, `orig_h`)
VALUES
	(1,'2015-11-09 16:22:53','image','Article',1,'image','.png',1,'744px-CC_some_rights_reserved.svg.png',80283,744,300),
	(2,'2015-11-09 16:55:17','image','User',2,'image','.jpg',1,'alternateYLbox_green_small.jpg',56060,301,342);

/*!40000 ALTER TABLE `media` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table notes
# ------------------------------------------------------------

DROP TABLE IF EXISTS `notes`;

CREATE TABLE `notes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` varchar(1023) NOT NULL,
  `body` text,
  `type` varchar(8) NOT NULL DEFAULT 'text',
  `table_id` varchar(32) DEFAULT NULL,
  `is_folder` tinyint(2) NOT NULL DEFAULT '0',
  `parent_id` int(11) DEFAULT NULL,
  `lft` int(11) NOT NULL,
  `rght` int(11) NOT NULL,
  `last_updated_by` int(11) unsigned NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table order_products
# ------------------------------------------------------------

DROP TABLE IF EXISTS `order_products`;

CREATE TABLE `order_products` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `order_id` int(11) unsigned NOT NULL,
  `product_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `blocked` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `distrib_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`,`product_id`),
  KEY `product_id` (`product_id`,`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table order_reports
# ------------------------------------------------------------

DROP TABLE IF EXISTS `order_reports`;

CREATE TABLE `order_reports` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `contractor_id` int(11) unsigned NOT NULL,
  `order_id` int(11) unsigned NOT NULL,
  `period_count` int(11) unsigned NOT NULL,
  `product_type_id` int(11) unsigned NOT NULL,
  `product_id` int(11) unsigned NOT NULL,
  `qty` int(11) unsigned NOT NULL,
  `price` float(9,2) unsigned NOT NULL,
  `oper_id` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `contractor_id` (`contractor_id`,`order_id`,`product_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table order_types
# ------------------------------------------------------------

DROP TABLE IF EXISTS `order_types`;

CREATE TABLE `order_types` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(11) unsigned NOT NULL,
  `product_type_id` int(11) unsigned NOT NULL,
  `qty` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `order_id_product_type_id` (`order_id`,`product_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table orders
# ------------------------------------------------------------

DROP TABLE IF EXISTS `orders`;

CREATE TABLE `orders` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `contractor_id` int(11) unsigned NOT NULL,
  `period` int(11) unsigned NOT NULL,
  `paid` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `shipped` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `contractor_id` (`contractor_id`),
  KEY `created` (`created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table product_types
# ------------------------------------------------------------

DROP TABLE IF EXISTS `product_types`;

CREATE TABLE `product_types` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `arenda_price` float(9,2) unsigned NOT NULL,
  `teaser` varchar(1023) DEFAULT NULL,
  `descr` text NOT NULL,
  `min_qty` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `product_types` WRITE;
/*!40000 ALTER TABLE `product_types` DISABLE KEYS */;

INSERT INTO `product_types` (`id`, `title`, `arenda_price`, `teaser`, `descr`, `min_qty`)
VALUES
	(1,'Mobile point',50.00,'iPad Air 16 GB, WiFi + Cellular, access to the paid services','<p>iPad Air 16 GB, WiFi + Cellular, access to the paid services,</p>\r\n\r\n<p>24/7 service support, +50 GB storage for one year</p>\r\n',50),
	(4,'Workstation',75.00,'Monitor, PC, access to the paid site services','<p>Monitor, PC, access to the paid site services,</p>\r\n\r\n<p>24/7 site support</p>\r\n',10),
	(5,'Print copies',0.02,'Black & white printer, access to the paid site services','<p>Black &amp; white printer, access to the paid site services,</p>\r\n\r\n<p>24/7 site support</p>\r\n',1);

/*!40000 ALTER TABLE `product_types` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table products
# ------------------------------------------------------------

DROP TABLE IF EXISTS `products`;

CREATE TABLE `products` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `product_type_id` int(11) unsigned NOT NULL,
  `serial` varchar(255) NOT NULL,
  `prev_counter` bigint(20) unsigned DEFAULT '0',
  `ip` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_type_id` (`product_type_id`),
  KEY `serial` (`serial`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table profile_achievements
# ------------------------------------------------------------

DROP TABLE IF EXISTS `profile_achievements`;

CREATE TABLE `profile_achievements` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `profile_id` int(11) unsigned NOT NULL,
  `title` text,
  `url` varchar(1023) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `profile_id` (`profile_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table profiles
# ------------------------------------------------------------

DROP TABLE IF EXISTS `profiles`;

CREATE TABLE `profiles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `video_url` varchar(1023) DEFAULT NULL,
  `skills` text,
  `birthday` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `live_place` text,
  `full_name` varchar(1023) DEFAULT NULL,
  `lang` varchar(3) DEFAULT 'eng',
  `phone` varchar(1023) DEFAULT NULL,
  `university` varchar(1023) DEFAULT NULL,
  `speciality` varchar(1023) DEFAULT NULL,
  `live_country` varchar(1023) DEFAULT NULL,
  `timezone` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table project_events
# ------------------------------------------------------------

DROP TABLE IF EXISTS `project_events`;

CREATE TABLE `project_events` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `project_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `event_type` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `msg_id` bigint(20) unsigned DEFAULT NULL,
  `file_id` varchar(2048) DEFAULT NULL,
  `task_id` int(11) unsigned DEFAULT NULL,
  `subproject_id` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `project_id` (`project_id`),
  KEY `created` (`created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `project_events` WRITE;
/*!40000 ALTER TABLE `project_events` DISABLE KEYS */;

INSERT INTO `project_events` (`id`, `created`, `project_id`, `user_id`, `event_type`, `msg_id`, `file_id`, `task_id`, `subproject_id`)
VALUES
	(1,'2015-11-10 13:33:56',1,13,1,NULL,NULL,NULL,NULL);

/*!40000 ALTER TABLE `project_events` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table project_finances
# ------------------------------------------------------------

DROP TABLE IF EXISTS `project_finances`;

CREATE TABLE `project_finances` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `income_id` int(11) NOT NULL,
  `expense_id` int(11) NOT NULL,
  `tax_id` int(11) NOT NULL,
  `percent_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `project_finances` WRITE;
/*!40000 ALTER TABLE `project_finances` DISABLE KEYS */;

INSERT INTO `project_finances` (`id`, `project_id`, `income_id`, `expense_id`, `tax_id`, `percent_id`)
VALUES
	(1,1,1,2,3,4);

/*!40000 ALTER TABLE `project_finances` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table project_members
# ------------------------------------------------------------

DROP TABLE IF EXISTS `project_members`;

CREATE TABLE `project_members` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `project_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `is_responsible` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `sort_order` int(11) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `project_user` (`project_id`,`user_id`),
  KEY `user_project` (`user_id`,`project_id`),
  KEY `project_created` (`project_id`,`created`),
  KEY `project_sort_order` (`project_id`,`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `project_members` WRITE;
/*!40000 ALTER TABLE `project_members` DISABLE KEYS */;

INSERT INTO `project_members` (`id`, `created`, `project_id`, `user_id`, `is_responsible`, `sort_order`)
VALUES
	(1,'2015-11-10 13:33:56',1,13,1,0);

/*!40000 ALTER TABLE `project_members` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table projects
# ------------------------------------------------------------

DROP TABLE IF EXISTS `projects`;

CREATE TABLE `projects` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NULL DEFAULT NULL,
  `group_id` int(11) unsigned NOT NULL,
  `owner_id` int(11) unsigned NOT NULL,
  `title` varchar(1023) NOT NULL,
  `descr` text,
  `deadline` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `hidden` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `closed` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `finance_account_id` int(11) DEFAULT NULL,
  `finance_category_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `group_id` (`group_id`),
  KEY `owner_id` (`owner_id`),
  KEY `closed` (`closed`),
  KEY `deadline` (`deadline`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `projects` WRITE;
/*!40000 ALTER TABLE `projects` DISABLE KEYS */;

INSERT INTO `projects` (`id`, `created`, `modified`, `group_id`, `owner_id`, `title`, `descr`, `deadline`, `hidden`, `closed`, `deleted`, `finance_account_id`, `finance_category_id`)
VALUES
	(1,'2015-11-10 13:33:56','2015-11-10 13:33:56',14,13,'Тест','Тест','0000-00-00 00:00:00',0,0,0,NULL,NULL);

/*!40000 ALTER TABLE `projects` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table ratings
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ratings`;

CREATE TABLE `ratings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `foreign_model` varchar(255) NOT NULL,
  `foreign_id` int(11) NOT NULL,
  `context` varchar(255) DEFAULT NULL,
  `context_id` int(11) DEFAULT NULL,
  `value` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_ratings_goreign_id_foreign_model` (`foreign_id`,`foreign_model`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `ratings` WRITE;
/*!40000 ALTER TABLE `ratings` DISABLE KEYS */;

INSERT INTO `ratings` (`id`, `foreign_model`, `foreign_id`, `context`, `context_id`, `value`)
VALUES
	(1,'Group',1,'Rating.Group.add',1,3),
	(2,'User',1,'Rating.Group.add',1,3),
	(3,'Group',2,'Rating.Group.add',2,3),
	(4,'User',2,'Rating.Group.add',2,3),
	(5,'User',2,'Rating.Article.add',1,5),
	(6,'User',2,'Rating.UserEvent.add',1,1),
	(7,'Group',3,'Rating.Group.add',3,3),
	(8,'User',3,'Rating.Group.add',3,3),
	(9,'Group',4,'Rating.Group.add',4,3),
	(10,'User',4,'Rating.Group.add',4,3),
	(11,'Group',5,'Rating.Group.add',5,3),
	(12,'User',5,'Rating.Group.add',5,3),
	(13,'Group',6,'Rating.Group.add',6,3),
	(14,'User',6,'Rating.Group.add',6,3),
	(15,'Group',7,'Rating.Group.add',7,3),
	(16,'User',7,'Rating.Group.add',7,3),
	(17,'Group',8,'Rating.Group.add',8,3),
	(18,'User',8,'Rating.Group.add',8,3),
	(19,'Group',9,'Rating.Group.add',9,3),
	(20,'User',9,'Rating.Group.add',9,3),
	(21,'Group',10,'Rating.Group.add',10,3),
	(22,'User',10,'Rating.Group.add',10,3),
	(23,'Group',11,'Rating.Group.add',11,3),
	(24,'User',11,'Rating.Group.add',11,3),
	(25,'Group',12,'Rating.Group.add',12,3),
	(26,'User',12,'Rating.Group.add',12,3),
	(27,'Group',13,'Rating.Group.add',13,3),
	(28,'User',13,'Rating.Group.add',13,3),
	(29,'Group',14,'Rating.Group.add',14,3),
	(30,'User',13,'Rating.Group.add',14,3),
	(31,'Group',14,'Rating.Project.add',1,1),
	(32,'User',13,'Rating.Project.add',1,1);

/*!40000 ALTER TABLE `ratings` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table schema_migrations
# ------------------------------------------------------------

DROP TABLE IF EXISTS `schema_migrations`;

CREATE TABLE `schema_migrations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class` varchar(255) NOT NULL,
  `type` varchar(50) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `schema_migrations` WRITE;
/*!40000 ALTER TABLE `schema_migrations` DISABLE KEYS */;

INSERT INTO `schema_migrations` (`id`, `class`, `type`, `created`)
VALUES
	(1,'InitMigrations','Migrations','2015-07-28 12:38:45'),
	(2,'ConvertVersionToClassNames','Migrations','2015-07-28 12:38:45'),
	(3,'IncreaseClassNameLength','Migrations','2015-07-28 12:38:45'),
	(4,'Initial','app','2015-07-28 12:38:55'),
	(5,'StorageLimit','app','2015-07-28 12:38:57'),
	(6,'AddInterestsField','app','2015-07-28 17:10:55'),
	(7,'Filesharing','app','2015-08-12 00:21:34'),
	(8,'Addclosedatefield','app','2015-08-12 00:21:35'),
	(9,'DocumentVersions','app','2015-09-15 15:17:37'),
	(10,'AddDocVersion','app','2015-09-15 15:17:37'),
	(11,'GroupLimitsTable','app','2015-09-15 15:17:49'),
	(12,'Initial','Billing','2015-09-15 15:17:49'),
	(13,'LimitsFragmentedToTables','Billing','2015-09-15 15:17:50'),
	(14,'IndexesForSubscriptions','Billing','2015-09-15 15:17:50'),
	(15,'IndexesRework','Billing','2015-09-15 15:17:50'),
	(16,'AppSessionsTable','app','2015-10-05 17:06:56'),
	(17,'TranslateInitial','Translate','2015-10-05 17:06:56'),
	(18,'TranslateTableRename','Translate','2015-10-05 17:06:56'),
	(19,'AttemptInitial','Attempt','2015-10-20 10:17:14'),
	(20,'Invitations','app','2015-10-21 06:16:21'),
	(21,'RatingsTable','app','2015-10-26 12:18:57'),
	(24,'NotificationsAndCharsets','app','2015-11-01 12:09:53'),
	(25,'GroupDream','app','2015-11-04 16:50:45');

/*!40000 ALTER TABLE `schema_migrations` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table share
# ------------------------------------------------------------

DROP TABLE IF EXISTS `share`;

CREATE TABLE `share` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `target` int(11) NOT NULL,
  `object_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `share_type` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table sheet_data
# ------------------------------------------------------------

DROP TABLE IF EXISTS `sheet_data`;

CREATE TABLE `sheet_data` (
  `sheetid` varchar(255) NOT NULL,
  `columnid` int(11) NOT NULL DEFAULT '0',
  `rowid` int(11) NOT NULL DEFAULT '0',
  `data` varchar(255) DEFAULT NULL,
  `style` varchar(255) DEFAULT NULL,
  `parsed` varchar(255) DEFAULT NULL,
  `calc` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`sheetid`,`columnid`,`rowid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table sheet_header
# ------------------------------------------------------------

DROP TABLE IF EXISTS `sheet_header`;

CREATE TABLE `sheet_header` (
  `sheetid` varchar(255) NOT NULL,
  `columnid` int(11) NOT NULL DEFAULT '0',
  `label` varchar(255) DEFAULT NULL,
  `width` int(11) DEFAULT NULL,
  PRIMARY KEY (`sheetid`,`columnid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table sheet_sheet
# ------------------------------------------------------------

DROP TABLE IF EXISTS `sheet_sheet`;

CREATE TABLE `sheet_sheet` (
  `sheetid` varchar(255) NOT NULL,
  `userid` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `key` varchar(255) DEFAULT NULL,
  `cfg` varchar(512) DEFAULT NULL,
  PRIMARY KEY (`sheetid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table sheet_triggers
# ------------------------------------------------------------

DROP TABLE IF EXISTS `sheet_triggers`;

CREATE TABLE `sheet_triggers` (
  `id` int(11) NOT NULL,
  `sheetid` varchar(255) DEFAULT NULL,
  `trigger` varchar(10) DEFAULT NULL,
  `source` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table sheet_user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `sheet_user`;

CREATE TABLE `sheet_user` (
  `userid` int(11) NOT NULL,
  `apikey` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `secret` varchar(64) DEFAULT NULL,
  `pass` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table skills
# ------------------------------------------------------------

DROP TABLE IF EXISTS `skills`;

CREATE TABLE `skills` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rus` varchar(127) DEFAULT NULL,
  `eng` varchar(127) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `skills` WRITE;
/*!40000 ALTER TABLE `skills` DISABLE KEYS */;

INSERT INTO `skills` (`id`, `rus`, `eng`)
VALUES
	(1,'.Net','.net'),
	(2,'Управление Счетом','Account management'),
	(3,'Бухгалтерский Учет','Accounting'),
	(4,'Реклама','Advertising'),
	(5,'Ajax','Ajax'),
	(6,'Аналитик','Analysis'),
	(7,'Asp.Net','Asp.net'),
	(8,'Автокад','Autocad'),
	(9,'В2в','B2B'),
	(10,'Банкир','Banking'),
	(11,'Основные HTML','Basic HTML'),
	(12,'Бюджетник','Budgets'),
	(13,'Бизнес Аналитик','Business analysis'),
	(14,'Развитие Бизнеса','Business development'),
	(15,'Бизнес-Аналитики','Business intelligence'),
	(16,'Бизнес-Планирование','Business planning'),
	(17,'Бизнес-Процесс Совершенствования','Business process improvement'),
	(18,'Бизнес-Стратегия','Business strategy'),
	(19,'C','C'),
	(20,'C#','C#'),
	(21,'С++','C++'),
	(22,'Кадровый Менеджер','Change management'),
	(23,'Тренер','Coaching'),
	(24,'Работы С Населением','Community outreach'),
	(25,'Строительство','Construction'),
	(26,'Постоянное Совершенствование','Continuous improvement'),
	(27,'Переговоры По Заключению Контрактов','Contract negotiation'),
	(28,'Креативный Писатель','Creative Writing'),
	(29,'Уголовного Правосудия','Criminal Justice'),
	(30,'Критическое Мышление ','Critical Thinking '),
	(31,'Crm','CRM'),
	(32,'Кросс-Функциональное Руководство Коллективом','Cross-functional Team Leadership'),
	(33,'Обслуживание Клиентов','Customer service'),
	(34,'Анализ Данных','Data analysis'),
	(35,'Цифровой Маркетинг','Digital marketing'),
	(36,'Электронная Коммерция','E-Commerce'),
	(37,'Редактирование','Editing'),
	(38,'Инженерные','Engineering'),
	(39,'Английский','English'),
	(40,'Предприниматель','Entrepreneurship'),
	(41,'Event-Менеджер','Event management'),
	(42,'Секретарь','Event Planning'),
	(43,'Семейная Терапия','Family Therapy'),
	(44,'Финансы','Finance'),
	(45,'Финансовый Учет','Financial accounting'),
	(46,'Финансовый Анализ','Financial analysis'),
	(47,'Финансовой Отчетности','Financial Reporting'),
	(48,'Прогнозирование','Forecasting'),
	(49,'Фандрайзинг','Fundraising'),
	(50,'Газ','Gas'),
	(51,'Управляющий Персоналом','General ledger'),
	(52,'Управление','Governance'),
	(53,'Правительство','Government'),
	(54,'Написание Грантов','Grant Writing'),
	(55,'Графика','Graphcs'),
	(56,'Графический Дизайн','Graphic design'),
	(57,'Групповая Терапия','Group Thearpy'),
	(58,'Здравоохранения','Healthcare'),
	(59,'Управление Здравоохранения','Healthcare management'),
	(60,'Высшее Образование','Higher Education'),
	(61,'Больницы','Public Health'),
	(62,'Гостиничный Бизнес','Hospitality Management'),
	(63,'Отдел Качества HP','HP Quality Center'),
	(64,'Html','HTML'),
	(65,'HTML Emails','HTML Emails'),
	(66,'Html Scripting','HTML Scripting'),
	(67,'Html5','HTML5'),
	(68,'Человеческие Ресурсы','Human resources'),
	(69,'Иллюстратор','Illustrator'),
	(70,'Indesign','Interior design'),
	(71,'Страхование','Insurance'),
	(72,'Интеграция','Integration'),
	(73,'Управление Запасами','Inventory management'),
	(74,'ИТ-Стратегия','It strategy'),
	(75,'Java','Java'),
	(76,'Издание Корпоративных Приложений На Java','Java enterprise edition'),
	(77,'Javascript','Javascript'),
	(78,'Журналистика','Journalism'),
	(79,'Jquery','jQuery'),
	(80,'Json','JSON'),
	(81,'Jsp','JSP'),
	(82,'Core Java','Core Java'),
	(83,'K-12','k-12'),
	(84,'Кайдзен','Kaizen'),
	(85,'Канбан','kanban'),
	(86,'Ключевые Разработки','key account development'),
	(87,'Работа С Ключевыми Клиентами','Key account management'),
	(88,'Ключевые Показатели Эффективности','key performance indicators'),
	(89,'Печатник','Keyboards'),
	(90,'Трикотаж','Knitwear'),
	(91,'Администрация','knowledge management'),
	(92,'Подбор Ведущего Персонала','Lead generation'),
	(93,'Руководство','Leadership'),
	(94,'Развитие Лидерства','Leadership development'),
	(95,'Бережливое Производство','Lean manufacturing'),
	(96,'Юридические Исследования','Legal research'),
	(97,'Юридического Письма','Legal writing'),
	(98,'Linux','Linux'),
	(99,'Судебные Тяжбы','Litigation'),
	(100,'Логистика','Logistics'),
	(101,'Управление','Management'),
	(102,'Маркетинговые Исследования','Market Research'),
	(103,'Маркетинг','Marketing'),
	(104,'Маркетинговые Коммуникации','Marketing communications'),
	(105,'Маркетинговая Стратегия','Marketing strategy'),
	(106,'Microsoft Excel','Microsoft Excel'),
	(107,'Microsoft Office','Microsoft office'),
	(108,'Microsoft Word','Microsoft Word'),
	(109,'Microsoft Outlock','Microsoft Outlock'),
	(110,'Музыкальный Театр','Musical Theatre'),
	(111,'N+','N+'),
	(112,'Навигация','Navigation'),
	(113,'Переговоры','Negotiation'),
	(114,'Интернет','Networking'),
	(115,'Развитие Новых Направлений Бизнеса','New business development'),
	(116,'Новые Медиа','New Media'),
	(117,'Некоммерческие Организации','Nonprofits'),
	(118,'Нефть И Газ','Oil & gas'),
	(119,'Интернет-Реклама','Online advertising'),
	(120,'Онлайн Маркетинг','Online marketing'),
	(121,'Операционный Менеджмент','Operations management'),
	(122,'Oracle','Oracle'),
	(123,'Организация Развития','Organization development'),
	(124,'Os X','OS X'),
	(125,'Os 8.2','OS 8.2'),
	(126,'Разработчик ОС','OS Developer'),
	(127,'Outlook','Outlook'),
	(128,'Аутсорсинг','Outsourcing'),
	(129,'Фотошоп','Photoshop'),
	(130,'Playstation','Playstation'),
	(131,'Poka Yoke','Poka yoke'),
	(132,'Powder X-Ray Diffraction','powder x-ray diffraction'),
	(133,'Powerpoint','Powerpoint'),
	(134,'Процесс Совершенствования','Process improvement'),
	(135,'Программа Управления','Program management'),
	(136,'Управление Проектами','Project management'),
	(137,'Планирование Проекта','Project planning'),
	(138,'Связи С Общественностью','Public Relations'),
	(139,'Публичные Выступления','Public speaking'),
	(140,'QA Автоматизации','QA Automation'),
	(141,'QA Директор','QA Director'),
	(142,'QA Инженерных','QA Engineering'),
	(143,'Руководитель Группы Тестирования','QA Lead'),
	(144,'Контроль Качества','Quality Control'),
	(145,'Менеджер Качества','Quality Managment'),
	(146,'Система Качества','Quality System'),
	(147,'Количественные Исследования','Quantitative Research'),
	(148,'Быстрочтение','QuickBooks'),
	(149,'Недвижимость','Real Estate'),
	(150,'Подбор','Recruting'),
	(151,'Исследования','Research'),
	(152,'Жилых Домов','Residential Homes'),
	(153,'Розничная','Retail'),
	(154,'Управление Рисками','Risk Management'),
	(155,'Продажи','Sales'),
	(156,'Управление Продажами','Sales management'),
	(157,'Операций По Продажам','Sales operations'),
	(158,'Социальные Медиа','Social media'),
	(159,'Маркетинг В Социальных Медиа','Social media marketing'),
	(160,'Социальные Сети','Social networking'),
	(161,'Программное Обеспечение Auqlity','Software Auqlity Assurance'),
	(162,'Разработка Программного Обеспечения','Software development'),
	(163,'Зоны Solaris','solaris zones'),
	(164,'Стартапы','Start-ups'),
	(165,'Статистика','Statistic'),
	(166,'Стратегическое Планирование','Strategic planning'),
	(167,'Стратегическое Мышление','Strategic thinking'),
	(168,'Стратегия','Strategy'),
	(169,'Преподавание','Teaching'),
	(170,'Тимбилдинг','Team Building'),
	(171,'Руководство Коллективом','Team Leadership'),
	(172,'Управление Командой','Team management'),
	(173,'Работа В Команде','Teamwork'),
	(174,'Телекоммуникации','Telecommunications'),
	(175,'Театр','Theatre'),
	(176,'Богословие','Theology'),
	(177,'Лечебный Массаж','Therapeutic massage'),
	(178,'Тайм-Менеджмент','Time management'),
	(179,'Обучение','Training'),
	(180,'Устранение Неполадок','Troubleshooting'),
	(181,'Язык UML','Uml'),
	(182,'Редактирование','Underwriting'),
	(183,'Унифицированные Коммуникации','Unified communications'),
	(184,'Преподавание В Университете','University teaching'),
	(185,'Unix','Unix'),
	(186,'Ups Stream','Ups stream'),
	(187,'Пользовательское Приемочное Тестирование','User acceptance testing'),
	(188,'Пользовательский Опыт','User experience'),
	(189,'Дизайн Пользовательского Интерфейса','User interface design'),
	(190,'V&V','V&V'),
	(191,'V+','V+'),
	(192,'Управление Поставщиками','Vendor Management'),
	(193,'Видео','Video'),
	(194,'Редактирование Видео','Video editing'),
	(195,'Видео Производства','Video production'),
	(196,'Виртуализация','Virtualization'),
	(197,'Visio','Visio'),
	(198,'Визуальный Мерчандайзинг','Visual merchandising'),
	(199,'Волонтер Управления','Volunteer Management'),
	(200,'Уплавление Финансами','Wealth Managment'),
	(201,'Web 2.0','Web 2.0'),
	(202,'Web-Аналитика','Web analytics'),
	(203,'Web-Приложений','Web Applications'),
	(204,'Web-Контент','Web Content'),
	(205,'Web-Разработка','Web Development'),
	(206,'Web-Дизайн','Web Disign'),
	(207,'Web-Сервис','Web Service'),
	(208,'Развитие Web-Проекта','Website Development'),
	(209,'Windows','Windows'),
	(210,'Windows Server','Windows Server'),
	(211,'Windows Word','Windows Word'),
	(212,'Windows ХР','Windows Xp'),
	(213,'Написание','Writing'),
	(214,'Х++','X++'),
	(215,'Xbox 360','xbox 360'),
	(216,'Xcode','xcode'),
	(217,'Xhtml','XHTML'),
	(218,'Xml','XML'),
	(219,'X-Ray','x-ray'),
	(220,'Xslt','XSLT'),
	(221,'Яхтинг','Yachting'),
	(222,'Ярди','Yardi'),
	(223,'Управление Доходами','Yield Management'),
	(224,'Йога','Yoga'),
	(225,'Развития Молодежи','Youth development'),
	(226,'Наставничества Молодежи','Youth mentoring'),
	(227,'Молодежное Служение','Youth ministry'),
	(228,'Z/Os','z/os'),
	(229,'Zabbix','zabbix'),
	(230,'Zbrush','Zbrush'),
	(231,'Платформы Zend','Zend framework'),
	(232,'Мобильная','zimbra'),
	(233,'Зонирование','Zoning'),
	(234,'Зоология','zoology'),
	(235,'Зумба','zumba'),
	(236,'1с - Программирование','1C - Programming'),
	(237,'3D Анимация','3D Animation'),
	(238,'3D Графика','3D Graphics'),
	(239,'3D Иллюстрация','3D illustration'),
	(240,'3Д Интерьеры','3D Interiors'),
	(241,'3D Моделирование','3D Modeling'),
	(242,'3D Персонажи','3D characters'),
	(243,'Флэш-Программирование','Flash programming'),
	(244,'Html - Кодер','Html- Coder'),
	(245,'IT-Специалист','It specialist'),
	(246,'Pr','PR'),
	(247,'ОК - Тестеры','QA - testers'),
	(248,'Смм','SMM'),
	(249,'Смо','SMO'),
	(250,'Веб-Дизайнеры','Web-designers'),
	(251,'Веб - Интегратор','Web- integrator'),
	(252,'Веб-Программист','Web-programmer'),
	(253,'Авиадиспетчер','Air Traffic Controller'),
	(254,'Гонки Драйвер','race driver'),
	(255,'Автомеханик','Auto mechanic'),
	(256,'Агроном','agronomist'),
	(257,'Администраторы Баз Данных','DBAs'),
	(258,'Аквариумист','aquarist'),
	(259,'Актер','actor'),
	(260,'Аниматор','animator'),
	(261,'Антрополог','anthropologist'),
	(262,'Оранжеровщик','Arranger'),
	(263,'Искусство','art'),
	(264,'Арт-Директор','Art Director'),
	(265,'Артист Цирка','Circus performer'),
	(266,'Археолог','archaeologist'),
	(267,'Архитектор','architect'),
	(268,'Архитектор - Дизайнер','Architect - Designer'),
	(269,'Астроном','astronomer'),
	(270,'Аудио Редактирования','audio editing'),
	(271,'Аудитор','auditor'),
	(272,'Аэрография','aerography'),
	(273,'Бариста','barista'),
	(274,'Библиограф','bibliographer'),
	(275,'Бизнес-Аналитик','Business Analyst'),
	(276,'Бизнес-Планы','Business plans'),
	(277,'Бизнес-Тренер','Business Coach'),
	(278,'Биоинженерия','bioengineering'),
	(279,'Блогер','blogger'),
	(280,'Ботаник','botanist'),
	(282,'Бренд-Менеджер','Brand Manager'),
	(283,'Бейкер','baker'),
	(284,'Бухгалтер','Accountants'),
	(285,'Валеолог','valeologist'),
	(286,'Валютный Трейдер','Currency trader'),
	(287,'Веб-Разработчик','Web developers'),
	(288,'Векторная Графика','Vector Graphics'),
	(289,'Гример','makeup man'),
	(290,'Макет','layout'),
	(291,'Ветеринар','Veterinarian'),
	(292,'Видео Дизайн','video design'),
	(293,'Видео Презентация','video presentation'),
	(294,'Видео Дизайн','Video designs'),
	(295,'Видео Инфографика','Video Infographics'),
	(296,'Видео Презентации','video presentations'),
	(297,'Видеооператоров','Videographers'),
	(298,'Виджай','Vijay'),
	(299,'Визажист','Make‐up Artist'),
	(300,'Винодел','winemaker'),
	(301,'Вирусолог','virologist'),
	(302,'Витражист','Stained Glass Artist'),
	(303,'Дайвер','diver'),
	(304,'Востоковед','orientalist'),
	(305,'Врач','doctor'),
	(306,'Вулканолог','vulcanologist'),
	(307,'Вышивальщица','embroideress'),
	(308,'Игры Арт','Game Art'),
	(309,'Гейм-Дизайнеры','Game designers'),
	(310,'Геммолог','gemologist'),
	(311,'Генетик','geneticist'),
	(312,'Маркшейдер','surveyor'),
	(313,'Геофизик','geophysicist'),
	(314,'Гид - Переводчик','Guide- translator'),
	(315,'Гидрогеолог','hydrogeologist'),
	(316,'Гидролог','hydrologist'),
	(317,'Шахтер','miner'),
	(318,'Графический Дизайнер','graphic Designer'),
	(319,'Граффити','graffiti'),
	(320,'Визажист','Make-up Artist'),
	(321,'Гринкиперы','greenkeepers'),
	(322,'Грумер','groomer'),
	(323,'Разработчик','developer'),
	(324,'Дегустатор','taster'),
	(325,'Декоратор','decorator'),
	(326,'Детектив','detective'),
	(327,'Диджей','DJ'),
	(328,'Дизайн Выставочных Стендов','Design of exhibition stands'),
	(329,'Дизайнер','designer'),
	(330,'Дизайнер , Визуализатор','Designer , Visualizer'),
	(331,'Модельер','Fashion designer'),
	(332,'Дизайнерские Шрифты','Designer fonts'),
	(333,'Дизайнеры','designers'),
	(334,'Диктор','speaker'),
	(335,'Дипломат','diplomat'),
	(336,'Дирижер','conductor'),
	(337,'Тренер','trainer'),
	(338,'Египтолог','Egyptologist'),
	(339,'Живопись','painting'),
	(340,'Журналист','journalist'),
	(341,'Звукооператор','Sound Technician'),
	(342,'Земля','Land'),
	(343,'Зоопсихология','zoopsychology'),
	(344,'Изобретатель','inventor'),
	(345,'Иконография','iconography'),
	(346,'Иллюстраторы','illustrators'),
	(347,'Имиджмейкер','image maker'),
	(348,'Интерфейсы','interfaces'),
	(349,'Инфографика','Infographics'),
	(350,'Конный наездник','Equestrian'),
	(351,'Искусствовед','Art critic'),
	(352,'Тестер','tester'),
	(353,'Исследования','investigations'),
	(354,'Историк','historian'),
	(355,'Ихтиолог','ichthyologist'),
	(356,'Кавист','Kavist'),
	(357,'Кадровый Учет','personnel records'),
	(358,'Каллиграф','calligrapher'),
	(359,'Каппер','Capper'),
	(360,'Карикатурист','cartoonist'),
	(361,'Картограф','cartographer'),
	(362,'Картограф','Cartographic design'),
	(363,'Каскадер','stuntman'),
	(364,'Литье','casting'),
	(365,'Киберспортсмены','Cyber ES sports players'),
	(366,'Сценарист','screenwriter'),
	(367,'Клык','Canine'),
	(368,'Киномеханик','projectionist'),
	(369,'Оператор','cameraman'),
	(370,'Кинопродюссер','Film producer'),
	(371,'Кинорежиссер','film director'),
	(372,'Климатолог','climatologist'),
	(373,'Клоун','clown'),
	(374,'Кодер','encoder'),
	(375,'Кодификатор','codifier'),
	(376,'Колорист','colorist'),
	(377,'Комиксы','comics'),
	(378,'Коммуникабельность','sociability'),
	(379,'Композитор','composer'),
	(380,'Кондитер','confectioner'),
	(381,'Проектирование','designing'),
	(382,'Консультант','consultant'),
	(383,'Консультанты','Consultants'),
	(384,'Контекстная Реклама','Contextual Advertising'),
	(385,'Содержание','content'),
	(386,'Содержание Менеджер','Content Manager'),
	(387,'Копирайтер','copywriter'),
	(388,'Корректор','corrector'),
	(389,'Корреспондент','correspondent'),
	(390,'Косметолог','beautician'),
	(391,'Астронавт','astronaut'),
	(392,'Диване','Coach'),
	(393,'Творческий','Creative'),
	(394,'Творчество','creativity'),
	(395,'Кристограф','Crystallographer'),
	(396,'Критик','critic'),
	(397,'Создатель','Creator'),
	(399,'Блендер','Blender'),
	(400,'Ландшафтный Дизайнер','Landscape Designer'),
	(402,'Лесопатолог','Environmentalist'),
	(403,'Пилот','pilot'),
	(404,'Лингвист','linguist'),
	(405,'Лоббист','lobbyist'),
	(406,'Логист','Logist'),
	(407,'Логотипы','logos'),
	(408,'Локализация Игр','Localization of games'),
	(409,'Локализация','Localization'),
	(410,'Настольное Издательство','Desktop Publisher'),
	(411,'Брокер','broker'),
	(412,'Манекен','mannequin'),
	(413,'Музыкант','manualist'),
	(414,'Маркетолог','Marketer'),
	(415,'Медиа-байер','Media Buyer'),
	(416,'Медиа-Байер','Media Bayer'),
	(417,'Посредник','mediator'),
	(418,'Менеджер','manager'),
	(419,'Мобильные Агентство Развития','Mobile Development Agency'),
	(420,'Мобильных Разработчиков','Mobile developers'),
	(421,'Модель','model'),
	(422,'Музыкант','musician'),
	(423,'Анимация','animation'),
	(424,'Наблюдения','observation'),
	(425,'Нанотехнологии','nanotechnology'),
	(426,'Настольных Приложений','Desktop applications'),
	(427,'Настройщик Музыкальных Инструментов','Adjuster Musical Instruments'),
	(428,'Обработка Заказов','order processing'),
	(429,'Обработка Почты','mail processing'),
	(430,'Обработка Платежей','payment Processing'),
	(431,'Звук Игры','Sound games'),
	(432,'Океанограф','Oceanographer'),
	(433,'Онлайн Помощники','online assistants'),
	(434,'Оптимизация','optimization'),
	(435,'Организатор','organizer'),
	(436,'Палеантолог','Paleantolog'),
	(438,'Переводчик','translator'),
	(439,'Переводы','Translations'),
	(440,'Переговорщик','The Negotiator'),
	(441,'Переплетчик','bookbinder'),
	(442,'Принтер','printer'),
	(443,'Пивовар','brewer'),
	(444,'Пиксель-Арт','Pixel-Art'),
	(445,'Писатели','writers'),
	(446,'Писатель','writer'),
	(447,'Плавец','Plavec'),
	(448,'Повар','cook'),
	(449,'Полиграф','polygraph'),
	(450,'Политолог','political scientist'),
	(451,'Портер','porter'),
	(452,'Трюкач','stunt'),
	(453,'Проводка','posting'),
	(454,'Почвовед','soil scientist'),
	(455,'Предметной Визуализации','Subject visualization'),
	(456,'Презентации','presentations'),
	(457,'Учитель','teacher'),
	(458,'Разработка Приложений','application Development'),
	(459,'Программирование','programming'),
	(460,'Программист','programmer'),
	(461,'Продаж И Маркетинга','Sales and Marketing'),
	(462,'Продакт-Менеджер','Product Manager'),
	(463,'Продюсер','producer'),
	(464,'Дизайн','design'),
	(465,'Промоутер','promoter'),
	(466,'Прототипирование','prototyping'),
	(467,'Профилировщик','profiler'),
	(468,'Пчеловод','beekeeper'),
	(469,'Радист','Radio Host/Broadcasting'),
	(470,'Разработка Сайтов','Website Development'),
	(471,'Type Design','Type Design'),
	(472,'Разработчики Игры','game developers'),
	(473,'Разработчики Мобильных Приложений','Developers of mobile applications'),
	(474,'Режиссер','Director'),
	(475,'Богослов','theologian'),
	(476,'Репетитор','tutor'),
	(477,'Переписчиком.','rewriter'),
	(478,'Реставратор','restorer'),
	(479,'Референт','referent'),
	(480,'Чертеж','drawing'),
	(481,'Садовник','gardener'),
	(482,'Администрирование Сети','Network Administration'),
	(483,'Системный Администратор','system administrator'),
	(484,'Скульптор','sculptor'),
	(485,'Создание Субтитров','Creating subtitles'),
	(486,'Спичрайтер','speechwriter'),
	(487,'Статистик','statistician'),
	(488,'Стилист','stylist'),
	(489,'Стоматолог','Dentist'),
	(490,'Строитель','builder'),
	(491,'Супервайзер','supervisor'),
	(492,'Балерина','Ballet Dancer'),
	(493,'Татуировщик','Tattoo Artist'),
	(494,'Телеведущий','anchorman'),
	(495,'Тележурналист','TV reporter'),
	(496,'Тестирование','testing'),
	(497,'Техник. Поддержка','Tech. support'),
	(498,'Технический Писатель','technical Writer'),
	(499,'Трейдер','trader'),
	(500,'Ученый','The Scientist'),
	(501,'Финансист','financier'),
	(502,'Финансовый Аналитик','financial Analyst'),
	(503,'Фитодизайнер','phytodesigners'),
	(504,'Флорист','florist'),
	(505,'Фотограф','photographer'),
	(506,'Фотомодель','fashion model'),
	(507,'Химик','chemist'),
	(508,'Хореограф','choreographer'),
	(509,'Художник','artist'),
	(510,'Костюм','Costume'),
	(512,'Шеф-Повар','Chef'),
	(513,'Водитель','The driver'),
	(514,'Навигатор','navigator'),
	(515,'Эколог','ecologist'),
	(516,'Экономист','economist'),
	(517,'Экстерьеры','exteriors'),
	(518,'Этнограф','ethnographer'),
	(519,'Ювелир','jeweler'),
	(520,'Юзабилити','Usability'),
	(521,'Юрист','lawyer');

/*!40000 ALTER TABLE `skills` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table statistic
# ------------------------------------------------------------

DROP TABLE IF EXISTS `statistic`;

CREATE TABLE `statistic` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `visitor_id` int(11) unsigned NOT NULL,
  `pk` int(11) unsigned NOT NULL,
  `type` tinyint(3) unsigned NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `statistic` WRITE;
/*!40000 ALTER TABLE `statistic` DISABLE KEYS */;

INSERT INTO `statistic` (`id`, `visitor_id`, `pk`, `type`, `created`)
VALUES
	(1,2,1,1,'2015-11-09 16:23:03'),
	(2,2,1,1,'2015-11-09 16:53:56'),
	(3,2,2,0,'2015-11-09 16:54:12'),
	(4,3,3,0,'2015-11-10 12:25:18'),
	(5,4,4,2,'2015-11-10 12:27:30'),
	(6,4,4,2,'2015-11-10 12:27:45'),
	(7,4,4,0,'2015-11-10 12:27:49'),
	(8,5,5,2,'2015-11-10 12:29:49'),
	(9,5,5,0,'2015-11-10 12:29:57'),
	(10,6,6,0,'2015-11-10 12:32:49'),
	(11,7,7,0,'2015-11-10 12:35:03'),
	(12,8,8,0,'2015-11-10 12:37:12'),
	(13,9,9,0,'2015-11-10 12:39:00'),
	(14,10,10,0,'2015-11-10 12:41:12'),
	(15,11,11,0,'2015-11-10 12:45:23'),
	(16,12,12,0,'2015-11-10 12:47:33'),
	(17,13,13,2,'2015-11-10 13:32:08'),
	(18,13,14,2,'2015-11-10 13:32:51'),
	(19,13,13,0,'2015-11-10 13:33:02'),
	(20,13,14,2,'2015-11-10 13:33:13'),
	(21,13,14,2,'2015-11-10 13:34:03'),
	(22,13,14,2,'2015-11-10 13:34:33');

/*!40000 ALTER TABLE `statistic` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table storage_limit
# ------------------------------------------------------------

DROP TABLE IF EXISTS `storage_limit`;

CREATE TABLE `storage_limit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `message_file_size` bigint(20) DEFAULT NULL,
  `project_file_size` bigint(20) DEFAULT NULL,
  `cloud_size` bigint(20) DEFAULT NULL,
  `storage_limit` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `storage_limit_user_id_idx` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `storage_limit` WRITE;
/*!40000 ALTER TABLE `storage_limit` DISABLE KEYS */;

INSERT INTO `storage_limit` (`id`, `user_id`, `message_file_size`, `project_file_size`, `cloud_size`, `storage_limit`)
VALUES
	(1,1,0,0,0,2147483648),
	(2,2,0,0,0,2147483648),
	(3,3,0,0,0,2147483648),
	(4,4,0,0,0,2147483648),
	(5,5,0,0,0,2147483648),
	(6,6,0,0,0,2147483648),
	(7,7,0,0,0,2147483648),
	(8,8,0,0,0,2147483648),
	(9,9,0,0,0,2147483648),
	(10,10,0,0,0,2147483648),
	(11,11,0,0,0,2147483648),
	(12,12,0,0,0,2147483648),
	(13,13,0,0,0,2147483648);

/*!40000 ALTER TABLE `storage_limit` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table subprojects
# ------------------------------------------------------------

DROP TABLE IF EXISTS `subprojects`;

CREATE TABLE `subprojects` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `project_id` int(11) unsigned NOT NULL,
  `title` varchar(1023) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`),
  KEY `created` (`created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table subscriptions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `subscriptions`;

CREATE TABLE `subscriptions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL,
  `subscriber_id` int(11) NOT NULL,
  `type` varchar(16) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table synonyms
# ------------------------------------------------------------

DROP TABLE IF EXISTS `synonyms`;

CREATE TABLE `synonyms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(1023) NOT NULL,
  `variations` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `synonyms` WRITE;
/*!40000 ALTER TABLE `synonyms` DISABLE KEYS */;

INSERT INTO `synonyms` (`id`, `title`, `variations`)
VALUES
	(1,'Afghanistan','Афганистан|'),
	(2,'?land','?land|'),
	(3,'Albania','Албания|'),
	(4,'Algeria','Алжир|'),
	(5,'American Samoa','Американское Самоа|'),
	(6,'Andorra','Андорра|'),
	(7,'Angola','Ангола|'),
	(8,'Anguilla','Ангилья|'),
	(9,'Antarctica','Антарктида|'),
	(10,'Antigua and Barbuda','Антигуа и Барбуда|'),
	(11,'Argentina','Аргентина|'),
	(12,'Armenia','Армения|'),
	(13,'Aruba','Аруба|'),
	(14,'Australia','Австралия|'),
	(15,'Austria','Австрия|'),
	(16,'Azerbaijan','Азербайджан|'),
	(17,'Bahamas','Багамские острова|'),
	(18,'Bahrain','Бахрейн|'),
	(19,'Bangladesh','Бангладеш|'),
	(20,'Barbados','Барбадос|'),
	(21,'Belarus','Беларусь|'),
	(22,'Belgium','Бельгия|'),
	(23,'Belize','Белиз|'),
	(24,'Benin','Бенин|'),
	(25,'Bermuda','Бермудские острова|'),
	(26,'Bhutan','Бутан|'),
	(27,'Bolivia','Боливия|'),
	(28,'Bonaire','Бонайре|'),
	(29,'Bosnia and Herzegovina','Босния и Герцеговина|'),
	(30,'Botswana','Ботсвана|'),
	(31,'Bouvet Island','Остров Буве|'),
	(32,'Brazil','Бразилия|'),
	(33,'British Indian Ocean Territory','Британская территория Индийского океана|'),
	(34,'British Virgin Islands','Британские Виргинские острова|'),
	(35,'Brunei','Бруней|'),
	(36,'Bulgaria','Болгария|'),
	(37,'Burkina Faso','Буркина-Фасо|'),
	(38,'Burundi','Бурунди|'),
	(39,'Cambodia','Камбоджа|'),
	(40,'Cameroon','Камерун|'),
	(41,'Canada','Канада|'),
	(42,'Cape Verde','Кабо-Верде|'),
	(43,'Cayman Islands','Каймановы острова|'),
	(44,'Central African Republic','Центрально-Африканская Республика|'),
	(45,'Chad','Чад|'),
	(46,'Chile','Чили|'),
	(47,'China','Китай|'),
	(48,'Christmas Island','Остров Рождества|'),
	(49,'Cocos [Keeling] Islands','Кокосовые [Килинг] острова|'),
	(50,'Colombia','Колумбия|'),
	(51,'Comoros','Коморские острова|'),
	(52,'Cook Islands','Острова Кука|'),
	(53,'Costa Rica','Коста-Рика|'),
	(54,'Croatia','Хорватия|'),
	(55,'Cuba','Куба|'),
	(56,'Curacao','Кюрасао|'),
	(57,'Cyprus','Кипр|'),
	(58,'Czech Republic','Чешская Республика|'),
	(59,'Democratic Republic of the Congo','Демократическая Республика Конго|'),
	(60,'Denmark','Дания|'),
	(61,'Djibouti','Джибути|'),
	(62,'Dominica','Доминика|'),
	(63,'Dominican Republic','Доминиканская Республика|'),
	(64,'East Timor','Восточный Тимор|'),
	(65,'Ecuador','Эквадор|'),
	(66,'Egypt','Египет|'),
	(67,'El Salvador','Сальвадор|'),
	(68,'Equatorial Guinea','Экваториальная Гвинея|'),
	(69,'Eritrea','Эритрея|'),
	(70,'Estonia','Эстония|'),
	(71,'Ethiopia','Эфиопия|'),
	(72,'Falkland Islands','Фолклендские острова|'),
	(73,'Faroe Islands','Фарерские острова|'),
	(74,'Fiji','Фиджи|'),
	(75,'Finland','Финляндия|'),
	(76,'France','Франция|'),
	(77,'French Guiana','Французская Гвиана|'),
	(78,'French Polynesia','Французская Полинезия|'),
	(79,'French Southern Territories','Французские Южные Территории|'),
	(80,'Gabon','Габон|'),
	(81,'Gambia','Гамбия|'),
	(82,'Georgia','Грузия|'),
	(83,'Germany','Германия|'),
	(84,'Ghana','Гана|'),
	(85,'Gibraltar','Гибралтар|'),
	(86,'Greece','Греция|'),
	(87,'Greenland','Гренландия|'),
	(88,'Grenada','Гренада|'),
	(89,'Guadeloupe','Гваделупа|'),
	(90,'Guam','Гуам|'),
	(91,'Guatemala','Гватемала|'),
	(92,'Guernsey','Гернси|'),
	(93,'Guinea','Гвинея|'),
	(94,'Guinea-Bissau','Гвинея-Бисау|'),
	(95,'Guyana','Гайана|'),
	(96,'Haiti','Гаити|'),
	(97,'Heard Island and McDonald Islands','Остров Херд и острова Макдональд|'),
	(98,'Honduras','Гондурас|'),
	(99,'Hong Kong','Гонконг|'),
	(100,'Hungary','Венгрия|'),
	(101,'Iceland','Исландия|'),
	(102,'India','Индия|'),
	(103,'Indonesia','Индонезия|'),
	(104,'Iran','Иран|'),
	(105,'Iraq','Ирак|'),
	(106,'Ireland','Ирландия|'),
	(107,'Isle of Man','Остров Мэн|'),
	(108,'Israel','Израиль|'),
	(109,'Italy','Италия|'),
	(110,'Ivory Coast','Берег Слоновой Кости|'),
	(111,'Jamaica','Ямайка|'),
	(112,'Japan','Япония|'),
	(113,'Jersey','Джерси|'),
	(114,'Jordan','Иордания|'),
	(115,'Kazakhstan','Казахстан|'),
	(116,'Kenya','Кения|'),
	(117,'Kiribati','Кирибати|'),
	(118,'Kosovo','Косово|'),
	(119,'Kuwait','Кувейт|'),
	(120,'Kyrgyzstan','Киргизия|'),
	(121,'Laos','Лаос|'),
	(122,'Latvia','Латвия|'),
	(123,'Lebanon','Ливан|'),
	(124,'Lesotho','Лесото|'),
	(125,'Liberia','Либерия|'),
	(126,'Libya','Ливия|'),
	(127,'Liechtenstein','Лихтенштейн|'),
	(128,'Lithuania','Литва|'),
	(129,'Luxembourg','Люксембург|'),
	(130,'Macao','Макао|'),
	(131,'Macedonia','Македония|'),
	(132,'Madagascar','Мадагаскар|'),
	(133,'Malawi','Малави|'),
	(134,'Malaysia','Малайзия|'),
	(135,'Maldives','Мальдивы|'),
	(136,'Mali','Мали|'),
	(137,'Malta','Мальта|'),
	(138,'Marshall Islands','Маршалловы острова|'),
	(139,'Martinique','Мартиника|'),
	(140,'Mauritania','Мавритания|'),
	(141,'Mauritius','Маврикий|'),
	(142,'Mayotte','Майотта|'),
	(143,'Mexico','Мексика|'),
	(144,'Micronesia','Микронезия|'),
	(145,'Moldova','Молдова|'),
	(146,'Monaco','Монако|'),
	(147,'Mongolia','Монголия|'),
	(148,'Montenegro','Черногория|'),
	(149,'Montserrat','Монтсеррат|'),
	(150,'Morocco','Марокко|'),
	(151,'Mozambique','Мозамбик|'),
	(152,'Myanmar [Burma]','Мьянма [Бирма]|'),
	(153,'Namibia','Намибия|'),
	(154,'Nauru','Науру|'),
	(155,'Nepal','Непал|'),
	(156,'Netherlands','Нидерланды|'),
	(157,'New Caledonia','Новая Каледония|'),
	(158,'New Zealand','Новая Зеландия|'),
	(159,'Nicaragua','Никарагуа|'),
	(160,'Niger','Нигер|'),
	(161,'Nigeria','Нигерия|'),
	(162,'Niue','Ниуэ|'),
	(163,'Norfolk Island','Остров Норфолк|'),
	(164,'North Korea','Северная Корея|'),
	(165,'Northern Mariana Islands','Северные Марианские острова|'),
	(166,'Norway','Норвегия|'),
	(167,'Oman','Оман|'),
	(168,'Pakistan','Пакистан|'),
	(169,'Palau','Палау|'),
	(170,'Palestine','Палестина|'),
	(171,'Panama','Панама|'),
	(172,'Papua New Guinea','Папуа-Новая Гвинея|'),
	(173,'Paraguay','Парагвай|'),
	(174,'Peru','Перу|'),
	(175,'Philippines','Филиппины|'),
	(176,'Pitcairn Islands','Питкэрн острова|'),
	(177,'Poland','Польша|'),
	(178,'Portugal','Португалия|'),
	(179,'Puerto Rico','Пуэрто-Рико|'),
	(180,'Qatar','Катар|'),
	(181,'Republic of the Congo','Республика Конго|'),
	(182,'R?union','Реюньон|'),
	(183,'Romania','Румыния|'),
	(184,'Russia','Россия|'),
	(185,'Rwanda','Руанда|'),
	(186,'Saint Barth?lemy','Сен-Бартелеми|'),
	(187,'Saint Helena','Остров Святой Елены|'),
	(188,'Saint Kitts and Nevis','Сент-Китс и Невис|'),
	(189,'Saint Lucia','Сент-Люсия|'),
	(190,'Saint Martin','Сен-Мартен|'),
	(191,'Saint Pierre and Miquelon','Сен-Пьер и Микелон|'),
	(192,'Saint Vincent and the Grenadines','Сент-Винсент и Гренадины|'),
	(193,'Samoa','Самоа|'),
	(194,'San Marino','Сан - Марино|'),
	(195,'S?o Tom? and Pr?ncipe','Сан-Томе и Принсипи|'),
	(196,'Saudi Arabia','Саудовская Аравия|'),
	(197,'Senegal','Сенегал|'),
	(198,'Serbia','Сербия|'),
	(199,'Seychelles','Сейшельские острова|'),
	(200,'Sierra Leone','Сьерра-Леоне|'),
	(201,'Singapore','Сингапур|'),
	(202,'Sint Maarten','Синт-Маартен|'),
	(203,'Slovakia','Словакия|'),
	(204,'Slovenia','Словения|'),
	(205,'Solomon Islands','Соломоновы острова|'),
	(206,'Somalia','Сомали|'),
	(207,'South Africa','ЮАР|'),
	(208,'South Georgia and the South Sandwich Islands','Южная Георгия и Южные Сандвичевы острова|'),
	(209,'South Korea','Южная Корея|'),
	(210,'South Sudan','Южный Судан|'),
	(211,'Spain','Испания|'),
	(212,'Sri Lanka','Шри Ланка|'),
	(213,'Sudan','Судан|'),
	(214,'Suriname','Суринам|'),
	(215,'Svalbard and Jan Mayen','Шпицберген и Ян-Майен|'),
	(216,'Swaziland','Свазиленд|'),
	(217,'Sweden','Швеция|'),
	(218,'Switzerland','Швейцария|'),
	(219,'Syria','Сирия|'),
	(220,'Taiwan','Тайвань|'),
	(221,'Tajikistan','Таджикистан|'),
	(222,'Tanzania','Танзания|'),
	(223,'Thailand','Таиланд|'),
	(224,'Togo','Того|'),
	(225,'Tokelau','Токелау|'),
	(226,'Tonga','Тонга|'),
	(227,'Trinidad and Tobago','Тринидад и Тобаго|'),
	(228,'Tunisia','Тунис|'),
	(229,'Turkey','Турция|'),
	(230,'Turkmenistan','Туркменистан|'),
	(231,'Turks and Caicos Islands','Теркс и Кайкос острова|'),
	(232,'Tuvalu','Тувалу|'),
	(233,'U.S. Minor Outlying Islands','Внешние малые острова США.|'),
	(234,'U.S. Virgin Islands','Американские Виргинские острова|'),
	(235,'Uganda','Уганда|'),
	(236,'Ukraine','Украина|'),
	(237,'United Arab Emirates','Объединенные Арабские Эмираты|'),
	(238,'United Kingdom','Великобритания|'),
	(239,'United States','США|'),
	(240,'Uruguay','Уругвай|'),
	(241,'Uzbekistan','Узбекистан|'),
	(242,'Vanuatu','Вануату|'),
	(243,'Vatican City','Ватикан|'),
	(244,'Venezuela','Венесуэла|'),
	(245,'Vietnam','Вьетнам|'),
	(246,'Wallis and Futuna','Уоллис и Футуна|'),
	(247,'Western Sahara','Западная Сахара|'),
	(248,'Yemen','Йемен|'),
	(249,'Zambia','Замбия|'),
	(250,'Zimbabwe','Зимбабве|');

/*!40000 ALTER TABLE `synonyms` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table tasks
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tasks`;

CREATE TABLE `tasks` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `subproject_id` int(11) unsigned NOT NULL,
  `title` varchar(1023) NOT NULL,
  `creator_id` int(11) unsigned NOT NULL,
  `manager_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `closed` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `deadline` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `descr` text,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `close_date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subproject_id` (`subproject_id`),
  KEY `user_id` (`user_id`),
  KEY `created` (`created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table temp_documents
# ------------------------------------------------------------

DROP TABLE IF EXISTS `temp_documents`;

CREATE TABLE `temp_documents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `type` varchar(16) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table translate_i18n
# ------------------------------------------------------------

DROP TABLE IF EXISTS `translate_i18n`;

CREATE TABLE `translate_i18n` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `locale` varchar(6) NOT NULL,
  `model` varchar(255) NOT NULL,
  `foreign_key` int(11) NOT NULL,
  `field` varchar(255) NOT NULL,
  `content` text,
  PRIMARY KEY (`id`),
  KEY `i18n_locale_index` (`locale`),
  KEY `i18n_model_index` (`model`),
  KEY `i18n_row_id_index` (`foreign_key`),
  KEY `i18n_field_index` (`field`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table user_achievements
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_achievements`;

CREATE TABLE `user_achievements` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int(11) unsigned NOT NULL,
  `title` text,
  `url` varchar(1023) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table user_event_shares
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_event_shares`;

CREATE TABLE `user_event_shares` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `user_event_id` int(11) NOT NULL,
  `acceptance` smallint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `user_event_shares` WRITE;
/*!40000 ALTER TABLE `user_event_shares` DISABLE KEYS */;

INSERT INTO `user_event_shares` (`id`, `user_id`, `user_event_id`, `acceptance`)
VALUES
	(1,2,1,1);

/*!40000 ALTER TABLE `user_event_shares` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_events
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_events`;

CREATE TABLE `user_events` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `event_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `event_end_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `previous_event_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `type` varchar(16) NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `recipient_id` varchar(255) DEFAULT NULL,
  `title` varchar(1023) NOT NULL,
  `descr` text,
  `shared` tinyint(1) NOT NULL DEFAULT '0',
  `is_delayed` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Отложенное событие',
  `category` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Категоря события. 0 - работа, 1 - личое. Изначально берётся при создании события, исходя из типа события.',
  `object_type` varchar(16) DEFAULT NULL,
  `object_id` int(11) DEFAULT NULL,
  `place_name` varchar(1023) DEFAULT NULL,
  `place_coords` varchar(63) DEFAULT NULL,
  `finance_operation_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id_event_time` (`user_id`,`event_time`),
  KEY `created` (`created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `user_events` WRITE;
/*!40000 ALTER TABLE `user_events` DISABLE KEYS */;

INSERT INTO `user_events` (`id`, `created`, `event_time`, `event_end_time`, `previous_event_time`, `type`, `user_id`, `recipient_id`, `title`, `descr`, `shared`, `is_delayed`, `category`, `object_type`, `object_id`, `place_name`, `place_coords`, `finance_operation_id`)
VALUES
	(1,'2015-11-09 16:58:13','2015-11-15 21:00:00','2015-11-15 21:00:00','0000-00-00 00:00:00','meet',2,'','The super meeting','Xamarin.Forms rulez etc',1,0,0,'',NULL,NULL,NULL,NULL);

/*!40000 ALTER TABLE `user_events` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `fbid` varchar(32) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `is_admin` int(11) unsigned NOT NULL DEFAULT '0',
  `is_confirmed` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `full_name` varchar(1023) DEFAULT NULL,
  `profile_url` varchar(32) DEFAULT NULL,
  `video_url` varchar(1023) DEFAULT NULL,
  `video_id` varchar(1023) DEFAULT NULL,
  `skills` text,
  `interests` varchar(1023) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `lang` varchar(3) DEFAULT 'eng',
  `phone` varchar(1023) DEFAULT NULL,
  `live_place` text,
  `live_address` text,
  `university` varchar(1023) DEFAULT NULL,
  `speciality` varchar(1023) DEFAULT NULL,
  `live_country` varchar(1023) DEFAULT NULL,
  `timezone` varchar(50) DEFAULT NULL,
  `lat` decimal(10,8) DEFAULT NULL,
  `lng` decimal(11,8) DEFAULT NULL,
  `balance` float(9,2) unsigned NOT NULL DEFAULT '0.00',
  `news_update` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_update` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `karma` int(11) NOT NULL DEFAULT '0',
  `rating` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `username` (`username`),
  KEY `user_group_id` (`is_admin`),
  KEY `created` (`created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;

INSERT INTO `users` (`id`, `fbid`, `created`, `modified`, `is_admin`, `is_confirmed`, `is_deleted`, `username`, `password`, `full_name`, `profile_url`, `video_url`, `video_id`, `skills`, `interests`, `birthday`, `lang`, `phone`, `live_place`, `live_address`, `university`, `speciality`, `live_country`, `timezone`, `lat`, `lng`, `balance`, `news_update`, `last_update`, `karma`, `rating`)
VALUES
	(1,NULL,'2015-11-09 16:15:14','2015-11-10 13:45:53',0,1,0,'alexpers49@gmail.com','af6459e1b31b0f1af860cc73624a8b9113ae7360','Alex Pers',NULL,NULL,NULL,'IT specialist, Manager, Economist','Astronomy, Finances, IT',NULL,'eng',NULL,'Kyiv','Khreschatyk Street, 20-22',NULL,NULL,'UA','Europe/Helsinki',50.45020900,30.52253690,0.00,'0000-00-00 00:00:00','2015-11-10 13:45:53',3,0),
	(2,NULL,'2015-11-09 14:18:58','2015-11-09 17:09:59',0,0,0,'metalliova@ua.fm','deeaa4c2e70e338490b9d93cbed7650615abb510','Yuriy Levytskyy',NULL,NULL,NULL,'IT specialist, Sportsman, Cook, Photographer','Astronomy, Sciense, Travel, Technology, Nature, Architecture, Weapon, Startup, IT, Cooking, Literature, Entertainment, Electronics',NULL,'eng',NULL,'L\'viv','Hetmana Mazepy Street, 5А',NULL,NULL,'UA','Europe/Helsinki',49.87098900,24.02852400,0.00,'0000-00-00 00:00:00','2015-11-09 17:09:59',9,0),
	(3,NULL,'2015-11-10 10:24:11','2015-11-10 12:25:32',0,0,0,'arch-sterling@rambler.ru','2c2ac56149e2a0de517daa8edeec16c53cec06c7','Archer Sterling',NULL,NULL,NULL,'IT специалист, Экономист','Финансы, Оружие, Культура, Стартапы, Информационные технологии',NULL,'rus',NULL,'Kyiv','Yuriya Kondratyuka Street, 7',NULL,NULL,'UA','Europe/Helsinki',50.52535940,30.46269490,0.00,'0000-00-00 00:00:00','2015-11-10 12:25:32',3,0),
	(4,NULL,'2015-11-10 10:26:49','2015-11-10 12:28:00',0,0,0,'telegrafi@rambler.ru','2c2ac56149e2a0de517daa8edeec16c53cec06c7','Telegraf Telefich',NULL,NULL,NULL,'Агроном, Архитектор, Эколог','Астрономия, Финансы, Технологии, Кулинария, Литература',NULL,'rus',NULL,'Kiev',NULL,NULL,NULL,'UA','Europe/Helsinki',50.43330000,30.51670000,0.00,'0000-00-00 00:00:00','2015-11-10 12:28:00',3,0),
	(5,NULL,'2015-11-10 10:28:51','2015-11-10 12:30:03',0,0,0,'smirnof-vasya@rambler.ru','2c2ac56149e2a0de517daa8edeec16c53cec06c7','Smirnof Vasya',NULL,NULL,NULL,'Бухгалтер, Экономист, Военный','Медицина, Благотворительность, Музыка, Культура, Стартапы',NULL,'rus',NULL,'Kiev',NULL,NULL,NULL,'UA','Europe/Helsinki',50.43330000,30.51670000,0.00,'0000-00-00 00:00:00','2015-11-10 12:30:03',3,0),
	(6,NULL,'2015-11-10 10:31:19','2015-11-10 12:32:54',0,0,0,'mackorme@rambler.ru','2c2ac56149e2a0de517daa8edeec16c53cec06c7','Kenny Mackormicj',NULL,NULL,NULL,'Дизайнер, Фотограф, Эколог, Инженер','Автомобили, Литература, Развлечения, Электроника',NULL,'rus',NULL,'Kiev',NULL,NULL,NULL,'UA','Europe/Helsinki',50.43330000,30.51670000,0.00,'0000-00-00 00:00:00','2015-11-10 12:32:54',3,0),
	(7,NULL,'2015-11-10 10:34:21','2015-11-10 12:35:11',0,0,0,'macween@rambler.ru','2c2ac56149e2a0de517daa8edeec16c53cec06c7','Steave  Macqween',NULL,NULL,NULL,'Дизайнер, IT специалист, Агроном, Предприниматель','Медицина, Благотворительность, Музыка, Культура',NULL,'rus',NULL,'Kiev',NULL,NULL,NULL,'UA','Europe/Helsinki',50.43330000,30.51670000,0.00,'0000-00-00 00:00:00','2015-11-10 12:35:11',3,0),
	(8,NULL,'2015-11-10 10:35:47','2015-11-10 12:37:19',0,0,0,'my.n@lenta.ru','2c2ac56149e2a0de517daa8edeec16c53cec06c7','My  Name',NULL,NULL,NULL,'Дизайнер, IT специалист, Бухгалтер, Художник, Фотограф','Медицина, Благотворительность, Культура, Информационные технологии',NULL,'rus',NULL,'Kiev',NULL,NULL,NULL,'UA','Europe/Helsinki',50.43330000,30.51670000,0.00,'0000-00-00 00:00:00','2015-11-10 12:37:19',3,0),
	(9,NULL,'2015-11-10 10:38:16','2015-11-10 12:39:19',0,0,0,'karter-s@rambler.ru','2c2ac56149e2a0de517daa8edeec16c53cec06c7','Karter Sky',NULL,NULL,NULL,'Менеджер, Логист, Журналист, Строитель','Культура, Стартапы, Домашние животные, Автомобили, Активный отдых, Кулинария',NULL,'rus',NULL,'Kiev',NULL,NULL,NULL,'UA','Europe/Helsinki',50.43330000,30.51670000,0.00,'0000-00-00 00:00:00','2015-11-10 12:39:19',3,0),
	(10,NULL,'2015-11-10 10:39:53','2015-11-10 12:41:21',0,0,0,'stalke-vera@rambler.ru','2c2ac56149e2a0de517daa8edeec16c53cec06c7','Vera Stalker',NULL,NULL,NULL,'IT специалист, Бухгалтер, Фотограф, Экономист','Музыка, Автомобили, Активный отдых, Кулинария, Литература, Электроника',NULL,'rus',NULL,'Kiev',NULL,NULL,NULL,'UA','Europe/Helsinki',50.43330000,30.51670000,0.00,'0000-00-00 00:00:00','2015-11-10 12:41:21',3,0),
	(11,NULL,'2015-11-10 10:42:24','2015-11-10 12:45:28',0,0,0,'agent_k@lenta.ru','2c2ac56149e2a0de517daa8edeec16c53cec06c7','Agent K',NULL,NULL,NULL,'IT специалист, Менеджер, Экономист, Строитель','Красота и мода, Оружие, Автомобили, Информационные технологии, Кулинария, Литература, Электроника',NULL,'rus',NULL,'Kiev',NULL,NULL,NULL,'UA','Europe/Helsinki',50.43330000,30.51670000,0.00,'0000-00-00 00:00:00','2015-11-10 12:45:28',3,0),
	(12,NULL,'2015-11-10 10:46:59','2015-11-10 12:47:53',0,0,0,'agent_c@lenta.ru','2c2ac56149e2a0de517daa8edeec16c53cec06c7','Agent C',NULL,NULL,NULL,'Архитектор, Юрист, Спортсмен, Художник, Фотограф, Эколог, Инженер, Военный, Логист','Информационные технологии, Кулинария, Литература, Развлечения, Электроника',NULL,'rus',NULL,'Kiev',NULL,NULL,NULL,'UA','Europe/Helsinki',50.43330000,30.51670000,0.00,'0000-00-00 00:00:00','2015-11-10 12:47:53',3,0),
	(13,NULL,'2015-11-10 11:30:36','2015-11-10 13:36:12',0,0,0,'begemokot@lenta.ru','2c2ac56149e2a0de517daa8edeec16c53cec06c7','Kot Begemot',NULL,NULL,NULL,'Преподаватель, Менеджер, Военный, Строитель','Наука, Финансы, Путешествия, Юмор, Оружие, Литература',NULL,'rus',NULL,'Kiev',NULL,NULL,NULL,'UA','Europe/Helsinki',50.43330000,30.51670000,0.00,'0000-00-00 00:00:00','2015-11-10 13:36:12',7,0);

/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table vacancy_response
# ------------------------------------------------------------

DROP TABLE IF EXISTS `vacancy_response`;

CREATE TABLE `vacancy_response` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `vacancy_id` int(11) NOT NULL,
  `approve` tinyint(2) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
