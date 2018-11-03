/*
Navicat MySQL Data Transfer

Source Server         : 本地
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : vegetable

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2018-11-03 11:39:03
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for permission_admin
-- ----------------------------
DROP TABLE IF EXISTS `permission_admin`;
CREATE TABLE `permission_admin` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uname` varchar(40) NOT NULL,
  `pwd` varchar(50) NOT NULL,
  `nikename` varchar(50) DEFAULT NULL,
  `sid` varchar(32) NOT NULL,
  `createtime` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uname` (`uname`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='管理员表';

-- ----------------------------
-- Records of permission_admin
-- ----------------------------
INSERT INTO `permission_admin` VALUES ('1', 'admin', '5423831c2ac685e3ec8951b3b63f75a4', '超级管理员', '26ce26cd90c7a16c0aa047de179afc52', '1510062870');

-- ----------------------------
-- Table structure for permission_admin_nav
-- ----------------------------
DROP TABLE IF EXISTS `permission_admin_nav`;
CREATE TABLE `permission_admin_nav` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '菜单表',
  `pid` int(11) unsigned DEFAULT '0' COMMENT '所属菜单',
  `name` varchar(15) DEFAULT '' COMMENT '菜单名称',
  `mca` varchar(255) DEFAULT '' COMMENT '模块、控制器、方法',
  `ico` varchar(20) DEFAULT '' COMMENT 'font-awesome图标',
  `order_number` int(11) unsigned DEFAULT NULL COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='管理员导航表';

-- ----------------------------
-- Records of permission_admin_nav
-- ----------------------------
INSERT INTO `permission_admin_nav` VALUES ('1', '0', '管理权限表', 'Admin/Jurisdiction/config', '', null);
INSERT INTO `permission_admin_nav` VALUES ('2', '1', '角色管理', 'Admin/Jurisdiction/user_list', '', null);
INSERT INTO `permission_admin_nav` VALUES ('3', '1', '管理员管理', 'Admin/Jurisdiction/member_list', '', null);

-- ----------------------------
-- Table structure for permission_auth_group
-- ----------------------------
DROP TABLE IF EXISTS `permission_auth_group`;
CREATE TABLE `permission_auth_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `rules` char(80) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='管理员权限表';

-- ----------------------------
-- Records of permission_auth_group
-- ----------------------------
INSERT INTO `permission_auth_group` VALUES ('1', '超级管理员', '1', '1,2,4,6,7,8,10');

-- ----------------------------
-- Table structure for permission_auth_group_access
-- ----------------------------
DROP TABLE IF EXISTS `permission_auth_group_access`;
CREATE TABLE `permission_auth_group_access` (
  `uid` mediumint(8) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL,
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
  KEY `uid` (`uid`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='管理员组表';

-- ----------------------------
-- Records of permission_auth_group_access
-- ----------------------------
INSERT INTO `permission_auth_group_access` VALUES ('1', '1');

-- ----------------------------
-- Table structure for permission_auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `permission_auth_rule`;
CREATE TABLE `permission_auth_rule` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(80) NOT NULL DEFAULT '',
  `title` char(20) NOT NULL DEFAULT '',
  `type` tinyint(1) NOT NULL DEFAULT '1',
  `ismenu` tinyint(1) DEFAULT NULL,
  `pid` tinyint(1) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `condition` char(100) NOT NULL DEFAULT '',
  `order_number` int(11) unsigned DEFAULT NULL COMMENT '排序',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8 COMMENT='菜单表';

-- ----------------------------
-- Records of permission_auth_rule
-- ----------------------------
INSERT INTO `permission_auth_rule` VALUES ('1', 'Admin/Jurisdiction/index', '管理权限表', '1', '1', '0', '1', '', null);
INSERT INTO `permission_auth_rule` VALUES ('2', 'Admin/Jurisdiction/user_list', '角色管理', '1', '0', '1', '0', '', null);
INSERT INTO `permission_auth_rule` VALUES ('3', 'Admin/Jurisdiction/member_list', '管理员管理', '1', '1', '1', '1', '', null);
INSERT INTO `permission_auth_rule` VALUES ('4', 'Admin/Jurisdiction/user_edit', '角色编辑', '1', '0', '2', '1', '', null);
INSERT INTO `permission_auth_rule` VALUES ('5', 'Admin/Jurisdiction/editmember', '编辑管理员', '1', '0', '3', '1', '', null);
INSERT INTO `permission_auth_rule` VALUES ('6', 'Admin/Jurisdiction/adduser', '添加角色', '1', '0', '2', '1', '', null);
INSERT INTO `permission_auth_rule` VALUES ('7', 'Admin/Jurisdiction/user_status', '管理员状态', '1', '0', '2', '1', '', null);
INSERT INTO `permission_auth_rule` VALUES ('8', 'Admin/Jurisdiction/delete', '删除角色', '1', '0', '2', '1', '', null);
INSERT INTO `permission_auth_rule` VALUES ('9', 'Admin/Jurisdiction/delete_member', '删除管理员', '1', '0', '3', '1', '', null);
INSERT INTO `permission_auth_rule` VALUES ('10', 'Admin/Jurisdiction/addmember', '添加管理员', '1', '0', '2', '1', '', null);
INSERT INTO `permission_auth_rule` VALUES ('13', 'Admin/Menu/Index', '菜单管理', '1', '1', '0', '1', '', null);
INSERT INTO `permission_auth_rule` VALUES ('14', 'Admin/Menu/menulist', '栏目列表', '1', '1', '13', '1', '', null);
INSERT INTO `permission_auth_rule` VALUES ('27', 'Admin/Shop/', '商品管理', '1', '1', '0', '1', '', null);
INSERT INTO `permission_auth_rule` VALUES ('30', 'Admin/Shop/index', '商品列表', '1', '1', '27', '1', '', null);
INSERT INTO `permission_auth_rule` VALUES ('52', 'Admin/Subject/index', '科目分类', '1', '1', '50', '1', '', null);
INSERT INTO `permission_auth_rule` VALUES ('53', 'Admin/Program/', '系统设置', '1', '1', '0', '1', '', null);
INSERT INTO `permission_auth_rule` VALUES ('54', 'Admin/Program/index', '基本设置', '1', '1', '53', '1', '', null);

-- ----------------------------
-- Table structure for permission_program
-- ----------------------------
DROP TABLE IF EXISTS `permission_program`;
CREATE TABLE `permission_program` (
  `id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '表ID',
  `title` varchar(20) DEFAULT '' COMMENT '小程序名称',
  `appid` varchar(255) DEFAULT NULL COMMENT '小程序APPID',
  `secret` varchar(255) DEFAULT NULL COMMENT '小程序secret',
  `mchid` varchar(255) DEFAULT NULL COMMENT '微信商户ID',
  `key` varchar(255) DEFAULT NULL COMMENT '微信商户密钥',
  `notify_url` varchar(255) DEFAULT NULL COMMENT '微信支付回调地址',
  `logo` varchar(255) DEFAULT NULL COMMENT 'logo',
  `background_color` varchar(255) DEFAULT NULL COMMENT '背景颜色',
  `background_img` varchar(255) DEFAULT NULL COMMENT '背景图',
  `updated_time` int(11) DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='系统配置表';

-- ----------------------------
-- Records of permission_program
-- ----------------------------
INSERT INTO `permission_program` VALUES ('1', '帅大妈', null, null, null, null, null, '\\/20181102\\eccfd805a44e70d614814f74792aff53.gif', '#000000', '', '1541172168');

-- ----------------------------
-- Table structure for permission_shop
-- ----------------------------
DROP TABLE IF EXISTS `permission_shop`;
CREATE TABLE `permission_shop` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '菜名',
  `price` float(11,2) NOT NULL DEFAULT '0.00' COMMENT '专业年限',
  `status` varchar(5) NOT NULL DEFAULT '01',
  `created_time` int(11) NOT NULL,
  `updated_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=53 DEFAULT CHARSET=utf8 COMMENT='专业表';

-- ----------------------------
-- Records of permission_shop
-- ----------------------------
INSERT INTO `permission_shop` VALUES ('2', '空心菜', '3.00', '01', '1538215283', '1539071528');
INSERT INTO `permission_shop` VALUES ('4', '空心菜', '4.00', '01', '1538216713', '1539071524');
INSERT INTO `permission_shop` VALUES ('5', '空心菜', '4.00', '01', '1538216723', '1539071524');
INSERT INTO `permission_shop` VALUES ('6', '空心菜', '4.00', '01', '1538894578', '1539071523');
INSERT INTO `permission_shop` VALUES ('7', '空心菜', '4.00', '01', '1538215283', '1538215283');
INSERT INTO `permission_shop` VALUES ('8', '空心菜', '4.00', '01', '1538215283', '1538215283');
INSERT INTO `permission_shop` VALUES ('9', '空心菜', '4.00', '01', '1538215283', '1538215283');
INSERT INTO `permission_shop` VALUES ('10', '空心菜', '4.00', '01', '1538215283', '1538215283');
INSERT INTO `permission_shop` VALUES ('18', '红萝卜', '1.20', '01', '1541125459', '1541133041');
INSERT INTO `permission_shop` VALUES ('19', '红萝卜', '3.20', '01', '1541124987', '1541124987');
INSERT INTO `permission_shop` VALUES ('20', '红萝卜', '3.20', '01', '1541124987', '1541124987');
INSERT INTO `permission_shop` VALUES ('21', '红萝卜', '3.20', '01', '1541124987', '1541124987');
INSERT INTO `permission_shop` VALUES ('22', '红萝卜', '3.20', '01', '1541124987', '1541124987');
INSERT INTO `permission_shop` VALUES ('23', '红萝卜', '3.20', '01', '1541124987', '1541124987');
INSERT INTO `permission_shop` VALUES ('17', '白菜', '3.20', '01', '1541124987', '1541125690');
INSERT INTO `permission_shop` VALUES ('24', '红萝卜', '3.20', '01', '1541124987', '1541124987');
INSERT INTO `permission_shop` VALUES ('25', '红萝卜', '3.20', '01', '1541124987', '1541124987');
INSERT INTO `permission_shop` VALUES ('26', '红萝卜', '3.20', '01', '1541124987', '1541124987');
INSERT INTO `permission_shop` VALUES ('27', '红萝卜', '3.20', '01', '1541124987', '1541124987');
INSERT INTO `permission_shop` VALUES ('28', '红萝卜', '3.20', '01', '1541124987', '1541124987');
INSERT INTO `permission_shop` VALUES ('29', '红萝卜', '3.20', '01', '1541124987', '1541124987');
INSERT INTO `permission_shop` VALUES ('30', '红萝卜', '3.20', '01', '1541124987', '1541124987');
INSERT INTO `permission_shop` VALUES ('31', '红萝卜', '3.20', '01', '1541124987', '1541124987');
INSERT INTO `permission_shop` VALUES ('32', '红萝卜', '3.20', '01', '1541124987', '1541124987');
INSERT INTO `permission_shop` VALUES ('33', '红萝卜', '3.20', '01', '1541124987', '1541124987');
INSERT INTO `permission_shop` VALUES ('34', '红萝卜', '3.20', '01', '1541124987', '1541124987');
INSERT INTO `permission_shop` VALUES ('35', '白菜', '2.00', '01', '1541124987', '1541124987');
INSERT INTO `permission_shop` VALUES ('36', '白菜', '2.00', '01', '1541124987', '1541124987');
INSERT INTO `permission_shop` VALUES ('37', '白菜', '2.00', '01', '1541124987', '1541124987');
INSERT INTO `permission_shop` VALUES ('38', '白菜', '2.00', '01', '1541124987', '1541124987');
INSERT INTO `permission_shop` VALUES ('39', '白菜', '2.00', '01', '1541124987', '1541124987');
INSERT INTO `permission_shop` VALUES ('40', '白菜', '2.00', '01', '1541124987', '1541124987');
INSERT INTO `permission_shop` VALUES ('41', '白菜', '2.00', '01', '1541124987', '1541124987');
INSERT INTO `permission_shop` VALUES ('42', '白菜', '2.00', '01', '1541124987', '1541124987');
INSERT INTO `permission_shop` VALUES ('43', '白菜', '2.00', '01', '1541124987', '1541124987');
INSERT INTO `permission_shop` VALUES ('44', '白菜', '2.00', '01', '1541124987', '1541124987');
INSERT INTO `permission_shop` VALUES ('45', '白菜', '2.00', '01', '1541124987', '1541124987');
INSERT INTO `permission_shop` VALUES ('46', '白菜', '2.00', '01', '1541124987', '1541124987');
INSERT INTO `permission_shop` VALUES ('47', '白菜', '2.00', '01', '1541124987', '1541124987');
INSERT INTO `permission_shop` VALUES ('48', '白菜', '2.00', '01', '1541124987', '1541124987');
INSERT INTO `permission_shop` VALUES ('49', '白菜', '2.00', '01', '1541124987', '1541124987');
INSERT INTO `permission_shop` VALUES ('50', '白菜', '2.00', '01', '1541124987', '1541124987');
INSERT INTO `permission_shop` VALUES ('51', '白菜', '2.00', '01', '1541124987', '1541124987');
INSERT INTO `permission_shop` VALUES ('52', '白菜', '2.00', '01', '1541124987', '1541124987');
