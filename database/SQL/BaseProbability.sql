/*
Navicat MySQL Data Transfer

Source Server         : FU_NTUST
Source Server Version : 50505
Source Host           : 140.118.127.156:3306
Source Database       : sevenpk

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2017-11-20 22:31:52
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for baseprobability
-- ----------------------------
DROP TABLE IF EXISTS `baseprobability`;
CREATE TABLE `baseprobability` (
  `GameResult` varchar(255) DEFAULT NULL,
  `BaseProbability` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of baseprobability
-- ----------------------------
INSERT INTO `baseprobability` VALUES ('RoyalFlushOdd', '0.0222');
INSERT INTO `baseprobability` VALUES ('FiveOfAKindOdd', '0.0556');
INSERT INTO `baseprobability` VALUES ('STRFlushOdd', '0.0926');
INSERT INTO `baseprobability` VALUES ('FourOfAKindOdd', '0.222');
INSERT INTO `baseprobability` VALUES ('FullHouseOdd', '1.5873');
INSERT INTO `baseprobability` VALUES ('FlushOdd', '2.2222');
INSERT INTO `baseprobability` VALUES ('StrightOdd', '3.7037');
INSERT INTO `baseprobability` VALUES ('ThreeOfAKindOdd', '5.5556');
INSERT INTO `baseprobability` VALUES ('TwoPairsOdd', '11.1111');
INSERT INTO `baseprobability` VALUES ('Nothing', '75.4275');
