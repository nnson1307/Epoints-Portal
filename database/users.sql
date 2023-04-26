/*
Navicat MySQL Data Transfer

Source Server         : local
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : chatroom

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2018-02-23 16:32:38
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('1', 'ProductGroup', 'admin@tamtm.com', '$2y$10$vxi0Yath8V9upmJnoqOny.Wu1OGVtXXtxNNKWqpPbENQvKnrQC8Ty', 'fKs4swqdKmGyYEJQP9E29b1IR3KFofGOzYg6AeYKMPUZBGjIn5VAFn1xyWWr', '1', '2017-03-11 08:58:25', '2017-10-06 20:03:02');
INSERT INTO `users` VALUES ('2', 'admin01', 'admin01@gmail.com', '$2y$10$PCfUpSQ2pKJgiLXbCYeUGexK9UQxaK86xGbOZEAeOpKtJNNlvQONW', 'VzwX31C4m8fEhMP6y7alehT1CbTrSwKNqOYs4faFYZRKrCqXEQU4Xbpvphp0', '1', '2017-10-23 14:09:39', '2017-10-23 14:11:38');
INSERT INTO `users` VALUES ('3', 'ProductGroup 02', 'admin02@gmail.com', '$2y$10$t1pkx5jdtX8rJ9sZ.fApLeM7QpGkyJQq85lzaJ6YuujcSoRv/475i', null, '1', '2017-10-23 14:11:12', '2017-10-23 14:11:36');
INSERT INTO `users` VALUES ('5', 'test', 'test1@gmail.com', '$2y$10$yRoj0LhFPwBzhGmqG6NG.OLiP1WxE1mCjPUI0AyVPhYFWzgKVvY9O', null, '0', '2018-01-04 07:45:25', '2018-01-04 07:45:25');
