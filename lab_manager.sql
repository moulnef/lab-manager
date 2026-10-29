-- MySQL dump 10.13  Distrib 8.4.3, for Win64 (x86_64)
--
-- Host: localhost    Database: lab_manager
-- ------------------------------------------------------
-- Server version	8.4.3

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Laptop & Máy tính','laptop','Laptop, máy tính bàn mini phục vụ thực hành lập trình.','2026-10-28 21:13:44','2026-10-28 21:13:44'),(2,'Máy chiếu & Trình chiếu','projector','Máy chiếu, màn chiếu, bút trình chiếu cho buổi báo cáo.','2026-10-28 21:13:44','2026-10-28 21:13:44'),(3,'Thiết bị mạng','router','Router, switch, access point cho bài thực hành mạng máy tính.','2026-10-28 21:13:44','2026-10-28 21:13:44'),(4,'Vi điều khiển & IoT','cpu','Arduino, Raspberry Pi, ESP32 và các bộ kit cảm biến.','2026-10-28 21:13:44','2026-10-28 21:13:44'),(5,'Thiết bị đo lường','speedometer2','Đồng hồ vạn năng, máy hiện sóng, nguồn DC.','2026-10-28 21:13:44','2026-10-28 21:13:44'),(6,'Âm thanh & Ghi hình','mic','Micro, loa, webcam, máy quay phục vụ hội thảo.','2026-10-28 21:13:44','2026-10-28 21:13:44');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `devices`
--

DROP TABLE IF EXISTS `devices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `devices` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint unsigned NOT NULL,
  `code` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('available','borrowed','maintenance') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'available',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `devices_code_unique` (`code`),
  KEY `devices_category_id_foreign` (`category_id`),
  KEY `devices_status_index` (`status`),
  CONSTRAINT `devices_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=144 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `devices`
--

LOCK TABLES `devices` WRITE;
/*!40000 ALTER TABLE `devices` DISABLE KEYS */;
INSERT INTO `devices` VALUES (1,1,'LAB-LT-001-01','Laptop Dell Latitude 5420 #01','Thiết bị Laptop Dell Latitude 5420 (Mã tài sản: LAB-LT-001-01, Đơn vị 1/3) thuộc nhóm Laptop & Máy tính, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/laptop-dell-latitude-5420.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(2,1,'LAB-LT-001-02','Laptop Dell Latitude 5420 #02','Thiết bị Laptop Dell Latitude 5420 (Mã tài sản: LAB-LT-001-02, Đơn vị 2/3) thuộc nhóm Laptop & Máy tính, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/laptop-dell-latitude-5420.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(3,1,'LAB-LT-001-03','Laptop Dell Latitude 5420 #03','Thiết bị Laptop Dell Latitude 5420 (Mã tài sản: LAB-LT-001-03, Đơn vị 3/3) thuộc nhóm Laptop & Máy tính, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/laptop-dell-latitude-5420.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(4,1,'LAB-LT-002-01','Laptop HP ProBook 450 G9 #01','Thiết bị Laptop HP ProBook 450 G9 (Mã tài sản: LAB-LT-002-01, Đơn vị 1/3) thuộc nhóm Laptop & Máy tính, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/laptop-hp-probook-450-g9.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(5,1,'LAB-LT-002-02','Laptop HP ProBook 450 G9 #02','Thiết bị Laptop HP ProBook 450 G9 (Mã tài sản: LAB-LT-002-02, Đơn vị 2/3) thuộc nhóm Laptop & Máy tính, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/laptop-hp-probook-450-g9.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(6,1,'LAB-LT-002-03','Laptop HP ProBook 450 G9 #03','Thiết bị Laptop HP ProBook 450 G9 (Mã tài sản: LAB-LT-002-03, Đơn vị 3/3) thuộc nhóm Laptop & Máy tính, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/laptop-hp-probook-450-g9.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(7,1,'LAB-LT-003-01','Laptop Lenovo ThinkPad E14 #01','Thiết bị Laptop Lenovo ThinkPad E14 (Mã tài sản: LAB-LT-003-01, Đơn vị 1/3) thuộc nhóm Laptop & Máy tính, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/laptop-lenovo-thinkpad-e14.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(8,1,'LAB-LT-003-02','Laptop Lenovo ThinkPad E14 #02','Thiết bị Laptop Lenovo ThinkPad E14 (Mã tài sản: LAB-LT-003-02, Đơn vị 2/3) thuộc nhóm Laptop & Máy tính, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/laptop-lenovo-thinkpad-e14.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(9,1,'LAB-LT-003-03','Laptop Lenovo ThinkPad E14 #03','Thiết bị Laptop Lenovo ThinkPad E14 (Mã tài sản: LAB-LT-003-03, Đơn vị 3/3) thuộc nhóm Laptop & Máy tính, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/laptop-lenovo-thinkpad-e14.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(10,1,'LAB-LT-004-01','Laptop ASUS ExpertBook B1 #01','Thiết bị Laptop ASUS ExpertBook B1 (Mã tài sản: LAB-LT-004-01, Đơn vị 1/3) thuộc nhóm Laptop & Máy tính, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/laptop-asus-expertbook-b1.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(11,1,'LAB-LT-004-02','Laptop ASUS ExpertBook B1 #02','Thiết bị Laptop ASUS ExpertBook B1 (Mã tài sản: LAB-LT-004-02, Đơn vị 2/3) thuộc nhóm Laptop & Máy tính, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/laptop-asus-expertbook-b1.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(12,1,'LAB-LT-004-03','Laptop ASUS ExpertBook B1 #03','Thiết bị Laptop ASUS ExpertBook B1 (Mã tài sản: LAB-LT-004-03, Đơn vị 3/3) thuộc nhóm Laptop & Máy tính, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/laptop-asus-expertbook-b1.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(13,1,'LAB-LT-005-01','MacBook Air M1 #01','Thiết bị MacBook Air M1 (Mã tài sản: LAB-LT-005-01, Đơn vị 1/3) thuộc nhóm Laptop & Máy tính, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/macbook-air-m1.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(14,1,'LAB-LT-005-02','MacBook Air M1 #02','Thiết bị MacBook Air M1 (Mã tài sản: LAB-LT-005-02, Đơn vị 2/3) thuộc nhóm Laptop & Máy tính, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/macbook-air-m1.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(15,1,'LAB-LT-005-03','MacBook Air M1 #03','Thiết bị MacBook Air M1 (Mã tài sản: LAB-LT-005-03, Đơn vị 3/3) thuộc nhóm Laptop & Máy tính, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/macbook-air-m1.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(16,1,'LAB-LT-006-01','Mini PC Intel NUC 11 #01','Thiết bị Mini PC Intel NUC 11 (Mã tài sản: LAB-LT-006-01, Đơn vị 1/3) thuộc nhóm Laptop & Máy tính, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/mini-pc-intel-nuc-11.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(17,1,'LAB-LT-006-02','Mini PC Intel NUC 11 #02','Thiết bị Mini PC Intel NUC 11 (Mã tài sản: LAB-LT-006-02, Đơn vị 2/3) thuộc nhóm Laptop & Máy tính, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/mini-pc-intel-nuc-11.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(18,1,'LAB-LT-006-03','Mini PC Intel NUC 11 #03','Thiết bị Mini PC Intel NUC 11 (Mã tài sản: LAB-LT-006-03, Đơn vị 3/3) thuộc nhóm Laptop & Máy tính, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/mini-pc-intel-nuc-11.png','maintenance','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(19,1,'LAB-LT-007-01','Laptop Acer Aspire 7 #01','Thiết bị Laptop Acer Aspire 7 (Mã tài sản: LAB-LT-007-01, Đơn vị 1/3) thuộc nhóm Laptop & Máy tính, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/laptop-acer-aspire-7.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(20,1,'LAB-LT-007-02','Laptop Acer Aspire 7 #02','Thiết bị Laptop Acer Aspire 7 (Mã tài sản: LAB-LT-007-02, Đơn vị 2/3) thuộc nhóm Laptop & Máy tính, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/laptop-acer-aspire-7.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(21,1,'LAB-LT-007-03','Laptop Acer Aspire 7 #03','Thiết bị Laptop Acer Aspire 7 (Mã tài sản: LAB-LT-007-03, Đơn vị 3/3) thuộc nhóm Laptop & Máy tính, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/laptop-acer-aspire-7.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(22,1,'LAB-LT-008-01','Laptop Dell Vostro 3520 #01','Thiết bị Laptop Dell Vostro 3520 (Mã tài sản: LAB-LT-008-01, Đơn vị 1/3) thuộc nhóm Laptop & Máy tính, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/laptop-dell-vostro-3520.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(23,1,'LAB-LT-008-02','Laptop Dell Vostro 3520 #02','Thiết bị Laptop Dell Vostro 3520 (Mã tài sản: LAB-LT-008-02, Đơn vị 2/3) thuộc nhóm Laptop & Máy tính, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/laptop-dell-vostro-3520.png','borrowed','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(24,1,'LAB-LT-008-03','Laptop Dell Vostro 3520 #03','Thiết bị Laptop Dell Vostro 3520 (Mã tài sản: LAB-LT-008-03, Đơn vị 3/3) thuộc nhóm Laptop & Máy tính, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/laptop-dell-vostro-3520.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(25,1,'LAB-LT-009-01','Laptop HP 240 G8 #01','Thiết bị Laptop HP 240 G8 (Mã tài sản: LAB-LT-009-01, Đơn vị 1/3) thuộc nhóm Laptop & Máy tính, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/laptop-hp-240-g8.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(26,1,'LAB-LT-009-02','Laptop HP 240 G8 #02','Thiết bị Laptop HP 240 G8 (Mã tài sản: LAB-LT-009-02, Đơn vị 2/3) thuộc nhóm Laptop & Máy tính, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/laptop-hp-240-g8.png','borrowed','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(27,1,'LAB-LT-009-03','Laptop HP 240 G8 #03','Thiết bị Laptop HP 240 G8 (Mã tài sản: LAB-LT-009-03, Đơn vị 3/3) thuộc nhóm Laptop & Máy tính, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/laptop-hp-240-g8.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(28,2,'LAB-MC-001-01','Máy chiếu Epson EB-X51 #01','Thiết bị Máy chiếu Epson EB-X51 (Mã tài sản: LAB-MC-001-01, Đơn vị 1/2) thuộc nhóm Máy chiếu & Trình chiếu, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/may-chieu-epson-eb-x51.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(29,2,'LAB-MC-001-02','Máy chiếu Epson EB-X51 #02','Thiết bị Máy chiếu Epson EB-X51 (Mã tài sản: LAB-MC-001-02, Đơn vị 2/2) thuộc nhóm Máy chiếu & Trình chiếu, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/may-chieu-epson-eb-x51.png','borrowed','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(30,2,'LAB-MC-002-01','Máy chiếu ViewSonic PA503S #01','Thiết bị Máy chiếu ViewSonic PA503S (Mã tài sản: LAB-MC-002-01, Đơn vị 1/2) thuộc nhóm Máy chiếu & Trình chiếu, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/may-chieu-viewsonic-pa503s.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(31,2,'LAB-MC-002-02','Máy chiếu ViewSonic PA503S #02','Thiết bị Máy chiếu ViewSonic PA503S (Mã tài sản: LAB-MC-002-02, Đơn vị 2/2) thuộc nhóm Máy chiếu & Trình chiếu, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/may-chieu-viewsonic-pa503s.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(32,2,'LAB-MC-003-01','Máy chiếu BenQ MX560 #01','Thiết bị Máy chiếu BenQ MX560 (Mã tài sản: LAB-MC-003-01, Đơn vị 1/2) thuộc nhóm Máy chiếu & Trình chiếu, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/may-chieu-benq-mx560.png','borrowed','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(33,2,'LAB-MC-003-02','Máy chiếu BenQ MX560 #02','Thiết bị Máy chiếu BenQ MX560 (Mã tài sản: LAB-MC-003-02, Đơn vị 2/2) thuộc nhóm Máy chiếu & Trình chiếu, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/may-chieu-benq-mx560.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(34,2,'LAB-MC-004-01','Máy chiếu Sony VPL-DX221 #01','Thiết bị Máy chiếu Sony VPL-DX221 (Mã tài sản: LAB-MC-004-01, Đơn vị 1/2) thuộc nhóm Máy chiếu & Trình chiếu, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/may-chieu-sony-vpl-dx221.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(35,2,'LAB-MC-004-02','Máy chiếu Sony VPL-DX221 #02','Thiết bị Máy chiếu Sony VPL-DX221 (Mã tài sản: LAB-MC-004-02, Đơn vị 2/2) thuộc nhóm Máy chiếu & Trình chiếu, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/may-chieu-sony-vpl-dx221.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(36,2,'LAB-MC-005-01','Bút trình chiếu Logitech R400 #01','Thiết bị Bút trình chiếu Logitech R400 (Mã tài sản: LAB-MC-005-01, Đơn vị 1/2) thuộc nhóm Máy chiếu & Trình chiếu, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/but-trinh-chieu-logitech-r400.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(37,2,'LAB-MC-005-02','Bút trình chiếu Logitech R400 #02','Thiết bị Bút trình chiếu Logitech R400 (Mã tài sản: LAB-MC-005-02, Đơn vị 2/2) thuộc nhóm Máy chiếu & Trình chiếu, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/but-trinh-chieu-logitech-r400.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(38,2,'LAB-MC-006-01','Màn chiếu treo tường 100 inch #01','Thiết bị Màn chiếu treo tường 100 inch (Mã tài sản: LAB-MC-006-01, Đơn vị 1/2) thuộc nhóm Máy chiếu & Trình chiếu, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/man-chieu-treo-tuong-100-inch.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(39,2,'LAB-MC-006-02','Màn chiếu treo tường 100 inch #02','Thiết bị Màn chiếu treo tường 100 inch (Mã tài sản: LAB-MC-006-02, Đơn vị 2/2) thuộc nhóm Máy chiếu & Trình chiếu, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/man-chieu-treo-tuong-100-inch.png','maintenance','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(40,2,'LAB-MC-007-01','Máy chiếu mini Xiaomi Wanbo T2 #01','Thiết bị Máy chiếu mini Xiaomi Wanbo T2 (Mã tài sản: LAB-MC-007-01, Đơn vị 1/2) thuộc nhóm Máy chiếu & Trình chiếu, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/may-chieu-mini-xiaomi-wanbo-t2.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(41,2,'LAB-MC-007-02','Máy chiếu mini Xiaomi Wanbo T2 #02','Thiết bị Máy chiếu mini Xiaomi Wanbo T2 (Mã tài sản: LAB-MC-007-02, Đơn vị 2/2) thuộc nhóm Máy chiếu & Trình chiếu, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/may-chieu-mini-xiaomi-wanbo-t2.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(42,2,'LAB-MC-008-01','Bút laser Logitech Spotlight #01','Thiết bị Bút laser Logitech Spotlight (Mã tài sản: LAB-MC-008-01, Đơn vị 1/2) thuộc nhóm Máy chiếu & Trình chiếu, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/but-laser-logitech-spotlight.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(43,2,'LAB-MC-008-02','Bút laser Logitech Spotlight #02','Thiết bị Bút laser Logitech Spotlight (Mã tài sản: LAB-MC-008-02, Đơn vị 2/2) thuộc nhóm Máy chiếu & Trình chiếu, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/but-laser-logitech-spotlight.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(44,3,'LAB-MG-001-01','Router Cisco ISR 4321 #01','Thiết bị Router Cisco ISR 4321 (Mã tài sản: LAB-MG-001-01, Đơn vị 1/3) thuộc nhóm Thiết bị mạng, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/router-cisco-isr-4321.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(45,3,'LAB-MG-001-02','Router Cisco ISR 4321 #02','Thiết bị Router Cisco ISR 4321 (Mã tài sản: LAB-MG-001-02, Đơn vị 2/3) thuộc nhóm Thiết bị mạng, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/router-cisco-isr-4321.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(46,3,'LAB-MG-001-03','Router Cisco ISR 4321 #03','Thiết bị Router Cisco ISR 4321 (Mã tài sản: LAB-MG-001-03, Đơn vị 3/3) thuộc nhóm Thiết bị mạng, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/router-cisco-isr-4321.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(47,3,'LAB-MG-002-01','Switch Cisco Catalyst 2960 #01','Thiết bị Switch Cisco Catalyst 2960 (Mã tài sản: LAB-MG-002-01, Đơn vị 1/3) thuộc nhóm Thiết bị mạng, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/switch-cisco-catalyst-2960.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(48,3,'LAB-MG-002-02','Switch Cisco Catalyst 2960 #02','Thiết bị Switch Cisco Catalyst 2960 (Mã tài sản: LAB-MG-002-02, Đơn vị 2/3) thuộc nhóm Thiết bị mạng, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/switch-cisco-catalyst-2960.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(49,3,'LAB-MG-002-03','Switch Cisco Catalyst 2960 #03','Thiết bị Switch Cisco Catalyst 2960 (Mã tài sản: LAB-MG-002-03, Đơn vị 3/3) thuộc nhóm Thiết bị mạng, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/switch-cisco-catalyst-2960.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(50,3,'LAB-MG-003-01','Router MikroTik hEX S #01','Thiết bị Router MikroTik hEX S (Mã tài sản: LAB-MG-003-01, Đơn vị 1/3) thuộc nhóm Thiết bị mạng, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/router-mikrotik-hex-s.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(51,3,'LAB-MG-003-02','Router MikroTik hEX S #02','Thiết bị Router MikroTik hEX S (Mã tài sản: LAB-MG-003-02, Đơn vị 2/3) thuộc nhóm Thiết bị mạng, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/router-mikrotik-hex-s.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(52,3,'LAB-MG-003-03','Router MikroTik hEX S #03','Thiết bị Router MikroTik hEX S (Mã tài sản: LAB-MG-003-03, Đơn vị 3/3) thuộc nhóm Thiết bị mạng, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/router-mikrotik-hex-s.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(53,3,'LAB-MG-004-01','Access Point Ubiquiti UniFi 6 Lite #01','Thiết bị Access Point Ubiquiti UniFi 6 Lite (Mã tài sản: LAB-MG-004-01, Đơn vị 1/3) thuộc nhóm Thiết bị mạng, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/access-point-ubiquiti-unifi-6-lite.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(54,3,'LAB-MG-004-02','Access Point Ubiquiti UniFi 6 Lite #02','Thiết bị Access Point Ubiquiti UniFi 6 Lite (Mã tài sản: LAB-MG-004-02, Đơn vị 2/3) thuộc nhóm Thiết bị mạng, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/access-point-ubiquiti-unifi-6-lite.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(55,3,'LAB-MG-004-03','Access Point Ubiquiti UniFi 6 Lite #03','Thiết bị Access Point Ubiquiti UniFi 6 Lite (Mã tài sản: LAB-MG-004-03, Đơn vị 3/3) thuộc nhóm Thiết bị mạng, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/access-point-ubiquiti-unifi-6-lite.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(56,3,'LAB-MG-005-01','Switch TP-Link TL-SG108 #01','Thiết bị Switch TP-Link TL-SG108 (Mã tài sản: LAB-MG-005-01, Đơn vị 1/3) thuộc nhóm Thiết bị mạng, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/switch-tp-link-tl-sg108.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(57,3,'LAB-MG-005-02','Switch TP-Link TL-SG108 #02','Thiết bị Switch TP-Link TL-SG108 (Mã tài sản: LAB-MG-005-02, Đơn vị 2/3) thuộc nhóm Thiết bị mạng, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/switch-tp-link-tl-sg108.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(58,3,'LAB-MG-005-03','Switch TP-Link TL-SG108 #03','Thiết bị Switch TP-Link TL-SG108 (Mã tài sản: LAB-MG-005-03, Đơn vị 3/3) thuộc nhóm Thiết bị mạng, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/switch-tp-link-tl-sg108.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(59,3,'LAB-MG-006-01','Router Wi-Fi TP-Link Archer C6 #01','Thiết bị Router Wi-Fi TP-Link Archer C6 (Mã tài sản: LAB-MG-006-01, Đơn vị 1/3) thuộc nhóm Thiết bị mạng, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/router-wi-fi-tp-link-archer-c6.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(60,3,'LAB-MG-006-02','Router Wi-Fi TP-Link Archer C6 #02','Thiết bị Router Wi-Fi TP-Link Archer C6 (Mã tài sản: LAB-MG-006-02, Đơn vị 2/3) thuộc nhóm Thiết bị mạng, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/router-wi-fi-tp-link-archer-c6.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(61,3,'LAB-MG-006-03','Router Wi-Fi TP-Link Archer C6 #03','Thiết bị Router Wi-Fi TP-Link Archer C6 (Mã tài sản: LAB-MG-006-03, Đơn vị 3/3) thuộc nhóm Thiết bị mạng, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/router-wi-fi-tp-link-archer-c6.png','maintenance','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(62,3,'LAB-MG-007-01','Bộ bấm mạng & Tester cáp #01','Thiết bị Bộ bấm mạng & Tester cáp (Mã tài sản: LAB-MG-007-01, Đơn vị 1/3) thuộc nhóm Thiết bị mạng, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/bo-bam-mang-tester-cap.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(63,3,'LAB-MG-007-02','Bộ bấm mạng & Tester cáp #02','Thiết bị Bộ bấm mạng & Tester cáp (Mã tài sản: LAB-MG-007-02, Đơn vị 2/3) thuộc nhóm Thiết bị mạng, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/bo-bam-mang-tester-cap.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(64,3,'LAB-MG-007-03','Bộ bấm mạng & Tester cáp #03','Thiết bị Bộ bấm mạng & Tester cáp (Mã tài sản: LAB-MG-007-03, Đơn vị 3/3) thuộc nhóm Thiết bị mạng, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/bo-bam-mang-tester-cap.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(65,3,'LAB-MG-008-01','Firewall Fortinet FortiGate 40F #01','Thiết bị Firewall Fortinet FortiGate 40F (Mã tài sản: LAB-MG-008-01, Đơn vị 1/3) thuộc nhóm Thiết bị mạng, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/firewall-fortinet-fortigate-40f.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(66,3,'LAB-MG-008-02','Firewall Fortinet FortiGate 40F #02','Thiết bị Firewall Fortinet FortiGate 40F (Mã tài sản: LAB-MG-008-02, Đơn vị 2/3) thuộc nhóm Thiết bị mạng, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/firewall-fortinet-fortigate-40f.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(67,3,'LAB-MG-008-03','Firewall Fortinet FortiGate 40F #03','Thiết bị Firewall Fortinet FortiGate 40F (Mã tài sản: LAB-MG-008-03, Đơn vị 3/3) thuộc nhóm Thiết bị mạng, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/firewall-fortinet-fortigate-40f.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(68,4,'LAB-IO-001-01','Arduino Uno R3 #01','Thiết bị Arduino Uno R3 (Mã tài sản: LAB-IO-001-01, Đơn vị 1/4) thuộc nhóm Vi điều khiển & IoT, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/arduino-uno-r3.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(69,4,'LAB-IO-001-02','Arduino Uno R3 #02','Thiết bị Arduino Uno R3 (Mã tài sản: LAB-IO-001-02, Đơn vị 2/4) thuộc nhóm Vi điều khiển & IoT, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/arduino-uno-r3.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(70,4,'LAB-IO-001-03','Arduino Uno R3 #03','Thiết bị Arduino Uno R3 (Mã tài sản: LAB-IO-001-03, Đơn vị 3/4) thuộc nhóm Vi điều khiển & IoT, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/arduino-uno-r3.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(71,4,'LAB-IO-001-04','Arduino Uno R3 #04','Thiết bị Arduino Uno R3 (Mã tài sản: LAB-IO-001-04, Đơn vị 4/4) thuộc nhóm Vi điều khiển & IoT, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/arduino-uno-r3.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(72,4,'LAB-IO-002-01','Raspberry Pi 4 Model B 4GB #01','Thiết bị Raspberry Pi 4 Model B 4GB (Mã tài sản: LAB-IO-002-01, Đơn vị 1/4) thuộc nhóm Vi điều khiển & IoT, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/raspberry-pi-4-model-b-4gb.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(73,4,'LAB-IO-002-02','Raspberry Pi 4 Model B 4GB #02','Thiết bị Raspberry Pi 4 Model B 4GB (Mã tài sản: LAB-IO-002-02, Đơn vị 2/4) thuộc nhóm Vi điều khiển & IoT, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/raspberry-pi-4-model-b-4gb.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(74,4,'LAB-IO-002-03','Raspberry Pi 4 Model B 4GB #03','Thiết bị Raspberry Pi 4 Model B 4GB (Mã tài sản: LAB-IO-002-03, Đơn vị 3/4) thuộc nhóm Vi điều khiển & IoT, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/raspberry-pi-4-model-b-4gb.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(75,4,'LAB-IO-002-04','Raspberry Pi 4 Model B 4GB #04','Thiết bị Raspberry Pi 4 Model B 4GB (Mã tài sản: LAB-IO-002-04, Đơn vị 4/4) thuộc nhóm Vi điều khiển & IoT, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/raspberry-pi-4-model-b-4gb.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(76,4,'LAB-IO-003-01','Kit ESP32 DevKit V1 #01','Thiết bị Kit ESP32 DevKit V1 (Mã tài sản: LAB-IO-003-01, Đơn vị 1/4) thuộc nhóm Vi điều khiển & IoT, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/kit-esp32-devkit-v1.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(77,4,'LAB-IO-003-02','Kit ESP32 DevKit V1 #02','Thiết bị Kit ESP32 DevKit V1 (Mã tài sản: LAB-IO-003-02, Đơn vị 2/4) thuộc nhóm Vi điều khiển & IoT, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/kit-esp32-devkit-v1.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(78,4,'LAB-IO-003-03','Kit ESP32 DevKit V1 #03','Thiết bị Kit ESP32 DevKit V1 (Mã tài sản: LAB-IO-003-03, Đơn vị 3/4) thuộc nhóm Vi điều khiển & IoT, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/kit-esp32-devkit-v1.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(79,4,'LAB-IO-003-04','Kit ESP32 DevKit V1 #04','Thiết bị Kit ESP32 DevKit V1 (Mã tài sản: LAB-IO-003-04, Đơn vị 4/4) thuộc nhóm Vi điều khiển & IoT, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/kit-esp32-devkit-v1.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(80,4,'LAB-IO-004-01','Bộ kit cảm biến 37 trong 1 #01','Thiết bị Bộ kit cảm biến 37 trong 1 (Mã tài sản: LAB-IO-004-01, Đơn vị 1/4) thuộc nhóm Vi điều khiển & IoT, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/bo-kit-cam-bien-37-trong-1.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(81,4,'LAB-IO-004-02','Bộ kit cảm biến 37 trong 1 #02','Thiết bị Bộ kit cảm biến 37 trong 1 (Mã tài sản: LAB-IO-004-02, Đơn vị 2/4) thuộc nhóm Vi điều khiển & IoT, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/bo-kit-cam-bien-37-trong-1.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(82,4,'LAB-IO-004-03','Bộ kit cảm biến 37 trong 1 #03','Thiết bị Bộ kit cảm biến 37 trong 1 (Mã tài sản: LAB-IO-004-03, Đơn vị 3/4) thuộc nhóm Vi điều khiển & IoT, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/bo-kit-cam-bien-37-trong-1.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(83,4,'LAB-IO-004-04','Bộ kit cảm biến 37 trong 1 #04','Thiết bị Bộ kit cảm biến 37 trong 1 (Mã tài sản: LAB-IO-004-04, Đơn vị 4/4) thuộc nhóm Vi điều khiển & IoT, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/bo-kit-cam-bien-37-trong-1.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(84,4,'LAB-IO-005-01','Arduino Mega 2560 #01','Thiết bị Arduino Mega 2560 (Mã tài sản: LAB-IO-005-01, Đơn vị 1/4) thuộc nhóm Vi điều khiển & IoT, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/arduino-mega-2560.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(85,4,'LAB-IO-005-02','Arduino Mega 2560 #02','Thiết bị Arduino Mega 2560 (Mã tài sản: LAB-IO-005-02, Đơn vị 2/4) thuộc nhóm Vi điều khiển & IoT, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/arduino-mega-2560.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(86,4,'LAB-IO-005-03','Arduino Mega 2560 #03','Thiết bị Arduino Mega 2560 (Mã tài sản: LAB-IO-005-03, Đơn vị 3/4) thuộc nhóm Vi điều khiển & IoT, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/arduino-mega-2560.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(87,4,'LAB-IO-005-04','Arduino Mega 2560 #04','Thiết bị Arduino Mega 2560 (Mã tài sản: LAB-IO-005-04, Đơn vị 4/4) thuộc nhóm Vi điều khiển & IoT, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/arduino-mega-2560.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(88,4,'LAB-IO-006-01','Raspberry Pi Pico W #01','Thiết bị Raspberry Pi Pico W (Mã tài sản: LAB-IO-006-01, Đơn vị 1/4) thuộc nhóm Vi điều khiển & IoT, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/raspberry-pi-pico-w.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(89,4,'LAB-IO-006-02','Raspberry Pi Pico W #02','Thiết bị Raspberry Pi Pico W (Mã tài sản: LAB-IO-006-02, Đơn vị 2/4) thuộc nhóm Vi điều khiển & IoT, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/raspberry-pi-pico-w.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(90,4,'LAB-IO-006-03','Raspberry Pi Pico W #03','Thiết bị Raspberry Pi Pico W (Mã tài sản: LAB-IO-006-03, Đơn vị 3/4) thuộc nhóm Vi điều khiển & IoT, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/raspberry-pi-pico-w.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(91,4,'LAB-IO-006-04','Raspberry Pi Pico W #04','Thiết bị Raspberry Pi Pico W (Mã tài sản: LAB-IO-006-04, Đơn vị 4/4) thuộc nhóm Vi điều khiển & IoT, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/raspberry-pi-pico-w.png','maintenance','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(92,4,'LAB-IO-007-01','Module LoRa SX1278 #01','Thiết bị Module LoRa SX1278 (Mã tài sản: LAB-IO-007-01, Đơn vị 1/4) thuộc nhóm Vi điều khiển & IoT, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/module-lora-sx1278.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(93,4,'LAB-IO-007-02','Module LoRa SX1278 #02','Thiết bị Module LoRa SX1278 (Mã tài sản: LAB-IO-007-02, Đơn vị 2/4) thuộc nhóm Vi điều khiển & IoT, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/module-lora-sx1278.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(94,4,'LAB-IO-007-03','Module LoRa SX1278 #03','Thiết bị Module LoRa SX1278 (Mã tài sản: LAB-IO-007-03, Đơn vị 3/4) thuộc nhóm Vi điều khiển & IoT, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/module-lora-sx1278.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(95,4,'LAB-IO-007-04','Module LoRa SX1278 #04','Thiết bị Module LoRa SX1278 (Mã tài sản: LAB-IO-007-04, Đơn vị 4/4) thuộc nhóm Vi điều khiển & IoT, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/module-lora-sx1278.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(96,4,'LAB-IO-008-01','Kit STM32 Nucleo F401RE #01','Thiết bị Kit STM32 Nucleo F401RE (Mã tài sản: LAB-IO-008-01, Đơn vị 1/4) thuộc nhóm Vi điều khiển & IoT, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/kit-stm32-nucleo-f401re.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(97,4,'LAB-IO-008-02','Kit STM32 Nucleo F401RE #02','Thiết bị Kit STM32 Nucleo F401RE (Mã tài sản: LAB-IO-008-02, Đơn vị 2/4) thuộc nhóm Vi điều khiển & IoT, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/kit-stm32-nucleo-f401re.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(98,4,'LAB-IO-008-03','Kit STM32 Nucleo F401RE #03','Thiết bị Kit STM32 Nucleo F401RE (Mã tài sản: LAB-IO-008-03, Đơn vị 3/4) thuộc nhóm Vi điều khiển & IoT, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/kit-stm32-nucleo-f401re.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(99,4,'LAB-IO-008-04','Kit STM32 Nucleo F401RE #04','Thiết bị Kit STM32 Nucleo F401RE (Mã tài sản: LAB-IO-008-04, Đơn vị 4/4) thuộc nhóm Vi điều khiển & IoT, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/kit-stm32-nucleo-f401re.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(100,4,'LAB-IO-009-01','Jetson Nano Developer Kit #01','Thiết bị Jetson Nano Developer Kit (Mã tài sản: LAB-IO-009-01, Đơn vị 1/4) thuộc nhóm Vi điều khiển & IoT, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/jetson-nano-developer-kit.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(101,4,'LAB-IO-009-02','Jetson Nano Developer Kit #02','Thiết bị Jetson Nano Developer Kit (Mã tài sản: LAB-IO-009-02, Đơn vị 2/4) thuộc nhóm Vi điều khiển & IoT, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/jetson-nano-developer-kit.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(102,4,'LAB-IO-009-03','Jetson Nano Developer Kit #03','Thiết bị Jetson Nano Developer Kit (Mã tài sản: LAB-IO-009-03, Đơn vị 3/4) thuộc nhóm Vi điều khiển & IoT, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/jetson-nano-developer-kit.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(103,4,'LAB-IO-009-04','Jetson Nano Developer Kit #04','Thiết bị Jetson Nano Developer Kit (Mã tài sản: LAB-IO-009-04, Đơn vị 4/4) thuộc nhóm Vi điều khiển & IoT, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/jetson-nano-developer-kit.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(104,5,'LAB-DL-001-01','Đồng hồ vạn năng Fluke 17B+ #01','Thiết bị Đồng hồ vạn năng Fluke 17B+ (Mã tài sản: LAB-DL-001-01, Đơn vị 1/3) thuộc nhóm Thiết bị đo lường, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/dong-ho-van-nang-fluke-17b.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(105,5,'LAB-DL-001-02','Đồng hồ vạn năng Fluke 17B+ #02','Thiết bị Đồng hồ vạn năng Fluke 17B+ (Mã tài sản: LAB-DL-001-02, Đơn vị 2/3) thuộc nhóm Thiết bị đo lường, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/dong-ho-van-nang-fluke-17b.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(106,5,'LAB-DL-001-03','Đồng hồ vạn năng Fluke 17B+ #03','Thiết bị Đồng hồ vạn năng Fluke 17B+ (Mã tài sản: LAB-DL-001-03, Đơn vị 3/3) thuộc nhóm Thiết bị đo lường, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/dong-ho-van-nang-fluke-17b.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(107,5,'LAB-DL-002-01','Máy hiện sóng Rigol DS1054Z #01','Thiết bị Máy hiện sóng Rigol DS1054Z (Mã tài sản: LAB-DL-002-01, Đơn vị 1/3) thuộc nhóm Thiết bị đo lường, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/may-hien-song-rigol-ds1054z.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(108,5,'LAB-DL-002-02','Máy hiện sóng Rigol DS1054Z #02','Thiết bị Máy hiện sóng Rigol DS1054Z (Mã tài sản: LAB-DL-002-02, Đơn vị 2/3) thuộc nhóm Thiết bị đo lường, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/may-hien-song-rigol-ds1054z.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(109,5,'LAB-DL-002-03','Máy hiện sóng Rigol DS1054Z #03','Thiết bị Máy hiện sóng Rigol DS1054Z (Mã tài sản: LAB-DL-002-03, Đơn vị 3/3) thuộc nhóm Thiết bị đo lường, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/may-hien-song-rigol-ds1054z.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(110,5,'LAB-DL-003-01','Nguồn DC Lodestar 30V-5A #01','Thiết bị Nguồn DC Lodestar 30V-5A (Mã tài sản: LAB-DL-003-01, Đơn vị 1/3) thuộc nhóm Thiết bị đo lường, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/nguon-dc-lodestar-30v-5a.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(111,5,'LAB-DL-003-02','Nguồn DC Lodestar 30V-5A #02','Thiết bị Nguồn DC Lodestar 30V-5A (Mã tài sản: LAB-DL-003-02, Đơn vị 2/3) thuộc nhóm Thiết bị đo lường, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/nguon-dc-lodestar-30v-5a.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(112,5,'LAB-DL-003-03','Nguồn DC Lodestar 30V-5A #03','Thiết bị Nguồn DC Lodestar 30V-5A (Mã tài sản: LAB-DL-003-03, Đơn vị 3/3) thuộc nhóm Thiết bị đo lường, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/nguon-dc-lodestar-30v-5a.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(113,5,'LAB-DL-004-01','Máy phát xung UNI-T UTG932 #01','Thiết bị Máy phát xung UNI-T UTG932 (Mã tài sản: LAB-DL-004-01, Đơn vị 1/3) thuộc nhóm Thiết bị đo lường, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/may-phat-xung-uni-t-utg932.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(114,5,'LAB-DL-004-02','Máy phát xung UNI-T UTG932 #02','Thiết bị Máy phát xung UNI-T UTG932 (Mã tài sản: LAB-DL-004-02, Đơn vị 2/3) thuộc nhóm Thiết bị đo lường, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/may-phat-xung-uni-t-utg932.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(115,5,'LAB-DL-004-03','Máy phát xung UNI-T UTG932 #03','Thiết bị Máy phát xung UNI-T UTG932 (Mã tài sản: LAB-DL-004-03, Đơn vị 3/3) thuộc nhóm Thiết bị đo lường, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/may-phat-xung-uni-t-utg932.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(116,5,'LAB-DL-005-01','Đồng hồ kẹp dòng Kyoritsu 2117R #01','Thiết bị Đồng hồ kẹp dòng Kyoritsu 2117R (Mã tài sản: LAB-DL-005-01, Đơn vị 1/3) thuộc nhóm Thiết bị đo lường, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/dong-ho-kep-dong-kyoritsu-2117r.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(117,5,'LAB-DL-005-02','Đồng hồ kẹp dòng Kyoritsu 2117R #02','Thiết bị Đồng hồ kẹp dòng Kyoritsu 2117R (Mã tài sản: LAB-DL-005-02, Đơn vị 2/3) thuộc nhóm Thiết bị đo lường, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/dong-ho-kep-dong-kyoritsu-2117r.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(118,5,'LAB-DL-005-03','Đồng hồ kẹp dòng Kyoritsu 2117R #03','Thiết bị Đồng hồ kẹp dòng Kyoritsu 2117R (Mã tài sản: LAB-DL-005-03, Đơn vị 3/3) thuộc nhóm Thiết bị đo lường, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/dong-ho-kep-dong-kyoritsu-2117r.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(119,5,'LAB-DL-006-01','Mỏ hàn Hakko FX-888D #01','Thiết bị Mỏ hàn Hakko FX-888D (Mã tài sản: LAB-DL-006-01, Đơn vị 1/3) thuộc nhóm Thiết bị đo lường, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/mo-han-hakko-fx-888d.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(120,5,'LAB-DL-006-02','Mỏ hàn Hakko FX-888D #02','Thiết bị Mỏ hàn Hakko FX-888D (Mã tài sản: LAB-DL-006-02, Đơn vị 2/3) thuộc nhóm Thiết bị đo lường, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/mo-han-hakko-fx-888d.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(121,5,'LAB-DL-006-03','Mỏ hàn Hakko FX-888D #03','Thiết bị Mỏ hàn Hakko FX-888D (Mã tài sản: LAB-DL-006-03, Đơn vị 3/3) thuộc nhóm Thiết bị đo lường, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/mo-han-hakko-fx-888d.png','maintenance','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(122,5,'LAB-DL-007-01','Máy đo LCR UNI-T UT612 #01','Thiết bị Máy đo LCR UNI-T UT612 (Mã tài sản: LAB-DL-007-01, Đơn vị 1/3) thuộc nhóm Thiết bị đo lường, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/may-do-lcr-uni-t-ut612.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(123,5,'LAB-DL-007-02','Máy đo LCR UNI-T UT612 #02','Thiết bị Máy đo LCR UNI-T UT612 (Mã tài sản: LAB-DL-007-02, Đơn vị 2/3) thuộc nhóm Thiết bị đo lường, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/may-do-lcr-uni-t-ut612.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(124,5,'LAB-DL-007-03','Máy đo LCR UNI-T UT612 #03','Thiết bị Máy đo LCR UNI-T UT612 (Mã tài sản: LAB-DL-007-03, Đơn vị 3/3) thuộc nhóm Thiết bị đo lường, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/may-do-lcr-uni-t-ut612.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(125,5,'LAB-DL-008-01','Logic Analyzer Saleae 8 kênh #01','Thiết bị Logic Analyzer Saleae 8 kênh (Mã tài sản: LAB-DL-008-01, Đơn vị 1/3) thuộc nhóm Thiết bị đo lường, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/logic-analyzer-saleae-8-kenh.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(126,5,'LAB-DL-008-02','Logic Analyzer Saleae 8 kênh #02','Thiết bị Logic Analyzer Saleae 8 kênh (Mã tài sản: LAB-DL-008-02, Đơn vị 2/3) thuộc nhóm Thiết bị đo lường, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/logic-analyzer-saleae-8-kenh.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(127,5,'LAB-DL-008-03','Logic Analyzer Saleae 8 kênh #03','Thiết bị Logic Analyzer Saleae 8 kênh (Mã tài sản: LAB-DL-008-03, Đơn vị 3/3) thuộc nhóm Thiết bị đo lường, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/logic-analyzer-saleae-8-kenh.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(128,6,'LAB-AT-001-01','Micro không dây Shure BLX24 #01','Thiết bị Micro không dây Shure BLX24 (Mã tài sản: LAB-AT-001-01, Đơn vị 1/2) thuộc nhóm Âm thanh & Ghi hình, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/micro-khong-day-shure-blx24.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(129,6,'LAB-AT-001-02','Micro không dây Shure BLX24 #02','Thiết bị Micro không dây Shure BLX24 (Mã tài sản: LAB-AT-001-02, Đơn vị 2/2) thuộc nhóm Âm thanh & Ghi hình, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/micro-khong-day-shure-blx24.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(130,6,'LAB-AT-002-01','Loa kéo JBL PartyBox 110 #01','Thiết bị Loa kéo JBL PartyBox 110 (Mã tài sản: LAB-AT-002-01, Đơn vị 1/2) thuộc nhóm Âm thanh & Ghi hình, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/loa-keo-jbl-partybox-110.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(131,6,'LAB-AT-002-02','Loa kéo JBL PartyBox 110 #02','Thiết bị Loa kéo JBL PartyBox 110 (Mã tài sản: LAB-AT-002-02, Đơn vị 2/2) thuộc nhóm Âm thanh & Ghi hình, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/loa-keo-jbl-partybox-110.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(132,6,'LAB-AT-003-01','Webcam Logitech C920 #01','Thiết bị Webcam Logitech C920 (Mã tài sản: LAB-AT-003-01, Đơn vị 1/2) thuộc nhóm Âm thanh & Ghi hình, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/webcam-logitech-c920.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(133,6,'LAB-AT-003-02','Webcam Logitech C920 #02','Thiết bị Webcam Logitech C920 (Mã tài sản: LAB-AT-003-02, Đơn vị 2/2) thuộc nhóm Âm thanh & Ghi hình, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/webcam-logitech-c920.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(134,6,'LAB-AT-004-01','Máy quay Sony HDR-CX405 #01','Thiết bị Máy quay Sony HDR-CX405 (Mã tài sản: LAB-AT-004-01, Đơn vị 1/2) thuộc nhóm Âm thanh & Ghi hình, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/may-quay-sony-hdr-cx405.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(135,6,'LAB-AT-004-02','Máy quay Sony HDR-CX405 #02','Thiết bị Máy quay Sony HDR-CX405 (Mã tài sản: LAB-AT-004-02, Đơn vị 2/2) thuộc nhóm Âm thanh & Ghi hình, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/may-quay-sony-hdr-cx405.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(136,6,'LAB-AT-005-01','Micro thu âm Boya BY-M1 #01','Thiết bị Micro thu âm Boya BY-M1 (Mã tài sản: LAB-AT-005-01, Đơn vị 1/2) thuộc nhóm Âm thanh & Ghi hình, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/micro-thu-am-boya-by-m1.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(137,6,'LAB-AT-005-02','Micro thu âm Boya BY-M1 #02','Thiết bị Micro thu âm Boya BY-M1 (Mã tài sản: LAB-AT-005-02, Đơn vị 2/2) thuộc nhóm Âm thanh & Ghi hình, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/micro-thu-am-boya-by-m1.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(138,6,'LAB-AT-006-01','Tai nghe Sony WH-1000XM4 #01','Thiết bị Tai nghe Sony WH-1000XM4 (Mã tài sản: LAB-AT-006-01, Đơn vị 1/2) thuộc nhóm Âm thanh & Ghi hình, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/tai-nghe-sony-wh-1000xm4.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(139,6,'LAB-AT-006-02','Tai nghe Sony WH-1000XM4 #02','Thiết bị Tai nghe Sony WH-1000XM4 (Mã tài sản: LAB-AT-006-02, Đơn vị 2/2) thuộc nhóm Âm thanh & Ghi hình, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/tai-nghe-sony-wh-1000xm4.png','maintenance','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(140,6,'LAB-AT-007-01','Chân máy quay Benro T880 #01','Thiết bị Chân máy quay Benro T880 (Mã tài sản: LAB-AT-007-01, Đơn vị 1/2) thuộc nhóm Âm thanh & Ghi hình, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/chan-may-quay-benro-t880.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(141,6,'LAB-AT-007-02','Chân máy quay Benro T880 #02','Thiết bị Chân máy quay Benro T880 (Mã tài sản: LAB-AT-007-02, Đơn vị 2/2) thuộc nhóm Âm thanh & Ghi hình, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/chan-may-quay-benro-t880.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(142,6,'LAB-AT-008-01','Loa hội nghị Jabra Speak 510 #01','Thiết bị Loa hội nghị Jabra Speak 510 (Mã tài sản: LAB-AT-008-01, Đơn vị 1/2) thuộc nhóm Âm thanh & Ghi hình, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/loa-hoi-nghi-jabra-speak-510.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL),(143,6,'LAB-AT-008-02','Loa hội nghị Jabra Speak 510 #02','Thiết bị Loa hội nghị Jabra Speak 510 (Mã tài sản: LAB-AT-008-02, Đơn vị 2/2) thuộc nhóm Âm thanh & Ghi hình, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.','devices/loa-hoi-nghi-jabra-speak-510.png','available','2026-10-28 21:13:44','2026-10-28 21:13:44',NULL);
/*!40000 ALTER TABLE `devices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_10_12_142600_create_categories_table',1),(5,'2026_10_12_142628_create_devices_table',1),(6,'2026_10_12_142633_create_transactions_table',1),(7,'2026_10_14_125345_create_personal_access_tokens_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  KEY `personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `device_id` bigint unsigned NOT NULL,
  `borrow_date` date NOT NULL,
  `return_date` date NOT NULL,
  `purpose` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','approved','returned','rejected','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `approved_at` timestamp NULL DEFAULT NULL,
  `returned_at` timestamp NULL DEFAULT NULL,
  `admin_note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transactions_user_id_foreign` (`user_id`),
  KEY `transactions_device_id_foreign` (`device_id`),
  KEY `transactions_status_index` (`status`),
  CONSTRAINT `transactions_device_id_foreign` FOREIGN KEY (`device_id`) REFERENCES `devices` (`id`) ON DELETE CASCADE,
  CONSTRAINT `transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transactions`
--

LOCK TABLES `transactions` WRITE;
/*!40000 ALTER TABLE `transactions` DISABLE KEYS */;
INSERT INTO `transactions` VALUES (1,2,1,'2026-10-09','2026-10-14','Thực hành môn Lập trình mã nguồn mở','returned','2026-10-09 01:30:00','2026-10-14 09:00:00',NULL,'2026-10-08 02:00:00','2026-10-28 21:13:44'),(2,3,4,'2026-10-14','2026-10-17','Báo cáo đồ án nhóm trên lớp','returned','2026-10-14 01:30:00','2026-10-17 09:00:00',NULL,'2026-10-13 02:00:00','2026-10-28 21:13:44'),(3,4,7,'2026-10-17','2026-10-24','Làm thí nghiệm IoT cho đề tài nghiên cứu','returned','2026-10-17 01:30:00','2026-10-24 09:00:00',NULL,'2026-10-16 02:00:00','2026-10-28 21:13:44'),(4,5,10,'2026-10-19','2026-10-21','Thuyết trình seminar khoa CNTT','returned','2026-10-19 01:30:00','2026-10-21 09:00:00',NULL,'2026-10-18 02:00:00','2026-10-28 21:13:44'),(5,6,13,'2026-10-21','2026-10-25','Thực hành cấu hình mạng LAN','returned','2026-10-21 01:30:00','2026-10-25 09:00:00',NULL,'2026-10-20 02:00:00','2026-10-28 21:13:44'),(6,7,16,'2026-10-23','2026-10-26','Thực hành môn Lập trình mã nguồn mở','rejected',NULL,NULL,'Thiết bị đã được đặt cho buổi thi.','2026-10-22 02:00:00','2026-10-28 21:13:44'),(7,8,20,'2026-10-24','2026-10-26','Báo cáo đồ án nhóm trên lớp','cancelled',NULL,NULL,NULL,'2026-10-23 02:00:00','2026-10-28 21:13:44'),(8,2,23,'2026-10-25','2026-10-31','Làm thí nghiệm IoT cho đề tài nghiên cứu','approved','2026-10-25 01:30:00',NULL,NULL,'2026-10-24 02:00:00','2026-10-28 21:13:44'),(9,3,26,'2026-10-26','2026-10-31','Thuyết trình seminar khoa CNTT','approved','2026-10-26 01:30:00',NULL,NULL,'2026-10-25 02:00:00','2026-10-28 21:13:44'),(10,4,29,'2026-10-27','2026-10-28','Thực hành cấu hình mạng LAN','approved','2026-10-27 01:30:00',NULL,NULL,'2026-10-26 02:00:00','2026-10-28 21:13:44'),(11,5,32,'2026-10-28','2026-11-01','Thực hành môn Lập trình mã nguồn mở','approved','2026-10-28 01:30:00',NULL,NULL,'2026-10-27 02:00:00','2026-10-28 21:13:44'),(12,6,35,'2026-10-30','2026-11-02','Báo cáo đồ án nhóm trên lớp','pending',NULL,NULL,NULL,'2026-10-29 02:00:00','2026-10-28 21:13:44'),(13,7,38,'2026-10-31','2026-11-02','Làm thí nghiệm IoT cho đề tài nghiên cứu','pending',NULL,NULL,NULL,'2026-10-30 02:00:00','2026-10-28 21:13:44'),(14,8,42,'2026-11-01','2026-11-06','Thuyết trình seminar khoa CNTT','pending',NULL,NULL,NULL,'2026-10-31 02:00:00','2026-10-28 21:13:44'),(15,2,45,'2026-10-29','2026-10-30','Thực hành cấu hình mạng LAN','pending',NULL,NULL,NULL,'2026-10-28 02:00:00','2026-10-28 21:13:44');
/*!40000 ALTER TABLE `transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Quản trị viên','admin@gmail.com','admin','2026-10-28 21:13:42','$2y$12$yHzEPLyCFqLlAB7EfM0uQO5YaB6SoaXSgvMtMOMCpO3z3BGMlckRC','mChNwrHjAA','2026-10-28 21:13:42','2026-10-28 21:13:42'),(2,'Nguyễn Văn An','user@gmail.com','user','2026-10-28 21:13:42','$2y$12$nfEVPQH9mfqbdVXo4PdI.OyOUPvTF8xejXzIKz26cWfDlsnqQNIRy','f0raW8LJ6z','2026-10-28 21:13:42','2026-10-28 21:13:42'),(3,'Trần Thị Bích','sv1@gmail.com','user','2026-10-28 21:13:42','$2y$12$OD5lwb9/sbD2vM4aV1d9Qunkg7njqXyM3OlbeNJdqNlOx6ksAl6KS','RbkaHF7VLM','2026-10-28 21:13:43','2026-10-28 21:13:43'),(4,'Lê Minh Châu','sv2@gmail.com','user','2026-10-28 21:13:43','$2y$12$lqetNQat.VaOalLYgnewYOD/JW9po/9a57vcgY1ENu8dXLA.Kdzbq','htMDppiIk6','2026-10-28 21:13:43','2026-10-28 21:13:43'),(5,'Phạm Quốc Dũng','sv3@gmail.com','user','2026-10-28 21:13:43','$2y$12$OwkLTb5KGkWRaun5bBkHYudtCbp5gONC6SMLLrxtk.RR7MgTsqrQa','UqzrTYQ1hC','2026-10-28 21:13:43','2026-10-28 21:13:43'),(6,'Hoàng Gia Hân','sv4@gmail.com','user','2026-10-28 21:13:43','$2y$12$65bzeyxZJ.add8oZc3njluCPNd7p8gw8LA.nLW3XbFvEtb5iZZfY.','wt63J3fbcd','2026-10-28 21:13:43','2026-10-28 21:13:43'),(7,'Võ Thành Khang','sv5@gmail.com','user','2026-10-28 21:13:43','$2y$12$dnBEWOtNa8.cLiN0oZ/GpuczEA5tfE4aUG2H9s0YFv9/K/OKNJGRW','HlPEtCNfeJ','2026-10-28 21:13:43','2026-10-28 21:13:43'),(8,'Đặng Ngọc Lan','sv6@gmail.com','user','2026-10-28 21:13:43','$2y$12$LAMjPRdv0TFxsi23.snSie6l9AvCpGbU2cpG.rAVH99MylP.ILv3e','lWtY1REyWZ','2026-10-28 21:13:44','2026-10-28 21:13:44');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-10-29 11:14:33
