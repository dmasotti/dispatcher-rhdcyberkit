-- Adminer 4.8.0 MySQL 5.7.23-23 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

CREATE TABLE `blk_apps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `list` int(11) NOT NULL,
  `smslist` int(11) NOT NULL,
  `description` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `fcm_api_key` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `blk_apps` (`id`, `code`, `list`, `smslist`, `description`, `fcm_api_key`) VALUES
(1,	'it.alfagroup.rhdCyberthExample.BlockExt',	1,	1,	'app prova per plugin rhdcyberth',	''),
(2,	'it.alfagroup.rhdcyberth.cyberThreat',	1,	1,	'',	''),
(3,	'it.alfagroup.rhdcyberth.cyberThreat1',	2,	0,	'',	''),
(4,	'it.alfagroup.rhdCyberthExample.BlockCall',	0,	1,	'',	''),
(5,	'it.alfagroup.rhdcyberth.BlockCall',	0,	1,	'prima lista',	''),
(6,	'it.alfagroup.rhdcyberth.BlockSMS',	0,	1,	'sms app',	''),
(7,	'it.alfagroup.rhdCyberthExample.BlockSMS',	0,	1,	'sms app',	''),
(8,	'it.alfagroup.rhd5.cyberThreat',	1,	1,	'lista per app RHD',	''),
(9,	'it.alfagroup.rhd5.BlockCall',	0,	1,	'blocco call per App RHD',	''),
(10,	'it.alfagroup.rhd5.BlockSMS',	0,	1,	'blocco lista sms per rhd app',	''),
(11,	'it.alfagroup.cybtest.cyberThreat',	1,	0,	'app test cybtest',	'AAAA3MCxiiE:APA91bEK8GtA6PyyL4HiuBfc_KrxvpyFWxoi7yjJcSH3a-ZXVqVpwLgnlbUJ5cI37yiZMiRwZhEvjly4ON5Xd19mvpNQ-9-LepWFgTGq78-1kXjrlUnKDaS2Y2QaIHjy_xfOpSNz2Em0');

CREATE TABLE `blk_cron_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ts` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `op_id` bigint(20) NOT NULL,
  `error` tinyint(4) NOT NULL,
  `operation` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `msg` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=156959 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `blk_cron_ops` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `ip` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `ua` varchar(400) COLLATE utf8_unicode_ci NOT NULL,
  `startts` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `currts` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `endts` timestamp NULL DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `list_id` int(11) NOT NULL,
  `url_read` int(11) NOT NULL,
  `url_uploaded` int(11) NOT NULL,
  `url_reported` int(11) NOT NULL,
  `url_victims` int(11) NOT NULL,
  `error` tinyint(4) NOT NULL,
  `msg` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=67093 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `blk_lists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `mintimestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `urls` bigint(20) NOT NULL,
  `md5` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `version` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `blk_lists` (`id`, `code`, `description`, `updated`, `mintimestamp`, `urls`, `md5`, `version`) VALUES
(1,	'LN1',	'prima lista',	'2024-02-11 16:14:25',	'2024-02-11 16:14:25',	4,	'a6565aa7c7e9f9d436dda6384d016dc8',	'1.0.1'),
(2,	'LN2',	'seconda lista',	'2022-09-01 09:20:24',	'0000-00-00 00:00:00',	0,	'',	'1.0.1'),
(3,	'LN3',	'lista 3',	'2022-09-01 09:20:38',	'0000-00-00 00:00:00',	0,	'',	'1.0.1');

CREATE TABLE `blk_numberblocked` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `number` varchar(5000) COLLATE utf8_unicode_ci NOT NULL,
  `ts` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `ip` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `ua` text COLLATE utf8_unicode_ci NOT NULL,
  `user` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `deviceId` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `app` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `blk_operations_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ts` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `ip` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `user` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `op` varchar(5000) COLLATE utf8_unicode_ci NOT NULL,
  `deviceId` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `app` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=67833 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `blk_pushlists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `deviceId` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `fcmId` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(4) NOT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `blk_sms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sms` varchar(400) COLLATE utf8_unicode_ci NOT NULL,
  `type` tinyint(4) NOT NULL,
  `list` int(11) NOT NULL,
  `description` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `datemod` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11007 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='type 0 - numero type 1 regular expression su numero - 2 regular expression su testo';


CREATE TABLE `blk_smsdetected` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `number` varchar(5000) COLLATE utf8_unicode_ci NOT NULL,
  `ts` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `ip` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `ua` text COLLATE utf8_unicode_ci NOT NULL,
  `user` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `deviceId` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `app` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `blk_smslists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `mintimestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `nsms` bigint(20) NOT NULL,
  `md5` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `version` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `blk_smslists` (`id`, `code`, `description`, `updated`, `mintimestamp`, `nsms`, `md5`, `version`) VALUES
(1,	'LN1',	'prima lista sms',	'2022-10-21 09:58:09',	'0000-00-00 00:00:00',	0,	'',	'1.0.0');

CREATE TABLE `blk_urlblocked` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(5000) COLLATE utf8_unicode_ci NOT NULL,
  `ts` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `ip` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `ua` text COLLATE utf8_unicode_ci NOT NULL,
  `user` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `deviceId` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `app` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `blk_urlreported` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(5000) COLLATE utf8_unicode_ci NOT NULL,
  `ts` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `ip` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `ua` text COLLATE utf8_unicode_ci NOT NULL,
  `user` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `deviceId` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `app` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `blk_urls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(400) COLLATE utf8_unicode_ci NOT NULL,
  `type` tinyint(4) NOT NULL,
  `list` int(11) NOT NULL,
  `description` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `datemod` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=131238 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `blk_urls` (`id`, `url`, `type`, `list`, `description`, `datemod`, `deleted`) VALUES
(131226,	'https://www.ilsole24ore.com/',	0,	1,	'sole 24',	'2024-02-06 08:58:45',	0),
(131228,	'www.ilsole24ore.com/',	0,	1,	'test http',	'2024-02-06 11:36:37',	0),
(131230,	'www.ilsole24ore.com/',	0,	1,	'test http',	'2024-02-06 11:37:55',	0),
(131232,	'www.lastampa.it',	0,	1,	'',	'2024-02-08 16:27:21',	0),
(131233,	'lastampa.it',	0,	1,	'',	'2024-02-11 16:15:54',	1),
(131236,	'www.udemy.com',	0,	1,	'',	'2024-02-11 12:50:24',	0),
(131237,	'ilsole24ore.com',	0,	1,	'',	'2024-02-12 08:48:33',	0);

CREATE TABLE `blk_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `rest_md5_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `lists` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `RHD_redirect` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `smslists` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `RHD_url` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `RHD_resolve` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `RHD_user` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `RHD_pwd` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `RHD_cron_sec` int(11) NOT NULL,
  `RHD_cron_enabled` tinyint(4) NOT NULL,
  `RHD_cron_running_ts` timestamp NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `RHD_cron_last_ts` timestamp NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `blk_users` (`id`, `username`, `rest_md5_key`, `lists`, `RHD_redirect`, `smslists`, `RHD_url`, `RHD_resolve`, `RHD_user`, `RHD_pwd`, `RHD_cron_sec`, `RHD_cron_enabled`, `RHD_cron_running_ts`, `RHD_cron_last_ts`) VALUES
(1,	'dmasotti',	'e7d8abbab93fe3a1b5b83f4358f8055f',	'1,2',	'https://labs.dmasotti.space/in.php',	'1,2',	'https://demo.rhd.it/fm360',	'',	'demo-fm',	'4LF4.demo-fm!',	60,	1,	'2024-02-12 12:30:04',	'2024-02-12 12:30:04');

-- 2024-02-12 12:42:40








