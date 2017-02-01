-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.6.25 - MySQL Community Server (GPL)
-- Server OS:                    Win32
-- HeidiSQL Version:             9.2.0.4947
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping database structure for yii2advanced
DROP DATABASE IF EXISTS `yii2advanced`;
CREATE DATABASE IF NOT EXISTS `yii2advanced` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `yii2advanced`;


-- Dumping structure for table yii2advanced.addtocart
DROP TABLE IF EXISTS `addtocart`;
CREATE TABLE IF NOT EXISTS `addtocart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `productname` varchar(255) DEFAULT NULL,
  `quantitybeli` decimal(65,0) DEFAULT NULL,
  `price` double(11,0) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_buy` int(11) DEFAULT NULL,
  `usevoucher` int(11) DEFAULT NULL,
  `priceretail` double DEFAULT NULL,
  `checkoutid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- Dumping data for table yii2advanced.addtocart: ~4 rows (approximately)
DELETE FROM `addtocart`;
/*!40000 ALTER TABLE `addtocart` DISABLE KEYS */;
INSERT INTO `addtocart` (`id`, `productname`, `quantitybeli`, `price`, `description`, `image`, `is_buy`, `usevoucher`, `priceretail`, `checkoutid`) VALUES
	(6, 'SECRET KEY', 12, 50, 'Secret Key Starting Treatment Essence 155ml', 'themes/images/secretkey.png', 1, NULL, 90, 2);
/*!40000 ALTER TABLE `addtocart` ENABLE KEYS */;


-- Dumping structure for table yii2advanced.checkout
DROP TABLE IF EXISTS `checkout`;
CREATE TABLE IF NOT EXISTS `checkout` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `voucher` varchar(255) DEFAULT NULL,
  `shipping` varchar(255) DEFAULT NULL,
  `shippingfee` double DEFAULT NULL,
  `discount` double DEFAULT NULL,
  `discounttype` int(11) DEFAULT NULL,
  `totalprice` double DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Dumping data for table yii2advanced.checkout: ~0 rows (approximately)
DELETE FROM `checkout`;
/*!40000 ALTER TABLE `checkout` DISABLE KEYS */;
INSERT INTO `checkout` (`id`, `voucher`, `shipping`, `shippingfee`, `discount`, `discounttype`, `totalprice`) VALUES
	(2, '1', '1', 10, 5, NULL, 610);
/*!40000 ALTER TABLE `checkout` ENABLE KEYS */;


-- Dumping structure for table yii2advanced.country
DROP TABLE IF EXISTS `country`;
CREATE TABLE IF NOT EXISTS `country` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `fee` double(255,0) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- Dumping data for table yii2advanced.country: ~2 rows (approximately)
DELETE FROM `country`;
/*!40000 ALTER TABLE `country` DISABLE KEYS */;
INSERT INTO `country` (`id`, `name`, `fee`, `description`) VALUES
	(1, 'Malaysia', 10, 'Buy 2 free shipping / Minimum purchase > MYR 150, otherwise the shipping fee is MYR 10'),
	(2, 'Brunei', 25, 'Minimum purchase > MYR 300, otherwise the shipping fee is MYR 25'),
	(3, 'Singapore', 20, 'Minimum purchase > MYR 300, otherwise the shipping fee is MYR 20');
/*!40000 ALTER TABLE `country` ENABLE KEYS */;


-- Dumping structure for table yii2advanced.migration
DROP TABLE IF EXISTS `migration`;
CREATE TABLE IF NOT EXISTS `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table yii2advanced.migration: ~2 rows (approximately)
DELETE FROM `migration`;
/*!40000 ALTER TABLE `migration` DISABLE KEYS */;
INSERT INTO `migration` (`version`, `apply_time`) VALUES
	('m000000_000000_base', 1485360524),
	('m130524_201442_init', 1485360532);
/*!40000 ALTER TABLE `migration` ENABLE KEYS */;


-- Dumping structure for table yii2advanced.productlist
DROP TABLE IF EXISTS `productlist`;
CREATE TABLE IF NOT EXISTS `productlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `productname` varchar(255) DEFAULT NULL,
  `quantity` decimal(65,0) DEFAULT NULL,
  `price` double(11,0) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `priceretail` double(11,0) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- Dumping data for table yii2advanced.productlist: ~4 rows (approximately)
DELETE FROM `productlist`;
/*!40000 ALTER TABLE `productlist` DISABLE KEYS */;
INSERT INTO `productlist` (`id`, `productname`, `quantity`, `price`, `description`, `image`, `priceretail`) VALUES
	(1, 'SECRET KEY', 30, 50, 'Secret Key Starting Treatment Essence 155ml', 'themes/images/secretkey.png', 90),
	(2, 'POBLING', 30, 100, 'Pobling Pore Sonic Cleanser_Color [#Gold]', 'themes/images/pobling.png', 180),
	(3, 'LABIOTTE', 30, 150, 'Labiotte Chateau Wine Lipstick [Melting] 3.7g [8 Colors To Choose]', 'themes/images/labiotte.png', 200),
	(4, 'BIOTHERM', 30, 250, 'Biotherm Skin Regenerating Treatment', 'themes/images/biotherm.png', 300);
/*!40000 ALTER TABLE `productlist` ENABLE KEYS */;


-- Dumping structure for table yii2advanced.student
DROP TABLE IF EXISTS `student`;
CREATE TABLE IF NOT EXISTS `student` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- Dumping data for table yii2advanced.student: ~5 rows (approximately)
DELETE FROM `student`;
/*!40000 ALTER TABLE `student` DISABLE KEYS */;
INSERT INTO `student` (`id`, `full_name`, `address`, `phone`) VALUES
	(1, 'Jojohn', 'Taman Puaka', '011111'),
	(3, 'Jaja', 'adasd', '123123123123'),
	(4, 'Juju', 'nfna', 'asdasd123'),
	(5, 'nanaz', 'teman tapi mesra', '01293123'),
	(6, 'asdadasjdl', 'laksjdklajdklajd', '901283901380');
/*!40000 ALTER TABLE `student` ENABLE KEYS */;


-- Dumping structure for table yii2advanced.user
DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '10',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `password_reset_token` (`password_reset_token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table yii2advanced.user: ~0 rows (approximately)
DELETE FROM `user`;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
/*!40000 ALTER TABLE `user` ENABLE KEYS */;


-- Dumping structure for table yii2advanced.voucher
DROP TABLE IF EXISTS `voucher`;
CREATE TABLE IF NOT EXISTS `voucher` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `discount` double(255,0) DEFAULT NULL,
  `type` int(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Dumping data for table yii2advanced.voucher: ~2 rows (approximately)
DELETE FROM `voucher`;
/*!40000 ALTER TABLE `voucher` DISABLE KEYS */;
INSERT INTO `voucher` (`id`, `name`, `description`, `discount`, `type`) VALUES
	(1, 'OFF5PC', '5% discount, with minimum purchase of 2 quantities of the product', 5, 1),
	(2, 'GIVEME15', ' RM15 discount with minimum purchase of MYR 100', 15, 2);
/*!40000 ALTER TABLE `voucher` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
