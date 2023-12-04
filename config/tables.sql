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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `blk_apps` (`id`, `code`, `list`, `smslist`, `description`) VALUES
(1,	'it.alfagroup.rhdCyberthExample.BlockExt',	1,	1,	'app prova per plugin rhdcyberth'),
(2,	'it.alfagroup.rhdcyberth.cyberThreat',	1,	1,	''),
(3,	'it.alfagroup.rhdcyberth.cyberThreat1',	2,	0,	''),
(4,	'it.alfagroup.rhdCyberthExample.BlockCall',	0,	1,	''),
(5,	'it.alfagroup.rhdcyberth.BlockCall',	0,	1,	'prima lista');

CREATE TABLE `blk_cron_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ts` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `op_id` bigint(20) NOT NULL,
  `error` tinyint(4) NOT NULL,
  `operation` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `msg` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34654 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


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
) ENGINE=InnoDB AUTO_INCREMENT=20285 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `blk_lists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `mintimestamp` timestamp,
  `urls` bigint(20) NOT NULL,
  `md5` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `version` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `blk_lists` (`id`, `code`, `description`, `updated`, `urls`, `md5`, `version`) VALUES
(1,	'LN1',	'prima lista',	'2022-11-04 10:35:41',	2,	'92f4c9535000ca28f3f7378684e28d4d',	'1.0.1'),
(2,	'LN2',	'seconda lista',	'2022-09-01 09:20:24',	0,	'',	'1.0.1'),
(3,	'LN3',	'lista 3',	'2022-09-01 09:20:38',	0,	'',	'1.0.1');

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
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `blk_operations_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ts` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `ip` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `user` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `op` varchar(5000) COLLATE utf8_unicode_ci NOT NULL,
  `deviceId` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `app` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8578 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `blk_sms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sms` varchar(400) COLLATE utf8_unicode_ci NOT NULL,
  `type` tinyint(4) NOT NULL,
  `list` int(11) NOT NULL,
  `description` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `datemod` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='type 0 - numero type 1 regular expression su numero - 2 regular expression su testo';



CREATE TABLE `blk_smslists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `nsms` bigint(20) NOT NULL,
  `md5` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `version` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `blk_smslists` (`id`, `code`, `description`, `updated`, `nsms`, `md5`, `version`) VALUES
(1,	'LN1',	'prima lista sms',	'2022-10-21 09:58:09',	0,	'',	'1.0.0');

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
) ENGINE=InnoDB AUTO_INCREMENT=179 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


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
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `blk_urls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(400) COLLATE utf8_unicode_ci NOT NULL,
  `type` tinyint(4) NOT NULL,
  `list` int(11) NOT NULL,
  `description` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `datemod` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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
(1,	'dmasotti',	'e7d8abbab93fe3a1b5b83f4358f8055f',	'1,2',	'https://labs.dmasotti.space/in.php',	'1,2',	'https://demo.rhd.it/fm360',	'',	'demo-fm',	'4LF4.demo-fm!',	60,	1,	NULL,	'2022-11-05 18:00:46');

-- 2022-11-05 18:06:19