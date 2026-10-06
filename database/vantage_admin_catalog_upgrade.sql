-- Vantage Luxe Realty: database-driven property types, purposes and enhanced pricing
-- Target: MariaDB 10.4+ / MySQL 8+
-- Run once AFTER taking a database backup. Safe to re-run on MariaDB 10.4+.

START TRANSACTION;

CREATE TABLE IF NOT EXISTS `property_purposes` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(120) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_property_purposes_name` (`name`),
  UNIQUE KEY `uq_property_purposes_slug` (`slug`),
  KEY `idx_property_purposes_active_sort` (`is_active`,`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `property_types` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(120) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_property_types_name` (`name`),
  UNIQUE KEY `uq_property_types_slug` (`slug`),
  KEY `idx_property_types_active_sort` (`is_active`,`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sensible defaults. You can rename/deactivate these from Admin later.
INSERT IGNORE INTO `property_purposes` (`name`,`slug`,`description`,`is_active`,`sort_order`) VALUES
('For Sale','sale','Properties available for outright purchase.',1,10),
('For Rent','rent','Properties available for rental.',1,20),
('Shortlet (Daily)','shortlet','Short-stay and daily accommodation.',1,30);

-- Preserve any legacy/custom purpose values already stored on properties.
INSERT IGNORE INTO `property_purposes` (`name`,`slug`,`is_active`,`sort_order`)
SELECT
  CONCAT(UPPER(LEFT(TRIM(`purpose`),1)), SUBSTRING(TRIM(`purpose`),2)),
  LOWER(REPLACE(REPLACE(REPLACE(TRIM(`purpose`),' ','-'),'/','-'),'_','-')),
  1,
  100
FROM `properties`
WHERE TRIM(COALESCE(`purpose`,'')) <> '';

-- Import existing property type text values into the new master table.
INSERT IGNORE INTO `property_types` (`name`,`slug`,`is_active`,`sort_order`)
SELECT DISTINCT
  TRIM(`property_type`),
  LOWER(REPLACE(REPLACE(REPLACE(TRIM(`property_type`),' ','-'),'/','-'),'_','-')),
  1,
  100
FROM `properties`
WHERE TRIM(COALESCE(`property_type`,'')) <> '';

-- Purpose used to be an ENUM. VARCHAR makes newly-created database purposes truly dynamic.
ALTER TABLE `properties` MODIFY COLUMN `purpose` varchar(120) NOT NULL;
ALTER TABLE `properties` ADD COLUMN IF NOT EXISTS `purpose_id` int unsigned DEFAULT NULL AFTER `purpose`;
ALTER TABLE `properties` ADD COLUMN IF NOT EXISTS `property_type_id` int unsigned DEFAULT NULL AFTER `property_type`;

UPDATE `properties` p
JOIN `property_purposes` pp ON pp.`slug` = LOWER(REPLACE(REPLACE(REPLACE(TRIM(p.`purpose`),' ','-'),'/','-'),'_','-'))
SET p.`purpose_id` = pp.`id`
WHERE p.`purpose_id` IS NULL;

UPDATE `properties` p
JOIN `property_types` pt ON LOWER(pt.`name`) = LOWER(TRIM(p.`property_type`))
SET p.`property_type_id` = pt.`id`
WHERE p.`property_type_id` IS NULL;

-- Ensure the pricing table exists even if this patch is applied independently.
CREATE TABLE IF NOT EXISTS `property_prices` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `property_id` int unsigned NOT NULL,
  `purpose_id` int unsigned DEFAULT NULL,
  `price` decimal(15,2) NOT NULL,
  `price_unit` varchar(30) NOT NULL DEFAULT 'One Time',
  `discount_price` decimal(15,2) DEFAULT NULL,
  `discount_percentage` decimal(5,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_property_prices_property` (`property_id`),
  KEY `idx_property_prices_purpose` (`purpose_id`),
  KEY `idx_property_prices_amount` (`price`),
  CONSTRAINT `fk_property_prices_property_catalog` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `property_prices` ADD COLUMN IF NOT EXISTS `purpose_id` int unsigned DEFAULT NULL AFTER `property_id`;
ALTER TABLE `property_prices` ADD COLUMN IF NOT EXISTS `discount_percentage` decimal(5,2) DEFAULT NULL AFTER `discount_price`;

UPDATE `property_prices`
SET `discount_percentage` = ROUND(((`price` - `discount_price`) / `price`) * 100, 2)
WHERE `price` > 0
  AND `discount_price` IS NOT NULL
  AND `discount_price` > 0
  AND `discount_price` < `price`;

UPDATE `property_prices`
SET `discount_percentage` = NULL
WHERE `discount_price` IS NULL OR `discount_price` <= 0 OR `discount_price` >= `price`;

ALTER TABLE `properties` ADD INDEX IF NOT EXISTS `idx_properties_purpose_id_status` (`purpose_id`,`status`);
ALTER TABLE `properties` ADD INDEX IF NOT EXISTS `idx_properties_property_type_id_status` (`property_type_id`,`status`);
ALTER TABLE `property_prices` ADD INDEX IF NOT EXISTS `idx_property_prices_purpose` (`purpose_id`);

COMMIT;
