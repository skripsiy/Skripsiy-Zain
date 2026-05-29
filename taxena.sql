-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.4.3 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for xena
CREATE DATABASE IF NOT EXISTS `xena` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `xena`;

-- Dumping structure for table xena.activity_log
CREATE TABLE IF NOT EXISTS `activity_log` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `log_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_id` bigint unsigned DEFAULT NULL,
  `causer_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `causer_id` bigint unsigned DEFAULT NULL,
  `properties` json DEFAULT NULL,
  `batch_uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subject` (`subject_type`,`subject_id`),
  KEY `causer` (`causer_type`,`causer_id`),
  KEY `activity_log_log_name_index` (`log_name`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table xena.activity_log: ~11 rows (approximately)
INSERT IGNORE INTO `activity_log` (`id`, `log_name`, `description`, `subject_type`, `event`, `subject_id`, `causer_type`, `causer_id`, `properties`, `batch_uuid`, `created_at`, `updated_at`) VALUES
	(1, 'default', 'created', 'App\\Models\\Ticket', 'created', 1, NULL, NULL, '{"attributes": {"PIC": null, "THT": null, "gaul": 0, "noSC": null, "gamas": null, "lapul": 0, "topic": null, "witel": "BANDUNG", "resume": null, "status": "QUEUED", "contact": null, "assignby": "agent@xena.com", "idTicket": 1, "namacust": "John Doe", "password": null, "regional": "JABAR", "responBE": null, "solvedby": null, "statusSC": null, "condition": null, "idlaporan": null, "created_at": "2026-03-30T15:54:03.000000Z", "datereport": "2026-03-25T00:00:00.000000Z", "datesolved": null, "notelpCust": "081234567890", "updated_at": "2026-03-30T15:54:03.000000Z", "description": null, "eksalasiVia": null, "jenisTicket": "GANGGUAN INTERNET", "klasifikasi": null, "reasonnoODS": null, "topicDetail": null, "detailticket": "Internet tidak bisa connect", "validateClose": null, "eksalasiTicket": null, "escalationStatus": null, "reportedpriority": null}}', NULL, '2026-03-30 08:54:03', '2026-03-30 08:54:03'),
	(2, 'default', 'created', 'App\\Models\\Ticket', 'created', 2, NULL, NULL, '{"attributes": {"PIC": null, "THT": null, "gaul": 0, "noSC": null, "gamas": null, "lapul": 0, "topic": null, "witel": "BANDUNG", "resume": null, "status": "QUEUED", "contact": null, "assignby": "agent@xena.com", "idTicket": 2, "namacust": "Jane Smith", "password": null, "regional": "JABAR", "responBE": null, "solvedby": null, "statusSC": null, "condition": null, "idlaporan": null, "created_at": "2026-03-30T15:54:03.000000Z", "datereport": "2026-03-26T00:00:00.000000Z", "datesolved": null, "notelpCust": "081234567891", "updated_at": "2026-03-30T15:54:03.000000Z", "description": null, "eksalasiVia": null, "jenisTicket": "GANGGUAN TELEPON", "klasifikasi": null, "reasonnoODS": null, "topicDetail": null, "detailticket": "Telepon tidak bisa digunakan", "validateClose": null, "eksalasiTicket": null, "escalationStatus": null, "reportedpriority": null}}', NULL, '2026-03-30 08:54:03', '2026-03-30 08:54:03'),
	(3, 'default', 'created', 'App\\Models\\Ticket', 'created', 3, NULL, NULL, '{"attributes": {"PIC": null, "THT": null, "gaul": 0, "noSC": null, "gamas": null, "lapul": 0, "topic": null, "witel": "BANDUNG", "resume": null, "status": "IN PROGRESS", "contact": null, "assignby": "agent@xena.com", "idTicket": 3, "namacust": "Bob Johnson", "password": null, "regional": "JABAR", "responBE": null, "solvedby": null, "statusSC": null, "condition": null, "idlaporan": null, "created_at": "2026-03-30T15:54:03.000000Z", "datereport": "2026-03-27T00:00:00.000000Z", "datesolved": null, "notelpCust": "081234567892", "updated_at": "2026-03-30T15:54:03.000000Z", "description": null, "eksalasiVia": null, "jenisTicket": "GANGGUAN TV", "klasifikasi": null, "reasonnoODS": null, "topicDetail": null, "detailticket": "TV tidak ada sinyal", "validateClose": null, "eksalasiTicket": null, "escalationStatus": null, "reportedpriority": null}}', NULL, '2026-03-30 08:54:03', '2026-03-30 08:54:03'),
	(4, 'default', 'created', 'App\\Models\\Ticket', 'created', 4, NULL, NULL, '{"attributes": {"PIC": null, "THT": null, "gaul": 0, "noSC": null, "gamas": null, "lapul": 0, "topic": null, "witel": "BANDUNG", "resume": null, "status": "SOLVED", "contact": null, "assignby": "agent@xena.com", "idTicket": 4, "namacust": "Alice Brown", "password": null, "regional": "JABAR", "responBE": null, "solvedby": "agent@xena.com", "statusSC": null, "condition": null, "idlaporan": null, "created_at": "2026-03-30T15:54:03.000000Z", "datereport": "2026-03-28T00:00:00.000000Z", "datesolved": "2026-03-29T00:00:00.000000Z", "notelpCust": "081234567893", "updated_at": "2026-03-30T15:54:03.000000Z", "description": null, "eksalasiVia": null, "jenisTicket": "GANGGUAN INTERNET", "klasifikasi": null, "reasonnoODS": null, "topicDetail": null, "detailticket": "Kecepatan internet lambat", "validateClose": null, "eksalasiTicket": null, "escalationStatus": null, "reportedpriority": null}}', NULL, '2026-03-30 08:54:03', '2026-03-30 08:54:03'),
	(5, 'default', 'created', 'App\\Models\\Ticket', 'created', 5, NULL, NULL, '{"attributes": {"PIC": null, "THT": null, "gaul": 0, "noSC": null, "gamas": null, "lapul": 0, "topic": null, "witel": "BANDUNG", "resume": null, "status": "SOLVED", "contact": null, "assignby": "agent@xena.com", "idTicket": 5, "namacust": "Charlie Wilson", "password": null, "regional": "JABAR", "responBE": null, "solvedby": "agent@xena.com", "statusSC": null, "condition": null, "idlaporan": null, "created_at": "2026-03-30T15:54:03.000000Z", "datereport": "2026-03-29T00:00:00.000000Z", "datesolved": "2026-03-30T00:00:00.000000Z", "notelpCust": "081234567894", "updated_at": "2026-03-30T15:54:03.000000Z", "description": null, "eksalasiVia": null, "jenisTicket": "GANGGUAN TELEPON", "klasifikasi": null, "reasonnoODS": null, "topicDetail": null, "detailticket": "Tidak bisa telepon keluar", "validateClose": null, "eksalasiTicket": null, "escalationStatus": null, "reportedpriority": null}}', NULL, '2026-03-30 08:54:03', '2026-03-30 08:54:03'),
	(6, 'default', 'created', 'App\\Models\\Ticket', 'created', 6, NULL, NULL, '{"attributes": {"PIC": null, "THT": null, "gaul": 0, "noSC": null, "gamas": null, "lapul": 0, "topic": null, "witel": "BANDUNG", "resume": null, "status": "QUEUED", "contact": null, "assignby": "teamleader@xena.com", "idTicket": 6, "namacust": "David Lee", "password": null, "regional": "JABAR", "responBE": null, "solvedby": null, "statusSC": null, "condition": null, "idlaporan": null, "created_at": "2026-03-30T15:54:03.000000Z", "datereport": "2026-03-27T00:00:00.000000Z", "datesolved": null, "notelpCust": "081234567895", "updated_at": "2026-03-30T15:54:03.000000Z", "description": null, "eksalasiVia": null, "jenisTicket": "GANGGUAN INTERNET", "klasifikasi": null, "reasonnoODS": null, "topicDetail": null, "detailticket": "Internet putus-putus", "validateClose": null, "eksalasiTicket": null, "escalationStatus": null, "reportedpriority": null}}', NULL, '2026-03-30 08:54:03', '2026-03-30 08:54:03'),
	(7, 'default', 'created', 'App\\Models\\Ticket', 'created', 7, NULL, NULL, '{"attributes": {"PIC": null, "THT": null, "gaul": 0, "noSC": null, "gamas": null, "lapul": 0, "topic": null, "witel": "BANDUNG", "resume": null, "status": "IN PROGRESS", "contact": null, "assignby": "teamleader@xena.com", "idTicket": 7, "namacust": "Eva Martinez", "password": null, "regional": "JABAR", "responBE": null, "solvedby": null, "statusSC": null, "condition": null, "idlaporan": null, "created_at": "2026-03-30T15:54:04.000000Z", "datereport": "2026-03-28T00:00:00.000000Z", "datesolved": null, "notelpCust": "081234567896", "updated_at": "2026-03-30T15:54:04.000000Z", "description": null, "eksalasiVia": null, "jenisTicket": "GANGGUAN TV", "klasifikasi": null, "reasonnoODS": null, "topicDetail": null, "detailticket": "Channel TV tidak lengkap", "validateClose": null, "eksalasiTicket": null, "escalationStatus": null, "reportedpriority": null}}', NULL, '2026-03-30 08:54:04', '2026-03-30 08:54:04'),
	(8, 'default', 'created', 'App\\Models\\Ticket', 'created', 8, NULL, NULL, '{"attributes": {"PIC": null, "THT": "2026-03-30T16:54:03.000000Z", "gaul": 0, "noSC": null, "gamas": null, "lapul": 0, "topic": null, "witel": "BANDUNG", "resume": null, "status": "QUEUED", "contact": null, "assignby": "agent@xena.com", "idTicket": 8, "namacust": "CRITICAL USER - PT Bank Mandiri", "password": null, "regional": "JABAR", "responBE": null, "solvedby": null, "statusSC": null, "condition": null, "idlaporan": null, "created_at": "2026-03-30T15:54:04.000000Z", "datereport": "2026-03-30T00:00:00.000000Z", "datesolved": null, "notelpCust": "081234567897", "updated_at": "2026-03-30T15:54:04.000000Z", "description": null, "eksalasiVia": null, "jenisTicket": "GANGGUAN INTERNET", "klasifikasi": null, "reasonnoODS": null, "topicDetail": null, "detailticket": "Seluruh jaringan internet kantor pusat down, urgent untuk transaksi banking", "validateClose": null, "eksalasiTicket": null, "escalationStatus": "URGENT", "reportedpriority": "SUPER EMERGENCY"}}', NULL, '2026-03-30 08:54:04', '2026-03-30 08:54:04'),
	(9, 'default', 'created', 'App\\Models\\Ticket', 'created', 9, NULL, NULL, '{"attributes": {"PIC": null, "THT": "2026-03-30T16:24:03.000000Z", "gaul": 0, "noSC": null, "gamas": null, "lapul": 0, "topic": null, "witel": "BANDUNG", "resume": null, "status": "IN PROGRESS", "contact": null, "assignby": "agent@xena.com", "idTicket": 9, "namacust": "CRITICAL USER - RS Hasan Sadikin", "password": null, "regional": "JABAR", "responBE": null, "solvedby": null, "statusSC": null, "condition": null, "idlaporan": null, "created_at": "2026-03-30T15:54:04.000000Z", "datereport": "2026-03-30T00:00:00.000000Z", "datesolved": null, "notelpCust": "081234567898", "updated_at": "2026-03-30T15:54:04.000000Z", "description": null, "eksalasiVia": null, "jenisTicket": "GANGGUAN TELEPON", "klasifikasi": null, "reasonnoODS": null, "topicDetail": null, "detailticket": "Sistem telepon rumah sakit mati total, mengganggu operasional emergency", "validateClose": null, "eksalasiTicket": null, "escalationStatus": "CRITICAL", "reportedpriority": "SUPER EMERGENCY"}}', NULL, '2026-03-30 08:54:04', '2026-03-30 08:54:04'),
	(10, 'default', 'created', 'App\\Models\\Ticket', 'created', 10, NULL, NULL, '{"attributes": {"PIC": null, "THT": "2026-03-30T16:39:03.000000Z", "gaul": 0, "noSC": null, "gamas": null, "lapul": 0, "topic": null, "witel": "BANDUNG", "resume": null, "status": "QUEUED", "contact": null, "assignby": "teamleader@xena.com", "idTicket": 10, "namacust": "CRITICAL USER - Polda Jabar", "password": null, "regional": "JABAR", "responBE": null, "solvedby": null, "statusSC": null, "condition": null, "idlaporan": null, "created_at": "2026-03-30T15:54:04.000000Z", "datereport": "2026-03-30T00:00:00.000000Z", "datesolved": null, "notelpCust": "081234567899", "updated_at": "2026-03-30T15:54:04.000000Z", "description": null, "eksalasiVia": null, "jenisTicket": "GANGGUAN INTERNET", "klasifikasi": null, "reasonnoODS": null, "topicDetail": null, "detailticket": "Koneksi internet pusat komando mati, butuh penanganan segera", "validateClose": null, "eksalasiTicket": null, "escalationStatus": "URGENT", "reportedpriority": "SUPER EMERGENCY"}}', NULL, '2026-03-30 08:54:04', '2026-03-30 08:54:04'),
	(11, 'default', 'created', 'App\\Models\\Ticket', 'created', 11, NULL, NULL, '{"attributes": {"PIC": null, "THT": null, "gaul": 0, "noSC": null, "gamas": null, "lapul": 0, "topic": null, "witel": "BANDUNG", "resume": null, "status": "SOLVED", "contact": null, "assignby": "agent@xena.com", "idTicket": 11, "namacust": "CRITICAL USER - TVRI Jabar", "password": null, "regional": "JABAR", "responBE": null, "solvedby": "agent@xena.com", "statusSC": null, "condition": null, "idlaporan": null, "created_at": "2026-03-30T15:54:04.000000Z", "datereport": "2026-03-29T00:00:00.000000Z", "datesolved": "2026-03-30T00:00:00.000000Z", "notelpCust": "081234567800", "updated_at": "2026-03-30T15:54:04.000000Z", "description": null, "eksalasiVia": null, "jenisTicket": "GANGGUAN TV", "klasifikasi": null, "reasonnoODS": null, "topicDetail": null, "detailticket": "Gangguan siaran TV nasional, segera ditangani", "validateClose": null, "eksalasiTicket": null, "escalationStatus": "RESOLVED", "reportedpriority": "SUPER EMERGENCY"}}', NULL, '2026-03-30 08:54:04', '2026-03-30 08:54:04');

-- Dumping structure for table xena.agent_work_sessions
CREATE TABLE IF NOT EXISTS `agent_work_sessions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `shift_start` timestamp NULL DEFAULT NULL,
  `shift_end` timestamp NULL DEFAULT NULL,
  `total_online_seconds` int NOT NULL DEFAULT '0',
  `total_aux_seconds` int NOT NULL DEFAULT '0',
  `aux_remaining_seconds` int NOT NULL DEFAULT '3600',
  `status` enum('offline','online','aux') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'offline',
  `current_session_start` timestamp NULL DEFAULT NULL,
  `work_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `agent_work_sessions_user_id_work_date_index` (`user_id`,`work_date`),
  CONSTRAINT `agent_work_sessions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table xena.agent_work_sessions: ~0 rows (approximately)

-- Dumping structure for table xena.cache
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table xena.cache: ~0 rows (approximately)

-- Dumping structure for table xena.cache_locks
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table xena.cache_locks: ~0 rows (approximately)

-- Dumping structure for table xena.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table xena.failed_jobs: ~0 rows (approximately)

-- Dumping structure for table xena.jobs
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table xena.jobs: ~0 rows (approximately)

-- Dumping structure for table xena.job_batches
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table xena.job_batches: ~0 rows (approximately)

-- Dumping structure for table xena.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table xena.migrations: ~0 rows (approximately)
INSERT IGNORE INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1),
	(4, '2025_10_29_074647_add_role_to_users_table', 1),
	(5, '2025_11_03_084242_add_profile_fields_to_users_table', 1),
	(6, '2025_11_03_100937_create_tickets_table', 1),
	(7, '2025_11_11_141104_create_agent_work_sessions_table', 1),
	(8, '2025_11_27_085249_add_status_and_area_to_users_table', 1),
	(9, '2025_11_28_083153_create_activity_log_table', 1),
	(10, '2025_11_28_083154_add_event_column_to_activity_log_table', 1),
	(11, '2025_11_28_083155_add_batch_uuid_column_to_activity_log_table', 1),
	(12, '2025_12_05_072455_update_agent_work_sessions_table_for_time_tracking', 1),
	(13, '2025_12_05_074832_fix_shift_start_nullable_in_agent_work_sessions', 1);

-- Dumping structure for table xena.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table xena.password_reset_tokens: ~0 rows (approximately)

-- Dumping structure for table xena.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table xena.sessions: ~1 rows (approximately)
INSERT IGNORE INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('bqmJYyoN8dycT7GjAN5pHg0IcfyBO6Fhp4HTdVUl', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoienRUSzRpUnhIMHZadXdzTnFFSjB3RnZpc3pDOVJxQWdmNWYxMWs5diI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjU6Imh0dHA6Ly90YS14ZW5hLnRlc3QvbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1776006092);

-- Dumping structure for table xena.tickets
CREATE TABLE IF NOT EXISTS `tickets` (
  `idTicket` bigint unsigned NOT NULL AUTO_INCREMENT,
  `datereport` date NOT NULL,
  `jenisTicket` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notelpCust` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `namacust` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `idlaporan` int DEFAULT NULL,
  `detailticket` text COLLATE utf8mb4_unicode_ci,
  `gamas` int DEFAULT NULL,
  `lapul` int NOT NULL DEFAULT '0',
  `gaul` int NOT NULL DEFAULT '0',
  `resume` text COLLATE utf8mb4_unicode_ci,
  `klasifikasi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `topic` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `topicDetail` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `noSC` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `statusSC` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `validateClose` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reasonnoODS` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `eksalasiTicket` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `eksalasiVia` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `PIC` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `responBE` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `reportedpriority` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `datesolved` date DEFAULT NULL,
  `THT` timestamp NULL DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'QUEUED',
  `regional` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `witel` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `condition` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `assignby` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `solvedby` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `escalationStatus` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idTicket`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table xena.tickets: ~11 rows (approximately)
INSERT IGNORE INTO `tickets` (`idTicket`, `datereport`, `jenisTicket`, `notelpCust`, `password`, `namacust`, `idlaporan`, `detailticket`, `gamas`, `lapul`, `gaul`, `resume`, `klasifikasi`, `topic`, `topicDetail`, `noSC`, `statusSC`, `validateClose`, `reasonnoODS`, `eksalasiTicket`, `eksalasiVia`, `PIC`, `contact`, `responBE`, `description`, `reportedpriority`, `datesolved`, `THT`, `status`, `regional`, `witel`, `condition`, `assignby`, `solvedby`, `escalationStatus`, `created_at`, `updated_at`) VALUES
	(1, '2026-03-25', 'GANGGUAN INTERNET', '081234567890', NULL, 'John Doe', NULL, 'Internet tidak bisa connect', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'QUEUED', 'JABAR', 'BANDUNG', NULL, 'agent@xena.com', NULL, NULL, '2026-03-30 08:54:03', '2026-03-30 08:54:03'),
	(2, '2026-03-26', 'GANGGUAN TELEPON', '081234567891', NULL, 'Jane Smith', NULL, 'Telepon tidak bisa digunakan', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'QUEUED', 'JABAR', 'BANDUNG', NULL, 'agent@xena.com', NULL, NULL, '2026-03-30 08:54:03', '2026-03-30 08:54:03'),
	(3, '2026-03-27', 'GANGGUAN TV', '081234567892', NULL, 'Bob Johnson', NULL, 'TV tidak ada sinyal', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'IN PROGRESS', 'JABAR', 'BANDUNG', NULL, 'agent@xena.com', NULL, NULL, '2026-03-30 08:54:03', '2026-03-30 08:54:03'),
	(4, '2026-03-28', 'GANGGUAN INTERNET', '081234567893', NULL, 'Alice Brown', NULL, 'Kecepatan internet lambat', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-29', NULL, 'SOLVED', 'JABAR', 'BANDUNG', NULL, 'agent@xena.com', 'agent@xena.com', NULL, '2026-03-30 08:54:03', '2026-03-30 08:54:03'),
	(5, '2026-03-29', 'GANGGUAN TELEPON', '081234567894', NULL, 'Charlie Wilson', NULL, 'Tidak bisa telepon keluar', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-30', NULL, 'SOLVED', 'JABAR', 'BANDUNG', NULL, 'agent@xena.com', 'agent@xena.com', NULL, '2026-03-30 08:54:03', '2026-03-30 08:54:03'),
	(6, '2026-03-27', 'GANGGUAN INTERNET', '081234567895', NULL, 'David Lee', NULL, 'Internet putus-putus', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'QUEUED', 'JABAR', 'BANDUNG', NULL, 'teamleader@xena.com', NULL, NULL, '2026-03-30 08:54:03', '2026-03-30 08:54:03'),
	(7, '2026-03-28', 'GANGGUAN TV', '081234567896', NULL, 'Eva Martinez', NULL, 'Channel TV tidak lengkap', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'IN PROGRESS', 'JABAR', 'BANDUNG', NULL, 'teamleader@xena.com', NULL, NULL, '2026-03-30 08:54:04', '2026-03-30 08:54:04'),
	(8, '2026-03-30', 'GANGGUAN INTERNET', '081234567897', NULL, 'CRITICAL USER - PT Bank Mandiri', NULL, 'Seluruh jaringan internet kantor pusat down, urgent untuk transaksi banking', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'SUPER EMERGENCY', NULL, '2026-03-30 09:54:03', 'QUEUED', 'JABAR', 'BANDUNG', NULL, 'agent@xena.com', NULL, 'URGENT', '2026-03-30 08:54:04', '2026-03-30 08:54:04'),
	(9, '2026-03-30', 'GANGGUAN TELEPON', '081234567898', NULL, 'CRITICAL USER - RS Hasan Sadikin', NULL, 'Sistem telepon rumah sakit mati total, mengganggu operasional emergency', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'SUPER EMERGENCY', NULL, '2026-03-30 09:24:03', 'IN PROGRESS', 'JABAR', 'BANDUNG', NULL, 'agent@xena.com', NULL, 'CRITICAL', '2026-03-30 08:54:04', '2026-03-30 08:54:04'),
	(10, '2026-03-30', 'GANGGUAN INTERNET', '081234567899', NULL, 'CRITICAL USER - Polda Jabar', NULL, 'Koneksi internet pusat komando mati, butuh penanganan segera', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'SUPER EMERGENCY', NULL, '2026-03-30 09:39:03', 'QUEUED', 'JABAR', 'BANDUNG', NULL, 'teamleader@xena.com', NULL, 'URGENT', '2026-03-30 08:54:04', '2026-03-30 08:54:04'),
	(11, '2026-03-29', 'GANGGUAN TV', '081234567800', NULL, 'CRITICAL USER - TVRI Jabar', NULL, 'Gangguan siaran TV nasional, segera ditangani', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'SUPER EMERGENCY', '2026-03-30', NULL, 'SOLVED', 'JABAR', 'BANDUNG', NULL, 'agent@xena.com', 'agent@xena.com', 'RESOLVED', '2026-03-30 08:54:04', '2026-03-30 08:54:04');

-- Dumping structure for table xena.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `campaign` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `area` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `site` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('admin','team_leader','agent') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'agent',
  `status` enum('active','inactive','suspend') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table xena.users: ~3 rows (approximately)
INSERT IGNORE INTO `users` (`id`, `name`, `email`, `campaign`, `area`, `site`, `username`, `phone`, `role`, `status`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'Admin User', 'admin@xena.com', NULL, NULL, NULL, NULL, NULL, 'admin', 'active', NULL, '$2y$12$WS20hQfSmmssamXO.2HeA.PEf/eu0nbLXBPUyU7Ud56suCZKMVpDu', NULL, '2026-03-30 08:54:03', '2026-03-30 08:54:03'),
	(2, 'Team Leader', 'teamleader@xena.com', NULL, NULL, NULL, NULL, NULL, 'team_leader', 'active', NULL, '$2y$12$LZk4RfIZsag6MEJXwxHvbuWs4bwAsjibGX63THLXPlQuo0ThSpxrS', NULL, '2026-03-30 08:54:03', '2026-03-30 08:54:03'),
	(3, 'Agent User', 'agent@xena.com', NULL, NULL, NULL, NULL, NULL, 'agent', 'active', NULL, '$2y$12$DcwSQFpl4cW3OOhObr1b..N0DTl1/IF2iLyRD7oBqDQl5vbqo11pa', NULL, '2026-03-30 08:54:03', '2026-03-30 08:54:03');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
