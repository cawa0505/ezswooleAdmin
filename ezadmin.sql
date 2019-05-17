/*
 Navicat Premium Data Transfer

 Source Server         : local
 Source Server Type    : MySQL
 Source Server Version : 50724
 Source Host           : localhost:3306
 Source Schema         : ezadmin

 Target Server Type    : MySQL
 Target Server Version : 50724
 File Encoding         : 65001

 Date: 18/05/2019 00:01:04
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for rd_admin
-- ----------------------------
DROP TABLE IF EXISTS `rd_admin`;
CREATE TABLE `rd_admin`  (
  `admin_id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '用户id',
  `username` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '密码',
  `mobile` char(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '手机号',
  `qrcode_img` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '客服微信二维码图片',
  `salt` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '盐',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '状态:0正常 1禁用',
  `last_login_ip` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '最后登录ip',
  `last_login_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '最后登录时间',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间',
  `update_time` int(11) NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`admin_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '管理员表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of rd_admin
-- ----------------------------
INSERT INTO `rd_admin` VALUES (1, 'admin', 'c4ca4238a0b923820dcc509a6f75849b', '15249279779', '', 'admin', 0, '0.0.0.0', 1556567769, 1556414422, 1556589987);
INSERT INTO `rd_admin` VALUES (2, 'user', 'c4ca4238a0b923820dcc509a6f75849b', '15249279779', '', 'user', 0, '0.0.0.0', 1556590069, 1556535428, 1556589982);
INSERT INTO `rd_admin` VALUES (3, 'test', 'c4ca4238a0b923820dcc509a6f75849b', '13411111111', '', 'test', 0, '0.0.0.0', 1556590110, 1556589974, 1556589974);

-- ----------------------------
-- Table structure for rd_admin_role
-- ----------------------------
DROP TABLE IF EXISTS `rd_admin_role`;
CREATE TABLE `rd_admin_role`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `admin_id` int(10) UNSIGNED NOT NULL COMMENT '管理员id',
  `role_id` int(10) UNSIGNED NOT NULL COMMENT '角色id',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '管理员角色关系表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of rd_admin_role
-- ----------------------------
INSERT INTO `rd_admin_role` VALUES (1, 3, 3);
INSERT INTO `rd_admin_role` VALUES (2, 2, 2);
INSERT INTO `rd_admin_role` VALUES (3, 1, 1);

-- ----------------------------
-- Table structure for rd_menu
-- ----------------------------
DROP TABLE IF EXISTS `rd_menu`;
CREATE TABLE `rd_menu`  (
  `menu_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `menu_name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '菜单名',
  `menu_url` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '地址',
  `menu_identify` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '标识',
  `parent_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '上级id',
  `menu_icon` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '图标',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '状态 0 正常 1 删除',
  `create_time` int(10) NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) NULL DEFAULT 0 COMMENT '修改时间',
  PRIMARY KEY (`menu_id`) USING BTREE,
  INDEX `menu_name`(`menu_name`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '菜单表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of rd_menu
-- ----------------------------
INSERT INTO `rd_menu` VALUES (1, '系统管理', '/', 'system', 0, 'layui-icon layui-icon-set', 0, 0, 0);
INSERT INTO `rd_menu` VALUES (2, '用户管理', '/backend/admin/index', '', 1, '', 0, 0, 0);
INSERT INTO `rd_menu` VALUES (3, '角色管理', '/backend/role/index', '', 1, '', 0, 0, 0);
INSERT INTO `rd_menu` VALUES (4, '权限管理', '/backend/permission/index', '', 1, '', 0, 0, 0);
INSERT INTO `rd_menu` VALUES (5, '菜单管理', '/backend/menu/index', '', 1, '', 0, 0, 0);

-- ----------------------------
-- Table structure for rd_permission
-- ----------------------------
DROP TABLE IF EXISTS `rd_permission`;
CREATE TABLE `rd_permission`  (
  `permission_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '权限名称',
  `url` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '访问url地址',
  `parent_id` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '上级id',
  `status` tinyint(1) UNSIGNED NULL DEFAULT 0 COMMENT '是否可用 0：正常，1 禁用',
  PRIMARY KEY (`permission_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 31 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '管理员权限表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of rd_permission
-- ----------------------------
INSERT INTO `rd_permission` VALUES (1, '系统管理', '/', 0, 0);
INSERT INTO `rd_permission` VALUES (2, '用户管理', '/backend/admin/index', 1, 0);
INSERT INTO `rd_permission` VALUES (3, '用户列表', '/backend/admin/data', 1, 0);
INSERT INTO `rd_permission` VALUES (4, '用户添加', '/backend/admin/create', 1, 0);
INSERT INTO `rd_permission` VALUES (5, '用户保存', '/backend/admin/store', 1, 0);
INSERT INTO `rd_permission` VALUES (6, '用户编辑', '/backend/admin/edit', 1, 0);
INSERT INTO `rd_permission` VALUES (7, '用户修改', '/backend/admin/update', 1, 0);
INSERT INTO `rd_permission` VALUES (8, '用户删除', '/backend/admin/delete', 1, 0);
INSERT INTO `rd_permission` VALUES (9, '用户详情', '/backend/admin/info', 1, 0);
INSERT INTO `rd_permission` VALUES (10, '角色管理', '/backend/role/index', 1, 0);
INSERT INTO `rd_permission` VALUES (11, '角色列表', '/backend/role/data', 1, 0);
INSERT INTO `rd_permission` VALUES (12, '角色添加', '/backend/role/create', 1, 0);
INSERT INTO `rd_permission` VALUES (13, '角色保存', '/backend/role/store', 1, 0);
INSERT INTO `rd_permission` VALUES (14, '角色编辑', '/backend/role/edit', 1, 0);
INSERT INTO `rd_permission` VALUES (15, '角色修改', '/backend/role/update', 1, 0);
INSERT INTO `rd_permission` VALUES (16, '角色删除', '/backend/role/delete', 1, 0);
INSERT INTO `rd_permission` VALUES (17, '权限管理', '/backend/permission/index', 1, 0);
INSERT INTO `rd_permission` VALUES (18, '权限列表', '/backend/permission/data', 1, 0);
INSERT INTO `rd_permission` VALUES (19, '权限添加', '/backend/permission/create', 1, 0);
INSERT INTO `rd_permission` VALUES (20, '权限保存', '/backend/permission/store', 1, 0);
INSERT INTO `rd_permission` VALUES (21, '权限编辑', '/backend/permission/edit', 1, 0);
INSERT INTO `rd_permission` VALUES (22, '权限修改', '/backend/permission/update', 1, 0);
INSERT INTO `rd_permission` VALUES (23, '权限删除', '/backend/permission/delete', 1, 0);
INSERT INTO `rd_permission` VALUES (24, '菜单管理', '/backend/menu/index', 1, 0);
INSERT INTO `rd_permission` VALUES (25, '菜单列表', '/backend/menu/data', 1, 0);
INSERT INTO `rd_permission` VALUES (26, '菜单添加', '/backend/menu/create', 1, 0);
INSERT INTO `rd_permission` VALUES (27, '菜单保存', '/backend/menu/store', 1, 0);
INSERT INTO `rd_permission` VALUES (28, '菜单编辑', '/backend/menu/edit', 1, 0);
INSERT INTO `rd_permission` VALUES (29, '菜单修改', '/backend/menu/update', 1, 0);
INSERT INTO `rd_permission` VALUES (30, '菜单删除', '/backend/menu/delete', 1, 0);

-- ----------------------------
-- Table structure for rd_role
-- ----------------------------
DROP TABLE IF EXISTS `rd_role`;
CREATE TABLE `rd_role`  (
  `role_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '角色ID',
  `role_name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '角色名称',
  `role_desc` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '角色描述',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '状态0 可用 1 禁用',
  PRIMARY KEY (`role_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '管理员角色表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of rd_role
-- ----------------------------
INSERT INTO `rd_role` VALUES (1, '超超级管理员', '超超级管理员', 0);
INSERT INTO `rd_role` VALUES (2, '用户管理', '用户管理', 0);
INSERT INTO `rd_role` VALUES (3, '测试', '测试', 0);

-- ----------------------------
-- Table structure for rd_role_permission
-- ----------------------------
DROP TABLE IF EXISTS `rd_role_permission`;
CREATE TABLE `rd_role_permission`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `role_id` int(10) UNSIGNED NOT NULL COMMENT '角色id',
  `permission_id` int(10) UNSIGNED NOT NULL COMMENT '权限id',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 40 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '角色权限关系表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of rd_role_permission
-- ----------------------------
INSERT INTO `rd_role_permission` VALUES (1, 1, 1);
INSERT INTO `rd_role_permission` VALUES (2, 1, 2);
INSERT INTO `rd_role_permission` VALUES (3, 1, 3);
INSERT INTO `rd_role_permission` VALUES (4, 1, 4);
INSERT INTO `rd_role_permission` VALUES (5, 1, 5);
INSERT INTO `rd_role_permission` VALUES (6, 1, 6);
INSERT INTO `rd_role_permission` VALUES (7, 1, 7);
INSERT INTO `rd_role_permission` VALUES (8, 1, 8);
INSERT INTO `rd_role_permission` VALUES (9, 1, 9);
INSERT INTO `rd_role_permission` VALUES (10, 1, 10);
INSERT INTO `rd_role_permission` VALUES (11, 1, 11);
INSERT INTO `rd_role_permission` VALUES (12, 1, 12);
INSERT INTO `rd_role_permission` VALUES (13, 1, 13);
INSERT INTO `rd_role_permission` VALUES (14, 1, 14);
INSERT INTO `rd_role_permission` VALUES (15, 1, 15);
INSERT INTO `rd_role_permission` VALUES (16, 1, 16);
INSERT INTO `rd_role_permission` VALUES (17, 1, 17);
INSERT INTO `rd_role_permission` VALUES (18, 1, 18);
INSERT INTO `rd_role_permission` VALUES (19, 1, 19);
INSERT INTO `rd_role_permission` VALUES (20, 1, 20);
INSERT INTO `rd_role_permission` VALUES (21, 1, 21);
INSERT INTO `rd_role_permission` VALUES (22, 1, 22);
INSERT INTO `rd_role_permission` VALUES (23, 1, 23);
INSERT INTO `rd_role_permission` VALUES (24, 1, 24);
INSERT INTO `rd_role_permission` VALUES (25, 1, 25);
INSERT INTO `rd_role_permission` VALUES (26, 1, 26);
INSERT INTO `rd_role_permission` VALUES (27, 1, 27);
INSERT INTO `rd_role_permission` VALUES (28, 1, 28);
INSERT INTO `rd_role_permission` VALUES (29, 1, 29);
INSERT INTO `rd_role_permission` VALUES (30, 1, 30);
INSERT INTO `rd_role_permission` VALUES (31, 2, 1);
INSERT INTO `rd_role_permission` VALUES (32, 2, 2);
INSERT INTO `rd_role_permission` VALUES (33, 2, 3);
INSERT INTO `rd_role_permission` VALUES (34, 2, 4);
INSERT INTO `rd_role_permission` VALUES (35, 2, 5);
INSERT INTO `rd_role_permission` VALUES (36, 2, 6);
INSERT INTO `rd_role_permission` VALUES (37, 2, 7);
INSERT INTO `rd_role_permission` VALUES (38, 2, 8);
INSERT INTO `rd_role_permission` VALUES (39, 2, 9);

SET FOREIGN_KEY_CHECKS = 1;
