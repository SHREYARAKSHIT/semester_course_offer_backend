-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.28-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             12.5.0.6677
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table project.auth_types
CREATE TABLE IF NOT EXISTS `auth_types` (
  `id` varchar(150) NOT NULL,
  `type` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table project.auth_types: ~1 rows (approximately)
DELETE FROM `auth_types`;
INSERT INTO `auth_types` (`id`, `type`) VALUES
	('stock_assistant_cc', 'Stock Assistant CC');

-- Dumping structure for table project.departments
CREATE TABLE IF NOT EXISTS `departments` (
  `id` varchar(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `type` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table project.departments: ~0 rows (approximately)
DELETE FROM `departments`;

-- Dumping structure for table project.designations
CREATE TABLE IF NOT EXISTS `designations` (
  `id` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table project.designations: ~0 rows (approximately)
DELETE FROM `designations`;

-- Dumping structure for table project.emp_basic_details
CREATE TABLE IF NOT EXISTS `emp_basic_details` (
  `emp_no` varchar(12) NOT NULL,
  `auth_id` varchar(10) NOT NULL,
  `designation` varchar(50) NOT NULL,
  `office_no` bigint(11) DEFAULT NULL,
  `fax` bigint(11) DEFAULT NULL,
  `joining_date` date NOT NULL,
  `retirement_ext` int(11) DEFAULT NULL,
  `retirement_date` date DEFAULT NULL,
  `employment_nature` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`emp_no`),
  KEY `auth_id` (`auth_id`),
  KEY `auth_id_2` (`auth_id`),
  KEY `auth_id_3` (`auth_id`),
  CONSTRAINT `emp_basic_details_ibfk_1` FOREIGN KEY (`auth_id`) REFERENCES `auth_types` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table project.emp_basic_details: ~1 rows (approximately)
DELETE FROM `emp_basic_details`;
INSERT INTO `emp_basic_details` (`emp_no`, `auth_id`, `designation`, `office_no`, `fax`, `joining_date`, `retirement_ext`, `retirement_date`, `employment_nature`) VALUES
	('1594', 'nftn', 'JrTechSup', 0, 0, '1023-10-26', 0, '2080-01-31', 'null');

-- Dumping structure for table project.login_logout_log
CREATE TABLE IF NOT EXISTS `login_logout_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(20) NOT NULL,
  `logged_in_time` datetime DEFAULT NULL,
  `logged_out_time` datetime DEFAULT NULL,
  `login_ip` varchar(20) NOT NULL,
  `logout_ip` varchar(20) DEFAULT NULL,
  `login_from` varchar(20) DEFAULT 'MIS',
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table project.login_logout_log: ~5 rows (approximately)
DELETE FROM `login_logout_log`;
INSERT INTO `login_logout_log` (`log_id`, `user_id`, `logged_in_time`, `logged_out_time`, `login_ip`, `logout_ip`, `login_from`) VALUES
	(1, '1594', '2024-03-12 09:06:22', NULL, '127.0.0.1', NULL, 'MIS'),
	(2, '1594', '2024-03-12 09:23:22', NULL, '127.0.0.1', NULL, 'MIS'),
	(3, '1594', '2024-03-12 09:39:22', NULL, '127.0.0.1', NULL, 'MIS'),
	(4, '1594', '2024-03-12 09:50:23', NULL, '127.0.0.1', NULL, 'MIS'),
	(5, '1594', '2024-03-12 09:51:39', NULL, '127.0.0.1', NULL, 'MIS');

-- Dumping structure for table project.mis_session
CREATE TABLE IF NOT EXISTS `mis_session` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session` varchar(20) NOT NULL,
  `active` enum('0','1') DEFAULT '0',
  `fellowship_active` enum('0','1') DEFAULT '0',
  `tms_active` enum('0','1') DEFAULT '0',
  `leave_active` enum('0','1') DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `Index 2` (`session`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table project.mis_session: ~3 rows (approximately)
DELETE FROM `mis_session`;
INSERT INTO `mis_session` (`id`, `session`, `active`, `fellowship_active`, `tms_active`, `leave_active`) VALUES
	(1, 'Monsoon', '1', '0', '1', '0'),
	(2, 'Winter', '1', '1', '0', '1'),
	(3, 'Summer', '1', '0', '0', '0');

-- Dumping structure for table project.mis_session_year
CREATE TABLE IF NOT EXISTS `mis_session_year` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session_year` varchar(20) NOT NULL,
  `active` enum('0','1') DEFAULT '0',
  `fellowship_active` enum('0','1') DEFAULT '0',
  `leave_active` enum('0','1') DEFAULT '0',
  `tms_active` enum('0','1') DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `Index 2` (`session_year`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table project.mis_session_year: ~10 rows (approximately)
DELETE FROM `mis_session_year`;
INSERT INTO `mis_session_year` (`id`, `session_year`, `active`, `fellowship_active`, `leave_active`, `tms_active`) VALUES
	(1, '2015-2016', '0', '0', '0', '0'),
	(2, '2016-2017', '0', '0', '0', '0'),
	(3, '2017-2018', '0', '0', '0', '0'),
	(4, '2018-2019', '0', '0', '0', '0'),
	(5, '2019-2020', '0', '0', '0', '0'),
	(6, '2020-2021', '0', '0', '0', '0'),
	(7, '2021-2022', '1', '0', '0', '0'),
	(11, '2022-2023', '1', '0', '0', '0'),
	(14, '2023-2024', '1', '1', '1', '1'),
	(15, '2024-2025', '1', '0', '0', '0');

-- Dumping structure for table project.notifications
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) unsigned DEFAULT NULL,
  `data` text DEFAULT NULL,
  `user_to` varchar(255) DEFAULT NULL,
  `user_from` varchar(255) DEFAULT NULL,
  `send_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `rec_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `auth_id` varchar(255) DEFAULT NULL,
  `module_id` varchar(255) DEFAULT NULL,
  `notice_title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `notice_path` varchar(255) DEFAULT NULL,
  `notice_type` varchar(255) DEFAULT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table project.notifications: ~2 rows (approximately)
DELETE FROM `notifications`;
INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `user_to`, `user_from`, `send_date`, `rec_date`, `auth_id`, `module_id`, `notice_title`, `description`, `notice_path`, `notice_type`, `read_at`, `created_at`, `updated_at`) VALUES
	('3214794e-6bcb-4f97-bce1-7048d9cbe64e', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 1594, '{"user_to":853,"user_from":"1594","auth_id":"dpgc","notice_title":"This is header","description":"This is body test by abhijeet for real time notification sync","module_id":"test","notice_path":null,"data":"This is body test by abhijeet for real time notification sync"}', NULL, NULL, '2024-03-12 09:23:43', '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-03-12 03:53:43', '2024-03-12 03:53:43'),
	('5dad21ef-4b75-4ac6-a689-48687afbf75e', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 1594, '{"user_to":853,"user_from":"1594","auth_id":"dpgc","notice_title":"This is header","description":"This is body test by abhijeet for real time notification sync","module_id":"test","notice_path":null,"data":"This is body test by abhijeet for real time notification sync"}', NULL, NULL, '2024-03-12 09:22:31', '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-03-12 03:52:31', '2024-03-12 03:52:31');

-- Dumping structure for table project.personal_access_tokens
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table project.personal_access_tokens: ~1 rows (approximately)
DELETE FROM `personal_access_tokens`;
INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
	(9, 'App\\Models\\User', '1594', 'mis_MyApp', '3349da4059f202e49b208e11f380413ec7302ce7177e934907236f64361c3909', '["server:update"]', NULL, NULL, '2024-03-12 04:09:51', '2024-03-12 04:09:51');

-- Dumping structure for table project.personal_access_tokens_log
CREATE TABLE IF NOT EXISTS `personal_access_tokens_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `token_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- Dumping data for table project.personal_access_tokens_log: ~8 rows (approximately)
DELETE FROM `personal_access_tokens_log`;
INSERT INTO `personal_access_tokens_log` (`id`, `token_id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
	(1, 1, 'App\\Models\\User', 1594, 'mis_MyApp', 'df86ccb3adfb297a5ab8688c034801225e6b108bf339d20e450023b718324e2f', '["server:update"]', NULL, NULL, '2024-03-12 09:20:46', '2024-03-12 09:20:46'),
	(2, 2, 'App\\Models\\User', 1594, 'mis_MyApp', '2beccb8b9a838de861f3df422a84cb176a72cb207aac2841ab80d47402729bd8', '["server:update"]', NULL, NULL, '2024-03-12 09:21:09', '2024-03-12 09:21:09'),
	(3, 3, 'App\\Models\\User', 1594, 'mis_MyApp', '9d73cce104329c85d10c2c80e60e15c625bb7088db3b8acc5de0285f3a33e0d4', '["server:update"]', NULL, NULL, '2024-03-12 09:21:35', '2024-03-12 09:21:35'),
	(4, 4, 'App\\Models\\User', 1594, 'mis_MyApp', '811c8ae4d1dff58c3490223a22c08480c4c9f60dbd17a057b7070a704eab2c9b', '["server:update"]', NULL, NULL, '2024-03-12 09:22:06', '2024-03-12 09:22:06'),
	(5, 5, 'App\\Models\\User', 1594, 'mis_MyApp', 'aab38aca168b62cb389e40541ce9bd4e471ba75d08d7a316ca33eb6eb75a7974', '["server:update"]', NULL, NULL, '2024-03-12 09:22:23', '2024-03-12 09:22:23'),
	(6, 6, 'App\\Models\\User', 1594, 'mis_MyApp', 'af097c3fe799ed856ffc7970b2d41bc4806b5b6190e99260e119b556f5a00364', '["server:update"]', '2024-03-12 03:52:31', NULL, '2024-03-12 09:22:39', '2024-03-12 09:22:39'),
	(7, 7, 'App\\Models\\User', 1594, 'mis_MyApp', 'b451563d72c004d165f25eefafacfa7de03628a146d7537fb3860406ac4fd79d', '["server:update"]', '2024-03-12 03:53:43', NULL, '2024-03-12 09:23:50', '2024-03-12 09:23:50'),
	(8, 8, 'App\\Models\\User', 1594, 'mis_MyApp', '8e99a581e7e8692792bf54fb88c494177111288f870cb91b73030d0f3f665afd', '["server:update"]', '2024-03-12 03:53:52', NULL, '2024-03-12 09:39:51', '2024-03-12 09:39:51');

-- Dumping structure for table project.tms_auth_menu_detail
CREATE TABLE IF NOT EXISTS `tms_auth_menu_detail` (
  `menu_id` int(11) NOT NULL AUTO_INCREMENT,
  `module_id` varchar(50) DEFAULT NULL,
  `auth_id` varchar(200) NOT NULL,
  `submenu1` varchar(500) NOT NULL,
  `submenu2` varchar(500) DEFAULT NULL,
  `submenu3` varchar(500) DEFAULT NULL,
  `submenu4` varchar(500) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `status` varchar(2) NOT NULL DEFAULT 'Y',
  `created_by` varchar(2) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`menu_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2579 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci ROW_FORMAT=DYNAMIC;

-- Dumping data for table project.tms_auth_menu_detail: ~7 rows (approximately)
DELETE FROM `tms_auth_menu_detail`;
INSERT INTO `tms_auth_menu_detail` (`menu_id`, `module_id`, `auth_id`, `submenu1`, `submenu2`, `submenu3`, `submenu4`, `link`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
	(2409, 'tms', 'stu', 'My Profile', 'DSC', NULL, NULL, 'tms/student/userview/dsc', 'Y', NULL, '2023-02-14 13:17:22', '2022-08-17 11:18:01'),
	(2410, 'tms', 'stu', 'My Profile', 'Registration Details', NULL, NULL, 'tms/student/userview/reg_details', 'Y', NULL, '2023-02-14 13:17:25', '2022-08-17 11:18:01'),
	(2411, 'tms', 'stu', 'My Profile', 'Current Course Details', NULL, NULL, 'tms/student/userview/current_course_details', 'Y', NULL, '2023-02-14 13:17:26', '2022-08-17 11:18:01'),
	(2412, 'tms', 'stu', 'My Profile', 'All Course Details', NULL, NULL, 'tms/student/userview/courses', 'Y', NULL, '2023-02-14 13:17:26', '2022-08-17 11:18:01'),
	(2413, 'tms', 'stu', 'PHD Progress', NULL, NULL, NULL, 'tms/student/phd_progress', 'N', NULL, '2023-07-21 19:07:23', '2022-08-17 11:18:01'),
	(2414, 'tms', 'stu', 'Supervisor Selection', NULL, NULL, NULL, 'tms/student/supervisor_selection', 'N', NULL, '2023-07-21 19:07:22', '2022-08-17 11:18:01'),
	(2415, 'tms', 'hod', 'TMS', 'Guide Allotement', 'Assign Guide', NULL, 'tms/hod/guide_allotment', 'N', NULL, '2023-07-24 22:10:42', '2022-08-17 11:18:01');

-- Dumping structure for table project.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` varchar(20) NOT NULL,
  `password` varchar(150) NOT NULL,
  `ci_password` varchar(150) NOT NULL,
  `auth_id` varchar(10) NOT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `user_hash` varchar(250) DEFAULT NULL,
  `failed_attempt_cnt` int(11) DEFAULT 0,
  `success_attempt_cnt` int(11) DEFAULT 0,
  `is_blocked` int(11) DEFAULT 0,
  `status` enum('A','D','P','L','I','R','N') NOT NULL DEFAULT 'A' COMMENT 'A-Active,D-Deactive,I-Incomplete,P-Passout,R-Retiered,L-Left/Terminated',
  `remark` varchar(100) NOT NULL DEFAULT 'emp',
  PRIMARY KEY (`id`),
  KEY `auth_id` (`auth_id`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`auth_id`) REFERENCES `auth_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table project.users: ~1 rows (approximately)
DELETE FROM `users`;
INSERT INTO `users` (`id`, `password`, `ci_password`, `auth_id`, `created_date`, `updated_date`, `user_hash`, `failed_attempt_cnt`, `success_attempt_cnt`, `is_blocked`, `status`, `remark`) VALUES
	('1594', '$2y$10$OQAewjiBNjOml.RUQBpXx.BrsHnTDqPYpyIsvH4P8o4rrB7Y134.2', '8cc30530e786765a266ad6d7207084d8', 'emp', '2024-01-04 17:50:56', '2024-03-12 14:50:06', 'NoVOLyeLV9', 0, 0, 0, 'A', '');

-- Dumping structure for table project.user_auth_types
CREATE TABLE IF NOT EXISTS `user_auth_types` (
  `id` varchar(20) NOT NULL,
  `auth_id` varchar(50) NOT NULL,
  PRIMARY KEY (`id`,`auth_id`),
  KEY `auth_id` (`auth_id`),
  CONSTRAINT `user_auth_types_ibfk_1` FOREIGN KEY (`id`) REFERENCES `users` (`id`),
  CONSTRAINT `user_auth_types_ibfk_2` FOREIGN KEY (`auth_id`) REFERENCES `auth_types` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table project.user_auth_types: ~3 rows (approximately)
DELETE FROM `user_auth_types`;
INSERT INTO `user_auth_types` (`id`, `auth_id`) VALUES
	('1594', 'dept_pns'),
	('1594', 'pns_da1'),
	('1594', 'stock_assistant_cc');

-- Dumping structure for table project.user_auth_types_extension
CREATE TABLE IF NOT EXISTS `user_auth_types_extension` (
  `id` varchar(20) NOT NULL,
  `auth_id` varchar(20) NOT NULL,
  `from` date NOT NULL,
  `to` date NOT NULL,
  `status` enum('A','D') NOT NULL,
  PRIMARY KEY (`id`,`status`,`auth_id`) USING BTREE,
  KEY `auth_id` (`auth_id`),
  CONSTRAINT `FK_user_auth_types_extension_auth_types_extension` FOREIGN KEY (`auth_id`) REFERENCES `auth_types_extension` (`id`),
  CONSTRAINT `user_auth_types_extension_ibfk_1` FOREIGN KEY (`id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table project.user_auth_types_extension: ~0 rows (approximately)
DELETE FROM `user_auth_types_extension`;

-- Dumping structure for table project.user_details
CREATE TABLE IF NOT EXISTS `user_details` (
  `id` varchar(20) NOT NULL COMMENT 'originally 11',
  `salutation` varchar(25) DEFAULT NULL,
  `first_name` varchar(150) NOT NULL,
  `middle_name` varchar(150) DEFAULT NULL,
  `last_name` varchar(150) DEFAULT NULL,
  `sex` varchar(10) NOT NULL,
  `category` varchar(25) DEFAULT NULL,
  `allocated_category` varchar(25) DEFAULT NULL,
  `dob` date NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `photopath` varchar(150) NOT NULL,
  `marital_status` varchar(20) DEFAULT NULL,
  `physically_challenged` varchar(5) DEFAULT NULL,
  `dept_id` varchar(11) NOT NULL,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE,
  KEY `dept_id` (`dept_id`) USING BTREE,
  KEY `Index 3` (`id`) USING BTREE,
  CONSTRAINT `user_details_ibfk_1` FOREIGN KEY (`dept_id`) REFERENCES `departments` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci ROW_FORMAT=DYNAMIC;

-- Dumping data for table project.user_details: ~1 rows (approximately)
DELETE FROM `user_details`;
INSERT INTO `user_details` (`id`, `salutation`, `first_name`, `middle_name`, `last_name`, `sex`, `category`, `allocated_category`, `dob`, `email`, `photopath`, `marital_status`, `physically_challenged`, `dept_id`, `updated`) VALUES
	('1594', 'Mr', 'Abhijeet', 'Kumar', 'Upadhyay', 'm', 'General', '', '1993-01-25', 'abhijeet@gmail.com', 'null', 'null', 'no', 'auce', '2024-03-12 09:15:18');

-- Dumping structure for table project.user_login_attempts
CREATE TABLE IF NOT EXISTS `user_login_attempts` (
  `id` varchar(11) NOT NULL,
  `time` datetime NOT NULL,
  `password` varchar(150) NOT NULL,
  `status` varchar(20) NOT NULL,
  `ip` varchar(20) NOT NULL,
  PRIMARY KEY (`id`,`time`),
  CONSTRAINT `user_login_attempts_ibfk_1` FOREIGN KEY (`id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table project.user_login_attempts: ~6 rows (approximately)
DELETE FROM `user_login_attempts`;
INSERT INTO `user_login_attempts` (`id`, `time`, `password`, `status`, `ip`) VALUES
	('1594', '2024-03-12 09:06:22', 'p', 'Success', '127.0.0.1'),
	('1594', '2024-03-12 09:23:22', 'p', 'Success', '127.0.0.1'),
	('1594', '2024-03-12 09:39:22', 'p', 'Success', '127.0.0.1'),
	('1594', '2024-03-12 09:50:23', 'p', 'Success', '127.0.0.1'),
	('1594', '2024-03-12 09:51:39', 'p', 'Success', '127.0.0.1'),
	('1594', '2024-03-12 09:53:19', 'p', 'Failed', '127.0.0.1');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
