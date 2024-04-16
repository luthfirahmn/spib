-- Adminer 4.8.1 MySQL 8.0.30 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

CREATE DATABASE `site_kuwil` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `site_kuwil`;

DROP TABLE IF EXISTS `data`;
CREATE TABLE `data` (
  `id` int NOT NULL AUTO_INCREMENT,
  `kode_instrument` varchar(50) NOT NULL COMMENT 'relasi ke db master -> table tr_instrument -> field kode_instrument',
  `tanggal` date NOT NULL,
  `jam` time NOT NULL,
  `keterangan` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` varchar(50) NOT NULL,
  `updated_by` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `data` (`id`, `kode_instrument`, `tanggal`, `jam`, `keterangan`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(6,	'VNOTCH2',	'2024-04-16',	'02:43:00',	'MANUAL',	'2024-04-15 19:45:38',	'2024-04-15 19:45:38',	'21',	'21'),
(10,	'VNOTCH1',	'2024-04-16',	'02:49:00',	'MANUAL',	'2024-04-15 19:49:16',	'2024-04-15 19:49:16',	'21',	'21'),
(11,	'VNOTCH2',	'2024-04-16',	'17:47:00',	'MANUAL',	'2024-04-16 10:47:50',	'2024-04-16 10:47:50',	'21',	'21'),
(12,	'AWLR3',	'2024-04-16',	'17:50:00',	'MANUAL',	'2024-04-16 10:50:03',	'2024-04-16 10:50:03',	'21',	'21'),
(13,	'VNOTCH2',	'2024-04-16',	'17:47:00',	'MANUAL',	'2024-04-16 10:47:50',	'2024-04-16 10:47:50',	'21',	'21'),
(14,	'VNOTCH2',	'2024-04-16',	'17:47:00',	'MANUAL',	'2024-04-16 10:47:50',	'2024-04-16 10:47:50',	'21',	'21'),
(15,	'VNOTCH2',	'2024-04-16',	'17:47:00',	'MANUAL',	'2024-04-16 10:47:50',	'2024-04-16 10:47:50',	'21',	'21'),
(16,	'VNOTCH2',	'2024-04-16',	'17:47:00',	'MANUAL',	'2024-04-16 10:47:50',	'2024-04-16 10:47:50',	'21',	'21'),
(17,	'VNOTCH2',	'2024-04-16',	'17:47:00',	'MANUAL',	'2024-04-16 10:47:50',	'2024-04-16 10:47:50',	'21',	'21'),
(18,	'VNOTCH2',	'2024-04-16',	'17:47:00',	'MANUAL',	'2024-04-16 10:47:50',	'2024-04-16 10:47:50',	'21',	'21'),
(19,	'VNOTCH2',	'2024-04-16',	'17:47:00',	'MANUAL',	'2024-04-16 10:47:50',	'2024-04-16 10:47:50',	'21',	'21'),
(20,	'VNOTCH2',	'2024-04-16',	'17:47:00',	'MANUAL',	'2024-04-16 10:47:50',	'2024-04-16 10:47:50',	'21',	'21'),
(21,	'VNOTCH2',	'2024-04-16',	'17:47:00',	'MANUAL',	'2024-04-16 10:47:50',	'2024-04-16 10:47:50',	'21',	'21'),
(22,	'VNOTCH2',	'2024-04-16',	'17:47:00',	'MANUAL',	'2024-04-16 10:47:50',	'2024-04-16 10:47:50',	'21',	'21'),
(23,	'VWP101',	'2024-04-16',	'17:51:00',	'MANUAL',	'2024-04-16 10:51:33',	'2024-04-16 10:51:33',	'21',	'21');

DROP TABLE IF EXISTS `data_value`;
CREATE TABLE `data_value` (
  `id` int NOT NULL AUTO_INCREMENT,
  `data_id` int NOT NULL,
  `sensor_id` int NOT NULL COMMENT 'relasi ke db master -> table sys_jenis_sensor_id',
  `data_primer` float DEFAULT NULL,
  `data_jadi` float DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` varchar(50) NOT NULL,
  `updated_by` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `data_id` (`data_id`),
  CONSTRAINT `data_value_ibfk_1` FOREIGN KEY (`data_id`) REFERENCES `data` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `data_value` (`id`, `data_id`, `sensor_id`, `data_primer`, `data_jadi`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(1,	6,	17,	10,	0,	'2024-04-15 19:45:38',	'2024-04-15 19:45:38',	'21',	'21'),
(2,	6,	15,	0,	10,	'2024-04-15 19:45:38',	'2024-04-15 19:45:38',	'21',	'21'),
(9,	10,	17,	0,	0,	'2024-04-15 19:49:16',	'2024-04-15 19:49:16',	'21',	'21'),
(10,	10,	15,	0,	0,	'2024-04-15 19:49:16',	'2024-04-15 19:49:16',	'21',	'21'),
(11,	11,	17,	56,	0,	'2024-04-16 10:47:50',	'2024-04-16 10:47:50',	'21',	'21'),
(12,	11,	15,	0,	46,	'2024-04-16 10:47:50',	'2024-04-16 10:47:50',	'21',	'21'),
(13,	12,	16,	44,	0,	'2024-04-16 10:50:03',	'2024-04-16 10:50:03',	'21',	'21'),
(14,	12,	14,	0,	12,	'2024-04-16 10:50:03',	'2024-04-16 10:50:03',	'21',	'21');

CREATE DATABASE `spib_master` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `spib_master`;

DROP TABLE IF EXISTS `files_temp`;
CREATE TABLE `files_temp` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `waktu` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `files_temp_instrument`;
CREATE TABLE `files_temp_instrument` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `waktu` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1,	'2019_12_14_000001_create_personal_access_tokens_table',	1),
(2,	'2024_02_17_113716_ms_menus',	1),
(3,	'2024_02_17_114156_ms_roles',	1),
(4,	'2024_02_17_114157_create_ms_users_table',	1),
(5,	'2024_02_17_130211_create_ms_accesscontrols',	1),
(6,	'2024_02_17_133910_create_ms_region',	1),
(7,	'2024_02_17_134535_create_ms_user_regions',	1);

DROP TABLE IF EXISTS `ms_accesscontrols`;
CREATE TABLE `ms_accesscontrols` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `ms_roles_id` bigint DEFAULT NULL,
  `ms_menus_id` bigint DEFAULT NULL,
  `view` tinyint DEFAULT '0',
  `insert` tinyint DEFAULT '0',
  `update` tinyint DEFAULT '0',
  `delete` tinyint DEFAULT '0',
  `created_by` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `ms_accesscontrols` (`id`, `ms_roles_id`, `ms_menus_id`, `view`, `insert`, `update`, `delete`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(1,	1,	50,	0,	1,	1,	0,	NULL,	'2024-02-28 03:56:20',	NULL,	'2024-03-07 12:54:43'),
(2,	1,	10,	1,	1,	1,	1,	NULL,	'2024-02-28 03:56:20',	NULL,	'2024-02-28 12:48:36'),
(3,	1,	31,	1,	1,	1,	1,	NULL,	'2024-02-28 03:56:20',	NULL,	'2024-04-04 18:17:53'),
(4,	1,	32,	1,	1,	1,	1,	NULL,	'2024-02-28 03:56:20',	NULL,	'2024-02-28 03:57:21'),
(5,	1,	20,	0,	1,	1,	1,	NULL,	'2024-02-28 03:56:20',	NULL,	'2024-03-07 12:54:43'),
(6,	1,	43,	1,	1,	1,	1,	NULL,	'2024-02-28 03:56:20',	NULL,	'2024-02-28 03:57:21'),
(7,	1,	60,	1,	1,	1,	1,	NULL,	'2024-02-28 03:56:20',	NULL,	'2024-04-16 17:19:03'),
(8,	1,	30,	1,	1,	1,	1,	NULL,	'2024-02-28 03:56:20',	NULL,	'2024-02-28 04:29:40'),
(9,	1,	41,	1,	1,	1,	1,	NULL,	'2024-02-28 03:56:20',	NULL,	'2024-03-16 14:20:55'),
(10,	1,	40,	1,	1,	1,	1,	NULL,	'2024-02-28 03:56:20',	NULL,	'2024-02-28 03:57:21'),
(11,	1,	72,	1,	1,	1,	1,	NULL,	'2024-02-28 03:56:20',	NULL,	'2024-04-01 18:32:42'),
(12,	1,	44,	1,	1,	1,	1,	NULL,	'2024-02-28 03:56:20',	NULL,	'2024-02-28 03:57:21'),
(13,	1,	42,	1,	1,	1,	1,	NULL,	'2024-02-28 03:56:20',	NULL,	'2024-02-28 03:57:21'),
(14,	1,	71,	1,	1,	1,	1,	NULL,	'2024-02-28 03:56:20',	NULL,	'2024-03-02 17:21:20'),
(15,	1,	70,	1,	1,	1,	1,	NULL,	'2024-02-28 03:56:20',	NULL,	'2024-02-28 04:49:06'),
(16,	2,	50,	0,	0,	0,	0,	NULL,	'2024-02-28 03:56:24',	NULL,	'2024-02-28 03:56:24'),
(17,	2,	10,	0,	0,	0,	0,	NULL,	'2024-02-28 03:56:24',	NULL,	'2024-02-28 03:56:24'),
(18,	2,	31,	0,	0,	0,	0,	NULL,	'2024-02-28 03:56:24',	NULL,	'2024-04-05 06:32:50'),
(19,	2,	32,	1,	1,	1,	1,	NULL,	'2024-02-28 03:56:24',	NULL,	'2024-04-01 18:31:54'),
(20,	2,	20,	0,	0,	0,	0,	NULL,	'2024-02-28 03:56:24',	NULL,	'2024-02-28 03:56:24'),
(21,	2,	43,	1,	1,	1,	1,	NULL,	'2024-02-28 03:56:24',	NULL,	'2024-03-07 13:20:49'),
(22,	2,	60,	0,	0,	0,	0,	NULL,	'2024-02-28 03:56:24',	NULL,	'2024-02-28 03:56:24'),
(23,	2,	30,	1,	1,	1,	1,	NULL,	'2024-02-28 03:56:24',	NULL,	'2024-03-07 12:49:46'),
(24,	2,	41,	0,	0,	0,	0,	NULL,	'2024-02-28 03:56:24',	NULL,	'2024-02-28 03:56:24'),
(25,	2,	40,	1,	1,	1,	1,	NULL,	'2024-02-28 03:56:24',	NULL,	'2024-04-01 18:31:54'),
(26,	2,	72,	0,	0,	0,	0,	NULL,	'2024-02-28 03:56:24',	NULL,	'2024-04-01 18:32:47'),
(27,	2,	44,	1,	1,	1,	1,	NULL,	'2024-02-28 03:56:24',	NULL,	'2024-03-07 13:20:49'),
(28,	2,	42,	1,	1,	1,	1,	NULL,	'2024-02-28 03:56:24',	NULL,	'2024-03-07 13:20:49'),
(29,	2,	71,	1,	1,	1,	1,	NULL,	'2024-02-28 03:56:24',	NULL,	'2024-04-01 18:31:54'),
(30,	2,	70,	1,	1,	1,	1,	NULL,	'2024-02-28 03:56:24',	NULL,	'2024-04-01 18:31:54'),
(31,	3,	50,	0,	0,	0,	0,	NULL,	'2024-02-28 03:56:29',	NULL,	'2024-02-28 03:56:29'),
(32,	3,	10,	0,	0,	0,	0,	NULL,	'2024-02-28 03:56:29',	NULL,	'2024-02-28 03:56:29'),
(33,	3,	31,	0,	0,	0,	0,	NULL,	'2024-02-28 03:56:29',	NULL,	'2024-02-28 03:56:29'),
(34,	3,	32,	0,	0,	0,	0,	NULL,	'2024-02-28 03:56:29',	NULL,	'2024-02-28 03:56:29'),
(35,	3,	20,	0,	0,	0,	0,	NULL,	'2024-02-28 03:56:29',	NULL,	'2024-02-28 03:56:29'),
(36,	3,	43,	0,	0,	0,	0,	NULL,	'2024-02-28 03:56:29',	NULL,	'2024-02-28 03:56:29'),
(37,	3,	60,	0,	0,	0,	0,	NULL,	'2024-02-28 03:56:29',	NULL,	'2024-02-28 03:56:29'),
(38,	3,	30,	0,	0,	0,	0,	NULL,	'2024-02-28 03:56:29',	NULL,	'2024-02-28 03:56:29'),
(39,	3,	41,	0,	0,	0,	0,	NULL,	'2024-02-28 03:56:29',	NULL,	'2024-02-28 03:56:29'),
(40,	3,	40,	0,	0,	0,	0,	NULL,	'2024-02-28 03:56:29',	NULL,	'2024-02-28 03:56:29'),
(41,	3,	72,	0,	0,	0,	0,	NULL,	'2024-02-28 03:56:29',	NULL,	'2024-02-28 03:56:29'),
(42,	3,	44,	0,	0,	0,	0,	NULL,	'2024-02-28 03:56:29',	NULL,	'2024-02-28 03:56:29'),
(43,	3,	42,	0,	0,	0,	0,	NULL,	'2024-02-28 03:56:29',	NULL,	'2024-02-28 03:56:29'),
(44,	3,	71,	0,	0,	0,	0,	NULL,	'2024-02-28 03:56:29',	NULL,	'2024-02-28 03:56:29'),
(45,	3,	70,	0,	0,	0,	0,	NULL,	'2024-02-28 03:56:29',	NULL,	'2024-02-28 04:28:55'),
(46,	1,	80,	1,	1,	1,	1,	NULL,	'2024-03-02 03:37:00',	NULL,	'2024-03-16 14:20:11'),
(47,	2,	80,	1,	1,	1,	1,	NULL,	'2024-03-02 03:39:10',	NULL,	'2024-04-01 18:31:54'),
(48,	3,	80,	1,	0,	0,	0,	NULL,	'2024-03-02 03:39:15',	NULL,	'2024-03-02 03:39:15'),
(49,	1,	45,	1,	1,	1,	1,	NULL,	'2024-03-19 13:08:25',	NULL,	'2024-03-19 13:12:34'),
(50,	2,	45,	1,	1,	1,	1,	NULL,	'2024-03-19 13:08:25',	NULL,	'2024-04-01 18:31:54'),
(51,	3,	45,	0,	0,	0,	0,	NULL,	'2024-03-19 13:08:25',	NULL,	'2024-03-19 13:08:25');

DROP TABLE IF EXISTS `ms_documentation_files`;
CREATE TABLE `ms_documentation_files` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ms_documentations_id` bigint unsigned DEFAULT NULL,
  `document_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_by` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `ms_documentation_files_ms_documentations_id_foreign` (`ms_documentations_id`),
  CONSTRAINT `ms_documentation_files_ms_documentations_id_foreign` FOREIGN KEY (`ms_documentations_id`) REFERENCES `ms_documentations` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `ms_documentation_files` (`id`, `ms_documentations_id`, `document_type`, `name`, `path`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(14,	8,	NULL,	'Perencanaan.png',	NULL,	NULL,	'2024-03-03 00:16:53',	NULL,	'2024-03-03 00:16:53'),
(15,	8,	NULL,	'Penelusuran.png',	NULL,	NULL,	'2024-03-03 00:16:53',	NULL,	'2024-03-03 00:16:53'),
(18,	17,	NULL,	'Capture.PNG',	NULL,	NULL,	'2024-04-04 05:40:14',	NULL,	'2024-04-04 05:40:14'),
(19,	18,	NULL,	'tes.docx',	NULL,	NULL,	'2024-04-04 05:40:53',	NULL,	'2024-04-04 05:40:53'),
(20,	19,	NULL,	'tes.pdf',	NULL,	NULL,	'2024-04-04 05:41:26',	NULL,	'2024-04-04 05:41:26'),
(21,	20,	NULL,	'Book1.xlsx',	NULL,	NULL,	'2024-04-04 05:42:03',	NULL,	'2024-04-04 05:42:03');

DROP TABLE IF EXISTS `ms_documentations`;
CREATE TABLE `ms_documentations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ms_regions_id` bigint unsigned NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date DEFAULT NULL,
  `about` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` int DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `ms_documentations_ms_regions_id_foreign` (`ms_regions_id`),
  CONSTRAINT `ms_documentations_ms_regions_id_foreign` FOREIGN KEY (`ms_regions_id`) REFERENCES `ms_regions` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `ms_documentations` (`id`, `ms_regions_id`, `title`, `date`, `about`, `category_id`, `description`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(8,	3,	'vvv',	'2024-03-05',	NULL,	1,	'testes',	NULL,	'2024-03-02 17:16:53',	NULL,	'2024-03-02 17:16:53'),
(17,	1,	'Main Dam',	'2024-04-04',	NULL,	1,	'ini data main dam png',	NULL,	'2024-04-03 22:40:14',	NULL,	'2024-04-03 22:40:14'),
(18,	1,	'Manual Pemeliharaan Instrument',	'2024-04-04',	NULL,	1,	'ini adalah manual pemeliharaan sop doc',	NULL,	'2024-04-03 22:40:53',	NULL,	'2024-04-03 22:40:53'),
(19,	1,	'Data Monitoring Bulan April',	'2024-04-04',	NULL,	2,	'ini adalah data monitoring april pdf',	NULL,	'2024-04-03 22:41:26',	NULL,	'2024-04-03 22:41:26'),
(20,	1,	'Presensi Operator',	'2024-04-04',	NULL,	3,	'Ini adalah presensi operator excel',	NULL,	'2024-04-03 22:42:03',	NULL,	'2024-04-03 22:42:03');

DROP TABLE IF EXISTS `ms_lookup_values`;
CREATE TABLE `ms_lookup_values` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `lookup_config` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `lookup_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `lookup_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ms_lookup_values_lookup_code_index` (`lookup_config`),
  KEY `ms_lookup_values_lookup_config_index` (`lookup_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `ms_lookup_values` (`id`, `lookup_config`, `lookup_code`, `lookup_name`) VALUES
(5,	'DOCUMENT_CATEGORY',	'1',	'Pelatihan'),
(6,	'DOCUMENT_CATEGORY',	'3',	'Kunjungan'),
(7,	'DOCUMENT_CATEGORY',	'2',	'Monitoring'),
(8,	'TYPE_INSTRUMENT',	'AWLR',	'AWLR'),
(9,	'TYPE_INSTRUMENT',	'KLIMATOLOGI',	'KLIMATOLOGI'),
(10,	'TYPE_INSTRUMENT',	'VNOTCH',	'VNOTCH'),
(11,	'TYPE_INSTRUMENT',	'VWP',	'VWP'),
(12,	'TYPE_INSTRUMENT',	'PRESSURE CELL',	'PRESSURE CELL'),
(13,	'TYPE_INSTRUMENT',	'OSP',	'OSP'),
(14,	'TYPE_INSTRUMENT',	'OW',	'OW'),
(15,	'ICON_SENSOR',	'pipe',	'path iconnya'),
(16,	'ICON_SENSOR',	'wind-turbine',	'path iconnya'),
(17,	'TYPE',	'VWP',	'VWP'),
(18,	'TYPE',	'NON_VWP',	'NON_VWP');

DROP TABLE IF EXISTS `ms_menus`;
CREATE TABLE `ms_menus` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `controller` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '',
  `menu_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent` int NOT NULL,
  `child` int DEFAULT NULL,
  `order` tinyint NOT NULL COMMENT 'list menu asc berdasarkan order',
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '0 = non active, 1 = active',
  `created_by` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_menu` (`menu_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `ms_menus` (`id`, `controller`, `menu_name`, `icon`, `parent`, `child`, `order`, `status`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(10,	'',	'Dashboard',	'ti ti-dashboard',	0,	0,	10,	1,	'SYSTEM',	'2024-02-17 18:38:54',	'SYSTEM',	'2024-02-28 03:24:52'),
(20,	'',	'Grafik',	'ti ti-chart-infographic',	0,	0,	20,	1,	'',	'2024-02-27 14:21:38',	'',	'2024-02-28 03:24:52'),
(30,	'',	'Master Data',	'ti ti-clipboard-list',	0,	1,	30,	1,	'',	'2024-02-28 02:39:43',	'',	'2024-02-28 03:25:06'),
(31,	'Data',	'Data',	'',	30,	0,	31,	1,	'SYSTEM',	'2024-02-17 18:38:54',	'SYSTEM',	'2024-04-04 09:30:04'),
(32,	'Dokumen',	'Documentation',	'',	30,	0,	32,	1,	'',	'2024-02-28 02:47:04',	'',	'2024-03-02 06:15:48'),
(40,	'',	'Region Management',	'ti ti-3d-cube-sphere',	0,	1,	40,	1,	'',	'2024-02-27 14:27:59',	'',	'2024-02-28 03:25:16'),
(41,	'Region',	'Region',	'',	40,	0,	41,	1,	'',	'2024-02-28 02:48:31',	'',	'2024-02-28 03:24:52'),
(42,	'Station',	'Station',	'',	40,	0,	42,	1,	'',	'2024-02-27 14:25:22',	'',	'2024-03-05 06:56:27'),
(43,	'Instrument',	'Instrument Type',	'',	40,	0,	43,	1,	'',	'2024-02-27 14:25:42',	'',	'2024-03-19 13:05:05'),
(44,	'Sensor',	'Sensor',	'',	40,	0,	44,	1,	'',	'2024-02-28 02:49:29',	'',	'2024-03-07 03:08:39'),
(45,	'InstrumentData',	'Instrument',	'',	40,	0,	45,	1,	'',	'2024-03-19 13:05:54',	'',	'2024-03-19 13:05:54'),
(50,	'',	'CCTV',	'ti ti-device-cctv',	0,	0,	50,	1,	'',	'2024-02-28 02:50:04',	'',	'2024-02-28 03:25:29'),
(60,	'Maps',	'Maps',	'ti ti-map',	0,	0,	60,	1,	'SYSTEM',	'2024-02-17 18:38:54',	'SYSTEM',	'2024-02-28 03:24:52'),
(70,	'',	'User Management',	'ti ti-users',	0,	1,	70,	1,	'',	'2024-02-27 14:22:59',	'',	'2024-02-28 03:25:32'),
(71,	'User',	'User',	'',	70,	0,	71,	1,	'',	'2024-02-28 02:52:31',	'',	'2024-03-07 12:57:31'),
(72,	'Role',	'Role Management',	'',	70,	0,	72,	1,	'',	'2024-02-27 14:23:34',	'',	'2024-02-28 03:24:52'),
(80,	'Site',	'Site',	'ti ti-map-pin',	40,	0,	80,	1,	'',	'2024-03-02 03:21:56',	'',	'2024-03-02 03:38:33');

DROP TABLE IF EXISTS `ms_regions`;
CREATE TABLE `ms_regions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `site_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `database_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `database_host` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `database_port` int NOT NULL,
  `database_username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `database_password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `app_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo_site` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `ms_regions` (`id`, `site_name`, `database_name`, `database_host`, `database_port`, `database_username`, `database_password`, `app_name`, `logo_site`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(1,	'Bendungan Kuwil',	'site_kuwil',	'localhost',	3306,	'root',	'root',	'SPIB KUWIL',	'logo_site_a.png',	'SYSTEM',	'2024-02-17 15:38:54',	'SYSTEM',	'2024-04-15 17:55:11'),
(2,	'Bendungan Ladongi',	'db_site_b',	'localhost',	3306,	'user_site_b',	'13213',	'SPIB Ladongi',	'logo_site_b.png',	'SYSTEM',	'2024-02-17 15:38:54',	'SYSTEM',	'2024-03-25 06:39:27'),
(3,	'Bendungan Gongseng',	'db_site_c',	'localhost',	3306,	'user_site_c',	'231321',	'Bendungan Gongseng',	'logo_site_c.png',	'SYSTEM',	'2024-02-17 15:38:54',	'SYSTEM',	'2024-03-25 06:39:44'),
(4,	'kupang',	'kupang',	'localhost',	3309,	'root',	'kosong',	'kupangaplikasi',	'wallpaperflare_com_wallpaper.jpg',	'',	'2024-03-25 03:13:52',	'',	'2024-03-25 03:13:52');

DROP TABLE IF EXISTS `ms_roles`;
CREATE TABLE `ms_roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `role_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_by` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `ms_roles` (`id`, `role_name`, `status`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(1,	'superadmin',	1,	'SYSTEM',	'2024-02-17 16:38:54',	'SYSTEM',	'2024-02-17 16:38:54'),
(2,	'admin site',	1,	'SYSTEM',	'2024-02-17 16:38:54',	'SYSTEM',	'2024-02-17 16:38:54'),
(3,	'guest',	1,	'SYSTEM',	'2024-02-17 16:38:54',	'SYSTEM',	'2024-02-17 16:38:54');

DROP TABLE IF EXISTS `ms_sites`;
CREATE TABLE `ms_sites` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ms_regions_id` bigint unsigned NOT NULL,
  `site_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `desa` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kecamatan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kabupaten` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provinsi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `longitude` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `elev_tanggul_utama` double(10,3) NOT NULL DEFAULT '0.000',
  `elev_tanggul_pembantu` double(10,3) NOT NULL DEFAULT '0.000',
  `elev_pelimpah` double(10,3) NOT NULL DEFAULT '0.000',
  `elev_pelimpah_pembantu` double(10,3) NOT NULL DEFAULT '0.000',
  `catatan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `elev_normal` double(10,3) NOT NULL DEFAULT '0.000',
  `elev_siaga3` double(10,3) NOT NULL DEFAULT '0.000',
  `elev_siaga2` double(10,3) NOT NULL DEFAULT '0.000',
  `elev_siaga1` double(10,3) NOT NULL DEFAULT '0.000',
  `batas_kritis_vwp` double(10,3) NOT NULL DEFAULT '0.000',
  `created_by` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `ms_sites_ms_regions_id_foreign` (`ms_regions_id`),
  CONSTRAINT `ms_sites_ms_regions_id_foreign` FOREIGN KEY (`ms_regions_id`) REFERENCES `ms_regions` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `ms_sites` (`id`, `ms_regions_id`, `site_name`, `desa`, `kecamatan`, `kabupaten`, `provinsi`, `latitude`, `longitude`, `elev_tanggul_utama`, `elev_tanggul_pembantu`, `elev_pelimpah`, `elev_pelimpah_pembantu`, `catatan`, `foto`, `elev_normal`, `elev_siaga3`, `elev_siaga2`, `elev_siaga1`, `batas_kritis_vwp`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(1,	1,	'Bendungan Kuwil-Kawangkoan',	'7106082002',	'710608',	'7106',	'71',	NULL,	NULL,	12.000,	12.000,	12.000,	12.000,	NULL,	NULL,	12.000,	12.000,	12.000,	12.000,	212.900,	'',	'2024-03-02 03:29:46',	'',	'2024-03-02 03:29:46');

DROP TABLE IF EXISTS `ms_stasiun`;
CREATE TABLE `ms_stasiun` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ms_regions_id` bigint unsigned NOT NULL,
  `nama_stasiun` varchar(255) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `wilayah_sungai` varchar(255) DEFAULT NULL,
  `daerah_aliran_sungai` varchar(255) DEFAULT NULL,
  `latitude` varchar(255) DEFAULT NULL,
  `longitude` varchar(255) DEFAULT NULL,
  `komunikasi` varchar(255) DEFAULT NULL,
  `kontak_gsm` varchar(255) DEFAULT NULL,
  `alamat_ip` varchar(255) DEFAULT NULL,
  `tahun_pembuatan` varchar(5) DEFAULT NULL,
  `elevasi` double(10,3) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ms_regions_id` (`ms_regions_id`),
  CONSTRAINT `ms_stasiun_ibfk_1` FOREIGN KEY (`ms_regions_id`) REFERENCES `ms_regions` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `ms_stasiun` (`id`, `ms_regions_id`, `nama_stasiun`, `foto`, `wilayah_sungai`, `daerah_aliran_sungai`, `latitude`, `longitude`, `komunikasi`, `kontak_gsm`, `alamat_ip`, `tahun_pembuatan`, `elevasi`) VALUES
(1,	1,	'VW PIEZOMETER STA 3+30',	'tes.png',	'Kuwil',	'Tondano',	'1.1946766067455297',	'124.80468750000001',	'WAN',	'-',	'192.168.4.8',	'2024',	123.450),
(2,	1,	'VW PIEZOMETER STA 3+90',	'',	'Kuwil',	'Tondano',	'1.2564605624116145',	'124.95849609375001',	'LAN',	'-',	'192.168.4.7',	'2024',	123.600),
(3,	1,	'KLIMATOLOGI1',	'KLIMATOLOGI.png',	'Kuwil',	'Tondano',	'1.2753708837057924',	'124.94150161743165',	'LORA',	'-',	'-',	'2024',	234.000),
(4,	1,	'VNOTCH',	'VNOTCH.jpg',	'Kuwil',	'Tondano',	'1.3223618361122975',	'124.87060546875001',	'WAN',	'-',	'192.168.4.6',	'2024',	100.000),
(5,	1,	'AWLR HULU',	'AWLRHULU.png',	'Kuwil',	'Tondano',	'1.3223618361122975',	'124.82666015625001',	'Satelit',	'-',	'-',	'2024',	368.000),
(6,	1,	'AWLR WADUK',	'AWLRWADUK.png',	'Kuwil',	'Tondano',	'1.106803716221284',	'124.93652343750001',	'LORA',	'-',	'-',	'2024',	124.200),
(7,	1,	'AWLR HILIR',	'AWLRHILIR.webp',	'Kuwil',	'Tondano',	'1.1507404998678228',	'125.02441406250001',	'GSM',	'082132123421',	'-',	'2024',	123.000),
(8,	1,	'KLIMATOLOGI2',	'KLIMATOLOGI2.jpg',	'Kuwil',	'Tondano',	'1.4508556276418016',	'125.18371582031251',	'GSM',	'0821312412',	'-',	'2024',	234.000),
(9,	1,	'KLIMATOLOGI3',	'KLIMATOLOGI3.png',	'Kuwil',	'Tondano',	'1.4980897753593954',	'125.09033203125001',	'Satelit',	'-',	'-',	'2024',	298.000),
(10,	2,	'tes',	'',	'tes',	'3213',	'1',	'1',	'test33213213',	'42342',	'3424',	'4234',	4324.000);

DROP TABLE IF EXISTS `ms_user_regions`;
CREATE TABLE `ms_user_regions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ms_users_id` bigint unsigned NOT NULL,
  `ms_regions_id` bigint unsigned NOT NULL,
  `created_by` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `ms_user_regions` (`id`, `ms_users_id`, `ms_regions_id`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(3,	3,	3,	'SYSTEM',	'2024-02-17 16:38:54',	'SYSTEM',	'2024-02-17 16:38:54'),
(22,	20,	1,	'',	'2024-03-06 12:48:25',	'',	'2024-03-06 12:48:25'),
(23,	20,	2,	'',	'2024-03-06 12:48:25',	'',	'2024-03-06 12:48:25'),
(24,	21,	2,	'',	'2024-03-07 12:51:02',	'',	'2024-03-07 12:51:02'),
(25,	21,	1,	'',	'2024-03-07 12:51:02',	'',	'2024-03-07 12:51:02'),
(27,	23,	1,	'',	'2024-03-24 00:02:11',	'',	'2024-03-24 00:02:11'),
(28,	23,	2,	'',	'2024-03-24 00:02:11',	'',	'2024-03-24 00:02:11'),
(29,	23,	3,	'',	'2024-03-24 00:02:11',	'',	'2024-03-24 00:02:11'),
(38,	30,	1,	'',	'2024-04-04 02:07:59',	'',	'2024-04-04 02:07:59'),
(39,	28,	1,	'',	'2024-04-04 02:08:22',	'',	'2024-04-04 02:08:22'),
(41,	31,	1,	'',	'2024-04-05 10:15:47',	'',	'2024-04-05 10:15:47');

DROP TABLE IF EXISTS `ms_users`;
CREATE TABLE `ms_users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ms_roles_id` bigint unsigned NOT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `foto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jabatan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tahun_kerja` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_telp` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dinas` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `api_status` tinyint NOT NULL DEFAULT '0',
  `api_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `ms_users` (`id`, `ms_roles_id`, `nama`, `username`, `password`, `foto`, `email`, `jabatan`, `tahun_kerja`, `no_telp`, `dinas`, `status`, `api_status`, `api_token`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(3,	3,	'Guest User',	'guest',	'hashed_password',	NULL,	'guest@example.com',	'Guest',	'1',	'111111111',	'Guest Dinas',	1,	0,	NULL,	'SYSTEM',	'2024-02-17 16:38:54',	'SYSTEM',	'2024-02-17 16:38:54'),
(20,	1,	'Superadmin User',	'superadmin',	'hashed_password',	'',	'superadmin@example.com',	'Superadmin',	NULL,	'123456789',	'Superadmin Dinas',	1,	0,	NULL,	'',	'2024-03-06 12:48:25',	'',	'2024-03-06 12:48:25'),
(21,	1,	'Admin User',	'admin',	'59ceb14713bd8922704730471290f566467f7306',	'',	'admin@example.com',	'Admin',	NULL,	'987654321',	'Admin Dinas',	1,	0,	NULL,	'',	'2024-03-07 12:51:02',	'',	'2024-03-07 12:51:02'),
(23,	1,	'cba',	'minggu',	'40bd001563085fc35165329ea1ff5c5ecbdbbeef',	'minggu.jpg',	'minggu@email.com',	'minggu',	NULL,	'0878221',	'minggu',	1,	0,	NULL,	'',	'2024-03-24 00:02:11',	'',	'2024-03-24 00:02:11'),
(28,	2,	'Admin Kuwil',	'adminkuwil',	'f865b53623b121fd34ee5426c792e5c33af8c227',	'adminkuwil.jpg',	'adminkuwil@gmail.com',	'Staff',	NULL,	'81312442231',	'Kalimantan',	1,	0,	NULL,	'',	'2024-04-04 02:08:22',	'',	'2024-04-04 02:08:22'),
(30,	3,	'Guest Kuwil',	'guestkuwil',	'7cf7eddb174125539dd241cd745391694250e526',	'guestkuwil.jpg',	'guest@gmail.com',	'Kepala Bidang Pemantauan',	NULL,	'08432235243',	'BMKG',	1,	0,	NULL,	'',	'2024-04-04 02:07:59',	'',	'2024-04-04 02:07:59'),
(31,	3,	'Rahmat',	'rahmatkuwil',	'f865b53623b121fd34ee5426c792e5c33af8c227',	'',	'rahmatalbarzanjie@gmail.com',	'Kepala Bidang Pemantauan',	NULL,	'082254049695',	'BMKG',	1,	0,	NULL,	'',	'2024-04-05 10:15:47',	'',	'2024-04-05 10:15:47');

DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `pj_ci_sessions`;
CREATE TABLE `pj_ci_sessions` (
  `id` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int unsigned NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `pj_ci_sessions` (`id`, `ip_address`, `timestamp`, `data`) VALUES
('9ec8a1f59eea72016cc4fb8ef63e7f19fd70b61d',	'::1',	1713289077,	'__ci_last_regenerate|i:1713289077;menu|a:6:{i:0;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"10\";s:10:\"controller\";s:0:\"\";s:9:\"menu_name\";s:9:\"Dashboard\";s:4:\"icon\";s:15:\"ti ti-dashboard\";s:6:\"parent\";s:1:\"0\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"10\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:6:\"SYSTEM\";s:10:\"created_at\";s:19:\"2024-02-18 01:38:54\";s:10:\"updated_by\";s:6:\"SYSTEM\";s:10:\"updated_at\";s:19:\"2024-02-28 10:24:52\";}i:1;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"60\";s:10:\"controller\";s:4:\"Maps\";s:9:\"menu_name\";s:4:\"Maps\";s:4:\"icon\";s:9:\"ti ti-map\";s:6:\"parent\";s:1:\"0\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"60\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:6:\"SYSTEM\";s:10:\"created_at\";s:19:\"2024-02-18 01:38:54\";s:10:\"updated_by\";s:6:\"SYSTEM\";s:10:\"updated_at\";s:19:\"2024-02-28 10:24:52\";}i:2;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"30\";s:10:\"controller\";s:0:\"\";s:9:\"menu_name\";s:11:\"Master Data\";s:4:\"icon\";s:20:\"ti ti-clipboard-list\";s:6:\"parent\";s:1:\"0\";s:5:\"child\";s:1:\"1\";s:5:\"order\";s:2:\"30\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-28 09:39:43\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-02-28 10:25:06\";}i:3;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"40\";s:10:\"controller\";s:0:\"\";s:9:\"menu_name\";s:17:\"Region Management\";s:4:\"icon\";s:20:\"ti ti-3d-cube-sphere\";s:6:\"parent\";s:1:\"0\";s:5:\"child\";s:1:\"1\";s:5:\"order\";s:2:\"40\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-27 21:27:59\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-02-28 10:25:16\";}i:4;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"70\";s:10:\"controller\";s:0:\"\";s:9:\"menu_name\";s:15:\"User Management\";s:4:\"icon\";s:11:\"ti ti-users\";s:6:\"parent\";s:1:\"0\";s:5:\"child\";s:1:\"1\";s:5:\"order\";s:2:\"70\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-27 21:22:59\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-02-28 10:25:32\";}i:5;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"80\";s:10:\"controller\";s:4:\"Site\";s:9:\"menu_name\";s:4:\"Site\";s:4:\"icon\";s:13:\"ti ti-map-pin\";s:6:\"parent\";s:1:\"0\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"80\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-03-02 10:21:56\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-02 10:38:33\";}}submenu|a:9:{i:0;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"31\";s:10:\"controller\";s:4:\"Data\";s:9:\"menu_name\";s:4:\"Data\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"30\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"31\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:6:\"SYSTEM\";s:10:\"created_at\";s:19:\"2024-02-18 01:38:54\";s:10:\"updated_by\";s:6:\"SYSTEM\";s:10:\"updated_at\";s:19:\"2024-04-04 16:30:04\";}i:1;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"32\";s:10:\"controller\";s:7:\"Dokumen\";s:9:\"menu_name\";s:13:\"Documentation\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"30\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"32\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-28 09:47:04\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-02 13:15:48\";}i:2;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"43\";s:10:\"controller\";s:10:\"Instrument\";s:9:\"menu_name\";s:15:\"Instrument Type\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"40\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"43\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-27 21:25:42\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-19 20:05:05\";}i:3;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"41\";s:10:\"controller\";s:6:\"Region\";s:9:\"menu_name\";s:6:\"Region\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"40\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"41\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-28 09:48:31\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-02-28 10:24:52\";}i:4;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"72\";s:10:\"controller\";s:4:\"Role\";s:9:\"menu_name\";s:15:\"Role Management\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"70\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"72\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-27 21:23:34\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-02-28 10:24:52\";}i:5;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"44\";s:10:\"controller\";s:6:\"Sensor\";s:9:\"menu_name\";s:6:\"Sensor\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"40\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"44\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-28 09:49:29\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-07 10:08:39\";}i:6;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"42\";s:10:\"controller\";s:7:\"Station\";s:9:\"menu_name\";s:7:\"Station\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"40\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"42\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-27 21:25:22\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-05 13:56:27\";}i:7;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"71\";s:10:\"controller\";s:4:\"User\";s:9:\"menu_name\";s:4:\"User\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"70\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"71\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-28 09:52:31\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-07 19:57:31\";}i:8;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"45\";s:10:\"controller\";s:14:\"InstrumentData\";s:9:\"menu_name\";s:10:\"Instrument\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"40\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"45\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-03-19 20:05:54\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-19 20:05:54\";}}roles_id|s:1:\"1\";list_region|a:2:{i:0;O:8:\"stdClass\":2:{s:9:\"site_name\";s:12:\"SPIB Ladongi\";s:9:\"logo_site\";s:15:\"logo_site_b.png\";}i:1;O:8:\"stdClass\":2:{s:9:\"site_name\";s:10:\"SPIB KUWIL\";s:9:\"logo_site\";s:15:\"logo_site_a.png\";}}foto|s:0:\"\";jabatan|s:5:\"Admin\";ap_id_user|s:2:\"21\";ap_password|s:40:\"59ceb14713bd8922704730471290f566467f7306\";ap_nama|s:10:\"Admin User\";'),
('05f239b9c4eaf13dde507bee03c3c2cb983f0d45',	'::1',	1713289553,	'__ci_last_regenerate|i:1713289553;menu|a:6:{i:0;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"10\";s:10:\"controller\";s:0:\"\";s:9:\"menu_name\";s:9:\"Dashboard\";s:4:\"icon\";s:15:\"ti ti-dashboard\";s:6:\"parent\";s:1:\"0\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"10\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:6:\"SYSTEM\";s:10:\"created_at\";s:19:\"2024-02-18 01:38:54\";s:10:\"updated_by\";s:6:\"SYSTEM\";s:10:\"updated_at\";s:19:\"2024-02-28 10:24:52\";}i:1;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"60\";s:10:\"controller\";s:4:\"Maps\";s:9:\"menu_name\";s:4:\"Maps\";s:4:\"icon\";s:9:\"ti ti-map\";s:6:\"parent\";s:1:\"0\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"60\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:6:\"SYSTEM\";s:10:\"created_at\";s:19:\"2024-02-18 01:38:54\";s:10:\"updated_by\";s:6:\"SYSTEM\";s:10:\"updated_at\";s:19:\"2024-02-28 10:24:52\";}i:2;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"30\";s:10:\"controller\";s:0:\"\";s:9:\"menu_name\";s:11:\"Master Data\";s:4:\"icon\";s:20:\"ti ti-clipboard-list\";s:6:\"parent\";s:1:\"0\";s:5:\"child\";s:1:\"1\";s:5:\"order\";s:2:\"30\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-28 09:39:43\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-02-28 10:25:06\";}i:3;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"40\";s:10:\"controller\";s:0:\"\";s:9:\"menu_name\";s:17:\"Region Management\";s:4:\"icon\";s:20:\"ti ti-3d-cube-sphere\";s:6:\"parent\";s:1:\"0\";s:5:\"child\";s:1:\"1\";s:5:\"order\";s:2:\"40\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-27 21:27:59\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-02-28 10:25:16\";}i:4;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"70\";s:10:\"controller\";s:0:\"\";s:9:\"menu_name\";s:15:\"User Management\";s:4:\"icon\";s:11:\"ti ti-users\";s:6:\"parent\";s:1:\"0\";s:5:\"child\";s:1:\"1\";s:5:\"order\";s:2:\"70\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-27 21:22:59\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-02-28 10:25:32\";}i:5;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"80\";s:10:\"controller\";s:4:\"Site\";s:9:\"menu_name\";s:4:\"Site\";s:4:\"icon\";s:13:\"ti ti-map-pin\";s:6:\"parent\";s:1:\"0\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"80\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-03-02 10:21:56\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-02 10:38:33\";}}submenu|a:9:{i:0;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"31\";s:10:\"controller\";s:4:\"Data\";s:9:\"menu_name\";s:4:\"Data\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"30\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"31\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:6:\"SYSTEM\";s:10:\"created_at\";s:19:\"2024-02-18 01:38:54\";s:10:\"updated_by\";s:6:\"SYSTEM\";s:10:\"updated_at\";s:19:\"2024-04-04 16:30:04\";}i:1;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"32\";s:10:\"controller\";s:7:\"Dokumen\";s:9:\"menu_name\";s:13:\"Documentation\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"30\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"32\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-28 09:47:04\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-02 13:15:48\";}i:2;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"43\";s:10:\"controller\";s:10:\"Instrument\";s:9:\"menu_name\";s:15:\"Instrument Type\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"40\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"43\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-27 21:25:42\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-19 20:05:05\";}i:3;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"41\";s:10:\"controller\";s:6:\"Region\";s:9:\"menu_name\";s:6:\"Region\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"40\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"41\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-28 09:48:31\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-02-28 10:24:52\";}i:4;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"72\";s:10:\"controller\";s:4:\"Role\";s:9:\"menu_name\";s:15:\"Role Management\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"70\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"72\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-27 21:23:34\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-02-28 10:24:52\";}i:5;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"44\";s:10:\"controller\";s:6:\"Sensor\";s:9:\"menu_name\";s:6:\"Sensor\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"40\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"44\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-28 09:49:29\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-07 10:08:39\";}i:6;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"42\";s:10:\"controller\";s:7:\"Station\";s:9:\"menu_name\";s:7:\"Station\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"40\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"42\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-27 21:25:22\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-05 13:56:27\";}i:7;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"71\";s:10:\"controller\";s:4:\"User\";s:9:\"menu_name\";s:4:\"User\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"70\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"71\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-28 09:52:31\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-07 19:57:31\";}i:8;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"45\";s:10:\"controller\";s:14:\"InstrumentData\";s:9:\"menu_name\";s:10:\"Instrument\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"40\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"45\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-03-19 20:05:54\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-19 20:05:54\";}}roles_id|s:1:\"1\";list_region|a:2:{i:0;O:8:\"stdClass\":2:{s:9:\"site_name\";s:12:\"SPIB Ladongi\";s:9:\"logo_site\";s:15:\"logo_site_b.png\";}i:1;O:8:\"stdClass\":2:{s:9:\"site_name\";s:10:\"SPIB KUWIL\";s:9:\"logo_site\";s:15:\"logo_site_a.png\";}}foto|s:0:\"\";jabatan|s:5:\"Admin\";ap_id_user|s:2:\"21\";ap_password|s:40:\"59ceb14713bd8922704730471290f566467f7306\";ap_nama|s:10:\"Admin User\";'),
('e43dbfb2173cc0b13b77598a897a7d9ee6516bcb',	'::1',	1713289949,	'__ci_last_regenerate|i:1713289949;menu|a:6:{i:0;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"10\";s:10:\"controller\";s:0:\"\";s:9:\"menu_name\";s:9:\"Dashboard\";s:4:\"icon\";s:15:\"ti ti-dashboard\";s:6:\"parent\";s:1:\"0\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"10\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:6:\"SYSTEM\";s:10:\"created_at\";s:19:\"2024-02-18 01:38:54\";s:10:\"updated_by\";s:6:\"SYSTEM\";s:10:\"updated_at\";s:19:\"2024-02-28 10:24:52\";}i:1;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"60\";s:10:\"controller\";s:4:\"Maps\";s:9:\"menu_name\";s:4:\"Maps\";s:4:\"icon\";s:9:\"ti ti-map\";s:6:\"parent\";s:1:\"0\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"60\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:6:\"SYSTEM\";s:10:\"created_at\";s:19:\"2024-02-18 01:38:54\";s:10:\"updated_by\";s:6:\"SYSTEM\";s:10:\"updated_at\";s:19:\"2024-02-28 10:24:52\";}i:2;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"30\";s:10:\"controller\";s:0:\"\";s:9:\"menu_name\";s:11:\"Master Data\";s:4:\"icon\";s:20:\"ti ti-clipboard-list\";s:6:\"parent\";s:1:\"0\";s:5:\"child\";s:1:\"1\";s:5:\"order\";s:2:\"30\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-28 09:39:43\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-02-28 10:25:06\";}i:3;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"40\";s:10:\"controller\";s:0:\"\";s:9:\"menu_name\";s:17:\"Region Management\";s:4:\"icon\";s:20:\"ti ti-3d-cube-sphere\";s:6:\"parent\";s:1:\"0\";s:5:\"child\";s:1:\"1\";s:5:\"order\";s:2:\"40\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-27 21:27:59\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-02-28 10:25:16\";}i:4;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"70\";s:10:\"controller\";s:0:\"\";s:9:\"menu_name\";s:15:\"User Management\";s:4:\"icon\";s:11:\"ti ti-users\";s:6:\"parent\";s:1:\"0\";s:5:\"child\";s:1:\"1\";s:5:\"order\";s:2:\"70\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-27 21:22:59\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-02-28 10:25:32\";}i:5;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"80\";s:10:\"controller\";s:4:\"Site\";s:9:\"menu_name\";s:4:\"Site\";s:4:\"icon\";s:13:\"ti ti-map-pin\";s:6:\"parent\";s:1:\"0\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"80\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-03-02 10:21:56\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-02 10:38:33\";}}submenu|a:9:{i:0;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"31\";s:10:\"controller\";s:4:\"Data\";s:9:\"menu_name\";s:4:\"Data\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"30\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"31\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:6:\"SYSTEM\";s:10:\"created_at\";s:19:\"2024-02-18 01:38:54\";s:10:\"updated_by\";s:6:\"SYSTEM\";s:10:\"updated_at\";s:19:\"2024-04-04 16:30:04\";}i:1;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"32\";s:10:\"controller\";s:7:\"Dokumen\";s:9:\"menu_name\";s:13:\"Documentation\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"30\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"32\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-28 09:47:04\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-02 13:15:48\";}i:2;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"43\";s:10:\"controller\";s:10:\"Instrument\";s:9:\"menu_name\";s:15:\"Instrument Type\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"40\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"43\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-27 21:25:42\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-19 20:05:05\";}i:3;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"41\";s:10:\"controller\";s:6:\"Region\";s:9:\"menu_name\";s:6:\"Region\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"40\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"41\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-28 09:48:31\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-02-28 10:24:52\";}i:4;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"72\";s:10:\"controller\";s:4:\"Role\";s:9:\"menu_name\";s:15:\"Role Management\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"70\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"72\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-27 21:23:34\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-02-28 10:24:52\";}i:5;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"44\";s:10:\"controller\";s:6:\"Sensor\";s:9:\"menu_name\";s:6:\"Sensor\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"40\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"44\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-28 09:49:29\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-07 10:08:39\";}i:6;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"42\";s:10:\"controller\";s:7:\"Station\";s:9:\"menu_name\";s:7:\"Station\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"40\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"42\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-27 21:25:22\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-05 13:56:27\";}i:7;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"71\";s:10:\"controller\";s:4:\"User\";s:9:\"menu_name\";s:4:\"User\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"70\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"71\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-28 09:52:31\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-07 19:57:31\";}i:8;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"45\";s:10:\"controller\";s:14:\"InstrumentData\";s:9:\"menu_name\";s:10:\"Instrument\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"40\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"45\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-03-19 20:05:54\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-19 20:05:54\";}}roles_id|s:1:\"1\";list_region|a:2:{i:0;O:8:\"stdClass\":2:{s:9:\"site_name\";s:12:\"SPIB Ladongi\";s:9:\"logo_site\";s:15:\"logo_site_b.png\";}i:1;O:8:\"stdClass\":2:{s:9:\"site_name\";s:10:\"SPIB KUWIL\";s:9:\"logo_site\";s:15:\"logo_site_a.png\";}}foto|s:0:\"\";jabatan|s:5:\"Admin\";ap_id_user|s:2:\"21\";ap_password|s:40:\"59ceb14713bd8922704730471290f566467f7306\";ap_nama|s:10:\"Admin User\";'),
('30fcdd3a6dedc624ba620e7a3277ae127982d7f2',	'::1',	1713290251,	'__ci_last_regenerate|i:1713290251;menu|a:6:{i:0;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"10\";s:10:\"controller\";s:0:\"\";s:9:\"menu_name\";s:9:\"Dashboard\";s:4:\"icon\";s:15:\"ti ti-dashboard\";s:6:\"parent\";s:1:\"0\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"10\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:6:\"SYSTEM\";s:10:\"created_at\";s:19:\"2024-02-18 01:38:54\";s:10:\"updated_by\";s:6:\"SYSTEM\";s:10:\"updated_at\";s:19:\"2024-02-28 10:24:52\";}i:1;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"60\";s:10:\"controller\";s:4:\"Maps\";s:9:\"menu_name\";s:4:\"Maps\";s:4:\"icon\";s:9:\"ti ti-map\";s:6:\"parent\";s:1:\"0\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"60\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:6:\"SYSTEM\";s:10:\"created_at\";s:19:\"2024-02-18 01:38:54\";s:10:\"updated_by\";s:6:\"SYSTEM\";s:10:\"updated_at\";s:19:\"2024-02-28 10:24:52\";}i:2;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"30\";s:10:\"controller\";s:0:\"\";s:9:\"menu_name\";s:11:\"Master Data\";s:4:\"icon\";s:20:\"ti ti-clipboard-list\";s:6:\"parent\";s:1:\"0\";s:5:\"child\";s:1:\"1\";s:5:\"order\";s:2:\"30\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-28 09:39:43\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-02-28 10:25:06\";}i:3;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"40\";s:10:\"controller\";s:0:\"\";s:9:\"menu_name\";s:17:\"Region Management\";s:4:\"icon\";s:20:\"ti ti-3d-cube-sphere\";s:6:\"parent\";s:1:\"0\";s:5:\"child\";s:1:\"1\";s:5:\"order\";s:2:\"40\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-27 21:27:59\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-02-28 10:25:16\";}i:4;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"70\";s:10:\"controller\";s:0:\"\";s:9:\"menu_name\";s:15:\"User Management\";s:4:\"icon\";s:11:\"ti ti-users\";s:6:\"parent\";s:1:\"0\";s:5:\"child\";s:1:\"1\";s:5:\"order\";s:2:\"70\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-27 21:22:59\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-02-28 10:25:32\";}i:5;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"80\";s:10:\"controller\";s:4:\"Site\";s:9:\"menu_name\";s:4:\"Site\";s:4:\"icon\";s:13:\"ti ti-map-pin\";s:6:\"parent\";s:1:\"0\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"80\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-03-02 10:21:56\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-02 10:38:33\";}}submenu|a:9:{i:0;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"31\";s:10:\"controller\";s:4:\"Data\";s:9:\"menu_name\";s:4:\"Data\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"30\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"31\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:6:\"SYSTEM\";s:10:\"created_at\";s:19:\"2024-02-18 01:38:54\";s:10:\"updated_by\";s:6:\"SYSTEM\";s:10:\"updated_at\";s:19:\"2024-04-04 16:30:04\";}i:1;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"32\";s:10:\"controller\";s:7:\"Dokumen\";s:9:\"menu_name\";s:13:\"Documentation\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"30\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"32\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-28 09:47:04\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-02 13:15:48\";}i:2;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"43\";s:10:\"controller\";s:10:\"Instrument\";s:9:\"menu_name\";s:15:\"Instrument Type\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"40\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"43\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-27 21:25:42\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-19 20:05:05\";}i:3;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"41\";s:10:\"controller\";s:6:\"Region\";s:9:\"menu_name\";s:6:\"Region\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"40\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"41\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-28 09:48:31\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-02-28 10:24:52\";}i:4;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"72\";s:10:\"controller\";s:4:\"Role\";s:9:\"menu_name\";s:15:\"Role Management\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"70\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"72\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-27 21:23:34\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-02-28 10:24:52\";}i:5;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"44\";s:10:\"controller\";s:6:\"Sensor\";s:9:\"menu_name\";s:6:\"Sensor\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"40\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"44\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-28 09:49:29\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-07 10:08:39\";}i:6;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"42\";s:10:\"controller\";s:7:\"Station\";s:9:\"menu_name\";s:7:\"Station\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"40\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"42\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-27 21:25:22\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-05 13:56:27\";}i:7;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"71\";s:10:\"controller\";s:4:\"User\";s:9:\"menu_name\";s:4:\"User\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"70\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"71\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-28 09:52:31\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-07 19:57:31\";}i:8;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"45\";s:10:\"controller\";s:14:\"InstrumentData\";s:9:\"menu_name\";s:10:\"Instrument\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"40\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"45\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-03-19 20:05:54\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-19 20:05:54\";}}roles_id|s:1:\"1\";list_region|a:2:{i:0;O:8:\"stdClass\":2:{s:9:\"site_name\";s:12:\"SPIB Ladongi\";s:9:\"logo_site\";s:15:\"logo_site_b.png\";}i:1;O:8:\"stdClass\":2:{s:9:\"site_name\";s:10:\"SPIB KUWIL\";s:9:\"logo_site\";s:15:\"logo_site_a.png\";}}foto|s:0:\"\";jabatan|s:5:\"Admin\";ap_id_user|s:2:\"21\";ap_password|s:40:\"59ceb14713bd8922704730471290f566467f7306\";ap_nama|s:10:\"Admin User\";'),
('14ac54b803b60a0781d410d54db3757631e07e95',	'::1',	1713290554,	'__ci_last_regenerate|i:1713290554;menu|a:6:{i:0;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"10\";s:10:\"controller\";s:0:\"\";s:9:\"menu_name\";s:9:\"Dashboard\";s:4:\"icon\";s:15:\"ti ti-dashboard\";s:6:\"parent\";s:1:\"0\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"10\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:6:\"SYSTEM\";s:10:\"created_at\";s:19:\"2024-02-18 01:38:54\";s:10:\"updated_by\";s:6:\"SYSTEM\";s:10:\"updated_at\";s:19:\"2024-02-28 10:24:52\";}i:1;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"60\";s:10:\"controller\";s:4:\"Maps\";s:9:\"menu_name\";s:4:\"Maps\";s:4:\"icon\";s:9:\"ti ti-map\";s:6:\"parent\";s:1:\"0\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"60\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:6:\"SYSTEM\";s:10:\"created_at\";s:19:\"2024-02-18 01:38:54\";s:10:\"updated_by\";s:6:\"SYSTEM\";s:10:\"updated_at\";s:19:\"2024-02-28 10:24:52\";}i:2;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"30\";s:10:\"controller\";s:0:\"\";s:9:\"menu_name\";s:11:\"Master Data\";s:4:\"icon\";s:20:\"ti ti-clipboard-list\";s:6:\"parent\";s:1:\"0\";s:5:\"child\";s:1:\"1\";s:5:\"order\";s:2:\"30\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-28 09:39:43\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-02-28 10:25:06\";}i:3;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"40\";s:10:\"controller\";s:0:\"\";s:9:\"menu_name\";s:17:\"Region Management\";s:4:\"icon\";s:20:\"ti ti-3d-cube-sphere\";s:6:\"parent\";s:1:\"0\";s:5:\"child\";s:1:\"1\";s:5:\"order\";s:2:\"40\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-27 21:27:59\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-02-28 10:25:16\";}i:4;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"70\";s:10:\"controller\";s:0:\"\";s:9:\"menu_name\";s:15:\"User Management\";s:4:\"icon\";s:11:\"ti ti-users\";s:6:\"parent\";s:1:\"0\";s:5:\"child\";s:1:\"1\";s:5:\"order\";s:2:\"70\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-27 21:22:59\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-02-28 10:25:32\";}i:5;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"80\";s:10:\"controller\";s:4:\"Site\";s:9:\"menu_name\";s:4:\"Site\";s:4:\"icon\";s:13:\"ti ti-map-pin\";s:6:\"parent\";s:1:\"0\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"80\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-03-02 10:21:56\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-02 10:38:33\";}}submenu|a:9:{i:0;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"31\";s:10:\"controller\";s:4:\"Data\";s:9:\"menu_name\";s:4:\"Data\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"30\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"31\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:6:\"SYSTEM\";s:10:\"created_at\";s:19:\"2024-02-18 01:38:54\";s:10:\"updated_by\";s:6:\"SYSTEM\";s:10:\"updated_at\";s:19:\"2024-04-04 16:30:04\";}i:1;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"32\";s:10:\"controller\";s:7:\"Dokumen\";s:9:\"menu_name\";s:13:\"Documentation\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"30\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"32\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-28 09:47:04\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-02 13:15:48\";}i:2;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"43\";s:10:\"controller\";s:10:\"Instrument\";s:9:\"menu_name\";s:15:\"Instrument Type\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"40\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"43\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-27 21:25:42\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-19 20:05:05\";}i:3;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"41\";s:10:\"controller\";s:6:\"Region\";s:9:\"menu_name\";s:6:\"Region\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"40\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"41\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-28 09:48:31\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-02-28 10:24:52\";}i:4;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"72\";s:10:\"controller\";s:4:\"Role\";s:9:\"menu_name\";s:15:\"Role Management\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"70\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"72\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-27 21:23:34\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-02-28 10:24:52\";}i:5;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"44\";s:10:\"controller\";s:6:\"Sensor\";s:9:\"menu_name\";s:6:\"Sensor\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"40\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"44\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-28 09:49:29\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-07 10:08:39\";}i:6;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"42\";s:10:\"controller\";s:7:\"Station\";s:9:\"menu_name\";s:7:\"Station\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"40\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"42\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-27 21:25:22\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-05 13:56:27\";}i:7;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"71\";s:10:\"controller\";s:4:\"User\";s:9:\"menu_name\";s:4:\"User\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"70\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"71\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-28 09:52:31\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-07 19:57:31\";}i:8;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"45\";s:10:\"controller\";s:14:\"InstrumentData\";s:9:\"menu_name\";s:10:\"Instrument\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"40\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"45\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-03-19 20:05:54\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-19 20:05:54\";}}roles_id|s:1:\"1\";list_region|a:2:{i:0;O:8:\"stdClass\":2:{s:9:\"site_name\";s:12:\"SPIB Ladongi\";s:9:\"logo_site\";s:15:\"logo_site_b.png\";}i:1;O:8:\"stdClass\":2:{s:9:\"site_name\";s:10:\"SPIB KUWIL\";s:9:\"logo_site\";s:15:\"logo_site_a.png\";}}foto|s:0:\"\";jabatan|s:5:\"Admin\";ap_id_user|s:2:\"21\";ap_password|s:40:\"59ceb14713bd8922704730471290f566467f7306\";ap_nama|s:10:\"Admin User\";'),
('4e05c0d54cc2e3505fdc0aeba50abb36cb6730f0',	'::1',	1713291175,	'__ci_last_regenerate|i:1713291175;menu|a:6:{i:0;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"10\";s:10:\"controller\";s:0:\"\";s:9:\"menu_name\";s:9:\"Dashboard\";s:4:\"icon\";s:15:\"ti ti-dashboard\";s:6:\"parent\";s:1:\"0\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"10\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:6:\"SYSTEM\";s:10:\"created_at\";s:19:\"2024-02-18 01:38:54\";s:10:\"updated_by\";s:6:\"SYSTEM\";s:10:\"updated_at\";s:19:\"2024-02-28 10:24:52\";}i:1;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"60\";s:10:\"controller\";s:4:\"Maps\";s:9:\"menu_name\";s:4:\"Maps\";s:4:\"icon\";s:9:\"ti ti-map\";s:6:\"parent\";s:1:\"0\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"60\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:6:\"SYSTEM\";s:10:\"created_at\";s:19:\"2024-02-18 01:38:54\";s:10:\"updated_by\";s:6:\"SYSTEM\";s:10:\"updated_at\";s:19:\"2024-02-28 10:24:52\";}i:2;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"30\";s:10:\"controller\";s:0:\"\";s:9:\"menu_name\";s:11:\"Master Data\";s:4:\"icon\";s:20:\"ti ti-clipboard-list\";s:6:\"parent\";s:1:\"0\";s:5:\"child\";s:1:\"1\";s:5:\"order\";s:2:\"30\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-28 09:39:43\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-02-28 10:25:06\";}i:3;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"40\";s:10:\"controller\";s:0:\"\";s:9:\"menu_name\";s:17:\"Region Management\";s:4:\"icon\";s:20:\"ti ti-3d-cube-sphere\";s:6:\"parent\";s:1:\"0\";s:5:\"child\";s:1:\"1\";s:5:\"order\";s:2:\"40\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-27 21:27:59\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-02-28 10:25:16\";}i:4;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"70\";s:10:\"controller\";s:0:\"\";s:9:\"menu_name\";s:15:\"User Management\";s:4:\"icon\";s:11:\"ti ti-users\";s:6:\"parent\";s:1:\"0\";s:5:\"child\";s:1:\"1\";s:5:\"order\";s:2:\"70\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-27 21:22:59\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-02-28 10:25:32\";}i:5;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"80\";s:10:\"controller\";s:4:\"Site\";s:9:\"menu_name\";s:4:\"Site\";s:4:\"icon\";s:13:\"ti ti-map-pin\";s:6:\"parent\";s:1:\"0\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"80\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-03-02 10:21:56\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-02 10:38:33\";}}submenu|a:9:{i:0;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"31\";s:10:\"controller\";s:4:\"Data\";s:9:\"menu_name\";s:4:\"Data\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"30\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"31\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:6:\"SYSTEM\";s:10:\"created_at\";s:19:\"2024-02-18 01:38:54\";s:10:\"updated_by\";s:6:\"SYSTEM\";s:10:\"updated_at\";s:19:\"2024-04-04 16:30:04\";}i:1;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"32\";s:10:\"controller\";s:7:\"Dokumen\";s:9:\"menu_name\";s:13:\"Documentation\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"30\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"32\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-28 09:47:04\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-02 13:15:48\";}i:2;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"43\";s:10:\"controller\";s:10:\"Instrument\";s:9:\"menu_name\";s:15:\"Instrument Type\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"40\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"43\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-27 21:25:42\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-19 20:05:05\";}i:3;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"41\";s:10:\"controller\";s:6:\"Region\";s:9:\"menu_name\";s:6:\"Region\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"40\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"41\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-28 09:48:31\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-02-28 10:24:52\";}i:4;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"72\";s:10:\"controller\";s:4:\"Role\";s:9:\"menu_name\";s:15:\"Role Management\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"70\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"72\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-27 21:23:34\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-02-28 10:24:52\";}i:5;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"44\";s:10:\"controller\";s:6:\"Sensor\";s:9:\"menu_name\";s:6:\"Sensor\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"40\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"44\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-28 09:49:29\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-07 10:08:39\";}i:6;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"42\";s:10:\"controller\";s:7:\"Station\";s:9:\"menu_name\";s:7:\"Station\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"40\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"42\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-27 21:25:22\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-05 13:56:27\";}i:7;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"71\";s:10:\"controller\";s:4:\"User\";s:9:\"menu_name\";s:4:\"User\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"70\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"71\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-28 09:52:31\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-07 19:57:31\";}i:8;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"45\";s:10:\"controller\";s:14:\"InstrumentData\";s:9:\"menu_name\";s:10:\"Instrument\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"40\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"45\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-03-19 20:05:54\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-19 20:05:54\";}}roles_id|s:1:\"1\";list_region|a:2:{i:0;O:8:\"stdClass\":2:{s:9:\"site_name\";s:12:\"SPIB Ladongi\";s:9:\"logo_site\";s:15:\"logo_site_b.png\";}i:1;O:8:\"stdClass\":2:{s:9:\"site_name\";s:10:\"SPIB KUWIL\";s:9:\"logo_site\";s:15:\"logo_site_a.png\";}}foto|s:0:\"\";jabatan|s:5:\"Admin\";ap_id_user|s:2:\"21\";ap_password|s:40:\"59ceb14713bd8922704730471290f566467f7306\";ap_nama|s:10:\"Admin User\";'),
('4443f85828afae84c8d5cc60b09258ea5874470b',	'::1',	1713292243,	'__ci_last_regenerate|i:1713292243;menu|a:6:{i:0;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"10\";s:10:\"controller\";s:0:\"\";s:9:\"menu_name\";s:9:\"Dashboard\";s:4:\"icon\";s:15:\"ti ti-dashboard\";s:6:\"parent\";s:1:\"0\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"10\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:6:\"SYSTEM\";s:10:\"created_at\";s:19:\"2024-02-18 01:38:54\";s:10:\"updated_by\";s:6:\"SYSTEM\";s:10:\"updated_at\";s:19:\"2024-02-28 10:24:52\";}i:1;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"60\";s:10:\"controller\";s:4:\"Maps\";s:9:\"menu_name\";s:4:\"Maps\";s:4:\"icon\";s:9:\"ti ti-map\";s:6:\"parent\";s:1:\"0\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"60\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:6:\"SYSTEM\";s:10:\"created_at\";s:19:\"2024-02-18 01:38:54\";s:10:\"updated_by\";s:6:\"SYSTEM\";s:10:\"updated_at\";s:19:\"2024-02-28 10:24:52\";}i:2;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"30\";s:10:\"controller\";s:0:\"\";s:9:\"menu_name\";s:11:\"Master Data\";s:4:\"icon\";s:20:\"ti ti-clipboard-list\";s:6:\"parent\";s:1:\"0\";s:5:\"child\";s:1:\"1\";s:5:\"order\";s:2:\"30\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-28 09:39:43\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-02-28 10:25:06\";}i:3;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"40\";s:10:\"controller\";s:0:\"\";s:9:\"menu_name\";s:17:\"Region Management\";s:4:\"icon\";s:20:\"ti ti-3d-cube-sphere\";s:6:\"parent\";s:1:\"0\";s:5:\"child\";s:1:\"1\";s:5:\"order\";s:2:\"40\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-27 21:27:59\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-02-28 10:25:16\";}i:4;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"70\";s:10:\"controller\";s:0:\"\";s:9:\"menu_name\";s:15:\"User Management\";s:4:\"icon\";s:11:\"ti ti-users\";s:6:\"parent\";s:1:\"0\";s:5:\"child\";s:1:\"1\";s:5:\"order\";s:2:\"70\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-27 21:22:59\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-02-28 10:25:32\";}i:5;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"80\";s:10:\"controller\";s:4:\"Site\";s:9:\"menu_name\";s:4:\"Site\";s:4:\"icon\";s:13:\"ti ti-map-pin\";s:6:\"parent\";s:1:\"0\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"80\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-03-02 10:21:56\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-02 10:38:33\";}}submenu|a:9:{i:0;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"31\";s:10:\"controller\";s:4:\"Data\";s:9:\"menu_name\";s:4:\"Data\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"30\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"31\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:6:\"SYSTEM\";s:10:\"created_at\";s:19:\"2024-02-18 01:38:54\";s:10:\"updated_by\";s:6:\"SYSTEM\";s:10:\"updated_at\";s:19:\"2024-04-04 16:30:04\";}i:1;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"32\";s:10:\"controller\";s:7:\"Dokumen\";s:9:\"menu_name\";s:13:\"Documentation\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"30\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"32\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-28 09:47:04\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-02 13:15:48\";}i:2;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"43\";s:10:\"controller\";s:10:\"Instrument\";s:9:\"menu_name\";s:15:\"Instrument Type\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"40\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"43\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-27 21:25:42\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-19 20:05:05\";}i:3;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"41\";s:10:\"controller\";s:6:\"Region\";s:9:\"menu_name\";s:6:\"Region\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"40\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"41\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-28 09:48:31\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-02-28 10:24:52\";}i:4;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"72\";s:10:\"controller\";s:4:\"Role\";s:9:\"menu_name\";s:15:\"Role Management\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"70\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"72\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-27 21:23:34\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-02-28 10:24:52\";}i:5;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"44\";s:10:\"controller\";s:6:\"Sensor\";s:9:\"menu_name\";s:6:\"Sensor\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"40\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"44\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-28 09:49:29\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-07 10:08:39\";}i:6;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"42\";s:10:\"controller\";s:7:\"Station\";s:9:\"menu_name\";s:7:\"Station\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"40\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"42\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-27 21:25:22\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-05 13:56:27\";}i:7;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"71\";s:10:\"controller\";s:4:\"User\";s:9:\"menu_name\";s:4:\"User\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"70\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"71\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-28 09:52:31\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-07 19:57:31\";}i:8;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"45\";s:10:\"controller\";s:14:\"InstrumentData\";s:9:\"menu_name\";s:10:\"Instrument\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"40\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"45\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-03-19 20:05:54\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-19 20:05:54\";}}roles_id|s:1:\"1\";list_region|a:2:{i:0;O:8:\"stdClass\":2:{s:9:\"site_name\";s:12:\"SPIB Ladongi\";s:9:\"logo_site\";s:15:\"logo_site_b.png\";}i:1;O:8:\"stdClass\":2:{s:9:\"site_name\";s:10:\"SPIB KUWIL\";s:9:\"logo_site\";s:15:\"logo_site_a.png\";}}foto|s:0:\"\";jabatan|s:5:\"Admin\";ap_id_user|s:2:\"21\";ap_password|s:40:\"59ceb14713bd8922704730471290f566467f7306\";ap_nama|s:10:\"Admin User\";'),
('9407446b41e3c706584c98445c61263162845cf5',	'::1',	1713292713,	'__ci_last_regenerate|i:1713292713;menu|a:6:{i:0;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"10\";s:10:\"controller\";s:0:\"\";s:9:\"menu_name\";s:9:\"Dashboard\";s:4:\"icon\";s:15:\"ti ti-dashboard\";s:6:\"parent\";s:1:\"0\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"10\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:6:\"SYSTEM\";s:10:\"created_at\";s:19:\"2024-02-18 01:38:54\";s:10:\"updated_by\";s:6:\"SYSTEM\";s:10:\"updated_at\";s:19:\"2024-02-28 10:24:52\";}i:1;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"60\";s:10:\"controller\";s:4:\"Maps\";s:9:\"menu_name\";s:4:\"Maps\";s:4:\"icon\";s:9:\"ti ti-map\";s:6:\"parent\";s:1:\"0\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"60\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:6:\"SYSTEM\";s:10:\"created_at\";s:19:\"2024-02-18 01:38:54\";s:10:\"updated_by\";s:6:\"SYSTEM\";s:10:\"updated_at\";s:19:\"2024-02-28 10:24:52\";}i:2;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"30\";s:10:\"controller\";s:0:\"\";s:9:\"menu_name\";s:11:\"Master Data\";s:4:\"icon\";s:20:\"ti ti-clipboard-list\";s:6:\"parent\";s:1:\"0\";s:5:\"child\";s:1:\"1\";s:5:\"order\";s:2:\"30\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-28 09:39:43\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-02-28 10:25:06\";}i:3;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"40\";s:10:\"controller\";s:0:\"\";s:9:\"menu_name\";s:17:\"Region Management\";s:4:\"icon\";s:20:\"ti ti-3d-cube-sphere\";s:6:\"parent\";s:1:\"0\";s:5:\"child\";s:1:\"1\";s:5:\"order\";s:2:\"40\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-27 21:27:59\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-02-28 10:25:16\";}i:4;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"70\";s:10:\"controller\";s:0:\"\";s:9:\"menu_name\";s:15:\"User Management\";s:4:\"icon\";s:11:\"ti ti-users\";s:6:\"parent\";s:1:\"0\";s:5:\"child\";s:1:\"1\";s:5:\"order\";s:2:\"70\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-27 21:22:59\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-02-28 10:25:32\";}i:5;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"80\";s:10:\"controller\";s:4:\"Site\";s:9:\"menu_name\";s:4:\"Site\";s:4:\"icon\";s:13:\"ti ti-map-pin\";s:6:\"parent\";s:1:\"0\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"80\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-03-02 10:21:56\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-02 10:38:33\";}}submenu|a:9:{i:0;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"31\";s:10:\"controller\";s:4:\"Data\";s:9:\"menu_name\";s:4:\"Data\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"30\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"31\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:6:\"SYSTEM\";s:10:\"created_at\";s:19:\"2024-02-18 01:38:54\";s:10:\"updated_by\";s:6:\"SYSTEM\";s:10:\"updated_at\";s:19:\"2024-04-04 16:30:04\";}i:1;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"32\";s:10:\"controller\";s:7:\"Dokumen\";s:9:\"menu_name\";s:13:\"Documentation\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"30\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"32\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-28 09:47:04\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-02 13:15:48\";}i:2;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"43\";s:10:\"controller\";s:10:\"Instrument\";s:9:\"menu_name\";s:15:\"Instrument Type\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"40\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"43\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-27 21:25:42\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-19 20:05:05\";}i:3;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"41\";s:10:\"controller\";s:6:\"Region\";s:9:\"menu_name\";s:6:\"Region\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"40\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"41\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-28 09:48:31\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-02-28 10:24:52\";}i:4;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"72\";s:10:\"controller\";s:4:\"Role\";s:9:\"menu_name\";s:15:\"Role Management\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"70\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"72\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-27 21:23:34\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-02-28 10:24:52\";}i:5;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"44\";s:10:\"controller\";s:6:\"Sensor\";s:9:\"menu_name\";s:6:\"Sensor\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"40\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"44\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-28 09:49:29\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-07 10:08:39\";}i:6;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"42\";s:10:\"controller\";s:7:\"Station\";s:9:\"menu_name\";s:7:\"Station\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"40\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"42\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-27 21:25:22\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-05 13:56:27\";}i:7;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"71\";s:10:\"controller\";s:4:\"User\";s:9:\"menu_name\";s:4:\"User\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"70\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"71\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-28 09:52:31\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-07 19:57:31\";}i:8;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"45\";s:10:\"controller\";s:14:\"InstrumentData\";s:9:\"menu_name\";s:10:\"Instrument\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"40\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"45\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-03-19 20:05:54\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-19 20:05:54\";}}roles_id|s:1:\"1\";list_region|a:2:{i:0;O:8:\"stdClass\":2:{s:9:\"site_name\";s:12:\"SPIB Ladongi\";s:9:\"logo_site\";s:15:\"logo_site_b.png\";}i:1;O:8:\"stdClass\":2:{s:9:\"site_name\";s:10:\"SPIB KUWIL\";s:9:\"logo_site\";s:15:\"logo_site_a.png\";}}foto|s:0:\"\";jabatan|s:5:\"Admin\";ap_id_user|s:2:\"21\";ap_password|s:40:\"59ceb14713bd8922704730471290f566467f7306\";ap_nama|s:10:\"Admin User\";'),
('f90297d505613fcc4bad985da8f5a76bcc2da6ea',	'::1',	1713293041,	'__ci_last_regenerate|i:1713293041;menu|a:5:{i:0;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"10\";s:10:\"controller\";s:0:\"\";s:9:\"menu_name\";s:9:\"Dashboard\";s:4:\"icon\";s:15:\"ti ti-dashboard\";s:6:\"parent\";s:1:\"0\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"10\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:6:\"SYSTEM\";s:10:\"created_at\";s:19:\"2024-02-18 01:38:54\";s:10:\"updated_by\";s:6:\"SYSTEM\";s:10:\"updated_at\";s:19:\"2024-02-28 10:24:52\";}i:1;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"60\";s:10:\"controller\";s:4:\"Maps\";s:9:\"menu_name\";s:4:\"Maps\";s:4:\"icon\";s:9:\"ti ti-map\";s:6:\"parent\";s:1:\"0\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"60\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:6:\"SYSTEM\";s:10:\"created_at\";s:19:\"2024-02-18 01:38:54\";s:10:\"updated_by\";s:6:\"SYSTEM\";s:10:\"updated_at\";s:19:\"2024-02-28 10:24:52\";}i:2;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"30\";s:10:\"controller\";s:0:\"\";s:9:\"menu_name\";s:11:\"Master Data\";s:4:\"icon\";s:20:\"ti ti-clipboard-list\";s:6:\"parent\";s:1:\"0\";s:5:\"child\";s:1:\"1\";s:5:\"order\";s:2:\"30\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-28 09:39:43\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-02-28 10:25:06\";}i:3;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"40\";s:10:\"controller\";s:0:\"\";s:9:\"menu_name\";s:17:\"Region Management\";s:4:\"icon\";s:20:\"ti ti-3d-cube-sphere\";s:6:\"parent\";s:1:\"0\";s:5:\"child\";s:1:\"1\";s:5:\"order\";s:2:\"40\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-27 21:27:59\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-02-28 10:25:16\";}i:4;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"70\";s:10:\"controller\";s:0:\"\";s:9:\"menu_name\";s:15:\"User Management\";s:4:\"icon\";s:11:\"ti ti-users\";s:6:\"parent\";s:1:\"0\";s:5:\"child\";s:1:\"1\";s:5:\"order\";s:2:\"70\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-27 21:22:59\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-02-28 10:25:32\";}}submenu|a:10:{i:0;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"31\";s:10:\"controller\";s:4:\"Data\";s:9:\"menu_name\";s:4:\"Data\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"30\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"31\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:6:\"SYSTEM\";s:10:\"created_at\";s:19:\"2024-02-18 01:38:54\";s:10:\"updated_by\";s:6:\"SYSTEM\";s:10:\"updated_at\";s:19:\"2024-04-04 16:30:04\";}i:1;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"32\";s:10:\"controller\";s:7:\"Dokumen\";s:9:\"menu_name\";s:13:\"Documentation\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"30\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"32\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-28 09:47:04\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-02 13:15:48\";}i:2;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"43\";s:10:\"controller\";s:10:\"Instrument\";s:9:\"menu_name\";s:15:\"Instrument Type\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"40\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"43\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-27 21:25:42\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-19 20:05:05\";}i:3;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"41\";s:10:\"controller\";s:6:\"Region\";s:9:\"menu_name\";s:6:\"Region\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"40\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"41\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-28 09:48:31\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-02-28 10:24:52\";}i:4;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"72\";s:10:\"controller\";s:4:\"Role\";s:9:\"menu_name\";s:15:\"Role Management\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"70\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"72\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-27 21:23:34\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-02-28 10:24:52\";}i:5;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"44\";s:10:\"controller\";s:6:\"Sensor\";s:9:\"menu_name\";s:6:\"Sensor\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"40\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"44\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-28 09:49:29\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-07 10:08:39\";}i:6;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"42\";s:10:\"controller\";s:7:\"Station\";s:9:\"menu_name\";s:7:\"Station\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"40\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"42\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-27 21:25:22\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-05 13:56:27\";}i:7;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"71\";s:10:\"controller\";s:4:\"User\";s:9:\"menu_name\";s:4:\"User\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"70\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"71\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-28 09:52:31\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-07 19:57:31\";}i:8;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"80\";s:10:\"controller\";s:4:\"Site\";s:9:\"menu_name\";s:4:\"Site\";s:4:\"icon\";s:13:\"ti ti-map-pin\";s:6:\"parent\";s:2:\"40\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"80\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-03-02 10:21:56\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-02 10:38:33\";}i:9;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"45\";s:10:\"controller\";s:14:\"InstrumentData\";s:9:\"menu_name\";s:10:\"Instrument\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"40\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"45\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-03-19 20:05:54\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-19 20:05:54\";}}roles_id|s:1:\"1\";list_region|a:2:{i:0;O:8:\"stdClass\":2:{s:9:\"site_name\";s:12:\"SPIB Ladongi\";s:9:\"logo_site\";s:15:\"logo_site_b.png\";}i:1;O:8:\"stdClass\":2:{s:9:\"site_name\";s:10:\"SPIB KUWIL\";s:9:\"logo_site\";s:15:\"logo_site_a.png\";}}foto|s:0:\"\";jabatan|s:5:\"Admin\";ap_id_user|s:2:\"21\";ap_password|s:40:\"59ceb14713bd8922704730471290f566467f7306\";ap_nama|s:10:\"Admin User\";'),
('707a1b0885102dcb4d4323c64430c9eeb47504f7',	'::1',	1713293394,	'__ci_last_regenerate|i:1713293394;menu|a:5:{i:0;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"10\";s:10:\"controller\";s:0:\"\";s:9:\"menu_name\";s:9:\"Dashboard\";s:4:\"icon\";s:15:\"ti ti-dashboard\";s:6:\"parent\";s:1:\"0\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"10\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:6:\"SYSTEM\";s:10:\"created_at\";s:19:\"2024-02-18 01:38:54\";s:10:\"updated_by\";s:6:\"SYSTEM\";s:10:\"updated_at\";s:19:\"2024-02-28 10:24:52\";}i:1;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"60\";s:10:\"controller\";s:4:\"Maps\";s:9:\"menu_name\";s:4:\"Maps\";s:4:\"icon\";s:9:\"ti ti-map\";s:6:\"parent\";s:1:\"0\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"60\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:6:\"SYSTEM\";s:10:\"created_at\";s:19:\"2024-02-18 01:38:54\";s:10:\"updated_by\";s:6:\"SYSTEM\";s:10:\"updated_at\";s:19:\"2024-02-28 10:24:52\";}i:2;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"30\";s:10:\"controller\";s:0:\"\";s:9:\"menu_name\";s:11:\"Master Data\";s:4:\"icon\";s:20:\"ti ti-clipboard-list\";s:6:\"parent\";s:1:\"0\";s:5:\"child\";s:1:\"1\";s:5:\"order\";s:2:\"30\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-28 09:39:43\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-02-28 10:25:06\";}i:3;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"40\";s:10:\"controller\";s:0:\"\";s:9:\"menu_name\";s:17:\"Region Management\";s:4:\"icon\";s:20:\"ti ti-3d-cube-sphere\";s:6:\"parent\";s:1:\"0\";s:5:\"child\";s:1:\"1\";s:5:\"order\";s:2:\"40\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-27 21:27:59\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-02-28 10:25:16\";}i:4;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"70\";s:10:\"controller\";s:0:\"\";s:9:\"menu_name\";s:15:\"User Management\";s:4:\"icon\";s:11:\"ti ti-users\";s:6:\"parent\";s:1:\"0\";s:5:\"child\";s:1:\"1\";s:5:\"order\";s:2:\"70\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-27 21:22:59\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-02-28 10:25:32\";}}submenu|a:10:{i:0;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"31\";s:10:\"controller\";s:4:\"Data\";s:9:\"menu_name\";s:4:\"Data\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"30\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"31\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:6:\"SYSTEM\";s:10:\"created_at\";s:19:\"2024-02-18 01:38:54\";s:10:\"updated_by\";s:6:\"SYSTEM\";s:10:\"updated_at\";s:19:\"2024-04-04 16:30:04\";}i:1;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"32\";s:10:\"controller\";s:7:\"Dokumen\";s:9:\"menu_name\";s:13:\"Documentation\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"30\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"32\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-28 09:47:04\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-02 13:15:48\";}i:2;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"43\";s:10:\"controller\";s:10:\"Instrument\";s:9:\"menu_name\";s:15:\"Instrument Type\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"40\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"43\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-27 21:25:42\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-19 20:05:05\";}i:3;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"41\";s:10:\"controller\";s:6:\"Region\";s:9:\"menu_name\";s:6:\"Region\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"40\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"41\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-28 09:48:31\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-02-28 10:24:52\";}i:4;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"72\";s:10:\"controller\";s:4:\"Role\";s:9:\"menu_name\";s:15:\"Role Management\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"70\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"72\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-27 21:23:34\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-02-28 10:24:52\";}i:5;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"44\";s:10:\"controller\";s:6:\"Sensor\";s:9:\"menu_name\";s:6:\"Sensor\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"40\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"44\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-28 09:49:29\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-07 10:08:39\";}i:6;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"42\";s:10:\"controller\";s:7:\"Station\";s:9:\"menu_name\";s:7:\"Station\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"40\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"42\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-27 21:25:22\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-05 13:56:27\";}i:7;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"71\";s:10:\"controller\";s:4:\"User\";s:9:\"menu_name\";s:4:\"User\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"70\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"71\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-28 09:52:31\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-07 19:57:31\";}i:8;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"80\";s:10:\"controller\";s:4:\"Site\";s:9:\"menu_name\";s:4:\"Site\";s:4:\"icon\";s:13:\"ti ti-map-pin\";s:6:\"parent\";s:2:\"40\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"80\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-03-02 10:21:56\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-02 10:38:33\";}i:9;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"45\";s:10:\"controller\";s:14:\"InstrumentData\";s:9:\"menu_name\";s:10:\"Instrument\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"40\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"45\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-03-19 20:05:54\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-19 20:05:54\";}}roles_id|s:1:\"1\";list_region|a:2:{i:0;O:8:\"stdClass\":2:{s:9:\"site_name\";s:12:\"SPIB Ladongi\";s:9:\"logo_site\";s:15:\"logo_site_b.png\";}i:1;O:8:\"stdClass\":2:{s:9:\"site_name\";s:10:\"SPIB KUWIL\";s:9:\"logo_site\";s:15:\"logo_site_a.png\";}}foto|s:0:\"\";jabatan|s:5:\"Admin\";ap_id_user|s:2:\"21\";ap_password|s:40:\"59ceb14713bd8922704730471290f566467f7306\";ap_nama|s:10:\"Admin User\";'),
('5295ae9741020af99088da8572d73f06da01a91b',	'::1',	1713293405,	'__ci_last_regenerate|i:1713293394;menu|a:5:{i:0;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"10\";s:10:\"controller\";s:0:\"\";s:9:\"menu_name\";s:9:\"Dashboard\";s:4:\"icon\";s:15:\"ti ti-dashboard\";s:6:\"parent\";s:1:\"0\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"10\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:6:\"SYSTEM\";s:10:\"created_at\";s:19:\"2024-02-18 01:38:54\";s:10:\"updated_by\";s:6:\"SYSTEM\";s:10:\"updated_at\";s:19:\"2024-02-28 10:24:52\";}i:1;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"60\";s:10:\"controller\";s:4:\"Maps\";s:9:\"menu_name\";s:4:\"Maps\";s:4:\"icon\";s:9:\"ti ti-map\";s:6:\"parent\";s:1:\"0\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"60\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:6:\"SYSTEM\";s:10:\"created_at\";s:19:\"2024-02-18 01:38:54\";s:10:\"updated_by\";s:6:\"SYSTEM\";s:10:\"updated_at\";s:19:\"2024-02-28 10:24:52\";}i:2;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"30\";s:10:\"controller\";s:0:\"\";s:9:\"menu_name\";s:11:\"Master Data\";s:4:\"icon\";s:20:\"ti ti-clipboard-list\";s:6:\"parent\";s:1:\"0\";s:5:\"child\";s:1:\"1\";s:5:\"order\";s:2:\"30\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-28 09:39:43\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-02-28 10:25:06\";}i:3;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"40\";s:10:\"controller\";s:0:\"\";s:9:\"menu_name\";s:17:\"Region Management\";s:4:\"icon\";s:20:\"ti ti-3d-cube-sphere\";s:6:\"parent\";s:1:\"0\";s:5:\"child\";s:1:\"1\";s:5:\"order\";s:2:\"40\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-27 21:27:59\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-02-28 10:25:16\";}i:4;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"70\";s:10:\"controller\";s:0:\"\";s:9:\"menu_name\";s:15:\"User Management\";s:4:\"icon\";s:11:\"ti ti-users\";s:6:\"parent\";s:1:\"0\";s:5:\"child\";s:1:\"1\";s:5:\"order\";s:2:\"70\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-27 21:22:59\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-02-28 10:25:32\";}}submenu|a:10:{i:0;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"31\";s:10:\"controller\";s:4:\"Data\";s:9:\"menu_name\";s:4:\"Data\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"30\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"31\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:6:\"SYSTEM\";s:10:\"created_at\";s:19:\"2024-02-18 01:38:54\";s:10:\"updated_by\";s:6:\"SYSTEM\";s:10:\"updated_at\";s:19:\"2024-04-04 16:30:04\";}i:1;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"32\";s:10:\"controller\";s:7:\"Dokumen\";s:9:\"menu_name\";s:13:\"Documentation\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"30\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"32\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-28 09:47:04\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-02 13:15:48\";}i:2;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"43\";s:10:\"controller\";s:10:\"Instrument\";s:9:\"menu_name\";s:15:\"Instrument Type\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"40\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"43\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-27 21:25:42\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-19 20:05:05\";}i:3;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"41\";s:10:\"controller\";s:6:\"Region\";s:9:\"menu_name\";s:6:\"Region\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"40\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"41\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-28 09:48:31\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-02-28 10:24:52\";}i:4;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"72\";s:10:\"controller\";s:4:\"Role\";s:9:\"menu_name\";s:15:\"Role Management\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"70\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"72\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-27 21:23:34\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-02-28 10:24:52\";}i:5;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"44\";s:10:\"controller\";s:6:\"Sensor\";s:9:\"menu_name\";s:6:\"Sensor\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"40\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"44\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-28 09:49:29\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-07 10:08:39\";}i:6;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"42\";s:10:\"controller\";s:7:\"Station\";s:9:\"menu_name\";s:7:\"Station\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"40\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"42\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-27 21:25:22\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-05 13:56:27\";}i:7;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"71\";s:10:\"controller\";s:4:\"User\";s:9:\"menu_name\";s:4:\"User\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"70\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"71\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-02-28 09:52:31\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-07 19:57:31\";}i:8;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"80\";s:10:\"controller\";s:4:\"Site\";s:9:\"menu_name\";s:4:\"Site\";s:4:\"icon\";s:13:\"ti ti-map-pin\";s:6:\"parent\";s:2:\"40\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"80\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-03-02 10:21:56\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-02 10:38:33\";}i:9;O:8:\"stdClass\":12:{s:2:\"id\";s:2:\"45\";s:10:\"controller\";s:14:\"InstrumentData\";s:9:\"menu_name\";s:10:\"Instrument\";s:4:\"icon\";s:0:\"\";s:6:\"parent\";s:2:\"40\";s:5:\"child\";s:1:\"0\";s:5:\"order\";s:2:\"45\";s:6:\"status\";s:1:\"1\";s:10:\"created_by\";s:0:\"\";s:10:\"created_at\";s:19:\"2024-03-19 20:05:54\";s:10:\"updated_by\";s:0:\"\";s:10:\"updated_at\";s:19:\"2024-03-19 20:05:54\";}}roles_id|s:1:\"1\";list_region|a:2:{i:0;O:8:\"stdClass\":2:{s:9:\"site_name\";s:12:\"SPIB Ladongi\";s:9:\"logo_site\";s:15:\"logo_site_b.png\";}i:1;O:8:\"stdClass\":2:{s:9:\"site_name\";s:10:\"SPIB KUWIL\";s:9:\"logo_site\";s:15:\"logo_site_a.png\";}}foto|s:0:\"\";jabatan|s:5:\"Admin\";ap_id_user|s:2:\"21\";ap_password|s:40:\"59ceb14713bd8922704730471290f566467f7306\";ap_nama|s:10:\"Admin User\";');

DROP TABLE IF EXISTS `sys_instruments`;
CREATE TABLE `sys_instruments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ms_regions_id` int NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `elevasi_batas_kritis` varchar(255) DEFAULT NULL,
  `elevasi_timbunan` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `sys_instruments` (`id`, `ms_regions_id`, `name`, `image`, `elevasi_batas_kritis`, `elevasi_timbunan`, `type`) VALUES
(1,	2,	'tes',	NULL,	'1',	'2',	NULL),
(5,	1,	'Site a',	NULL,	'111',	'123',	NULL);

DROP TABLE IF EXISTS `sys_instruments_files`;
CREATE TABLE `sys_instruments_files` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sys_instruments_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ms_documentation_files_ms_documentations_id_foreign` (`sys_instruments_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `sys_jenis_sensor`;
CREATE TABLE `sys_jenis_sensor` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ms_regions_id` int NOT NULL,
  `idch` int DEFAULT NULL,
  `jenis_sensor` varchar(255) DEFAULT NULL,
  `unit_sensor` varchar(255) DEFAULT NULL,
  `var_name` varchar(255) DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `formula` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `sys_jenis_sensor` (`id`, `ms_regions_id`, `idch`, `jenis_sensor`, `unit_sensor`, `var_name`, `icon`, `formula`) VALUES
(3,	1,	NULL,	'Frekuensi',	'Hz',	'm/s',	NULL,	NULL),
(4,	1,	NULL,	'Temperature',	'C',	'temp',	NULL,	NULL),
(5,	1,	NULL,	'Level Freatik Air',	'm',	'level_freatik_air',	NULL,	NULL),
(6,	1,	NULL,	'Rainfall',	'mm',	'arr',	NULL,	NULL),
(7,	1,	NULL,	'Wind Speed',	'm/s',	'wsp',	NULL,	NULL),
(8,	1,	NULL,	'Wind Direction',	'deg',	'wdr',	NULL,	NULL),
(9,	1,	NULL,	'Solar Radiation',	'W.M2',	'sol',	NULL,	NULL),
(10,	1,	NULL,	'Air Humidity',	'%',	'hum',	NULL,	NULL),
(11,	1,	NULL,	'Air Pressure',	'mbar',	'pres',	NULL,	NULL),
(12,	1,	NULL,	'Air Temperature',	'C',	'temp',	NULL,	NULL),
(13,	1,	NULL,	'Evaporation',	'mm',	'eva',	NULL,	NULL),
(14,	1,	NULL,	'Water Level',	'm',	'tma',	NULL,	NULL),
(15,	1,	NULL,	'Discharge',	'L/s',	'disc',	NULL,	NULL),
(16,	1,	NULL,	'Tinggi Air',	'm',	'h',	NULL,	NULL),
(17,	1,	NULL,	'Tinggi Air ',	'Cm',	'h',	NULL,	NULL),
(18,	1,	NULL,	'Pulse Counter',	'knock',	'count',	NULL,	NULL);

DROP TABLE IF EXISTS `temp_koefisien`;
CREATE TABLE `temp_koefisien` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ap_id_user` varchar(11) DEFAULT NULL,
  `tr_instrument_type_id` int DEFAULT NULL,
  `tmaw` varchar(20) DEFAULT NULL,
  `tmas` varchar(20) DEFAULT NULL,
  `parameter` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


DROP TABLE IF EXISTS `temp_sensor`;
CREATE TABLE `temp_sensor` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_temp_koefisien` int DEFAULT NULL,
  `jenis_sensor` int DEFAULT NULL,
  `jenis_sensor_mentah` int DEFAULT NULL,
  `jenis_sensor_jadi` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


DROP TABLE IF EXISTS `tr_instrument`;
CREATE TABLE `tr_instrument` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ms_regions_id` bigint unsigned NOT NULL,
  `kode_instrument` varchar(255) DEFAULT NULL,
  `nama_instrument` varchar(255) DEFAULT NULL,
  `tr_instrument_type_id` int DEFAULT NULL,
  `ms_stasiun_id` int DEFAULT NULL,
  `tahun_pembuatan` year DEFAULT NULL,
  `tr_instrument_sensor_id` int DEFAULT NULL,
  `latitude` varchar(255) DEFAULT NULL,
  `longitude` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ms_regions_id` (`ms_regions_id`),
  KEY `ms_stasiun_id` (`ms_stasiun_id`),
  KEY `tr_instrument_sensor_id` (`tr_instrument_sensor_id`),
  KEY `tr_instrument_type_id` (`tr_instrument_type_id`),
  CONSTRAINT `tr_instrument_ibfk_1` FOREIGN KEY (`ms_regions_id`) REFERENCES `ms_regions` (`id`),
  CONSTRAINT `tr_instrument_ibfk_2` FOREIGN KEY (`tr_instrument_type_id`) REFERENCES `tr_instrument_type` (`id`),
  CONSTRAINT `tr_instrument_ibfk_3` FOREIGN KEY (`ms_stasiun_id`) REFERENCES `ms_stasiun` (`id`),
  CONSTRAINT `tr_instrument_ibfk_4` FOREIGN KEY (`tr_instrument_sensor_id`) REFERENCES `tr_instrument_sensor` (`id`),
  CONSTRAINT `tr_instrument_ibfk_5` FOREIGN KEY (`tr_instrument_type_id`) REFERENCES `tr_instrument_type` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `tr_instrument` (`id`, `ms_regions_id`, `kode_instrument`, `nama_instrument`, `tr_instrument_type_id`, `ms_stasiun_id`, `tahun_pembuatan`, `tr_instrument_sensor_id`, `latitude`, `longitude`, `created_at`, `updated_at`) VALUES
(27,	1,	'ARR1',	'Rainfall',	35,	3,	'2024',	NULL,	'1.2753708837057924',	'124.94150161743165',	'2024-04-04 08:29:19',	'2024-04-04 08:29:19'),
(28,	1,	'WSP1',	'Wind Speed',	36,	3,	'2024',	NULL,	'1.2753708837057924',	'124.94150161743165',	'2024-04-04 08:30:37',	'2024-04-04 08:30:37'),
(29,	1,	'WDR1',	'Wind Direction',	21,	3,	'2024',	NULL,	'1.2753708837057924',	'124.94150161743165',	'2024-04-04 07:40:02',	'2024-04-04 07:40:02'),
(30,	1,	'EVA1',	'Evaporation',	34,	3,	'2024',	NULL,	'1.2753708837057924',	'124.94150161743165',	'2024-04-04 08:31:14',	'2024-04-04 08:31:14'),
(31,	1,	'SOL1',	'Solar Radiation',	24,	3,	'2024',	NULL,	'1.2753708837057924',	'124.94150161743165',	'2024-04-04 07:44:33',	'2024-04-04 07:44:33'),
(32,	1,	'TEM1',	'Air Temperature',	26,	3,	'2024',	NULL,	'',	'',	'2024-04-04 08:23:40',	'2024-04-04 08:23:40'),
(33,	1,	'HUM1',	'Air Humidity',	27,	3,	'2024',	NULL,	'1.2753708837057924',	'124.94150161743165',	'2024-04-04 07:47:52',	'2024-04-04 07:47:52'),
(34,	1,	'PRS1',	'Air Pressure',	28,	3,	'2024',	NULL,	'1.2753708837057924',	'124.94150161743165',	'2024-04-04 07:49:33',	'2024-04-04 07:49:33'),
(35,	1,	'ARR2',	'Rainfall',	35,	8,	'2024',	NULL,	'1.4508556276418016',	'125.18371582031251',	'2024-04-04 08:29:46',	'2024-04-04 08:29:46'),
(36,	1,	'ARR3',	'Rainfall',	41,	9,	'2024',	NULL,	'1.4980897753593954',	'125.09033203125001',	'2024-04-04 09:02:45',	'2024-04-04 09:02:45'),
(37,	1,	'AWLR1',	'AWLR Waduk',	34,	3,	'2024',	NULL,	'1.2753708837057924',	'124.94150161743165',	'2024-04-04 08:34:04',	'2024-04-04 08:34:04'),
(38,	1,	'AWLR2',	'AWLR Hulu',	33,	5,	'2024',	NULL,	'1.3223618361122975',	'124.82666015625001',	'2024-04-04 08:34:44',	'2024-04-04 08:34:44'),
(39,	1,	'AWLR3',	'AWLR Hilir',	34,	7,	'2024',	NULL,	'1.1507404998678228',	'125.02441406250001',	'2024-04-04 08:35:14',	'2024-04-04 08:35:14'),
(41,	1,	'VNOTCH1',	'VNotch 1',	37,	4,	'2024',	NULL,	'1.3223618361122975',	'124.87060546875001',	'2024-04-04 08:40:02',	'2024-04-04 08:40:02'),
(42,	1,	'VNOTCH2',	'VNotch 2',	38,	4,	'2024',	NULL,	'1.3223618361122975',	'124.87060546875001',	'2024-04-04 08:42:05',	'2024-04-04 08:42:05'),
(43,	1,	'VWP101',	'EP1',	39,	1,	'2024',	NULL,	'1.1946766067455297',	'124.80468750000001',	'2024-04-04 08:46:57',	'2024-04-04 08:46:57'),
(44,	1,	'tes',	'tes',	40,	1,	'2021',	NULL,	'',	'',	'2024-04-15 11:32:30',	'2024-04-15 11:32:30');

DROP TABLE IF EXISTS `tr_instrument_instalasi`;
CREATE TABLE `tr_instrument_instalasi` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tr_instrument_id` int DEFAULT NULL,
  `zona_pemasangan` enum('Pondasi,Timbunan') DEFAULT NULL,
  `latitude` varchar(255) DEFAULT NULL,
  `longitude` varchar(255) DEFAULT NULL,
  `tanggal_rekalibrasi` date DEFAULT NULL,
  `tanggal_instalasi` date DEFAULT NULL,
  `tanggal_zero_reading` date DEFAULT NULL,
  `elevasi_puncak` double(10,3) DEFAULT NULL,
  `elevasi_permukaan_saat_ini` double(10,3) DEFAULT NULL,
  `elevasi_sensor` double(10,3) DEFAULT NULL,
  `elevasi_ground_water_level` double(10,3) DEFAULT NULL,
  `kedalaman_sensor` double(10,3) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tr_instrument_id` (`tr_instrument_id`),
  CONSTRAINT `tr_instrument_instalasi_ibfk_1` FOREIGN KEY (`tr_instrument_id`) REFERENCES `tr_instrument` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `tr_instrument_instalasi` (`id`, `tr_instrument_id`, `zona_pemasangan`, `latitude`, `longitude`, `tanggal_rekalibrasi`, `tanggal_instalasi`, `tanggal_zero_reading`, `elevasi_puncak`, `elevasi_permukaan_saat_ini`, `elevasi_sensor`, `elevasi_ground_water_level`, `kedalaman_sensor`) VALUES
(11,	NULL,	'',	'-6.267390119195093',	'106.95198061410339',	'2024-03-24',	'2024-03-24',	'2024-03-24',	23.000,	23.000,	12.000,	12.000,	12.000),
(21,	43,	'',	'1.1946766067455297',	'124.80468750000001',	'2024-04-04',	'2024-04-04',	'2024-04-04',	156.000,	100.000,	40.000,	50.000,	60.000);

DROP TABLE IF EXISTS `tr_instrument_parameter`;
CREATE TABLE `tr_instrument_parameter` (
  `id` int NOT NULL AUTO_INCREMENT,
  `parameter_name` varchar(255) DEFAULT NULL,
  `tr_instrument_type_id` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `tr_instrument_parameter` (`id`, `parameter_name`, `tr_instrument_type_id`) VALUES
(49,	'kalibrasi',	24),
(54,	'kalibrasi',	26),
(55,	'kalibrasi',	28),
(56,	'kalibrasi',	27),
(67,	'kalibrasi',	36),
(68,	'resolusi',	35),
(69,	'kalibrasi',	35),
(72,	'kalibrasi',	34),
(73,	'tinggi_sensor',	34),
(74,	'elevasi_sensor',	33),
(75,	'kalibrasi',	33),
(76,	'konstanta_v',	37),
(77,	'faktor_koreksi',	37),
(78,	'kalibrasi',	37),
(79,	'faktor_koreksi',	38),
(80,	'tinggi_sensor',	38),
(81,	'konstanta_v',	38),
(82,	'kalibrasi',	38),
(86,	'frekuensi',	39),
(87,	'temperature',	39),
(88,	'kalibrasi',	39),
(89,	'konstanta_v',	40),
(90,	'faktor_koreksi',	40),
(91,	'kalibrasi',	40),
(92,	'kalibrasi',	42),
(93,	'elevasi_sensor',	42),
(94,	'kalibrasi',	43),
(95,	'temperature',	43),
(96,	'frekuensi',	43),
(97,	'temperature',	44),
(98,	'frekuensi',	44),
(99,	'kalibrasi',	44),
(100,	'kalibrasi',	45),
(101,	'elevasi_top_pipa',	45),
(102,	'kalibrasi',	46),
(103,	'elevasi_top_pipa',	46),
(104,	'temperature',	47),
(105,	'kalibrasi',	47),
(106,	'frekuensi',	47),
(107,	'resolusi_arr',	41),
(108,	'kalibrasi',	41),
(110,	'kalibrasi',	21);

DROP TABLE IF EXISTS `tr_instrument_sensor`;
CREATE TABLE `tr_instrument_sensor` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama_sensor` varchar(255) DEFAULT NULL,
  `serial_number` varchar(255) DEFAULT NULL,
  `range` varchar(255) DEFAULT NULL,
  `output` varchar(255) DEFAULT NULL,
  `tr_instrument_id` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `tr_instrument_sensor` (`id`, `nama_sensor`, `serial_number`, `range`, `output`, `tr_instrument_id`) VALUES
(7,	'sensor',	'sn2',	'range2',	'output',	NULL),
(24,	'Rika Sensor',	'123125',	'0-360 Deg',	'Analog (0-5V)',	29),
(26,	'Rika Sensor',	'123127',	'0-1500 wm2',	'Analog (0-5V)',	31),
(28,	'Rika Sensor',	'123129',	'0-100 %',	'Modbus RS485',	33),
(30,	'Rika Sensor',	'123110',	'0-1300 mbar',	'Modbus RS485',	34),
(46,	'Rika Sensor',	'123128',	'0-50 C',	'Modbus RS485',	32),
(51,	'Rika Sensor',	'123123',	'0-1500 wm2',	'Analog (0-5V)',	27),
(52,	'Rika Sensor',	'123127',	'0-1',	'Counter Digital',	35),
(54,	'Rika Sensor',	'123124',	'0-5 m/s',	'Analog (0-5V)',	28),
(55,	'Rika Sensor',	'123126',	'0-100 cm',	'Analog (0-5V)',	30),
(56,	'HOLLY KEL',	'123125',	'0-20 m',	'Modbus RS485',	37),
(57,	'HOLLY KEL',	'123124',	'0-100 m',	'Analog (4-20 mA)',	38),
(58,	'HOLLY KEL',	'123123',	'0-10 m',	'Analog (4-20mA)',	39),
(60,	'HOLLY KEL',	'123122',	'0-1m',	'Analog (0-5V)',	41),
(62,	'HOLLY KEL',	'123127',	'0-1m',	'Modbus RS485',	42),
(63,	'ACE Instrument',	'123122',	'0-100 mmHg',	'Analog',	43),
(64,	'Rika Sensor',	'123126',	'0-1',	'Counter Digital',	36),
(65,	'1241',	'44214',	'421421',	'4214214',	44);

DROP TABLE IF EXISTS `tr_instrument_tp_region`;
CREATE TABLE `tr_instrument_tp_region` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ms_regions_id` bigint DEFAULT NULL,
  `tr_instrument_type_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tr_instrument_type_id` (`tr_instrument_type_id`),
  KEY `ms_regions_id` (`ms_regions_id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `tr_instrument_tp_region` (`id`, `ms_regions_id`, `tr_instrument_type_id`) VALUES
(67,	1,	21),
(70,	1,	24),
(72,	1,	26),
(74,	1,	28),
(75,	1,	27),
(88,	1,	33),
(89,	1,	34),
(90,	1,	35),
(91,	1,	36),
(95,	1,	37),
(96,	1,	38),
(97,	1,	39),
(98,	1,	40),
(99,	1,	41),
(100,	1,	42),
(102,	1,	44),
(103,	1,	45),
(104,	1,	46),
(105,	1,	47),
(106,	1,	43);

DROP TABLE IF EXISTS `tr_instrument_type`;
CREATE TABLE `tr_instrument_type` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `type` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `tr_instrument_type` (`id`, `name`, `type`) VALUES
(21,	'Wind Direction',	'NON_VWP'),
(24,	'Solar Radiation',	'NON_VWP'),
(26,	'Air Temperature',	'NON_VWP'),
(27,	'Air Humidity',	'NON_VWP'),
(28,	'Air Pressure',	'NON_VWP'),
(33,	'Pressure',	'NON_VWP'),
(34,	'Ultrasonic',	'NON_VWP'),
(35,	'Tipping Bucket',	'NON_VWP'),
(36,	'Hall Effect',	'NON_VWP'),
(37,	'Pressure - VNotch',	'NON_VWP'),
(38,	'Ultrasonic - VNotch',	'NON_VWP'),
(39,	'Vibrating Wire',	'VWP'),
(40,	'VNOTCH',	'NON_VWP'),
(41,	'KLIMATOLOGI',	'NON_VWP'),
(42,	'AWLR',	'NON_VWP'),
(43,	'PIEZOMETER',	'VWP'),
(44,	'PRESSURE CELL',	'VWP'),
(45,	'OSP',	'NON_VWP'),
(46,	'OW',	'NON_VWP'),
(47,	'TILT METER',	'VWP');

DROP TABLE IF EXISTS `tr_koefisien`;
CREATE TABLE `tr_koefisien` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tr_instrument_id` int NOT NULL,
  `tr_instrument_type_id` int NOT NULL,
  `parameter` text,
  PRIMARY KEY (`id`),
  KEY `tr_instrument_id` (`tr_instrument_id`),
  KEY `tr_instrument_type_id` (`tr_instrument_type_id`),
  CONSTRAINT `tr_koefisien_ibfk_1` FOREIGN KEY (`tr_instrument_id`) REFERENCES `tr_instrument` (`id`),
  CONSTRAINT `tr_koefisien_ibfk_2` FOREIGN KEY (`tr_instrument_type_id`) REFERENCES `tr_instrument_type` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `tr_koefisien` (`id`, `tr_instrument_id`, `tr_instrument_type_id`, `parameter`) VALUES
(40,	29,	21,	'{\"kalibrasi\":\"0\"}'),
(42,	31,	24,	'{\"kalibrasi\":\"0\"}'),
(44,	33,	27,	'{\"kalibrasi\":\"0\"}'),
(46,	34,	28,	'{\"kalibrasi\":\"0\"}'),
(62,	32,	26,	'{\"kalibrasi\":\"0\"}'),
(67,	27,	35,	'{\"resolusi\":\"0.2\",\"kalibrasi\":\"0\"}'),
(68,	35,	35,	'{\"resolusi\":\"0.2\",\"kalibrasi\":\"0\"}'),
(70,	28,	36,	'{\"kalibrasi\":\"0\"}'),
(71,	30,	34,	'{\"kalibrasi\":\"0\",\"tinggi_sensor\":\"65\"}'),
(72,	37,	34,	'{\"kalibrasi\":\"0\",\"tinggi_sensor\":\"156\"}'),
(73,	38,	33,	'{\"elevasi_sensor\":\"178\",\"kalibrasi\":\"0\"}'),
(74,	39,	34,	'{\"kalibrasi\":\"0\",\"tinggi_sensor\":\"190\"}'),
(76,	41,	37,	'{\"konstanta_v\":\"0.0056\",\"faktor_koreksi\":\"0.42\",\"kalibrasi\":\"0\"}'),
(78,	42,	38,	'{\"faktor_koreksi\":\"0.76\",\"tinggi_sensor\":\"47\",\"konstanta_v\":\"0.0054\",\"kalibrasi\":\"0\"}'),
(79,	36,	41,	'{\"resolusi_arr\":\"0.2\",\"kalibrasi\":\"0\"}'),
(80,	44,	40,	'{\"konstanta_v\":\"12\",\"faktor_koreksi\":\"2112\",\"kalibrasi\":\"12\"}');

DROP TABLE IF EXISTS `tr_koefisien_data`;
CREATE TABLE `tr_koefisien_data` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tr_koefisien_id` int NOT NULL,
  `tr_instrument_parameter_id` int DEFAULT NULL,
  `value` double(10,3) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tr_koefisien_id` (`tr_koefisien_id`),
  KEY `tr_instrument_parameter_id` (`tr_instrument_parameter_id`),
  CONSTRAINT `tr_koefisien_data_ibfk_1` FOREIGN KEY (`tr_koefisien_id`) REFERENCES `tr_koefisien` (`id`),
  CONSTRAINT `tr_koefisien_data_ibfk_2` FOREIGN KEY (`tr_instrument_parameter_id`) REFERENCES `tr_instrument_parameter` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `tr_koefisien_sensor_non_vwp`;
CREATE TABLE `tr_koefisien_sensor_non_vwp` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tr_koefisien_id` int NOT NULL,
  `jenis_sensor_mentah` int NOT NULL,
  `jenis_sensor_jadi` int NOT NULL,
  `tr_instrument_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tr_koefisien_id` (`tr_koefisien_id`),
  KEY `jenis_sensor_mentah` (`jenis_sensor_mentah`),
  KEY `jenis_sensor_jadi` (`jenis_sensor_jadi`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `tr_koefisien_sensor_non_vwp` (`id`, `tr_koefisien_id`, `jenis_sensor_mentah`, `jenis_sensor_jadi`, `tr_instrument_id`) VALUES
(27,	0,	3,	0,	0),
(28,	0,	5,	0,	0),
(29,	0,	4,	0,	0),
(30,	0,	0,	0,	0),
(31,	0,	0,	0,	0),
(32,	0,	0,	0,	0),
(33,	0,	0,	0,	0),
(34,	0,	0,	0,	0),
(35,	0,	0,	0,	0),
(36,	0,	0,	0,	0),
(42,	0,	0,	0,	0),
(43,	0,	0,	0,	0),
(44,	0,	0,	0,	0),
(45,	0,	0,	0,	0),
(46,	0,	0,	0,	0),
(47,	0,	0,	0,	0),
(48,	0,	0,	0,	0),
(49,	0,	0,	0,	0),
(50,	0,	0,	3,	0),
(51,	0,	0,	5,	0),
(80,	40,	8,	0,	29),
(81,	40,	0,	8,	29),
(84,	42,	9,	0,	31),
(85,	42,	0,	9,	31),
(88,	44,	10,	0,	33),
(89,	44,	0,	10,	33),
(92,	46,	11,	0,	34),
(93,	46,	0,	11,	34),
(144,	62,	12,	0,	32),
(145,	62,	0,	0,	32),
(147,	62,	0,	0,	32),
(148,	62,	0,	12,	32),
(165,	67,	18,	0,	27),
(166,	67,	0,	0,	27),
(168,	67,	0,	0,	27),
(169,	67,	0,	6,	27),
(171,	68,	18,	0,	35),
(172,	68,	0,	6,	35),
(175,	70,	7,	0,	28),
(176,	70,	0,	7,	28),
(177,	71,	17,	0,	30),
(178,	71,	0,	13,	30),
(179,	72,	16,	0,	37),
(180,	72,	0,	14,	37),
(181,	73,	16,	0,	38),
(182,	73,	0,	14,	38),
(183,	74,	16,	0,	39),
(184,	74,	0,	14,	39),
(187,	76,	17,	0,	41),
(188,	76,	0,	15,	41),
(191,	78,	17,	0,	42),
(192,	78,	0,	15,	42),
(193,	79,	18,	0,	36),
(194,	79,	0,	6,	36),
(195,	80,	10,	0,	44),
(196,	80,	11,	0,	44),
(198,	80,	0,	11,	44),
(199,	80,	0,	12,	44);

DROP TABLE IF EXISTS `tr_koefisien_sensor_vwp`;
CREATE TABLE `tr_koefisien_sensor_vwp` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tr_koefisien_id` int NOT NULL,
  `jenis_sensor` int NOT NULL,
  `tr_instrument_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tr_koefisien_id` (`tr_koefisien_id`),
  KEY `jenis_sensor` (`jenis_sensor`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `tr_koefisien_sensor_vwp` (`id`, `tr_koefisien_id`, `jenis_sensor`, `tr_instrument_id`) VALUES
(15,	0,	3,	0),
(16,	0,	5,	0),
(17,	0,	4,	0),
(18,	0,	3,	0),
(19,	0,	5,	0),
(20,	0,	4,	0);

DROP TABLE IF EXISTS `wilayah`;
CREATE TABLE `wilayah` (
  `kode` varchar(13) NOT NULL,
  `nama` varchar(25) DEFAULT NULL,
  `kode_pos` varchar(8) DEFAULT NULL,
  PRIMARY KEY (`kode`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `wilayah` (`kode`, `nama`, `kode_pos`) VALUES
('11',	'ACEH',	NULL),
('1101',	'KABUPATEN ACEH SELATAN',	NULL),
('110101',	'Bakongan',	NULL),
('1101012001',	'Keude Bakongan',	NULL),
('1101012002',	'Ujung Mangki',	NULL),
('1101012003',	'Ujung Padang',	NULL),
('1101012004',	'Kampong Drien',	NULL),
('1101012015',	'Darul Ikhsan',	NULL),
('1101012016',	'Padang Berahan',	NULL),
('1101012017',	'Gampong Baro',	NULL),
('110102',	'Kluet Utara',	NULL),
('1101022001',	'Fajar Harapan',	NULL),
('1101022002',	'Krueng Batee',	NULL),
('1101022003',	'Pasi Kuala Asahan',	NULL),
('1101022004',	'Gunung Pulo',	NULL),
('1101022005',	'Pulo IE I',	NULL),
('1101022006',	'Jambo Manyang',	NULL),
('1101022007',	'Simpang Empat',	NULL),
('1101022008',	'Limau Purut',	NULL),
('1101022009',	'Pulo Kambing',	NULL),
('1101022010',	'Kampung Paya',	NULL),
('1101022011',	'Krueng Batu',	NULL),
('1101022012',	'Krueng Kluet',	NULL),
('1101022013',	'Alur Mas',	NULL),
('1101022014',	'Simpang Dua',	NULL),
('1101022015',	'Simpang Tiga',	NULL),
('1101022016',	'Simpang Lhee',	NULL),
('1101022017',	'Suag Geuringgeng',	NULL),
('1101022018',	'Pasi Kuala Baku',	NULL),
('1101022019',	'Kedai Padang',	NULL),
('1101022020',	'Kotafajar',	NULL),
('1101022021',	'Gunung Pudung',	NULL),
('110103',	'Kluet Selatan',	NULL),
('1101032001',	'Suaq Bakong',	NULL),
('1101032002',	'Rantau Benuang',	NULL),
('1101032003',	'Barat Daya',	NULL),
('1101032004',	'Sialang',	NULL),
('1101032005',	'Kapeh',	NULL),
('1101032006',	'Pulo IE',	NULL),
('1101032007',	'Kedai Runding',	NULL),
('1101032008',	'Kedai Kandang',	NULL),
('1101032009',	'Luar',	NULL),
('1101032010',	'Ujong',	NULL),
('1101032011',	'Jua',	NULL),
('1101032012',	'Pasi Meurapat',	NULL),
('1101032013',	'Ujung Pasir',	NULL),
('1101032014',	'Geulumbuk',	NULL),
('1101032015',	'Pasie Lambang',	NULL),
('1101032016',	'Ujung Padang',	NULL),
('1101032017',	'Indra Damai',	NULL);

-- 2024-04-16 19:15:12
