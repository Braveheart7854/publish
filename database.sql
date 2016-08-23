/*
Navicat MySQL Data Transfer

Source Server         : LOCAL_CENTOS
Source Server Version : 50549
Source Host           : 192.168.1.253:3306
Source Database       : publish

Target Server Type    : MYSQL
Target Server Version : 50549
File Encoding         : 65001

Date: 2016-08-23 21:30:04
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for project
-- ----------------------------
DROP TABLE IF EXISTS `project`;
CREATE TABLE `project` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PK',
  `uid` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `trunk` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '分支',
  `checkout` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '检出路径',
  `export` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '导出路径',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for task
-- ----------------------------
DROP TABLE IF EXISTS `task`;
CREATE TABLE `task` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PK',
  `uid` int(11) DEFAULT NULL COMMENT '用户ID',
  `projectId` int(11) DEFAULT NULL COMMENT '项目ID',
  `title` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '标题',
  `status` int(11) DEFAULT NULL COMMENT '状态',
  `branches` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '发布分支',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
