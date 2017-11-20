/*
Navicat MySQL Data Transfer

Source Server         : FU_NTUST
Source Server Version : 50505
Source Host           : 140.118.127.156:3306
Source Database       : sevenpk

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2017-11-20 22:31:31
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for probabilityadj
-- ----------------------------
DROP TABLE IF EXISTS `probabilityadj`;
CREATE TABLE `probabilityadj` (
  `Weight` smallint(2) NOT NULL,
  `AdjMagnification` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of probabilityadj
-- ----------------------------
INSERT INTO `probabilityadj` VALUES ('0', '0');
INSERT INTO `probabilityadj` VALUES ('1', '1.12');
INSERT INTO `probabilityadj` VALUES ('2', '1.09');
INSERT INTO `probabilityadj` VALUES ('3', '1.06');
INSERT INTO `probabilityadj` VALUES ('4', '1.03');
INSERT INTO `probabilityadj` VALUES ('5', '1');
INSERT INTO `probabilityadj` VALUES ('6', '0.97');
INSERT INTO `probabilityadj` VALUES ('7', '0.94');
INSERT INTO `probabilityadj` VALUES ('8', '0.91');
INSERT INTO `probabilityadj` VALUES ('9', '0.88');
INSERT INTO `probabilityadj` VALUES ('10', '0.85');
