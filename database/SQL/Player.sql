/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : sevenpk

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2017-08-25 17:40:31
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for player
-- ----------------------------
DROP TABLE IF EXISTS `player`;
CREATE TABLE `player` (
  `ID` int(255) NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL DEFAULT '0',
  `CardNumber` varchar(255) DEFAULT NULL,
  `Balance` int(11) NOT NULL DEFAULT '0',
  `CardType` varchar(255) DEFAULT NULL,
  `IDCardNumber` varchar(255) NOT NULL DEFAULT '0',
  `Cellphone` varchar(255) DEFAULT NULL,
  `IntroducerID` int(11) NOT NULL DEFAULT '0',
  `DocumentFront` blob,
  `DocumentBack` blob,
  `Photo` blob,
  `Gender` int(1) NOT NULL DEFAULT '2',
  `NickName` varchar(255) DEFAULT NULL,
  `Career` varchar(255) DEFAULT NULL,
  `Coming` int(1) DEFAULT NULL,
  `ReceiveAd` int(1) DEFAULT NULL,
  `Telephone` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of player
-- ----------------------------
INSERT INTO `player` VALUES ('1', '000014', 'BN00717', '0', '會員', 'W238268364', '0912875961', '1', null, null, null, '2', null, null, null, null, null);
