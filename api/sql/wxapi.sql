-- 建立数据库
CREATE DATABASE `wxapi` DEFAULT charset=utf8;

-- 测试表建立
DROP TABLE IF EXISTS `test`;
CREATE TABLE IF NOT EXISTS `test`(
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` VARCHAR(128) NOT NULL COMMENT '测试',
  `time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `update_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '修改时间',
  `status` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '状态(单选):0=未激活,1=激活',
  PRIMARY KEY(`id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='测试表管理';

-- 轮播图表建立
DROP TABLE IF EXISTS `lunbotu`;
CREATE TABLE IF NOT EXISTS `lunbotu`(
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` VARCHAR(128) NOT NULL COMMENT '图片标题',
  `img` VARCHAR(256) NOT NULL COMMENT '图片地址',
  `href` VARCHAR(256) NOT NULL COMMENT '跳转链接',
  `time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `update_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '修改时间',
  `status` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '状态(单选):0=未激活,1=激活',
  PRIMARY KEY(`id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='轮播图表管理';

-- 视频文件信息表建立
DROP TABLE IF EXISTS `video`;
CREATE TABLE IF NOT EXISTS `video`(
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` VARCHAR(128) NOT NULL COMMENT '视频名称',
  `img` VARCHAR(256) NOT NULL COMMENT '图片地址',
  `src` VARCHAR(256) NOT NULL COMMENT '跳转链接',
  `author` VARCHAR(32) NOT NULL COMMENT '上传作者名',
  `length` VARCHAR(32) NOT NULL COMMENT '视频时长',
  `time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `update_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '修改时间',
  `status` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '状态(单选):0=未激活,1=激活',
  PRIMARY KEY(`id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='视频文件信息表管理';