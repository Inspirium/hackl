/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `attendances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `attendances` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `reservation_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `school_group_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `attendances_reservation_id_foreign` (`reservation_id`),
  KEY `attendances_user_id_foreign` (`user_id`),
  KEY `attendances_school_group_id_foreign` (`school_group_id`),
  CONSTRAINT `attendances_reservation_id_foreign` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `attendances_school_group_id_foreign` FOREIGN KEY (`school_group_id`) REFERENCES `school_groups` (`id`) ON DELETE CASCADE,
  CONSTRAINT `attendances_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `audits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `audits` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `event` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `auditable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `auditable_id` bigint unsigned NOT NULL,
  `old_values` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `new_values` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(1023) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tags` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `audits_auditable_type_auditable_id_index` (`auditable_type`,`auditable_id`),
  KEY `audits_user_id_user_type_index` (`user_id`,`user_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `business_units`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `business_units` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `business_data` json DEFAULT NULL,
  `club_id` bigint unsigned DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `invoice_number_structure` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `operator_id` bigint unsigned DEFAULT NULL,
  `has_fiscalization` tinyint(1) NOT NULL DEFAULT '0',
  `operator_oib` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `business_units_club_id_foreign` (`club_id`),
  KEY `business_units_operator_id_foreign` (`operator_id`),
  CONSTRAINT `business_units_club_id_foreign` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`id`),
  CONSTRAINT `business_units_operator_id_foreign` FOREIGN KEY (`operator_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cities` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:json)',
  `country_id` bigint unsigned NOT NULL,
  `latitude` double(16,8) DEFAULT NULL,
  `longitude` double(16,8) DEFAULT NULL,
  `timezone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cities_country_id_foreign` (`country_id`),
  CONSTRAINT `cities_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `classifieds`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `classifieds` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `price` double(8,2) DEFAULT NULL,
  `user_id` bigint unsigned NOT NULL,
  `image` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `category` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `club_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `classifieds_user_id_foreign` (`user_id`),
  KEY `classifieds_club_id_foreign` (`club_id`),
  CONSTRAINT `classifieds_club_id_foreign` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `classifieds_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `club_admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `club_admin` (
  `club_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `level` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `club_admin_club_id_foreign` (`club_id`),
  KEY `club_admin_user_id_foreign` (`user_id`),
  CONSTRAINT `club_admin_club_id_foreign` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `club_admin_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `club_socials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `club_socials` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `service` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `club_id` bigint unsigned NOT NULL,
  `data` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `club_socials_club_id_foreign` (`club_id`),
  CONSTRAINT `club_socials_club_id_foreign` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `club_sport`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `club_sport` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `club_id` bigint unsigned NOT NULL,
  `sport_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `club_sport_club_id_foreign` (`club_id`),
  KEY `club_sport_sport_id_foreign` (`sport_id`),
  CONSTRAINT `club_sport_club_id_foreign` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `club_sport_sport_id_foreign` FOREIGN KEY (`sport_id`) REFERENCES `sports` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `club_team`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `club_team` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `club_id` bigint unsigned NOT NULL,
  `team_id` bigint unsigned NOT NULL,
  `power` double DEFAULT NULL,
  `rank` int DEFAULT NULL,
  `rating` double NOT NULL DEFAULT '1500',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `club_team_club_id_foreign` (`club_id`),
  KEY `club_team_team_id_foreign` (`team_id`),
  CONSTRAINT `club_team_club_id_foreign` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `club_team_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `club_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `club_user` (
  `club_id` bigint unsigned NOT NULL,
  `player_id` bigint unsigned NOT NULL,
  `rank` int DEFAULT '0',
  `power` double(8,2) DEFAULT '0.00',
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `active_membership` bigint unsigned DEFAULT NULL,
  `messages` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `cashier` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `club_user_club_id_foreign` (`club_id`),
  KEY `club_user_player_id_foreign` (`player_id`),
  CONSTRAINT `club_user_club_id_foreign` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `club_user_player_id_foreign` FOREIGN KEY (`player_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `club_user_chk_1` CHECK (json_valid(`messages`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `clubs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clubs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subdomain` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `domain` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `postal_code` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `county` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `region` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `fax` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `email` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `latitude` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `longitude` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `has_players` tinyint(1) NOT NULL DEFAULT '1',
  `has_courts` tinyint(1) NOT NULL DEFAULT '1',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `logo` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `weather` tinyint(1) DEFAULT NULL,
  `main_thread` bigint unsigned DEFAULT NULL,
  `validate_user` tinyint(1) DEFAULT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'classic',
  `hide_personal_data` tinyint(1) DEFAULT NULL,
  `hide_ranks` tinyint(1) DEFAULT NULL,
  `hero_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `social` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `payment_accontation` tinyint(1) DEFAULT NULL,
  `payment_online` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `weather_message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `show_competitions` tinyint(1) NOT NULL DEFAULT '1',
  `is_w2p` tinyint(1) NOT NULL DEFAULT '0',
  `country_id` bigint unsigned DEFAULT NULL,
  `tax_class_id` bigint unsigned DEFAULT NULL,
  `business_data` json DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `clubs_main_thread_foreign` (`main_thread`),
  KEY `clubs_country_id_foreign` (`country_id`),
  KEY `clubs_tax_class_id_foreign` (`tax_class_id`),
  CONSTRAINT `clubs_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`),
  CONSTRAINT `clubs_main_thread_foreign` FOREIGN KEY (`main_thread`) REFERENCES `threads` (`id`),
  CONSTRAINT `clubs_tax_class_id_foreign` FOREIGN KEY (`tax_class_id`) REFERENCES `tax_classes` (`id`),
  CONSTRAINT `clubs_chk_1` CHECK (json_valid(`hero_images`)),
  CONSTRAINT `clubs_chk_2` CHECK (json_valid(`social`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `comments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `player_id` bigint unsigned DEFAULT NULL,
  `commentable_id` int NOT NULL,
  `commentable_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `multimedia` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `multimedia_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `comments_player_id_foreign` (`player_id`),
  CONSTRAINT `comments_player_id_foreign` FOREIGN KEY (`player_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `companies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `companies` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `vat_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postal_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_id` bigint unsigned DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `companies_country_id_foreign` (`country_id`),
  CONSTRAINT `companies_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `company_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `company_user` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `company_user_company_id_foreign` (`company_id`),
  KEY `company_user_user_id_foreign` (`user_id`),
  CONSTRAINT `company_user_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  CONSTRAINT `company_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `competition_team`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `competition_team` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `competition_id` bigint unsigned NOT NULL,
  `team_id` bigint unsigned NOT NULL,
  `points` int NOT NULL DEFAULT '0',
  `played` int NOT NULL DEFAULT '0',
  `won` int NOT NULL DEFAULT '0',
  `drawn` int NOT NULL DEFAULT '0',
  `lost` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `competition_team_competition_id_foreign` (`competition_id`),
  KEY `competition_team_team_id_foreign` (`team_id`),
  CONSTRAINT `competition_team_competition_id_foreign` FOREIGN KEY (`competition_id`) REFERENCES `competitions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `competition_team_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `competitions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `competitions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `club_id` bigint unsigned NOT NULL,
  `scoring` json DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `competitions_club_id_foreign` (`club_id`),
  CONSTRAINT `competitions_club_id_foreign` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `countries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `countries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:json)',
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `locale` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `flag` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` double(8,2) DEFAULT NULL,
  `longitude` double(8,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `court_sport`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `court_sport` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `court_id` bigint unsigned DEFAULT NULL,
  `sport_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `court_sport_court_id_foreign` (`court_id`),
  KEY `court_sport_sport_id_foreign` (`sport_id`),
  CONSTRAINT `court_sport_court_id_foreign` FOREIGN KEY (`court_id`) REFERENCES `courts` (`id`),
  CONSTRAINT `court_sport_sport_id_foreign` FOREIGN KEY (`sport_id`) REFERENCES `sports` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `court_weather_updates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `court_weather_updates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `court_id` bigint unsigned NOT NULL,
  `note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `from` timestamp NULL DEFAULT NULL,
  `to` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `court_weather_updates_court_id_foreign` (`court_id`),
  KEY `court_weather_updates_created_by_foreign` (`created_by`),
  CONSTRAINT `court_weather_updates_court_id_foreign` FOREIGN KEY (`court_id`) REFERENCES `courts` (`id`),
  CONSTRAINT `court_weather_updates_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `courts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `courts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `surface_id` int NOT NULL DEFAULT '9',
  `working_from` time DEFAULT NULL,
  `working_to` time DEFAULT NULL,
  `type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lights` tinyint(1) NOT NULL DEFAULT '0',
  `reservation_confirmation` tinyint(1) NOT NULL DEFAULT '0',
  `reservation_duration` int DEFAULT NULL,
  `club_id` bigint unsigned DEFAULT NULL,
  `cover` tinyint(1) DEFAULT NULL,
  `weather` tinyint(1) DEFAULT NULL,
  `reservation_hole` tinyint(1) NOT NULL DEFAULT '0',
  `order` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `hero_image` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `show_on_tenisplus` tinyint(1) DEFAULT NULL,
  `member_reservation` tinyint(1) NOT NULL DEFAULT '1',
  `weather_message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `court_break_from` timestamp NULL DEFAULT NULL,
  `court_break_to` timestamp NULL DEFAULT NULL,
  `airconditioner` tinyint(1) DEFAULT NULL,
  `wifi` tinyint(1) DEFAULT NULL,
  `heating` tinyint(1) DEFAULT NULL,
  `size` int DEFAULT NULL,
  `invalid` tinyint(1) DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `courts_club_id_foreign` (`club_id`),
  CONSTRAINT `courts_club_id_foreign` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `document_owners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `document_owners` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `document_id` bigint unsigned NOT NULL,
  `owner_id` bigint unsigned NOT NULL,
  `owner_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `document_owners_document_id_foreign` (`document_id`),
  CONSTRAINT `document_owners_document_id_foreign` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `documents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `documents_user_id_foreign` (`user_id`),
  CONSTRAINT `documents_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `equipment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `equipment` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `player_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `equipment_player_id_foreign` (`player_id`),
  CONSTRAINT `equipment_player_id_foreign` FOREIGN KEY (`player_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `equipment_chk_1` CHECK (json_valid(`data`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `fire_base_subscriptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fire_base_subscriptions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `club_id` bigint unsigned DEFAULT NULL,
  `token` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `platform` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fire_base_subscriptions_user_id_foreign` (`user_id`),
  KEY `fire_base_subscriptions_club_id_foreign` (`club_id`),
  CONSTRAINT `fire_base_subscriptions_club_id_foreign` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fire_base_subscriptions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `games`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `games` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `type_id` int DEFAULT NULL,
  `type_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `court_id` bigint unsigned DEFAULT NULL,
  `played_at` timestamp NULL DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_surrendered` int DEFAULT NULL,
  `order` bigint unsigned DEFAULT NULL,
  `admin` tinyint(1) DEFAULT NULL,
  `player` tinyint(1) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `live` tinyint(1) DEFAULT NULL,
  `round_of_play` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `games_court_id_foreign` (`court_id`),
  CONSTRAINT `games_court_id_foreign` FOREIGN KEY (`court_id`) REFERENCES `courts` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `hour_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `hour_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_paid` tinyint(1) DEFAULT NULL,
  `is_attractive` tinyint(1) DEFAULT NULL,
  `club_id` bigint unsigned NOT NULL,
  `types` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hour_categories_club_id_foreign` (`club_id`),
  CONSTRAINT `hour_categories_club_id_foreign` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`id`),
  CONSTRAINT `hour_categories_chk_1` CHECK (json_valid(`types`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `invoice_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `invoice_items` (
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `invoice_id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `invoiceable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `invoiceable_id` bigint unsigned NOT NULL,
  `quantity` int DEFAULT NULL,
  `amount` double(8,2) DEFAULT NULL,
  `taxes` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `total_amount` double(8,2) DEFAULT NULL,
  `currency` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  KEY `invoice_items_invoiceable_type_invoiceable_id_index` (`invoiceable_type`,`invoiceable_id`),
  CONSTRAINT `invoice_items_chk_1` CHECK (json_valid(`taxes`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `invoices` (
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `club_id` bigint unsigned NOT NULL,
  `operator_id` bigint unsigned NOT NULL,
  `offer_number` int DEFAULT NULL,
  `offer_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `invoice_number` int DEFAULT NULL,
  `invoice_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `amount` double(8,2) DEFAULT NULL,
  `taxes` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `total_amount` double(8,2) DEFAULT NULL,
  `currency` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_method` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_date` timestamp NULL DEFAULT NULL,
  `payment_amount` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_currency` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `fiscalization` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `note_internal` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `payment_intent` json DEFAULT NULL,
  `business_unit_id` bigint unsigned DEFAULT NULL,
  `company_id` bigint unsigned DEFAULT NULL,
  KEY `invoices_user_id_foreign` (`user_id`),
  KEY `invoices_club_id_foreign` (`club_id`),
  KEY `invoices_operator_id_foreign` (`operator_id`),
  KEY `invoices_business_unit_id_foreign` (`business_unit_id`),
  KEY `invoices_company_id_foreign` (`company_id`),
  CONSTRAINT `invoices_business_unit_id_foreign` FOREIGN KEY (`business_unit_id`) REFERENCES `business_units` (`id`),
  CONSTRAINT `invoices_club_id_foreign` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`id`),
  CONSTRAINT `invoices_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  CONSTRAINT `invoices_operator_id_foreign` FOREIGN KEY (`operator_id`) REFERENCES `users` (`id`),
  CONSTRAINT `invoices_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `invoices_chk_1` CHECK (json_valid(`offer_data`)),
  CONSTRAINT `invoices_chk_2` CHECK (json_valid(`invoice_data`)),
  CONSTRAINT `invoices_chk_3` CHECK (json_valid(`taxes`)),
  CONSTRAINT `invoices_chk_4` CHECK (json_valid(`payment_data`)),
  CONSTRAINT `invoices_chk_5` CHECK (json_valid(`fiscalization`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `league_group_player`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `league_group_player` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `league_group_id` bigint unsigned NOT NULL,
  `player_id` bigint unsigned NOT NULL,
  `score` float DEFAULT NULL,
  `player` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `set_diff` int DEFAULT NULL,
  `point_diff` int DEFAULT NULL,
  `freeze` tinyint(1) DEFAULT NULL,
  `position` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `league_group_player_league_group_id_foreign` (`league_group_id`),
  KEY `league_group_player_player_id_foreign` (`player_id`),
  CONSTRAINT `league_group_player_league_group_id_foreign` FOREIGN KEY (`league_group_id`) REFERENCES `league_groups` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `league_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `league_groups` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `league_id` bigint unsigned NOT NULL,
  `order` int DEFAULT NULL,
  `move_down` int DEFAULT NULL,
  `move_up` int DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `players_in_group` int DEFAULT NULL,
  `points_match` int DEFAULT NULL,
  `points` int DEFAULT NULL,
  `points_loser` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `league_groups_league_id_foreign` (`league_id`),
  CONSTRAINT `league_groups_league_id_foreign` FOREIGN KEY (`league_id`) REFERENCES `leagues` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `league_player`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `league_player` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `league_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `admin` tinyint(1) DEFAULT NULL,
  `player` tinyint(1) DEFAULT NULL,
  `rank` int DEFAULT NULL,
  `score` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `group_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `league_player_league_id_foreign` (`league_id`),
  KEY `league_player_user_id_foreign` (`user_id`),
  CONSTRAINT `league_player_league_id_foreign` FOREIGN KEY (`league_id`) REFERENCES `leagues` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `leagues`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `leagues` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `club_id` bigint unsigned DEFAULT NULL,
  `number_of_groups` int DEFAULT NULL,
  `players_in_groups` int DEFAULT NULL,
  `rounds_of_play` int DEFAULT NULL,
  `points` int DEFAULT NULL,
  `move_up` int DEFAULT NULL,
  `move_down` int DEFAULT NULL,
  `playing_sets` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `game_in_set` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_set` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `finish_date` timestamp NULL DEFAULT NULL,
  `start_date` datetime DEFAULT NULL,
  `finish_onboarding` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `points_loser` int DEFAULT NULL,
  `boarding_type` tinyint(1) DEFAULT NULL,
  `points_match` int DEFAULT NULL,
  `parent_id` bigint unsigned DEFAULT NULL,
  `classification` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_doubles` tinyint(1) DEFAULT NULL,
  `freeze` tinyint(1) DEFAULT NULL,
  `show_tournament` tinyint(1) DEFAULT NULL,
  `groups_custom_points` tinyint(1) NOT NULL DEFAULT '0',
  `show_on_tenisplus` tinyint(1) DEFAULT NULL,
  `show_in_club` tinyint(1) NOT NULL DEFAULT '1',
  `points_set_winner` int NOT NULL DEFAULT '0',
  `competition_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `leagues_club_id_foreign` (`club_id`),
  KEY `leagues_parent_id_foreign` (`parent_id`),
  KEY `leagues_competition_id_foreign` (`competition_id`),
  CONSTRAINT `leagues_club_id_foreign` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `leagues_competition_id_foreign` FOREIGN KEY (`competition_id`) REFERENCES `competitions` (`id`),
  CONSTRAINT `leagues_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `leagues` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `locationables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `locationables` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `location_id` bigint unsigned NOT NULL,
  `locationable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `locationable_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `locationables_location_id_foreign` (`location_id`),
  KEY `locationables_locationable_type_locationable_id_index` (`locationable_type`,`locationable_id`),
  CONSTRAINT `locationables_location_id_foreign` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `locations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `locations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `club_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `locations_club_id_foreign` (`club_id`),
  CONSTRAINT `locations_club_id_foreign` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `media` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `club_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `media_club_id_foreign` (`club_id`),
  KEY `media_user_id_foreign` (`user_id`),
  CONSTRAINT `media_club_id_foreign` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `media_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `media_imageable`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `media_imageable` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `media_id` bigint unsigned NOT NULL,
  `imageable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `imageable_id` bigint unsigned NOT NULL,
  `main` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `media_imageable_media_id_foreign` (`media_id`),
  CONSTRAINT `media_imageable_media_id_foreign` FOREIGN KEY (`media_id`) REFERENCES `media` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `memberships`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `memberships` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `club_id` bigint unsigned NOT NULL,
  `max_reservation_span` int NOT NULL DEFAULT '0',
  `is_reservation_cancelable` tinyint(1) NOT NULL DEFAULT '1',
  `reservation_cancelable` int DEFAULT '0',
  `has_discount` tinyint(1) DEFAULT '0',
  `discount_type` int DEFAULT NULL,
  `discount_amount` double(8,2) DEFAULT NULL,
  `price` double(8,2) DEFAULT NULL,
  `tax_class_id` bigint unsigned DEFAULT NULL,
  `currency` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `business_unit_id` bigint unsigned DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `public` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `basic` tinyint(1) DEFAULT NULL,
  `max_reservation_per_period` tinyint(1) DEFAULT '0',
  `max_reservation_per_period_days` int DEFAULT '0',
  `reservation_prepayment` tinyint(1) NOT NULL DEFAULT '0',
  `subscription` json DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `memberships_tax_class_id_foreign` (`tax_class_id`),
  KEY `memberships_business_unit_id_foreign` (`business_unit_id`),
  CONSTRAINT `memberships_business_unit_id_foreign` FOREIGN KEY (`business_unit_id`) REFERENCES `business_units` (`id`),
  CONSTRAINT `memberships_tax_class_id_foreign` FOREIGN KEY (`tax_class_id`) REFERENCES `tax_classes` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `messages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `thread_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `player_id` bigint unsigned NOT NULL,
  `multimedia` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `multimedia_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `messages_thread_id_foreign` (`thread_id`),
  KEY `messages_player_id_foreign` (`player_id`),
  CONSTRAINT `messages_player_id_foreign` FOREIGN KEY (`player_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `messages_thread_id_foreign` FOREIGN KEY (`thread_id`) REFERENCES `threads` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `news` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `club_id` bigint unsigned DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `news_user_id_foreign` (`user_id`),
  KEY `news_club_id_foreign` (`club_id`),
  CONSTRAINT `news_club_id_foreign` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `news_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint unsigned NOT NULL,
  `data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `oauth_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int DEFAULT NULL,
  `client_id` int NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_access_tokens_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `oauth_auth_codes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int NOT NULL,
  `client_id` int NOT NULL,
  `scopes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `oauth_clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `oauth_clients` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `provider` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `redirect` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_clients_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `oauth_personal_access_clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `oauth_personal_access_clients` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `client_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_personal_access_clients_client_id_index` (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `oauth_refresh_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `other_expenses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `other_expenses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` double(8,2) NOT NULL,
  `club_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `other_expenses_club_id_foreign` (`club_id`),
  CONSTRAINT `other_expenses_club_id_foreign` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `parents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `parents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` bigint unsigned NOT NULL,
  `child_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `parents_parent_id_foreign` (`parent_id`),
  KEY `parents_child_id_foreign` (`child_id`),
  CONSTRAINT `parents_child_id_foreign` FOREIGN KEY (`child_id`) REFERENCES `users` (`id`),
  CONSTRAINT `parents_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `participants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `participants` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `game_id` bigint unsigned NOT NULL,
  `participant_id` int NOT NULL,
  `participant_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `admin` tinyint(1) DEFAULT NULL,
  `player` tinyint(1) DEFAULT NULL,
  `order` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `participants_game_id_foreign` (`game_id`),
  CONSTRAINT `participants_game_id_foreign` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `payment_intents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payment_intents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `intent_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paymentable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `paymentable_id` bigint unsigned NOT NULL,
  `client_secret` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `stripe_data` json DEFAULT NULL,
  `payment_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payment_intents_paymentable_type_paymentable_id_index` (`paymentable_type`,`paymentable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `club_id` bigint unsigned NOT NULL,
  `amount` double(8,2) DEFAULT NULL,
  `receiver_id` bigint unsigned DEFAULT NULL,
  `type_id` bigint unsigned DEFAULT NULL,
  `type_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `from_credit` tinyint(1) DEFAULT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `wallet_id` bigint unsigned DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payments_user_id_foreign` (`user_id`),
  KEY `payments_club_id_foreign` (`club_id`),
  KEY `payments_receiver_id_foreign` (`receiver_id`),
  KEY `payments_wallet_id_foreign` (`wallet_id`),
  CONSTRAINT `payments_club_id_foreign` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`id`),
  CONSTRAINT `payments_receiver_id_foreign` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`),
  CONSTRAINT `payments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `payments_wallet_id_foreign` FOREIGN KEY (`wallet_id`) REFERENCES `wallets` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `player_profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `player_profiles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `player_id` bigint unsigned NOT NULL,
  `owner_id` bigint unsigned NOT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `player_profiles_player_id_foreign` (`player_id`),
  KEY `player_profiles_owner_id_foreign` (`owner_id`),
  CONSTRAINT `player_profiles_owner_id_foreign` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`),
  CONSTRAINT `player_profiles_player_id_foreign` FOREIGN KEY (`player_id`) REFERENCES `users` (`id`),
  CONSTRAINT `player_profiles_chk_1` CHECK (json_valid(`data`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `player_team`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `player_team` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `player_id` bigint unsigned NOT NULL,
  `team_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `player_team_player_id_foreign` (`player_id`),
  KEY `player_team_team_id_foreign` (`team_id`),
  CONSTRAINT `player_team_player_id_foreign` FOREIGN KEY (`player_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `player_team_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `reservation_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reservation_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_paid` tinyint(1) DEFAULT NULL,
  `club_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reservation_categories_club_id_foreign` (`club_id`),
  CONSTRAINT `reservation_categories_club_id_foreign` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `reservations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reservations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `court_id` bigint unsigned DEFAULT NULL,
  `from` timestamp NULL DEFAULT NULL,
  `to` timestamp NULL DEFAULT NULL,
  `price` double(8,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `school_group_id` bigint unsigned DEFAULT NULL,
  `game_id` bigint unsigned DEFAULT NULL,
  `payment_invoice` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_note` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_user_id` bigint unsigned DEFAULT NULL,
  `payment_date` datetime DEFAULT NULL,
  `category_id` bigint unsigned DEFAULT NULL,
  `series` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `needs_payment` tinyint(1) NOT NULL DEFAULT '0',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `applicant` text COLLATE utf8mb4_unicode_ci,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `note` text COLLATE utf8mb4_unicode_ci,
  `type_of_applicant` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `public_description` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `reservations_court_id_foreign` (`court_id`),
  KEY `reservations_school_group_id_foreign` (`school_group_id`),
  KEY `reservations_game_id_foreign` (`game_id`),
  KEY `reservations_payment_user_id_foreign` (`payment_user_id`),
  KEY `reservations_category_id_foreign` (`category_id`),
  CONSTRAINT `reservations_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `reservation_categories` (`id`),
  CONSTRAINT `reservations_court_id_foreign` FOREIGN KEY (`court_id`) REFERENCES `courts` (`id`),
  CONSTRAINT `reservations_game_id_foreign` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`),
  CONSTRAINT `reservations_payment_user_id_foreign` FOREIGN KEY (`payment_user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `reservations_school_group_id_foreign` FOREIGN KEY (`school_group_id`) REFERENCES `school_groups` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `result_participant`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `result_participant` (
  `result_id` bigint unsigned NOT NULL,
  `participant_id` bigint unsigned NOT NULL,
  `verified` tinyint(1) DEFAULT NULL,
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `participant_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'App\\Models\\User',
  PRIMARY KEY (`id`),
  KEY `results_users_user_id_foreign` (`participant_id`),
  KEY `results_users_result_id_foreign` (`result_id`),
  CONSTRAINT `results_users_result_id_foreign` FOREIGN KEY (`result_id`) REFERENCES `results` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `results`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `results` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sets` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `date` timestamp NULL DEFAULT NULL,
  `surface_id` bigint unsigned DEFAULT NULL,
  `non_member` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `non_court` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `official` tinyint(1) DEFAULT NULL,
  `rated` tinyint(1) DEFAULT NULL,
  `verified` tinyint(1) DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `points` double(8,2) DEFAULT NULL,
  `club_id` bigint unsigned DEFAULT NULL,
  `game_id` bigint unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `live` tinyint(1) DEFAULT NULL,
  `live_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `sport_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `results_surface_id_foreign` (`surface_id`),
  KEY `results_club_id_foreign` (`club_id`),
  KEY `results_game_id_foreign` (`game_id`),
  KEY `results_sport_id_foreign` (`sport_id`),
  CONSTRAINT `results_club_id_foreign` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`id`) ON DELETE SET NULL,
  CONSTRAINT `results_game_id_foreign` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`),
  CONSTRAINT `results_sport_id_foreign` FOREIGN KEY (`sport_id`) REFERENCES `sports` (`id`),
  CONSTRAINT `results_surface_id_foreign` FOREIGN KEY (`surface_id`) REFERENCES `surfaces` (`id`) ON DELETE SET NULL,
  CONSTRAINT `results_chk_1` CHECK (json_valid(`live_data`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `school_group_trainer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `school_group_trainer` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_group_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `school_group_trainer_school_group_id_foreign` (`school_group_id`),
  KEY `school_group_trainer_user_id_foreign` (`user_id`),
  CONSTRAINT `school_group_trainer_school_group_id_foreign` FOREIGN KEY (`school_group_id`) REFERENCES `school_groups` (`id`) ON DELETE CASCADE,
  CONSTRAINT `school_group_trainer_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `school_group_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `school_group_user` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_group_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `team_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `school_group_user_school_group_id_foreign` (`school_group_id`),
  KEY `school_group_user_user_id_foreign` (`user_id`),
  KEY `school_group_user_team_id_foreign` (`team_id`),
  CONSTRAINT `school_group_user_school_group_id_foreign` FOREIGN KEY (`school_group_id`) REFERENCES `school_groups` (`id`) ON DELETE CASCADE,
  CONSTRAINT `school_group_user_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`),
  CONSTRAINT `school_group_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `school_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `school_groups` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `club_id` bigint unsigned DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trainer_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `thread_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `school_groups_club_id_foreign` (`club_id`),
  KEY `school_groups_trainer_id_foreign` (`trainer_id`),
  KEY `school_groups_thread_id_foreign` (`thread_id`),
  CONSTRAINT `school_groups_club_id_foreign` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `school_groups_thread_id_foreign` FOREIGN KEY (`thread_id`) REFERENCES `threads` (`id`) ON DELETE CASCADE,
  CONSTRAINT `school_groups_trainer_id_foreign` FOREIGN KEY (`trainer_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `school_performances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `school_performances` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `player_id` bigint unsigned NOT NULL,
  `trainer_id` bigint unsigned NOT NULL,
  `school_group_id` bigint unsigned NOT NULL,
  `reservation_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `school_performances_player_id_foreign` (`player_id`),
  KEY `school_performances_trainer_id_foreign` (`trainer_id`),
  KEY `school_performances_school_group_id_foreign` (`school_group_id`),
  KEY `school_performances_reservation_id_foreign` (`reservation_id`),
  CONSTRAINT `school_performances_player_id_foreign` FOREIGN KEY (`player_id`) REFERENCES `users` (`id`),
  CONSTRAINT `school_performances_reservation_id_foreign` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`id`),
  CONSTRAINT `school_performances_school_group_id_foreign` FOREIGN KEY (`school_group_id`) REFERENCES `school_groups` (`id`),
  CONSTRAINT `school_performances_trainer_id_foreign` FOREIGN KEY (`trainer_id`) REFERENCES `users` (`id`),
  CONSTRAINT `school_performances_chk_1` CHECK (json_valid(`data`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `shop_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `shop_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_id` bigint unsigned DEFAULT NULL,
  `club_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `shop_categories_parent_id_foreign` (`parent_id`),
  KEY `shop_categories_club_id_foreign` (`club_id`),
  CONSTRAINT `shop_categories_club_id_foreign` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `shop_categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `shop_categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `shop_order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `shop_order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL,
  `price` double(8,2) NOT NULL,
  `tax_total` double(8,2) NOT NULL,
  `total` double(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  PRIMARY KEY (`id`),
  KEY `shop_order_items_order_id_foreign` (`order_id`),
  KEY `shop_order_items_product_id_foreign` (`product_id`),
  CONSTRAINT `shop_order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `shop_orders` (`id`),
  CONSTRAINT `shop_order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `shop_products` (`id`),
  CONSTRAINT `shop_order_items_chk_1` CHECK (json_valid(`data`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `shop_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `shop_orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `club_id` bigint unsigned DEFAULT NULL,
  `buyer_id` bigint unsigned NOT NULL,
  `total` double(8,2) NOT NULL,
  `tax_total` double(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `creator_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `shop_orders_club_id_foreign` (`club_id`),
  KEY `shop_orders_buyer_id_foreign` (`buyer_id`),
  KEY `shop_orders_creator_id_foreign` (`creator_id`),
  CONSTRAINT `shop_orders_buyer_id_foreign` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`id`),
  CONSTRAINT `shop_orders_club_id_foreign` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`id`),
  CONSTRAINT `shop_orders_creator_id_foreign` FOREIGN KEY (`creator_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `shop_products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `shop_products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `price` double(8,2) DEFAULT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `club_id` bigint unsigned DEFAULT NULL,
  `tax_percent` double(8,2) DEFAULT NULL,
  `special` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `category_id` bigint unsigned DEFAULT NULL,
  `stock` int DEFAULT '1',
  `sku` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `waiting_list` tinyint DEFAULT NULL,
  `wish_list` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `shop_products_club_id_foreign` (`club_id`),
  KEY `shop_products_category_id_foreign` (`category_id`),
  CONSTRAINT `shop_products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `shop_categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `shop_products_club_id_foreign` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sponsors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sponsors` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `club_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sponsors_club_id_foreign` (`club_id`),
  CONSTRAINT `sponsors_club_id_foreign` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sports` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `sports_chk_1` CHECK (json_valid(`options`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `subscription_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `subscription_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `subscription_id` bigint unsigned NOT NULL,
  `stripe_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `stripe_product` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `stripe_price` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `subscription_items_subscription_id_stripe_price_unique` (`subscription_id`,`stripe_price`),
  UNIQUE KEY `subscription_items_stripe_id_unique` (`stripe_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `subscription_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `subscription_user` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_email_sent` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `subscriptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `subscriptions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `price` double(8,2) DEFAULT NULL,
  `currency` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `interval` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `bank_account` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_account_owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_account_owner_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_account_owner_address2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sending_bills` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `warnings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `bank_model` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_call_number_format` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `club_id` bigint unsigned DEFAULT NULL,
  `business_unit_id` bigint unsigned DEFAULT NULL,
  `tax_class_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subscriptions_club_id_foreign` (`club_id`),
  KEY `subscriptions_business_unit_id_foreign` (`business_unit_id`),
  KEY `subscriptions_tax_class_id_foreign` (`tax_class_id`),
  CONSTRAINT `subscriptions_business_unit_id_foreign` FOREIGN KEY (`business_unit_id`) REFERENCES `business_units` (`id`),
  CONSTRAINT `subscriptions_club_id_foreign` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`id`),
  CONSTRAINT `subscriptions_tax_class_id_foreign` FOREIGN KEY (`tax_class_id`) REFERENCES `tax_classes` (`id`),
  CONSTRAINT `subscriptions_chk_1` CHECK (json_valid(`warnings`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `surfaces`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `surfaces` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `designation` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `badge` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `fill` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `reserved` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `tax_classes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tax_classes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `rate` double(8,2) NOT NULL,
  `active_from` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active_to` timestamp NULL DEFAULT NULL,
  `country_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tax_classes_country_id_foreign` (`country_id`),
  CONSTRAINT `tax_classes_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `teams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `teams` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `display_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rank` double DEFAULT NULL,
  `rating` int DEFAULT '1500',
  `power` double DEFAULT NULL,
  `primary_contact_id` bigint unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `number_of_players` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `teams_primary_contact_id_foreign` (`primary_contact_id`),
  CONSTRAINT `teams_primary_contact_id_foreign` FOREIGN KEY (`primary_contact_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `threads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `threads` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `club_id` bigint unsigned DEFAULT NULL,
  `public` tinyint(1) DEFAULT NULL,
  `threadable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `threadable_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `threads_club_id_foreign` (`club_id`),
  KEY `threads_threadable_type_threadable_id_index` (`threadable_type`,`threadable_id`),
  CONSTRAINT `threads_club_id_foreign` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `threads_players`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `threads_players` (
  `player_id` bigint unsigned NOT NULL,
  `thread_id` bigint unsigned NOT NULL,
  `owner` tinyint(1) DEFAULT NULL,
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `tournament_player`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tournament_player` (
  `tournament_id` int DEFAULT NULL,
  `player_id` int DEFAULT NULL,
  `player_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin` tinyint(1) DEFAULT NULL,
  `player` tinyint(1) DEFAULT NULL,
  `seed` bigint unsigned DEFAULT NULL,
  `final_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `final_score` int DEFAULT NULL,
  `final_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `tournament_rounds`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tournament_rounds` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tournament_id` bigint unsigned DEFAULT NULL,
  `marker` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order` bigint unsigned DEFAULT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tournament_rounds_tournament_id_foreign` (`tournament_id`),
  CONSTRAINT `tournament_rounds_tournament_id_foreign` FOREIGN KEY (`tournament_id`) REFERENCES `tournaments` (`id`),
  CONSTRAINT `tournament_rounds_chk_1` CHECK (json_valid(`data`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `tournaments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tournaments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `number_of_players` int DEFAULT NULL,
  `club_id` bigint unsigned DEFAULT NULL,
  `access_level` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `type_of_registration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `application_deadline` timestamp NULL DEFAULT NULL,
  `active_from` timestamp NULL DEFAULT NULL,
  `active_to` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `league_id` bigint unsigned DEFAULT NULL,
  `show_on_tenisplus` tinyint(1) DEFAULT NULL,
  `show_in_club` tinyint(1) NOT NULL DEFAULT '1',
  `competition_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tournaments_club_id_foreign` (`club_id`),
  KEY `tournaments_league_id_foreign` (`league_id`),
  KEY `tournaments_competition_id_foreign` (`competition_id`),
  CONSTRAINT `tournaments_club_id_foreign` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tournaments_competition_id_foreign` FOREIGN KEY (`competition_id`) REFERENCES `competitions` (`id`),
  CONSTRAINT `tournaments_league_id_foreign` FOREIGN KEY (`league_id`) REFERENCES `leagues` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `trainer_notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `trainer_notes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `trainer_id` bigint unsigned NOT NULL,
  `team_id` bigint unsigned NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `club_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `is_public` tinyint(1) DEFAULT NULL,
  `videos` json DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trainer_notes_trainer_id_foreign` (`trainer_id`),
  KEY `trainer_notes_team_id_foreign` (`team_id`),
  KEY `trainer_notes_club_id_foreign` (`club_id`),
  CONSTRAINT `trainer_notes_club_id_foreign` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`id`),
  CONSTRAINT `trainer_notes_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`),
  CONSTRAINT `trainer_notes_trainer_id_foreign` FOREIGN KEY (`trainer_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `trainers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `trainers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `show_phone` tinyint(1) NOT NULL DEFAULT '0',
  `price` double(8,2) DEFAULT NULL,
  `court_included` tinyint(1) NOT NULL DEFAULT '0',
  `available` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trainers_user_id_foreign` (`user_id`),
  CONSTRAINT `trainers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `user_membership`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_membership` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `membership_id` bigint unsigned NOT NULL,
  `price` double(8,2) DEFAULT NULL,
  `payment_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_membership_user_id_foreign` (`user_id`),
  KEY `user_membership_membership_id_foreign` (`membership_id`),
  KEY `user_membership_payment_id_foreign` (`payment_id`),
  CONSTRAINT `user_membership_membership_id_foreign` FOREIGN KEY (`membership_id`) REFERENCES `memberships` (`id`),
  CONSTRAINT `user_membership_payment_id_foreign` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`),
  CONSTRAINT `user_membership_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `user_socials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_socials` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `social_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `social_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `token` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `service` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_socials_user_id_foreign` (`user_id`),
  CONSTRAINT `user_socials_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `user_subscriptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_subscriptions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `subscription_id` bigint unsigned NOT NULL,
  `is_paused` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `team_id` bigint unsigned DEFAULT NULL,
  `subscribable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `subscribable_id` bigint unsigned NOT NULL,
  `price` double(8,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_subscriptions_subscription_id_foreign` (`subscription_id`),
  KEY `user_subscriptions_team_id_foreign` (`team_id`),
  KEY `user_subscriptions_subscribable_type_subscribable_id_index` (`subscribable_type`,`subscribable_id`),
  CONSTRAINT `user_subscriptions_subscription_id_foreign` FOREIGN KEY (`subscription_id`) REFERENCES `subscriptions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_subscriptions_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `lang` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'hr',
  `first_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `oib` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `postal_code` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `county` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` char(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `birthyear` int DEFAULT NULL,
  `rating` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `power_club` double(8,2) DEFAULT '0.00',
  `power_global` double(8,2) DEFAULT '0.00',
  `rank_club` int DEFAULT NULL,
  `rank_global` int DEFAULT NULL,
  `primary_club_id` bigint unsigned DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `notifications_settings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `is_doubles` int DEFAULT NULL,
  `stripe_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pm_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pm_last_four` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trial_ends_at` timestamp NULL DEFAULT NULL,
  `hidden_fields` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `hidden_notifications` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_primary_club_id_foreign` (`primary_club_id`),
  KEY `users_stripe_id_index` (`stripe_id`),
  CONSTRAINT `users_primary_club_id_foreign` FOREIGN KEY (`primary_club_id`) REFERENCES `clubs` (`id`) ON DELETE SET NULL,
  CONSTRAINT `users_chk_1` CHECK (json_valid(`notifications_settings`)),
  CONSTRAINT `users_chk_2` CHECK (json_valid(`hidden_fields`)),
  CONSTRAINT `users_chk_3` CHECK (json_valid(`hidden_notifications`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `users_reservations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users_reservations` (
  `user_id` bigint unsigned NOT NULL,
  `reservation_id` bigint unsigned NOT NULL,
  `status` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `users_reservations_user_id_foreign` (`user_id`),
  KEY `users_reservations_reservation_id_foreign` (`reservation_id`),
  CONSTRAINT `users_reservations_reservation_id_foreign` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `wallet_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wallet_transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `wallet_id` bigint unsigned NOT NULL,
  `note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `amount` double NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `storno` tinyint(1) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `wallet_transactions_wallet_id_foreign` (`wallet_id`),
  KEY `wallet_transactions_user_id_foreign` (`user_id`),
  CONSTRAINT `wallet_transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `wallet_transactions_wallet_id_foreign` FOREIGN KEY (`wallet_id`) REFERENCES `wallets` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `wallets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wallets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `owner_id` bigint unsigned NOT NULL,
  `club_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `wallets_owner_id_foreign` (`owner_id`),
  KEY `wallets_club_id_foreign` (`club_id`),
  CONSTRAINT `wallets_club_id_foreign` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `wallets_owner_id_foreign` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `websockets_statistics_entries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `websockets_statistics_entries` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `app_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `peak_connection_count` int NOT NULL,
  `websocket_message_count` int NOT NULL,
  `api_message_count` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `work_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `work_orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `orderable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `orderable_id` bigint unsigned NOT NULL,
  `assignee_id` bigint unsigned NOT NULL,
  `assigner_id` bigint unsigned NOT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `club_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `work_orders_assignee_id_foreign` (`assignee_id`),
  KEY `work_orders_assigner_id_foreign` (`assigner_id`),
  KEY `work_orders_club_id_foreign` (`club_id`),
  CONSTRAINT `work_orders_assignee_id_foreign` FOREIGN KEY (`assignee_id`) REFERENCES `users` (`id`),
  CONSTRAINT `work_orders_assigner_id_foreign` FOREIGN KEY (`assigner_id`) REFERENCES `users` (`id`),
  CONSTRAINT `work_orders_club_id_foreign` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `work_orders_chk_1` CHECK (json_valid(`data`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `working_hours`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `working_hours` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `court_id` bigint unsigned NOT NULL,
  `cron` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `working` tinyint(1) DEFAULT NULL,
  `active_from` timestamp NULL DEFAULT NULL,
  `active_to` timestamp NULL DEFAULT NULL,
  `price` double(8,2) NOT NULL,
  `membership_id` bigint unsigned DEFAULT NULL,
  `category_id` bigint unsigned DEFAULT NULL,
  `tax_class_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `working_hours_court_id_foreign` (`court_id`),
  KEY `working_hours_membership_id_foreign` (`membership_id`),
  KEY `working_hours_category_id_foreign` (`category_id`),
  KEY `working_hours_tax_class_id_foreign` (`tax_class_id`),
  CONSTRAINT `working_hours_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `hour_categories` (`id`),
  CONSTRAINT `working_hours_court_id_foreign` FOREIGN KEY (`court_id`) REFERENCES `courts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `working_hours_membership_id_foreign` FOREIGN KEY (`membership_id`) REFERENCES `memberships` (`id`),
  CONSTRAINT `working_hours_tax_class_id_foreign` FOREIGN KEY (`tax_class_id`) REFERENCES `tax_classes` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

INSERT INTO `migrations` VALUES (136,'2014_10_12_000000_create_users_table',1);
INSERT INTO `migrations` VALUES (137,'2014_10_12_100000_create_password_resets_table',1);
INSERT INTO `migrations` VALUES (138,'2016_06_01_000001_create_oauth_auth_codes_table',1);
INSERT INTO `migrations` VALUES (139,'2016_06_01_000002_create_oauth_access_tokens_table',1);
INSERT INTO `migrations` VALUES (140,'2016_06_01_000003_create_oauth_refresh_tokens_table',1);
INSERT INTO `migrations` VALUES (141,'2016_06_01_000004_create_oauth_clients_table',1);
INSERT INTO `migrations` VALUES (142,'2016_06_01_000005_create_oauth_personal_access_clients_table',1);
INSERT INTO `migrations` VALUES (143,'2017_08_02_185925_create_clubs_table',1);
INSERT INTO `migrations` VALUES (144,'2017_08_02_185937_create_courts_table',1);
INSERT INTO `migrations` VALUES (145,'2017_08_02_190022_create_reservations_table',1);
INSERT INTO `migrations` VALUES (146,'2017_08_05_205036_create_court_prices_table',1);
INSERT INTO `migrations` VALUES (147,'2017_08_08_091315_create_club_user_pivot',1);
INSERT INTO `migrations` VALUES (148,'2017_08_08_091345_create_club_admin_pivot',1);
INSERT INTO `migrations` VALUES (149,'2017_08_09_145732_create_user_reservation_pivot',1);
INSERT INTO `migrations` VALUES (150,'2017_08_11_083507_create_surfaces_table',1);
INSERT INTO `migrations` VALUES (151,'2018_04_05_203500_create_results_table',1);
INSERT INTO `migrations` VALUES (152,'2018_04_05_204703_create_results_users_pivot_table',1);
INSERT INTO `migrations` VALUES (153,'2018_04_07_211534_create_notifications_table',1);
INSERT INTO `migrations` VALUES (154,'2018_04_17_110013_update_users_table',2);
INSERT INTO `migrations` VALUES (155,'2018_04_17_111627_update_results_table',2);
INSERT INTO `migrations` VALUES (156,'2018_04_19_103240_update_users_table2',3);
INSERT INTO `migrations` VALUES (157,'2018_04_24_113535_create_comments_table',4);
INSERT INTO `migrations` VALUES (158,'2018_04_24_114644_create_messages_table',4);
INSERT INTO `migrations` VALUES (159,'2018_04_24_122736_create_threads_table',4);
INSERT INTO `migrations` VALUES (160,'2018_04_24_123020_create_threads_players_table',4);
INSERT INTO `migrations` VALUES (161,'2018_05_08_214508_create_classifieds_table',5);
INSERT INTO `migrations` VALUES (162,'2018_05_09_152750_update_courts_table',6);
INSERT INTO `migrations` VALUES (163,'2018_05_18_141816_update_clubs_table',6);
INSERT INTO `migrations` VALUES (164,'2018_05_18_154054_update_club_user_pivot',6);
INSERT INTO `migrations` VALUES (165,'2018_05_18_155224_create_working_hours_table',6);
INSERT INTO `migrations` VALUES (166,'2018_05_22_152951_make_club_threads',7);
INSERT INTO `migrations` VALUES (167,'2018_05_28_114547_create_failed_jobs_table',8);
INSERT INTO `migrations` VALUES (168,'2018_05_25_142442_update_club_relations',9);
INSERT INTO `migrations` VALUES (169,'2018_06_14_230421_add_price_to_working_hours',10);
INSERT INTO `migrations` VALUES (170,'2018_06_29_122940_add_primary_club',11);
INSERT INTO `migrations` VALUES (171,'2018_07_02_131202_create_tournaments_table',12);
INSERT INTO `migrations` VALUES (172,'2018_07_02_135801_create_brackets_table',12);
INSERT INTO `migrations` VALUES (173,'2018_07_02_135846_create_tournament_player_table',12);
INSERT INTO `migrations` VALUES (174,'2018_07_02_153831_create_teams_table',12);
INSERT INTO `migrations` VALUES (175,'2018_07_04_140832_create_player_team_table',12);
INSERT INTO `migrations` VALUES (176,'2018_07_14_215518_modify_results_table',12);
INSERT INTO `migrations` VALUES (177,'2020_06_05_140738_update_oauth_clients',13);
INSERT INTO `migrations` VALUES (178,'2020_07_10_163631_create_user_socials_table',14);
INSERT INTO `migrations` VALUES (179,'2020_07_15_151709_update_users_table3',15);
INSERT INTO `migrations` VALUES (180,'2020_07_22_134156_create_news_table',16);
INSERT INTO `migrations` VALUES (181,'2020_08_03_221210_create_websockets_statistics_entries_table',17);
INSERT INTO `migrations` VALUES (182,'2020_10_03_110654_create_trainers_table',18);
INSERT INTO `migrations` VALUES (183,'2020_10_03_224350_modify_columns_add_foreign_keys',18);
INSERT INTO `migrations` VALUES (184,'2020_10_06_214520_modify_courts_table',19);
INSERT INTO `migrations` VALUES (185,'2020_10_07_173605_update_courts_table1',20);
INSERT INTO `migrations` VALUES (186,'2020_10_07_205515_create_memberships_table',21);
INSERT INTO `migrations` VALUES (187,'2020_10_08_105309_create_user_membership_table',22);
INSERT INTO `migrations` VALUES (188,'2020_10_08_110405_create_payments_table',22);
INSERT INTO `migrations` VALUES (189,'2020_10_08_151947_create_other_expenses_table',22);
INSERT INTO `migrations` VALUES (190,'2020_10_08_153139_update_membership_tables',23);
INSERT INTO `migrations` VALUES (191,'2020_10_13_123745_fix_results_table',24);
INSERT INTO `migrations` VALUES (192,'2020_10_13_164320_modify_court_table',25);
INSERT INTO `migrations` VALUES (193,'2020_10_13_231625_update_club_admin_table',26);
INSERT INTO `migrations` VALUES (194,'2020_10_20_132824_create_push_subscriptions_table',27);
INSERT INTO `migrations` VALUES (195,'2020_10_21_161809_drop_webpush_table',28);
INSERT INTO `migrations` VALUES (196,'2020_10_21_215017_create_fire_base_subscriptions_table',28);
INSERT INTO `migrations` VALUES (197,'2020_11_05_131402_create_school_groups_table',29);
INSERT INTO `migrations` VALUES (198,'2020_11_06_143924_update_reservations_table',30);
INSERT INTO `migrations` VALUES (199,'2020_11_10_223615_create_attendances_table',31);
INSERT INTO `migrations` VALUES (200,'2020_11_11_210909_create_tournaments_table',32);
INSERT INTO `migrations` VALUES (201,'2020_11_12_145916_update_school_groups_table',33);
INSERT INTO `migrations` VALUES (205,'2020_12_01_114229_create_leagues_table',34);
INSERT INTO `migrations` VALUES (206,'2020_12_01_221432_create_league_player_table',34);
INSERT INTO `migrations` VALUES (207,'2021_06_14_203359_create_league_groups_table',35);
INSERT INTO `migrations` VALUES (208,'2021_07_05_103432_create_league_group_player_table',36);
INSERT INTO `migrations` VALUES (209,'2021_07_08_215021_update_league_table',37);
INSERT INTO `migrations` VALUES (210,'2021_07_12_215729_create_games_table',38);
INSERT INTO `migrations` VALUES (211,'2021_07_13_160328_create_participants_table',38);
INSERT INTO `migrations` VALUES (212,'2021_07_14_134112_update_leagues_table',39);
INSERT INTO `migrations` VALUES (213,'2021_07_17_160907_update_games_table',40);
INSERT INTO `migrations` VALUES (214,'2021_07_19_161544_create_documents_table',41);
INSERT INTO `migrations` VALUES (215,'2021_07_19_163640_create_document_owners_table',41);
INSERT INTO `migrations` VALUES (216,'2021_07_21_223127_update_leagues_table2',42);
INSERT INTO `migrations` VALUES (217,'2022_04_04_164247_update_leagues_table3',43);
INSERT INTO `migrations` VALUES (218,'2022_04_05_232256_create_audits_table',44);
INSERT INTO `migrations` VALUES (219,'2022_04_26_154045_create_tournament_rounds_table',45);
INSERT INTO `migrations` VALUES (220,'2022_04_26_174053_update_games_table2',45);
INSERT INTO `migrations` VALUES (221,'2022_04_27_092647_fix_participants',46);
INSERT INTO `migrations` VALUES (222,'2022_04_27_110302_fix_participants2',47);
INSERT INTO `migrations` VALUES (223,'2022_05_02_224907_add_soft_deletes',48);
INSERT INTO `migrations` VALUES (224,'2022_05_03_154725_update_tournament_player_table',49);
INSERT INTO `migrations` VALUES (225,'2022_05_04_223046_add_notification_to_user',50);
INSERT INTO `migrations` VALUES (226,'2022_05_08_202332_live_results_update',51);
INSERT INTO `migrations` VALUES (227,'2022_05_10_144838_update_clubs_table',51);
INSERT INTO `migrations` VALUES (228,'2022_05_11_114508_create_team_table',52);
INSERT INTO `migrations` VALUES (229,'2022_05_11_165713_update_results_table',53);
INSERT INTO `migrations` VALUES (230,'2022_06_01_141227_update_tournaments_table',54);
INSERT INTO `migrations` VALUES (231,'2022_06_14_151735_update_reservation_table',55);
INSERT INTO `migrations` VALUES (232,'2022_06_30_130153_add_doubles_to_leagues',56);
INSERT INTO `migrations` VALUES (233,'2022_07_05_095527_create_wallets_table',57);
INSERT INTO `migrations` VALUES (234,'2022_07_05_101617_create_wallet_transactions_table',57);
INSERT INTO `migrations` VALUES (235,'2022_07_19_095410_add_data_to_clubs',58);
INSERT INTO `migrations` VALUES (236,'2022_07_20_164011_create_media_table',59);
INSERT INTO `migrations` VALUES (237,'2022_07_20_171208_add_hero_images',59);
INSERT INTO `migrations` VALUES (238,'2022_07_22_140335_court_hero_images',60);
INSERT INTO `migrations` VALUES (239,'2022_07_24_214154_working_hours_price',61);
INSERT INTO `migrations` VALUES (240,'2022_07_26_160941_update_teams_table',62);
INSERT INTO `migrations` VALUES (241,'2022_09_07_100559_add_social_networks_to_club',63);
INSERT INTO `migrations` VALUES (242,'2022_09_23_110520_update_league_group_players_table',64);
INSERT INTO `migrations` VALUES (243,'2022_09_23_153055_create_player_profiles_table',65);
INSERT INTO `migrations` VALUES (244,'2022_09_23_163859_update_tournament_player',66);
INSERT INTO `migrations` VALUES (245,'2022_10_05_154709_update_clubs_table',67);
INSERT INTO `migrations` VALUES (246,'2022_10_11_223440_remove_foreign_keys_for_users',68);
INSERT INTO `migrations` VALUES (247,'2022_10_13_131121_add_primary_key',69);
INSERT INTO `migrations` VALUES (248,'2022_10_25_082505_update_payments_table',70);
INSERT INTO `migrations` VALUES (249,'2022_11_08_211809_update_tournament_players',71);
INSERT INTO `migrations` VALUES (250,'2022_11_15_144749_update_leagues_table3',72);
INSERT INTO `migrations` VALUES (251,'2022_11_15_231242_update_league_group_player_table',73);
INSERT INTO `migrations` VALUES (252,'2022_12_03_225138_create_shop_products_table',74);
INSERT INTO `migrations` VALUES (253,'2022_12_03_225206_create_shop_orders_table',74);
INSERT INTO `migrations` VALUES (254,'2022_12_03_225322_create_shop_order_items_tables',74);
INSERT INTO `migrations` VALUES (255,'2022_12_07_212653_create_media_product_table',75);
INSERT INTO `migrations` VALUES (256,'2023_01_08_095745_fix_school_group_team',76);
INSERT INTO `migrations` VALUES (257,'2023_01_17_094201_update_courts_for_tennisplus',77);
INSERT INTO `migrations` VALUES (258,'2023_01_18_105938_create_sports_table',78);
INSERT INTO `migrations` VALUES (259,'2023_01_18_110423_create_court_sport_table',78);
INSERT INTO `migrations` VALUES (260,'2023_01_18_151331_update_shop_orders_tables',79);
INSERT INTO `migrations` VALUES (261,'2023_01_28_100147_create_categories_table',80);
INSERT INTO `migrations` VALUES (262,'2023_01_28_104556_update_shop_products_table',80);
INSERT INTO `migrations` VALUES (263,'2023_01_28_113522_create_equipment_table',81);
INSERT INTO `migrations` VALUES (264,'2023_01_30_122355_update_shop_order_items_table',82);
INSERT INTO `migrations` VALUES (265,'2023_02_01_184339_update_leagues_cups_table',83);
INSERT INTO `migrations` VALUES (266,'2023_02_01_213222_update_league_groups_table',84);
INSERT INTO `migrations` VALUES (267,'2023_02_02_095559_create_reservation_categories_table',85);
INSERT INTO `migrations` VALUES (268,'2023_02_02_095615_create_hour_categories_table',86);
INSERT INTO `migrations` VALUES (269,'2023_02_03_110629_update_reservation_table2',87);
INSERT INTO `migrations` VALUES (270,'2023_02_03_114751_update_working_hours_table2',88);
INSERT INTO `migrations` VALUES (271,'2023_02_03_151640_update_leagues_table3',88);
INSERT INTO `migrations` VALUES (272,'2023_02_03_184755_update_hours_categories_table',89);
INSERT INTO `migrations` VALUES (273,'2023_02_06_110826_update_clubs_table',90);
INSERT INTO `migrations` VALUES (274,'2023_02_14_135311_create_work_orders_table',91);
INSERT INTO `migrations` VALUES (275,'2023_02_14_213548_create_parents_table',92);
INSERT INTO `migrations` VALUES (276,'2023_02_22_162945_create_school_performances_table',93);
INSERT INTO `migrations` VALUES (277,'2023_02_26_114011_update_tournaments_leagues_table',94);
INSERT INTO `migrations` VALUES (278,'2019_12_14_000001_create_personal_access_tokens_table',95);
INSERT INTO `migrations` VALUES (279,'2023_03_01_222831_update_memberships_table',96);
INSERT INTO `migrations` VALUES (280,'2023_03_08_182110_update_games_table2',97);
INSERT INTO `migrations` VALUES (281,'2023_04_03_150043_update3_courts_table',98);
INSERT INTO `migrations` VALUES (282,'2023_04_04_181953_create_subscriptions_table',99);
INSERT INTO `migrations` VALUES (283,'2023_04_05_162211_update_subscriptions_table',99);
INSERT INTO `migrations` VALUES (284,'2023_04_10_155525_update_clubs_table',99);
INSERT INTO `migrations` VALUES (285,'2023_04_11_144524_update_users_table_lang',100);
INSERT INTO `migrations` VALUES (286,'2019_05_03_000001_create_customer_columns',101);
INSERT INTO `migrations` VALUES (287,'2023_04_17_094423_update_threads_table',102);
INSERT INTO `migrations` VALUES (288,'2023_04_20_103206_update_work_orders_table',103);
INSERT INTO `migrations` VALUES (289,'2023_04_25_142326_hide_league_cups',104);
INSERT INTO `migrations` VALUES (290,'2023_04_05_162211_update_subscriptions_table2',105);
INSERT INTO `migrations` VALUES (291,'2023_04_27_095403_add_messages_for_club_users',106);
INSERT INTO `migrations` VALUES (292,'2023_04_28_105233_add_cashier_to_users',107);
INSERT INTO `migrations` VALUES (293,'2023_04_30_132913_disable_competitions_in_clubs',108);
INSERT INTO `migrations` VALUES (294,'2023_05_03_151438_create_user_subscriptions_table',109);
INSERT INTO `migrations` VALUES (295,'2023_05_05_081645_update_leagues_table_points',110);
INSERT INTO `migrations` VALUES (296,'2023_05_15_153428_create_gdpr_field_users',111);
INSERT INTO `migrations` VALUES (297,'2023_05_27_141708_update_user_subscriptions',112);
INSERT INTO `migrations` VALUES (298,'2023_05_27_144641_update_user_subscriptions_table2',113);
INSERT INTO `migrations` VALUES (299,'2023_06_04_212910_add_club_to_subscription',114);
INSERT INTO `migrations` VALUES (300,'2023_06_08_081723_update_clubs_for_w2p',115);
INSERT INTO `migrations` VALUES (301,'2023_06_08_223502_update_users_table_for_oib',116);
INSERT INTO `migrations` VALUES (302,'2023_06_09_142952_create_invoices_table',117);
INSERT INTO `migrations` VALUES (304,'2023_06_11_124023_create_invoice_items_table',118);
INSERT INTO `migrations` VALUES (305,'2023_06_16_094453_add_hidden_notifications_field',119);
INSERT INTO `migrations` VALUES (306,'2023_06_16_162500_create_countries_table',120);
INSERT INTO `migrations` VALUES (307,'2023_06_16_162509_create_cities_table',120);
INSERT INTO `migrations` VALUES (308,'2023_06_16_171436_add_translations_for_countries',121);
INSERT INTO `migrations` VALUES (309,'2023_06_19_180316_update_league_group_player_table',122);
INSERT INTO `migrations` VALUES (310,'2023_06_21_092517_add_country_to_clubs',123);
INSERT INTO `migrations` VALUES (311,'2019_05_03_000002_create_subscriptions_table',124);
INSERT INTO `migrations` VALUES (312,'2019_05_03_000003_create_subscription_items_table',124);
INSERT INTO `migrations` VALUES (313,'2023_07_04_123423_create_tax_classes_table',125);
INSERT INTO `migrations` VALUES (314,'2023_07_04_134546_update_working_hours',126);
INSERT INTO `migrations` VALUES (315,'2023_07_04_145737_create_business_units_table',127);
INSERT INTO `migrations` VALUES (316,'2023_07_04_151031_update_clubs_table',128);
INSERT INTO `migrations` VALUES (317,'2023_07_13_113056_update_invoices_table',129);
INSERT INTO `migrations` VALUES (318,'2023_07_13_231503_update_payments_table',129);
INSERT INTO `migrations` VALUES (319,'2023_07_13_231854_create_payment_intents_table',129);
INSERT INTO `migrations` VALUES (320,'2023_07_18_103352_update_invoices_table',130);
INSERT INTO `migrations` VALUES (321,'2023_07_18_113434_update_business_units_table',131);
INSERT INTO `migrations` VALUES (322,'2023_07_18_145232_update_business_units_table',132);
INSERT INTO `migrations` VALUES (323,'2023_07_19_125606_update_reservations_table',133);
INSERT INTO `migrations` VALUES (324,'2023_07_24_124143_update_memberships_table',134);
INSERT INTO `migrations` VALUES (325,'2023_08_02_100913_create_companies_table',135);
INSERT INTO `migrations` VALUES (326,'2023_08_02_111823_update_invoices_table',136);
INSERT INTO `migrations` VALUES (327,'2023_08_07_125713_update_reservations_table',137);
INSERT INTO `migrations` VALUES (328,'2023_08_12_131817_create_court_weather_updates_table',138);
INSERT INTO `migrations` VALUES (329,'2023_08_17_210206_create_sponsors_table',139);
INSERT INTO `migrations` VALUES (330,'2023_09_14_150339_create_competitions_table',140);
INSERT INTO `migrations` VALUES (331,'2023_09_14_155608_update_leagues_tournaments_table',140);
INSERT INTO `migrations` VALUES (332,'2023_09_14_155940_create_competition_team_table',140);
INSERT INTO `migrations` VALUES (333,'2023_09_11_132104_create_trainer_notes_table',141);
INSERT INTO `migrations` VALUES (334,'2023_09_22_110707_create_club_socials_table',142);
INSERT INTO `migrations` VALUES (335,'2023_09_25_111823_update_trainer_notes',143);
INSERT INTO `migrations` VALUES (336,'2023_10_03_140102_update_subscriptions_table',144);
INSERT INTO `migrations` VALUES (337,'2023_10_10_094445_update_payment_intents',145);
INSERT INTO `migrations` VALUES (338,'2023_09_11_132049_create_locations_table',146);
INSERT INTO `migrations` VALUES (339,'2023_10_21_173009_update_courts_table',147);
INSERT INTO `migrations` VALUES (340,'2023_11_10_221053_update_memberships_table',148);
INSERT INTO `migrations` VALUES (341,'2023_11_11_215200_update_memberships_table',148);
INSERT INTO `migrations` VALUES (342,'2023_11_22_104506_add_sports_to_clubs',149);
INSERT INTO `migrations` VALUES (343,'2023_11_22_172851_add_sport_to_result',150);
INSERT INTO `migrations` VALUES (344,'2023_11_23_224010_update_shop_products',151);
