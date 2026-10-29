-- =============================================================
--  Lab Manager – Hệ thống mượn thiết bị phòng Lab (Laravel 13)
--  File CSDL MySQL 8 / MariaDB – xuất ngày 22/10/2026
--  Gồm cấu trúc bảng (sinh từ Migration) + dữ liệu mẫu (từ Seeder):
--    8 người dùng · 6 danh mục · 50 thiết bị · 15 phiếu mượn
--  Tài khoản demo: admin@gmail.com / 12345678  –  user@gmail.com / 12345678
--  Cách dùng: mở HeidiSQL/phpMyAdmin → chạy toàn bộ file này,
--  hoặc: mysql -u root -p < database.sql
-- =============================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
SET time_zone = '+07:00';

CREATE DATABASE IF NOT EXISTS `lab_manager` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `lab_manager`;

-- -------------------- Xóa bảng cũ (nếu có) --------------------
DROP TABLE IF EXISTS `migrations`;
DROP TABLE IF EXISTS `personal_access_tokens`;
DROP TABLE IF EXISTS `transactions`;
DROP TABLE IF EXISTS `devices`;
DROP TABLE IF EXISTS `categories`;
DROP TABLE IF EXISTS `failed_jobs`;
DROP TABLE IF EXISTS `job_batches`;
DROP TABLE IF EXISTS `jobs`;
DROP TABLE IF EXISTS `cache_locks`;
DROP TABLE IF EXISTS `cache`;
DROP TABLE IF EXISTS `sessions`;
DROP TABLE IF EXISTS `password_reset_tokens`;
DROP TABLE IF EXISTS `users`;

-- -------------------- Cấu trúc bảng --------------------
create table `migrations` (`id` int unsigned not null auto_increment primary key, `migration` varchar(255) not null, `batch` int not null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
create table `users` (`id` bigint unsigned not null auto_increment primary key, `name` varchar(255) not null, `email` varchar(255) not null, `role` varchar(255) not null default 'user', `email_verified_at` timestamp null, `password` varchar(255) not null, `remember_token` varchar(100) null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `users` add unique `users_email_unique`(`email`);
create table `password_reset_tokens` (`email` varchar(255) not null, `token` varchar(255) not null, `created_at` timestamp null, primary key (`email`)) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
create table `sessions` (`id` varchar(255) not null, `user_id` bigint unsigned null, `ip_address` varchar(45) null, `user_agent` text null, `payload` longtext not null, `last_activity` int not null, primary key (`id`)) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `sessions` add index `sessions_user_id_index`(`user_id`);
alter table `sessions` add index `sessions_last_activity_index`(`last_activity`);
create table `cache` (`key` varchar(255) not null, `value` mediumtext not null, `expiration` bigint not null, primary key (`key`)) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `cache` add index `cache_expiration_index`(`expiration`);
create table `cache_locks` (`key` varchar(255) not null, `owner` varchar(255) not null, `expiration` bigint not null, primary key (`key`)) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `cache_locks` add index `cache_locks_expiration_index`(`expiration`);
create table `jobs` (`id` bigint unsigned not null auto_increment primary key, `queue` varchar(255) not null, `payload` longtext not null, `attempts` smallint unsigned not null, `reserved_at` int unsigned null, `available_at` int unsigned not null, `created_at` int unsigned not null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `jobs` add index `jobs_queue_index`(`queue`);
create table `job_batches` (`id` varchar(255) not null, `name` varchar(255) not null, `total_jobs` int not null, `pending_jobs` int not null, `failed_jobs` int not null, `failed_job_ids` longtext not null, `options` mediumtext null, `cancelled_at` int null, `created_at` int not null, `finished_at` int null, primary key (`id`)) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
create table `failed_jobs` (`id` bigint unsigned not null auto_increment primary key, `uuid` varchar(255) not null, `connection` varchar(255) not null, `queue` varchar(255) not null, `payload` longtext not null, `exception` longtext not null, `failed_at` timestamp not null default CURRENT_TIMESTAMP) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `failed_jobs` add index `failed_jobs_connection_queue_failed_at_index`(`connection`, `queue`, `failed_at`);
alter table `failed_jobs` add unique `failed_jobs_uuid_unique`(`uuid`);
create table `categories` (`id` bigint unsigned not null auto_increment primary key, `name` varchar(100) not null, `icon` varchar(50) null, `description` text null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `categories` add unique `categories_name_unique`(`name`);
create table `devices` (`id` bigint unsigned not null auto_increment primary key, `category_id` bigint unsigned not null, `code` varchar(30) not null, `name` varchar(255) not null, `description` text null, `image` varchar(255) null, `status` enum('available', 'borrowed', 'maintenance') not null default 'available', `created_at` timestamp null, `updated_at` timestamp null, `deleted_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `devices` add constraint `devices_category_id_foreign` foreign key (`category_id`) references `categories` (`id`) on delete restrict;
alter table `devices` add index `devices_status_index`(`status`);
alter table `devices` add unique `devices_code_unique`(`code`);
create table `transactions` (`id` bigint unsigned not null auto_increment primary key, `user_id` bigint unsigned not null, `device_id` bigint unsigned not null, `borrow_date` date not null, `return_date` date not null, `purpose` varchar(500) null, `status` enum('pending', 'approved', 'returned', 'rejected', 'cancelled') not null default 'pending', `approved_at` timestamp null, `returned_at` timestamp null, `admin_note` varchar(255) null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `transactions` add constraint `transactions_user_id_foreign` foreign key (`user_id`) references `users` (`id`) on delete cascade;
alter table `transactions` add constraint `transactions_device_id_foreign` foreign key (`device_id`) references `devices` (`id`) on delete cascade;
alter table `transactions` add index `transactions_status_index`(`status`);
create table `personal_access_tokens` (`id` bigint unsigned not null auto_increment primary key, `tokenable_type` varchar(255) not null, `tokenable_id` bigint unsigned not null, `name` text not null, `token` varchar(64) not null, `abilities` text null, `last_used_at` timestamp null, `expires_at` timestamp null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `personal_access_tokens` add index `personal_access_tokens_tokenable_type_tokenable_id_index`(`tokenable_type`, `tokenable_id`);
alter table `personal_access_tokens` add unique `personal_access_tokens_token_unique`(`token`);
alter table `personal_access_tokens` add index `personal_access_tokens_expires_at_index`(`expires_at`);

-- -------------------- Dữ liệu bảng `migrations` (7 dòng) --------------------
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_10_12_142600_create_categories_table', 1),
(5, '2026_10_12_142628_create_devices_table', 1),
(6, '2026_10_12_142633_create_transactions_table', 1),
(7, '2026_10_14_125345_create_personal_access_tokens_table', 1);
ALTER TABLE `migrations` AUTO_INCREMENT = 8;

-- -------------------- Dữ liệu bảng `users` (8 dòng) --------------------
INSERT INTO `users` (`id`, `name`, `email`, `role`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Quản trị viên', 'admin@gmail.com', 'admin', '2026-10-22 09:10:12', '$2y$12$sRM.C4L9oBvU9X1UnYqoZ.jdxWZJOi0y48f0AbjabxYt8vDUMsHjW', 'Y2tD1rNFs9', '2026-10-22 09:10:12', '2026-10-22 09:10:12'),
(2, 'Nguyễn Văn An', 'user@gmail.com', 'user', '2026-10-22 09:10:12', '$2y$12$t2ZpBu1yH33E6h0hN2GTxOIzJzuR1K5xEAotp7s6.P6Uc2wDQpPgu', 'sh7Y5cVXdR', '2026-10-22 09:10:13', '2026-10-22 09:10:13'),
(3, 'Trần Thị Bích', 'sv1@gmail.com', 'user', '2026-10-22 09:10:13', '$2y$12$J/x91wdgjGe0tBCSZAH4ierDfGSo.jQsA0nfGCRebSXCfiocZ.jdO', 'XjMnpzBsTU', '2026-10-22 09:10:13', '2026-10-22 09:10:13'),
(4, 'Lê Minh Châu', 'sv2@gmail.com', 'user', '2026-10-22 09:10:13', '$2y$12$isNukIMP34BPxuCl6VXcPeDd7yx6NHrhrcL4ppw6RD1qOO0WC7cp.', 'Pcvev5DMZi', '2026-10-22 09:10:13', '2026-10-22 09:10:13'),
(5, 'Phạm Quốc Dũng', 'sv3@gmail.com', 'user', '2026-10-22 09:10:13', '$2y$12$hOo9KLXgm1/.go/dBS1/AerVH92xqaGCFICfsFHIPfp53.SFGT0pu', '4wDWJGukLb', '2026-10-22 09:10:13', '2026-10-22 09:10:13'),
(6, 'Hoàng Gia Hân', 'sv4@gmail.com', 'user', '2026-10-22 09:10:13', '$2y$12$K5geT6Q4qG8TIoFoeUWJUucEYD6svrPPGYAJTLF1yGK7UkSZIMrkK', 'Q4ICTYOm2Q', '2026-10-22 09:10:14', '2026-10-22 09:10:14'),
(7, 'Võ Thành Khang', 'sv5@gmail.com', 'user', '2026-10-22 09:10:14', '$2y$12$Kwjo6IGdzM9G6Epx0Gh.vejMkOp/FViXFIeMFjewPexR6cV57N9RO', 'F7TBuJzVYZ', '2026-10-22 09:10:14', '2026-10-22 09:10:14'),
(8, 'Đặng Ngọc Lan', 'sv6@gmail.com', 'user', '2026-10-22 09:10:14', '$2y$12$43iqEOKPvKA8nNzxQr2eouZmKhHNbX0AiCHUqW3Znh.MzvmBNHDKu', 'GD2afrj4yt', '2026-10-22 09:10:14', '2026-10-22 09:10:14');
ALTER TABLE `users` AUTO_INCREMENT = 9;

-- -------------------- Dữ liệu bảng `categories` (6 dòng) --------------------
INSERT INTO `categories` (`id`, `name`, `icon`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Laptop & Máy tính', 'laptop', 'Laptop, máy tính bàn mini phục vụ thực hành lập trình.', '2026-10-22 09:10:14', '2026-10-22 09:10:14'),
(2, 'Máy chiếu & Trình chiếu', 'projector', 'Máy chiếu, màn chiếu, bút trình chiếu cho buổi báo cáo.', '2026-10-22 09:10:14', '2026-10-22 09:10:14'),
(3, 'Thiết bị mạng', 'router', 'Router, switch, access point cho bài thực hành mạng máy tính.', '2026-10-22 09:10:14', '2026-10-22 09:10:14'),
(4, 'Vi điều khiển & IoT', 'cpu', 'Arduino, Raspberry Pi, ESP32 và các bộ kit cảm biến.', '2026-10-22 09:10:14', '2026-10-22 09:10:14'),
(5, 'Thiết bị đo lường', 'speedometer2', 'Đồng hồ vạn năng, máy hiện sóng, nguồn DC.', '2026-10-22 09:10:14', '2026-10-22 09:10:14'),
(6, 'Âm thanh & Ghi hình', 'mic', 'Micro, loa, webcam, máy quay phục vụ hội thảo.', '2026-10-22 09:10:14', '2026-10-22 09:10:14');
ALTER TABLE `categories` AUTO_INCREMENT = 7;

-- -------------------- Dữ liệu bảng `devices` (50 dòng) --------------------
INSERT INTO `devices` (`id`, `category_id`, `code`, `name`, `description`, `image`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'LAB-LT-001', 'Laptop Dell Latitude 5420', 'Thiết bị Laptop Dell Latitude 5420 thuộc nhóm Laptop & Máy tính, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.', NULL, 'available', '2026-10-22 09:10:14', '2026-10-22 09:10:14', NULL),
(2, 1, 'LAB-LT-002', 'Laptop HP ProBook 450 G9', 'Thiết bị Laptop HP ProBook 450 G9 thuộc nhóm Laptop & Máy tính, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.', NULL, 'available', '2026-10-22 09:10:14', '2026-10-22 09:10:14', NULL),
(3, 1, 'LAB-LT-003', 'Laptop Lenovo ThinkPad E14', 'Thiết bị Laptop Lenovo ThinkPad E14 thuộc nhóm Laptop & Máy tính, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.', NULL, 'available', '2026-10-22 09:10:14', '2026-10-22 09:10:14', NULL),
(4, 1, 'LAB-LT-004', 'Laptop ASUS ExpertBook B1', 'Thiết bị Laptop ASUS ExpertBook B1 thuộc nhóm Laptop & Máy tính, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.', NULL, 'available', '2026-10-22 09:10:14', '2026-10-22 09:10:14', NULL),
(5, 1, 'LAB-LT-005', 'MacBook Air M1', 'Thiết bị MacBook Air M1 thuộc nhóm Laptop & Máy tính, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.', NULL, 'available', '2026-10-22 09:10:14', '2026-10-22 09:10:14', NULL),
(6, 1, 'LAB-LT-006', 'Mini PC Intel NUC 11', 'Thiết bị Mini PC Intel NUC 11 thuộc nhóm Laptop & Máy tính, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.', NULL, 'maintenance', '2026-10-22 09:10:14', '2026-10-22 09:10:14', NULL),
(7, 1, 'LAB-LT-007', 'Laptop Acer Aspire 7', 'Thiết bị Laptop Acer Aspire 7 thuộc nhóm Laptop & Máy tính, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.', NULL, 'available', '2026-10-22 09:10:14', '2026-10-22 09:10:14', NULL),
(8, 1, 'LAB-LT-008', 'Laptop Dell Vostro 3520', 'Thiết bị Laptop Dell Vostro 3520 thuộc nhóm Laptop & Máy tính, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.', NULL, 'available', '2026-10-22 09:10:14', '2026-10-22 09:10:14', NULL),
(9, 1, 'LAB-LT-009', 'Laptop HP 240 G8', 'Thiết bị Laptop HP 240 G8 thuộc nhóm Laptop & Máy tính, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.', NULL, 'available', '2026-10-22 09:10:14', '2026-10-22 09:10:14', NULL),
(10, 2, 'LAB-MC-001', 'Máy chiếu Epson EB-X51', 'Thiết bị Máy chiếu Epson EB-X51 thuộc nhóm Máy chiếu & Trình chiếu, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.', NULL, 'available', '2026-10-22 09:10:14', '2026-10-22 09:10:14', NULL),
(11, 2, 'LAB-MC-002', 'Máy chiếu ViewSonic PA503S', 'Thiết bị Máy chiếu ViewSonic PA503S thuộc nhóm Máy chiếu & Trình chiếu, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.', NULL, 'available', '2026-10-22 09:10:14', '2026-10-22 09:10:14', NULL),
(12, 2, 'LAB-MC-003', 'Máy chiếu BenQ MX560', 'Thiết bị Máy chiếu BenQ MX560 thuộc nhóm Máy chiếu & Trình chiếu, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.', NULL, 'available', '2026-10-22 09:10:14', '2026-10-22 09:10:14', NULL),
(13, 2, 'LAB-MC-004', 'Máy chiếu Sony VPL-DX221', 'Thiết bị Máy chiếu Sony VPL-DX221 thuộc nhóm Máy chiếu & Trình chiếu, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.', NULL, 'available', '2026-10-22 09:10:14', '2026-10-22 09:10:14', NULL),
(14, 2, 'LAB-MC-005', 'Bút trình chiếu Logitech R400', 'Thiết bị Bút trình chiếu Logitech R400 thuộc nhóm Máy chiếu & Trình chiếu, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.', NULL, 'available', '2026-10-22 09:10:14', '2026-10-22 09:10:14', NULL),
(15, 2, 'LAB-MC-006', 'Màn chiếu treo tường 100 inch', 'Thiết bị Màn chiếu treo tường 100 inch thuộc nhóm Máy chiếu & Trình chiếu, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.', NULL, 'maintenance', '2026-10-22 09:10:14', '2026-10-22 09:10:14', NULL),
(16, 2, 'LAB-MC-007', 'Máy chiếu mini Xiaomi Wanbo T2', 'Thiết bị Máy chiếu mini Xiaomi Wanbo T2 thuộc nhóm Máy chiếu & Trình chiếu, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.', NULL, 'available', '2026-10-22 09:10:14', '2026-10-22 09:10:14', NULL),
(17, 2, 'LAB-MC-008', 'Bút laser Logitech Spotlight', 'Thiết bị Bút laser Logitech Spotlight thuộc nhóm Máy chiếu & Trình chiếu, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.', NULL, 'available', '2026-10-22 09:10:14', '2026-10-22 09:10:14', NULL),
(18, 3, 'LAB-MG-001', 'Router Cisco ISR 4321', 'Thiết bị Router Cisco ISR 4321 thuộc nhóm Thiết bị mạng, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.', NULL, 'available', '2026-10-22 09:10:14', '2026-10-22 09:10:14', NULL),
(19, 3, 'LAB-MG-002', 'Switch Cisco Catalyst 2960', 'Thiết bị Switch Cisco Catalyst 2960 thuộc nhóm Thiết bị mạng, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.', NULL, 'available', '2026-10-22 09:10:14', '2026-10-22 09:10:14', NULL),
(20, 3, 'LAB-MG-003', 'Router MikroTik hEX S', 'Thiết bị Router MikroTik hEX S thuộc nhóm Thiết bị mạng, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.', NULL, 'available', '2026-10-22 09:10:14', '2026-10-22 09:10:14', NULL),
(21, 3, 'LAB-MG-004', 'Access Point Ubiquiti UniFi 6 Lite', 'Thiết bị Access Point Ubiquiti UniFi 6 Lite thuộc nhóm Thiết bị mạng, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.', NULL, 'available', '2026-10-22 09:10:14', '2026-10-22 09:10:14', NULL),
(22, 3, 'LAB-MG-005', 'Switch TP-Link TL-SG108', 'Thiết bị Switch TP-Link TL-SG108 thuộc nhóm Thiết bị mạng, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.', NULL, 'available', '2026-10-22 09:10:14', '2026-10-22 09:10:14', NULL),
(23, 3, 'LAB-MG-006', 'Router Wi-Fi TP-Link Archer C6', 'Thiết bị Router Wi-Fi TP-Link Archer C6 thuộc nhóm Thiết bị mạng, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.', NULL, 'maintenance', '2026-10-22 09:10:14', '2026-10-22 09:10:14', NULL),
(24, 3, 'LAB-MG-007', 'Bộ bấm mạng & Tester cáp', 'Thiết bị Bộ bấm mạng & Tester cáp thuộc nhóm Thiết bị mạng, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.', NULL, 'available', '2026-10-22 09:10:14', '2026-10-22 09:10:14', NULL),
(25, 3, 'LAB-MG-008', 'Firewall Fortinet FortiGate 40F', 'Thiết bị Firewall Fortinet FortiGate 40F thuộc nhóm Thiết bị mạng, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.', NULL, 'borrowed', '2026-10-22 09:10:14', '2026-10-22 09:10:14', NULL),
(26, 4, 'LAB-IO-001', 'Arduino Uno R3', 'Thiết bị Arduino Uno R3 thuộc nhóm Vi điều khiển & IoT, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.', NULL, 'available', '2026-10-22 09:10:14', '2026-10-22 09:10:14', NULL),
(27, 4, 'LAB-IO-002', 'Raspberry Pi 4 Model B 4GB', 'Thiết bị Raspberry Pi 4 Model B 4GB thuộc nhóm Vi điều khiển & IoT, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.', NULL, 'available', '2026-10-22 09:10:14', '2026-10-22 09:10:14', NULL),
(28, 4, 'LAB-IO-003', 'Kit ESP32 DevKit V1', 'Thiết bị Kit ESP32 DevKit V1 thuộc nhóm Vi điều khiển & IoT, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.', NULL, 'borrowed', '2026-10-22 09:10:14', '2026-10-22 09:10:14', NULL),
(29, 4, 'LAB-IO-004', 'Bộ kit cảm biến 37 trong 1', 'Thiết bị Bộ kit cảm biến 37 trong 1 thuộc nhóm Vi điều khiển & IoT, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.', NULL, 'available', '2026-10-22 09:10:14', '2026-10-22 09:10:14', NULL),
(30, 4, 'LAB-IO-005', 'Arduino Mega 2560', 'Thiết bị Arduino Mega 2560 thuộc nhóm Vi điều khiển & IoT, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.', NULL, 'available', '2026-10-22 09:10:14', '2026-10-22 09:10:14', NULL),
(31, 4, 'LAB-IO-006', 'Raspberry Pi Pico W', 'Thiết bị Raspberry Pi Pico W thuộc nhóm Vi điều khiển & IoT, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.', NULL, 'maintenance', '2026-10-22 09:10:14', '2026-10-22 09:10:14', NULL),
(32, 4, 'LAB-IO-007', 'Module LoRa SX1278', 'Thiết bị Module LoRa SX1278 thuộc nhóm Vi điều khiển & IoT, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.', NULL, 'borrowed', '2026-10-22 09:10:14', '2026-10-22 09:10:14', NULL),
(33, 4, 'LAB-IO-008', 'Kit STM32 Nucleo F401RE', 'Thiết bị Kit STM32 Nucleo F401RE thuộc nhóm Vi điều khiển & IoT, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.', NULL, 'available', '2026-10-22 09:10:14', '2026-10-22 09:10:14', NULL),
(34, 4, 'LAB-IO-009', 'Jetson Nano Developer Kit', 'Thiết bị Jetson Nano Developer Kit thuộc nhóm Vi điều khiển & IoT, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.', NULL, 'available', '2026-10-22 09:10:14', '2026-10-22 09:10:14', NULL),
(35, 5, 'LAB-DL-001', 'Đồng hồ vạn năng Fluke 17B+', 'Thiết bị Đồng hồ vạn năng Fluke 17B+ thuộc nhóm Thiết bị đo lường, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.', NULL, 'borrowed', '2026-10-22 09:10:14', '2026-10-22 09:10:14', NULL),
(36, 5, 'LAB-DL-002', 'Máy hiện sóng Rigol DS1054Z', 'Thiết bị Máy hiện sóng Rigol DS1054Z thuộc nhóm Thiết bị đo lường, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.', NULL, 'available', '2026-10-22 09:10:14', '2026-10-22 09:10:14', NULL),
(37, 5, 'LAB-DL-003', 'Nguồn DC Lodestar 30V-5A', 'Thiết bị Nguồn DC Lodestar 30V-5A thuộc nhóm Thiết bị đo lường, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.', NULL, 'available', '2026-10-22 09:10:14', '2026-10-22 09:10:14', NULL),
(38, 5, 'LAB-DL-004', 'Máy phát xung UNI-T UTG932', 'Thiết bị Máy phát xung UNI-T UTG932 thuộc nhóm Thiết bị đo lường, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.', NULL, 'available', '2026-10-22 09:10:14', '2026-10-22 09:10:14', NULL),
(39, 5, 'LAB-DL-005', 'Đồng hồ kẹp dòng Kyoritsu 2117R', 'Thiết bị Đồng hồ kẹp dòng Kyoritsu 2117R thuộc nhóm Thiết bị đo lường, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.', NULL, 'available', '2026-10-22 09:10:14', '2026-10-22 09:10:14', NULL),
(40, 5, 'LAB-DL-006', 'Mỏ hàn Hakko FX-888D', 'Thiết bị Mỏ hàn Hakko FX-888D thuộc nhóm Thiết bị đo lường, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.', NULL, 'maintenance', '2026-10-22 09:10:14', '2026-10-22 09:10:14', NULL),
(41, 5, 'LAB-DL-007', 'Máy đo LCR UNI-T UT612', 'Thiết bị Máy đo LCR UNI-T UT612 thuộc nhóm Thiết bị đo lường, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.', NULL, 'available', '2026-10-22 09:10:14', '2026-10-22 09:10:14', NULL),
(42, 5, 'LAB-DL-008', 'Logic Analyzer Saleae 8 kênh', 'Thiết bị Logic Analyzer Saleae 8 kênh thuộc nhóm Thiết bị đo lường, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.', NULL, 'available', '2026-10-22 09:10:14', '2026-10-22 09:10:14', NULL),
(43, 6, 'LAB-AT-001', 'Micro không dây Shure BLX24', 'Thiết bị Micro không dây Shure BLX24 thuộc nhóm Âm thanh & Ghi hình, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.', NULL, 'available', '2026-10-22 09:10:14', '2026-10-22 09:10:14', NULL),
(44, 6, 'LAB-AT-002', 'Loa kéo JBL PartyBox 110', 'Thiết bị Loa kéo JBL PartyBox 110 thuộc nhóm Âm thanh & Ghi hình, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.', NULL, 'available', '2026-10-22 09:10:14', '2026-10-22 09:10:14', NULL),
(45, 6, 'LAB-AT-003', 'Webcam Logitech C920', 'Thiết bị Webcam Logitech C920 thuộc nhóm Âm thanh & Ghi hình, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.', NULL, 'available', '2026-10-22 09:10:14', '2026-10-22 09:10:14', NULL),
(46, 6, 'LAB-AT-004', 'Máy quay Sony HDR-CX405', 'Thiết bị Máy quay Sony HDR-CX405 thuộc nhóm Âm thanh & Ghi hình, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.', NULL, 'available', '2026-10-22 09:10:14', '2026-10-22 09:10:14', NULL),
(47, 6, 'LAB-AT-005', 'Micro thu âm Boya BY-M1', 'Thiết bị Micro thu âm Boya BY-M1 thuộc nhóm Âm thanh & Ghi hình, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.', NULL, 'available', '2026-10-22 09:10:14', '2026-10-22 09:10:14', NULL),
(48, 6, 'LAB-AT-006', 'Tai nghe Sony WH-1000XM4', 'Thiết bị Tai nghe Sony WH-1000XM4 thuộc nhóm Âm thanh & Ghi hình, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.', NULL, 'maintenance', '2026-10-22 09:10:14', '2026-10-22 09:10:14', NULL),
(49, 6, 'LAB-AT-007', 'Chân máy quay Benro T880', 'Thiết bị Chân máy quay Benro T880 thuộc nhóm Âm thanh & Ghi hình, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.', NULL, 'available', '2026-10-22 09:10:14', '2026-10-22 09:10:14', NULL),
(50, 6, 'LAB-AT-008', 'Loa hội nghị Jabra Speak 510', 'Thiết bị Loa hội nghị Jabra Speak 510 thuộc nhóm Âm thanh & Ghi hình, đã kiểm tra hoạt động tốt, đủ phụ kiện đi kèm. Vui lòng kiểm tra tình trạng khi nhận và bàn giao lại đúng hạn.', NULL, 'available', '2026-10-22 09:10:14', '2026-10-22 09:10:14', NULL);
ALTER TABLE `devices` AUTO_INCREMENT = 51;

-- -------------------- Dữ liệu bảng `transactions` (15 dòng) --------------------
INSERT INTO `transactions` (`id`, `user_id`, `device_id`, `borrow_date`, `return_date`, `purpose`, `status`, `approved_at`, `returned_at`, `admin_note`, `created_at`, `updated_at`) VALUES
(1, 2, 1, '2026-10-02', '2026-10-07', 'Thực hành môn Lập trình mã nguồn mở', 'returned', '2026-10-02 08:30:00', '2026-10-07 16:00:00', NULL, '2026-10-01 09:00:00', '2026-10-22 09:10:14'),
(2, 3, 4, '2026-10-07', '2026-10-10', 'Báo cáo đồ án nhóm trên lớp', 'returned', '2026-10-07 08:30:00', '2026-10-10 16:00:00', NULL, '2026-10-06 09:00:00', '2026-10-22 09:10:14'),
(3, 4, 8, '2026-10-10', '2026-10-17', 'Làm thí nghiệm IoT cho đề tài nghiên cứu', 'returned', '2026-10-10 08:30:00', '2026-10-17 16:00:00', NULL, '2026-10-09 09:00:00', '2026-10-22 09:10:14'),
(4, 5, 11, '2026-10-12', '2026-10-14', 'Thuyết trình seminar khoa CNTT', 'returned', '2026-10-12 08:30:00', '2026-10-14 16:00:00', NULL, '2026-10-11 09:00:00', '2026-10-22 09:10:14'),
(5, 6, 14, '2026-10-14', '2026-10-18', 'Thực hành cấu hình mạng LAN', 'returned', '2026-10-14 08:30:00', '2026-10-18 16:00:00', NULL, '2026-10-13 09:00:00', '2026-10-22 09:10:14'),
(6, 7, 18, '2026-10-16', '2026-10-19', 'Thực hành môn Lập trình mã nguồn mở', 'rejected', NULL, NULL, 'Thiết bị đã được đặt cho buổi thi.', '2026-10-15 09:00:00', '2026-10-22 09:10:14'),
(7, 8, 21, '2026-10-17', '2026-10-19', 'Báo cáo đồ án nhóm trên lớp', 'cancelled', NULL, NULL, NULL, '2026-10-16 09:00:00', '2026-10-22 09:10:14'),
(8, 2, 25, '2026-10-18', '2026-10-24', 'Làm thí nghiệm IoT cho đề tài nghiên cứu', 'approved', '2026-10-18 08:30:00', NULL, NULL, '2026-10-17 09:00:00', '2026-10-22 09:10:14'),
(9, 3, 28, '2026-10-19', '2026-10-24', 'Thuyết trình seminar khoa CNTT', 'approved', '2026-10-19 08:30:00', NULL, NULL, '2026-10-18 09:00:00', '2026-10-22 09:10:14'),
(10, 4, 32, '2026-10-20', '2026-10-21', 'Thực hành cấu hình mạng LAN', 'approved', '2026-10-20 08:30:00', NULL, NULL, '2026-10-19 09:00:00', '2026-10-22 09:10:14'),
(11, 5, 35, '2026-10-21', '2026-10-25', 'Thực hành môn Lập trình mã nguồn mở', 'approved', '2026-10-21 08:30:00', NULL, NULL, '2026-10-20 09:00:00', '2026-10-22 09:10:14'),
(12, 6, 38, '2026-10-23', '2026-10-26', 'Báo cáo đồ án nhóm trên lớp', 'pending', NULL, NULL, NULL, '2026-10-22 09:00:00', '2026-10-22 09:10:14'),
(13, 7, 42, '2026-10-24', '2026-10-26', 'Làm thí nghiệm IoT cho đề tài nghiên cứu', 'pending', NULL, NULL, NULL, '2026-10-23 09:00:00', '2026-10-22 09:10:14'),
(14, 8, 45, '2026-10-25', '2026-10-30', 'Thuyết trình seminar khoa CNTT', 'pending', NULL, NULL, NULL, '2026-10-24 09:00:00', '2026-10-22 09:10:14'),
(15, 2, 49, '2026-10-22', '2026-10-23', 'Thực hành cấu hình mạng LAN', 'pending', NULL, NULL, NULL, '2026-10-21 09:00:00', '2026-10-22 09:10:14');
ALTER TABLE `transactions` AUTO_INCREMENT = 16;

SET FOREIGN_KEY_CHECKS = 1;
