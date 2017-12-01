/*
Navicat MySQL Data Transfer

Source Server         : FU_NTUST
Source Server Version : 50505
Source Host           : 140.118.127.156:3306
Source Database       : sevenpk

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2017-12-01 14:17:17
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for machinepublicsetting
-- ----------------------------
DROP TABLE IF EXISTS `machinepublicsetting`;
CREATE TABLE `machinepublicsetting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `JokerWin` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of machinepublicsetting
-- ----------------------------
INSERT INTO `machinepublicsetting` VALUES ('1', '1');
