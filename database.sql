/*
Navicat MySQL Data Transfer

Source Server         : local_b_db
Source Server Version : 50632
Source Host           : 192.168.1.22:3306
Source Database       : publish

Target Server Type    : MYSQL
Target Server Version : 50632
File Encoding         : 65001

Date: 2016-08-24 01:04:43
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for project
-- ----------------------------
DROP TABLE IF EXISTS `project`;
CREATE TABLE `project` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PK',
  `uid` int(11) DEFAULT NULL,
  `name` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `trunk` varchar(255) DEFAULT NULL COMMENT '分支',
  `checkout` varchar(255) DEFAULT NULL COMMENT '检出路径',
  `export` varchar(255) DEFAULT NULL COMMENT '导出路径',
  `remote_host` varchar(255) DEFAULT NULL COMMENT '目标机器',
  `remote_user` varchar(255) DEFAULT NULL COMMENT '目标用户',
  `excludes` varchar(255) DEFAULT NULL COMMENT '忽略文件',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for task
-- ----------------------------
DROP TABLE IF EXISTS `task`;
CREATE TABLE `task` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PK',
  `uid` int(11) DEFAULT NULL COMMENT '用户ID',
  `projectId` int(11) DEFAULT NULL COMMENT '项目ID',
  `title` varchar(255) DEFAULT NULL COMMENT '标题',
  `status` int(11) DEFAULT NULL COMMENT '状态',
  `branches` varchar(255) DEFAULT NULL COMMENT '发布分支',
  `errorMsg` varchar(255) DEFAULT NULL COMMENT '错误信息',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
