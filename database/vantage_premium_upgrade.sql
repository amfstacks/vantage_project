-- Vantage Luxe Realty premium upgrade
-- Target: MariaDB 10.4+ / MySQL 8+
-- Run this once after backing up the production database.

START TRANSACTION;

-- Supporting tables used by the existing application. CREATE IF NOT EXISTS makes
-- this safe when these tables are already present in your production database.
CREATE TABLE IF NOT EXISTS `property_images` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `property_id` int unsigned NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_property_images_property` (`property_id`),
  KEY `idx_property_images_primary` (`property_id`,`is_primary`),
  CONSTRAINT `fk_property_images_property` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `property_prices` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `property_id` int unsigned NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `price_unit` varchar(30) NOT NULL DEFAULT 'One Time',
  `discount_price` decimal(15,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_property_prices_property` (`property_id`),
  KEY `idx_property_prices_amount` (`price`),
  CONSTRAINT `fk_property_prices_property` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Backfill legacy property-level prices into the normalized price table when needed.
INSERT INTO `property_prices` (`property_id`, `price`, `price_unit`, `discount_price`)
SELECT p.`id`, p.`price`, COALESCE(NULLIF(p.`price_unit`, ''), 'One Time'), p.`discount_price`
FROM `properties` p
WHERE p.`price` > 0
  AND NOT EXISTS (SELECT 1 FROM `property_prices` pp WHERE pp.`property_id` = p.`id`);

CREATE TABLE IF NOT EXISTS `amenities` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL,
  `icon` varchar(80) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_amenities_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `property_amenities` (
  `property_id` int unsigned NOT NULL,
  `amenity_id` int unsigned NOT NULL,
  PRIMARY KEY (`property_id`,`amenity_id`),
  KEY `idx_property_amenities_amenity` (`amenity_id`),
  CONSTRAINT `fk_property_amenities_property` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_property_amenities_amenity` FOREIGN KEY (`amenity_id`) REFERENCES `amenities` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `property_requests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `property_id` int unsigned NOT NULL,
  `request_reference` varchar(32) NOT NULL,
  `full_name` varchar(120) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `email` varchar(160) DEFAULT NULL,
  `request_type` enum('information','viewing','call','offer') NOT NULL DEFAULT 'information',
  `preferred_date` date DEFAULT NULL,
  `message` text DEFAULT NULL,
  `status` enum('new','contacted','scheduled','closed') NOT NULL DEFAULT 'new',
  `source_url` varchar(500) DEFAULT NULL,
  `client_ip_hash` char(64) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_property_requests_reference` (`request_reference`),
  KEY `idx_property_requests_property` (`property_id`),
  KEY `idx_property_requests_status_created` (`status`,`created_at`),
  CONSTRAINT `fk_property_requests_property` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Optional analytics field. The application checks for its existence before incrementing it.
ALTER TABLE `properties` ADD COLUMN IF NOT EXISTS `view_count` int unsigned NOT NULL DEFAULT 0 AFTER `meta_description`;

-- Helpful search indexes for the upgraded listing experience.
-- MariaDB supports IF NOT EXISTS on ADD INDEX; if your host reports an existing-index
-- warning, it can be safely ignored.
ALTER TABLE `properties` ADD INDEX IF NOT EXISTS `idx_properties_status_created` (`status`,`created_at`);
ALTER TABLE `properties` ADD INDEX IF NOT EXISTS `idx_properties_purpose_status` (`purpose`,`status`);
ALTER TABLE `properties` ADD INDEX IF NOT EXISTS `idx_properties_location_status` (`location`,`status`);
ALTER TABLE `properties` ADD INDEX IF NOT EXISTS `idx_properties_slug` (`slug`);

COMMIT;
