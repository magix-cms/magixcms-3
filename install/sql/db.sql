CREATE TABLE IF NOT EXISTS `mc_admin_access` (
  `id_access` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `id_role` smallint(5) unsigned NOT NULL,
  `id_module` int(7) unsigned NOT NULL,
  `view` smallint(2) unsigned NOT NULL DEFAULT '0',
  `append` smallint(2) unsigned NOT NULL DEFAULT '0',
  `edit` smallint(2) unsigned NOT NULL DEFAULT '0',
  `del` smallint(2) unsigned NOT NULL DEFAULT '0',
  `action` smallint(2) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_access`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mc_admin_access_rel` (
  `id_access_rel` int(7) unsigned NOT NULL AUTO_INCREMENT,
  `id_admin` smallint(5) unsigned NOT NULL,
  `id_role` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id_access_rel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mc_admin_employee` (
  `id_admin` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `keyuniqid_admin` varchar(50) NOT NULL,
  `title_admin` enum('m','w') NOT NULL DEFAULT 'm',
  `lastname_admin` varchar(50) DEFAULT NULL,
  `firstname_admin` varchar(50) DEFAULT NULL,
  `pseudo_admin` varchar(50) DEFAULT NULL,
  `email_admin` varchar(150) NOT NULL,
  `phone_admin` varchar(150) DEFAULT NULL,
  `address_admin` varchar(200) DEFAULT NULL,
  `postcode_admin` varchar(8) DEFAULT NULL,
  `city_admin` varchar(100) DEFAULT NULL,
  `country_admin` varchar(120) DEFAULT NULL,
  `passwd_admin` varchar(80) NOT NULL,
  `last_change_admin` timestamp NULL DEFAULT NULL,
  `change_passwd` varchar(32) DEFAULT NULL,
  `active_admin` smallint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_admin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mc_admin_role_user` (
  `id_role` smallint(3) unsigned NOT NULL AUTO_INCREMENT,
  `role_name` varchar(50) NOT NULL,
  PRIMARY KEY (`id_role`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `mc_admin_role_user` (`id_role`, `role_name`) VALUES
(1, 'administrator');

CREATE TABLE IF NOT EXISTS `mc_admin_session` (
  `id_admin_session` varchar(150) NOT NULL,
  `id_admin` smallint(5) unsigned NOT NULL,
  `keyuniqid_admin` varchar(50) NOT NULL,
  `ip_session` varchar(25) NOT NULL,
  `browser_admin` varchar(50) NOT NULL,
  `last_modified_session` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_admin_session`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `mc_config` (
  `idconfig` smallint(3) unsigned NOT NULL AUTO_INCREMENT,
  `attr_name` varchar(20) NOT NULL,
  `status` smallint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`idconfig`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `mc_config` (`idconfig`, `attr_name`, `status`) VALUES
(NULL, 'pages', 1),
(NULL, 'news', 1),
(NULL, 'catalog', 1),
(NULL, 'about', 1);

CREATE TABLE IF NOT EXISTS `mc_country` (
  `id_country` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `iso_country` varchar(5) NOT NULL,
  `name_country` varchar(125) NOT NULL,
  `order_country` int(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_country`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mc_css_inliner_color` (
  `id_cssi` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `property_cssi` varchar(125) NOT NULL,
  `color_cssi` varchar(50) NOT NULL,
  PRIMARY KEY (`id_cssi`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `mc_css_inliner_color` (`id_cssi`, `property_cssi`, `color_cssi`) VALUES
(1, 'header_bg', '#f2f2f2'),
(2, 'header_c', '#ffffff'),
(3, 'footer_bg', '#333333'),
(4, 'footer_c', '#ffffff');

CREATE TABLE IF NOT EXISTS `mc_lang` (
  `id_lang` smallint(3) unsigned NOT NULL AUTO_INCREMENT,
  `iso_lang` varchar(10) NOT NULL,
  `name_lang` varchar(40) DEFAULT NULL,
  `default_lang` smallint(1) unsigned NOT NULL DEFAULT '0',
  `active_lang` smallint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_lang`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `mc_lang` (`id_lang`, `iso_lang`, `name_lang`, `default_lang`, `active_lang`) VALUES
(1, 'fr', 'francais', 1, 1);

CREATE TABLE IF NOT EXISTS `mc_module` (
  `id_module` int(7) unsigned NOT NULL AUTO_INCREMENT,
  `class_name` varchar(50) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_module`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `mc_module` (`id_module`, `class_name`, `name`) VALUES
(NULL, 'backend_controller_dashboard', 'dashboard'),
(NULL, 'backend_controller_employee', 'employee'),
(NULL, 'backend_controller_access', 'access'),
(NULL, 'backend_controller_language', 'language'),
(NULL, 'backend_controller_country', 'country'),
(NULL, 'backend_controller_domain', 'domain'),
(NULL, 'backend_controller_setting', 'setting'),
(NULL, 'backend_controller_home', 'home'),
(NULL, 'backend_controller_pages', 'pages'),
(NULL, 'backend_controller_files', 'files'),
(NULL, 'backend_controller_about', 'about'),
(NULL, 'backend_controller_news', 'news'),
(NULL, 'backend_controller_webservice', 'webservice'),
(NULL, 'backend_controller_category', 'category'),
(NULL, 'backend_controller_catalog', 'catalog'),
(NULL, 'backend_controller_product', 'product'),
(NULL, 'backend_controller_seo', 'seo'),
(NULL, 'backend_controller_theme', 'theme');

CREATE TABLE IF NOT EXISTS `mc_setting` (
  `id_setting` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `value` text,
  `type` varchar(8) NOT NULL DEFAULT 'string',
  `label` text,
  `category` varchar(20) NOT NULL,
  PRIMARY KEY (`id_setting`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `mc_setting` (`id_setting`, `name`, `value`, `type`, `label`, `category`) VALUES
(NULL, 'theme', 'default', 'string', 'site theme', 'theme'),
(NULL, 'analytics', NULL, 'string', 'google analytics', 'google'),
(NULL, 'magix_version', '3.0.0', 'string', 'Version Magix CMS', 'release'),
(NULL, 'content_css', NULL, 'string', 'css from skin for tinyMCE', 'general'),
(NULL, 'concat', '0', 'int', 'concat URL', 'general'),
(NULL, 'cache', 'none', 'string', 'Cache template', 'general'),
(NULL, 'robots', 'noindex,nofollow', 'string', 'metas robots', 'general'),
(NULL, 'css_inliner', '1', 'string', 'CSS inliner', 'general'),
(NULL, 'mode', 'dev', 'string', 'Environment types', 'general'),
(NULL, 'ssl', '0', 'int', 'SSL protocol', 'general');

CREATE TABLE IF NOT EXISTS `mc_plugins` (
  `id_plugins` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `version` varchar(10) NOT NULL,
  `home` smallint(5) unsigned NOT NULL DEFAULT '0',
  `pages` smallint(5) unsigned NOT NULL DEFAULT '0',
  `news` smallint(5) unsigned NOT NULL DEFAULT '0',
  `category` smallint(5) unsigned NOT NULL DEFAULT '0',
  `product` smallint(5) unsigned NOT NULL DEFAULT '0',
  `seo` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_plugins`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mc_domain` (
  `id_domain` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `url_domain` varchar(175) NOT NULL,
  `default_domain` smallint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_domain`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mc_config_img` (
  `id_config_img` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `module_img` enum('catalog','news','pages','plugins') NOT NULL,
  `attribute_img` varchar(40) NOT NULL,
  `width_img` decimal(4,0) NOT NULL,
  `height_img` decimal(4,0) NOT NULL,
  `type_img` enum('small','medium','large') NOT NULL,
  `resize_img` enum('basic','adaptive') NOT NULL,
  PRIMARY KEY (`id_config_img`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mc_home_page` (
  `id_page` smallint(3) unsigned NOT NULL AUTO_INCREMENT,
  `date_register` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_page`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mc_home_page_content` (
  `id_content` smallint(3) unsigned NOT NULL AUTO_INCREMENT,
  `id_page` smallint(3) unsigned NOT NULL,
  `id_lang` smallint(3) unsigned NOT NULL,
  `title_page` varchar(150) NOT NULL,
  `content_page` text,
  `seo_title_page` varchar(180) DEFAULT NULL,
  `seo_desc_page` varchar(180) DEFAULT NULL,
  `published` smallint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_content`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mc_cms_page` (
  `id_pages` int(7) unsigned NOT NULL AUTO_INCREMENT,
  `id_parent` int(7) unsigned DEFAULT NULL,
  `img_pages` varchar(125) DEFAULT NULL,
  `menu_pages` smallint(1) unsigned DEFAULT '0',
  `order_pages` smallint(5) unsigned NOT NULL DEFAULT '0',
  `date_register` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pages`),
  KEY `id_parent` (`id_parent`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE `mc_cms_page`
  ADD CONSTRAINT `mc_cms_page_ibfk_1` FOREIGN KEY (`id_parent`) REFERENCES `mc_cms_page` (`id_pages`) ON DELETE CASCADE ON UPDATE CASCADE;

CREATE TABLE IF NOT EXISTS `mc_cms_page_content` (
  `id_content` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_pages` int(7) unsigned NOT NULL,
  `id_lang` smallint(3) unsigned NOT NULL DEFAULT '1',
  `name_pages` varchar(150) DEFAULT NULL,
  `url_pages` varchar(150) DEFAULT NULL,
  `resume_pages` text,
  `content_pages` text,
  `seo_title_pages` varchar(180) DEFAULT NULL,
  `seo_desc_pages` varchar(180) DEFAULT NULL,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `published_pages` smallint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_content`),
  KEY `id_pages` (`id_pages`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE `mc_cms_page_content`
  ADD CONSTRAINT `mc_cms_page_content_ibfk_1` FOREIGN KEY (`id_pages`) REFERENCES `mc_cms_page` (`id_pages`) ON DELETE CASCADE ON UPDATE CASCADE;

CREATE TABLE IF NOT EXISTS `mc_about` (
  `id_info` smallint(2) unsigned NOT NULL AUTO_INCREMENT,
  `name_info` varchar(30) NOT NULL,
  `value_info` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_info`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `mc_about` (`id_info`, `name_info`, `value_info`) VALUES
(NULL, 'name', NULL),
(NULL, 'type', 'org'),
(NULL, 'eshop', '0'),
(NULL, 'tva', NULL),
(NULL, 'adress', NULL),
(NULL, 'street', NULL),
(NULL, 'postcode', NULL),
(NULL, 'city', NULL),
(NULL, 'mail', NULL),
(NULL, 'click_to_mail', '0'),
(NULL, 'crypt_mail', '1'),
(NULL, 'phone', NULL),
(NULL, 'mobile', NULL),
(NULL, 'click_to_call', '1'),
(NULL, 'fax', NULL),
(NULL, 'languages', 'French'),
(NULL, 'facebook', NULL),
(NULL, 'twitter', NULL),
(NULL, 'google', NULL),
(NULL, 'linkedin', NULL),
(NULL, 'viadeo', NULL),
(NULL, 'openinghours', '0');

CREATE TABLE IF NOT EXISTS `mc_about_op` (
  `id_day` smallint(2) unsigned NOT NULL AUTO_INCREMENT,
  `day_abbr` varchar(2) NOT NULL,
  `open_day` smallint(1) unsigned DEFAULT '0',
  `noon_time` smallint(1) unsigned DEFAULT '0',
  `open_time` varchar(5) DEFAULT NULL,
  `close_time` varchar(5) DEFAULT NULL,
  `noon_start` varchar(5) DEFAULT NULL,
  `noon_end` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`id_day`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `mc_about_op` (`id_day`, `day_abbr`, `open_day`, `noon_time`, `open_time`, `close_time`, `noon_start`, `noon_end`) VALUES
(1, 'Mo', 1, 1, NULL, NULL, NULL, NULL),
(2, 'Tu', 0, 0, NULL, NULL, NULL, NULL),
(3, 'We', 0, 0, NULL, NULL, NULL, NULL),
(4, 'Th', 0, 0, NULL, NULL, NULL, NULL),
(5, 'Fr', 0, 0, NULL, NULL, NULL, NULL),
(6, 'Sa', 0, 0, NULL, NULL, NULL, NULL),
(7, 'Su', 0, 0, NULL, NULL, NULL, NULL);

CREATE TABLE IF NOT EXISTS `mc_about_page` (
  `id_pages` int(7) unsigned NOT NULL AUTO_INCREMENT,
  `id_parent` int(7) unsigned DEFAULT NULL,
  `img_pages` varchar(125) DEFAULT NULL,
  `menu_pages` smallint(1) unsigned DEFAULT '0',
  `order_pages` smallint(5) unsigned NOT NULL DEFAULT '0',
  `date_register` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pages`),
  KEY `id_parent` (`id_parent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mc_about_page_content` (
  `id_content` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_pages` int(7) unsigned NOT NULL,
  `id_lang` smallint(3) unsigned NOT NULL DEFAULT '1',
  `name_pages` varchar(150) DEFAULT NULL,
  `url_pages` varchar(150) DEFAULT NULL,
  `resume_pages` text,
  `content_pages` text,
  `seo_title_pages` varchar(180) DEFAULT NULL,
  `seo_desc_pages` varchar(180) DEFAULT NULL,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `published_pages` smallint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_content`),
  KEY `id_pages` (`id_pages`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE `mc_about_page`
  ADD CONSTRAINT `mc_about_page_ibfk_1` FOREIGN KEY (`id_parent`) REFERENCES `mc_about_page` (`id_pages`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `mc_about_page_content`
  ADD CONSTRAINT `mc_about_page_content_ibfk_1` FOREIGN KEY (`id_pages`) REFERENCES `mc_about_page` (`id_pages`) ON DELETE CASCADE ON UPDATE CASCADE;

CREATE TABLE IF NOT EXISTS `mc_news` (
  `id_news` int(7) unsigned NOT NULL AUTO_INCREMENT,
  `img_news` varchar(125) DEFAULT NULL,
  `date_register` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_news`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mc_news_content` (
  `id_content` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_news` int(7) unsigned NOT NULL,
  `id_lang` smallint(3) unsigned NOT NULL,
  `name_news` varchar(150) DEFAULT NULL,
  `url_news` varchar(150) DEFAULT NULL,
  `resume_news` text,
  `content_news` text,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_publish` timestamp NULL DEFAULT NULL,
  `published_news` smallint(1) unsigned DEFAULT '0',
  PRIMARY KEY (`id_content`),
  KEY `id_news` (`id_news`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE `mc_news_content`
  ADD CONSTRAINT `mc_news_content_ibfk_1` FOREIGN KEY (`id_news`) REFERENCES `mc_news` (`id_news`) ON DELETE CASCADE ON UPDATE CASCADE;

CREATE TABLE IF NOT EXISTS `mc_news_tag` (
  `id_tag` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `id_lang` smallint(3) unsigned NOT NULL,
  `name_tag` varchar(50) NOT NULL,
  PRIMARY KEY (`id_tag`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mc_news_tag_rel` (
  `id_rel` int(7) unsigned NOT NULL AUTO_INCREMENT,
  `id_news` int(7) unsigned NOT NULL,
  `id_tag` int(5) unsigned NOT NULL,
  PRIMARY KEY (`id_rel`),
  KEY `id_tag` (`id_tag`),
  KEY `id_news` (`id_news`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE `mc_news_tag_rel`
  ADD CONSTRAINT `mc_news_tag_rel_ibfk_2` FOREIGN KEY (`id_news`) REFERENCES `mc_news` (`id_news`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mc_news_tag_rel_ibfk_1` FOREIGN KEY (`id_tag`) REFERENCES `mc_news_tag` (`id_tag`) ON DELETE CASCADE ON UPDATE CASCADE;

CREATE TABLE IF NOT EXISTS `mc_catalog_cat` (
  `id_cat` int(7) unsigned NOT NULL AUTO_INCREMENT,
  `id_parent` int(7) unsigned DEFAULT NULL,
  `img_cat` varchar(150) DEFAULT NULL,
  `order_cat` smallint(5) unsigned NOT NULL DEFAULT '0',
  `date_register` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_cat`),
  KEY `id_parent` (`id_parent`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mc_catalog_cat_content` (
  `id_content` int(7) unsigned NOT NULL AUTO_INCREMENT,
  `id_cat` int(7) unsigned NOT NULL,
  `id_lang` smallint(3) unsigned NOT NULL DEFAULT '1',
  `name_cat` varchar(150) DEFAULT NULL,
  `url_cat` varchar(150) DEFAULT NULL,
  `resume_cat` text,
  `content_cat` text,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `published_cat` smallint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_content`),
  KEY `id_cat` (`id_cat`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE `mc_catalog_cat`
  ADD CONSTRAINT `mc_catalog_cat_ibfk_1` FOREIGN KEY (`id_parent`) REFERENCES `mc_catalog_cat` (`id_cat`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `mc_catalog_cat_content`
  ADD CONSTRAINT `mc_catalog_cat_content_ibfk_1` FOREIGN KEY (`id_cat`) REFERENCES `mc_catalog_cat` (`id_cat`) ON DELETE CASCADE ON UPDATE CASCADE;

CREATE TABLE IF NOT EXISTS `mc_catalog_data` (
  `id_data` smallint(2) unsigned NOT NULL AUTO_INCREMENT,
  `id_lang` smallint(3) unsigned NOT NULL,
  `name_info` varchar(30) DEFAULT NULL,
  `value_info` text,
  PRIMARY KEY (`id_data`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mc_catalog_product` (
  `id_product` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `price_p` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `reference_p` varchar(32) DEFAULT NULL,
  `width_p` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `height_p` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `depth_p` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `weight_p` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `date_register` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_product`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mc_catalog_product_content` (
  `id_content` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_product` int(11) unsigned NOT NULL,
  `id_lang` smallint(3) unsigned NOT NULL DEFAULT '1',
  `name_p` varchar(125) DEFAULT NULL,
  `url_p` varchar(125) DEFAULT NULL,
  `resume_p` text,
  `content_p` text,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `published_p` smallint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_content`),
  KEY `id_product` (`id_product`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE `mc_catalog_product_content`
  ADD CONSTRAINT `mc_catalog_product_content_ibfk_1` FOREIGN KEY (`id_product`) REFERENCES `mc_catalog_product` (`id_product`) ON DELETE CASCADE ON UPDATE CASCADE;

CREATE TABLE IF NOT EXISTS `mc_catalog_product_img` (
  `id_img` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_product` int(11) unsigned NOT NULL,
  `name_img` varchar(150) NOT NULL,
  `alt_img` varchar(35) DEFAULT NULL,
  `title_img` varchar(35) DEFAULT NULL,
  `default_img` smallint(1) NOT NULL DEFAULT '0',
  `order_img` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_img`),
  KEY `id_product` (`id_product`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE `mc_catalog_product_img`
  ADD CONSTRAINT `mc_catalog_product_img_ibfk_1` FOREIGN KEY (`id_product`) REFERENCES `mc_catalog_product` (`id_product`) ON DELETE CASCADE ON UPDATE CASCADE;

CREATE TABLE IF NOT EXISTS `mc_catalog_product_img_content` (
  `id_content` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_img` int(11) unsigned NOT NULL,
  `id_lang` smallint(3) unsigned NOT NULL,
  `alt_img` varchar(35) DEFAULT NULL,
  `title_img` varchar(35) DEFAULT NULL,
  PRIMARY KEY (`id_content`),
  KEY `id_img` (`id_img`,`id_lang`),
  KEY `id_lang` (`id_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE `mc_catalog_product_img_content`
  ADD CONSTRAINT `mc_catalog_product_img_content_ibfk_2` FOREIGN KEY (`id_lang`) REFERENCES `mc_lang` (`id_lang`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mc_catalog_product_img_content_ibfk_1` FOREIGN KEY (`id_img`) REFERENCES `mc_catalog_product_img` (`id_img`) ON DELETE CASCADE ON UPDATE CASCADE;

CREATE TABLE IF NOT EXISTS `mc_catalog` (
  `id_catalog` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_product` int(11) unsigned NOT NULL,
  `id_cat` int(7) unsigned NOT NULL,
  `default_c` smallint(1) unsigned NOT NULL DEFAULT '0',
  `order_p` int(7) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_catalog`),
  KEY `id_product` (`id_product`,`id_cat`),
  KEY `id_cat` (`id_cat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE `mc_catalog`
  ADD CONSTRAINT `mc_catalog_ibfk_2` FOREIGN KEY (`id_cat`) REFERENCES `mc_catalog_cat` (`id_cat`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mc_catalog_ibfk_1` FOREIGN KEY (`id_product`) REFERENCES `mc_catalog_product` (`id_product`) ON DELETE CASCADE ON UPDATE CASCADE;

CREATE TABLE IF NOT EXISTS `mc_webservice` (
  `id_ws` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `key_ws` varchar(125) DEFAULT NULL,
  `status_ws` smallint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_ws`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mc_seo` (
  `id_seo` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `level_seo` varchar(30) NOT NULL,
  `attribute_seo` varchar(50) NOT NULL,
  `type_seo` enum('title','description') NOT NULL,
  PRIMARY KEY (`id_seo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mc_seo_content` (
  `id_content` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `id_seo` smallint(5) unsigned NOT NULL,
  `id_lang` smallint(3) unsigned NOT NULL,
  `content_seo` text NOT NULL,
  PRIMARY KEY (`id_content`),
  KEY `id_seo` (`id_seo`),
  KEY `id_lang` (`id_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE `mc_seo_content`
  ADD CONSTRAINT `mc_seo_content_ibfk_1` FOREIGN KEY (`id_seo`) REFERENCES `mc_seo` (`id_seo`) ON DELETE CASCADE ON UPDATE CASCADE;

CREATE TABLE IF NOT EXISTS `mc_catalog_product_rel` (
  `id_rel` int(11) NOT NULL AUTO_INCREMENT,
  `id_product` int(11) unsigned NOT NULL,
  `id_product_2` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id_rel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;