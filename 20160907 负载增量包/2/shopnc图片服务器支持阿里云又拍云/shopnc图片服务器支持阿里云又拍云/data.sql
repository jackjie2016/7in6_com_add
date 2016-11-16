/*
 Navicat Premium Data Transfer

 Source Server         : mac
 Source Server Type    : MySQL
 Source Server Version : 50536
 Source Host           : localhost
 Source Database       : shoping

 Target Server Type    : MySQL
 Target Server Version : 50536
 File Encoding         : utf-8

 Date: 01/13/2015 15:25:26 PM
*/

-- ----------------------------
--  Records of `shopnc_setting`
-- ----------------------------



BEGIN;
INSERT INTO `club_setting` VALUES ('upload_service_bucket', '空间名'), ('upload_service_domain', '绑定域名'), ('upload_service_enabled', '0'), ('upload_service_host', '服务器地址'), ('upload_service_password', '密码'), ('upload_service_type', 'upyun'), ('upload_service_username', '用户');
COMMIT;
