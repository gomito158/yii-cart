CREATE TABLE `tbl_catalog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL DEFAULT '0',
  `url` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `position` int(3) NOT NULL DEFAULT '0',
  `status` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE `tbl_catalog_relation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL DEFAULT '0',
  `pr_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `cid` (`cid`),
  KEY `pr_id` (`pr_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE `tbl_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `manger_id` int(11) NOT NULL DEFAULT '0',
  `manger_comments` text NOT NULL,
  `createdate` int(11) NOT NULL DEFAULT '0',
  `finishdate` int(11) NOT NULL DEFAULT '0',
  `payable` float(11,2) NOT NULL DEFAULT '0.00',
  `status` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `manger_id` (`manger_id`),
  KEY `createdate` (`createdate`),
  KEY `finishdate` (`finishdate`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `tbl_orders_fields` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `varname` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `field_type` varchar(50) NOT NULL,
  `field_size` int(3) NOT NULL DEFAULT '0',
  `field_size_min` int(3) NOT NULL DEFAULT '0',
  `required` int(1) NOT NULL DEFAULT '0',
  `match` varchar(255) NOT NULL,
  `range` varchar(5000) NOT NULL,
  `error_message` varchar(255) NOT NULL,
  `other_validator` varchar(255) NOT NULL,
  `default` varchar(255) NOT NULL,
  `widget` varchar(255) NOT NULL DEFAULT '',
  `widgetparams` varchar(5000) NOT NULL DEFAULT '',
  `position` int(3) NOT NULL DEFAULT '0',
  `visible` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `varname` (`varname`,`visible`,`widget`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE `tbl_orders_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `or_id` int(11) NOT NULL DEFAULT '0',
  `pr_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `quan` int(11) NOT NULL DEFAULT '0',
  `price` float(11,2) NOT NULL DEFAULT '0.00',
  `product_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `or_id` (`or_id`),
  KEY `pr_id` (`pr_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `tbl_orders_values` (
  `or_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `payingtype` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`or_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `tbl_photos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prefix` varchar(50) NOT NULL DEFAULT '',
  `ngroup` varchar(50) NOT NULL DEFAULT '',
  `filename` varchar(255) NOT NULL DEFAULT '',
  `createdate` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  `status` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE `tbl_photos_relation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL DEFAULT '0',
  `photo_id` int(11) NOT NULL DEFAULT '0',
  `position` int(3) NOT NULL DEFAULT '0',
  `main` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `item_id` (`item_id`),
  KEY `photo_id` (`photo_id`),
  KEY `main` (`main`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE `tbl_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `photo_id` int(11) NOT NULL DEFAULT '0',
  `artno` varchar(255) NOT NULL,
  `url` varchar(50) NOT NULL,
  `tags` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `shortdesc` text NOT NULL,
  `fulldesc` text NOT NULL,
  `price` float(11,2) NOT NULL DEFAULT '0.00',
  `store` int(11) NOT NULL DEFAULT '0',
  `type` int(1) NOT NULL DEFAULT '0',
  `status` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `artno` (`artno`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

CREATE TABLE `tbl_products_fields` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `varname` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `field_type` varchar(50) NOT NULL,
  `field_size` int(3) NOT NULL DEFAULT '0',
  `field_size_min` int(3) NOT NULL DEFAULT '0',
  `required` int(1) NOT NULL DEFAULT '0',
  `match` varchar(255) NOT NULL,
  `range` varchar(5000) NOT NULL,
  `error_message` varchar(255) NOT NULL,
  `other_validator` varchar(255) NOT NULL,
  `default` varchar(255) NOT NULL,
  `widget` varchar(255) NOT NULL DEFAULT '',
  `widgetparams` varchar(5000) NOT NULL DEFAULT '',
  `position` int(3) NOT NULL DEFAULT '0',
  `visible` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `varname` (`varname`,`visible`,`widget`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE `tbl_products_relation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL DEFAULT '0',
  `pr_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `pr_id` (`pr_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE `tbl_products_values` (
  `pr_id` int(11) NOT NULL,
  `artno` text NOT NULL,
  `size` int(10) NOT NULL DEFAULT '0',
  `weight` int(10) NOT NULL DEFAULT '0',
  `quantity` int(10) NOT NULL DEFAULT '0',
  `type` varchar(255) NOT NULL DEFAULT '',
  `shape` varchar(255) NOT NULL DEFAULT '',
  `surface` varchar(255) NOT NULL DEFAULT '',
  `quality` varchar(255) NOT NULL DEFAULT '',
  `color` varchar(255) NOT NULL DEFAULT '',
  `length` varchar(255) NOT NULL DEFAULT '',
  `clean` varchar(255) NOT NULL DEFAULT '',
  `ennobling` varchar(255) NOT NULL DEFAULT '',
  `metal` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`pr_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `tbl_products_fields_by_catalog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fid` int(11) NOT NULL DEFAULT '0',
  `cid` int(11) NOT NULL DEFAULT '0',
  `range` varchar(5000) NOT NULL,
  `filter` int(1) NOT NULL DEFAULT '0',
  `required` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `cid` (`cid`),
  KEY `fid` (`fid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
