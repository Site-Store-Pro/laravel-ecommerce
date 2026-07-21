/*
 Navicat Premium Dump SQL

 Source Server         : NEW AWS MARIA
 Source Server Type    : MySQL
 Source Server Version : 110410 (11.4.10-MariaDB)
 Source Host           : 18.224.160.163:3306
 Source Schema         : storedemo1

 Target Server Type    : MySQL
 Target Server Version : 110410 (11.4.10-MariaDB)
 File Encoding         : 65001

 Date: 18/07/2026 21:15:47
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for sitestorepro_manufacturers
-- ----------------------------
DROP TABLE IF EXISTS `sitestorepro_manufacturers`;
CREATE TABLE `sitestorepro_manufacturers`  (
  `ManufacturerID` int NOT NULL AUTO_INCREMENT,
  `ManufacturerName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `ShowBrandURL` int NULL DEFAULT 0,
  `ManufacturerURL` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `Description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `IncludeCatHeader` int NULL DEFAULT 1,
  `CatHeader` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `IncludeCatFooter` int NULL DEFAULT 1,
  `CatFooter` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `CategoryImage` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `IncludeInMenu` int NULL DEFAULT 1,
  `MenuOrdering` double NULL DEFAULT 1,
  `StyleDiscount` int NULL DEFAULT 0,
  `Active` int NULL DEFAULT 0,
  `StoreID` int NULL DEFAULT 0,
  `METAKeywords` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `METADescription` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `ShowBannerImage` int NULL DEFAULT 1,
  `BannerImage` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `BannerImageALT` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `ShowDirectoryDisplay` int NULL DEFAULT 1,
  `DirectoryText` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `ShowDirectoryImage` int NULL DEFAULT 1,
  `MenuLinkText` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `MenuTitleText` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `ShowDrillDowns` int NULL DEFAULT 1,
  `DrillDownsTitle` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `DrillDownsDescription` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_text1` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_text2` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_text3` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_vc1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sitestorepro_upgrade_vc2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sitestorepro_upgrade_num1` int NULL DEFAULT NULL,
  `sitestorepro_upgrade_num2` int NULL DEFAULT NULL,
  `sitestorepro_upgrade_num3` int NULL DEFAULT NULL,
  `sitestorepro_upgrade_num4` int NULL DEFAULT NULL,
  `sitestorepro_upgrade_num5` int NULL DEFAULT NULL,
  `sitestorepro_upgrade_fl1` double NULL DEFAULT NULL,
  `sitestorepro_upgrade_fl2` double NULL DEFAULT NULL,
  `sitestorepro_upgrade_fl3` double NULL DEFAULT NULL,
  `sitestorepro_upgrade_ln1` bigint NULL DEFAULT NULL,
  `sitestorepro_upgrade_ln2` bigint NULL DEFAULT NULL,
  `sitestorepro_upgrade_date1` datetime NULL DEFAULT NULL,
  `sitestorepro_upgrade_date2` datetime NULL DEFAULT NULL,
  `sitestorepro_upgrade_date3` datetime NULL DEFAULT NULL,
  `sitestorepro_upgrade_date4` datetime NULL DEFAULT NULL,
  `sitestorepro_upgrade_image1` longblob NULL,
  PRIMARY KEY (`ManufacturerID`) USING BTREE,
  INDEX `StoreIDbrand`(`StoreID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sitestorepro_manufacturers
-- ----------------------------
INSERT INTO `sitestorepro_manufacturers` VALUES (1, 'Prestige Design', 0, '', '', 1, 'This sample brand shows how the store administrator can create a custom header display for a specific&nbsp;manufacturer.&nbsp; The Header HTML is managed via a WYSIWYG editor and a banner image can be uploaded for each invididual brand, category, subcategory and collection directly through the included administration system. The admin can also allow the customer&nbsp;to narrow their search results by \"drilling-down\"&nbsp;to the category level(s).<br><br>All the features and content displayed on this page are managed through the web-based admin system and can be enabled/disabled with point-and-click simplicity.', 0, 'This is a sample footer for the prestige brand name.', 'prestige-brand.webp', 1, 0, 0, 1, 1, 'jewerly, custom, design, wholesale, rings, diamonds, necklaces, pearls, designer', '', 0, 'placeholder.png', '', 0, 'Prestige', 1, 'Prestige Design', 'Prestige Design Items', 1, '', 'Use the links below to refine your shopping criteria or simply browse the items displayed in the results section below.', NULL, NULL, NULL, NULL, 'prestige-design', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_manufacturers` VALUES (2, 'DeMarco', 0, '', '', 0, '', 0, '', 'demarco-brand.webp', 1, 3, 0, 1, 1, 'sample, test, demo, shopping cart, online, store', 'This is a sample company description meta tag.', 0, 'placeholder.png', 'DeMarco Custom Apparel', 0, 'DeMarco', 1, 'DeMarco', 'DeMarco Items', 0, 'Narrow Your Search....', '', NULL, NULL, NULL, NULL, 'demarco', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_manufacturers` VALUES (3, 'Old Heritage', 0, '', '', 0, '', 0, '', 'old-heritage-brand.webp', 1, 2, NULL, 1, 1, '', '', 0, 'placeholder.png', '', 0, 'Old Heritage', 1, 'Old Heritage', 'Old Heritage', 0, 'Old Heritage', '', NULL, NULL, NULL, NULL, 'old-heritage', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_manufacturers` VALUES (4, 'Bella Luna', 0, '', '', 0, '', 0, '', 'bella-luna-brand.webp', 1, 4, NULL, 1, 1, '', '', 0, 'placeholder.png', '', 0, 'Bella Luna', 1, 'Bella Luna', 'Bella Luna', 0, 'Bella Luna', '', NULL, NULL, NULL, NULL, 'bella-luna', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_manufacturers` VALUES (5, 'Excelsior', 0, '', '', 0, '', 0, '', 'excelsior-brand.webp', 1, 1, NULL, 1, 1, '', '', 0, 'placeholder.png', '', 0, 'Excelsior', 1, 'Excelsior', 'Excelsior', 0, '', '', NULL, NULL, NULL, NULL, 'excelsior-fine-gifts', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- ----------------------------
-- Table structure for sitestorepro_prod_colors
-- ----------------------------
DROP TABLE IF EXISTS `sitestorepro_prod_colors`;
CREATE TABLE `sitestorepro_prod_colors`  (
  `ColorID` int NOT NULL AUTO_INCREMENT,
  `ColorName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `SearchMenuLabel` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `SearchResultsTitle` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `IncludeInMenu` int NULL DEFAULT 1,
  `ColorImage` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `ColorCode` char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `ColorFee` double NULL DEFAULT 0,
  `MenuOrdering` double NULL DEFAULT 0,
  `IncludeOnDetailsPage` int NULL DEFAULT 0,
  `StoreID` int NULL DEFAULT 1,
  `sitestorepro_upgrade_text1` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_text2` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_text3` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_vc1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sitestorepro_upgrade_vc2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sitestorepro_upgrade_num1` int NULL DEFAULT NULL,
  `sitestorepro_upgrade_num2` int NULL DEFAULT NULL,
  `sitestorepro_upgrade_num3` int NULL DEFAULT NULL,
  `sitestorepro_upgrade_num4` int NULL DEFAULT NULL,
  `sitestorepro_upgrade_num5` int NULL DEFAULT NULL,
  `sitestorepro_upgrade_fl1` double NULL DEFAULT NULL,
  `sitestorepro_upgrade_fl2` double NULL DEFAULT NULL,
  `sitestorepro_upgrade_fl3` double NULL DEFAULT NULL,
  `sitestorepro_upgrade_ln1` bigint NULL DEFAULT NULL,
  `sitestorepro_upgrade_ln2` bigint NULL DEFAULT NULL,
  `sitestorepro_upgrade_date1` datetime NULL DEFAULT NULL,
  `sitestorepro_upgrade_date2` datetime NULL DEFAULT NULL,
  `sitestorepro_upgrade_date3` datetime NULL DEFAULT NULL,
  `sitestorepro_upgrade_date4` datetime NULL DEFAULT NULL,
  `sitestorepro_upgrade_image1` longblob NULL,
  PRIMARY KEY (`ColorID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 62 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sitestorepro_prod_colors
-- ----------------------------
INSERT INTO `sitestorepro_prod_colors` VALUES (52, 'White', 'White', 'White', 1, NULL, NULL, 0, 8, 0, 1, NULL, 'white.jpg', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_colors` VALUES (53, 'Black', 'Black', 'Black', 1, '', NULL, 0, 1, 0, 1, NULL, 'black.jpg', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_colors` VALUES (54, 'Burgundy', 'Burgundy', ' Burgundy', 1, '', NULL, 0, 3, 0, 1, NULL, 'burgundy.jpg', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_colors` VALUES (55, 'Brown', 'Brown', 'Brown', 1, NULL, NULL, 0, 2, 0, 1, 'Brown', 'brown.jpg', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_colors` VALUES (56, 'Orange', 'Orange', 'Orange', 1, NULL, NULL, 0, 6, 0, 1, 'Orange', '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_colors` VALUES (57, 'Green', 'Green', 'Green', 0, NULL, NULL, 0, 4, 0, 1, 'Green', '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_colors` VALUES (58, 'Navy Blue', 'Navy Blue', 'Navy Blue', 1, NULL, NULL, 0, 6, 0, 1, 'Navy Blue', '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_colors` VALUES (59, 'Grey', 'Grey', 'Grey', 1, NULL, NULL, 0, 5, 0, 1, 'Grey', '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_colors` VALUES (60, 'Royal Blue', 'Royal Blue', 'Royal Blue', 1, NULL, NULL, 0, 6.5, 0, 1, 'Royal Blue', '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_colors` VALUES (61, 'Red', 'Red', 'Red', 1, NULL, NULL, 0, 4, 0, 1, 'Red', 'red.jpg', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- ----------------------------
-- Table structure for sitestorepro_prod_colors_assoc
-- ----------------------------
DROP TABLE IF EXISTS `sitestorepro_prod_colors_assoc`;
CREATE TABLE `sitestorepro_prod_colors_assoc`  (
  `colorassocid` int NOT NULL AUTO_INCREMENT,
  `colorid` int NULL DEFAULT NULL,
  `prodid` int NULL DEFAULT NULL,
  `menuordering` double NULL DEFAULT NULL,
  `use_custom` int NULL DEFAULT 0,
  `custom_retail_label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `custom_retail_fee` double NULL DEFAULT 0,
  `custom_wholesale_label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `custom_wholesale_fee` double NULL DEFAULT 0,
  `custom_sales_tax` int NULL DEFAULT 0,
  `custom_weight` double NULL DEFAULT 0,
  PRIMARY KEY (`colorassocid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 43 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sitestorepro_prod_colors_assoc
-- ----------------------------
INSERT INTO `sitestorepro_prod_colors_assoc` VALUES (4, 53, 1000015, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_colors_assoc` VALUES (5, 54, 1000015, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_colors_assoc` VALUES (6, 52, 1000015, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_colors_assoc` VALUES (7, 55, 1000020, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_colors_assoc` VALUES (8, 53, 1000020, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_colors_assoc` VALUES (16, 55, 1000025, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_colors_assoc` VALUES (17, 57, 1000025, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_colors_assoc` VALUES (18, 59, 1000025, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_colors_assoc` VALUES (19, 56, 1000025, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_colors_assoc` VALUES (20, 58, 1000025, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_colors_assoc` VALUES (21, 60, 1000025, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_colors_assoc` VALUES (22, 55, 1000026, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_colors_assoc` VALUES (23, 57, 1000026, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_colors_assoc` VALUES (24, 59, 1000026, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_colors_assoc` VALUES (25, 56, 1000026, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_colors_assoc` VALUES (26, 58, 1000026, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_colors_assoc` VALUES (27, 60, 1000026, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_colors_assoc` VALUES (31, 53, 1000027, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_colors_assoc` VALUES (32, 61, 1000027, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_colors_assoc` VALUES (33, 52, 1000027, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_colors_assoc` VALUES (34, 53, 1000029, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_colors_assoc` VALUES (35, 52, 1000029, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_colors_assoc` VALUES (36, NULL, 1000032, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_colors_assoc` VALUES (37, NULL, 1000033, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_colors_assoc` VALUES (38, NULL, 1000034, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_colors_assoc` VALUES (39, NULL, 1000035, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_colors_assoc` VALUES (40, NULL, 1000036, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_colors_assoc` VALUES (41, NULL, 1000037, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_colors_assoc` VALUES (42, NULL, 1000038, NULL, 0, NULL, 0, NULL, 0, 0, 0);

-- ----------------------------
-- Table structure for sitestorepro_prod_crosssell
-- ----------------------------
DROP TABLE IF EXISTS `sitestorepro_prod_crosssell`;
CREATE TABLE `sitestorepro_prod_crosssell`  (
  `CrossSellID` int NOT NULL AUTO_INCREMENT,
  `ProdID` int NULL DEFAULT 0,
  `CrossSellProdID` int NULL DEFAULT 0,
  `OrderingValue` double NULL DEFAULT NULL,
  `DisplayType` int NULL DEFAULT 0,
  PRIMARY KEY (`CrossSellID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 95 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sitestorepro_prod_crosssell
-- ----------------------------
INSERT INTO `sitestorepro_prod_crosssell` VALUES (52, 1000001, 1000012, 1, 1);
INSERT INTO `sitestorepro_prod_crosssell` VALUES (53, 1000001, 1000013, 2, 1);
INSERT INTO `sitestorepro_prod_crosssell` VALUES (54, 1000001, 1000007, 3, 1);
INSERT INTO `sitestorepro_prod_crosssell` VALUES (55, 1000001, 1000009, 4, 1);
INSERT INTO `sitestorepro_prod_crosssell` VALUES (56, 1000002, 1000005, 1, 1);
INSERT INTO `sitestorepro_prod_crosssell` VALUES (57, 1000002, 1000006, 2, 1);
INSERT INTO `sitestorepro_prod_crosssell` VALUES (58, 1000002, 1000013, 3, 1);
INSERT INTO `sitestorepro_prod_crosssell` VALUES (59, 1000002, 1000009, 4, 1);
INSERT INTO `sitestorepro_prod_crosssell` VALUES (60, 1000003, 1000002, 1, 1);
INSERT INTO `sitestorepro_prod_crosssell` VALUES (61, 1000003, 1000001, 2, 1);
INSERT INTO `sitestorepro_prod_crosssell` VALUES (62, 1000004, 1000002, 1, 1);
INSERT INTO `sitestorepro_prod_crosssell` VALUES (63, 1000006, 1000005, 1, 1);
INSERT INTO `sitestorepro_prod_crosssell` VALUES (64, 1000006, 1000004, 2, 1);
INSERT INTO `sitestorepro_prod_crosssell` VALUES (65, 1000006, 1000002, 3, 1);
INSERT INTO `sitestorepro_prod_crosssell` VALUES (66, 1000006, 1000003, 4, 1);
INSERT INTO `sitestorepro_prod_crosssell` VALUES (67, 1000008, 1000007, 1, 1);
INSERT INTO `sitestorepro_prod_crosssell` VALUES (68, 1000008, 1000001, 2, 1);
INSERT INTO `sitestorepro_prod_crosssell` VALUES (69, 1000009, 1000001, 1, 1);
INSERT INTO `sitestorepro_prod_crosssell` VALUES (70, 1000009, 1000007, 2, 1);
INSERT INTO `sitestorepro_prod_crosssell` VALUES (71, 1000009, 1000008, 3, 1);
INSERT INTO `sitestorepro_prod_crosssell` VALUES (73, 1000011, 1000009, 2, 1);
INSERT INTO `sitestorepro_prod_crosssell` VALUES (74, 1000011, 1000001, 3, 1);
INSERT INTO `sitestorepro_prod_crosssell` VALUES (75, 1000011, 1000007, 4, 1);
INSERT INTO `sitestorepro_prod_crosssell` VALUES (76, 1000012, 1000009, 1, 1);
INSERT INTO `sitestorepro_prod_crosssell` VALUES (77, 1000012, 1000001, 2, 1);
INSERT INTO `sitestorepro_prod_crosssell` VALUES (78, 1000012, 1000007, 1, 2);
INSERT INTO `sitestorepro_prod_crosssell` VALUES (79, 1000012, 1000010, 2, 2);
INSERT INTO `sitestorepro_prod_crosssell` VALUES (80, 1000013, 1000007, 1, 1);
INSERT INTO `sitestorepro_prod_crosssell` VALUES (81, 1000013, 1000008, 2, 1);
INSERT INTO `sitestorepro_prod_crosssell` VALUES (82, 1000013, 1000012, 3, 1);
INSERT INTO `sitestorepro_prod_crosssell` VALUES (84, 1000013, 1000010, 1, 2);
INSERT INTO `sitestorepro_prod_crosssell` VALUES (85, 1000016, 1000017, 1, 1);
INSERT INTO `sitestorepro_prod_crosssell` VALUES (86, 1000017, 1000016, 1, 1);
INSERT INTO `sitestorepro_prod_crosssell` VALUES (87, 1000025, 1000015, 1, 1);
INSERT INTO `sitestorepro_prod_crosssell` VALUES (88, 1000015, 1000025, 1, 1);
INSERT INTO `sitestorepro_prod_crosssell` VALUES (89, 1000015, 1000025, 1, 2);
INSERT INTO `sitestorepro_prod_crosssell` VALUES (90, 1000016, 1000014, 0, 1);
INSERT INTO `sitestorepro_prod_crosssell` VALUES (91, 1000032, 1000036, 0, 1);
INSERT INTO `sitestorepro_prod_crosssell` VALUES (92, 1000032, 1000036, 0, 2);
INSERT INTO `sitestorepro_prod_crosssell` VALUES (93, 1000036, 1000032, 0, 1);
INSERT INTO `sitestorepro_prod_crosssell` VALUES (94, 1000036, 1000032, 0, 2);

-- ----------------------------
-- Table structure for sitestorepro_prod_download_log
-- ----------------------------
DROP TABLE IF EXISTS `sitestorepro_prod_download_log`;
CREATE TABLE `sitestorepro_prod_download_log`  (
  `DownloadLogID` int NOT NULL AUTO_INCREMENT,
  `ProdID` int NULL DEFAULT NULL,
  `UserID` int NULL DEFAULT NULL,
  `DownloadDate` datetime NULL DEFAULT NULL,
  `UserIPAddress` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`DownloadLogID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 610 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sitestorepro_prod_download_log
-- ----------------------------
INSERT INTO `sitestorepro_prod_download_log` VALUES (1, 1000014, 309, '2024-12-27 03:02:16', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (2, 1000016, 879, '2026-03-06 01:50:08', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (3, 1000017, 334, '2025-01-19 02:32:09', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (4, 1000016, 754, '2026-06-05 08:27:23', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (5, 1000017, 356, '2025-02-02 06:31:50', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (6, 1000014, 756, '2026-01-01 07:30:49', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (7, 1000014, 12, '2024-06-27 07:14:32', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (8, 1000014, 920, '2026-05-07 05:13:56', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (9, 1000014, 67, '2026-07-14 11:49:30', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (10, 1000014, 197, '2024-10-16 00:53:27', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (11, 1000016, 178, '2024-10-02 04:46:20', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (12, 1000014, 813, '2026-02-10 05:31:40', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (13, 1000014, 952, '2026-05-25 04:42:03', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (14, 1000016, 455, '2026-03-14 03:57:44', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (15, 1000014, 732, '2025-11-30 03:00:14', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (16, 1000014, 113, '2025-11-07 02:56:03', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (17, 1000014, 59, '2024-08-02 05:03:30', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (18, 1000016, 827, '2026-03-03 05:03:41', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (19, 1000016, 460, '2025-04-30 08:13:37', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (20, 1000014, 282, '2024-12-09 08:35:16', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (21, 1000017, 436, '2025-04-19 06:29:32', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (22, 1000014, 983, '2026-07-04 02:03:14', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (23, 1000014, 719, '2025-12-10 04:58:38', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (24, 1000017, 456, '2025-06-26 05:50:27', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (25, 1000014, 489, '2025-05-18 02:21:53', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (26, 1000017, 110, '2024-08-24 03:52:34', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (27, 1000014, 631, '2025-09-05 02:11:02', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (28, 1000014, 893, '2026-04-10 04:51:10', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (29, 1000014, 422, '2025-03-21 07:22:59', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (30, 1000016, 378, '2025-04-25 01:59:49', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (31, 1000014, 44, '2024-10-22 06:46:25', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (32, 1000014, 811, '2026-02-14 03:15:55', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (33, 1000014, 123, '2024-08-19 08:01:00', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (34, 1000014, 171, '2024-09-28 01:41:14', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (35, 1000014, 501, '2025-06-13 02:20:32', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (36, 1000014, 433, '2025-04-14 05:54:42', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (37, 1000014, 204, '2024-10-15 02:53:14', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (38, 1000016, 765, '2026-01-03 05:39:58', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (39, 1000014, 518, '2025-06-18 06:16:16', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (40, 1000014, 1004, '2026-07-14 08:48:05', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (41, 1000014, 767, '2026-01-14 01:58:22', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (42, 1000016, 531, '2025-07-04 06:41:10', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (43, 1000016, 721, '2025-11-25 04:41:36', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (44, 1000014, 747, '2025-12-31 00:33:20', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (45, 1000014, 937, '2026-05-23 01:49:34', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (46, 1000014, 580, '2025-07-31 01:33:38', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (47, 1000014, 16, '2024-06-27 01:25:10', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (48, 1000014, 627, '2025-08-30 08:27:46', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (49, 1000014, 130, '2024-11-22 06:49:55', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (50, 1000014, 762, '2025-12-31 05:11:25', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (51, 1000016, 662, '2025-10-04 05:09:07', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (52, 1000014, 835, '2026-03-11 00:39:40', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (53, 1000014, 226, '2024-12-25 01:30:25', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (54, 1000014, 322, '2025-01-03 08:56:35', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (55, 1000014, 388, '2025-09-02 03:15:11', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (56, 1000016, 14, '2024-07-14 05:12:17', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (57, 1000014, 690, '2025-11-05 08:25:17', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (58, 1000014, 97, '2025-05-22 05:07:32', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (59, 1000016, 678, '2025-10-22 03:31:39', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (60, 1000017, 911, '2026-04-17 01:29:13', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (61, 1000016, 319, '2025-12-03 03:08:46', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (62, 1000014, 668, '2025-10-09 06:05:41', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (63, 1000014, 276, '2025-03-14 05:38:40', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (64, 1000014, 1022, '2026-07-14 01:34:28', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (65, 1000014, 847, '2026-03-07 08:18:41', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (66, 1000014, 440, '2025-04-19 04:55:16', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (67, 1000016, 290, '2024-12-24 07:03:38', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (68, 1000014, 862, '2026-03-22 08:22:00', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (69, 1000014, 93, '2024-12-24 02:54:45', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (70, 1000014, 750, '2025-12-28 03:44:05', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (71, 1000014, 18, '2024-07-05 03:39:48', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (72, 1000014, 997, '2026-07-03 01:27:05', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (73, 1000014, 532, '2025-07-06 04:05:22', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (74, 1000014, 35, '2024-07-24 08:42:11', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (75, 1000014, 49, '2024-07-13 05:35:29', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (76, 1000014, 142, '2024-09-13 07:16:49', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (77, 1000014, 62, '2024-07-20 07:48:33', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (78, 1000016, 931, '2026-04-30 06:15:43', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (79, 1000017, 687, '2025-11-10 02:55:18', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (80, 1000016, 38, '2024-07-11 03:50:13', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (81, 1000014, 242, '2025-12-25 01:44:06', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (82, 1000014, 868, '2026-03-25 06:27:12', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (83, 1000017, 372, '2025-08-16 08:22:32', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (84, 1000014, 90, '2024-08-06 00:44:12', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (85, 1000014, 34, '2024-07-07 07:21:30', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (86, 1000014, 332, '2025-01-14 06:04:15', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (87, 1000014, 313, '2025-01-15 07:23:41', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (88, 1000014, 317, '2025-12-27 02:38:24', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (89, 1000014, 47, '2024-10-04 02:59:50', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (90, 1000014, 739, '2025-12-27 07:28:44', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (91, 1000016, 1001, '2026-07-09 07:38:45', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (92, 1000016, 223, '2024-10-19 03:34:01', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (93, 1000014, 98, '2024-08-15 03:21:28', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (94, 1000017, 11, '2024-11-21 00:26:37', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (95, 1000016, 192, '2024-10-17 02:17:54', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (96, 1000016, 777, '2026-01-10 07:39:28', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (97, 1000014, 17, '2024-07-16 04:09:37', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (98, 1000014, 1016, '2026-07-11 01:52:47', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (99, 1000014, 76, '2024-08-04 05:46:59', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (100, 1000014, 169, '2024-09-14 03:07:25', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (101, 1000014, 346, '2025-01-28 08:16:45', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (102, 1000014, 271, '2024-12-10 03:33:38', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (103, 1000014, 856, '2026-03-15 02:56:33', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (104, 1000014, 124, '2024-08-20 08:49:28', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (105, 1000014, 155, '2024-09-23 06:27:46', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (106, 1000014, 197, '2025-08-09 03:51:18', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (107, 1000014, 182, '2024-10-06 06:04:31', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (108, 1000014, 545, '2025-09-08 08:57:29', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (109, 1000014, 889, '2026-03-28 00:28:05', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (110, 1000014, 701, '2025-11-08 08:18:54', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (111, 1000016, 898, '2026-04-09 02:42:12', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (112, 1000016, 933, '2026-05-11 08:27:05', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (113, 1000014, 466, '2025-06-18 02:57:28', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (114, 1000014, 146, '2024-09-08 03:11:55', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (115, 1000014, 264, '2024-12-07 07:20:19', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (116, 1000014, 467, '2025-05-09 07:23:05', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (117, 1000014, 683, '2026-06-07 07:36:19', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (118, 1000016, 802, '2026-02-05 02:54:34', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (119, 1000014, 169, '2025-05-31 08:24:05', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (120, 1000014, 875, '2026-04-03 07:04:59', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (121, 1000014, 59, '2024-07-18 02:34:05', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (122, 1000014, 492, '2025-05-28 00:36:17', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (123, 1000014, 390, '2025-03-13 07:03:45', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (124, 1000014, 820, '2026-02-23 07:30:44', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (125, 1000014, 385, '2025-03-08 04:34:59', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (126, 1000014, 833, '2026-02-28 01:17:49', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (127, 1000014, 267, '2025-01-07 00:40:31', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (128, 1000014, 278, '2025-07-25 08:08:59', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (129, 1000016, 319, '2026-07-10 07:52:12', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (130, 1000017, 229, '2024-10-29 03:24:22', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (131, 1000014, 597, '2025-08-14 04:55:47', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (132, 1000014, 758, '2026-01-08 07:10:39', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (133, 1000014, 620, '2025-08-23 05:00:28', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (134, 1000017, 647, '2025-09-11 02:44:02', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (135, 1000017, 796, '2026-02-04 07:26:38', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (136, 1000014, 699, '2025-10-21 08:54:27', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (137, 1000014, 311, '2024-12-29 08:57:44', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (138, 1000014, 1015, '2026-07-14 09:50:50', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (139, 1000014, 355, '2025-01-30 04:48:08', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (140, 1000014, 854, '2026-03-10 02:49:45', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (141, 1000014, 626, '2025-10-18 03:10:35', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (142, 1000014, 156, '2024-12-20 06:54:21', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (143, 1000014, 604, '2025-08-23 05:04:18', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (144, 1000014, 986, '2026-06-24 03:00:54', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (145, 1000014, 72, '2024-09-30 05:52:25', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (146, 1000017, 602, '2025-08-14 04:29:53', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (147, 1000016, 165, '2026-05-25 01:05:42', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (148, 1000014, 714, '2025-11-26 04:42:45', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (149, 1000014, 233, '2024-11-03 05:18:57', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (150, 1000016, 816, '2026-02-21 00:53:49', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (151, 1000014, 143, '2024-08-30 05:02:30', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (152, 1000016, 447, '2025-04-17 07:10:36', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (153, 1000014, 28, '2024-09-06 02:13:09', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (154, 1000014, 650, '2025-09-15 08:53:16', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (155, 1000014, 694, '2025-10-27 02:01:02', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (156, 1000014, 825, '2026-02-26 02:12:58', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (157, 1000016, 566, '2026-05-17 02:17:12', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (158, 1000014, 852, '2026-03-04 03:48:49', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (159, 1000014, 727, '2025-12-13 06:32:41', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (160, 1000016, 308, '2025-01-01 05:02:06', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (161, 1000014, 909, '2026-04-21 07:33:29', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (162, 1000016, 28, '2025-12-27 03:48:29', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (163, 1000014, 568, '2025-07-24 01:09:37', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (164, 1000016, 163, '2024-09-24 03:29:59', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (165, 1000014, 14, '2025-09-15 07:49:37', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (166, 1000016, 654, '2025-09-22 00:23:47', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (167, 1000016, 199, '2024-10-09 03:30:23', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (168, 1000016, 880, '2026-03-22 04:11:25', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (169, 1000016, 689, '2025-10-25 04:07:37', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (170, 1000014, 23, '2024-07-10 04:23:55', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (171, 1000014, 161, '2024-09-08 00:39:09', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (172, 1000014, 710, '2025-11-25 03:11:03', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (173, 1000016, 315, '2025-01-11 06:23:50', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (174, 1000016, 51, '2025-04-18 05:13:07', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (175, 1000014, 740, '2025-12-13 03:22:17', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (176, 1000016, 294, '2024-11-13 03:14:57', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (177, 1000014, 527, '2025-06-30 06:26:10', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (178, 1000016, 337, '2025-01-14 01:45:29', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (179, 1000014, 801, '2026-01-26 06:01:29', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (180, 1000014, 439, '2025-04-13 04:29:42', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (181, 1000014, 166, '2024-09-19 00:49:58', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (182, 1000016, 372, '2025-02-13 04:47:19', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (183, 1000014, 431, '2025-04-17 02:20:24', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (184, 1000014, 962, '2026-06-10 04:19:04', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (185, 1000017, 632, '2025-08-30 04:31:33', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (186, 1000014, 8, '2024-06-25 08:41:37', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (187, 1000014, 513, '2025-06-16 00:55:01', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (188, 1000014, 699, '2025-11-13 03:48:38', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (189, 1000014, 191, '2024-11-04 03:12:18', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (190, 1000016, 57, '2024-07-28 02:00:58', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (191, 1000014, 563, '2025-07-25 03:39:54', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (192, 1000014, 141, '2024-09-14 01:39:56', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (193, 1000016, 830, '2026-02-25 02:30:48', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (194, 1000017, 661, '2025-10-01 06:19:47', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (195, 1000014, 439, '2025-05-05 08:12:07', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (196, 1000016, 11, '2024-07-05 04:02:26', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (197, 1000014, 464, '2025-05-12 02:20:34', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (198, 1000014, 393, '2025-06-16 02:40:02', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (199, 1000017, 41, '2024-07-17 03:48:36', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (200, 1000014, 538, '2025-07-15 05:11:39', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (201, 1000014, 895, '2026-04-14 00:41:08', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (202, 1000014, 196, '2026-01-30 07:01:49', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (203, 1000016, 361, '2025-02-19 02:08:07', '172.70.210.9');
INSERT INTO `sitestorepro_prod_download_log` VALUES (204, 1000014, 895, '2026-04-17 04:27:05', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (205, 1000014, 1016, '2026-07-16 07:14:39', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (206, 1000016, 169, '2025-05-30 05:29:40', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (207, 1000014, 893, '2026-04-04 01:59:52', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (208, 1000014, 820, '2026-03-01 08:42:00', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (209, 1000014, 862, '2026-03-11 07:37:45', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (210, 1000014, 346, '2025-01-27 03:39:11', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (211, 1000014, 754, '2026-05-24 02:01:54', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (212, 1000014, 264, '2024-12-02 04:13:54', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (213, 1000014, 62, '2024-07-24 02:26:16', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (214, 1000016, 777, '2026-01-25 03:41:08', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (215, 1000017, 372, '2025-08-25 06:13:04', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (216, 1000014, 361, '2025-02-11 04:57:07', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (217, 1000014, 597, '2025-08-22 00:50:51', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (218, 1000014, 683, '2026-05-30 01:42:33', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (219, 1000014, 690, '2025-10-28 06:13:03', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (220, 1000014, 489, '2025-06-01 05:02:01', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (221, 1000016, 199, '2024-10-08 02:05:35', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (222, 1000016, 827, '2026-02-13 06:06:54', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (223, 1000014, 816, '2026-02-14 06:25:19', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (224, 1000016, 28, '2026-01-01 00:34:54', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (225, 1000017, 650, '2025-09-19 00:24:28', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (226, 1000014, 242, '2026-01-09 07:27:30', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (227, 1000016, 710, '2025-11-26 02:03:40', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (228, 1000016, 689, '2025-11-09 05:55:32', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (229, 1000014, 98, '2024-08-05 02:45:22', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (230, 1000016, 531, '2025-06-28 02:40:31', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (231, 1000017, 602, '2025-08-15 06:21:52', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (232, 1000014, 1004, '2026-07-13 12:50:12', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (233, 1000014, 732, '2025-12-02 05:27:47', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (234, 1000014, 756, '2025-12-30 03:12:22', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (235, 1000014, 532, '2025-07-04 01:36:01', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (236, 1000016, 880, '2026-03-25 00:36:45', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (237, 1000016, 933, '2026-05-14 04:11:38', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (238, 1000016, 165, '2026-05-10 05:42:21', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (239, 1000014, 687, '2025-11-04 06:27:58', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (240, 1000014, 18, '2024-07-09 01:33:11', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (241, 1000014, 14, '2025-09-20 02:50:44', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (242, 1000014, 545, '2025-09-03 06:48:45', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (243, 1000014, 796, '2026-02-07 08:59:39', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (244, 1000016, 854, '2026-03-01 04:29:56', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (245, 1000014, 123, '2024-08-15 03:12:48', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (246, 1000016, 130, '2024-11-29 06:32:09', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (247, 1000014, 513, '2025-06-25 04:17:39', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (248, 1000016, 308, '2025-01-02 07:40:45', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (249, 1000014, 76, '2024-07-21 06:59:24', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (250, 1000014, 169, '2024-09-20 04:51:49', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (251, 1000014, 388, '2025-08-31 08:33:08', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (252, 1000014, 911, '2026-04-26 03:38:57', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (253, 1000014, 701, '2025-11-16 01:47:00', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (254, 1000014, 439, '2025-04-06 04:24:34', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (255, 1000016, 802, '2026-01-26 05:45:45', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (256, 1000017, 661, '2025-10-09 05:08:01', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (257, 1000014, 28, '2024-09-11 06:14:23', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (258, 1000016, 38, '2024-07-09 07:19:09', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (259, 1000014, 311, '2024-12-31 07:57:57', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (260, 1000014, 1022, '2026-07-14 15:36:32', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (261, 1000014, 161, '2024-09-15 02:33:03', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (262, 1000017, 34, '2024-07-06 01:29:49', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (263, 1000014, 811, '2026-02-09 08:28:28', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (264, 1000014, 801, '2026-01-30 05:48:43', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (265, 1000014, 456, '2025-07-03 05:58:58', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (266, 1000014, 647, '2025-09-23 04:38:48', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (267, 1000014, 59, '2024-07-24 04:04:00', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (268, 1000014, 97, '2025-06-05 00:35:08', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (269, 1000016, 223, '2024-11-03 05:01:03', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (270, 1000014, 17, '2024-07-20 05:21:31', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (271, 1000016, 879, '2026-03-01 04:06:35', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (272, 1000016, 191, '2024-11-06 08:44:42', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (273, 1000014, 538, '2025-07-02 01:02:35', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (274, 1000014, 23, '2024-07-12 06:06:19', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (275, 1000014, 563, '2025-07-19 08:14:06', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (276, 1000016, 455, '2026-03-23 04:59:05', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (277, 1000014, 271, '2024-12-07 04:00:06', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (278, 1000014, 197, '2025-08-05 05:58:01', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (279, 1000016, 835, '2026-03-02 04:19:49', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (280, 1000016, 460, '2025-05-12 04:06:47', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (281, 1000014, 226, '2024-12-23 05:51:52', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (282, 1000014, 758, '2026-01-13 02:26:49', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (283, 1000014, 67, '2026-07-17 15:43:55', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (284, 1000016, 962, '2026-05-29 02:40:07', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (285, 1000016, 59, '2024-07-29 03:20:47', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (286, 1000017, 356, '2025-02-06 06:22:33', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (287, 1000014, 833, '2026-02-25 03:09:48', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (288, 1000014, 699, '2025-10-11 01:50:21', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (289, 1000014, 35, '2024-07-24 08:09:43', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (290, 1000014, 141, '2024-09-05 07:04:31', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (291, 1000014, 49, '2024-07-18 05:06:58', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (292, 1000014, 332, '2025-01-20 04:50:42', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (293, 1000016, 337, '2025-01-10 02:53:07', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (294, 1000014, 747, '2025-12-20 05:39:37', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (295, 1000014, 668, '2025-10-05 02:02:50', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (296, 1000016, 447, '2025-04-23 05:55:55', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (297, 1000014, 146, '2024-09-20 00:30:04', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (298, 1000016, 11, '2024-06-30 07:15:30', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (299, 1000014, 604, '2025-08-13 02:38:27', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (300, 1000017, 93, '2024-12-24 05:26:19', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (301, 1000014, 182, '2024-10-10 08:15:20', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (302, 1000016, 654, '2025-09-19 06:33:45', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (303, 1000014, 41, '2024-07-24 04:30:18', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (304, 1000016, 385, '2025-02-21 06:14:51', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (305, 1000014, 155, '2024-09-09 07:29:32', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (306, 1000014, 422, '2025-04-07 02:20:33', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (307, 1000014, 727, '2025-12-03 08:51:04', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (308, 1000014, 171, '2024-09-19 08:24:42', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (309, 1000014, 580, '2025-07-28 04:23:57', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (310, 1000014, 740, '2025-12-13 08:52:36', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (311, 1000014, 627, '2025-09-03 02:02:56', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (312, 1000014, 309, '2024-12-29 08:22:42', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (313, 1000014, 518, '2025-07-04 02:41:22', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (314, 1000014, 847, '2026-02-28 00:52:41', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (315, 1000016, 721, '2025-12-12 01:52:12', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (316, 1000016, 315, '2025-01-05 04:06:21', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (317, 1000014, 278, '2025-07-27 08:21:30', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (318, 1000014, 390, '2025-03-13 05:50:44', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (319, 1000014, 282, '2024-12-15 06:05:34', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (320, 1000014, 72, '2024-10-01 01:58:49', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (321, 1000014, 464, '2025-05-19 06:09:09', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (322, 1000014, 931, '2026-05-17 02:50:29', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (323, 1000014, 762, '2026-01-17 05:09:56', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (324, 1000014, 166, '2024-09-27 03:46:59', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (325, 1000014, 317, '2025-12-26 03:34:04', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (326, 1000014, 204, '2024-10-23 01:02:59', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (327, 1000014, 632, '2025-09-14 00:32:25', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (328, 1000014, 920, '2026-04-20 03:16:08', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (329, 1000017, 229, '2024-10-26 06:05:04', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (330, 1000014, 631, '2025-09-02 04:20:49', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (331, 1000014, 699, '2025-11-09 06:16:21', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (332, 1000014, 875, '2026-03-30 02:24:36', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (333, 1000014, 12, '2024-07-06 05:46:42', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (334, 1000016, 294, '2024-11-21 01:50:45', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (335, 1000014, 889, '2026-04-12 02:46:07', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (336, 1000017, 313, '2025-01-08 05:07:14', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (337, 1000014, 355, '2025-01-27 08:49:21', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (338, 1000014, 110, '2024-08-16 07:55:58', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (339, 1000014, 47, '2024-10-13 07:58:54', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (340, 1000014, 113, '2025-11-07 02:10:50', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (341, 1000014, 568, '2025-08-02 06:19:53', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (342, 1000014, 620, '2025-09-01 05:54:44', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (343, 1000014, 156, '2024-12-21 07:25:59', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (344, 1000014, 233, '2024-11-12 03:05:24', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (345, 1000014, 868, '2026-03-29 01:04:21', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (346, 1000014, 986, '2026-07-03 03:11:48', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (347, 1000016, 57, '2024-07-17 02:11:33', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (348, 1000014, 196, '2026-01-22 05:35:17', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (349, 1000014, 439, '2025-05-14 01:34:24', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (350, 1000014, 626, '2025-10-22 03:37:05', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (351, 1000014, 334, '2025-01-20 07:40:36', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (352, 1000016, 378, '2025-04-23 03:12:24', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (353, 1000014, 436, '2025-04-23 00:51:33', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (354, 1000016, 739, '2025-12-19 05:12:01', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (355, 1000014, 124, '2024-09-02 08:08:21', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (356, 1000014, 813, '2026-02-10 01:35:55', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (357, 1000014, 197, '2024-10-06 03:41:54', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (358, 1000014, 750, '2025-12-22 02:55:02', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (359, 1000014, 527, '2025-06-25 02:49:04', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (360, 1000014, 501, '2025-06-27 02:35:28', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (361, 1000014, 719, '2025-12-05 04:44:33', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (362, 1000014, 16, '2024-07-14 04:56:21', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (363, 1000016, 163, '2024-09-12 02:25:16', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (364, 1000014, 852, '2026-03-05 08:08:53', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (365, 1000014, 714, '2025-11-24 00:58:14', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (366, 1000014, 909, '2026-04-19 05:26:15', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (367, 1000014, 825, '2026-02-19 06:25:47', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (368, 1000014, 276, '2025-03-03 05:10:06', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (369, 1000016, 51, '2025-04-24 04:16:41', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (370, 1000014, 440, '2025-04-14 05:15:12', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (371, 1000014, 997, '2026-07-13 05:02:02', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (372, 1000016, 290, '2024-12-17 05:17:54', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (373, 1000014, 267, '2025-01-03 04:36:34', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (374, 1000016, 178, '2024-10-04 00:50:13', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (375, 1000016, 662, '2025-09-26 04:02:54', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (376, 1000016, 319, '2026-07-16 05:16:20', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (377, 1000014, 142, '2024-09-17 08:24:59', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (378, 1000014, 492, '2025-06-05 04:09:41', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (379, 1000016, 566, '2026-05-11 03:35:21', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (380, 1000016, 678, '2025-10-17 06:12:53', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (381, 1000014, 952, '2026-05-23 04:05:14', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (382, 1000014, 433, '2025-04-15 07:37:52', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (383, 1000014, 431, '2025-04-08 05:34:09', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (384, 1000014, 856, '2026-03-22 00:59:41', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (385, 1000014, 8, '2024-06-29 03:56:32', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (386, 1000014, 1015, '2026-07-17 11:53:48', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (387, 1000017, 11, '2024-11-16 07:12:29', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (388, 1000014, 467, '2025-05-07 01:31:28', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (389, 1000014, 1001, '2026-07-08 01:45:25', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (390, 1000016, 372, '2025-02-24 08:01:28', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (391, 1000014, 322, '2025-01-17 00:27:48', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (392, 1000016, 830, '2026-02-26 06:21:36', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (393, 1000014, 90, '2024-08-16 01:29:55', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (394, 1000014, 393, '2025-06-21 07:57:26', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (395, 1000016, 319, '2025-12-10 00:46:51', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (396, 1000014, 937, '2026-05-13 08:43:54', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (397, 1000016, 898, '2026-04-04 07:14:36', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (398, 1000014, 14, '2024-07-08 04:08:16', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (399, 1000014, 143, '2024-08-30 06:45:17', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (400, 1000014, 44, '2024-10-17 04:51:06', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (401, 1000014, 466, '2025-06-02 02:11:49', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (402, 1000014, 694, '2025-11-11 06:58:35', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (403, 1000014, 983, '2026-06-28 07:45:44', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (404, 1000014, 192, '2024-10-13 02:01:41', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (405, 1000014, 765, '2026-01-03 03:23:33', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (406, 1000014, 767, '2026-01-22 07:04:25', '172.70.206.202');
INSERT INTO `sitestorepro_prod_download_log` VALUES (407, 1000014, 191, '2024-10-27 07:58:40', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (408, 1000014, 699, '2025-10-22 04:16:00', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (409, 1000016, 163, '2024-09-13 08:30:56', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (410, 1000014, 747, '2025-12-27 03:56:44', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (411, 1000014, 334, '2025-01-25 03:42:17', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (412, 1000014, 997, '2026-07-04 05:18:51', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (413, 1000014, 721, '2025-12-02 07:38:41', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (414, 1000014, 1022, '2026-07-12 08:30:24', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (415, 1000016, 879, '2026-03-02 05:25:23', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (416, 1000014, 124, '2024-08-16 07:12:43', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (417, 1000014, 278, '2025-08-04 02:52:05', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (418, 1000017, 229, '2024-11-07 03:06:09', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (419, 1000014, 332, '2025-01-20 05:44:23', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (420, 1000014, 889, '2026-04-15 04:18:52', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (421, 1000014, 531, '2025-07-02 01:09:36', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (422, 1000014, 440, '2025-04-15 05:14:33', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (423, 1000014, 93, '2024-12-19 02:35:09', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (424, 1000016, 165, '2026-05-19 01:45:11', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (425, 1000014, 311, '2025-01-10 07:17:53', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (426, 1000014, 1004, '2026-07-05 08:34:01', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (427, 1000014, 431, '2025-04-13 07:29:24', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (428, 1000014, 833, '2026-03-10 04:40:17', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (429, 1000014, 90, '2024-08-09 07:46:27', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (430, 1000014, 492, '2025-05-22 07:44:05', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (431, 1000014, 156, '2024-12-15 06:17:16', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (432, 1000014, 647, '2025-09-14 06:01:04', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (433, 1000014, 143, '2024-09-09 06:34:28', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (434, 1000016, 130, '2024-11-17 01:34:07', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (435, 1000014, 532, '2025-07-13 01:52:39', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (436, 1000014, 875, '2026-04-03 05:04:57', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (437, 1000014, 714, '2025-12-04 02:02:54', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (438, 1000014, 862, '2026-03-06 06:30:49', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (439, 1000014, 35, '2024-07-12 00:44:39', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (440, 1000014, 661, '2025-09-24 02:14:20', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (441, 1000014, 388, '2025-09-13 00:38:53', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (442, 1000014, 98, '2024-08-14 06:32:15', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (443, 1000014, 171, '2024-09-22 06:34:28', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (444, 1000014, 34, '2024-07-14 05:17:44', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (445, 1000014, 355, '2025-02-04 07:02:40', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (446, 1000014, 16, '2024-07-01 02:56:26', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (447, 1000016, 898, '2026-04-06 08:43:38', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (448, 1000016, 802, '2026-02-07 01:52:18', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (449, 1000014, 113, '2025-11-06 03:32:24', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (450, 1000014, 545, '2025-09-04 03:43:50', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (451, 1000017, 632, '2025-09-05 01:48:29', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (452, 1000014, 563, '2025-07-28 01:18:43', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (453, 1000014, 464, '2025-05-09 04:34:55', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (454, 1000016, 962, '2026-05-27 03:55:09', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (455, 1000016, 385, '2025-02-24 07:42:35', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (456, 1000014, 1001, '2026-07-17 17:52:24', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (457, 1000014, 197, '2024-10-13 02:48:52', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (458, 1000016, 827, '2026-02-27 00:54:45', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (459, 1000014, 23, '2024-07-02 03:12:54', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (460, 1000014, 204, '2024-10-17 02:11:46', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (461, 1000014, 59, '2024-07-19 04:13:01', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (462, 1000014, 811, '2026-02-18 04:35:18', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (463, 1000016, 319, '2025-12-05 01:52:47', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (464, 1000014, 489, '2025-05-18 09:00:06', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (465, 1000014, 17, '2024-07-07 04:55:29', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (466, 1000016, 933, '2026-05-06 01:00:18', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (467, 1000016, 372, '2025-02-20 05:08:27', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (468, 1000014, 72, '2024-10-05 03:10:28', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (469, 1000014, 852, '2026-03-15 03:49:51', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (470, 1000014, 825, '2026-02-28 07:03:40', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (471, 1000014, 518, '2025-06-19 08:42:32', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (472, 1000014, 683, '2026-05-29 03:22:58', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (473, 1000014, 986, '2026-07-08 00:52:04', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (474, 1000016, 192, '2024-10-07 06:50:55', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (475, 1000014, 142, '2024-09-15 04:31:13', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (476, 1000016, 199, '2024-10-24 01:21:22', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (477, 1000014, 276, '2025-03-07 02:58:38', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (478, 1000014, 166, '2024-09-21 03:51:27', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (479, 1000016, 28, '2025-12-26 08:17:01', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (480, 1000014, 67, '2026-07-17 19:22:09', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (481, 1000014, 758, '2025-12-30 04:09:27', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (482, 1000014, 439, '2025-05-16 02:34:02', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (483, 1000016, 51, '2025-04-23 06:16:56', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (484, 1000016, 835, '2026-02-21 01:28:51', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (485, 1000016, 11, '2024-07-01 02:57:47', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (486, 1000014, 604, '2025-08-26 08:12:12', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (487, 1000016, 294, '2024-11-16 02:12:40', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (488, 1000016, 854, '2026-03-02 08:25:29', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (489, 1000014, 1015, '2026-07-09 07:03:55', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (490, 1000014, 732, '2025-12-17 01:00:35', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (491, 1000014, 18, '2024-07-08 00:30:52', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (492, 1000016, 830, '2026-03-08 02:23:49', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (493, 1000017, 313, '2025-01-11 00:23:54', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (494, 1000014, 12, '2024-06-30 05:44:51', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (495, 1000014, 97, '2025-06-03 07:24:17', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (496, 1000014, 169, '2025-06-01 08:01:04', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (497, 1000017, 626, '2025-10-20 03:56:06', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (498, 1000014, 654, '2025-09-22 08:28:02', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (499, 1000016, 689, '2025-11-02 03:48:32', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (500, 1000014, 767, '2026-01-19 07:12:20', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (501, 1000014, 687, '2025-11-04 05:54:00', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (502, 1000014, 820, '2026-02-20 08:59:44', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (503, 1000016, 290, '2024-12-18 04:51:40', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (504, 1000014, 796, '2026-01-21 04:57:28', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (505, 1000017, 356, '2025-01-27 00:24:25', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (506, 1000014, 597, '2025-08-09 03:46:01', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (507, 1000014, 847, '2026-03-11 08:42:18', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (508, 1000014, 169, '2024-09-18 06:33:09', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (509, 1000014, 620, '2025-09-05 02:01:04', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (510, 1000014, 141, '2024-09-03 05:03:03', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (511, 1000014, 765, '2026-01-07 00:45:25', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (512, 1000014, 719, '2025-11-22 03:33:14', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (513, 1000017, 372, '2025-08-30 02:26:20', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (514, 1000014, 568, '2025-07-27 05:34:21', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (515, 1000014, 182, '2024-10-08 04:47:02', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (516, 1000014, 197, '2025-08-03 07:02:07', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (517, 1000017, 456, '2025-06-28 05:29:14', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (518, 1000016, 777, '2026-01-15 02:54:26', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (519, 1000014, 538, '2025-07-19 00:56:49', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (520, 1000014, 264, '2024-11-27 02:04:48', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (521, 1000014, 161, '2024-09-08 04:53:07', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (522, 1000017, 911, '2026-04-25 04:04:46', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (523, 1000014, 14, '2025-09-09 03:18:19', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (524, 1000014, 439, '2025-04-24 02:36:56', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (525, 1000016, 378, '2025-04-26 00:34:30', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (526, 1000014, 422, '2025-03-28 04:35:38', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (527, 1000014, 931, '2026-05-18 06:33:32', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (528, 1000014, 436, '2025-04-11 08:29:04', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (529, 1000014, 196, '2026-02-06 03:30:18', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (530, 1000014, 11, '2024-11-20 04:33:28', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (531, 1000014, 123, '2024-08-24 07:50:50', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (532, 1000014, 801, '2026-01-30 04:01:51', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (533, 1000014, 893, '2026-04-07 07:21:35', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (534, 1000016, 57, '2024-07-15 04:33:33', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (535, 1000014, 315, '2024-12-28 02:52:11', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (536, 1000014, 466, '2025-06-05 01:16:47', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (537, 1000014, 1016, '2026-07-11 07:13:37', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (538, 1000017, 110, '2024-08-19 08:47:15', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (539, 1000014, 909, '2026-04-28 07:11:32', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (540, 1000016, 662, '2025-09-28 01:46:16', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (541, 1000014, 856, '2026-03-19 06:19:19', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (542, 1000014, 668, '2025-09-30 02:25:55', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (543, 1000014, 756, '2026-01-03 07:21:27', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (544, 1000014, 513, '2025-06-21 04:59:06', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (545, 1000014, 28, '2024-09-16 08:53:50', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (546, 1000014, 501, '2025-06-17 07:24:24', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (547, 1000014, 727, '2025-11-29 03:15:11', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (548, 1000014, 309, '2025-01-04 00:48:44', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (549, 1000014, 49, '2024-07-28 06:59:27', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (550, 1000014, 762, '2026-01-18 08:04:15', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (551, 1000014, 694, '2025-11-11 01:43:15', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (552, 1000016, 447, '2025-04-26 08:50:35', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (553, 1000016, 319, '2026-07-17 18:49:19', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (554, 1000014, 527, '2025-07-07 02:49:10', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (555, 1000016, 361, '2025-02-04 04:33:54', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (556, 1000016, 59, '2024-07-28 07:11:01', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (557, 1000016, 566, '2026-05-08 05:57:14', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (558, 1000017, 602, '2025-08-16 06:06:06', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (559, 1000014, 813, '2026-02-23 06:01:41', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (560, 1000017, 282, '2024-12-21 06:23:41', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (561, 1000014, 267, '2025-01-11 03:59:27', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (562, 1000014, 433, '2025-04-18 08:02:34', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (563, 1000016, 223, '2024-11-01 04:24:26', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (564, 1000014, 710, '2025-11-26 07:49:52', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (565, 1000014, 76, '2024-07-29 03:10:49', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (566, 1000014, 47, '2024-10-10 01:25:39', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (567, 1000014, 460, '2025-05-07 03:04:44', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (568, 1000016, 308, '2025-01-08 07:31:19', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (569, 1000014, 690, '2025-10-29 02:40:37', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (570, 1000014, 8, '2024-06-28 08:30:43', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (571, 1000014, 740, '2025-12-19 05:21:35', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (572, 1000014, 317, '2025-12-27 00:42:23', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (573, 1000014, 895, '2026-04-11 01:36:13', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (574, 1000014, 467, '2025-05-18 07:45:47', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (575, 1000017, 41, '2024-07-17 00:29:54', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (576, 1000014, 346, '2025-01-25 02:50:13', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (577, 1000014, 920, '2026-04-22 04:28:55', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (578, 1000014, 952, '2026-05-31 08:00:38', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (579, 1000014, 44, '2024-10-10 08:26:32', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (580, 1000014, 983, '2026-07-09 00:41:43', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (581, 1000014, 937, '2026-05-13 05:23:44', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (582, 1000014, 14, '2024-07-03 01:15:40', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (583, 1000014, 226, '2024-12-24 06:20:12', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (584, 1000014, 750, '2026-01-02 07:25:01', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (585, 1000014, 233, '2024-10-29 03:20:17', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (586, 1000014, 627, '2025-08-24 06:42:19', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (587, 1000016, 455, '2026-03-28 06:29:29', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (588, 1000016, 678, '2025-10-28 07:36:19', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (589, 1000014, 701, '2025-11-20 01:07:58', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (590, 1000014, 155, '2024-09-20 07:18:16', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (591, 1000014, 393, '2025-06-24 07:17:43', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (592, 1000014, 699, '2025-11-14 08:19:37', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (593, 1000014, 390, '2025-03-14 02:45:45', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (594, 1000016, 38, '2024-07-18 01:04:44', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (595, 1000016, 880, '2026-04-05 00:26:12', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (596, 1000014, 322, '2025-01-09 01:59:28', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (597, 1000014, 62, '2024-07-29 07:24:17', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (598, 1000014, 580, '2025-07-25 03:29:01', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (599, 1000014, 816, '2026-02-06 01:24:52', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (600, 1000014, 271, '2024-12-07 06:29:26', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (601, 1000016, 754, '2026-05-31 04:35:24', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (602, 1000017, 650, '2025-09-18 02:43:46', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (603, 1000014, 868, '2026-03-23 01:32:44', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (604, 1000016, 178, '2024-09-30 01:23:13', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (605, 1000014, 631, '2025-08-30 01:41:52', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (606, 1000014, 739, '2025-12-17 04:57:15', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (607, 1000014, 146, '2024-09-05 03:32:34', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (608, 1000016, 337, '2025-01-11 08:47:08', '162.158.91.61');
INSERT INTO `sitestorepro_prod_download_log` VALUES (609, 1000016, 242, '2025-12-24 01:13:03', '162.158.91.61');

-- ----------------------------
-- Table structure for sitestorepro_prod_images
-- ----------------------------
DROP TABLE IF EXISTS `sitestorepro_prod_images`;
CREATE TABLE `sitestorepro_prod_images`  (
  `prodpicid` int NOT NULL AUTO_INCREMENT,
  `imagename` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `ProdID` bigint NULL DEFAULT NULL,
  `imagedescription` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `IsColor` int NULL DEFAULT 0,
  `colorassocid` int NULL DEFAULT NULL,
  `Active` int NULL DEFAULT 1,
  `SearchImage` int NULL DEFAULT 0,
  `sitestorepro_upgrade_text1` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_text2` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_text3` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_vc1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sitestorepro_upgrade_vc2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sitestorepro_upgrade_num1` int NULL DEFAULT 0,
  `sitestorepro_upgrade_num2` int NULL DEFAULT NULL,
  `sitestorepro_upgrade_num3` int NULL DEFAULT NULL,
  `sitestorepro_upgrade_num4` int NULL DEFAULT NULL,
  `sitestorepro_upgrade_num5` int NULL DEFAULT NULL,
  `sitestorepro_upgrade_fl1` double NULL DEFAULT NULL,
  `sitestorepro_upgrade_fl2` double NULL DEFAULT NULL,
  `sitestorepro_upgrade_fl3` double NULL DEFAULT NULL,
  `sitestorepro_upgrade_ln1` bigint NULL DEFAULT NULL,
  `sitestorepro_upgrade_ln2` bigint NULL DEFAULT NULL,
  `sitestorepro_upgrade_date1` datetime NULL DEFAULT NULL,
  `sitestorepro_upgrade_date2` datetime NULL DEFAULT NULL,
  `sitestorepro_upgrade_date3` datetime NULL DEFAULT NULL,
  `sitestorepro_upgrade_date4` datetime NULL DEFAULT NULL,
  `sitestorepro_upgrade_image1` longblob NULL,
  PRIMARY KEY (`prodpicid`) USING BTREE,
  INDEX `ProdIDimagecs`(`ProdID` ASC) USING BTREE,
  INDEX `Activeimagescs`(`Active` ASC) USING BTREE,
  INDEX `SearchImageimagescs`(`SearchImage` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 841442 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sitestorepro_prod_images
-- ----------------------------
INSERT INTO `sitestorepro_prod_images` VALUES (841121, '2021_1000001.webp', 1000001, NULL, 0, NULL, 1, 1, NULL, NULL, NULL, NULL, '2021_1000001_zoom.webp', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_images` VALUES (841122, '2021_sample_002.webp', 1000002, NULL, 0, NULL, 1, 1, NULL, NULL, NULL, NULL, '2021_sample_002_zoom.webp', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_images` VALUES (841123, '2021_sample_1000003.webp', 1000003, NULL, 0, NULL, 1, 1, NULL, NULL, NULL, NULL, '2021_sample_1000003_zoom.webp', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_images` VALUES (841124, '2021_sample_004.webp', 1000004, NULL, 0, NULL, 1, 1, NULL, NULL, NULL, NULL, '2021_sample_004_zoom.webp', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_images` VALUES (841126, '2021_sample_1000005.webp', 1000005, 'Great Ring!', 0, NULL, 1, 1, NULL, NULL, NULL, NULL, '2021_sample_1000005_zoom.webp', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_images` VALUES (841127, '2021_1000006.webp', 1000006, NULL, 0, NULL, 1, 1, NULL, NULL, NULL, NULL, '2021_1000006_zoom.webp', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_images` VALUES (841128, '2021_1000007.webp', 1000007, NULL, 0, NULL, 1, 1, NULL, NULL, NULL, NULL, '2021_1000007_zoom.webp', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_images` VALUES (841129, '2021_1000008.webp', 1000008, NULL, 0, NULL, 1, 1, NULL, NULL, NULL, NULL, '2021_1000008_zoom.webp', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_images` VALUES (841130, '2021_1000009.webp', 1000009, NULL, 0, NULL, 1, 1, NULL, NULL, NULL, NULL, '2021_1000009_zoom.webp', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_images` VALUES (841131, '2021_1000010.webp', 1000010, NULL, 0, NULL, 1, 1, NULL, NULL, NULL, NULL, '2021_1000010_zoom.webp', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_images` VALUES (841132, '2021_1000011.webp', 1000011, NULL, 0, NULL, 1, 1, NULL, NULL, NULL, NULL, '2021_1000011_zoom.webp', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_images` VALUES (841133, '2021_1000012.webp', 1000012, NULL, 0, NULL, 1, 1, NULL, NULL, NULL, NULL, '2021_1000012_zoom.webp', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_images` VALUES (841134, '2021_sample_1000013.webp', 1000013, NULL, 0, NULL, 1, 1, NULL, NULL, NULL, NULL, '2021_sample_1000013_zoom.webp', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_images` VALUES (841355, '2021_burgundy sweatshirt.webp', 1000015, 'Burgundy', 54, NULL, 1, 0, 'Burgundy Sweatshirt', NULL, 'Burgundy', NULL, '2021_burgundy sweatshirt_zoom.webp', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_images` VALUES (841356, '2021_white_sweatshirt.webp', 1000015, 'White', 52, NULL, 1, 0, 'White Sweatshirt', NULL, 'White', NULL, '2021_white_sweatshirt_zoom.webp', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_images` VALUES (841357, '2021_black_sweatshirt.webp', 1000015, 'Black', 53, NULL, 1, 0, 'Black Sweatshirt', NULL, 'Black', NULL, '2021_black_sweatshirt_zoom.webp', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_images` VALUES (841362, 'https://d3t23w3v39t89j.cloudfront.net/2021_CDN_sample.webp', 1000018, 'CDN Example', 0, NULL, 1, 1, 'Zoom Image Stored On CDN', 'https://d3t23w3v39t89j.cloudfront.net/2021_CDN_sample_small.webp', NULL, NULL, 'https://d3t23w3v39t89j.cloudfront.net/2021_CDN_sample_zoom.webp', 1, 1, 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_images` VALUES (841363, '2021_sweatshirt_group.webp', 1000015, NULL, 0, NULL, 1, 1, NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_images` VALUES (841365, '2021_vintage_sample.webp', 1000019, 'Sample Vintage Watch Item', 0, NULL, 1, 1, 'Sample Vintage Watch', 'vintage_sample_small.webp', NULL, NULL, '2021_vintage_sample_zoom.webp', 2, NULL, 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_images` VALUES (841367, 'watch.webp', 1000020, 'Sample Watch Item', 0, NULL, 1, 1, 'Sample Watch', 'watch_small.webp', NULL, NULL, 'watch_zoom.webp', 1, NULL, 1, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_images` VALUES (841368, 'watch_black.webp', 1000020, 'Sample Watch Item', 53, NULL, 1, 0, 'Sample Black Vintage Watch', 'watch_black_small.webp', NULL, NULL, 'watch_black_zoom.webp', 2, NULL, 0, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_images` VALUES (841369, 'watch_brown.webp', 1000020, 'Sample Watch Item', 55, NULL, 1, 0, 'Sample Brown Vintage Watch', 'watch_brown_small.webp', NULL, NULL, 'watch_brown_zoom.webp', 1, NULL, 0, NULL, NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_images` VALUES (841371, 'sample_pens.webp', 1000021, 'Sample Pen Product', 0, NULL, 1, 1, 'Zoom View For Sample Pens', 'sample_pens_small.webp', NULL, NULL, 'sample_pens_zoom.webp', 2, NULL, 0, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_images` VALUES (841373, '2021_gift_box.webp', 1000022, 'Vintage Gift Box', 0, NULL, 1, 1, 'sample Vintage Gift Box', 'gift_box_small.webp', NULL, NULL, '2021_gift_box_zoom.webp', 2, NULL, 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_images` VALUES (841375, '2021_pocket_modern.webp', 1000023, 'Sample Modern Pocketwatch', 0, NULL, 1, 1, 'Sample Modern Watch Zoom Image', 'pocket_modern_small.webp', NULL, NULL, '', 0, NULL, 0, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_images` VALUES (841377, '2021_modern_watch_sample.webp', 1000024, 'Modern Sample Watch', 0, NULL, 1, 1, 'Modern Watch', 'modern_watch_sample_small.webp', NULL, NULL, '2021_modern_watch_sample_zoom.webp', 1, NULL, 0, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_images` VALUES (841379, 'brown.webp', 1000025, 'Brown Tee', 55, NULL, 1, 1, 'Brown Tee', 'brown_small.webp', NULL, NULL, 'brown_zoom.webp', 1, NULL, 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_images` VALUES (841380, 'gray.webp', 1000025, 'Grey Tee', 59, NULL, 1, 0, 'Grey Tee', 'gray_small.webp', NULL, NULL, 'gray_zoom.webp', 1, NULL, 0, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_images` VALUES (841381, 'green.webp', 1000025, 'Green Tee', 57, NULL, 1, 0, 'Green Tee', 'green_small.webp', NULL, NULL, 'green_zoom.webp', 1, NULL, 0, NULL, NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_images` VALUES (841382, 'navy.webp', 1000025, 'Navy Tee', 58, NULL, 1, 0, 'Navy Tee', 'navy_small.webp', NULL, NULL, 'navy_zoom.webp', 1, NULL, 0, NULL, NULL, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_images` VALUES (841383, 'orange.webp', 1000025, 'Orange Tee', 56, NULL, 1, 0, 'Orange Tee', 'orange_small.webp', NULL, NULL, 'orange_zoom.webp', 1, NULL, 0, NULL, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_images` VALUES (841384, 'royal.webp', 1000025, 'Royal Tee', 60, NULL, 1, 0, 'Royal Tee', 'royal_small.webp', NULL, NULL, 'royal_zoom.webp', 1, NULL, 0, NULL, NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_images` VALUES (841385, 'brown.webp', 1000026, 'Brown Tee', 55, NULL, 1, 0, 'Brown Tee', 'brown_small.webp', NULL, NULL, 'brown_zoom.webp', 1, NULL, 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_images` VALUES (841386, 'gray.webp', 1000026, 'Grey Tee', 59, NULL, 1, 0, 'Grey Tee', 'gray_small.webp', NULL, NULL, 'gray_zoom.webp', 1, NULL, 0, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_images` VALUES (841387, 'green.webp', 1000026, 'Green Tee', 57, NULL, 1, 1, 'Green Tee', 'green_small.webp', NULL, NULL, 'green_zoom.webp', 1, NULL, 0, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_images` VALUES (841388, 'navy.webp', 1000026, 'Navy Tee', 58, NULL, 1, 0, 'Navy Tee', 'navy_small.webp', NULL, NULL, 'navy_zoom.webp', 1, NULL, 0, NULL, NULL, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_images` VALUES (841389, 'orange.webp', 1000026, 'Orange Tee', 56, NULL, 1, 0, 'Orange Tee', 'orange_small.webp', NULL, NULL, 'orange_zoom.webp', 1, NULL, 0, NULL, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_images` VALUES (841390, 'royal.webp', 1000026, 'Royal Tee', 60, NULL, 1, 0, 'Royal Tee', 'royal_small.webp', NULL, NULL, 'royal_zoom.webp', 1, NULL, 0, NULL, NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_images` VALUES (841391, '2021_red_sweatshirt.webp', 1000027, 'Red', 61, NULL, 1, 0, 'Red Sweatshirt', '', 'Red', NULL, '2021_red_sweatshirt_zoom.webp', 2, NULL, 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_images` VALUES (841392, '2021_white_sweatshirt.webp', 1000027, 'White', 52, NULL, 1, 0, 'White Sweatshirt', NULL, 'White', NULL, '2021_white_sweatshirt_zoom.webp', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_images` VALUES (841393, '2021_black_sweatshirt.webp', 1000027, 'Black', 53, NULL, 1, 0, 'Black Sweatshirt', NULL, 'Black', NULL, '2021_black_sweatshirt_zoom.webp', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_images` VALUES (841394, '2021_sweatshirt_group_women.webp', 1000027, NULL, 0, NULL, 1, 1, NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_images` VALUES (841395, 'jewelry_cleaning_101.webp', 1000014, '', 0, 0, 1, 0, '', '', NULL, NULL, '', 0, NULL, 0, NULL, NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_images` VALUES (841396, 'jewelry_cleaning_101-b.webp', 1000014, '', 0, 0, 1, 0, '', '', NULL, NULL, '', 0, NULL, 0, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_images` VALUES (841397, 'jewelry_cleaning_101-c.webp', 1000014, '', 0, 0, 1, 0, '', '', NULL, NULL, '', 0, NULL, 0, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_images` VALUES (841398, '2021_1000014.webp', 1000014, '', 0, 0, 1, 1, '', '', NULL, NULL, '', 0, NULL, 1, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_images` VALUES (841399, '1000016-sample-4.webp', 1000016, '', 0, 0, 1, 0, '', '1000016-sample-4_small.webp', NULL, NULL, '1000016-sample-4_zoom.webp', 0, NULL, 0, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_images` VALUES (841400, '2021_1000017.webp', 1000017, NULL, 0, 0, 1, 1, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_images` VALUES (841403, 'product-builder-demo.webp', 1000029, 'Builder Demo', 0, NULL, 1, 1, '', 'product-builder-demo_small.webp', NULL, NULL, '', 0, NULL, 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_images` VALUES (841404, '1000016-sample-3.webp', 1000016, '', 0, NULL, 1, 1, '', '1000016-sample-3_small.webp', NULL, NULL, '1000016-sample-3_zoom.webp', 0, NULL, 0, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_images` VALUES (841405, '1000016-sample-2.webp', 1000016, '', 0, NULL, 1, 0, '', '1000016-sample-2_small.webp', NULL, NULL, '1000016-sample-2_zoom.webp', 0, NULL, 0, NULL, NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_images` VALUES (841406, '1000016-sample-1.webp', 1000016, '', 0, NULL, 1, 0, '', '1000016-sample-1_small.webp', NULL, NULL, '1000016-sample-1_zoom.webp', 0, NULL, 0, NULL, NULL, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_images` VALUES (841407, 'invoice-product.webp', 1000028, '', 0, NULL, 1, 1, '', 'invoice-product_small.webp', NULL, NULL, '', 0, NULL, 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_images` VALUES (841409, 'donate-demo.webp', 1000030, '', 0, NULL, 1, 1, '', 'donate-demo_small.webp', NULL, NULL, 'donate-demo_zoom.webp', 0, NULL, 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_images` VALUES (841422, 'event-sample-2.webp', 1000037, NULL, 0, 0, 1, 1, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_images` VALUES (841423, 'event-sample-3.webp', 1000033, NULL, 0, 0, 1, 1, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_images` VALUES (841424, 'event-sample-4.webp', 1000035, NULL, 0, 0, 1, 1, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_images` VALUES (841425, 'event-sample-1.webp', 1000031, NULL, 0, 0, 1, 1, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_images` VALUES (841426, 'event-sample-5.webp', 1000038, NULL, 0, 0, 1, 1, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_images` VALUES (841427, 'event-sample-6.webp', 1000036, NULL, 0, 0, 1, 1, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_images` VALUES (841429, 'event-sample-7.webp', 1000032, '', 0, 0, 1, 1, '', '', NULL, NULL, '', 0, NULL, 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_images` VALUES (841430, 'event-sample-9.webp', 1000034, '', 0, NULL, 1, 1, '', 'event-sample-9_small.webp', NULL, NULL, 'event-sample-9_zoom.webp', 0, NULL, 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- ----------------------------
-- Table structure for sitestorepro_prod_input_fields
-- ----------------------------
DROP TABLE IF EXISTS `sitestorepro_prod_input_fields`;
CREATE TABLE `sitestorepro_prod_input_fields`  (
  `ProdInputFieldID` int NOT NULL AUTO_INCREMENT,
  `ProdID` int NULL DEFAULT 0,
  `InputField1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `InputField1On` int NULL DEFAULT 0,
  `InputField1Instructions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `InputField1Length` int NULL DEFAULT 30,
  `InputField1MaxCharacters` int NULL DEFAULT 255,
  `InputField1Required` int NULL DEFAULT 0,
  `InputField1ErrorMessage` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `InputField2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `InputField2On` int NULL DEFAULT 0,
  `InputField2Instructions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `InputField2Length` int NULL DEFAULT 30,
  `InputField2MaxCharacters` int NULL DEFAULT 255,
  `InputField2Required` int NULL DEFAULT 0,
  `InputField2ErrorMessage` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `InputField3` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `InputField3On` int NULL DEFAULT 0,
  `InputField3Instructions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `InputField3Length` int NULL DEFAULT 30,
  `InputField3MaxCharacters` int NULL DEFAULT 255,
  `InputField3Required` int NULL DEFAULT 0,
  `InputField3ErrorMessage` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `InputField4` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `InputField4On` int NULL DEFAULT 0,
  `InputField4Instructions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `InputField4Length` int NULL DEFAULT 30,
  `InputField4MaxCharacters` int NULL DEFAULT 255,
  `InputField4Required` int NULL DEFAULT 0,
  `InputField4ErrorMessage` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `InputField5` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `InputField5On` int NULL DEFAULT 0,
  `InputField5Instructions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `InputField5Length` int NULL DEFAULT 30,
  `InputField5MaxCharacters` int NULL DEFAULT 255,
  `InputField5Required` int NULL DEFAULT 0,
  `InputField5ErrorMessage` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `InputArea1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `InputArea1On` int NULL DEFAULT 0,
  `InputArea1Instructions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `InputArea1Length` int NULL DEFAULT 20,
  `InputArea1MaxCharacters` int NULL DEFAULT 255,
  `InputArea1NumLines` int NULL DEFAULT 4,
  `InputArea1Required` int NULL DEFAULT 0,
  `InputArea1ErrorMessage` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `InputArea2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `InputArea2On` int NULL DEFAULT 0,
  `InputArea2Instructions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `InputArea2Length` int NULL DEFAULT 20,
  `InputArea2MaxCharacters` int NULL DEFAULT 255,
  `InputArea2NumLines` int NULL DEFAULT 4,
  `InputArea2Required` int NULL DEFAULT 0,
  `InputArea2ErrorMessage` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `InputArea3` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `InputArea3On` int NULL DEFAULT 0,
  `InputArea3Instructions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `InputArea3Length` int NULL DEFAULT 20,
  `InputArea3MaxCharacters` int NULL DEFAULT 255,
  `InputArea3NumLines` int NULL DEFAULT 4,
  `InputArea3Required` int NULL DEFAULT 0,
  `InputArea3ErrorMessage` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `InputArea4` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `InputArea4On` int NULL DEFAULT 0,
  `InputArea4Instructions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `InputArea4Length` int NULL DEFAULT 20,
  `InputArea4MaxCharacters` int NULL DEFAULT 255,
  `InputArea4NumLines` int NULL DEFAULT 4,
  `InputArea4Required` int NULL DEFAULT 0,
  `InputArea4ErrorMessage` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `InputArea5` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `InputArea5On` int NULL DEFAULT 0,
  `InputArea5Instructions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `InputArea5Length` int NULL DEFAULT 20,
  `InputArea5MaxCharacters` int NULL DEFAULT 255,
  `InputArea5NumLines` int NULL DEFAULT 4,
  `InputArea5Required` int NULL DEFAULT 0,
  `InputArea5ErrorMessage` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_text1` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_text2` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_text3` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_text4` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_text5` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_text6` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_text7` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_text8` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_text9` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_text10` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_vc1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sitestorepro_upgrade_vc2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sitestorepro_upgrade_vc3` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sitestorepro_upgrade_vc4` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sitestorepro_upgrade_vc5` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sitestorepro_upgrade_vc6` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sitestorepro_upgrade_vc7` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sitestorepro_upgrade_vc8` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sitestorepro_upgrade_vc9` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sitestorepro_upgrade_vc10` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sitestorepro_upgrade_num1` int NULL DEFAULT 0,
  `sitestorepro_upgrade_num2` int NULL DEFAULT 0,
  `sitestorepro_upgrade_num3` int NULL DEFAULT 0,
  `sitestorepro_upgrade_num4` int NULL DEFAULT 0,
  `sitestorepro_upgrade_num5` int NULL DEFAULT 0,
  `sitestorepro_upgrade_num6` int NULL DEFAULT 0,
  `sitestorepro_upgrade_num7` int NULL DEFAULT 0,
  `sitestorepro_upgrade_num8` int NULL DEFAULT 0,
  `sitestorepro_upgrade_num9` int NULL DEFAULT 0,
  `sitestorepro_upgrade_num10` int NULL DEFAULT 0,
  `sitestorepro_upgrade_fl1` double NULL DEFAULT NULL,
  `sitestorepro_upgrade_fl2` double NULL DEFAULT NULL,
  `sitestorepro_upgrade_fl3` double NULL DEFAULT NULL,
  `sitestorepro_upgrade_fl4` double NULL DEFAULT NULL,
  `sitestorepro_upgrade_fl5` double NULL DEFAULT NULL,
  `sitestorepro_upgrade_fl6` double NULL DEFAULT NULL,
  `sitestorepro_upgrade_fl7` double NULL DEFAULT NULL,
  `sitestorepro_upgrade_fl8` double NULL DEFAULT NULL,
  `sitestorepro_upgrade_fl9` double NULL DEFAULT NULL,
  `sitestorepro_upgrade_fl10` double NULL DEFAULT NULL,
  `sitestorepro_upgrade_ln1` bigint NULL DEFAULT NULL,
  `sitestorepro_upgrade_ln2` bigint NULL DEFAULT NULL,
  `sitestorepro_upgrade_ln3` bigint NULL DEFAULT NULL,
  `sitestorepro_upgrade_ln4` bigint NULL DEFAULT NULL,
  `sitestorepro_upgrade_ln5` bigint NULL DEFAULT NULL,
  `sitestorepro_upgrade_date1` datetime NULL DEFAULT NULL,
  `sitestorepro_upgrade_date2` datetime NULL DEFAULT NULL,
  `sitestorepro_upgrade_date3` datetime NULL DEFAULT NULL,
  `sitestorepro_upgrade_date4` datetime NULL DEFAULT NULL,
  `sitestorepro_upgrade_date5` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`ProdInputFieldID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 51 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sitestorepro_prod_input_fields
-- ----------------------------
INSERT INTO `sitestorepro_prod_input_fields` VALUES (5, 1000015, 'InputField 1', 0, 'InputField 1 Instructions', 50, 255, 0, 'Input Field 1 Is Required', 'InputField 2', 0, 'InputField 2 Instructions', 50, 255, 0, 'Input Field 2 Is Required', 'InputField 3', 0, 'InputField 3 Instructions', 50, 255, 0, 'Input Field 3 Is Required', 'InputField 4', 0, 'InputField 4 Instructions', 50, 255, 0, 'Input Field 4 Is Required', 'InputField 5', 0, 'InputField 5 Instructions', 50, 255, 0, 'Input Field 5 Is Required', 'InputArea 1', 0, 'InputArea 1 Instructions', 30, 255, 4, 0, 'Input Area 1 Is Required', 'InputArea 2', 0, 'InputArea 2 Instructions', 30, 255, 4, 0, 'Input Area 2 Is Required', 'InputArea 3', 0, 'InputArea 3 Instructions', 30, 255, 4, 0, 'Input Area 3 Is Required', 'InputArea 4', 0, 'InputArea 4 Instructions', 30, 255, 4, 0, 'Input Area 4 Is Required', 'InputArea 5', 0, 'InputArea 5 Instructions', 30, 255, 4, 0, 'Input Area 5 Is Required', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_input_fields` VALUES (6, 1000014, 'InputField 1', 0, 'InputField 1 Instructions', 30, 255, 0, 'Input Field 1 Is Required', 'InputField 2', 0, 'InputField 2 Instructions', 30, 255, 0, 'Input Field 2 Is Required', 'InputField 3', 0, 'InputField 3 Instructions', 30, 255, 0, 'Input Field 3 Is Required', 'InputField 4', 0, 'InputField 4 Instructions', 30, 255, 0, 'Input Field 4 Is Required', 'InputField 5', 0, 'InputField 5 Instructions', 30, 255, 0, 'Input Field 5 Is Required', 'InputArea 1', 0, 'InputArea 1 Instructions', 20, 255, 4, 0, 'Input Area 1 Is Required', 'InputArea 2', 0, 'InputArea 2 Instructions', 20, 255, 4, 0, 'Input Area 2 Is Required', 'InputArea 3', 0, 'InputArea 3 Instructions', 20, 255, 4, 0, 'Input Area 3 Is Required', 'InputArea 4', 0, 'InputArea 4 Instructions', 20, 255, 4, 0, 'Input Area 4 Is Required', 'InputArea 5', 0, 'InputArea 5 Instructions', 20, 255, 4, 0, 'Input Area 5 Is Required', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_input_fields` VALUES (7, 1000005, 'InputField 1', 0, 'InputField 1 Instructions', 30, 255, 0, 'Input Field 1 Is Required', 'InputField 2', 0, 'InputField 2 Instructions', 30, 255, 0, 'Input Field 2 Is Required', 'InputField 3', 0, 'InputField 3 Instructions', 30, 255, 0, 'Input Field 3 Is Required', 'InputField 4', 0, 'InputField 4 Instructions', 30, 255, 0, 'Input Field 4 Is Required', 'InputField 5', 0, 'InputField 5 Instructions', 30, 255, 0, 'Input Field 5 Is Required', 'InputArea 1', 0, 'InputArea 1 Instructions', 20, 255, 4, 0, 'Input Area 1 Is Required', 'InputArea 2', 0, 'InputArea 2 Instructions', 20, 255, 4, 0, 'Input Area 2 Is Required', 'InputArea 3', 0, 'InputArea 3 Instructions', 20, 255, 4, 0, 'Input Area 3 Is Required', 'InputArea 4', 0, 'InputArea 4 Instructions', 20, 255, 4, 0, 'Input Area 4 Is Required', 'InputArea 5', 0, 'InputArea 5 Instructions', 20, 255, 4, 0, 'Input Area 5 Is Required', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_input_fields` VALUES (8, 1000017, 'InputField 1', 0, 'InputField 1 Instructions', 30, 255, 0, 'Input Field 1 Is Required', 'InputField 2', 0, 'InputField 2 Instructions', 30, 255, 0, 'Input Field 2 Is Required', 'InputField 3', 0, 'InputField 3 Instructions', 30, 255, 0, 'Input Field 3 Is Required', 'InputField 4', 0, 'InputField 4 Instructions', 30, 255, 0, 'Input Field 4 Is Required', 'InputField 5', 0, 'InputField 5 Instructions', 30, 255, 0, 'Input Field 5 Is Required', 'InputArea 1', 0, 'InputArea 1 Instructions', 20, 255, 4, 0, 'Input Area 1 Is Required', 'InputArea 2', 0, 'InputArea 2 Instructions', 20, 255, 4, 0, 'Input Area 2 Is Required', 'InputArea 3', 0, 'InputArea 3 Instructions', 20, 255, 4, 0, 'Input Area 3 Is Required', 'InputArea 4', 0, 'InputArea 4 Instructions', 20, 255, 4, 0, 'Input Area 4 Is Required', 'InputArea 5', 0, 'InputArea 5 Instructions', 20, 255, 4, 0, 'Input Area 5 Is Required', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_input_fields` VALUES (9, 1000013, 'InputField 1', 0, 'InputField 1 Instructions', 30, 255, 0, 'Input Field 1 Is Required', 'InputField 2', 0, 'InputField 2 Instructions', 30, 255, 0, 'Input Field 2 Is Required', 'InputField 3', 0, 'InputField 3 Instructions', 30, 255, 0, 'Input Field 3 Is Required', 'InputField 4', 0, 'InputField 4 Instructions', 30, 255, 0, 'Input Field 4 Is Required', 'InputField 5', 0, 'InputField 5 Instructions', 30, 255, 0, 'Input Field 5 Is Required', 'InputArea 1', 0, 'InputArea 1 Instructions', 20, 255, 4, 0, 'Input Area 1 Is Required', 'InputArea 2', 0, 'InputArea 2 Instructions', 20, 255, 4, 0, 'Input Area 2 Is Required', 'InputArea 3', 0, 'InputArea 3 Instructions', 20, 255, 4, 0, 'Input Area 3 Is Required', 'InputArea 4', 0, 'InputArea 4 Instructions', 20, 255, 4, 0, 'Input Area 4 Is Required', 'InputArea 5', 0, 'InputArea 5 Instructions', 20, 255, 4, 0, 'Input Area 5 Is Required', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_input_fields` VALUES (10, 1000006, 'InputField 1', 0, 'InputField 1 Instructions', 30, 255, 0, 'Input Field 1 Is Required', 'InputField 2', 0, 'InputField 2 Instructions', 30, 255, 0, 'Input Field 2 Is Required', 'InputField 3', 0, 'InputField 3 Instructions', 30, 255, 0, 'Input Field 3 Is Required', 'InputField 4', 0, 'InputField 4 Instructions', 30, 255, 0, 'Input Field 4 Is Required', 'InputField 5', 0, 'InputField 5 Instructions', 30, 255, 0, 'Input Field 5 Is Required', 'InputArea 1', 0, 'InputArea 1 Instructions', 20, 255, 4, 0, 'Input Area 1 Is Required', 'InputArea 2', 0, 'InputArea 2 Instructions', 20, 255, 4, 0, 'Input Area 2 Is Required', 'InputArea 3', 0, 'InputArea 3 Instructions', 20, 255, 4, 0, 'Input Area 3 Is Required', 'InputArea 4', 0, 'InputArea 4 Instructions', 20, 255, 4, 0, 'Input Area 4 Is Required', 'InputArea 5', 0, 'InputArea 5 Instructions', 20, 255, 4, 0, 'Input Area 5 Is Required', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_input_fields` VALUES (11, 1000001, 'InputField 1', 0, 'InputField 1 Instructions', 30, 255, 0, 'Input Field 1 Is Required', 'InputField 2', 0, 'InputField 2 Instructions', 30, 255, 0, 'Input Field 2 Is Required', 'InputField 3', 0, 'InputField 3 Instructions', 30, 255, 0, 'Input Field 3 Is Required', 'InputField 4', 0, 'InputField 4 Instructions', 30, 255, 0, 'Input Field 4 Is Required', 'InputField 5', 0, 'InputField 5 Instructions', 30, 255, 0, 'Input Field 5 Is Required', 'InputArea 1', 0, 'InputArea 1 Instructions', 20, 255, 4, 0, 'Input Area 1 Is Required', 'InputArea 2', 0, 'InputArea 2 Instructions', 20, 255, 4, 0, 'Input Area 2 Is Required', 'InputArea 3', 0, 'InputArea 3 Instructions', 20, 255, 4, 0, 'Input Area 3 Is Required', 'InputArea 4', 0, 'InputArea 4 Instructions', 20, 255, 4, 0, 'Input Area 4 Is Required', 'InputArea 5', 0, 'InputArea 5 Instructions', 20, 255, 4, 0, 'Input Area 5 Is Required', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_input_fields` VALUES (12, 1000004, 'InputField 1', 0, 'InputField 1 Instructions', 30, 255, 0, 'Input Field 1 Is Required', 'InputField 2', 0, 'InputField 2 Instructions', 30, 255, 0, 'Input Field 2 Is Required', 'InputField 3', 0, 'InputField 3 Instructions', 30, 255, 0, 'Input Field 3 Is Required', 'InputField 4', 0, 'InputField 4 Instructions', 30, 255, 0, 'Input Field 4 Is Required', 'InputField 5', 0, 'InputField 5 Instructions', 30, 255, 0, 'Input Field 5 Is Required', 'InputArea 1', 0, 'InputArea 1 Instructions', 20, 255, 4, 0, 'Input Area 1 Is Required', 'InputArea 2', 0, 'InputArea 2 Instructions', 20, 255, 4, 0, 'Input Area 2 Is Required', 'InputArea 3', 0, 'InputArea 3 Instructions', 20, 255, 4, 0, 'Input Area 3 Is Required', 'InputArea 4', 0, 'InputArea 4 Instructions', 20, 255, 4, 0, 'Input Area 4 Is Required', 'InputArea 5', 0, 'InputArea 5 Instructions', 20, 255, 4, 0, 'Input Area 5 Is Required', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_input_fields` VALUES (14, 1000003, 'InputField 1', 0, 'InputField 1 Instructions', 30, 255, 0, 'Input Field 1 Is Required', 'InputField 2', 0, 'InputField 2 Instructions', 30, 255, 0, 'Input Field 2 Is Required', 'InputField 3', 0, 'InputField 3 Instructions', 30, 255, 0, 'Input Field 3 Is Required', 'InputField 4', 0, 'InputField 4 Instructions', 30, 255, 0, 'Input Field 4 Is Required', 'InputField 5', 0, 'InputField 5 Instructions', 30, 255, 0, 'Input Field 5 Is Required', 'InputArea 1', 0, 'InputArea 1 Instructions', 20, 255, 4, 0, 'Input Area 1 Is Required', 'InputArea 2', 0, 'InputArea 2 Instructions', 20, 255, 4, 0, 'Input Area 2 Is Required', 'InputArea 3', 0, 'InputArea 3 Instructions', 20, 255, 4, 0, 'Input Area 3 Is Required', 'InputArea 4', 0, 'InputArea 4 Instructions', 20, 255, 4, 0, 'Input Area 4 Is Required', 'InputArea 5', 0, 'InputArea 5 Instructions', 20, 255, 4, 0, 'Input Area 5 Is Required', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_input_fields` VALUES (17, 1000002, 'InputField 1', 0, 'InputField 1 Instructions', 30, 255, 0, 'Input Field 1 Is Required', 'InputField 2', 0, 'InputField 2 Instructions', 30, 255, 0, 'Input Field 2 Is Required', 'InputField 3', 0, 'InputField 3 Instructions', 30, 255, 0, 'Input Field 3 Is Required', 'InputField 4', 0, 'InputField 4 Instructions', 30, 255, 0, 'Input Field 4 Is Required', 'InputField 5', 0, 'InputField 5 Instructions', 30, 255, 0, 'Input Field 5 Is Required', 'InputArea 1', 0, 'InputArea 1 Instructions', 20, 255, 4, 0, 'Input Area 1 Is Required', 'InputArea 2', 0, 'InputArea 2 Instructions', 20, 255, 4, 0, 'Input Area 2 Is Required', 'InputArea 3', 0, 'InputArea 3 Instructions', 20, 255, 4, 0, 'Input Area 3 Is Required', 'InputArea 4', 0, 'InputArea 4 Instructions', 20, 255, 4, 0, 'Input Area 4 Is Required', 'InputArea 5', 0, 'InputArea 5 Instructions', 20, 255, 4, 0, 'Input Area 5 Is Required', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_input_fields` VALUES (18, 1000007, 'InputField 1', 0, 'InputField 1 Instructions', 30, 255, 0, 'Input Field 1 Is Required', 'InputField 2', 0, 'InputField 2 Instructions', 30, 255, 0, 'Input Field 2 Is Required', 'InputField 3', 0, 'InputField 3 Instructions', 30, 255, 0, 'Input Field 3 Is Required', 'InputField 4', 0, 'InputField 4 Instructions', 30, 255, 0, 'Input Field 4 Is Required', 'InputField 5', 0, 'InputField 5 Instructions', 30, 255, 0, 'Input Field 5 Is Required', 'InputArea 1', 0, 'InputArea 1 Instructions', 20, 255, 4, 0, 'Input Area 1 Is Required', 'InputArea 2', 0, 'InputArea 2 Instructions', 20, 255, 4, 0, 'Input Area 2 Is Required', 'InputArea 3', 0, 'InputArea 3 Instructions', 20, 255, 4, 0, 'Input Area 3 Is Required', 'InputArea 4', 0, 'InputArea 4 Instructions', 20, 255, 4, 0, 'Input Area 4 Is Required', 'InputArea 5', 0, 'InputArea 5 Instructions', 20, 255, 4, 0, 'Input Area 5 Is Required', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_input_fields` VALUES (19, 1000008, 'InputField 1', 0, 'InputField 1 Instructions', 30, 255, 0, 'Input Field 1 Is Required', 'InputField 2', 0, 'InputField 2 Instructions', 30, 255, 0, 'Input Field 2 Is Required', 'InputField 3', 0, 'InputField 3 Instructions', 30, 255, 0, 'Input Field 3 Is Required', 'InputField 4', 0, 'InputField 4 Instructions', 30, 255, 0, 'Input Field 4 Is Required', 'InputField 5', 0, 'InputField 5 Instructions', 30, 255, 0, 'Input Field 5 Is Required', 'InputArea 1', 0, 'InputArea 1 Instructions', 20, 255, 4, 0, 'Input Area 1 Is Required', 'InputArea 2', 0, 'InputArea 2 Instructions', 20, 255, 4, 0, 'Input Area 2 Is Required', 'InputArea 3', 0, 'InputArea 3 Instructions', 20, 255, 4, 0, 'Input Area 3 Is Required', 'InputArea 4', 0, 'InputArea 4 Instructions', 20, 255, 4, 0, 'Input Area 4 Is Required', 'InputArea 5', 0, 'InputArea 5 Instructions', 20, 255, 4, 0, 'Input Area 5 Is Required', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_input_fields` VALUES (20, 1000009, 'InputField 1', 0, 'InputField 1 Instructions', 30, 255, 0, 'Input Field 1 Is Required', 'InputField 2', 0, 'InputField 2 Instructions', 30, 255, 0, 'Input Field 2 Is Required', 'InputField 3', 0, 'InputField 3 Instructions', 30, 255, 0, 'Input Field 3 Is Required', 'InputField 4', 0, 'InputField 4 Instructions', 30, 255, 0, 'Input Field 4 Is Required', 'InputField 5', 0, 'InputField 5 Instructions', 30, 255, 0, 'Input Field 5 Is Required', 'InputArea 1', 0, 'InputArea 1 Instructions', 20, 255, 4, 0, 'Input Area 1 Is Required', 'InputArea 2', 0, 'InputArea 2 Instructions', 20, 255, 4, 0, 'Input Area 2 Is Required', 'InputArea 3', 0, 'InputArea 3 Instructions', 20, 255, 4, 0, 'Input Area 3 Is Required', 'InputArea 4', 0, 'InputArea 4 Instructions', 20, 255, 4, 0, 'Input Area 4 Is Required', 'InputArea 5', 0, 'InputArea 5 Instructions', 20, 255, 4, 0, 'Input Area 5 Is Required', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_input_fields` VALUES (21, 1000010, 'InputField 1', 0, 'InputField 1 Instructions', 30, 255, 0, 'Input Field 1 Is Required', 'InputField 2', 0, 'InputField 2 Instructions', 30, 255, 0, 'Input Field 2 Is Required', 'InputField 3', 0, 'InputField 3 Instructions', 30, 255, 0, 'Input Field 3 Is Required', 'InputField 4', 0, 'InputField 4 Instructions', 30, 255, 0, 'Input Field 4 Is Required', 'InputField 5', 0, 'InputField 5 Instructions', 30, 255, 0, 'Input Field 5 Is Required', 'InputArea 1', 0, 'InputArea 1 Instructions', 20, 255, 4, 0, 'Input Area 1 Is Required', 'InputArea 2', 0, 'InputArea 2 Instructions', 20, 255, 4, 0, 'Input Area 2 Is Required', 'InputArea 3', 0, 'InputArea 3 Instructions', 20, 255, 4, 0, 'Input Area 3 Is Required', 'InputArea 4', 0, 'InputArea 4 Instructions', 20, 255, 4, 0, 'Input Area 4 Is Required', 'InputArea 5', 0, 'InputArea 5 Instructions', 20, 255, 4, 0, 'Input Area 5 Is Required', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_input_fields` VALUES (22, 1000011, 'InputField 1', 0, 'InputField 1 Instructions', 30, 255, 0, 'Input Field 1 Is Required', 'InputField 2', 0, 'InputField 2 Instructions', 30, 255, 0, 'Input Field 2 Is Required', 'InputField 3', 0, 'InputField 3 Instructions', 30, 255, 0, 'Input Field 3 Is Required', 'InputField 4', 0, 'InputField 4 Instructions', 30, 255, 0, 'Input Field 4 Is Required', 'InputField 5', 0, 'InputField 5 Instructions', 30, 255, 0, 'Input Field 5 Is Required', 'InputArea 1', 0, 'InputArea 1 Instructions', 20, 255, 4, 0, 'Input Area 1 Is Required', 'InputArea 2', 0, 'InputArea 2 Instructions', 20, 255, 4, 0, 'Input Area 2 Is Required', 'InputArea 3', 0, 'InputArea 3 Instructions', 20, 255, 4, 0, 'Input Area 3 Is Required', 'InputArea 4', 0, 'InputArea 4 Instructions', 20, 255, 4, 0, 'Input Area 4 Is Required', 'InputArea 5', 0, 'InputArea 5 Instructions', 20, 255, 4, 0, 'Input Area 5 Is Required', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_input_fields` VALUES (23, 1000012, 'InputField 1', 0, 'InputField 1 Instructions', 30, 255, 0, 'Input Field 1 Is Required', 'InputField 2', 0, 'InputField 2 Instructions', 30, 255, 0, 'Input Field 2 Is Required', 'InputField 3', 0, 'InputField 3 Instructions', 30, 255, 0, 'Input Field 3 Is Required', 'InputField 4', 0, 'InputField 4 Instructions', 30, 255, 0, 'Input Field 4 Is Required', 'InputField 5', 0, 'InputField 5 Instructions', 30, 255, 0, 'Input Field 5 Is Required', 'InputArea 1', 0, 'InputArea 1 Instructions', 20, 255, 4, 0, 'Input Area 1 Is Required', 'InputArea 2', 0, 'InputArea 2 Instructions', 20, 255, 4, 0, 'Input Area 2 Is Required', 'InputArea 3', 0, 'InputArea 3 Instructions', 20, 255, 4, 0, 'Input Area 3 Is Required', 'InputArea 4', 0, 'InputArea 4 Instructions', 20, 255, 4, 0, 'Input Area 4 Is Required', 'InputArea 5', 0, 'InputArea 5 Instructions', 20, 255, 4, 0, 'Input Area 5 Is Required', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_input_fields` VALUES (24, 1000016, 'InputField 1', 0, 'InputField 1 Instructions', 30, 255, 0, 'Input Field 1 Is Required', 'InputField 2', 0, 'InputField 2 Instructions', 30, 255, 0, 'Input Field 2 Is Required', 'InputField 3', 0, 'InputField 3 Instructions', 30, 255, 0, 'Input Field 3 Is Required', 'InputField 4', 0, 'InputField 4 Instructions', 30, 255, 0, 'Input Field 4 Is Required', 'InputField 5', 0, 'InputField 5 Instructions', 30, 255, 0, 'Input Field 5 Is Required', 'InputArea 1', 0, 'InputArea 1 Instructions', 20, 255, 4, 0, 'Input Area 1 Is Required', 'InputArea 2', 0, 'InputArea 2 Instructions', 20, 255, 4, 0, 'Input Area 2 Is Required', 'InputArea 3', 0, 'InputArea 3 Instructions', 20, 255, 4, 0, 'Input Area 3 Is Required', 'InputArea 4', 0, 'InputArea 4 Instructions', 20, 255, 4, 0, 'Input Area 4 Is Required', 'InputArea 5', 0, 'InputArea 5 Instructions', 20, 255, 4, 0, 'Input Area 5 Is Required', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_input_fields` VALUES (25, 1000018, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_input_fields` VALUES (26, 1000019, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_input_fields` VALUES (27, 1000020, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_input_fields` VALUES (28, 1000021, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_input_fields` VALUES (29, 1000022, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_input_fields` VALUES (30, 1000023, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_input_fields` VALUES (31, 1000024, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_input_fields` VALUES (32, 1000025, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_input_fields` VALUES (33, 1000026, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_input_fields` VALUES (34, 1000027, 'InputField 1', 0, 'InputField 1 Instructions', 50, 255, 0, 'Input Field 1 Is Required', 'InputField 2', 0, 'InputField 2 Instructions', 50, 255, 0, 'Input Field 2 Is Required', 'InputField 3', 0, 'InputField 3 Instructions', 50, 255, 0, 'Input Field 3 Is Required', 'InputField 4', 0, 'InputField 4 Instructions', 50, 255, 0, 'Input Field 4 Is Required', 'InputField 5', 0, 'InputField 5 Instructions', 50, 255, 0, 'Input Field 5 Is Required', 'InputArea 1', 0, 'InputArea 1 Instructions', 30, 255, 4, 0, 'Input Area 1 Is Required', 'InputArea 2', 0, 'InputArea 2 Instructions', 30, 255, 4, 0, 'Input Area 2 Is Required', 'InputArea 3', 0, 'InputArea 3 Instructions', 30, 255, 4, 0, 'Input Area 3 Is Required', 'InputArea 4', 0, 'InputArea 4 Instructions', 30, 255, 4, 0, 'Input Area 4 Is Required', 'InputArea 5', 0, 'InputArea 5 Instructions', 30, 255, 4, 0, 'Input Area 5 Is Required', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_input_fields` VALUES (35, 1000028, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_input_fields` VALUES (36, 1000029, 'Input Field 1 :', 0, NULL, 30, 255, 0, 'Input Field 1 Required', 'Input Field 2 :', 0, NULL, 30, 255, 0, 'Input Field 2 Required', 'Input Field 3 :', 0, NULL, 30, 255, 0, 'Input Field 3 Required', 'Input Field 4 :', 0, NULL, 30, 255, 0, 'Input Field 4 Required', 'Input Field 5 :', 0, NULL, 30, 255, 0, 'Input Field 5 Required', 'Input Area 1:', 0, NULL, 20, 255, 4, 0, 'Input Area 1 Required', 'Input Area 2:', 0, NULL, 20, 255, 4, 0, 'Input Area 2 Required', 'Input Area 3:', 0, NULL, 20, 255, 4, 0, 'Input Area 3 Required', 'Input Area 4:', 0, NULL, 20, 255, 4, 0, 'Input Area 4 Required', 'Input Area 5:', 0, NULL, 20, 255, 4, 0, 'Input Area 5 Required', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_input_fields` VALUES (37, 1000030, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_input_fields` VALUES (38, 1000031, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_input_fields` VALUES (39, 1000032, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_input_fields` VALUES (40, 1000033, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_input_fields` VALUES (41, 1000034, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_input_fields` VALUES (42, 1000035, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_input_fields` VALUES (43, 1000036, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_input_fields` VALUES (44, 1000037, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_input_fields` VALUES (45, 1000038, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 30, 255, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, 0, NULL, 20, 255, 4, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- ----------------------------
-- Table structure for sitestorepro_prod_materials
-- ----------------------------
DROP TABLE IF EXISTS `sitestorepro_prod_materials`;
CREATE TABLE `sitestorepro_prod_materials`  (
  `MaterialID` int NOT NULL AUTO_INCREMENT,
  `MaterialName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `SearchMenuLabel` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `SearchResultsTitle` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `IncludeInMenu` int NULL DEFAULT 1,
  `MaterialFee` double NULL DEFAULT 0,
  `MaterialImage` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `MenuOrdering` double NULL DEFAULT 0,
  `IncludeOnDetailsPage` int NULL DEFAULT 0,
  `StoreID` int NULL DEFAULT 1,
  `sitestorepro_upgrade_text1` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_text2` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_text3` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_vc1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sitestorepro_upgrade_vc2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sitestorepro_upgrade_num1` int NULL DEFAULT NULL,
  `sitestorepro_upgrade_num2` int NULL DEFAULT NULL,
  `sitestorepro_upgrade_num3` int NULL DEFAULT NULL,
  `sitestorepro_upgrade_num4` int NULL DEFAULT NULL,
  `sitestorepro_upgrade_num5` int NULL DEFAULT NULL,
  `sitestorepro_upgrade_fl1` double NULL DEFAULT NULL,
  `sitestorepro_upgrade_fl2` double NULL DEFAULT NULL,
  `sitestorepro_upgrade_fl3` double NULL DEFAULT NULL,
  `sitestorepro_upgrade_ln1` bigint NULL DEFAULT NULL,
  `sitestorepro_upgrade_ln2` bigint NULL DEFAULT NULL,
  `sitestorepro_upgrade_date1` datetime NULL DEFAULT NULL,
  `sitestorepro_upgrade_date2` datetime NULL DEFAULT NULL,
  `sitestorepro_upgrade_date3` datetime NULL DEFAULT NULL,
  `sitestorepro_upgrade_date4` datetime NULL DEFAULT NULL,
  `sitestorepro_upgrade_image1` longblob NULL,
  PRIMARY KEY (`MaterialID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sitestorepro_prod_materials
-- ----------------------------
INSERT INTO `sitestorepro_prod_materials` VALUES (5, '14 K Gold', '14 K Gold Rings', '14 K Gold Search Results', 1, 0, NULL, 0, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_materials` VALUES (6, '24 K Gold (+$25.00)', '24 K Gold Rings', '24K Gold Rings Search Results', 1, 25, NULL, 0, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_materials` VALUES (7, 'Platinum (+$35.00)', 'Platinum Rings', 'Platinum Rings Search Results', 1, 35, NULL, 0, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- ----------------------------
-- Table structure for sitestorepro_prod_materials_assoc
-- ----------------------------
DROP TABLE IF EXISTS `sitestorepro_prod_materials_assoc`;
CREATE TABLE `sitestorepro_prod_materials_assoc`  (
  `materialsassociation` int NOT NULL AUTO_INCREMENT,
  `materialid` int NULL DEFAULT NULL,
  `ProdID` int NULL DEFAULT NULL,
  `menuordering` double NULL DEFAULT 0,
  `use_custom` int NULL DEFAULT 0,
  `custom_retail_label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `custom_retail_fee` double NULL DEFAULT 0,
  `custom_wholesale_label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `custom_wholesale_fee` double NULL DEFAULT 0,
  `custom_sales_tax` int NULL DEFAULT 0,
  `custom_weight` double NULL DEFAULT 0,
  PRIMARY KEY (`materialsassociation`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 106 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sitestorepro_prod_materials_assoc
-- ----------------------------
INSERT INTO `sitestorepro_prod_materials_assoc` VALUES (92, NULL, 1000026, 0, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_materials_assoc` VALUES (93, NULL, 1000027, 0, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_materials_assoc` VALUES (96, 5, 1000002, 0, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_materials_assoc` VALUES (97, 6, 1000002, 0, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_materials_assoc` VALUES (98, 7, 1000002, 0, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_materials_assoc` VALUES (99, NULL, 1000032, 0, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_materials_assoc` VALUES (100, NULL, 1000033, 0, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_materials_assoc` VALUES (101, NULL, 1000034, 0, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_materials_assoc` VALUES (102, NULL, 1000035, 0, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_materials_assoc` VALUES (103, NULL, 1000036, 0, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_materials_assoc` VALUES (104, NULL, 1000037, 0, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_materials_assoc` VALUES (105, NULL, 1000038, 0, 0, NULL, 0, NULL, 0, 0, 0);

-- ----------------------------
-- Table structure for sitestorepro_prod_options_custom_assoc
-- ----------------------------
DROP TABLE IF EXISTS `sitestorepro_prod_options_custom_assoc`;
CREATE TABLE `sitestorepro_prod_options_custom_assoc`  (
  `customassocId` int NOT NULL AUTO_INCREMENT,
  `ProdID` int NULL DEFAULT 0,
  `optiontype` int NULL DEFAULT 0,
  `optionid` int NULL DEFAULT 0,
  `retail_label` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `retail_fee` float NULL DEFAULT NULL,
  `wholesale_label` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `wholesale_fee` float NULL DEFAULT NULL,
  `charge_tax` int NULL DEFAULT NULL,
  `option_weight` float NULL DEFAULT NULL,
  `selection_image` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  PRIMARY KEY (`customassocId`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 35 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sitestorepro_prod_options_custom_assoc
-- ----------------------------
INSERT INTO `sitestorepro_prod_options_custom_assoc` VALUES (8, 1000025, 2, 55, 'Brown', 0, 'Brown', 0, 1, 0, 'tee_brown.jpg');
INSERT INTO `sitestorepro_prod_options_custom_assoc` VALUES (12, 1000025, 2, 60, 'Royal Blue', 0, 'Royal Blue', 0, 1, 0, 'tee_royal.jpg');
INSERT INTO `sitestorepro_prod_options_custom_assoc` VALUES (13, 1000025, 2, 57, 'Green', 0, 'Green', 0, 1, 0, 'tee_greeen.jpg');
INSERT INTO `sitestorepro_prod_options_custom_assoc` VALUES (14, 1000025, 2, 59, 'Grey', 0, 'Grey', 0, 1, 0, 'tee_grey.jpg');
INSERT INTO `sitestorepro_prod_options_custom_assoc` VALUES (15, 1000025, 2, 56, 'Orange', 0, 'Orange', 0, 1, 0, 'tee_orange.jpg');
INSERT INTO `sitestorepro_prod_options_custom_assoc` VALUES (16, 1000025, 2, 58, 'Navy', 0, 'Navy', 0, 1, 0, 'tee_navy.jpg');
INSERT INTO `sitestorepro_prod_options_custom_assoc` VALUES (17, 1000025, 1, 137, 'XXL (+$3.00)', 3, 'XXL (+2.00)', 1, 0, 0, NULL);
INSERT INTO `sitestorepro_prod_options_custom_assoc` VALUES (18, 1000026, 2, 55, 'Brown', 0, 'Brown', 0, 1, 0, 'tee_brown.jpg');
INSERT INTO `sitestorepro_prod_options_custom_assoc` VALUES (19, 1000026, 2, 60, 'Royal Blue', 0, 'Royal Blue', 0, 1, 0, 'tee_royal.jpg');
INSERT INTO `sitestorepro_prod_options_custom_assoc` VALUES (20, 1000026, 2, 57, 'Green', 0, 'Green', 0, 1, 0, 'tee_greeen.jpg');
INSERT INTO `sitestorepro_prod_options_custom_assoc` VALUES (21, 1000026, 2, 59, 'Grey', 0, 'Grey', 0, 1, 0, 'tee_grey.jpg');
INSERT INTO `sitestorepro_prod_options_custom_assoc` VALUES (22, 1000026, 2, 56, 'Orange', 0, 'Orange', 0, 1, 0, 'tee_orange.jpg');
INSERT INTO `sitestorepro_prod_options_custom_assoc` VALUES (23, 1000026, 2, 58, 'Navy', 0, 'Navy', 0, 1, 0, 'tee_navy.jpg');
INSERT INTO `sitestorepro_prod_options_custom_assoc` VALUES (25, 1000027, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_options_custom_assoc` VALUES (26, 1000015, 1, 137, 'XXL (+$3.00)', 3, 'XXL (+3.00)', 3, 1, 0.02, NULL);
INSERT INTO `sitestorepro_prod_options_custom_assoc` VALUES (27, 1000032, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_options_custom_assoc` VALUES (28, 1000033, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_options_custom_assoc` VALUES (29, 1000034, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_options_custom_assoc` VALUES (30, 1000035, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_options_custom_assoc` VALUES (31, 1000036, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_options_custom_assoc` VALUES (32, 1000037, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_options_custom_assoc` VALUES (33, 1000038, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_options_custom_assoc` VALUES (34, 1000029, 1, 134, 'Large (+$10.00)', 10, 'Large (+$10.00)', 2, 0, 0, NULL);

-- ----------------------------
-- Table structure for sitestorepro_prod_personalization
-- ----------------------------
DROP TABLE IF EXISTS `sitestorepro_prod_personalization`;
CREATE TABLE `sitestorepro_prod_personalization`  (
  `PersonalizeID` int NOT NULL AUTO_INCREMENT,
  `SelectionName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `SpecialFee` double NULL DEFAULT 0,
  `StoreID` int NULL DEFAULT 1,
  `PerImage` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `MenuOrdering` double NULL DEFAULT NULL,
  PRIMARY KEY (`PersonalizeID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sitestorepro_prod_personalization
-- ----------------------------
INSERT INTO `sitestorepro_prod_personalization` VALUES (1, 'Initials Etching (+$5.00)', 5, 1, NULL, NULL);
INSERT INTO `sitestorepro_prod_personalization` VALUES (2, 'Custom Embroidery (+$5.00)', 5, 1, NULL, NULL);
INSERT INTO `sitestorepro_prod_personalization` VALUES (3, 'Message Etching (+$10.00)', 10, 1, NULL, NULL);
INSERT INTO `sitestorepro_prod_personalization` VALUES (4, 'Name Tag/Badge Info', 0, 1, NULL, NULL);

-- ----------------------------
-- Table structure for sitestorepro_prod_review_types
-- ----------------------------
DROP TABLE IF EXISTS `sitestorepro_prod_review_types`;
CREATE TABLE `sitestorepro_prod_review_types`  (
  `RatingLevel` int NOT NULL AUTO_INCREMENT,
  `RatingText` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `RatingPic` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Ordering` double NULL DEFAULT NULL,
  `StoreID` int NULL DEFAULT 0,
  PRIMARY KEY (`RatingLevel`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sitestorepro_prod_review_types
-- ----------------------------
INSERT INTO `sitestorepro_prod_review_types` VALUES (1, 'Not Recommended ', 'review_rating1.jpg', 6, 1);
INSERT INTO `sitestorepro_prod_review_types` VALUES (2, 'Needs Improvement', 'review_rating2.jpg', 5, 1);
INSERT INTO `sitestorepro_prod_review_types` VALUES (3, 'Fair Product ', 'review_rating3.jpg', 3, 1);
INSERT INTO `sitestorepro_prod_review_types` VALUES (4, 'Good Product ', 'review_rating4.jpg', 2, 1);
INSERT INTO `sitestorepro_prod_review_types` VALUES (5, 'Excellent Product - Highly Recommended ', 'review_rating5.jpg', 1, 1);

-- ----------------------------
-- Table structure for sitestorepro_prod_reviews
-- ----------------------------
DROP TABLE IF EXISTS `sitestorepro_prod_reviews`;
CREATE TABLE `sitestorepro_prod_reviews`  (
  `ReviewID` int NOT NULL AUTO_INCREMENT,
  `ProdID` int NULL DEFAULT NULL,
  `ReviewTitle` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `ReviewerName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `ReviewDate` datetime NULL DEFAULT NULL,
  `ReviewLocation` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `TestimonialText` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `RatingLevel` int NULL DEFAULT NULL,
  `ReviewActive` int NULL DEFAULT 1,
  `StoreID` int NULL DEFAULT 1,
  `sitestorepro_upgrade_text1` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_text2` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_text3` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_vc1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sitestorepro_upgrade_vc2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sitestorepro_upgrade_num1` int NULL DEFAULT NULL,
  `sitestorepro_upgrade_num2` int NULL DEFAULT NULL,
  `sitestorepro_upgrade_num3` int NULL DEFAULT NULL,
  `sitestorepro_upgrade_num4` int NULL DEFAULT NULL,
  `sitestorepro_upgrade_num5` int NULL DEFAULT NULL,
  `sitestorepro_upgrade_fl1` double NULL DEFAULT NULL,
  `sitestorepro_upgrade_fl2` double NULL DEFAULT NULL,
  `sitestorepro_upgrade_fl3` double NULL DEFAULT NULL,
  `sitestorepro_upgrade_ln1` bigint NULL DEFAULT NULL,
  `sitestorepro_upgrade_ln2` bigint NULL DEFAULT NULL,
  `sitestorepro_upgrade_date1` datetime NULL DEFAULT NULL,
  `sitestorepro_upgrade_date2` datetime NULL DEFAULT NULL,
  `sitestorepro_upgrade_date3` datetime NULL DEFAULT NULL,
  `sitestorepro_upgrade_date4` datetime NULL DEFAULT NULL,
  `sitestorepro_upgrade_image1` longblob NULL,
  PRIMARY KEY (`ReviewID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sitestorepro_prod_reviews
-- ----------------------------
INSERT INTO `sitestorepro_prod_reviews` VALUES (1, 1000014, 'Great Resource!', 'Mike Smith', '2021-12-24 20:34:06', 'Michigan', 'I recently purchased the Cleaning Jewelry 101 Instructional eBook and couldn\'t be more pleased! As someone who loves jewelry and has a few pieces that have been passed down from generations, I was looking for an easy way to keep them looking their best. The eBook was an instant download in PDF format and contained 45 pages and 26 full-color illustrations. The instructions were very easy to follow and understand, with step-by-step directions on how to clean a variety of jewelry pieces. I would highly recommend this eBook to anyone who is looking for a quick, easy, and affordable way to keep their jewelry looking beautiful.', 5, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_reviews` VALUES (2, 1000014, 'The best solution to learn jewelry cleaning', 'Jean Paul', '2022-06-11 20:35:13', 'Paris, France', 'This eBook is an excellent resource for anyone interested in learning how to clean their jewelry like a professional. Not only is the eBook instantly downloadable, but it is also available in PDF format, making it easy to access no matter what device youâ€™re using. The eBook contains 45 pages of helpful information, plus 26 full-color illustrations. With this helpful guide, I am now more confident in my ability to clean my jewelry and keep it looking its best. I highly recommend this eBook for anyone wanting to learn how to clean their jewelry like a pro.', 4, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_reviews` VALUES (3, 1000014, 'Recommended!', 'Pam', '2022-08-24 20:36:06', 'San Diego, CA', 'The instructions are easy to follow and I feel confident that I\'m able to now keep my jewelry looking as good as new. Highly recommend this product for anyone looking to learn how to clean jewelry!', 5, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_reviews` VALUES (4, 1000014, 'Just what I needed!', 'Brett Simpson', '2023-01-06 20:37:46', 'Seattle', 'As a novice jewelry cleaner, I found this eBook extremely helpful. It provides detailed instructions on the best techniques for cleaning jewelry, and offers tips on how to make sure your jewelry lasts for years to come. For anyone looking for a comprehensive guide on how to clean jewelry, I would highly recommend Cleaning Jewelry 101 Instructional eBook. It\'s a great resource and is definitely worth the price!', 5, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_reviews` VALUES (5, 1000014, 'Wish i found this sooner.', 'Cindy Rourke', '2023-02-14 20:39:47', 'New York, NY USA', 'I recently purchased the Cleaning Jewelry 101 Instructional eBOOK and I am so glad I did! This detailed eBook teaches you how to clean jewelry, from start to finish. The instructions are written in a friendly, easy-to-understand tone and are accompanied by helpful diagrams and illustrations. I found the information to be incredibly helpful and the eBook is full of useful tips. I would highly recommend this eBook to anyone who wants to learn how to clean jewelry, it\'s definitely worth the purchase!', 5, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_reviews` VALUES (6, 1000014, 'Worked for Me', 'Amy', '2023-03-24 23:49:56', 'London UK', 'This wasn\'t what I expected for jewelry training but it worked for me anyways. Thanks for the product.', 3, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_reviews` VALUES (7, 1000014, 'Pleasantly Suprised', 'Christine Owens', '2023-03-25 23:23:34', 'Rome, Italy', 'Wasn\'t expecting much but it was a very full-featured training guide. Thanks for the great product!', 5, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_reviews` VALUES (8, 1000013, 'Wonderful Bracelet!', 'Sandy', '2023-03-07 14:55:17', 'New York, NY', 'The Sapphire, Ruby and Emerald Bracelet is an exquisite piece of jewelry that is perfect for anyone who appreciates quality and beauty.  From the careful selection of the gemstones to the precision of the setting, every detail has been carefully considered to ensure a finished product that is not only beautiful but also durable and long-lasting.In addition to its quality, the Sapphire, Ruby and Emerald Bracelet is also incredibly beautiful. The interplay of colors between the emeralds, sapphires and rubies is truly mesmerizing, and the choice of sterling silver and 18K gold accents only serves to enhance the overall aesthetic appeal of the piece.', 5, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_reviews` VALUES (9, 1000013, 'Such a great piece!', 'Ashley S.', '2023-05-15 14:58:05', 'Seattle, WA', 'The Sapphire, Ruby and Emerald Bracelet is an absolute stunner! This gorgeous piece of jewelry is expertly crafted with intricate detail and quality materials that make it truly stand out. The emeralds, sapphires, and rubies in the bracelet glisten and sparkle like nothing else.Made with sterling silver and 18K gold accents, the bracelet measures 7 1/4\" and features a unique diamond accent. The combination of these top-notch materials gives the bracelet a luxurious feel without being too heavy or overwhelming.Overall, the Sapphire, Ruby and Emerald Bracelet is a beautiful addition to any jewelry collection. The quality craftsmanship and stunning design are sure to turn heads and leave a lasting impression. Only reason for 4 stars and not 5 is that it is a little on the expensive side.', 4, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_reviews` VALUES (10, 1000013, 'Makes a great gift', 'Barbara Worthington', '2023-06-04 15:00:01', 'Denver, Colorado', 'Looking for a quality, beautiful piece of jewelry that will make you stand out from the crowd? Look no further than the Sapphire, Ruby And Emerald Bracelet. This stunning piece is expertly crafted with intricate attention to detail, and features a breathtaking combination of emeralds, sapphires, and rubies set in sterling silver with 18K gold and diamond accents. At 7 1/4\" in length, it\'s the perfect size for any wrist.Whether you\'re dressing up for a special occasion or just looking to add a bold statement piece to your everyday wardrobe, the Sapphire, Ruby And Emerald Bracelet is sure to impress. So why wait? Treat yourself today and experience the beauty and quality of this must-have accessory.', 5, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- ----------------------------
-- Table structure for sitestorepro_prod_reviews_config
-- ----------------------------
DROP TABLE IF EXISTS `sitestorepro_prod_reviews_config`;
CREATE TABLE `sitestorepro_prod_reviews_config`  (
  `ConfigID` int NOT NULL AUTO_INCREMENT,
  `StoreID` int NULL DEFAULT 1,
  `HeaderText` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `MainTab` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `MostRecentLable` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `AllReviewsTab` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `AllReviewsLabel` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `AddReviewTab` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `NoReviewsText` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `FormInstructions` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `NameFieldLabel` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `NameInstructions` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `NameFieldError` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `ProductRatingLabel` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `RatingInstructions` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `RatingLevel5Text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `RatingLevel4Text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `RatingLevel3Text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `RatingLevel2Text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `RatingLevel1Text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `RatingSelectionError` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `TitleOfReviewLabel` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `TitleIntructions` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `TitleError` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `CommentsLabel` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `CommentsInstructions` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `CommentsError` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `LocationLabel` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `LocationInstructions` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `LocationError` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `FormVerifyLabel` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `FormVerifyError` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `ButtonLabel` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `ResubmitLabel` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `SuccessHeader` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `SuccessMessage` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `SuccessWindowClose` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `ErrorMessage` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `SendReviewSubmitEmail` int NULL DEFAULT 0,
  `Recipient_email` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `BCC_email` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `message_subject` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `message_body` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_num1` int NULL DEFAULT NULL,
  `sitestorepro_upgrade_num2` int NULL DEFAULT NULL,
  `sitestorepro_upgrade_num3` int NULL DEFAULT NULL,
  `sitestorepro_upgrade_num4` int NULL DEFAULT NULL,
  `sitestorepro_upgrade_num5` int NULL DEFAULT NULL,
  `sitestorepro_upgrade_num6` int NULL DEFAULT NULL,
  `sitestorepro_upgrade_num7` int NULL DEFAULT NULL,
  `sitestorepro_upgrade_num8` int NULL DEFAULT NULL,
  `sitestorepro_upgrade_num9` int NULL DEFAULT NULL,
  `sitestorepro_upgrade_num10` int NULL DEFAULT NULL,
  `sitestorepro_upgrade_num11` int NULL DEFAULT NULL,
  `sitestorepro_upgrade_num12` int NULL DEFAULT NULL,
  `sitestorepro_upgrade_num13` int NULL DEFAULT NULL,
  `sitestorepro_upgrade_num14` int NULL DEFAULT NULL,
  `sitestorepro_upgrade_num15` int NULL DEFAULT NULL,
  `sitestorepro_upgrade_text1` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_text2` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_text3` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_text4` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_text5` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_text6` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_text7` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_text8` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_text9` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_text10` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_text11` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_text12` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_text13` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_text14` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_text15` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_text16` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_text17` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_text18` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_text19` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_text20` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  PRIMARY KEY (`ConfigID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sitestorepro_prod_reviews_config
-- ----------------------------
INSERT INTO `sitestorepro_prod_reviews_config` VALUES (1, 1, 'User Reviews For ', '', 'Most Recent Review...', 'All Reviews', 'Review Average: ', '(+) Add Review', 'Click the \'Add Review\' tab to be the first one to review this item!', 'Please complete the form below to add your review of this item. (All Fields Are Required) ', 'Your Name or \"Nickname\":', '(Cannot Be Empty & Max 50 Characters) ', '(Name Is Required)', 'Overall Product Rating:', '', 'Excellent Product', 'Good Product ', 'Fair Product ', 'Needs Improvement', 'Not Recommended ', '(Selection Is Required)', 'Title/Caption Of Your Review:', '(Max 100 Characters) ', '(Cannot Be Empty & Max 100 Characters)', 'Your comments | opinion about this product:', '(Cannot Be Empty & Max 1000 Characters) ', '(Comments Are Required. Max 1000 Characters)', 'Your Location (City, State):', '(Max 50 Characters)', '(Location Is Required)', '', 'Please verify that you are not a bot with the above Captcha requirement.', 'Submit Review', 'Resubmit Review', 'Product Review Submitted!', 'Thank you for your product review submission. All submissions are reviewed prior to activation. Please allow 24-48 hours for your review to appear online. ', 'Close This Window', 'There were error(s)in your product review submission. Please correct the required information above and resubmit your review.', 0, NULL, NULL, 'Online Product Review Submitted', 'A product review was submitted online. <br><br>Login to your web-based admin to approve/activate the new product review.  (Products > Products Reviews)', 100095, 100093, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'review_star.png', 'review_star_half.png', 'review_star_none.png', 'Sort By:', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- ----------------------------
-- Table structure for sitestorepro_prod_size_assoc
-- ----------------------------
DROP TABLE IF EXISTS `sitestorepro_prod_size_assoc`;
CREATE TABLE `sitestorepro_prod_size_assoc`  (
  `SizeIDAssoc` int NOT NULL AUTO_INCREMENT,
  `SizeID` int NULL DEFAULT NULL,
  `ProdID` int NULL DEFAULT NULL,
  `menuordering` double NULL DEFAULT NULL,
  `use_custom` int NULL DEFAULT 0,
  `custom_retail_label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `custom_retail_fee` double NULL DEFAULT 0,
  `custom_wholesale_label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `custom_wholesale_fee` double NULL DEFAULT 0,
  `custom_sales_tax` int NULL DEFAULT 0,
  `custom_weight` double NULL DEFAULT 0,
  PRIMARY KEY (`SizeIDAssoc`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 389 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sitestorepro_prod_size_assoc
-- ----------------------------
INSERT INTO `sitestorepro_prod_size_assoc` VALUES (275, 123, 1000002, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_size_assoc` VALUES (276, 124, 1000002, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_size_assoc` VALUES (277, 125, 1000002, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_size_assoc` VALUES (278, 126, 1000002, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_size_assoc` VALUES (279, 127, 1000002, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_size_assoc` VALUES (280, 128, 1000002, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_size_assoc` VALUES (281, 129, 1000002, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_size_assoc` VALUES (282, 123, 1000003, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_size_assoc` VALUES (283, 124, 1000003, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_size_assoc` VALUES (284, 125, 1000003, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_size_assoc` VALUES (285, 126, 1000003, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_size_assoc` VALUES (286, 127, 1000003, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_size_assoc` VALUES (287, 128, 1000003, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_size_assoc` VALUES (288, 129, 1000003, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_size_assoc` VALUES (289, 123, 1000004, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_size_assoc` VALUES (290, 124, 1000004, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_size_assoc` VALUES (291, 125, 1000004, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_size_assoc` VALUES (292, 126, 1000004, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_size_assoc` VALUES (293, 127, 1000004, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_size_assoc` VALUES (294, 128, 1000004, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_size_assoc` VALUES (295, 129, 1000004, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_size_assoc` VALUES (303, 123, 1000006, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_size_assoc` VALUES (304, 124, 1000006, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_size_assoc` VALUES (305, 125, 1000006, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_size_assoc` VALUES (306, 126, 1000006, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_size_assoc` VALUES (307, 127, 1000006, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_size_assoc` VALUES (308, 128, 1000006, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_size_assoc` VALUES (309, 129, 1000006, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_size_assoc` VALUES (329, 123, 1000005, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_size_assoc` VALUES (330, 124, 1000005, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_size_assoc` VALUES (331, 125, 1000005, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_size_assoc` VALUES (332, 126, 1000005, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_size_assoc` VALUES (333, 127, 1000005, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_size_assoc` VALUES (334, 128, 1000005, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_size_assoc` VALUES (335, 129, 1000005, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_size_assoc` VALUES (351, 136, 1000025, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_size_assoc` VALUES (352, 132, 1000025, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_size_assoc` VALUES (353, 133, 1000025, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_size_assoc` VALUES (354, 134, 1000025, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_size_assoc` VALUES (355, 135, 1000025, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_size_assoc` VALUES (356, 137, 1000025, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_size_assoc` VALUES (363, 136, 1000026, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_size_assoc` VALUES (364, 132, 1000026, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_size_assoc` VALUES (365, 133, 1000026, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_size_assoc` VALUES (366, 134, 1000026, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_size_assoc` VALUES (367, 135, 1000026, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_size_assoc` VALUES (368, 132, 1000027, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_size_assoc` VALUES (369, 133, 1000027, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_size_assoc` VALUES (370, 134, 1000027, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_size_assoc` VALUES (371, 135, 1000027, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_size_assoc` VALUES (372, 132, 1000015, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_size_assoc` VALUES (373, 133, 1000015, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_size_assoc` VALUES (374, 134, 1000015, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_size_assoc` VALUES (375, 135, 1000015, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_size_assoc` VALUES (376, 137, 1000015, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_size_assoc` VALUES (379, 132, 1000029, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_size_assoc` VALUES (380, 133, 1000029, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_size_assoc` VALUES (381, 134, 1000029, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_size_assoc` VALUES (382, NULL, 1000032, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_size_assoc` VALUES (383, NULL, 1000033, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_size_assoc` VALUES (384, NULL, 1000034, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_size_assoc` VALUES (385, NULL, 1000035, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_size_assoc` VALUES (386, NULL, 1000036, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_size_assoc` VALUES (387, NULL, 1000037, NULL, 0, NULL, 0, NULL, 0, 0, 0);
INSERT INTO `sitestorepro_prod_size_assoc` VALUES (388, NULL, 1000038, NULL, 0, NULL, 0, NULL, 0, 0, 0);

-- ----------------------------
-- Table structure for sitestorepro_prod_sizes
-- ----------------------------
DROP TABLE IF EXISTS `sitestorepro_prod_sizes`;
CREATE TABLE `sitestorepro_prod_sizes`  (
  `SizeID` int NOT NULL AUTO_INCREMENT,
  `SizeName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `SearchMenuLabel` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `SearchResultsTitle` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `IncludeInMenu` int NULL DEFAULT 1,
  `SizeFee` double NULL DEFAULT 0,
  `SizeImage` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `IncludeOnDetailsPage` int NULL DEFAULT 0,
  `MenuOrdering` double NULL DEFAULT 0,
  `StoreID` int NULL DEFAULT 1,
  `sitestorepro_upgrade_text1` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_text2` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_text3` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_vc1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sitestorepro_upgrade_vc2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sitestorepro_upgrade_num1` int NULL DEFAULT NULL,
  `sitestorepro_upgrade_num2` int NULL DEFAULT NULL,
  `sitestorepro_upgrade_num3` int NULL DEFAULT NULL,
  `sitestorepro_upgrade_num4` int NULL DEFAULT NULL,
  `sitestorepro_upgrade_num5` int NULL DEFAULT NULL,
  `sitestorepro_upgrade_fl1` double NULL DEFAULT NULL,
  `sitestorepro_upgrade_fl2` double NULL DEFAULT NULL,
  `sitestorepro_upgrade_fl3` double NULL DEFAULT NULL,
  `sitestorepro_upgrade_ln1` bigint NULL DEFAULT NULL,
  `sitestorepro_upgrade_ln2` bigint NULL DEFAULT NULL,
  `sitestorepro_upgrade_date1` datetime NULL DEFAULT NULL,
  `sitestorepro_upgrade_date2` datetime NULL DEFAULT NULL,
  `sitestorepro_upgrade_date3` datetime NULL DEFAULT NULL,
  `sitestorepro_upgrade_date4` datetime NULL DEFAULT NULL,
  `sitestorepro_upgrade_image1` longblob NULL,
  PRIMARY KEY (`SizeID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 138 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sitestorepro_prod_sizes
-- ----------------------------
INSERT INTO `sitestorepro_prod_sizes` VALUES (123, '5', '5', 'Search Results For Size 5', 1, 0, '', 0, 5.1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_sizes` VALUES (124, '5.5', '5.5', 'Search Results For Size 5', 1, 0, '', 0, 5.5, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_sizes` VALUES (125, '6', '6', 'Search Results For Size 6', 1, 0, '', 0, 6, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_sizes` VALUES (126, '6.5', '6.5', 'Search Results For Size 6.5', 1, 0, '', 0, 7, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_sizes` VALUES (127, '7', '7 ', 'Search Results For Size 7', 1, 0, '', 0, 8, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_sizes` VALUES (128, '7.5', '7.5', 'Search Results For Size 7.5', 1, 0, '', 0, 9, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_sizes` VALUES (129, '8', '8', 'Search Results For Size 8', 1, 0, '', 0, 10, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_sizes` VALUES (132, 'S', 'Small', 'Small', 0, 0, '', 0, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_sizes` VALUES (133, 'M', 'Medium', 'Medium', 0, 0, '', 0, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_sizes` VALUES (134, 'L', 'Large', 'Large', 0, 0, '', 0, 3, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_sizes` VALUES (135, 'XL', 'XL', 'XL', 1, 0, '', 0, 4, 1, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_sizes` VALUES (136, 'XS', 'X-Small', 'X-Small', 1, 0, NULL, 0, 0, 1, 'X-Small', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_sizes` VALUES (137, 'XXL', 'XXL', 'XXL', 1, 0, NULL, 0, 4.7, 1, 'XXL', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- ----------------------------
-- Table structure for sitestorepro_prod_styles
-- ----------------------------
DROP TABLE IF EXISTS `sitestorepro_prod_styles`;
CREATE TABLE `sitestorepro_prod_styles`  (
  `StyleID` int NOT NULL AUTO_INCREMENT,
  `CatID` int NULL DEFAULT NULL,
  `SubCatID` int NULL DEFAULT NULL,
  `CategoryName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `CategoryDescription` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `IncludeCatHeader` int NULL DEFAULT 1,
  `CatHeader` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `IncludeCatFooter` int NULL DEFAULT 1,
  `CatFooter` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `CategoryImage` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `IncludeInMenu` int NULL DEFAULT 1,
  `MenuOrdering` double NULL DEFAULT 1,
  `StyleDiscount` int NULL DEFAULT 0,
  `Active` int NULL DEFAULT 0,
  `StoreID` int NULL DEFAULT 0,
  `METAKeywords` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `METADescription` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `ShowBannerImage` int NULL DEFAULT 1,
  `BannerImage` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `BannerImageALT` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `ShowDirectoryDisplay` int NULL DEFAULT 1,
  `DirectoryText` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `ShowDirectoryImage` int NULL DEFAULT 1,
  `MenuLinkText` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `MenuTitleText` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `ShowDrillDowns` int NULL DEFAULT 1,
  `DrillDownsTitle` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `DrillDownsDescription` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_text1` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_text2` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_text3` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_vc1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sitestorepro_upgrade_vc2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sitestorepro_upgrade_num1` int NULL DEFAULT NULL,
  `sitestorepro_upgrade_num2` int NULL DEFAULT NULL,
  `sitestorepro_upgrade_num3` int NULL DEFAULT NULL,
  `sitestorepro_upgrade_num4` int NULL DEFAULT NULL,
  `sitestorepro_upgrade_num5` int NULL DEFAULT NULL,
  `sitestorepro_upgrade_fl1` double NULL DEFAULT NULL,
  `sitestorepro_upgrade_fl2` double NULL DEFAULT NULL,
  `sitestorepro_upgrade_fl3` double NULL DEFAULT NULL,
  `sitestorepro_upgrade_ln1` bigint NULL DEFAULT NULL,
  `sitestorepro_upgrade_ln2` bigint NULL DEFAULT NULL,
  `sitestorepro_upgrade_date1` datetime NULL DEFAULT NULL,
  `sitestorepro_upgrade_date2` datetime NULL DEFAULT NULL,
  `sitestorepro_upgrade_date3` datetime NULL DEFAULT NULL,
  `sitestorepro_upgrade_date4` datetime NULL DEFAULT NULL,
  `sitestorepro_upgrade_image1` longblob NULL,
  PRIMARY KEY (`StyleID`) USING BTREE,
  INDEX `CatIDstyqx`(`CatID` ASC) USING BTREE,
  INDEX `SubCatIDstyqx`(`SubCatID` ASC) USING BTREE,
  INDEX `IncludeInMenustyqx`(`IncludeInMenu` ASC) USING BTREE,
  INDEX `StoreIDstyqx`(`StoreID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 100010 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sitestorepro_prod_styles
-- ----------------------------
INSERT INTO `sitestorepro_prod_styles` VALUES (100006, 1, 1001, 'Diamond Bracelets', '', 1, '<p>This is sample header information for the diamond bracelet collection. You can display any text or HTML here via the web-based admin and it will only be displayed when the customer view this specific record.</p>', 0, '<p>This is sample footer information for the diamond bracelet \'Collection\'. You can display any text or HTML here via the web-based admin system.</p>', '', 1, 0, 0, 1, 1, 'diamonds, bracelets, cheap, direct', 'Great diamond bracelets at great prices!', 1, '', '', 1, 'Diamond Bracelets', 0, 'Diamond Bracelets', 'Diamond Bracelets', 1, '', '', NULL, NULL, NULL, NULL, 'diamond-bracelets', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_styles` VALUES (100007, 1, 1001, 'White Gold Bracelets', '', 1, '<p></p>', 0, '<p></p>', '', 1, 2, 0, 1, 1, 'White Gold', 'White Gold', 1, '', '', 1, 'White Gold Bracelets', 0, 'White Gold Bracelets', 'White Gold Bracelets', 1, '', '', NULL, NULL, NULL, NULL, 'white-gold-bracelets', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_styles` VALUES (100008, 1, 1001, 'Gold Bracelets', '', 1, '<p></p>', 0, '<p></p>', '', 1, 1, 0, 1, 1, 'Gold Bracelets', '', 1, '', '', 1, 'Gold Bracelets', 0, 'Gold Bracelets', 'Gold Bracelets', 1, '', '', NULL, NULL, NULL, NULL, 'gold-bracelets', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prod_styles` VALUES (100009, 1, 1001, 'Precious Stones Bracelets', 'Precious Stones Bracelets', 1, '<p></p>', 0, '<p></p>', '', 1, 3, 0, 1, 1, '', '', 1, '', '', 1, 'Precious Stones Bracelets', 0, 'Precious Stones Bracelets', 'Precious Stones Bracelets', 1, '', '', NULL, NULL, NULL, NULL, 'precious-stones-bracelets', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- ----------------------------
-- Table structure for sitestorepro_prod_user_option1
-- ----------------------------
DROP TABLE IF EXISTS `sitestorepro_prod_user_option1`;
CREATE TABLE `sitestorepro_prod_user_option1`  (
  `OptionMenu1ID` int NOT NULL AUTO_INCREMENT,
  `ProdID` int NULL DEFAULT 0,
  `OptionName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `OptionPic` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `LanguageID` int NULL DEFAULT 1,
  `OptionFee` double NULL DEFAULT 0,
  `Override` int NULL DEFAULT NULL,
  `InventoryLevel` int NULL DEFAULT NULL,
  `Ordering` double NULL DEFAULT 0,
  `one_time_fee` int NULL DEFAULT 0,
  `charge_tax` int NULL DEFAULT 0,
  `charge_shipping` int NULL DEFAULT 0,
  `shipping_weight` float NULL DEFAULT 0,
  `wholesale_label` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `wholesale_fee` float NULL DEFAULT 0,
  PRIMARY KEY (`OptionMenu1ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sitestorepro_prod_user_option1
-- ----------------------------
INSERT INTO `sitestorepro_prod_user_option1` VALUES (1, 1000029, 'Selection 1A (+$10.00)', NULL, 1, 10, NULL, NULL, 1, 0, 0, 0, 0, NULL, 0);
INSERT INTO `sitestorepro_prod_user_option1` VALUES (2, 1000029, 'Selection 1B (+$0.00)', NULL, 1, 0, NULL, NULL, 2, 0, 0, 0, 0, NULL, 0);

-- ----------------------------
-- Table structure for sitestorepro_prod_user_option2
-- ----------------------------
DROP TABLE IF EXISTS `sitestorepro_prod_user_option2`;
CREATE TABLE `sitestorepro_prod_user_option2`  (
  `OptionMenu2ID` int NOT NULL AUTO_INCREMENT,
  `ProdID` int NULL DEFAULT 0,
  `OptionName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `OptionPic` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `LanguageID` int NULL DEFAULT 1,
  `OptionFee` double NULL DEFAULT 0,
  `Override` int NULL DEFAULT NULL,
  `InventoryLevel` int NULL DEFAULT NULL,
  `Ordering` double NULL DEFAULT 0,
  `one_time_fee` int NULL DEFAULT 0,
  `charge_tax` int NULL DEFAULT 0,
  `charge_shipping` int NULL DEFAULT 0,
  `shipping_weight` float NULL DEFAULT 0,
  `wholesale_label` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `wholesale_fee` float NULL DEFAULT 0,
  PRIMARY KEY (`OptionMenu2ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sitestorepro_prod_user_option2
-- ----------------------------
INSERT INTO `sitestorepro_prod_user_option2` VALUES (1, 1000029, 'Selection 2A (+$0.00)', NULL, 1, 0, NULL, NULL, 1, 0, 0, 0, 0, NULL, 0);
INSERT INTO `sitestorepro_prod_user_option2` VALUES (2, 1000029, 'Selection 2B (+$50.00)', NULL, 1, 50, NULL, NULL, 2, 0, 0, 0, 0, NULL, 0);
INSERT INTO `sitestorepro_prod_user_option2` VALUES (3, 1000029, 'Selection 2c (+$19.00)', NULL, 1, 19, NULL, NULL, 3, 0, 0, 0, 0, NULL, 0);

-- ----------------------------
-- Table structure for sitestorepro_prodcategory
-- ----------------------------
DROP TABLE IF EXISTS `sitestorepro_prodcategory`;
CREATE TABLE `sitestorepro_prodcategory`  (
  `CatID` int NOT NULL AUTO_INCREMENT,
  `CategoryName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `CategoryDescription` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `IncludeCatHeader` int NULL DEFAULT 1,
  `CatHeader` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `IncludeCatFooter` int NULL DEFAULT 1,
  `CatFooter` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `CatDiscount` double NULL DEFAULT NULL,
  `MenuOrdering` double NULL DEFAULT NULL,
  `IncludeInMenu` int NULL DEFAULT NULL,
  `Active` int NULL DEFAULT 1,
  `StoreID` int NULL DEFAULT 1,
  `METAKeywords` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `METADescription` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `ShowBannerImage` int NULL DEFAULT 1,
  `BannerImage` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `BannerImageALT` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `ShowDirectoryDisplay` int NULL DEFAULT 1,
  `DirectoryText` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `MenuLinkText` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `MenuTitleText` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `ShowDrillDowns` int NULL DEFAULT 1,
  `DrillDownsTitle` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `DrillDownsDescription` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `ShowDirectoryImage` int NULL DEFAULT 1,
  `CategoryImage` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sitestorepro_upgrade_text1` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_text2` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_text3` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_vc1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sitestorepro_upgrade_vc2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sitestorepro_upgrade_num1` int NULL DEFAULT NULL,
  `sitestorepro_upgrade_num2` int NULL DEFAULT NULL,
  `sitestorepro_upgrade_num3` int NULL DEFAULT NULL,
  `sitestorepro_upgrade_num4` int NULL DEFAULT NULL,
  `sitestorepro_upgrade_num5` int NULL DEFAULT NULL,
  `sitestorepro_upgrade_fl1` double NULL DEFAULT NULL,
  `sitestorepro_upgrade_fl2` double NULL DEFAULT NULL,
  `sitestorepro_upgrade_fl3` double NULL DEFAULT NULL,
  `sitestorepro_upgrade_ln1` bigint NULL DEFAULT NULL,
  `sitestorepro_upgrade_ln2` bigint NULL DEFAULT NULL,
  `sitestorepro_upgrade_date1` datetime NULL DEFAULT NULL,
  `sitestorepro_upgrade_date2` datetime NULL DEFAULT NULL,
  `sitestorepro_upgrade_date3` datetime NULL DEFAULT NULL,
  `sitestorepro_upgrade_date4` datetime NULL DEFAULT NULL,
  `sitestorepro_upgrade_image1` longblob NULL,
  PRIMARY KEY (`CatID`) USING BTREE,
  INDEX `IncludeInMenucatxp`(`IncludeInMenu` ASC) USING BTREE,
  INDEX `StoreIDcatxp`(`StoreID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sitestorepro_prodcategory
-- ----------------------------
INSERT INTO `sitestorepro_prodcategory` VALUES (1, 'Custom Jewelry', 'Earring, Necklaces, etc.', 0, '<p>This is a sample header content...</p>', 0, '<p>This is some sample footer content for the jewelry category...</p>', NULL, 0, 1, 1, 1, 'Jewelry, Rings, Bracelets, necklaces, gold, silver, platinum', 'Buy jewelry at discount prices direct from the manufacturer!', 0, '', '', 0, 'Jewelry', 'Custom Jewelry', 'Custom Jewelry At Factory Direct Prices', 1, '', 'Select a sub-section of the jewerly category to narrow your search or simple browse through the displayed items below.', 1, 'jewelry.jpg', NULL, NULL, NULL, 'jewelry_header_image.jpg', 'jewelry-sample', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prodcategory` VALUES (2, 'Watches', 'Men\'s And Women\'s Watches and Time Pieces', 1, '', 0, '', NULL, 2, 1, 1, 1, 'watches, rolex, tag, citizen, time, sport, men\'s, women\'s, diving, dress, gift', 'Buy watches direct online at discount prices.', 0, '', '', 0, 'Watches', 'Watches', 'Watches And Luxury Time Pieces', 0, '', '', 1, 'watches.jpg', NULL, NULL, NULL, 'watches_header_image.jpg', 'watches', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prodcategory` VALUES (3, 'Downloads & Videos', 'PDF Downloads and On-Demand Media', 1, '', 0, '', NULL, 3, 1, 1, 1, '', '', 0, '', '', 0, 'Media', 'Media', 'Videos | On-Demand | Downloads', 0, '', '', 1, 'media.jpg', NULL, NULL, NULL, 'media_header_image.jpg', 'media', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prodcategory` VALUES (5, 'Gifts & Apparel', 'Sweatshirts, etc', 1, '<p><br>All features for item search results are controlled through the easy-to-use Site Store Pro web-based administration system ... No programming required!</p>', 0, '', NULL, 1, 1, 1, 1, '', '', 0, '', '', 0, 'Gifts And Apparel', 'Gifts & Apparel', 'Gifts & Apparel', 0, '', '', 1, 'gifts.jpg', NULL, NULL, NULL, 'gifts_header_image.jpg', 'gifts', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prodcategory` VALUES (6, 'Service Only Items', '', 0, '', 0, '', NULL, 4, 0, 1, 1, '', '', 0, '', '', 0, 'Service Only Items', 'Service Only Items', 'Service Only Items', 0, '', '', 0, '', NULL, NULL, NULL, '', 'service-only-items', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prodcategory` VALUES (7, 'Workshops & Seminars', '', 0, '', 0, '', NULL, 5, 1, 1, 1, '', '', 0, '', '', 0, 'Workshops & Seminars', 'Workshops & Seminars', 'Workshops & Seminars', 0, '', '', 0, '', NULL, NULL, NULL, '', 'workshops', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- ----------------------------
-- Table structure for sitestorepro_prodcategory_assoc
-- ----------------------------
DROP TABLE IF EXISTS `sitestorepro_prodcategory_assoc`;
CREATE TABLE `sitestorepro_prodcategory_assoc`  (
  `cat_assoc_id` int NOT NULL AUTO_INCREMENT,
  `prod_id` int NULL DEFAULT 0,
  `prod_category_id` int NULL DEFAULT 0,
  PRIMARY KEY (`cat_assoc_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 210 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sitestorepro_prodcategory_assoc
-- ----------------------------
INSERT INTO `sitestorepro_prodcategory_assoc` VALUES (190, 1000015, 5);
INSERT INTO `sitestorepro_prodcategory_assoc` VALUES (200, 1000002, 1);
INSERT INTO `sitestorepro_prodcategory_assoc` VALUES (205, 1000014, 3);
INSERT INTO `sitestorepro_prodcategory_assoc` VALUES (209, 1000029, 6);

-- ----------------------------
-- Table structure for sitestorepro_prodsubcat
-- ----------------------------
DROP TABLE IF EXISTS `sitestorepro_prodsubcat`;
CREATE TABLE `sitestorepro_prodsubcat`  (
  `SubCatID` int NOT NULL AUTO_INCREMENT,
  `CatID` int NULL DEFAULT NULL,
  `CategoryName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `CategoryDescription` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `IncludeCatHeader` int NULL DEFAULT 1,
  `CatHeader` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `IncludeCatFooter` int NULL DEFAULT 1,
  `CatFooter` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `CategoryImage` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `CatDiscount` double NOT NULL DEFAULT 0,
  `MenuOrdering` double NULL DEFAULT NULL,
  `IncludeInMenu` int NULL DEFAULT NULL,
  `SameNameAsCat` int NULL DEFAULT 0,
  `Active` int NULL DEFAULT 1,
  `StoreID` int NULL DEFAULT 1,
  `METAKeywords` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `METADescription` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `ShowBannerImage` int NULL DEFAULT 1,
  `BannerImage` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `BannerImageALT` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `ShowDirectoryDisplay` int NULL DEFAULT 1,
  `DirectoryText` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `ShowDirectoryImage` int NULL DEFAULT 1,
  `MenuLinkText` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `MenuTitleText` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `ShowDrillDowns` int NULL DEFAULT 1,
  `DrillDownsTitle` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `DrillDownsDescription` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_text1` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_text2` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_text3` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_vc1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sitestorepro_upgrade_vc2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sitestorepro_upgrade_num1` int NULL DEFAULT NULL,
  `sitestorepro_upgrade_num2` int NULL DEFAULT NULL,
  `sitestorepro_upgrade_num3` int NULL DEFAULT NULL,
  `sitestorepro_upgrade_num4` int NULL DEFAULT NULL,
  `sitestorepro_upgrade_num5` int NULL DEFAULT NULL,
  `sitestorepro_upgrade_fl1` double NULL DEFAULT NULL,
  `sitestorepro_upgrade_fl2` double NULL DEFAULT NULL,
  `sitestorepro_upgrade_fl3` double NULL DEFAULT NULL,
  `sitestorepro_upgrade_ln1` bigint NULL DEFAULT NULL,
  `sitestorepro_upgrade_ln2` bigint NULL DEFAULT NULL,
  `sitestorepro_upgrade_date1` datetime NULL DEFAULT NULL,
  `sitestorepro_upgrade_date2` datetime NULL DEFAULT NULL,
  `sitestorepro_upgrade_date3` datetime NULL DEFAULT NULL,
  `sitestorepro_upgrade_date4` datetime NULL DEFAULT NULL,
  `sitestorepro_upgrade_image1` longblob NULL,
  PRIMARY KEY (`SubCatID`) USING BTREE,
  INDEX `CatIDsubcatx`(`CatID` ASC) USING BTREE,
  INDEX `IncludeInMenusubcatx`(`IncludeInMenu` ASC) USING BTREE,
  INDEX `StoreIDsubcatx`(`StoreID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1012 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sitestorepro_prodsubcat
-- ----------------------------
INSERT INTO `sitestorepro_prodsubcat` VALUES (1001, 1, 'Bracelets', '', 1, '<p>This page shows how you can drill-down to another level when a sub-category has collections applied to the items.</p>', 0, 'This is sample footer content for the bracelets subcategory...', 'bracelets-sub-example.jpg', 0, 1, 1, 0, 1, 1, 'bracelets, shop, online, direct', 'This is a sample bracelets sub-category display page for the Site Store Pro shopping cart.', 0, '', 'Bracelets', 0, 'Bracelets For Any Occasion!', 1, 'Bracelets', 'High Quality Bracelets At Low Prices', 1, '', '', NULL, NULL, NULL, NULL, 'bracelets', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prodsubcat` VALUES (1003, 1, 'Rings', 'Rings &gt; Jewerly', 0, '<p></p>', 0, '<p></p>', 'rings-sub-example.jpg', 0, 0, 1, NULL, 1, 1, '', '', 0, '', 'Rings', 0, 'Great Selection Of Rings!', 1, 'Rings', 'All Rings On Sale Now!', 0, '', '', NULL, NULL, NULL, NULL, 'rings', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prodsubcat` VALUES (1004, 3, 'PDF Downloads', 'PDF Downloads', 1, '<p></p>', 0, '<p></p>', NULL, 0, 5, 1, NULL, 1, 1, 'downloads, instant, pdf, learn, now', 'Sample PDF downloads sub-category.', 0, NULL, 'PDF Downloads', 0, 'PDF Downloads', 0, 'PDF Downloads', 'Instructional PDFs', 0, '', '', NULL, NULL, NULL, NULL, 'downloads', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prodsubcat` VALUES (1008, 5, 'Clothing', '', 0, '', 0, '', NULL, 0, 2, 1, 0, 1, 1, '', '', 0, NULL, 'Clothing', 0, 'Clothing', 0, 'Clothing', 'Clothing', 0, '', '', NULL, NULL, NULL, NULL, 'clothing', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prodsubcat` VALUES (1009, 3, 'Training Videos', 'On-Demand Content', 0, '', 0, '', NULL, 0, 6, 1, 0, 1, 1, '', '', 0, NULL, '', 0, 'Training Videos', 0, 'Training Videos', 'Training Videos', 0, '', '', NULL, NULL, NULL, NULL, 'training-videos', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prodsubcat` VALUES (1010, 5, 'Writing Pens', '', 0, '<p></p>', 0, '<p></p>', NULL, 0, 4, 1, NULL, 1, 1, '', '', 0, NULL, '', 0, 'Luxury Writing Pens!', 0, 'Writing Pens', 'Writing Pens', 0, '', '', NULL, NULL, NULL, NULL, 'pens', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_prodsubcat` VALUES (1011, 5, 'Jewelry Boxes', '', 0, '<p></p>', 0, '<p></p>', NULL, 0, 3, 1, NULL, 1, 1, '', '', 0, NULL, '', 0, 'Vintage Jewelry Boxes!', 0, 'Jewelry Boxes', 'Jewelry Boxes', 0, '', '', NULL, NULL, NULL, NULL, 'jewelry-boxes', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- ----------------------------
-- Table structure for sitestorepro_products
-- ----------------------------
DROP TABLE IF EXISTS `sitestorepro_products`;
CREATE TABLE `sitestorepro_products`  (
  `ProdID` int NOT NULL AUTO_INCREMENT,
  `ProdName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ProdShortDesc` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `ProdLongDesc` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `ProdBullet1` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `ProdBullet2` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `ProdBullet3` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `ProdBullet4` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `ProdDescription` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `ProdKeywords` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `ProdSKU` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `SubCatID` int NULL DEFAULT 0,
  `MasterCatID` int NULL DEFAULT 0,
  `ManufacturerID` int NULL DEFAULT 0,
  `StyleID` int NULL DEFAULT 0,
  `ProdPrice` decimal(15, 2) NULL DEFAULT 0.00,
  `ProdPrice2` decimal(15, 2) NULL DEFAULT 0.00,
  `ProdCost` decimal(15, 2) NULL DEFAULT 0.00,
  `SpecialPrice` decimal(15, 2) NULL DEFAULT 0.00,
  `IsSpecial` int NULL DEFAULT 0,
  `Special_Items_Sort` int NULL DEFAULT 0,
  `ChargeTax` int NULL DEFAULT 1,
  `ChargeShipping` int NULL DEFAULT 0,
  `ProdWeight` double NULL DEFAULT 1,
  `Active` int NULL DEFAULT 0,
  `IsCatalog` int NULL DEFAULT 0,
  `IsFeatured` int NULL DEFAULT 0,
  `Featured_Items_Sort` int NULL DEFAULT 0,
  `OnHomePage` int NULL DEFAULT 0,
  `Home_Page_Items_Sort` int NULL DEFAULT 0,
  `InvOptionSpecific` int NULL DEFAULT 0,
  `OutOfStockID` int NULL DEFAULT 0,
  `IncludeHandling` int NULL DEFAULT 0,
  `HandlingFeeID` int NULL DEFAULT 0,
  `IncludeGiftWrap` int NULL DEFAULT 0,
  `GiftWrapID` int NULL DEFAULT 0,
  `GiftWrapLabel` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `GiftWrapInstructions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `WishList` int NULL DEFAULT 0,
  `EnableQTYDisc` int NULL DEFAULT 0,
  `CustomQtyPrices` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `ShowQuantity` int NULL DEFAULT 0,
  `MaxQty` int NULL DEFAULT 0,
  `Qty_List_Values` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `Itemview_Media_Player` int NULL DEFAULT 0,
  `ItemView_Media_Title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `ItemView_Media_Desc` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `ItemView_Media_File` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Hide_Item_Images` int NULL DEFAULT 0,
  `Purchased_Media_Title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Purchased_Media_Desc` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `Purchased_Media_File` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `OnDemand_Media_Purchase` int NULL DEFAULT 0,
  `CustomPriceLabel` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `Plugins_Loader` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `Full_Width_Header_Image_ON` int NULL DEFAULT 0,
  `Background_Image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `Background_Image_CDN` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `ItemView_Columns_Layout` int NULL DEFAULT 0,
  `Show_Categories_Drill_Down` int NULL DEFAULT 0,
  `ItemView_Left_Column_Content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `ItemView_Right_Column_Content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `ShopByStyles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `ShopByOccasions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `SizesList` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `SizesSearch` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `ColorsList` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `ColorsSearch` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `MaterialsList` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `MaterialsSearch` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `SKUSearch` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `Hide_Tabs_Display` int NULL DEFAULT 0,
  `Hide_BreadCrumbs` int NULL DEFAULT 0,
  `Hide_Itemview_Pricing` int NULL DEFAULT 0,
  `Custom_AddToCart_Label` int NULL DEFAULT 0,
  `Item_Prerequisite_ID` int NULL DEFAULT 0,
  `Hide_JS_Content_Area` int NULL DEFAULT 0,
  `Background_Image_ON` int NULL DEFAULT 0,
  `Background_Video_ON` int NULL DEFAULT 0,
  `Donation_Min_Required` int NULL DEFAULT 0,
  `IncludeReview` int NULL DEFAULT 0,
  `Show_Options_Total` int NULL DEFAULT 0,
  `ShippingMessageID` int NULL DEFAULT 0,
  `DeliveryMethod` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Downloadable` int NULL DEFAULT 0,
  `DownloadDays` int NULL DEFAULT 365,
  `DownloadLimit` int NULL DEFAULT 15,
  `DownloadFile` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `AssignSerialNumber` int NULL DEFAULT 0,
  `DownloadShip` int NULL DEFAULT 0,
  `IsOptionOnly` int NULL DEFAULT 0,
  `Disclaimer` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `IsEvent` int NULL DEFAULT 0,
  `EventStartDate` datetime NULL DEFAULT NULL,
  `EventEndDate` datetime NULL DEFAULT NULL,
  `LocationID` int NULL DEFAULT 0,
  `Show_Event_Map` int NULL DEFAULT 0,
  `PersonalizeOption` int NULL DEFAULT 0,
  `PersonalizeLabel` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `PersonalizeInstructions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `PersonalizeID` int NULL DEFAULT 0,
  `StoreID` int NULL DEFAULT 1,
  `AddedBy` int NULL DEFAULT 0,
  `AddDate` datetime NULL DEFAULT NULL,
  `LastModifiedUserID` int NULL DEFAULT 0,
  `LastModifiedDate` datetime NULL DEFAULT NULL,
  `Total_Item_Views` int NULL DEFAULT 0,
  `TotalSold` int NULL DEFAULT 0,
  `Option1Name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Option1Enable` int NULL DEFAULT 1,
  `Option2Name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Option2Enable` int NULL DEFAULT 1,
  `sitestorepro_upgrade_text2` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_text3` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_text4` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_text5` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_text6` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_text7` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_text8` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_text9` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_text10` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_vc1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `sitestorepro_upgrade_vc2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `sitestorepro_upgrade_vc3` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `sitestorepro_upgrade_vc4` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `sitestorepro_upgrade_vc5` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `sitestorepro_upgrade_vc6` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `sitestorepro_upgrade_vc7` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `sitestorepro_upgrade_vc8` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `sitestorepro_upgrade_vc9` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `sitestorepro_upgrade_vc10` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `sitestorepro_upgrade_num1` int NULL DEFAULT 0,
  `sitestorepro_upgrade_num2` int NULL DEFAULT 0,
  `sitestorepro_upgrade_num3` int NULL DEFAULT 0,
  `sitestorepro_upgrade_num4` int NULL DEFAULT 0,
  `sitestorepro_upgrade_num5` int NULL DEFAULT 0,
  `sitestorepro_upgrade_num6` int NULL DEFAULT 0,
  `sitestorepro_upgrade_num7` int NULL DEFAULT 0,
  `sitestorepro_upgrade_num8` int NULL DEFAULT 0,
  `sitestorepro_upgrade_num9` int NULL DEFAULT 0,
  `sitestorepro_upgrade_num10` int NULL DEFAULT 0,
  `sitestorepro_upgrade_fl1` double NULL DEFAULT 0,
  `sitestorepro_upgrade_fl2` double NULL DEFAULT 0,
  `sitestorepro_upgrade_fl3` double NULL DEFAULT 0,
  `sitestorepro_upgrade_fl4` double NULL DEFAULT 0,
  `sitestorepro_upgrade_fl5` double NULL DEFAULT 0,
  `sitestorepro_upgrade_fl6` double NULL DEFAULT 0,
  `sitestorepro_upgrade_fl7` double NULL DEFAULT 0,
  `sitestorepro_upgrade_fl8` double NULL DEFAULT 0,
  `sitestorepro_upgrade_fl9` double NULL DEFAULT 0,
  `sitestorepro_upgrade_fl10` double NULL DEFAULT 0,
  `sitestorepro_upgrade_ln1` bigint NULL DEFAULT 0,
  `sitestorepro_upgrade_ln2` bigint NULL DEFAULT 0,
  `sitestorepro_upgrade_ln3` bigint NULL DEFAULT 0,
  `sitestorepro_upgrade_ln4` bigint NULL DEFAULT 0,
  `sitestorepro_upgrade_ln5` bigint NULL DEFAULT 0,
  `sitestorepro_upgrade_date1` datetime NULL DEFAULT '2023-01-01 00:00:00',
  `sitestorepro_upgrade_date2` datetime NULL DEFAULT '2023-01-01 00:00:00',
  `sitestorepro_upgrade_date3` datetime NULL DEFAULT '2023-01-01 00:00:00',
  `mens_filter` int NULL DEFAULT 0,
  `womens_filter` int NULL DEFAULT 0,
  `boys_filter` int NULL DEFAULT 0,
  `girls_filter` int NULL DEFAULT 0,
  `babies_filter` int NULL DEFAULT 0,
  `baby_boys_filter` int NULL DEFAULT 0,
  `baby_girls_filter` int NULL DEFAULT 0,
  `seniors_filter` int NULL DEFAULT 0,
  `seo_link` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `seo_title` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `view_format` int NULL DEFAULT 0,
  `tab1_label` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `tab2_label` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `tab3_label` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `tab4_label` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `tab5_label` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `tab6_label` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `one_time_fee_label` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `one_time_fee_total` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `ItemView_Media_CDN` int NULL DEFAULT 0,
  `ItemView_Media_CDN_File` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `event_label` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `event_error` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `Full_Width_Header_Image_CDN` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `Full_Width_Header_Image_Overlay` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `Full_Width_Header_Image` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `OnDemand_Media_Purchase_CDN` int NULL DEFAULT 0,
  `OnDemand_Media_Purchase_CDN_File` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_donation` int NULL DEFAULT 0,
  `donationmax` float NULL DEFAULT 1000,
  `donationfieldlabel` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `donationdefault` float NULL DEFAULT 25,
  `donationlist` int NULL DEFAULT 0,
  `donationlistvalues` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `ShortDescriptionTop` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `ShowAddCartOnResults` int NULL DEFAULT 0,
  `AddCartOnResults` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `ShowGoToInfoOnResults` int NULL DEFAULT 0,
  `GoToInfoOnResults` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `ShowSpecialsIcon` int NULL DEFAULT 0,
  `ShowFeaturedIcon` int NULL DEFAULT 0,
  `twitter_card` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `twitter_site` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `twitter_title` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `twitter_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `twitter_creator` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `twitter_image` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `twitter_data1` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `twitter_label1` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `og_title` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `og_type` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `og_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `og_site_name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `og_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `og_image` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `og_image_secure_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `og_image_type` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `og_image_width` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `og_image_height` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `og_price_amount` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `og_price_currency` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `fb_app_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `Background_Video` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `Background_Video_CDN` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `video_poster_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `video_poster_image_preview` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `video_poster_image_CDN` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `video_poster_image_CDN_preview` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Custom_CSS` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `custom_download_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `s3_expiring_url` int NULL DEFAULT 0,
  `s3_access_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `s3_secret_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `s3_bucket` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `s3_file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `s3_file_preview` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `s3_poster_image_preview` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `s3_poster_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `s3_expiration_seconds` int NULL DEFAULT 600,
  `s3_region` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `vimeo_purchase` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `vimeo_preview` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `media_view_button_label` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `download_button_label` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sitestorepro_upgrade_text1` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_date5` datetime NULL DEFAULT '2023-01-01 00:00:00',
  `sitestorepro_upgrade_date4` datetime NULL DEFAULT '2023-01-01 00:00:00',
  `subscription` int NULL DEFAULT 0,
  `subscription_interval` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `subscription_frequency` int NULL DEFAULT 0,
  `subcription_auto_renew` int NULL DEFAULT 1,
  `subscription_trial` int NULL DEFAULT 0,
  `subscription_trial_period` int NULL DEFAULT 7,
  `subscription_trial_amount` decimal(15, 2) NULL DEFAULT 0.00,
  `subscription_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `subscription_carry_over` int NULL DEFAULT 0,
  `subcription_retries` int NULL DEFAULT 3,
  `subscription_maxfail` int NULL DEFAULT 0,
  PRIMARY KEY (`ProdID`) USING BTREE,
  INDEX `sitestorepro_products_sspro_ brandidindex`(`ManufacturerID` ASC) USING BTREE,
  INDEX `sitestorepro_products_sspro_maincatid`(`MasterCatID` ASC) USING BTREE,
  INDEX `sitestorepro_products_sspro_styleidindex`(`StyleID` ASC) USING BTREE,
  INDEX `sitestorepro_products_sspro_subcatidindex`(`SubCatID` ASC) USING BTREE,
  INDEX `sitestorepro_products_sspro_specials_index`(`IsSpecial` ASC) USING BTREE,
  INDEX `sitestorepro_products_sspro_homepageitems`(`OnHomePage` ASC) USING BTREE,
  INDEX `sitestorepro_products_sspro_featured`(`IsFeatured` ASC) USING BTREE,
  INDEX `Activeprodid`(`Active` ASC) USING BTREE,
  INDEX `IsCatalogprodid`(`IsCatalog` ASC) USING BTREE,
  FULLTEXT INDEX `ssprokeysearch`(`sitestorepro_upgrade_text5`)
) ENGINE = InnoDB AUTO_INCREMENT = 1000044 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sitestorepro_products
-- ----------------------------
INSERT INTO `sitestorepro_products` VALUES (1000001, '14k|24k 3 Ct Bracelet', 'This sample item shows how the item can be gift wrapped. Gift wrapping can be per item or for the entire order.&nbsp; This item is marked as a \"New\" item so the label appears on the top-left of the image.', '<p>Round-Cut Diamonds and 14 or 24 K Yellow Gold. The perfect gift for that special someone! <br>3.0 total carat weight for all 48 diamonds in bracelet. Individual Diamonds: H Color, VVs2 Clarity. Diamonds are certified by GIA.</p>', 'Gift Wrap This Item!', NULL, NULL, NULL, NULL, NULL, 'sample-sku-1000001', 1001, 1, 1, 100008, 7598.00, 7598.00, 0.00, 7560.00, 0, 0, 1, 1, 0.5, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 'Ship With Gift Box?', 'Enter Gift Card Message (Optional):', 0, 0, NULL, 1, 0, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, 0, 0, NULL, NULL, ' 22, 20,', ' 2,', NULL, NULL, NULL, NULL, NULL, NULL, 'sample-sku-1000001', 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 3, NULL, 0, 365, 15, NULL, 0, 0, NULL, NULL, 0, NULL, NULL, 0, 0, 0, 'Personalize Item?', 'Please enter your item personalization information:', 0, 1, 9067098, '2011-01-06 17:21:39', 2, '2023-05-16 21:21:18', 392, 3, NULL, 1, NULL, 1, 'You have exceeded the max number of items allowed for this item. The maximum number (QTY) you can purchase of this item is: ', 'Please Select', 'Please make a selection', '14k|24k 3 ct bracelet sample item shows item gift wrapped. gift wrapping per item entire order. item marked new item so label appears top-left image. prestige design custom jewelry bracelets gold bracelets round-cut diamonds 14 24 yellow gold. perfect gift special someone 3.0 total carat weight 48 diamonds bracelet. individual diamonds: color vvs2 clarity. diamonds certified gia. gift wrap item 1000001 sample-sku-1000001 sample-sku-1000001 ', 'Please Select', 'Please make a selection', 'Total : ', NULL, NULL, NULL, NULL, 'Stock Level: ', NULL, NULL, 'Buy Now', 'Quick-Shop', NULL, NULL, '7306D686', 2, 1, 0, 0, NULL, 0, 0, 0, 1, 0, 0, 1, 0, 15, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, 0, 1, 0, 0, 0, 0, 0, 0, '14k-24k-3-ct-bracelet', NULL, 7, 'Product Info', 'Video', 'Tell-A-Friend', 'Product Reviews', 'Add Review', NULL, '<strong>One Time Fee(s)<strong>: ', '<strong>Total One-Time Fee(s)<strong>:', 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 1000, NULL, 25, 0, NULL, 'This sample item shows how the item can be gift wrapped and a card can be included to the recipient. Gift wrapping can be per item or for the entire order. The gift wrapping features are built-in to the cart and controlled via the admin area. This item show the standard QTY input field. Item\'s can have either a QTY input field, a QTY list or have no QTY selection and automatically default to a QTY of 1.', 0, 'Add-to-Cart', 1, 'More Info', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 600, NULL, NULL, NULL, NULL, NULL, 'You did not enter the order minimum amount for this item. The minimum number you can purchase of this item is: ', '2023-05-16 21:21:18', NULL, 0, NULL, 0, 1, 0, 7, 0.00, NULL, 0, 3, 0);
INSERT INTO `sitestorepro_products` VALUES (1000002, 'Heart Of Sapphire Ring', 'This item shows different options such as size and material and personalization. All features and options are controlled via the web-based admin.', 'Beautiful sapphire surrounded by diamonds (1/4 ct. t.w.) Available in 14k, 24K or platinum. <br><br>Perfect gift for birthdays and graduations!', NULL, NULL, NULL, NULL, NULL, NULL, 'sample-sku-1000002', 1003, 1, 3, 0, 399.99, 399.99, 0.00, 0.00, 0, 0, 1, 1, 0.3, 1, 1, 1, 3, 0, 0, 0, 0, 0, 0, 0, 0, 'Gift Wrap Item?', 'Please enter a message to be included on a gift card with the wrapped gift (optional):', 0, 0, NULL, 1, 0, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, 0, 0, NULL, NULL, ' 22, 20, 26,', ' 4, 6, 2, 3, 5, 7,', ' 123, 124, 125, 126, 127, 128, 129, ', ' 5, 5.5, 6, 6.5, 7, 7.5, 8', '', '', ' 5, 6, 7,', ' 14 K Gold, 24 K Gold (+$25.00), Platinum (+$35.00),', 'sample-sku-1000002', 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 0, NULL, 0, 365, 15, NULL, 0, 0, NULL, NULL, 0, NULL, NULL, 0, 0, 1, 'Personalize Item?', 'Please enter your initials:', 1, 1, 9067098, '2011-01-06 17:59:18', 2, '2023-06-06 22:53:25', 1228, 3, NULL, 1, NULL, 1, 'You have exceeded the max number of items allowed for this item. The maximum number (QTY) you can purchase of this item is: ', 'Please Select', 'Please make a selection', 'heart sapphire ring item shows different options such size material personalization features options controlled via web based 5 5 5 6 6 5 7 7 5 8 14 gold 24 gold 25 00 platinum 35 00 old heritage custom jewelry rings beautiful sapphire surrounded diamonds 1 4 ct t w available 14k 24k platinum perfect gift birthdays graduations 1000002 sample sku 1000002 sample sku 1000002 ', 'Please Select', 'Please make a selection', 'Ring Total:', 'This item shows different options such as size and material and personalization. All features and options are controlled via the web-based admin.', NULL, NULL, NULL, 'Stock Level: ', NULL, NULL, 'Buy Now', 'Quick-Shop', NULL, NULL, '1E3239EF', 0, 1, 0, 0, NULL, 0, 0, 0, 1, 0, 0, 1, 0, 15, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, 0, 1, 0, 0, 0, 0, 0, 0, 'heart-of-sapphire-ring', NULL, 8, 'Product Info', 'Video', 'Tell-A-Friend', 'Product Reviews', 'Add Review', NULL, '<strong>One Time Fee(s)<strong>: ', '<strong>Total One-Time Fee(s)<strong>:', 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 1000, NULL, 25, 0, NULL, 'This item shows different options such as size and material and personalization. All features and options are controlled via the web-based admin. This is an alternate short description field. An item can have seperate short descriptions for the search results, quick view and item view pages as well as the long description at the bottom of the page. This item is also setup with a alternate layout (Left side images) Site Store Pro comes with 5 built-in item layouts and unlimited custom layout functionality.', 0, 'Add-to-Cart', 1, 'More Info', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 600, NULL, NULL, NULL, NULL, NULL, 'You did not enter the order minimum amount for this item. The minimum number you can purchase of this item is: ', '2023-06-06 22:53:25', NULL, 0, NULL, 0, 1, 0, 7, 0.00, NULL, 0, 3, 0);
INSERT INTO `sitestorepro_products` VALUES (1000003, 'Diamond Mosaic Ring', 'This item shows a standard layout with a QTY input field and standard layout (product image on the right side of the item view page).', '<p>uDiamond flower ring with amazing, intricate design.. Diamonds are set in 14 K white gold. Platinum Available.</p>', '14 K Gold or Platinum', '1/8 Total Carat Weight', NULL, NULL, 'Brilliant Diamond Ring!. Make a great gift for that special someone! Exclusively online at Prestige Jewerly.', 'diamonds, ring, gift, brilliant', 'sample-sku-1000003', 1003, 1, 1, 0, 299.99, 265.00, 0.00, 0.00, 0, 0, 1, 1, 0.3, 1, 1, 0, 0, 1, 2, 0, 0, 0, 0, 0, 0, 'Gift Wrap Item?', 'Please enter a message to be included on a gift card with the wrapped gift (optional):', 1, 0, NULL, 1, 0, NULL, 0, '', '', NULL, 0, '', '', NULL, 0, NULL, NULL, 0, '', '', 0, 0, NULL, '', ' 22, 20,', ' 2, 7,', ' 123, 124, 125, 126, 127, 128, 129, ', ' 5, 5.5, 6, 6.5, 7, 7.5, 8', '', '', NULL, NULL, 'sample-sku-1000003', 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 1, NULL, 0, 365, 15, NULL, 0, 0, NULL, NULL, 0, NULL, NULL, 0, 0, 0, 'Personalize Item?', 'Please enter your item personalization information:', 0, 1, 9067098, '2011-01-09 21:46:39', 1, '2021-09-26 16:56:20', 613, 1, NULL, 1, NULL, 1, 'You have exceeded the max number of items allowed for this item. The maximum number (QTY) you can purchase of this item is: ', 'Please Select', 'Please make a selection', 'diamond mosaic ring item shows standard layout qty input field standard layout product image right side item view page. 5 5.5 6 6.5 7 7.5 8 prestige design custom jewelry rings udiamond flower ring amazing intricate design.. diamonds set 14 white gold. platinum available. 14 gold platinum 1/8 total carat weight brilliant diamond ring . make great gift special someone exclusively online prestige jewerly. 1000003 diamonds ring gift brilliant sample_003 ', 'Please Select', 'Please make a selection', 'Total : ', NULL, NULL, NULL, NULL, 'Stock Level: ', NULL, NULL, NULL, 'Quick-Shop', NULL, NULL, 'E5A46A24', 1, 1, 0, 0, 0, 0, 0, 0, 1, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, 0, 1, 0, 0, 0, 0, 0, 0, 'diamond-mosaic-ring', NULL, 0, 'Product Info', 'Video', 'Tell-A-Friend', 'Product Reviews', 'Add Review', NULL, '<strong>One Time Fee(s)<strong>: ', '<strong>Total One-Time Fee(s)<strong>:', 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 1000, NULL, 25, 0, NULL, 'This item shows a standard layout with a QTY input field. Item quantity control can be an input field with a +/- selector, a QTY selection list or a default QTY of 1. You can also set min and max QTY values for each individual item so that the customer is limited to how many they can purchase regardless of stock level.&nbsp; This item is using the standard (default) item layout where the image(s) are display on the right side of the page and the title, short descripton, options and pricing (add-to-cart form) is displayed on the left. All features are set in real-time via the included web-based admin system.', 0, 'Add-to-Cart', 1, 'More Info', 1, 0, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 600, NULL, NULL, NULL, NULL, NULL, 'You did not enter the order minimum amount for this item. The minimum number you can purchase of this item is: ', '2021-09-26 16:56:20', NULL, 0, NULL, 0, 1, 0, 7, 0.00, NULL, 0, 3, 0);
INSERT INTO `sitestorepro_products` VALUES (1000004, '14K Ring With Cultured Pearl And Diamonds', 'This sample item shows how the product can be offered with personalization and gift wrap options.', '<p>Beautiful cultured pearl with 6 diamonds set in a shimmering 14 K gold ring.</p>\r\n<p>This sample item shows how the product can be offered with personalization and gift wrap options.</p>', NULL, NULL, NULL, NULL, NULL, NULL, 'sample-sku-1000004', 1003, 1, 4, 0, 789.00, 780.00, 0.00, 0.00, 0, 0, 1, 1, 0.4, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 'Ship With Gift Box?', 'Please enter a message to be included on a gift card with the wrapped gift (optional):', 1, 0, NULL, 1, 0, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, 0, 0, NULL, NULL, ' 20, 23,', ' 2, 5, 7,', ' 123, 124, 125, 126, 127, 128, 129, ', ' 5, 5.5, 6, 6.5, 7, 7.5, 8', NULL, NULL, NULL, NULL, 'sample-sku-1000004', 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 0, NULL, 0, 365, 15, NULL, 0, 0, NULL, NULL, 0, NULL, NULL, 0, 0, 1, 'Add Ring Inscription?', 'Please enter your item personalization information (Ring Inscription)', 3, 1, 9067098, '2011-01-09 21:59:47', 2, '2023-06-06 22:51:08', 944, 2, NULL, 1, NULL, 1, 'You have exceeded the max number of items allowed for this item. The maximum number (QTY) you can purchase of this item is: ', 'Please Select', 'Please make a selection', '14k ring cultured pearl diamonds sample item shows product offered personalization gift wrap options 5 5 5 6 6 5 7 7 5 8 bella luna custom jewelry rings beautiful cultured pearl 6 diamonds set shimmering 14 gold ring this sample item shows product offered personalization gift wrap options 1000004 sample sku 1000004 sample sku 1000004 ', 'Please Select', 'Please make a selection', 'Total : ', NULL, NULL, NULL, NULL, 'Stock Level: ', NULL, NULL, 'Buy Now', 'Quick-Shop', NULL, NULL, '833E2704', 1, 1, 0, 0, NULL, 0, 0, 0, 1, 0, 0, 1, 0, 15, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, 0, 1, 0, 0, 0, 0, 0, 0, '14k-ring-cultured-pearl-and-diamonds', NULL, 7, 'Product Info', 'Video', 'Tell-A-Friend', 'Product Reviews', 'Add Review', NULL, '<strong>One Time Fee(s)<strong>: ', '<strong>Total One-Time Fee(s)<strong>:', 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 1000, NULL, 25, 0, NULL, NULL, 0, 'Add-to-Cart', 1, 'More Info', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 600, NULL, NULL, NULL, NULL, NULL, 'You did not enter the order minimum amount for this item. The minimum number you can purchase of this item is: ', '2023-06-06 22:51:08', NULL, 0, NULL, 0, 1, 0, 7, 0.00, NULL, 0, 3, 0);
INSERT INTO `sitestorepro_products` VALUES (1000005, 'Sapphire and Diamond Ring', 'This sample product shows some basic personalization options as well as a real-time total display.', '<p class=\"match_site_look\">This breathtaking ring design presents a single oval-cut sapphire (1-1/2 ct. t.w.), prong-set in a polished 14K white gold, 24 K white gold or platinum band and framed by a sparkling 1/4 ct. t.w. diamonds.</p>', '14K, 24K Or Platinum Band', '1.5 ct Sapphire', '.25 ct Diamonds', NULL, NULL, NULL, 'sample-sku-1000005', 1003, 1, 1, 0, 499.99, 485.00, 0.00, 319.99, 0, 0, 1, 1, 0.3, 1, 1, 0, 0, 1, 3, 0, 0, 0, 0, 0, 0, 'Gift Wrap Item?', 'Please enter a message to be included on a gift card with the wrapped gift (optional):', 0, 0, NULL, 1, 0, NULL, 0, '', '', NULL, 0, '', '', NULL, 0, NULL, NULL, 0, NULL, NULL, 0, 0, NULL, NULL, ' 22, 26,', ' 2, 3, 5,', ' 123, 124, 125, 126, 127, 128, 129, ', ' 5, 5.5, 6, 6.5, 7, 7.5, 8', '', '', '', '', 'sample-sku-1000005', 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 2, NULL, 0, 50, 10, NULL, 0, 0, NULL, NULL, 0, NULL, NULL, 0, 0, 1, 'Add Ring Inscription?', 'Please enter your ring inscription here:', 3, 1, 9067098, '2011-01-09 22:08:53', 2, '2023-05-17 13:38:45', 558, 3, NULL, 1, NULL, 1, 'You have exceeded the max number of items allowed for this item. The maximum number (QTY) you can purchase of this item is: ', 'Please Select', 'Please make a selection', 'sapphire diamond ring sample product shows some basic personalization options well real-time total display. 5 5.5 6 6.5 7 7.5 8 prestige design custom jewelry rings breathtaking ring design presents single oval-cut sapphire 1-1/2 ct. t.w. prong-set polished 14k white gold 24 white gold platinum band framed sparkling 1/4 ct. t.w. diamonds. 14k 24k platinum band 1.5 ct sapphire .25 ct diamonds 1000005 sample-sku-1000005 sample-sku-1000005 ', 'Please Select', 'Please make a selection', 'Ring Total:', NULL, NULL, NULL, NULL, 'Stock Level: ', NULL, NULL, 'Buy Now', 'Quick-Shop', NULL, NULL, 'A431ABC4', 1, 1, 0, 0, NULL, 0, 0, 0, 1, 0, 0, 1, 0, 15, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, 0, 1, 0, 0, 0, 0, 0, 0, 'sapphire-diamond-ring', NULL, 7, 'Product Info', 'Video', 'Tell-A-Friend', 'Product Reviews', 'Add Review', NULL, '<strong>One Time Fee(s)<strong>: ', '<strong>Total One-Time Fee(s)<strong>:', 0, '', NULL, NULL, NULL, NULL, NULL, 0, '', 0, 1000, NULL, 25, 0, NULL, 'This sample product shows the real-time total display when a selection is made on an option.', 0, 'Add-to-Cart', 1, 'More Info', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 600, NULL, NULL, NULL, NULL, NULL, 'You did not enter the order minimum amount for this item. The minimum number you can purchase of this item is: ', '2023-05-17 13:38:45', NULL, 0, NULL, 0, 1, 0, 7, 0.00, NULL, 0, 3, 0);
INSERT INTO `sitestorepro_products` VALUES (1000006, 'Ruby and Diamond Ring with 14K Band', '3/4 ct. rubies set and 1/8 ct. diamonds set in a 14K gold bracelet.', '<p>This ring design has a single red marquis-cut ruby flanked by baguette-cut rubies and fiery diamonds on a gleaming 14K gold band. 3/4 ct. t.w. of rubies and 1/8 ct. t.w. in diamonds. Ruby is the birthstone for July.</p>', NULL, NULL, NULL, NULL, NULL, NULL, 'sample-sku-1000006', 1003, 1, 4, 0, 389.00, 389.00, 0.00, 0.00, 0, 0, 1, 1, 0.3, 1, 1, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 'Gift Wrap Item?', 'Please enter a message to be included on a gift card with the wrapped gift (optional):', 1, 0, NULL, 1, 0, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, 0, 0, NULL, NULL, ' 22, 20, 25,', ' 2, 7,', ' 123, 124, 125, 126, 127, 128, 129, ', ' 5, 5.5, 6, 6.5, 7, 7.5, 8', NULL, NULL, NULL, NULL, 'sample-sku-1000006', 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, NULL, 0, 365, 15, NULL, 0, 0, NULL, NULL, 0, NULL, NULL, 0, 0, 0, 'Personalize Item?', 'Please enter your item personalization information:', 0, 1, 9067098, '2011-01-09 22:16:53', 3, '2014-06-09 19:30:23', 324, 2, NULL, 1, NULL, 1, 'You have exceeded the max number of items allowed for this item. The maximum number (QTY) you can purchase of this item is: ', 'Please Select', 'Please make a selection', 'ruby diamond ring 14k band 3/4 ct. rubies set 1/8 ct. diamonds set 14k gold bracelet. 5 5.5 6 6.5 7 7.5 8 bella luna custom jewelry rings ring design has single red marquis-cut ruby flanked baguette-cut rubies fiery diamonds gleaming 14k gold band. 3/4 ct. t.w. rubies 1/8 ct. t.w. diamonds. ruby birthstone july. 1000006 sample_006 ', 'Please Select', 'Please make a selection', 'Total : ', NULL, NULL, NULL, NULL, 'Stock Level: ', NULL, NULL, NULL, 'Quick-Shop', NULL, NULL, 'W2D292W', 1, 1, 0, 0, 0, 0, 0, 0, 1, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, 0, 1, 0, 0, 0, 0, 0, 0, 'ruby-diamond-ring-14k-b', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, '<strong>One Time Fee(s)<strong>: ', '<strong>Total One-Time Fee(s)<strong>:', 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 1000, NULL, 25, 0, NULL, NULL, 0, 'Add-to-Cart', 1, 'More Info', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 600, NULL, NULL, NULL, NULL, NULL, 'You did not enter the order minimum amount for this item. The minimum number you can purchase of this item is: ', '2014-08-11 11:36:01', NULL, 0, NULL, 0, 1, 0, 7, 0.00, NULL, 0, 3, 0);
INSERT INTO `sitestorepro_products` VALUES (1000007, 'Diamond Wave Bracelet', 'A vintage-inspired design with modern sparkle. Measures 7-1/4 inches', '<p>A vintage-inspired design with modern sparkle. Measures 7-1/4 inches</p>', NULL, NULL, NULL, NULL, NULL, NULL, 'sample-sku-1000007', 1001, 1, 1, 100006, 699.99, 699.99, NULL, 649.99, 1, 1, 1, 1, 0.3, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 'Gift Wrap Item?', 'Please enter a message to be included on a gift card with the wrapped gift (optional):', 1, 0, NULL, 1, 0, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, 0, 0, NULL, NULL, ' 20, 22, 31,', ' 4, 2, 5,', NULL, NULL, '', '', NULL, NULL, 'sample-sku-1000007', 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, NULL, 0, 365, 15, NULL, 0, 0, NULL, NULL, 0, NULL, NULL, 0, 0, 0, 'Personalize Item?', 'Please enter your item personalization information:', 0, 1, 9067098, '2011-01-09 22:34:19', 2, '2023-06-11 17:53:04', 207, 1, NULL, 1, NULL, 1, 'You have exceeded the max number of items allowed for this item. The maximum number (QTY) you can purchase of this item is: ', 'Please Select', 'Please make a selection', 'diamond wave bracelet vintage inspired design modern sparkle measures 7 1 4 inches prestige design custom jewelry bracelets diamond bracelets vintage inspired design modern sparkle measures 7 1 4 inches 1000007 sample sku 1000007 sample sku 1000007 ', 'Please Select', 'Please make a selection', 'Total : ', NULL, NULL, NULL, NULL, 'Stock Level: ', NULL, NULL, 'Buy Now', 'Quick-Shop', NULL, NULL, 'RD9D33B3', 3, 1, 0, 0, NULL, 0, 0, 0, 1, 0, 0, 0, 0, 15, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, 0, 1, 0, 0, 0, 0, 0, 0, 'diamond-wave-bracelet', NULL, 7, 'Product Info', 'Video', 'Tell-A-Friend', 'Product Reviews', 'Add Review', NULL, '<strong>One Time Fee(s)<strong>: ', '<strong>Total One-Time Fee(s)<strong>:', 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 1000, NULL, 25, 0, NULL, NULL, 1, 'Add-to-Cart', 1, 'More Info', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 600, NULL, NULL, NULL, NULL, NULL, 'You did not enter the order minimum amount for this item. The minimum number you can purchase of this item is: ', '2023-06-11 17:53:04', NULL, 0, NULL, 0, 1, 0, 7, 0.00, NULL, 0, 3, 0);
INSERT INTO `sitestorepro_products` VALUES (1000008, 'Pinched Style Diamond Bracelet', 'This item is marked on \"Special\" as well as \"Clearance\".', '<p>Pinched detail make this an ideal bracelet for both social and business attire. Round-cut diamonds (1 ct. t.w.) are set in 14k white gold. Measures 7\".</p>', 'Available in 14K or 24K White Gold', NULL, NULL, NULL, NULL, NULL, 'sample-sku-1000008', 1001, 1, 1, 100006, 999.99, 999.99, 0.00, 750.00, 1, 3, 1, 1, 0.3, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 'Gift Wrap Item?', 'Please enter a message to be included on a gift card with the wrapped gift (optional):', 1, 0, NULL, 1, 0, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, 0, 0, NULL, NULL, ' 22, 20,', ' 2,', NULL, NULL, NULL, NULL, NULL, NULL, 'sample-sku-1000008', 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 3, NULL, 0, 365, 15, NULL, 0, 0, NULL, NULL, 0, NULL, NULL, 0, 0, 0, 'Personalize Item?', 'Please enter your item personalization information:', 0, 1, 9067098, '2011-01-09 22:51:58', 2, '2023-06-11 17:51:50', 123, 6, NULL, 1, NULL, 1, 'You have exceeded the max number of items allowed for this item. The maximum number (QTY) you can purchase of this item is: ', 'Please Select', 'Please make a selection', 'pinched style diamond bracelet item marked special well clearance prestige design custom jewelry bracelets diamond bracelets pinched detail make ideal bracelet both social business attire round cut diamonds 1 ct t w set 14k white gold measures 7 available 14k 24k white gold 1000008 sample sku 1000008 sample sku 1000008 ', 'Please Select', 'Please make a selection', 'Total : ', NULL, NULL, NULL, NULL, 'Stock Level: ', NULL, NULL, 'Buy Now', 'Quick-Shop', NULL, NULL, '55CB157W', 1, 1, 0, 0, NULL, 0, 0, 0, 1, 0, 0, 0, 0, 15, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, 0, 1, 0, 0, 0, 0, 0, 0, 'pinched-style-diamond-bracelet', NULL, 7, 'Product Info', 'Video', 'Tell-A-Friend', 'Product Reviews', 'Add Review', NULL, '<strong>One Time Fee(s)<strong>: ', '<strong>Total One-Time Fee(s)<strong>:', 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 1000, NULL, 25, 0, NULL, NULL, 1, 'Add-to-Cart', 1, 'More Info', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 600, NULL, NULL, NULL, NULL, NULL, 'You did not enter the order minimum amount for this item. The minimum number you can purchase of this item is: ', '2023-06-11 17:51:50', NULL, 0, NULL, 0, 1, 0, 7, 0.00, NULL, 0, 3, 0);
INSERT INTO `sitestorepro_products` VALUES (1000009, 'Diamond Heart Bracelet With Your Initials Inscribed', 'This sample item shows the product personalization feature that can be included for items as an additional fee or a bundled service.', '<p>The ultimate gift for your sweatheart!. This bracelet has brilliant diamonds (1/4 ct. t.w.) set in heart-shaped links of polished 14K white gold.<br><br></p>\r\n<p>Inscribe your initials or a short message to personalize it for yourself or as a gift!</p>', NULL, NULL, NULL, NULL, NULL, NULL, 'sample-sku-1000009', 1001, 1, 1, 100007, 239.99, 215.00, 0.00, 205.99, 0, 0, 1, 1, 0.32, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 'Gift Wrap Item?', 'Please enter a message to be included on a gift card with the wrapped gift (optional):', 1, 0, NULL, 1, 0, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, 0, 0, NULL, NULL, ' 22, 20,', ' 4, 6, 3,', NULL, NULL, NULL, NULL, NULL, NULL, 'sample-sku-1000009', 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, NULL, 0, 365, 15, NULL, 0, 0, NULL, NULL, 0, NULL, NULL, 0, 0, 1, 'Personalize Item?', 'Please enter the initials to be etched on this bracelet here.', 1, 1, 9067098, '2011-01-09 23:01:22', 2, '2023-05-16 21:20:15', 334, 2, NULL, 1, NULL, 1, 'You have exceeded the max number of items allowed for this item. The maximum number (QTY) you can purchase of this item is: ', 'Please Select', 'Please make a selection', 'diamond heart bracelet initials inscribed sample item shows product personalization feature included items an additional fee bundled service. prestige design custom jewelry bracelets white gold bracelets ultimate gift sweatheart . bracelet has brilliant diamonds 1/4 ct. t.w. set heart-shaped links polished 14k white gold. inscribe initials short message personalize yourself gift 1000009 sample-sku-1000009 sample-sku-1000009 ', 'Please Select', 'Please make a selection', 'Total : ', NULL, NULL, NULL, NULL, 'Stock Level: ', NULL, NULL, 'Buy Now', 'Quick-Shop', NULL, NULL, 'Q5A34732', 1, 1, 0, 0, NULL, 0, 0, 0, 1, 0, 0, 1, 0, 15, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, 0, 1, 0, 0, 0, 0, 0, 0, '14k-white-gold-diamond-heart-bracelet', NULL, 7, 'Product Info', 'Video', 'Tell-A-Friend', 'Product Reviews', 'Add Review', NULL, '<strong>One Time Fee(s)<strong>: ', '<strong>Total One-Time Fee(s)<strong>:', 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 1000, NULL, 25, 0, NULL, NULL, 0, 'Add-to-Cart', 1, 'More Info', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 600, NULL, NULL, NULL, NULL, NULL, 'You did not enter the order minimum amount for this item. The minimum number you can purchase of this item is: ', '2023-05-16 21:20:15', NULL, 0, NULL, 0, 1, 0, 7, 0.00, NULL, 0, 3, 0);
INSERT INTO `sitestorepro_products` VALUES (1000010, '14k Or 24K White Gold 2 Carat Diamond Bracelet', 'Shimmering round-cut diamonds (2 ct. t.w.) are set in an elegant14k or 24K gold wave setting.', '<p>Shimmering round-cut diamonds (2 ct. t.w.) are set in an elegant14k or 24K gold wave setting.</p>', '14K or 24K White Gold', '7\"', NULL, NULL, NULL, NULL, 'sample-sku-1000010', 1001, 1, 1, 100007, 1850.00, 1850.00, 0.00, 1760.00, 1, 2, 1, 1, 0.41, 1, 1, 0, 0, 1, 5, 0, 0, 0, 0, 0, 0, 'Gift Wrap Item?', 'Please enter a message to be included on a gift card with the wrapped gift (optional):', 1, 0, NULL, 1, 0, '1,2,3,4,5,6,7,8,9,10', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, 0, 0, NULL, NULL, ' 22, 20,', ' 4, 5,', NULL, NULL, NULL, NULL, NULL, NULL, 'sample-sku-1000010', 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, NULL, 0, 365, 15, NULL, 0, 0, NULL, NULL, 0, NULL, NULL, 0, 0, 1, 'Personalize Item?', 'Please enter your message inscription here.', 3, 1, 9067098, '2011-01-09 23:07:37', 2, '2023-06-11 17:53:24', 331, 3, NULL, 1, NULL, 1, 'You have exceeded the max number of items allowed for this item. The maximum number (QTY) you can purchase of this item is: ', 'Please Select', 'Please make a selection', '14k 24k white gold 2 carat diamond bracelet shimmering round cut diamonds 2 ct t w set elegant14k 24k gold wave setting prestige design custom jewelry bracelets white gold bracelets shimmering round cut diamonds 2 ct t w set elegant14k 24k gold wave setting 14k 24k white gold 7 1000010 sample sku 1000010 sample sku 1000010 ', 'Please Select', 'Please make a selection', 'Total : ', NULL, NULL, NULL, NULL, 'Stock Level: ', NULL, NULL, 'Buy Now', 'Quick-Shop', NULL, NULL, 'ZVFAC448', 3, 1, 0, 0, NULL, 0, 0, 0, 1, 0, 0, 1, 0, 15, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, 0, 1, 0, 0, 0, 0, 0, 0, '14k-or-24k-white-gold-2-carat-diamond-bracelet', NULL, 7, 'Product Info', 'Video', 'Tell-A-Friend', 'Product Reviews', 'Add Review', NULL, '<strong>One Time Fee(s)<strong>: ', '<strong>Total One-Time Fee(s)<strong>:', 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 1000, NULL, 25, 0, NULL, NULL, 0, 'Add-to-Cart', 1, 'More Info', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 600, NULL, NULL, NULL, NULL, NULL, 'You did not enter the order minimum amount for this item. The minimum number you can purchase of this item is: ', '2023-06-11 17:53:24', NULL, 0, NULL, 0, 1, 0, 7, 0.00, NULL, 0, 3, 0);
INSERT INTO `sitestorepro_products` VALUES (1000011, '18k Gold 5 Carat GIA Certified Diamond Bracelet', 'The classic diamond bracelet. Sparkling, round-cut diamonds are set in 18k gold.', '<p>The classic diamond bracelet. Sparkling, round-cut diamonds are set in 18k gold (5 ct. t.w.). Color: G-H. Clarity: SI(1)/SI(2). Measures 7\". Includes GIA certificate listing the cut, carat weight, color and clarity grades.</p>', '5 carat total weight', '18K', NULL, NULL, NULL, NULL, 'sample-sku-1000011', 1001, 1, 1, 100006, 7999.99, 7999.99, 0.00, 7159.99, 0, 0, 1, 1, 0.3, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 'Gift Wrap Item?', 'Please enter a message to be included on a gift card with the wrapped gift (optional):', 1, 0, NULL, 1, 0, NULL, 0, '', '', NULL, 0, '', '', '', 0, NULL, NULL, 0, '', '', 0, 0, NULL, '', ' 22, 20,', ' 2,', NULL, NULL, NULL, NULL, NULL, NULL, 'sample-sku-1000011', 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 1, NULL, 0, 365, 15, NULL, 0, 0, NULL, NULL, 0, NULL, NULL, 0, 0, 0, 'Personalize Item?', 'Please enter your item personalization information:', 0, 1, 9067098, '2011-01-09 23:14:38', 1, '2020-05-24 16:45:33', 488, 2, NULL, 1, NULL, 1, 'You have exceeded the max number of items allowed for this item. The maximum number (QTY) you can purchase of this item is: ', 'Please Select', 'Please make a selection', '18k gold 5 carat gia certified diamond bracelet classic diamond bracelet. sparkling round-cut diamonds set 18k gold. prestige design custom jewelry bracelets diamond bracelets classic diamond bracelet. sparkling round-cut diamonds set 18k gold 5 ct. t.w.. color: g-h. clarity: si1/si2. measures 7. includes gia certificate listing cut carat weight color clarity grades. 5 carat total weight 18k 1000011 sample_011 ', 'Please Select', 'Please make a selection', 'Total : ', NULL, NULL, NULL, NULL, 'Stock Level: ', NULL, NULL, NULL, 'Quick-Shop', NULL, NULL, '2CA34F4V', 2, 1, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, 0, 1, 0, 0, 0, 0, 0, 0, '18k-gold-5-carat-gia-certified-diamond-bracelet', NULL, 0, 'Product Info', 'Video', 'Tell-A-Friend', 'Product Reviews', 'Add Review', NULL, '<strong>One Time Fee(s)<strong>: ', '<strong>Total One-Time Fee(s)<strong>:', 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 1000, NULL, 25, 0, NULL, NULL, 1, 'Add-to-Cart', 1, 'More Info', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 600, NULL, NULL, NULL, NULL, NULL, 'You did not enter the order minimum amount for this item. The minimum number you can purchase of this item is: ', '2020-05-24 16:45:33', NULL, 0, NULL, 0, 1, 0, 7, 0.00, NULL, 0, 3, 0);
INSERT INTO `sitestorepro_products` VALUES (1000012, 'Ruby and Diamond Bracelet', 'Buy 1-2 = $999.99/each Buy 3-4 = $949.99/each Buy 5-10 = $910.99/each Buy more than 10 = $899.99/each!', '<p>7\" bracelet with marquis-cut rubies (6 5/8 ct. t.w.) and diamond accents set in a 14K or 24K gold. <br><br><strong>Buy More .. Save More ! </strong><br><br>Buy 1-2 = $999.99/each <br>Buy 3-4 = $949.99/each <br>Buy 5-9 = $910.99/each <br>Buy more than 10 = $899.99/each!</p>', NULL, NULL, NULL, NULL, NULL, NULL, 'sample-sku-1000012', 1001, 1, 1, 100009, 999.99, 955.00, 0.00, 810.99, 0, 0, 1, 1, 0.3, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 'Gift Wrap Item?', 'Please enter a message to be included on a gift card with the wrapped gift (optional):', 1, 1, '1-2=999.99,3-4=949.99,5-9=910.99,Other=899.99', 1, 0, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, 0, 0, NULL, NULL, ' 22, 20, 25,', ' 2,', NULL, NULL, NULL, NULL, NULL, NULL, 'sample-sku-1000012', 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 0, NULL, 0, 365, 15, NULL, 0, 0, NULL, NULL, 0, NULL, NULL, 0, 0, 0, 'Personalize Item?', 'Please enter your item personalization information:', 0, 1, 9067098, '2011-01-10 08:58:03', 2, '2023-07-09 18:19:27', 217, 0, NULL, 1, NULL, 1, 'You have exceeded the max number of items allowed for this item. The maximum number (QTY) you can purchase of this item is: ', 'Please Select', 'Please make a selection', 'ruby diamond bracelet buy 1 2 999 99 each buy 3 4 949 99 each buy 5 10 910 99 each buy more than 10 899 99 each prestige design custom jewelry bracelets precious stones bracelets 7 bracelet marquis cut rubies 6 5 8 ct t w diamond accents set 14k 24k gold buy more save more buy 1 2 999 99 each buy 3 4 949 99 each buy 5 9 910 99 each buy more than 10 899 99 each 1000012 sample sku 1000012 sample sku 1000012 ', 'Please Select', 'Please make a selection', 'Item Total : ', NULL, NULL, NULL, NULL, 'Stock Level: ', NULL, NULL, 'Buy Now', 'Quick-Shop', NULL, NULL, '3151D1D3', 0, 1, 0, 0, NULL, 0, 0, 0, 1, 0, 0, 1, 0, 15, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, 0, 1, 0, 0, 0, 0, 0, 0, 'ruby-diamond-bracelet', NULL, 7, 'Product Info', 'Video', 'Tell-A-Friend', 'Product Reviews', 'Add Review', NULL, '<strong>One Time Fee(s)<strong>: ', '<strong>Total One-Time Fee(s)<strong>:', 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 1000, NULL, 25, 0, NULL, '<p>This sample product shows how you can setup a QTY based discount for an item and also display the total to the customer when they select a new QTY.<br><br>Buy 1-2 = $999.99/each</p>\r\n<p>Buy 3-4 = $949.99/each</p>\r\n<p>Buy 5-9 = $910.99/each</p>\r\n<p>Buy more than 10 = $899.99/each</p>', 0, 'Add-to-Cart', 1, 'More Info', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 600, NULL, NULL, NULL, NULL, NULL, 'You did not enter the order minimum amount for this item. The minimum number you can purchase of this item is: ', '2023-07-09 18:19:27', NULL, 0, NULL, 0, 1, 0, 7, 0.00, NULL, 0, 3, 0);
INSERT INTO `sitestorepro_products` VALUES (1000013, 'Sapphire, Ruby And Emerald Bracelet', 'Default item display with no additional features except recommended items.', '<p>The setup of this item is the default (right side product media and a left page sidebar) for any new item added to the store until other features are turned on and/or an alternate item or page view layout is selected. The only feature that was \"activated\" for this default item example is the \"cross-selling\" list of recommended items below.&nbsp;<br><br>The bullet list below is the default item feature highlights display but that can also be substituted with additional lists and alternative product feature highlights and content.</p>', 'Mearures 7 1/4\"', 'Intricate Design', 'Emeralds, Sapphires & Rubies', 'Sterling Silver with 18K Gold & Diamond Accents', NULL, NULL, 'sample-sku-1000013', 1001, 1, 1, 100009, 2170.00, 2000.00, 0.00, 1979.00, 0, 0, 1, 1, 0.3, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 'Gift Wrap Item?', 'Please enter a message to be included on a gift card with the wrapped gift (optional):', 1, 0, NULL, 1, 0, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, 0, 0, NULL, NULL, ' 24, 25, 26,', ' 2,', NULL, NULL, NULL, NULL, NULL, NULL, 'sample-sku-1000013', 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, NULL, 0, 365, 15, NULL, 0, 0, NULL, NULL, 0, NULL, NULL, 0, 0, 0, 'Personalize Item?', 'Please enter your item personalization information:', 0, 1, 9067098, '2011-01-10 09:13:03', 2, '2023-06-04 15:18:58', 839, 18, NULL, 1, NULL, 1, 'You have exceeded the max number of items allowed for this item. The maximum number (QTY) you can purchase of this item is: ', 'Please Select', 'Please make a selection', 'sapphire ruby emerald bracelet default item display no additional features except recommended items. prestige design custom jewelry bracelets precious stones bracelets setup item default right side product media left page sidebar any new item added store until other features turned and/or an alternate item page view layout selected. only feature activated default item example cross-selling list recommended items below. the bullet list below default item feature highlights display also substituted additional lists alternative product feature highlights content. mearures 7 1/4 intricate design emeralds sapphires & rubies sterling silver 18k gold & diamond accents 1000013 sample-sku-1000013 sample-sku-1000013 ', 'Please Select', 'Please make a selection', 'Total : ', 'This is the default setup (layout) for a product.', NULL, NULL, NULL, 'Stock Level: ', NULL, NULL, 'Buy Now', 'Quick-Shop', NULL, NULL, 'C9BC766F', 2, 14, 0, 0, NULL, 0, 0, 0, 1, 0, 0, 0, 0, 15, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, 0, 1, 0, 0, 0, 0, 0, 0, 'sapphire-ruby-emerald-bracelet', NULL, 7, 'Product Info', 'Video', 'Tell-A-Friend', 'Product Reviews', 'Add Review', NULL, '<strong>One Time Fee(s)<strong>: ', '<strong>Total One-Time Fee(s)<strong>:', 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 1000, NULL, 25, 0, NULL, 'This is the basic (default) setup for an item when no alternate layout and/or product options are assigned in the product manager.', 1, 'Add-to-Cart', 1, 'More Info', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 600, NULL, NULL, NULL, NULL, NULL, 'You did not enter the order minimum amount for this item. The minimum number you can purchase of this item is: ', '2023-06-04 15:18:58', NULL, 0, NULL, 0, 1, 0, 7, 0.00, NULL, 0, 3, 0);
INSERT INTO `sitestorepro_products` VALUES (1000014, 'Jewelry Cleaning eBOOK', 'This item shows how to offer a downloadable ebook as well as displaying different images on search results and item view pages.', '<p>Learn how to clean jewelry like the pros with this instructional eBOOK. Available as an <strong style=\"background-color: #ccffff;\"><em>instant download</em>!<br></strong></p>', 'Instant Download', 'PDF Format', '45 Pages', '26 Full-Color Illustrations', NULL, NULL, 'sample-sku-1000014', 1004, 3, 1, 0, 19.99, 10.00, 0.00, 0.00, 0, 0, 0, 0, 0.5, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 'Gift Wrap Item?', 'Please enter a message to be included on a gift card with the wrapped gift (optional):', 1, 0, NULL, 1, 0, NULL, 0, '', '', NULL, 0, '', '', NULL, 0, NULL, NULL, 0, '', '', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sample-sku-1000014', 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, NULL, 1, 365, 500, '/store_content/downloads/1000014_3PTU2SI3409/sample_ebook.pdf', 0, 0, NULL, 'This is a downloadable item. Upon completion of your order you will be provided instant access to download this item through your online account manager.', 0, NULL, NULL, 0, 0, 0, 'Personalize Item?', 'Please enter your item personalization information:', 0, 1, 9067098, '2011-01-11 15:24:24', 2, '2023-06-22 16:47:45', 1448, 84, NULL, 1, NULL, 1, 'You have exceeded the max number of items allowed for this item. The maximum number (QTY) you can purchase of this item is: ', 'Please Select', 'Please make a selection', 'jewelry cleaning ebook item shows offer downloadable ebook well displaying different images search results item view pages prestige design downloads amp videos pdf downloads learn clean jewelry like pros instructional ebook available instant download instant download pdf format 45 pages 26 full color illustrations 1000014 sample sku 1000014 sample sku 1000014 ', 'Please Select', 'Please make a selection', 'Total : ', 'This is an alternate product description that only appears on the \'quick shop\" (quick view) modal window.', NULL, 'MyeBook', NULL, 'Stock Level: ', NULL, NULL, 'Buy Now', 'Quick-Shop', NULL, NULL, '8E014F66', 1, 1, 0, 0, NULL, 0, 0, NULL, 1, 0, 0, 0, 0, 15, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, 1, 1, 1, 1, 1, 1, 1, 1, 'cleaning-jewelry-101-instructional-ebook', NULL, 7, 'Product Info', 'Video', 'Tell-A-Friend', 'Product Reviews', 'Add Review', NULL, '<strong>One Time Fee(s)<strong>: ', '<strong>Total One-Time Fee(s)<strong>:', 0, '', NULL, NULL, NULL, NULL, NULL, 0, '', 0, 1000, NULL, 25, 0, NULL, 'The images displayed to the right are only shown on this page. While the image that appears in the search results does not appear on this page. You can upload an unlimited number of images per product and decide which one is shown on the search results and which image(s) appear on this page.', 1, 'Add-to-Cart', 1, 'More Info', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'You did not enter the order minimum amount for this item. The minimum number you can purchase of this item is: ', '2023-06-22 16:47:45', NULL, 0, NULL, 0, 1, 0, 7, 0.00, NULL, 0, 3, 0);
INSERT INTO `sitestorepro_products` VALUES (1000015, 'Men\'s Logo Sweatshirt', 'This sample item shows common product options such as color, size as well as the option upcharge feature.', '<h2>Sample Clothing Product</h2>\r\n<p>This sample item shows common product options as well as the image viewer that responds to color selection changes.&Acirc;&nbsp; Products can have unlimited images and different images can be assigned to appear on the search results and/or the item view.&Acirc;&nbsp;</p>\r\n<p>This item includes an upcharge on the XXL size to demonstrate how an additional fee can be assigned to a specific product variation.&Acirc;&nbsp; (Any option for an item can have an additional fee and/or weight adjustments).</p>\r\n<p><strong>This sample product uses the \'Option-Specific\' (advanced) inventory feature where product variations have different stock levels and SKU numbers.</strong></p>\r\n<p>All features for the item are set in the admin including all display elements and labels.<br><br></p>\r\n<p><a class=\"img-zoom\" href=\"https://cdn-demo-store.s3.us-west-1.amazonaws.com/shopping-cart-sample-product-image-love-sweats.jpg\"><img src=\"https://cdn-demo-store.s3.us-west-1.amazonaws.com/shopping-cart-sample-product-image-love-sweats.jpg\" class=\"img-responsive\" alt=\"\"></a></p>', NULL, NULL, NULL, NULL, NULL, NULL, 'sample-sku-1000015', 1008, 5, 2, 0, 25.00, 22.00, 0.00, 0.00, 0, 0, 1, 1, 0.25, 1, 1, 1, 1, 0, 0, 1, 0, 0, 0, 0, 0, 'Gift Wrap Item?', 'Please enter a message to be included on a gift card with the wrapped gift (optional):', 1, 0, NULL, 1, 0, NULL, 1, '', '', NULL, 0, '', '', NULL, 0, NULL, NULL, 0, NULL, NULL, 0, 0, NULL, NULL, NULL, ' 3,', ' 132, 133, 134, 135, 137,', ' Small, Medium, Large, XL, XXL,', ' 53, 54, 52, ', ' Black, Burgundy, White', NULL, NULL, 'sample-sku-1000015', 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 2, NULL, 0, 365, 15, NULL, 0, 0, NULL, NULL, 0, NULL, NULL, 0, 0, 1, 'Choose Embriodery Type:', 'Enter Your Initials or Company Name:', 0, 1, 9067098, '2011-01-11 00:27:28', 2, '2023-07-08 19:32:55', 4006, 20, NULL, 1, NULL, 1, 'You have exceeded the max number of items allowed for this item. The maximum number (QTY) you can purchase of this item is: ', 'Please Select', 'Please make a selection', 'men s logo sweatshirt sample item shows common product options such color size well option upcharge feature small medium large xl xxl black burgundy white demarco gifts amp apparel clothing sample clothing product this sample item shows common product options well image viewer responds color selection changes products unlimited images different images assigned appear search results and or item view this item includes upcharge xxl size demonstrate additional fee assigned specific product variation any option item additional fee and or weight adjustments this sample product uses option specific advanced inventory feature product variations different stock levels sku numbers all features item set including display elements labels 1000015 sample sku 1000015 sample sku 1000015 white xxl ', 'Please Select', 'Please make a selection', 'Sweatshirt Total : ', NULL, NULL, NULL, NULL, 'Stock Level: ', NULL, NULL, 'Buy Now', 'Quick-Shop', NULL, NULL, '8296EFB3', 1, 13, 0, 0, NULL, 0, 0, 0, 1, 0, 0, 1, 1, 25, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, 1, 0, 0, 0, 0, 0, 0, 0, 'mens-logo-sweatshirt', NULL, 7, 'Product Info', 'Video', 'Tell-A-Friend', 'Product Reviews', 'Add Review', NULL, '<strong>One Time Fee(s)<strong>: ', '<strong>Total One-Time Fee(s)<strong>:', 1, 'https://cdn-demo-store.s3.us-west-1.amazonaws.com/sample-sweatshirt-video.mp4', NULL, NULL, NULL, NULL, NULL, 0, '', 0, 1000, NULL, 25, 0, NULL, 'This full-featured sample item includes product variations, advanced inventory management and color-assigned images.', 0, 'Add-to-Cart', 1, 'More Info', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 600, NULL, NULL, NULL, NULL, NULL, 'You did not enter the order minimum amount for this item. The minimum number you can purchase of this item is: ', '2023-07-08 19:32:55', NULL, 0, NULL, 0, 1, 0, 7, 0.00, NULL, 0, 3, 0);
INSERT INTO `sitestorepro_products` VALUES (1000016, 'Jewelry Repair Webinar Plus eBook', 'This item also demonstrates the ability to offer an item as an on-demand media item, on-demand + ship or ship-only.', '<p>The Download and/or Ship Feature allows the merchant to sell the same media item as a download, download and ship... or ship only.</p>\r\n<p><strong>Or.. this feature allows the merchant to offer a streaming video with a PDF handout as well as the option to ship a copy of the handout.</strong></p>\r\n<p>Many Site Store Pro customers who sell online training seminars offer supplementary materials along with the video content (webinar file).Â  <br><br>In addition to the preview video shown above, the purchaser of this webinar will also get downloadable content directly from the video viewing page... or they can choose to have their eductional supplements shipped as well.</p>\r\n<p>If a shippable option is selected, a shipping fee and sales tax (if applicable) will be charged during checkout.Â  You are not required to offer shipping options for downloadable or video items but Site Store Pro gives you that added flexibility.</p>', NULL, NULL, NULL, NULL, NULL, NULL, 'sample-sku-1000016', 1009, 3, 1, 0, 39.99, 29.99, 0.00, 0.00, 0, 0, 0, 0, 0.15, 1, 1, 1, 2, 0, 0, 0, 0, 0, 0, 0, 0, 'Gift Wrap Item?', 'Please enter a message to be included on a gift card with the wrapped gift (optional):', 1, 0, NULL, 0, 0, NULL, 1, '', '', NULL, 0, '', '<h2>Thank you for your sample webinar purchase!</h2>\r\n<br><a href=\"https://cdn-demo-store.s3.us-west-1.amazonaws.com/sample-purchased-ebook.pdf\" target=\"_blank\"><strong>Click here to download your sample eBook</strong></a> and watch the sample video below. All secure content on this page is controlled directly via the admin area and requires no programming!', '/store_content/downloads/video/1000016_d13de06d053e4a71870f7b4538a5ab31/blue_-_45965.mp4', 1, NULL, NULL, 0, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sample-sku-1000016', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 1, 365, 25, '', 0, 0, NULL, NULL, 0, NULL, NULL, 0, 0, 0, NULL, NULL, 0, 1, 8755673, '2011-01-13 12:18:20', 2, '2023-06-23 18:54:25', 636, 25, NULL, 1, NULL, 1, 'You have exceeded the max number of items allowed for this item. The maximum number (QTY) you can purchase of this item is: ', 'Please Select', 'Please make a selection', 'jewelry repair webinar plus ebook item also demonstrates ability offer item on demand media item on demand ship ship only prestige design downloads amp videos training videos download and or ship feature allows merchant sell same media item download download ship ship only or feature allows merchant offer streaming video pdf handout well option ship copy handout many site store pro customers sell online training seminars offer supplementary materials along video content webinar file addition preview video shown above purchaser webinar also get downloadable content directly video viewing page choose their eductional supplements shipped well if shippable option selected shipping fee sales tax if applicable charged during checkout required offer shipping options downloadable video items site store pro gives added flexibility 1000016 sample sku 1000016 sample sku 1000016 ', 'Please Select', 'Please make a selection', 'Total : ', '<p>The Download and/or Ship Feature allows the merchant to sell the same media item as a download, download and ship... or ship only.</p>\r\n<p><strong>Or.. this feature allows the merchant to offer a streaming video with a PDF handout as well as the option to ship a copy of the handout.</strong></p>', NULL, '', NULL, 'Stock Level: ', NULL, NULL, 'Buy Now', 'Quick-Shop', NULL, NULL, '37FC9B12', 1, 1, 1, 0, NULL, 0, 0, 0, 1, 0, 0, 1, 0, 15, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, 0, 1, 0, 1, 0, 0, 0, 1, 'sample-webinar-plus-ebook', NULL, 9, 'Webinar Info', 'Video', 'Tell-A-Friend', 'Product Reviews', 'Product Reviews', NULL, '<strong>One Time Fee(s)<strong>: ', '<strong>Total One-Time Fee(s)<strong>:', 1, 'https://cdn-demo-store.s3.us-west-1.amazonaws.com/video-preview-example.mp4', NULL, NULL, NULL, NULL, NULL, 1, 'https://cdn-demo-store.s3.us-west-1.amazonaws.com/video-preview-example.mp4', 0, 1000, NULL, 25, 0, NULL, 'This is an example of an item that includes both images and a video on the item view page. You can upload both images and video directly from the admin area, link to CDN content and/or and have a \"preview\" video of your streamed or downloaded content.<br><br>This item also demonstrates the ability to offer an item as downloadable (or watchable), download + ship or ship-only. Merchants frequently use the \"download and ship\" feature to offer an item as all digital, digital + hardcopy or hardcopy only.<br><br>This sample item also has the QTY field disabled with a MAX of 1 available for purchase since customer\'s will rarely be purchasing multiple quantities of the same digital media item.', 0, 'Add-to-Cart', 1, 'More Info', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'https://cdn-demo-store.s3.us-west-1.amazonaws.com/video-poster-preview-sample.webp', 'https://cdn-demo-store.s3.us-west-1.amazonaws.com/video-poster-preview-sample.webp', NULL, '', 0, '', '', '', '', '', '', '', 600, '', '', '', 'View Now', NULL, 'You did not enter the order minimum amount for this item. The minimum number you can purchase of this item is: ', '2023-06-23 18:54:25', NULL, 0, NULL, 0, 1, 0, 7, 0.00, NULL, 0, 3, 0);
INSERT INTO `sitestorepro_products` VALUES (1000017, 'Jewelry Accessorizing ONLINE Webinar', 'This sample item shows how a merchant can display a video preview &nbsp;for an item and also offer the item as an on-demand video product.', '<p>This sample item shows how a merchant can display a video preview &nbsp;for an item and also offer the item as an on-demand video product.<br><br>The store administrator can restrict number of views and duration of viewing and all options are set via the web-based admin.. no programming required.<br><br>This page is using one of the alternate layout options that is built-into to the product manager in the admin area. (video player on top)</p>', NULL, NULL, NULL, NULL, NULL, NULL, 'sample-sku-1000017', 1009, 3, 1, 0, 29.99, 25.95, 0.00, 0.00, 0, 0, 0, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 'Gift Wrap Item?', 'Please enter a message to be included on a gift card with the wrapped gift (optional):', 1, 0, NULL, 0, 0, NULL, 1, '', '', NULL, 1, 'Sample Webinar', 'This is a sample on-demand video from a streaming video file.', NULL, 1, NULL, NULL, 0, NULL, NULL, 4, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sample-sku-1000017', 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 1, 365, 150, NULL, 0, 0, NULL, NULL, 0, NULL, NULL, 0, 0, 0, NULL, NULL, 0, 1, 8755673, '2011-01-14 15:19:12', 2, '2023-07-20 15:35:47', 613, 9, NULL, 1, NULL, 1, 'You have exceeded the max number of items allowed for this item. The maximum number (QTY) you can purchase of this item is: ', 'Please Select', 'Please make a selection', 'jewelry accessorizing online webinar sample item shows merchant display video preview for item also offer item on demand video product prestige design downloads amp videos training videos sample item shows merchant display video preview for item also offer item on demand video product store istrator restrict number views duration viewing options set via web based no programming required page one alternate layout options built into product manager video player top 1000017 sample sku 1000017 sample sku 1000017 ', 'Please Select', 'Please make a selection', 'Total : ', NULL, NULL, NULL, NULL, 'Stock Level: ', NULL, NULL, 'Buy Now', 'Quick-Shop', NULL, NULL, '1FFAC606', 1, 1, 0, 0, NULL, 0, 0, 0, 1, 0, 0, 0, 0, 15, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, 0, 1, 0, 1, 0, 0, 0, 1, 'jewelry-accessorizing-online-webinar', NULL, 11, 'Product Info', 'Video', 'Tell-A-Friend', 'Product Reviews', 'Add Review', NULL, '<strong>One Time Fee(s)<strong>: ', '<strong>Total One-Time Fee(s)<strong>:', 1, 'https://cdn-demo-store.s3.us-west-1.amazonaws.com/video-preview-example-2.mp4', NULL, NULL, NULL, NULL, NULL, 1, 'https://cdn-demo-store.s3.us-west-1.amazonaws.com/video-purchase-example.mp4', 0, 1000, NULL, 25, 0, NULL, 'This sample item shows how a merchant can display a video preview &nbsp;for an item and also offer the item as an on-demand video product. On-Demand video products can be viewed directly after purchase and do not need to be downloaded or shipped.', 1, 'Add-to-Cart', 1, 'More Info', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'https://cdn-demo-store.s3.us-west-1.amazonaws.com/video-poster-example.jpg', 'https://cdn-demo-store.s3.us-west-1.amazonaws.com/webinar-poster-sample-image.webp', NULL, '', 0, '', '', '', '', '', '', '', 600, '', '', '', 'View Now', NULL, 'You did not enter the order minimum amount for this item. The minimum number you can purchase of this item is: ', '2023-07-20 15:35:47', NULL, 0, 'Monthly', 0, 1, 0, 7, 0.00, NULL, 0, 3, 0);
INSERT INTO `sitestorepro_products` VALUES (1000018, 'CDN Sample Product', 'The product image on this item and background video on the details page are both loaded from an external URL.', '<h3>All features, formatting and styling for this item, as well as this entire demo site, were set directly from the admin and required no customization to the default CMS or shopping cart feature-set.</h3>\r\n<span style=\"font-size: 1.3em; font-weight: 500;\">The image and background video for this item are being loaded from a CDN.&nbsp; You can either upload your images or videos to your webserver (hosting account) or enter direct links to your media files using external URLs.</span> <br><br>The image load type (local or CDN) and the item layout are not dependent on each other. All item layouts can load images from either the same server (i.e. direct upload from the admin) or via a external URL (remote server / CDN) or a combination of both methods at the same time.<br><br><span style=\"font-size: 1.3em; font-weight: 500;\">This specific item was formatted with the online product manager to demonstrate how an item can be styled completely differently from the rest of the store item\'s without requiring any direct code access or cart customizations and without affecting the other items on the store. All items have a custom CSS field where you can add optional CSS rules that override the site-wide default CSS.</span><br><span style=\"font-size: 1em;\"><br>Site Store Pro is the only ecommerce system that gives you the option of a complete full-featured, online site builder (CMS) plus also has the flexibility for you to use a custom design created with any editor such as Dreamweaver, Atom, Visual Studio code, etc. You can also have a hybrid site where some content is managed through the online CMS and some is managed with an offline HTML editor. Site Store Pro works with any site design and branding scheme and allows you to create a powerful and secure site in record time.</span>', NULL, NULL, NULL, NULL, NULL, NULL, 'sample-sku-1000018', 1003, 1, 1, 0, 49.99, 35.99, 31.00, 35.00, 0, 0, 1, 1, 0.4, 1, 1, 0, 0, 1, 6, 0, 0, 0, 0, 0, 0, 'Gift Wrap Item?', 'Please enter a message to be included on a gift card with the wrapped gift (optional):', 0, 0, NULL, 1, 0, NULL, 0, NULL, NULL, NULL, 0, '', NULL, '', 0, NULL, '', 0, '', '', 4, 0, NULL, NULL, ' 20, 25, 22,', ' 2,', NULL, NULL, NULL, NULL, NULL, NULL, 'sample-sku-1000018', 0, 1, 0, 1, 0, 1, 0, 1, 0, 0, 0, 0, NULL, 0, 365, 1000, NULL, 0, 0, NULL, NULL, 0, NULL, NULL, 0, 0, 0, NULL, NULL, 0, 1, 8755673, '2014-05-14 13:20:11', 2, '2023-07-08 14:57:17', 965, 18, NULL, 1, NULL, 1, 'You have exceeded the max number of items allowed for this item. The maximum number (QTY) you can purchase of this item is: ', 'Please Select', 'Please make a selection', 'ring cdn sample product product images item being loaded an external url. prestige design custom jewelry rings images item being loaded cdn. either upload images directly inside admin a link images an external url ie. cdn.this item layout one built-in layouts. centered layout images top. site store pro includes several different layouts items plus ability unlimilted custom layouts. 1000018 cdn-00001 rubies ruby diamond ring diamonds rings ', 'Please Select', 'Please make a selection', 'Total : ', NULL, NULL, NULL, NULL, 'Stock Level: ', NULL, NULL, '&#xf217 Add-To-Cart', 'Quick-Shop', NULL, NULL, '94CE6117', 0, 0, 0, 0, NULL, 0, 0, 0, 1, 1, 0, 0, 1, 15, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, 0, 1, 0, 0, 0, 0, 0, 1, 'ring-cdn-sample-product', NULL, 7, 'Product Info', 'Video', 'Tell-A-Friend', 'Product Reviews', 'Add Review', NULL, '<strong>One Time Fee(s)<strong>: ', '<strong>Total One-Time Fee(s)<strong>:', 0, NULL, NULL, NULL, '', '', '', 0, NULL, 0, 1000, NULL, 25, 0, NULL, 'The product image on this item and background video are both loaded from a CDN (Content Delivery Network) source instead of being stored on the site\'s hosting account.', 1, 'Add-to-Cart', 1, 'More Info', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', 'https://d3t23w3v39t89j.cloudfront.net/sample-ring-item-background.mp4', NULL, NULL, NULL, NULL, '.product-info-content {\r\n    opacity: 0.8;\r\n    margin-top: 50px !important;\r\n    margin-bottom: 200px !important;\r\n    padding: 0;\r\n}\r\n.product-form-rows {\r\n    align-items: center;\r\n}\r\n.product_title-content h1 {\r\n    font-size: 3em;\r\n    margin-bottom: 0 !important;\r\n    padding-bottom: 0 !important;\r\n    color: white !important;\r\n}\r\n.product-short-description-content {\r\n    margin-top: 0 !important;\r\n    margin-bottom: 30px !important;\r\n    color: white !important;\r\n}\r\n.product-add-to-cart-row-content {\r\n    max-width: 550px;\r\n    opacity: 0.8;\r\n}\r\n.product-add-to-cart-row-content {\r\n    padding: 0;\r\n    margin-bottom: 0;\r\n}\r\n.product-tabs .product-tabs-panel {\r\n    background-color: rgba(0, 0, 0, 0.8);\r\n}\r\n#item_long_description {\r\n    color: #eee !important;\r\n    padding: 25px;\r\n    line-height: 1.6;\r\n    font-weight: 300;\r\n}\r\n#item_long_description h3 {\r\n    color: white !important;\r\n    padding: 0;\r\n    margin-top: 0;\r\n    margin-bottom: 20px;\r\n    font-size: 2em !important;\r\n    font-weight: 300 !important;\r\n}\r\n.product-short-description-content {\r\n    max-width: 550px;\r\n    opacity: 0.8;\r\n}\r\n.product-tabs-header {\r\n    display: none;\r\n}\r\n.product-view-main-content {\r\n    width: 100%;\r\n    max-width: 1100px;\r\n    margin: 25px auto;\r\n}\r\n\r\n.product-short-description-content {\r\n    margin-bottom: 10px;\r\n    padding: 0;\r\n}\r\n.product-media {\r\n    margin-top: 25px;\r\n}\r\n.product-media-content {\r\n    padding: 0;\r\n    min-width: 517px;\r\n    opacity: 0.7;\r\n}\r\n.product-info-content {\r\n    padding: 0;\r\n    margin-bottom: 25px;\r\n}\r\n.product-add-to-cart-row-content {\r\n    padding: 0;\r\n    margin-bottom: 25px;\r\n}\r\n\r\n.product-add-to-cart-button {\r\n    background: black !important;\r\n    color: white;\r\n    border: none !important;\r\n    max-width: 200px;\r\n    width: 200px;\r\n    min-width: 200px;\r\n\r\n    color: #fff !important;\r\n    font-family: \"Font Awesome 5 Free\", sans-serif !important;\r\n    -moz-osx-font-smoothing: grayscale;\r\n    font-style: normal !important;\r\n    font-variant: normal !important;\r\n    text-rendering: auto !important;\r\n    font-weight: 900 !important;\r\n}\r\n.product-regular-price {\r\n    /* this formats the regular price (before discount applied) text*/\r\n    color: #eee;\r\n    font-size: var(--font-size-emphasis);\r\n    font-weight: 300;\r\n    text-decoration: line-through;\r\n    opacity: 0.8;\r\n}\r\n.product-add-to-cart-button:hover {\r\n    background: #666 !important;\r\n    border: none !important;\r\n    max-width: 200px;\r\n    width: 200px;\r\n    min-width: 200px;\r\n    color: #000 !important;\r\n    font-family: \"Font Awesome 5 Free\", sans-serif !important;\r\n    -moz-osx-font-smoothing: grayscale;\r\n    font-style: normal !important;\r\n    font-variant: normal !important;\r\n    text-rendering: auto !important;\r\n    font-weight: 900 !important;\r\n}\r\n\r\n@media only screen and (max-width: 950px) {\r\n    #item_long_description h3 {\r\n        color: white !important;\r\n        padding: 0;\r\n        margin-top: 0;\r\n        margin-bottom: 20px;\r\n        font-size: 1.5em !important;\r\n        font-weight: 300 !important;\r\n    }\r\n    .product-tabs .product-tabs-accordion-title {\r\n        display: none !important;\r\n    }\r\n    .product-short-description-content {\r\n        margin: 0 auto;\r\n    }\r\n    .product-media {\r\n        margin: 0 auto;\r\n    }\r\n    .product-media-content {\r\n        margin: 0 auto;\r\n        min-width: 250px;\r\n    }\r\n    .product-info-content {\r\n        margin: 0 auto;\r\n    }\r\n    .product-add-to-cart-row-content {\r\n        margin: 0 auto;\r\n    }\r\n}\r\n#main_content_area {\r\n    width: 100%;\r\n    max-width: 100% !important;\r\n    background-color: black;\r\n}\r\n.product-add-to-cart-container {\r\n    /* this formats box around the add-to-cart / options selection area for the item. */\r\n    background-color: black;\r\n    color: #eee;\r\n    border: 1px solid black;\r\n    padding: 15px;\r\n    border-radius: var(--global-border-radius);\r\n    box-shadow: var(--global-box-shadow-light);\r\n    box-sizing: border-box;\r\n}\r\n@media (max-width: 600px) {\r\n    .product-cart-buttons-container {\r\n       text-align: center!important;\r\n    }\r\n}\r\n', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 600, NULL, NULL, NULL, NULL, NULL, 'You did not enter the order minimum amount for this item. The minimum number you can purchase of this item is: ', '2021-09-26 14:57:22', NULL, 0, NULL, 0, 1, 0, 7, 0.00, NULL, 0, 3, 0);
INSERT INTO `sitestorepro_products` VALUES (1000019, 'Vintage Pocket Watch', 'This page shows another alternate layout configuration that can be selected in the admin when configuring the product (left side media &gt; right sidebar - no breadcrumbs).', 'Hand-made by our artisan craftsman. Reproduction of an 18th century pocket watch made with hammered brass', NULL, NULL, NULL, NULL, NULL, NULL, 'sample-sku-1000019', 0, 2, 3, 0, 75.50, 65.00, 15.00, 69.99, 1, 6, 0, 1, 1, 1, 1, 1, 4, 0, 0, 0, 0, 0, 0, 0, 0, 'Gift Wrap Item?', 'Please enter a message to be included on a gift card with the wrapped gift (optional):', 1, 0, NULL, 1, 0, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, 2, 0, NULL, '{{Past Viewed Items}}', ' 31,', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sample-sku-1000019', 1, 1, 0, 0, 0, 1, 0, 0, 0, 1, 0, 3, NULL, 0, 365, 1000, NULL, 0, 0, NULL, NULL, 0, NULL, NULL, 0, 0, 0, NULL, NULL, 0, 1, 8755673, '2014-05-14 13:20:11', 2, '2023-06-06 22:52:04', 509, 22, NULL, 1, NULL, 1, 'You have exceeded the max number of items allowed for this item. The maximum number (QTY) you can purchase of this item is: ', 'Please Select', 'Please make a selection', 'vintage pocket watch page shows another alternate layout configuration selected when configuring product left side media gt right sidebar no breadcrumbs old heritage watches hand made our artisan craftsman reproduction 18th century pocket watch made hammered brass 1000019 sample sku 1000019 sample sku 1000019 ', 'Please Select', 'Please make a selection', 'Total : ', NULL, NULL, NULL, NULL, 'Stock Level: ', NULL, NULL, 'Buy Now', 'Quick-Shop', NULL, NULL, '259B5737', 1, 0, 0, 0, NULL, 0, 0, 0, 1, 0, 0, 0, 0, 15, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, 1, 0, 0, 0, 0, 0, 0, 1, 'vintage-pocket-watch', NULL, 8, 'Product Info', 'Video', 'Tell-A-Friend', 'Product Reviews', 'Add Review', NULL, '<strong>One Time Fee(s)<strong>: ', '<strong>Total One-Time Fee(s)<strong>:', 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 1000, NULL, 25, 0, NULL, 'This page shows an alternate layout that can be selected in the admin when configuring the product.&nbsp; This layout is left-side media with a right-side column (sidebar) instead of the default right side images and left-side column. The breadcrumbs and product details tabs have been turned off as well. <strong>All features can be activated/deactivated in real-time in the admin and there are 1000s of different product configurations possible with no programming required.</strong><br><br>The column on the right contains a shortcode plugin to display items marked as \"Featured\" and it is set to be a scroller.', 1, 'Add-to-Cart', 1, 'More Info', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 600, NULL, NULL, NULL, NULL, NULL, 'You did not enter the order minimum amount for this item. The minimum number you can purchase of this item is: ', '2023-06-06 22:52:04', NULL, 0, NULL, 0, 1, 0, 7, 0.00, NULL, 0, 3, 0);
INSERT INTO `sitestorepro_products` VALUES (1000020, 'Fashion Wrist Watch', 'Shows color swatches feature and Qty selector feature.', '<p>Simple, elegant women\'s watch. Available with a brown or black band.</p>', 'Made In Italy', 'Genuine Leather Band', 'Pink-Tinted Watch Face', 'Swiss Movement', NULL, NULL, 'sample-sku-1000020', 0, 2, 4, 0, 25.99, 19.00, 13.00, 0.00, 0, 0, 1, 1, 0.5, 1, 1, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 'Gift Wrap Item?', 'Please enter a message to be included on a gift card with the wrapped gift (optional):', 0, 0, NULL, 1, 0, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, 0, 0, NULL, NULL, ' 30,', NULL, NULL, NULL, ' 55, 53,', ' Brown, Black,', NULL, NULL, 'sample-sku-1000020', 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, NULL, 0, 365, 1000, NULL, 0, 0, NULL, NULL, 0, NULL, NULL, 0, 0, 0, NULL, NULL, 0, 1, 8755673, '2014-05-14 13:20:11', 2, '2023-05-16 02:53:13', 343, 6, NULL, 1, NULL, 1, 'You have exceeded the max number of items allowed for this item. The maximum number (QTY) you can purchase of this item is: ', 'Please Select', 'Please make a selection', 'fashion wrist watch shows color swatches feature qty selector feature. brown black bella luna watches simple elegant womens watch. available brown black band. made italy genuine leather band pink-tinted watch face swiss movement 1000020 sample-sku-1000020 sample-sku-1000020 ', 'Please Select', 'Please make a selection', 'Total : ', 'The product can have four seperate description fields for different areas. This description only appears on the \"quick-shop\".', NULL, NULL, NULL, 'Stock Level: ', NULL, NULL, 'Buy Now', 'Quick-Shop', NULL, NULL, '38512BC1', 0, 0, 0, 0, NULL, 0, 0, 0, 1, 0, 0, 1, 0, 15, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, 0, 1, 0, 0, 0, 0, 0, 0, 'fashion-wrist-watch', NULL, 7, 'Product Info', 'Video', 'Tell-A-Friend', 'Product Reviews', 'Add Review', NULL, '<strong>One Time Fee(s)<strong>: ', '<strong>Total One-Time Fee(s)<strong>:', 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 1000, NULL, 25, 0, NULL, 'The details page can have a different short description than what appears on the search page plus a long description below. This sample item shows the color swatch feature and Qty selector feature', 0, 'Add-to-Cart', 1, 'More Info', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 600, NULL, NULL, NULL, NULL, NULL, 'You did not enter the order minimum amount for this item. The minimum number you can purchase of this item is: ', '2023-05-16 02:53:13', NULL, 0, NULL, 0, 1, 0, 7, 0.00, NULL, 0, 3, 0);
INSERT INTO `sitestorepro_products` VALUES (1000021, 'Premium Office Pens 2 Pack', 'Professional writing instrument by Excelsior Office Products.', '<p>Fluid writing on virtually any surface. Ink Color is Black.</p>', '2 Pack', 'Silver and Black Pens Included', 'Made In China', NULL, NULL, NULL, 'sample-sku-1000021', 1010, 5, 5, 0, 17.99, 13.20, 8.00, 15.99, 1, 0, 1, 1, 0.4, 1, 1, 0, 0, 1, 7, 0, 0, 0, 0, 0, 0, 'Gift Wrap Item?', 'Please enter a message to be included on a gift card with the wrapped gift (optional):', 0, 0, NULL, 1, 0, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, 0, 0, NULL, NULL, ' 21,', ' 6,', NULL, NULL, NULL, NULL, NULL, NULL, 'sample-sku-1000021', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0, 365, 1000, NULL, 0, 0, NULL, NULL, 0, NULL, NULL, 0, 0, 0, NULL, NULL, 0, 1, 3, '2014-06-02 13:42:46', 2, '2023-06-11 17:47:59', 281, 3, NULL, 1, NULL, 1, 'You have exceeded the max number of items allowed for this item. The maximum number (QTY) you can purchase of this item is: ', 'Please Select', 'Please make a selection', 'premium office pens 2 pack professional writing instrument excelsior office products excelsior gifts amp apparel writing pens fluid writing virtually any surface ink color black 2 pack silver black pens included made china 1000021 sample sku 1000021 sample sku 1000021 ', 'Please Select', 'Please make a selection', 'Total : ', NULL, NULL, NULL, NULL, 'Stock Level: ', NULL, NULL, 'Buy Now', 'Quick-Shop', NULL, NULL, 'C9C56592', 1, 0, 0, 0, NULL, 0, 0, 0, 1, 0, 0, 0, 0, 15, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, 1, 1, 1, 1, 0, 0, 0, 1, 'premium-office-pens-2-pack', NULL, 8, 'Product Info', 'Video', 'Tell-A-Friend', 'Product Reviews', 'Add Review', NULL, '<strong>One Time Fee(s)<strong>: ', '<strong>Total One-Time Fee(s)<strong>:', 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 1000, NULL, 25, 0, NULL, NULL, 1, 'Add-to-Cart', 1, 'More Info', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 600, NULL, NULL, NULL, NULL, NULL, 'You did not enter the order minimum amount for this item. The minimum number you can purchase of this item is: ', '2023-06-11 17:47:59', NULL, 0, NULL, 0, 1, 0, 7, 0.00, NULL, 0, 3, 0);
INSERT INTO `sitestorepro_products` VALUES (1000022, 'Silver Jewelry Box', 'Reproduction 17th Century silver gift box.', '<p>Beautiful reproduction of a 17th century jewerly box. Made with sterling silver. Limited quantities.</p>', NULL, NULL, NULL, NULL, NULL, NULL, 'sample-sku-1000022', 1011, 5, 3, 0, 499.99, 400.00, 295.00, 0.00, 0, 0, 1, 1, 2, 1, 1, 0, 0, 1, 8, 0, 0, 0, 0, 0, 0, 'Gift Wrap Item?', 'Please enter a message to be included on a gift card with the wrapped gift (optional):', 0, 0, NULL, 1, 0, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, 0, 0, NULL, NULL, ' 21,', ' 4, 5,', NULL, NULL, NULL, NULL, NULL, NULL, 'sample-sku-1000022', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0, 365, 1000, NULL, 0, 0, NULL, NULL, 0, NULL, NULL, 0, 0, 0, NULL, NULL, 0, 1, 3, '2014-06-02 15:03:59', 2, '2023-05-16 02:53:34', 510, 17, NULL, 1, NULL, 1, 'You have exceeded the max number of items allowed for this item. The maximum number (QTY) you can purchase of this item is: ', 'Please Select', 'Please make a selection', 'silver jewelry box reproduction 17th century silver gift box. old heritage gifts & apparel jewelry boxes beautiful reproduction 17th century jewerly box. made sterling silver. limited quantities. 1000022 sample-sku-1000022 sample-sku-1000022 ', 'Please Select', 'Please make a selection', 'Total : ', NULL, NULL, NULL, NULL, 'Stock Level: ', NULL, NULL, 'Buy Now', 'Quick-Shop', NULL, NULL, '34833992', 0, 0, 0, 0, NULL, 0, 0, 0, 1, 0, 0, 0, 0, 15, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, 0, 1, 0, 0, 0, 0, 0, 0, 'silver-jewelry-box', NULL, 7, 'Product Info', 'Video', 'Tell-A-Friend', 'Product Reviews', 'Add Review', NULL, '<strong>One Time Fee(s)<strong>: ', '<strong>Total One-Time Fee(s)<strong>:', 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 1000, NULL, 25, 0, NULL, NULL, 1, 'Add-to-Cart', 1, 'More Info', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 600, NULL, NULL, NULL, NULL, NULL, 'You did not enter the order minimum amount for this item. The minimum number you can purchase of this item is: ', '2023-05-16 02:53:34', NULL, 0, NULL, 0, 1, 0, 7, 0.00, NULL, 0, 3, 0);
INSERT INTO `sitestorepro_products` VALUES (1000023, 'Modern Pocket Watch', 'This sample item contains a basic image with an associated zoom image.', '<p>Sample pocket watch with a modern design. Stainless Steel. Japan Movement.</p>', NULL, NULL, NULL, NULL, NULL, NULL, 'sample-sku-1000023', 0, 2, 5, 0, 49.99, 45.00, 37.00, 46.99, 1, 4, 1, 1, 0.4, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 'Gift Wrap Item?', 'Please enter a message to be included on a gift card with the wrapped gift (optional):', 0, 0, NULL, 1, 0, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, 0, 0, NULL, NULL, ' 32,', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sample-sku-1000023', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0, 365, 1000, NULL, 0, 0, NULL, NULL, 0, NULL, NULL, 0, 0, 0, NULL, NULL, 0, 1, 3, '2014-06-09 19:07:44', 2, '2023-06-03 21:26:45', 82, 2, NULL, 1, NULL, 1, 'You have exceeded the max number of items allowed for this item. The maximum number (QTY) you can purchase of this item is: ', 'Please Select', 'Please make a selection', 'modern pocket watch sample item contains basic image an associated zoom image. excelsior watches sample pocket watch modern design. stainless steel. japan movement. 1000023 sample-sku-1000023 sample-sku-1000023 ', 'Please Select', 'Please make a selection', 'Total : ', NULL, NULL, NULL, NULL, 'Stock Level: ', NULL, NULL, 'Buy Now', 'Quick-Shop', NULL, NULL, 'DD7B9242', 0, 0, 0, 0, NULL, 0, 0, 0, 1, 0, 0, 0, 1, 15, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, 1, 0, 0, 0, 0, 0, 0, 1, 'modern-pocket-watch', NULL, 7, 'Product Info', 'Video', 'Tell-A-Friend', 'Product Reviews', 'Add Review', NULL, '<strong>One Time Fee(s)<strong>: ', '<strong>Total One-Time Fee(s)<strong>:', 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 1000, NULL, 25, 0, NULL, 'Although it is usually recommended to have a large (zoom) image associated with the regular size page image, it is not a requirement. This image has only one basic image without a zoom option.', 1, 'Add-to-Cart', 1, 'More Info', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 600, NULL, NULL, NULL, NULL, NULL, 'You did not enter the order minimum amount for this item. The minimum number you can purchase of this item is: ', '2023-06-03 21:26:45', NULL, 0, NULL, 0, 1, 0, 7, 0.00, NULL, 0, 3, 0);
INSERT INTO `sitestorepro_products` VALUES (1000024, 'Modern Wrist Watch', 'This sample item shows how you can hide the QTY selector and also limit the item to a max of 1.', '<p>High quality modern watch. Great for format events. Diamond lined hearts on the band.</p>', NULL, NULL, NULL, NULL, NULL, NULL, 'sample-sku-1000024', 0, 2, 5, 0, 99.99, 75.00, 65.00, 0.00, 0, 0, 1, 1, 0.4, 1, 1, 0, 0, 1, 4, 0, 0, 0, 0, 0, 0, 'Gift Wrap Item?', 'Please enter a message to be included on a gift card with the wrapped gift (optional):', 0, 0, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, 0, 0, NULL, NULL, ' 22, 21, 32,', ' 2,', NULL, NULL, NULL, NULL, NULL, NULL, 'sample-sku-1000024', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0, 365, 1000, NULL, 0, 0, NULL, NULL, 0, NULL, NULL, 0, 0, 0, NULL, NULL, 0, 1, 3, '2014-06-09 19:23:08', 2, '2023-06-03 21:25:56', 124, 4, NULL, 1, NULL, 1, 'You have exceeded the max number of items allowed for this item. The maximum number (QTY) you can purchase of this item is: ', 'Please Select', 'Please make a selection', 'modern wrist watch sample item shows hide qty selector also limit item max 1. excelsior watches high quality modern watch. great format events. diamond lined hearts band. 1000024 sample-sku-1000024 sample-sku-1000024 ', 'Please Select', 'Please make a selection', 'Total : ', NULL, NULL, NULL, NULL, 'Stock Level: ', NULL, NULL, 'Buy Now', 'Quick-Shop', NULL, NULL, '5D9D7BBA', 0, 0, 1, 0, NULL, 0, 0, 0, 1, 0, 0, 0, 0, 15, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, 0, 1, 0, 0, 0, 0, 0, 0, 'modern-wrist-watch', NULL, 7, 'Product Info', 'Video', 'Tell-A-Friend', 'Product Reviews', 'Add Review', NULL, '<strong>One Time Fee(s)<strong>: ', '<strong>Total One-Time Fee(s)<strong>:', 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 1000, NULL, 25, 0, NULL, 'This sample item shows how you can hide the QTY selector and also limit the item to a max quantity value so if the customer changes their qty after purchase, it still reverts back to the set value. (For this example, both features are turned on but they are not dependent on each other.)', 1, 'Add-to-Cart', 1, 'More Info', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 600, NULL, NULL, NULL, NULL, NULL, 'You did not enter the order minimum amount for this item. The minimum number you can purchase of this item is: ', '2023-06-03 21:25:56', NULL, 0, NULL, 0, 1, 0, 7, 0.00, NULL, 0, 3, 0);
INSERT INTO `sitestorepro_products` VALUES (1000025, 'Men\'s Vintage Logo Shirt', 'High-quality, cotton t-shirt with the Prestige Design logo.', 'Pre-faded and pre-shrunk. for a \"vintage\" look and feel. Extremely soft and durable. <br>\r\n<p>&nbsp;</p>', 'Made In The U.K.', '100% Cotton.', NULL, NULL, NULL, NULL, 'sample-sku-1000025', 1008, 5, 2, 0, 19.99, 14.50, 12.00, 15.99, 1, 5, 1, 1, 0.7, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 'Gift Wrap Item?', 'Please enter a message to be included on a gift card with the wrapped gift (optional):', 1, 0, NULL, 1, 0, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, 0, 0, NULL, NULL, ' 31,', ' 3,', ' 136, 132, 133, 134, 135, 137,', ' X-Small, Small, Medium, Large, XL, XXL,', ' 55, 57, 59, 56, 58, 60,', ' Brown, Green, Grey, Orange, Navy Blue, Royal Blue,', NULL, NULL, 'sample-sku-1000025', 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 0, NULL, 0, 365, 1000, NULL, 0, 0, NULL, NULL, 0, NULL, NULL, 0, 0, 0, NULL, NULL, 0, 1, 3, '2014-06-14 13:31:20', 2, '2023-06-11 17:54:06', 649, 6, NULL, 0, NULL, 1, 'You have exceeded the max number of items allowed for this item. The maximum number (QTY) you can purchase of this item is: ', 'Please Select', 'Please make a selection', 'men s vintage logo shirt high quality cotton t shirt prestige design logo x small small medium large xl xxl brown green grey orange navy blue royal blue demarco gifts amp apparel clothing pre faded pre shrunk vintage look feel extremely soft durable made u k 100 cotton 1000025 sample sku 1000025 sample sku 1000025 ', 'Please Select', 'Please make a selection', 'Total : ', NULL, NULL, NULL, NULL, 'Stock Level: ', NULL, NULL, 'Buy Now', 'Quick-Shop', NULL, NULL, 'DF3C208A', 0, 0, 0, 0, NULL, 0, 0, 0, 1, 0, 0, 1, 0, 15, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, 1, 0, 0, 0, 0, 0, 0, 0, 'mens-vintage-logo-shirt', 'Men\'s Vintage Faded Tee', 7, 'Product Info', 'Video', 'Tell-A-Friend', 'Product Reviews', 'Add Review', NULL, '<strong>One Time Fee(s)<strong>: ', '<strong>Total One-Time Fee(s)<strong>:', 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 1000, NULL, 25, 0, NULL, NULL, 0, 'Add-to-Cart', 1, 'More Info', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 600, NULL, NULL, NULL, NULL, NULL, 'You did not enter the order minimum amount for this item. The minimum number you can purchase of this item is: ', '2023-06-11 17:54:06', NULL, 0, NULL, 0, 1, 0, 7, 0.00, NULL, 0, 3, 0);
INSERT INTO `sitestorepro_products` VALUES (1000026, 'Women\'s Vintage Logo Shirt', 'This sample item show several of the built-in product option features including size, color and color swatches.', 'Pre-faded and pre-shrunk. for a \"vintage\" look and feel. Extremely soft and durable. <br>\r\n<p>&nbsp;</p>', 'Made In The U.K.', '100% Cotton.', NULL, NULL, NULL, NULL, 'sample-sku-1000026', 1008, 5, 2, 0, 19.99, 14.50, 12.00, 0.00, 0, 0, 1, 1, 0.7, 1, 1, 1, 5, 0, 0, 0, 0, 0, 0, 0, 0, 'Gift Wrap Item?', 'Please enter a message to be included on a gift card with the wrapped gift (optional):', 1, 0, NULL, 1, 0, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, 0, 0, NULL, NULL, ' 31,', ' 3,', ' 136, 132, 133, 134, 135,', ' X-Small, Small, Medium, Large, XL,', ' 55, 57, 59, 56, 58, 60,', ' Brown, Green, Grey, Orange, Navy Blue, Royal Blue,', NULL, NULL, 'sample-sku-1000026', 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, NULL, 0, 365, 1000, NULL, 0, 0, NULL, NULL, 0, NULL, NULL, 0, 0, 0, NULL, NULL, 0, 1, 3, '2014-06-15 10:27:38', 2, '2023-06-04 15:20:07', 796, 6, NULL, 1, NULL, 1, 'You have exceeded the max number of items allowed for this item. The maximum number (QTY) you can purchase of this item is: ', 'Please Select', 'Please make a selection', 'womens vintage logo shirt sample item show several built-in product option features including size color color swatches. x-small small medium large xl brown green grey orange navy blue royal blue demarco gifts & apparel clothing pre-faded pre-shrunk. vintage look feel. extremely soft durable. made u.k. 100% cotton. 1000026 sample-sku-1000026 sample-sku-1000026 ', 'Please Select', 'Please make a selection', 'Total : ', NULL, NULL, NULL, NULL, 'Stock Level: ', NULL, NULL, 'Buy Now', 'Quick-Shop', NULL, NULL, '650B167C', 0, 0, 0, 0, NULL, 0, 0, 0, 1, 0, 0, 1, 0, 15, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, 0, 1, 0, 0, 0, 0, 0, 0, 'womens-vintage-logo-shirt', NULL, 8, 'Product Info', 'Video', 'Tell-A-Friend', 'Product Reviews', 'Add Review', NULL, '<strong>One Time Fee(s)<strong>: ', '<strong>Total One-Time Fee(s)<strong>:', 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 1000, NULL, 25, 0, NULL, 'This sample item show several of the built-in product option features including size, color and color swatches. This item also is set with an alternate product layout (left side media instead of the default right side media layout).', 0, 'Add-to-Cart', 1, 'More Info', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 600, NULL, NULL, NULL, NULL, NULL, 'You did not enter the order minimum amount for this item. The minimum number you can purchase of this item is: ', '2023-06-04 15:20:07', NULL, 0, NULL, 0, 1, 0, 7, 0.00, NULL, 0, 3, 0);
INSERT INTO `sitestorepro_products` VALUES (1000027, 'Women\'s Logo Sweatshirt', 'Company sweatshirt with logo! Women\'s sizes S-XL.', '<p>High-quality cotton fleece sweatshirt. Available in red, white or black. Includes the Prestige \"Diamond\" logo on the crest. Sizes S-XL.</p>\r\n<p>Company logo is embroidered on the crest in white (burgundy and black sweatshirts) or black (on white sweatshirt).</p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>', 'Heavyweight Cotton', NULL, NULL, NULL, NULL, NULL, 'sample-sku-1000027', 1008, 5, 2, 0, 25.00, 22.00, 0.00, 0.00, 0, 0, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 'Gift Wrap Item?', 'Please enter a message to be included on a gift card with the wrapped gift (optional):', 1, 0, NULL, 1, 0, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, 0, 0, NULL, NULL, NULL, ' 3,', ' 132, 133, 134, 135, ', ' Small, Medium, Large, X-Large', ' 53, 61, 52,', ' Black, Red, White,', NULL, NULL, 'sample-sku-1000027', 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 2, NULL, 0, 365, 15, NULL, 0, 0, NULL, NULL, 0, NULL, NULL, 0, 0, 1, 'Choose Embriodery Type:', 'Enter Your Initials or Company Name:', 0, 1, 9067098, '2014-06-15 13:33:46', 2, '2023-06-03 21:01:46', 503, 1, NULL, 1, NULL, 1, 'You have exceeded the max number of items allowed for this item. The maximum number (QTY) you can purchase of this item is: ', 'Please Select', 'Please make a selection', 'womens logo sweatshirt company sweatshirt logo womens sizes s-xl. small medium large x-large black red white demarco gifts & apparel clothing high-quality cotton fleece sweatshirt. available red white black. includes prestige diamond logo crest. sizes s-xl. company logo embroidered crest white burgundy black sweatshirts black on white sweatshirt. heavyweight cotton 1000027 sample-sku-1000027 sample-sku-1000027 ', 'Please Select', 'Please make a selection', 'Total : ', NULL, NULL, NULL, NULL, 'Stock Level: ', NULL, NULL, 'Buy Now', 'Quick-Shop', NULL, NULL, 'BEAFF4B4', 1, 13, 0, 0, 0, 0, 0, 0, 1, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, 0, 1, 0, 0, 0, 0, 0, 0, 'womens-logo-sweatshirt', NULL, 0, 'Product Info', 'Video', 'Tell-A-Friend', 'Product Reviews', 'Add Review', NULL, '<strong>One Time Fee(s)<strong>: ', '<strong>Total One-Time Fee(s)<strong>:', 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 1000, NULL, 25, 0, NULL, NULL, 0, 'Add-to-Cart', 1, 'More Info', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 600, NULL, NULL, NULL, NULL, NULL, 'You did not enter the order minimum amount for this item. The minimum number you can purchase of this item is: ', '2023-06-03 21:01:46', NULL, 0, NULL, 0, 1, 0, 7, 0.00, NULL, 0, 3, 0);
INSERT INTO `sitestorepro_products` VALUES (1000028, 'Invoice Example', 'This is an example of a simple onlilne billing invoice item.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'service-example-item', 0, 6, 1, 0, 50.00, 50.00, 0.00, 0.00, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 'Gift Wrap Item?', 'Please enter a message to be included on a gift card with the wrapped gift (optional):', 0, 0, NULL, 0, 0, NULL, 0, '', '', NULL, 1, '', '', NULL, 0, 'Invoice Amount:', NULL, 0, NULL, NULL, 4, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, 1, 0, 1, 0, 0, 0, 0, 0, 0, NULL, 0, 365, 1000, NULL, 0, 0, NULL, NULL, 0, NULL, NULL, 0, 0, 0, NULL, NULL, 0, 1, 2, '2023-03-30 22:06:13', 2, '2023-06-19 01:46:09', 308, 1, NULL, 1, NULL, 1, 'You have exceeded the max number of items allowed for this item. The maximum number (QTY) you can purchase of this item is: ', 'Please Select', 'Selection is required.', 'invoice example example simple onlilne billing invoice item prestige design service only items 1000028 service example item invoice example ', 'Please Select', 'Selection is required.', 'Item Total: ', NULL, NULL, NULL, NULL, 'Stock Level: ', NULL, NULL, 'Pay Invoice Now', 'Quick-Shop', NULL, NULL, 'FC38E354', 0, 0, 1, 0, NULL, 0, 0, 0, 1, 0, 0, 0, 0, 15, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 'simple-invoice-example', NULL, 10, 'Product Info', 'Video', 'Tell-A-Friend', 'Product Reviews', 'Add Review', NULL, '<strong>One-Time Item Fee(s):</strong> ', 'Total One-Time Fees(s): ', 0, '', NULL, NULL, NULL, NULL, NULL, 0, '', 0, 1000, NULL, 25, 0, NULL, '<p style=\"border: solid 2px #ccc; border-radius: 4px; padding: 15px;\">Thiis online invoice example is a \"product\" that has not been marked as shippable, downloadable or an event and therefore it can be used as a simple and quick client billing method.&nbsp; Since shipping and tax will not be calculated, the order total will be the price of the item without any additional fees. You can even include a PDF invoice payment receipt and a custom confirmation page specific for the client (if required).<br><br><span style=\"color: rgb(224, 62, 45);\"><strong><span style=\"font-size: 1rem;\">This item has a max QTY of 1 with an inventory value of one (1) and the QTY field is hidden. </span>Since the inventory (stock level) is set at (1),&nbsp; once the item is purchased, it will be automatically disabled so the client cannot accidently pay for the invoice twice.</strong> After the invoice has been paid, if the invoice URL is revisited, a message will be automatically displayed to the client that the invoice has already been paid.</span><br><br>This item is also marked to be excluded from the public search results so it cannot be located unless the URL is sent directly to the client. The&nbsp; breadcrumb links, media (images) area, sharing buttons area and the product information tabs area are all turned off to present this minimalized display. If required , you can also secure the invoice with a client access code by embedding the invoice on a secured CMS page.</p>', 0, 'Add-to-Cart', 0, 'More Info', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 600, NULL, NULL, NULL, NULL, NULL, 'You did not enter the order minimum amount for this item. The minimum number you can purchase of this item is:  ', '2023-06-19 01:46:09', NULL, 0, NULL, 0, 1, 0, 7, 0.00, NULL, 0, 3, 0);
INSERT INTO `sitestorepro_products` VALUES (1000029, 'Product Builder Example', 'This is a sample item to illustrate the product builder features.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'builder-example', 0, 6, 1, 0, 100.00, 100.00, 0.00, 0.00, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 'Gift Wrap Item?', 'Please enter a message to be included on a gift card with the wrapped gift (optional):', 0, 0, NULL, 1, 0, NULL, 0, '', '', NULL, 1, '', '', NULL, 0, 'Base Price:', NULL, 0, NULL, NULL, 3, 1, NULL, '{{Products: Featured Items Grid}}', NULL, NULL, ' 132, 133, 134,', ' S, M, L,', ' 53, 52,', ' Black, White,', NULL, NULL, NULL, 1, 0, 0, 1, 0, 0, 0, 0, 0, 0, 1, 0, NULL, 0, 365, 1000, NULL, 0, 0, NULL, NULL, 0, NULL, NULL, 0, 0, 0, NULL, NULL, 0, 1, 2, '2023-03-31 01:44:35', 2, '2023-07-09 18:23:11', 696, 2, 'Build Option 1', 1, 'Build Option 2', 1, 'You have exceeded the max number of items allowed for this item. The maximum number (QTY) you can purchase of this item is: ', 'Please Select', 'Please Select A Build 1 Option.', 'product builder example sample item illustrate product builder features black white prestige design service only items 1000029 builder example builder example ', 'Please Select', 'Please Select A Build 2 Option.', 'Build Total:', NULL, NULL, NULL, NULL, 'Stock Level: ', NULL, NULL, 'Buy Customized Item', 'Quick-Shop', NULL, NULL, 'F8465E35', 0, 0, 0, 0, NULL, 0, 0, 0, 1, 0, 0, 0, 0, 15, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 'product-builder-example', NULL, 37, 'Product Info', 'Video', 'Tell-A-Friend', 'Product Reviews', 'Add Review', NULL, '<strong>One-Time Item Fee(s):</strong> ', 'Total One-Time Fees(s): ', 0, '', NULL, NULL, NULL, NULL, NULL, 0, '', 0, 1000, NULL, 25, 0, NULL, '<span style=\"font-size: 1.5rem;\">This is a sample item to illustrate the product builder features. You can create unlimited fields, lists and more including full HTML and media to go along with the custom fields. Calculated total can be displayed in real-time to the user as shown below when selections are made on this sample item.</span><br><br><span style=\"font-size: 1.5rem;\">This item also is using a dual sidebar layout which is another configuration option that can be set globally and/or individually per item.</span>', 0, 'Add-to-Cart', 0, 'More Info', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 600, NULL, NULL, NULL, NULL, NULL, 'You did not enter the order minimum amount for this item. The minimum number you can purchase of this item is:  ', '2023-07-09 18:23:11', NULL, 0, NULL, 0, 1, 0, 7, 0.00, NULL, 0, 3, 0);
INSERT INTO `sitestorepro_products` VALUES (1000030, 'Donation | \"Make An Offer\" Example', 'This is a sample donation item where the customer can enter in a specific amount to pay (or you can provide a selectable list of amounts). The price of the \"item\" is set to the donation value and a min and max value can also be specified.<br><br>The donation featuire can also be used for partial to full pay invoices to give a client the opportunatity to choose how much they pay for an invoice that is due.<br><br>Another popular use for the donation feature is for \"Make an Offer\" items where the customer can enter in the amount they wish to pay for an item such as domain name sales by owner, antiques and collectibles. You can set a starting value that is automatically displayed and choose whether to enfore that value as a mininium amount you will accept for an item.&nbsp;<br><br>The \"enter donation amount\" label is set in the admin and can be labeled to whatever type of payment you are accepting. (i.e., a donation, a project deposit, minimum offer, etc).<br><br><strong>This donation example has a min amount of $25 and a max donation amount of $500.</strong>', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'donation-example', NULL, 6, 1, 0, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 'Gift Wrap Item?', 'Please enter a message to be included on a gift card with the wrapped gift (optional):', 0, 0, NULL, 1, 0, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, 0, 0, 1, 0, 0, 1, 0, 0, 0, NULL, 0, 365, 1000, NULL, 0, 0, NULL, NULL, 0, NULL, NULL, 0, 0, 0, NULL, NULL, 0, 1, 2, '2023-05-17 02:18:19', 2, '2023-05-23 22:53:30', 103, 3, NULL, 1, NULL, 1, 'You have exceeded the max number of items allowed for this item. The maximum number (QTY) you can purchase of this item is: ', 'Please Select', 'Selection is required.', 'donation | make an offer example sample donation item customer enter specific amount pay or provide selectable list amounts. price item set donation value min max value also specified.the donation featuire also used partial full pay invoices give client opportunatity choose much they pay an invoice due.another popular donation feature make an offer items customer enter amount they wish pay an item such domain name sales owner antiques collectibles. set starting value automatically displayed choose whether enfore value mininium amount accept an item. the enter donation amount label set admin labeled whatever type payment accepting. i.e. donation project deposit minimum offer etc.this donation example has min amount $25 max donation amount $500. prestige design service only items 1000030 donation-example donation-example ', 'Please Select', 'Selection is required.', 'Item Total: ', NULL, NULL, NULL, NULL, 'Stock Level: ', NULL, NULL, 'Buy Now', 'Quick-Shop', NULL, NULL, 'B1B46A2B', 0, 0, 1, 0, NULL, 0, 0, 0, 1, 0, 0, 0, 0, 15, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 'donation-example', NULL, 7, 'Product Info', 'Video', 'Tell-A-Friend', 'Product Reviews', 'Add Review', NULL, '<strong>One-Time Item Fee(s):</strong> ', 'Total One-Time Fees(s): ', 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, 500, 'Enter Amount: $<br> (Max $500):', 25, 0, NULL, NULL, 0, 'Add-to-Cart', 0, 'More Info', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 600, NULL, NULL, NULL, NULL, NULL, 'You did not enter the order minimum amount for this item. The minimum number you can purchase of this item is:  ', '2023-05-23 22:53:30', NULL, 0, NULL, 0, 1, 0, 7, 0.00, NULL, 0, 3, 0);
INSERT INTO `sitestorepro_products` VALUES (1000031, '2-Day Social Media Workshop', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 7, 1, 0, 150.00, 15.00, 0.00, 0.00, 0, 0, 0, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 'Gift Wrap Item?', 'Please enter a message to be included on a gift card with the wrapped gift (optional):', 0, 0, NULL, 1, 0, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0, 365, 1000, NULL, 0, 0, NULL, NULL, 1, '2023-05-12 00:00:00', '2023-05-12 00:00:00', 0, 0, 0, NULL, NULL, 0, 1, 2, '2023-05-24 00:48:12', 2, '2023-07-25 14:20:44', 175, 3, NULL, 1, NULL, 1, 'You have exceeded the max number of items allowed for this item. The maximum number (QTY) you can purchase of this item is: ', 'Please Select', 'Selection is required.', '2 day social media workshop prestige design workshops amp seminars 1000031 2 day social ', 'Please Select', 'Selection is required.', 'Item Total: ', NULL, NULL, NULL, NULL, 'Stock Level: ', NULL, NULL, 'Buy Now', 'Quick-Shop', NULL, NULL, 'CB92E08D', 0, 0, 0, 0, NULL, 0, 0, 0, 1, 0, 0, 0, 0, 15, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 'sample-event', NULL, 7, 'Product Info', 'Video', 'Tell-A-Friend', 'Product Reviews', 'Add Review', NULL, '<strong>One-Time Item Fee(s):</strong> ', 'Total One-Time Fees(s): ', 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 1000, NULL, 25, 0, NULL, NULL, 0, 'Add-to-Cart', 1, 'More Info', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 600, NULL, NULL, NULL, NULL, NULL, 'You did not enter the order minimum amount for this item. The minimum number you can purchase of this item is:  ', '2023-07-25 14:20:44', NULL, 0, NULL, 0, 1, 0, 7, 0.00, NULL, 0, 3, 0);
INSERT INTO `sitestorepro_products` VALUES (1000032, 'Inventory Management Seminar - Advanced Course', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '11C7CC80', 0, 7, 1, 0, 450.00, 300.00, 0.00, 0.00, 0, 0, 0, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 'Gift Wrap Item?', 'Please enter a message to be included on a gift card with the wrapped gift (optional):', 0, 0, NULL, 1, 0, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0, 365, 1000, NULL, 0, 0, NULL, NULL, 1, '2023-05-26 00:00:00', '2023-05-26 00:00:00', 0, 0, 0, NULL, NULL, 0, 1, 2, '2023-05-24 17:19:07', 1, '2023-08-01 01:28:36', 93, 0, NULL, 1, NULL, 1, 'You have exceeded the max number of items allowed for this item. The maximum number (QTY) you can purchase of this item is: ', 'Please Select', 'Selection is required.', 'inventory management seminar advanced course prestige design workshops amp seminars 1000032 11c7cc80 11c7cc80 ', 'Please Select', 'Selection is required.', 'Item Total: ', NULL, NULL, NULL, NULL, 'Stock Level: ', NULL, NULL, 'Buy Now', 'Quick-Shop', NULL, NULL, '11C7CC80', 0, 0, 0, 0, NULL, 0, 0, 0, 1, 0, 0, 0, 0, 15, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 'inventory-course-advanced', NULL, 7, 'Product Info', 'Video', 'Tell-A-Friend', 'Product Reviews', 'Add Review', NULL, NULL, NULL, 0, NULL, 'Please select event date/time', 'Please make a selection.', NULL, NULL, NULL, 0, NULL, 0, 1000, NULL, 25, 0, NULL, 'This is an example of an event that is listed on two seperate items instead of one item with multiple dates per event. This type of listing is typically used when creating a event that has mulitple workshops such as a basic or advanced course.', 0, 'Add-to-Cart', 1, 'More Info', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 600, NULL, NULL, NULL, NULL, NULL, 'You did not enter the order minimum amount for this item. The minimum number you can purchase of this item is:  ', '2023-08-01 01:28:36', NULL, 0, NULL, 0, 1, 0, 7, 0.00, NULL, 0, 3, 0);
INSERT INTO `sitestorepro_products` VALUES (1000033, 'eCommerce Strategies Seminar', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'C73349BF', 0, 7, 1, 0, 150.00, 15.00, 0.00, 0.00, 0, 0, 0, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 'Gift Wrap Item?', 'Please enter a message to be included on a gift card with the wrapped gift (optional):', 0, 0, NULL, 1, 0, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0, 365, 1000, NULL, 0, 0, NULL, NULL, 1, '2023-05-27 00:00:00', '2023-05-27 00:00:00', 0, 0, 0, NULL, NULL, 0, 1, 2, '2023-05-24 23:20:43', 1, '2023-08-01 01:32:34', 129, 1, NULL, 1, NULL, 1, 'You have exceeded the max number of items allowed for this item. The maximum number (QTY) you can purchase of this item is: ', 'Please Select', 'Selection is required.', 'ecommerce strategies seminar prestige design workshops amp seminars 1000033 c73349bf c73349bf ', 'Please Select', 'Selection is required.', 'Item Total: ', NULL, NULL, NULL, NULL, 'Stock Level: ', NULL, NULL, 'Buy Now', 'Quick-Shop', NULL, NULL, 'C73349BF', 0, 0, 0, 0, 1, 0, 0, 0, 1, 0, 0, 0, 0, 15, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 7, 'Product Info', 'Video', 'Tell-A-Friend', 'Product Reviews', 'Add Review', NULL, NULL, NULL, 0, NULL, 'Please select event date/time', 'Please make a selection.', NULL, NULL, NULL, 0, NULL, 0, 1000, NULL, 25, 0, NULL, NULL, 0, 'Add-to-Cart', 1, 'More Info', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 600, NULL, NULL, NULL, NULL, NULL, 'You did not enter the order minimum amount for this item. The minimum number you can purchase of this item is:  ', '2023-08-01 01:32:34', NULL, 0, NULL, 0, 1, 0, 7, 0.00, NULL, 0, 3, 0);
INSERT INTO `sitestorepro_products` VALUES (1000034, 'Digital Marketing Seminar', NULL, '<div id=\"cms-b6ggcff6ha0g\" class=\"cms-row-no-borders\">\r\n<h2><span style=\"font-size: 3rem;\">Don\'t Miss Our Digital Marketing Seminar!</span></h2>\r\n<h3>Three different dates this month!</h3>\r\n<div><span style=\"font-size: 1.2rem;\">From marketing basics such as adwords, social media and email campaigns to advanced online advertising strategies that utilize multi-channel promotions,&nbsp; our Marketing workshop will teach you how to successfully expand your business with digital marketing.</span></div>\r\n<br><span style=\"font-size: 1.2rem;\">This sample event shows you how you have the same event with different dates and each date can have a specific inventory level. All content on this page was created without any custom programming. The image gallery and image slideshow features can be added to any item or page with the included shortcode features.</span></div>\r\n<div class=\"plugin\">{{Slideshow||id=2}}</div>\r\n<div class=\"cms-row-no-borders\">\r\n<div align=\"center\">\r\n<h2><span style=\"font-size: 3rem;\">More Upcoming Events</span></h2>\r\n</div>\r\n</div>\r\n<div class=\"plugin\">{{Calendar}}</div>', NULL, NULL, NULL, NULL, NULL, NULL, '92212DB3', 0, 7, 1, 0, 150.00, 15.00, 0.00, 0.00, 0, 0, 0, 0, 0, 1, 1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 'Gift Wrap Item?', 'Please enter a message to be included on a gift card with the wrapped gift (optional):', 0, 0, NULL, 1, 0, NULL, 1, '', '', NULL, 0, '', '', NULL, 0, NULL, NULL, 1, '', 'https://d3t23w3v39t89j.cloudfront.net/event-background-image-sample.jpg', 4, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 0, 1, 0, 0, 1, 0, 0, 0, 1, 0, NULL, 0, 365, 1000, NULL, 0, 0, NULL, NULL, 1, '2023-05-29 00:00:00', '2023-05-29 00:00:00', 0, 0, 0, NULL, NULL, 0, 1, 2, '2023-05-24 23:22:27', 1, '2023-08-01 01:29:31', 868, 3, NULL, 1, NULL, 1, 'You have exceeded the max number of items allowed for this item. The maximum number (QTY) you can purchase of this item is: ', 'Please Select', 'Selection is required.', 'digital marketing seminar prestige design workshops amp seminars don t miss our digital marketing seminar three different dates month from marketing basics such adwords social media email campaigns advanced online advertising strategies utilize multi channel promotions our marketing workshop teach successfully expand business digital marketing sample event shows same event different dates each date specific inventory level content page created without any custom programming image gallery image slideshow features added any item page included shortcode features more upcoming events general listings set 2 jan 01 2024 new year s 2024 general listings set 3 jan 01 2025 new year s 2025 general listings set 4 jan 01 2026 new year s 2026 general listings set 5 jan 01 2027 new year s 2027 general listings set 6 jan 01 2028 new year s 2028 var events list var events lister response events list eachevents lister functionkey val events list events list key val events container events container events list event list htmlevents container cal setdataresponse days grid else event list html events header month year no events set events jquery event listing container on click event count function var content id this attr content id document on click event target function event event preventdefault html body animate scrolltop attrthis href offset top 500 1000034 92212db3 3rd session 2 pm 10 00 ', 'Please Select', 'Selection is required.', 'Workshop Total:', 'This is an example workshop that has multiple date/times with individual inventory levels (attendee | space availability) for each session.', NULL, NULL, NULL, 'Spaces Left: ', NULL, NULL, 'Register', 'Quick-Enroll', NULL, NULL, '92212DB3', 0, 0, 0, 0, NULL, 0, 0, 0, 1, 0, 0, 1, 1, 50, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 'multi-session-workshop', NULL, 9, 'Workshop Information', 'Workshop Video', 'Invite Co-Worker', 'Product Reviews', 'Add Review', NULL, NULL, NULL, 1, 'https://cdn-demo-store.s3.us-west-1.amazonaws.com/video-conference-edample.mp4', 'Select Event Date:', 'Please select your preferred event date/time.', 'https://d3t23w3v39t89j.cloudfront.net/sample-events-full-width-header.jpg', '<h1>Up Your Digital Marketing IQ!</h1>', '', 0, '', 0, 1000, NULL, 25, 0, NULL, '<span style=\"font-size: 1.5rem;\">This is an example workshop that has multiple dates with individual inventory levels (attendee | space availability) for each session.&nbsp;</span><br><br><span style=\"font-size: 1.5rem;\">This sample event also utilizes some other built-in item display features such as a full-width header image and a content background image. Both of those optional image features are controlled directly from the product manager (admin) and require no page customization or programming.</span>', 0, 'Add-to-Cart', 1, 'More Info', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, NULL, NULL, '', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 600, NULL, NULL, NULL, NULL, NULL, 'You did not enter the order minimum amount for this item. The minimum number you can purchase of this item is:  ', '2023-08-01 01:29:31', NULL, 0, NULL, 0, 1, 0, 7, 0.00, NULL, 0, 3, 0);
INSERT INTO `sitestorepro_products` VALUES (1000035, 'Business Processes Seminar', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'DAE7B482', 0, 7, 1, 0, 300.00, 15.00, 0.00, 0.00, 0, 0, 0, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 'Gift Wrap Item?', 'Please enter a message to be included on a gift card with the wrapped gift (optional):', 0, 0, NULL, 1, 0, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0, 365, 1000, NULL, 0, 0, NULL, NULL, 1, '2023-05-29 00:00:00', '2023-05-29 00:00:00', 0, 0, 0, NULL, NULL, 0, 1, 2, '2023-05-24 23:23:45', 1, '2023-08-01 01:33:58', 60, 4, NULL, 1, NULL, 1, 'You have exceeded the max number of items allowed for this item. The maximum number (QTY) you can purchase of this item is: ', 'Please Select', 'Selection is required.', 'business processes seminar prestige design workshops amp seminars 1000035 dae7b482 dae7b482 ', 'Please Select', 'Selection is required.', 'Item Total: ', NULL, NULL, NULL, NULL, 'Stock Level: ', NULL, NULL, 'Buy Now', 'Quick-Shop', NULL, NULL, 'DAE7B482', 0, 0, 0, 0, NULL, 0, 0, 0, 1, 0, 0, 0, 0, 15, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 7, 'Product Info', 'Video', 'Tell-A-Friend', 'Product Reviews', 'Add Review', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 1000, NULL, 25, 0, NULL, NULL, 0, 'Add-to-Cart', 1, 'More Info', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 600, NULL, NULL, NULL, NULL, NULL, 'You did not enter the order minimum amount for this item. The minimum number you can purchase of this item is:  ', '2023-08-01 01:33:58', NULL, 0, NULL, 0, 1, 0, 7, 0.00, NULL, 0, 3, 0);
INSERT INTO `sitestorepro_products` VALUES (1000036, 'Inventory Management Seminar - Intro Course', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'C649D6DC', 0, 7, 1, 0, 300.00, 15.00, 0.00, 0.00, 0, 0, 0, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 'Gift Wrap Item?', 'Please enter a message to be included on a gift card with the wrapped gift (optional):', 0, 0, NULL, 1, 0, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0, 365, 1000, NULL, 0, 0, NULL, NULL, 1, '2023-05-29 00:00:00', '2023-05-29 00:00:00', 0, 0, 0, NULL, NULL, 0, 1, 2, '2023-05-26 11:48:34', 2, '2023-07-25 14:21:45', 58, 2, NULL, 1, NULL, 1, 'You have exceeded the max number of items allowed for this item. The maximum number (QTY) you can purchase of this item is: ', 'Please Select', 'Selection is required.', 'inventory management seminar intro course prestige design workshops amp seminars 1000036 c649d6dc c649d6dc ', 'Please Select', 'Selection is required.', 'Item Total: ', NULL, NULL, NULL, NULL, 'Stock Level: ', NULL, NULL, 'Buy Now', 'Quick-Shop', NULL, NULL, 'C649D6DC', 0, 0, 0, 0, NULL, 0, 0, 0, 1, 0, 0, 0, 0, 15, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 'inventory-course-intro', NULL, 7, 'Product Info', 'Video', 'Tell-A-Friend', 'Product Reviews', 'Add Review', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 1000, NULL, 25, 0, NULL, 'This is an example of an event that is listed on two seperate items instead of one item with multiple dates per event. This type of listing is typically used when creating a event that has mulitple workshops such as a basic or advanced course.', 0, 'Add-to-Cart', 1, 'More Info', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 600, NULL, NULL, NULL, NULL, NULL, 'You did not enter the order minimum amount for this item. The minimum number you can purchase of this item is:  ', '2023-07-25 14:21:45', NULL, 0, NULL, 0, 1, 0, 7, 0.00, NULL, 0, 3, 0);
INSERT INTO `sitestorepro_products` VALUES (1000037, 'Event Sold-Out Example', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '622E9F21', 0, 7, 1, 0, 150.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 'Gift Wrap Item?', 'Please enter a message to be included on a gift card with the wrapped gift (optional):', 0, 0, NULL, 1, 0, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0, 365, 1000, NULL, 0, 0, NULL, NULL, 1, '2023-05-29 00:00:00', '2023-05-29 00:00:00', 0, 0, 0, NULL, NULL, 0, 1, 2, '2023-05-28 01:27:43', 2, '2023-07-08 14:10:51', 76, 0, NULL, 1, NULL, 1, 'You have exceeded the max number of items allowed for this item. The maximum number (QTY) you can purchase of this item is: ', 'Please Select', 'Selection is required.', 'event sold out example prestige design workshops amp seminars 1000037 622e9f21 622e9f21 ', 'Please Select', 'Selection is required.', 'Item Total: ', 'This is an example to show how an event could look if it has been sold out. Unlike the information-only event example, the pricing and add-to-cart functions are still turned on for this item but it has an inventory level of zero so both those features are automatically hidden (disabled).<br><br>Like any item in the online store, once the item reaches an inventory level of zero (0), an out-of-stock message will be automatically displayed. The \"sold-out\" (out-of-stock) message can be any text you like and each item or event can even have it\'s own specific sold-out message.', NULL, NULL, NULL, 'Stock Level: ', NULL, NULL, 'Buy Now', 'Quick-Shop', NULL, NULL, '622E9F21', 0, 0, 0, 0, NULL, 0, 0, 0, 1, 0, 0, 0, 0, 15, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 7, 'Product Info', 'Video', 'Tell-A-Friend', 'Product Reviews', 'Add Review', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 1000, NULL, 25, 0, NULL, 'This is an example to show how an event could look if it has been sold out. Unlike the information-only event example, the pricing and add-to-cart functions are still turned on for this item but it has an inventory level of zero so both those features are automatically hidden (disabled).<br><br>Like any item in the online store, once the item reaches an inventory level of zero (0), an out-of-stock message will be automatically displayed. The \"sold-out\" (out-of-stock) message can be any text you like and each item or event can even have it\'s own specific sold-out message.', 0, 'Add-to-Cart', 1, 'More Info', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 600, NULL, NULL, NULL, NULL, NULL, 'You did not enter the order minimum amount for this item. The minimum number you can purchase of this item is:  ', '2023-07-08 14:10:51', NULL, 0, NULL, 0, 1, 0, 7, 0.00, NULL, 0, 3, 0);
INSERT INTO `sitestorepro_products` VALUES (1000038, 'Information Only Example', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '3F30C224', 0, 7, 1, 0, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 'Gift Wrap Item?', 'Please enter a message to be included on a gift card with the wrapped gift (optional):', 0, 0, NULL, 1, 0, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0, 365, 1000, NULL, 0, 0, 1, NULL, 1, '2023-05-29 00:00:00', '2023-05-29 00:00:00', 0, 0, 0, NULL, NULL, 0, 1, 2, '2023-05-28 01:42:43', 2, '2023-06-03 20:43:00', 80, 0, NULL, 1, NULL, 1, 'You have exceeded the max number of items allowed for this item. The maximum number (QTY) you can purchase of this item is: ', 'Please Select', 'Selection is required.', 'information only example prestige design workshops & seminars 1000038 3f30c224 3f30c224 ', 'Please Select', 'Selection is required.', 'Item Total: ', 'This is an example of an event that it posted as an information item only. The pricing and add-to-cart functionality has been turned off for this specific item so only the event information is display.<br><br>Merchants frequently use this type of \"item\" for promotional events where there is no fee charged but they want it to appear in their event calendar like normal paid event items.', NULL, NULL, NULL, 'Stock Level: ', NULL, NULL, 'Buy Now', 'Quick-Shop', NULL, NULL, '3F30C224', 0, 0, 0, 0, NULL, 0, 0, 0, 1, 0, 0, 0, 0, 15, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 7, 'Product Info', 'Video', 'Tell-A-Friend', 'Product Reviews', 'Add Review', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 1000, NULL, 25, 0, NULL, 'This is an example of an event that it posted as an information item only. The pricing and add-to-cart functionality has been turned off for this specific item so only the event information is display.<br><br>Merchants frequently use this type of \"item\" for promotional events where there is no fee charged but they want it to appear in their event calendar like normal paid event items.', 0, 'Add-to-Cart', 1, 'More Info', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 600, NULL, NULL, NULL, NULL, NULL, 'You did not enter the order minimum amount for this item. The minimum number you can purchase of this item is:  ', '2023-06-03 20:43:00', NULL, 0, NULL, 0, 1, 0, 7, 0.00, NULL, 0, 3, 0);

-- ----------------------------
-- Table structure for sitestorepro_products_delivery
-- ----------------------------
DROP TABLE IF EXISTS `sitestorepro_products_delivery`;
CREATE TABLE `sitestorepro_products_delivery`  (
  `DeliveryMethodID` int NOT NULL AUTO_INCREMENT,
  `DeliveyMethod` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `AddFee` double NULL DEFAULT 0,
  `Active` int NULL DEFAULT 0,
  `StoreID` int NULL DEFAULT NULL,
  `sitestorepro_upgrade_text1` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_text2` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_text3` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_vc1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sitestorepro_upgrade_vc2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sitestorepro_upgrade_num1` int NULL DEFAULT NULL,
  `sitestorepro_upgrade_num2` int NULL DEFAULT NULL,
  `sitestorepro_upgrade_num3` int NULL DEFAULT NULL,
  `sitestorepro_upgrade_num4` int NULL DEFAULT NULL,
  `sitestorepro_upgrade_num5` int NULL DEFAULT NULL,
  `sitestorepro_upgrade_fl1` double NULL DEFAULT NULL,
  `sitestorepro_upgrade_fl2` double NULL DEFAULT NULL,
  `sitestorepro_upgrade_fl3` double NULL DEFAULT NULL,
  `sitestorepro_upgrade_ln1` bigint NULL DEFAULT NULL,
  `sitestorepro_upgrade_ln2` bigint NULL DEFAULT NULL,
  `sitestorepro_upgrade_date1` datetime NULL DEFAULT NULL,
  `sitestorepro_upgrade_date2` datetime NULL DEFAULT NULL,
  `sitestorepro_upgrade_date3` datetime NULL DEFAULT NULL,
  `sitestorepro_upgrade_date4` datetime NULL DEFAULT NULL,
  `sitestorepro_upgrade_image1` longblob NULL,
  PRIMARY KEY (`DeliveryMethodID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sitestorepro_products_delivery
-- ----------------------------

-- ----------------------------
-- Table structure for sitestorepro_products_download_opt_php
-- ----------------------------
DROP TABLE IF EXISTS `sitestorepro_products_download_opt_php`;
CREATE TABLE `sitestorepro_products_download_opt_php`  (
  `DeliveryMethodID` int NOT NULL AUTO_INCREMENT,
  `ProdID` int NULL DEFAULT NULL,
  `DeliveyMethod` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `AddFee` double NULL DEFAULT NULL,
  `StoreID` int NULL DEFAULT 1,
  `MenuOrdering` double NULL DEFAULT NULL,
  `Shipment` int NULL DEFAULT 0,
  `ChargeTax` int NULL DEFAULT 0,
  `sitestorepro_upgrade_text1` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_text2` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_text3` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_num1` int NULL DEFAULT NULL,
  `sitestorepro_upgrade_num2` int NULL DEFAULT NULL,
  `sitestorepro_upgrade_num3` int NULL DEFAULT NULL,
  PRIMARY KEY (`DeliveryMethodID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sitestorepro_products_download_opt_php
-- ----------------------------
INSERT INTO `sitestorepro_products_download_opt_php` VALUES (4, 1000016, 'Watch Online Plus Downloadable eBook', 0, 1, 1, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_products_download_opt_php` VALUES (5, 1000016, 'Online + Shipped Hardcopy (+10.00)', 10, 1, 2, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL);

-- ----------------------------
-- Table structure for sitestorepro_products_events
-- ----------------------------
DROP TABLE IF EXISTS `sitestorepro_products_events`;
CREATE TABLE `sitestorepro_products_events`  (
  `eventid` int NOT NULL AUTO_INCREMENT,
  `prodid` int NULL DEFAULT 0,
  `event_start_date` datetime NULL DEFAULT '2023-01-01 00:00:00',
  `event_end_date` datetime NULL DEFAULT '2023-01-01 00:00:00',
  `event_label` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `label_background` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `alternate_label` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `show_date` int NULL DEFAULT 0,
  `date_format` int NULL DEFAULT 0,
  `event_fee` double NULL DEFAULT 0,
  `event_location` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `event_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `event_sort` float NULL DEFAULT 0,
  PRIMARY KEY (`eventid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 15 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sitestorepro_products_events
-- ----------------------------
INSERT INTO `sitestorepro_products_events` VALUES (1, 1000031, '2023-09-08 10:00:00', '2023-09-09 11:00:00', '2-Day Social Media Workshop', 'blue', NULL, 0, 0, 0, NULL, NULL, 0);
INSERT INTO `sitestorepro_products_events` VALUES (2, 1000032, '2023-09-27 13:00:00', '2023-09-27 15:00:00', 'Inventory Management Seminar', NULL, 'Inventory Management Seminar | 1 PM - 3 PM |', 0, 0, 0, NULL, NULL, 0);
INSERT INTO `sitestorepro_products_events` VALUES (3, 1000033, '2023-09-21 10:00:00', '2023-09-21 11:00:00', 'eCommerce Strategies Seminar', '#006699', NULL, 0, 0, 0, NULL, NULL, 0);
INSERT INTO `sitestorepro_products_events` VALUES (4, 1000034, '2023-09-16 09:00:00', '2023-09-16 10:00:00', 'Sept 16, 2023 : 9 AM - 10 AM', '#ff6d0d', 'Digital Marketing Seminar | 9 AM - 10 AM |', 0, 0, 0, NULL, NULL, 0);
INSERT INTO `sitestorepro_products_events` VALUES (5, 1000034, '2023-09-24 13:00:00', '2023-09-24 14:00:00', 'Sept 24, 2023 :  1 PM - 2 PM', '#ff6d0d', 'Digital Marketing Seminar | 1 PM - 2 PM |', 0, 0, 0, NULL, NULL, 0);
INSERT INTO `sitestorepro_products_events` VALUES (6, 1000034, '2023-09-18 14:00:00', '2023-09-18 15:00:00', 'Sept 18 : 2 PM - 3 PM', '#ff7b24', 'Digital Marketing Seminar | 2 PM - 3 PM |', 0, 0, 0, NULL, NULL, 0);
INSERT INTO `sitestorepro_products_events` VALUES (8, 1000037, '2023-09-24 00:00:00', '2023-09-24 00:00:00', 'Event Sold Out Example', 'red', NULL, 0, 0, 0, NULL, NULL, 0);
INSERT INTO `sitestorepro_products_events` VALUES (9, 1000038, '2023-09-04 00:00:00', '2023-09-04 00:00:00', 'Info-Only Example', '#99CC00', NULL, 0, 0, 0, NULL, NULL, 0);
INSERT INTO `sitestorepro_products_events` VALUES (10, 1000035, '2023-09-14 09:00:00', '2023-09-14 11:00:00', 'Business Processes Seminar', '#7db3cd', 'Business Processes Seminar | 9 AM - 11 AM |', 0, 0, 0, NULL, NULL, 0);
INSERT INTO `sitestorepro_products_events` VALUES (14, 1000036, '2023-09-18 09:00:00', '2023-09-18 11:00:00', 'Inventory Management Seminar', NULL, 'Inventory Management Seminar | 9 AM - 11 AM |', 0, 0, 0, NULL, NULL, 0);

-- ----------------------------
-- Table structure for sitestorepro_products_inventory_levels
-- ----------------------------
DROP TABLE IF EXISTS `sitestorepro_products_inventory_levels`;
CREATE TABLE `sitestorepro_products_inventory_levels`  (
  `inventorylevelid` int NOT NULL AUTO_INCREMENT,
  `prodid` int NULL DEFAULT 0,
  `InventorySKU` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sizeid` int NULL DEFAULT 0,
  `colorid` int NULL DEFAULT 0,
  `materialid` int NULL DEFAULT 0,
  `option1id` int NULL DEFAULT 0,
  `option2id` int NULL DEFAULT 0,
  `inventorylevel` int NULL DEFAULT 0,
  `inventorybuffer` int NULL DEFAULT 0,
  `alertlevel` int NULL DEFAULT 0,
  `inventorysold` int NULL DEFAULT 0,
  `backordered` int NULL DEFAULT 0,
  `discontinued` int NULL DEFAULT 0,
  `vendorid` int NULL DEFAULT 0,
  `OutOfStockID` int NULL DEFAULT 1,
  `lastmodified` datetime NULL DEFAULT NULL,
  `lastmodifiedby` int NULL DEFAULT 0,
  `StoreID` int NULL DEFAULT 0,
  `lastsentalert` datetime NULL DEFAULT NULL,
  `sitestorepro_upgrade_text1` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_text2` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_text3` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_text4` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_text5` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_text6` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_text7` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_text8` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_text9` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_text10` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sitestorepro_upgrade_vc1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sitestorepro_upgrade_vc2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sitestorepro_upgrade_vc3` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sitestorepro_upgrade_vc4` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sitestorepro_upgrade_vc5` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sitestorepro_upgrade_vc6` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sitestorepro_upgrade_vc7` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sitestorepro_upgrade_vc8` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sitestorepro_upgrade_vc9` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sitestorepro_upgrade_vc10` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sitestorepro_upgrade_num1` int NULL DEFAULT 0,
  `sitestorepro_upgrade_num2` int NULL DEFAULT 0,
  `sitestorepro_upgrade_num3` int NULL DEFAULT 0,
  `sitestorepro_upgrade_num4` int NULL DEFAULT 0,
  `sitestorepro_upgrade_num5` int NULL DEFAULT 0,
  `sitestorepro_upgrade_num6` int NULL DEFAULT 0,
  `sitestorepro_upgrade_num7` int NULL DEFAULT 0,
  `sitestorepro_upgrade_num8` int NULL DEFAULT 0,
  `sitestorepro_upgrade_num9` int NULL DEFAULT 0,
  `sitestorepro_upgrade_num10` int NULL DEFAULT 0,
  `sitestorepro_upgrade_fl1` double NULL DEFAULT 0,
  `sitestorepro_upgrade_fl2` double NULL DEFAULT 0,
  `sitestorepro_upgrade_fl3` double NULL DEFAULT 0,
  `sitestorepro_upgrade_fl4` double NULL DEFAULT 0,
  `sitestorepro_upgrade_fl5` double NULL DEFAULT 0,
  `sitestorepro_upgrade_fl6` double NULL DEFAULT 0,
  `sitestorepro_upgrade_fl7` double NULL DEFAULT 0,
  `sitestorepro_upgrade_fl8` double NULL DEFAULT 0,
  `sitestorepro_upgrade_fl9` double NULL DEFAULT 0,
  `sitestorepro_upgrade_fl10` double NULL DEFAULT 0,
  `sitestorepro_upgrade_ln1` bigint NULL DEFAULT 0,
  `sitestorepro_upgrade_ln2` bigint NULL DEFAULT 0,
  `sitestorepro_upgrade_ln3` bigint NULL DEFAULT 0,
  `sitestorepro_upgrade_ln4` bigint NULL DEFAULT 0,
  `sitestorepro_upgrade_ln5` bigint NULL DEFAULT 0,
  `sitestorepro_upgrade_date1` datetime NULL DEFAULT NULL,
  `sitestorepro_upgrade_date2` datetime NULL DEFAULT NULL,
  `sitestorepro_upgrade_date3` datetime NULL DEFAULT NULL,
  `sitestorepro_upgrade_date4` datetime NULL DEFAULT NULL,
  `sitestorepro_upgrade_date5` datetime NULL DEFAULT NULL,
  `sitestorepro_upgrade_image1` longblob NULL,
  `sitestorepro_upgrade_image2` longblob NULL,
  `sitestorepro_upgrade_image3` longblob NULL,
  `sitestorepro_upgrade_image4` longblob NULL,
  `sitestorepro_upgrade_image5` longblob NULL,
  PRIMARY KEY (`inventorylevelid`) USING BTREE,
  INDEX `prodidinvr`(`prodid` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 148 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sitestorepro_products_inventory_levels
-- ----------------------------
INSERT INTO `sitestorepro_products_inventory_levels` VALUES (23, 1000002, 'sample-sku-1000002', 0, 0, 0, 0, 0, 997, 1, 5, 3, 0, 0, 0, 1, '2012-02-08 20:25:47', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_products_inventory_levels` VALUES (24, 1000003, 'sample-sku-1000003', 0, 0, 0, 0, 0, 999, 1, 5, 1, 0, 0, 0, 1, '2012-01-04 13:05:05', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_products_inventory_levels` VALUES (25, 1000005, 'sample-sku-1000005', 0, 0, 0, 0, 0, 997, 1, 5, 3, 0, 0, 0, 1, '2012-02-08 14:20:59', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_products_inventory_levels` VALUES (26, 1000006, 'sample-sku-1000006', 0, 0, 0, 0, 0, 998, 1, 5, 2, 0, 0, 0, 1, '2012-01-02 14:21:05', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_products_inventory_levels` VALUES (28, 1000014, 'sample-sku-1000014', 0, 0, 0, 0, 0, 920, 0, 5, 80, 0, 0, 0, 1, '2011-01-20 23:43:15', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_products_inventory_levels` VALUES (29, 1000013, 'sample-sku-1000013', 0, 0, 0, 0, 0, 983, 1, 5, 17, 0, 0, 0, 1, '2011-08-15 13:24:50', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_products_inventory_levels` VALUES (30, 1000011, 'sample-sku-1000011', 0, 0, 0, 0, 0, 998, 0, 5, 2, 0, 0, 0, 1, '2011-08-09 19:19:16', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_products_inventory_levels` VALUES (31, 1000004, 'sample-sku-1000004', 0, 0, 0, 0, 0, 998, 1, 5, 2, 0, 0, 0, 1, '2011-08-15 13:40:55', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_products_inventory_levels` VALUES (32, 1000009, 'sample-sku-1000009', 0, 0, 0, 0, 0, 999, 1, 5, 1, 0, 0, 0, 1, '2011-08-15 16:47:14', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_products_inventory_levels` VALUES (33, 1000008, 'sample-sku-1000008', 0, 0, 0, 0, 0, 994, 1, 5, 6, 0, 0, 0, 1, '2011-10-08 20:28:27', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_products_inventory_levels` VALUES (34, 1000010, 'sample-sku-1000010', 0, 0, 0, 0, 0, 997, 1, 5, 3, 0, 0, 0, 1, '2011-08-16 00:54:48', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_products_inventory_levels` VALUES (35, 1000012, 'sample-sku-1000012', 0, 0, 0, 0, 0, 1300, 1, 5, 0, 0, 0, 0, 1, '2023-07-09 18:19:15', 2, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_products_inventory_levels` VALUES (49, 1000007, 'sample-sku-1000007', 0, 0, 0, 0, 0, 999, 1, 5, 1, 0, 0, 0, 1, '2011-08-30 16:49:29', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_products_inventory_levels` VALUES (90, 1000001, 'sample-sku-1000001', 0, 0, 0, 0, 0, 999, 1, 5, 1, 0, 0, 0, 1, '2011-10-08 20:24:57', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_products_inventory_levels` VALUES (99, 1000016, 'sample-sku-1000016', 0, 0, 0, 0, 0, 975, 0, 1, 25, 0, 0, 0, 1, '2011-08-13 00:29:25', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_products_inventory_levels` VALUES (100, 1000017, 'sample-sku-1000017', 0, 0, 0, 0, 0, 991, 0, 1, 9, 0, 0, 0, 1, '2011-08-20 03:24:13', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_products_inventory_levels` VALUES (101, 1000018, 'sample-sku-1000018', 0, 0, 0, 0, 0, 983, 0, 0, 17, 0, 0, 0, 1, '2014-06-01 13:24:54', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_products_inventory_levels` VALUES (102, 1000019, 'sample-sku-1000019', 0, 0, 0, 0, 0, 978, 0, 0, 22, 0, 0, 0, 1, '2014-06-01 16:48:58', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_products_inventory_levels` VALUES (103, 1000020, 'sample-sku-1000020', 0, 0, 0, 0, 0, 996, 0, 0, 4, 0, 0, 0, 1, '2014-06-01 17:47:45', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_products_inventory_levels` VALUES (104, 1000021, 'sample-sku-1000021', 0, 0, 0, 0, 0, 997, 0, 0, 3, 0, 0, 0, 1, '2014-06-02 13:44:47', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_products_inventory_levels` VALUES (105, 1000022, 'sample-sku-1000022', 0, 0, 0, 0, 0, 983, 0, 0, 17, 0, 0, 0, 1, '2014-06-02 15:08:22', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_products_inventory_levels` VALUES (106, 1000023, 'sample-sku-1000023', 0, 0, 0, 0, 0, 998, 0, 0, 2, 0, 0, 0, 1, '2014-06-09 19:09:02', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_products_inventory_levels` VALUES (107, 1000024, 'sample-sku-1000024', 0, 0, 0, 0, 0, 996, 0, 0, 4, 0, 0, 0, 1, '2014-06-09 19:24:47', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_products_inventory_levels` VALUES (108, 1000025, 'sample-sku-1000025', 0, 0, 0, 0, 0, 994, 0, 0, 6, 0, 0, 0, 1, '2014-06-14 13:32:44', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_products_inventory_levels` VALUES (109, 1000026, 'sample-sku-1000026', 0, 0, 0, 0, 0, 994, 0, 0, 6, 0, 0, 0, 1, '2014-06-15 10:27:38', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_products_inventory_levels` VALUES (110, 1000027, 'sample-sku-1000027', 0, 0, 0, 0, 0, 999, 0, 0, 1, 0, 0, 0, 1, '2023-06-03 21:01:44', 2, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_products_inventory_levels` VALUES (112, 1000015, 'sample-sku-1000015-black-large', 134, 53, 0, 0, 0, 0, 0, 5, 24, 0, 0, 0, 1, '2023-03-30 00:24:07', 2, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_products_inventory_levels` VALUES (113, 1000015, 'sample-sku-1000015-black-medium', 133, 53, 0, 0, 0, 5, 0, 5, 17, 0, 0, 0, 1, '2023-03-30 00:22:27', 2, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_products_inventory_levels` VALUES (114, 1000015, 'sample-sku-1000015-black-small', 132, 53, 0, 0, 0, 0, 0, 5, 24, 0, 0, 0, 1, '2023-03-30 00:22:44', 2, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_products_inventory_levels` VALUES (115, 1000015, 'sample-sku-1000015-black-xl', 135, 53, 0, 0, 0, 11, 0, 5, 13, 0, 0, 0, 1, '2023-03-30 00:22:54', 2, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_products_inventory_levels` VALUES (116, 1000015, 'sample-sku-1000015-black-xxl', 137, 53, 0, 0, 0, 14, 0, 5, 10, 0, 0, 0, 1, '2023-03-30 00:23:04', 2, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_products_inventory_levels` VALUES (117, 1000015, 'sample-sku-1000015-small-burgundy', 132, 54, 0, 0, 0, 10, 0, 5, 14, 0, 0, 0, 1, '2023-03-30 00:23:13', 2, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_products_inventory_levels` VALUES (118, 1000015, 'sample-sku-1000015-burgundy-medium', 133, 54, 0, 0, 0, 3, 0, 5, 21, 0, 0, 0, 1, '2023-03-30 00:23:20', 2, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_products_inventory_levels` VALUES (119, 1000015, 'sample-sku-1000015-large-burgundy', 134, 54, 0, 0, 0, 0, 0, 5, 24, 0, 0, 0, 1, '2023-03-30 00:23:27', 2, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_products_inventory_levels` VALUES (120, 1000015, 'sample-sku-1000015-burgungy-xl', 135, 54, 0, 0, 0, 22, 0, 5, 2, 0, 0, 0, 1, '2023-03-30 00:20:03', 2, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_products_inventory_levels` VALUES (121, 1000015, 'sample-sku-1000015-burgundy-xxl', 137, 54, 0, 0, 0, 13, 0, 5, 11, 0, 0, 0, 1, '2023-03-30 00:23:45', 2, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_products_inventory_levels` VALUES (122, 1000015, 'sample-sku-1000015-small-white', 132, 52, 0, 0, 0, 0, 0, 5, 24, 0, 0, 0, 1, '2023-03-30 00:24:25', 2, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_products_inventory_levels` VALUES (123, 1000015, 'sample-sku-1000015-white-medium', 133, 52, 0, 0, 0, 9, 0, 5, 15, 0, 0, 0, 1, '2023-03-30 00:25:08', 2, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_products_inventory_levels` VALUES (124, 1000015, 'sample-sku-1000015-large-white', 134, 52, 0, 0, 0, 18, 0, 5, 6, 0, 0, 0, 1, '2023-03-30 00:26:04', 2, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_products_inventory_levels` VALUES (125, 1000015, 'sample-sku-1000015-white-xl', 135, 52, 0, 0, 0, 4, 0, 5, 20, 0, 0, 0, 1, '2023-03-30 00:26:49', 2, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_products_inventory_levels` VALUES (126, 1000015, 'sample-sku-1000015-white-xxl', 137, 52, 0, 0, 0, 17, 0, 5, 7, 0, 0, 0, 1, '2023-03-30 00:29:31', 2, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_products_inventory_levels` VALUES (127, 1000028, 'invoice-example', 0, 0, 0, 0, 0, 1, 0, 0, 1, 0, 0, 0, 6, '2023-06-19 01:46:05', 2, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_products_inventory_levels` VALUES (128, 1000029, 'builder-example', 0, 0, 0, 0, 0, 24, 0, 0, 2, 0, 0, 0, 1, '2023-05-11 17:52:31', 2, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_products_inventory_levels` VALUES (129, 1000030, 'donation-example', 0, 0, 0, 0, 0, 997, 0, 0, 3, 0, 0, 0, 1, '2023-05-17 02:21:08', 2, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_products_inventory_levels` VALUES (130, 1000031, '2-day-social', 0, 0, 0, 0, 0, 0, 0, 0, 3, 0, 0, 0, 7, '2023-07-25 14:18:26', 2, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_products_inventory_levels` VALUES (131, 1000032, '11C7CC80', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 7, '2023-08-01 01:28:28', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_products_inventory_levels` VALUES (132, 1000033, 'C73349BF', 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 7, '2023-08-01 01:32:27', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_products_inventory_levels` VALUES (133, 1000034, '1st Session : 9 AM', 0, 0, 0, 0, 0, 0, 0, 0, 2, 0, 0, 0, 7, '2023-07-25 14:22:51', 2, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_products_inventory_levels` VALUES (134, 1000035, 'DAE7B482', 0, 0, 0, 0, 0, 0, 0, 0, 4, 0, 0, 0, 7, '2023-08-01 01:33:49', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_products_inventory_levels` VALUES (135, 1000036, 'C649D6DC', 0, 0, 0, 0, 0, 0, 0, 0, 2, 0, 0, 0, 7, '2023-07-25 14:14:48', 2, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_products_inventory_levels` VALUES (136, 1000037, '622E9F21', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 5, '2023-07-08 14:10:46', 2, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_products_inventory_levels` VALUES (137, 1000038, '3F30C224', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 7, '2023-06-03 20:42:52', 2, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_products_inventory_levels` VALUES (138, 1000034, '2nd Session : 11 AM', 0, 0, 0, 0, 0, 0, 0, 5, 14, 0, 0, 0, 7, '2023-07-25 14:23:03', 2, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `sitestorepro_products_inventory_levels` VALUES (139, 1000034, '3rd Session : 2 PM (+$10.00)', 0, 0, 0, 0, 0, 0, 0, 5, 0, 0, 0, 0, 7, '2023-08-01 01:29:18', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

SET FOREIGN_KEY_CHECKS = 1;
