-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         8.0.30 - MySQL Community Server - GPL
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Volcando datos para la tabla store_sport.cache: ~0 rows (aproximadamente)
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
	('products', 'a:2:{i:0;a:16:{s:2:"id";i:1;s:11:"id_category";i:1;s:3:"sku";i:12312;s:4:"name";s:17:"Zapatillas Adidas";s:11:"description";s:6:"sdfsdf";s:6:"amount";i:35000;s:3:"img";s:6:"1.avif";s:7:"visible";i:1;s:10:"created_at";N;s:10:"updated_at";N;s:15:"product_variant";a:2:{i:0;a:4:{s:2:"id";i:1;s:10:"product_id";i:1;s:5:"color";s:6:"Blanco";s:7:"variant";s:86:"[{"size": "38", "stock": 8}, {"size": "40", "stock": 20}, {"size": "41", "stock": 24}]";}i:1;a:4:{s:2:"id";i:2;s:10:"product_id";i:1;s:5:"color";s:5:"Negro";s:7:"variant";s:28:"[{"size": "41", "stock": 2}]";}}s:8:"cantidad";i:1;s:11:"stockActual";i:8;s:15:"selectedVariant";a:4:{s:2:"id";i:1;s:10:"product_id";i:1;s:5:"color";s:6:"Blanco";s:7:"variant";s:86:"[{"size": "38", "stock": 8}, {"size": "40", "stock": 20}, {"size": "41", "stock": 24}]";}s:13:"selectedColor";s:6:"Blanco";s:12:"selectedSize";s:2:"38";}i:1;a:16:{s:2:"id";i:2;s:11:"id_category";i:1;s:3:"sku";i:546456;s:4:"name";s:16:"Air Jordan 1 Mid";s:11:"description";s:5:"xxxxx";s:6:"amount";i:90000;s:3:"img";s:21:"819564-1200-1200.webp";s:7:"visible";i:1;s:10:"created_at";N;s:10:"updated_at";N;s:15:"product_variant";a:1:{i:0;a:4:{s:2:"id";i:3;s:10:"product_id";i:2;s:5:"color";s:10:"MultiColor";s:7:"variant";s:57:"[{"size": "41", "stock": 30}, {"size": "40", "stock": 5}]";}}s:8:"cantidad";i:2;s:15:"selectedVariant";a:4:{s:2:"id";i:3;s:10:"product_id";i:2;s:5:"color";s:10:"MultiColor";s:7:"variant";s:57:"[{"size": "41", "stock": 30}, {"size": "40", "stock": 5}]";}s:11:"stockActual";i:5;s:13:"selectedColor";s:10:"MultiColor";s:12:"selectedSize";s:2:"40";}}', 2034983483);

-- Volcando datos para la tabla store_sport.cache_locks: ~0 rows (aproximadamente)

-- Volcando datos para la tabla store_sport.categories: ~1 rows (aproximadamente)
INSERT INTO `categories` (`id`, `name`, `featured`, `created_at`, `updated_at`) VALUES
	(1, 'Zapatillas', '0', NULL, NULL);

-- Volcando datos para la tabla store_sport.migrations: ~7 rows (aproximadamente)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(2, '2024_06_11_144156_create_categories_table', 1),
	(3, '2024_06_11_144231_create_products_table', 1),
	(4, '2024_06_11_145351_create_personal_access_tokens_table', 1),
	(8, '2024_06_11_150447_create_product_variants_table', 2),
	(10, '2024_06_11_223146_create_transactions_table', 3),
	(11, '0001_01_01_000000_create_users_table', 4),
	(12, '2024_06_12_031935_create_purchases_table', 5),
	(13, '2024_06_12_032515_create_cache_table', 5);

-- Volcando datos para la tabla store_sport.personal_access_tokens: ~0 rows (aproximadamente)

-- Volcando datos para la tabla store_sport.products: ~0 rows (aproximadamente)
INSERT INTO `products` (`id`, `id_category`, `sku`, `name`, `description`, `amount`, `img`, `visible`, `created_at`, `updated_at`) VALUES
	(1, 1, 12312, 'Zapatillas Adidas', 'sdfsdf', 35000, '1.avif', 1, NULL, NULL),
	(2, 1, 546456, 'Air Jordan 1 Mid', 'xxxxx', 90000, '819564-1200-1200.webp', 1, NULL, NULL);

-- Volcando datos para la tabla store_sport.product_variants: ~3 rows (aproximadamente)
INSERT INTO `product_variants` (`id`, `product_id`, `color`, `variant`) VALUES
	(1, 1, 'Blanco', '[{"size": "38", "stock": 7}, {"size": "40", "stock": 20}, {"size": "41", "stock": 24}]'),
	(2, 1, 'Negro', '[{"size": "41", "stock": 2}]'),
	(3, 2, 'MultiColor', '[{"size": "41", "stock": 30}, {"size": "40", "stock": 3}]');

-- Volcando datos para la tabla store_sport.purchases: ~0 rows (aproximadamente)

-- Volcando datos para la tabla store_sport.transactions: ~0 rows (aproximadamente)

-- Volcando datos para la tabla store_sport.users: ~0 rows (aproximadamente)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
