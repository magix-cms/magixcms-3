CREATE TABLE IF NOT EXISTS `mc_admin_role_user` (
  `id_role` smallint(3) unsigned NOT NULL AUTO_INCREMENT,
  `role_name` varchar(50) NOT NULL,
  PRIMARY KEY (`id_role`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `mc_admin_role_user` (`id_role`, `role_name`) VALUES
(1, 'administrator');

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
(NULL, 'backend_controller_theme', 'theme'),
(NULL, 'backend_controller_plugins', 'plugins'),
(NULL, 'backend_controller_translate', 'translate'),
(NULL, 'backend_controller_logo', 'logo'),
(NULL, 'backend_controller_snippet', 'snippet');

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

INSERT INTO `mc_admin_access` (`id_access`, `id_role`, `id_module`, `view`, `append`, `edit`, `del`, `action`) VALUES
(NULL, 1, 1, 1, 1, 1, 1, 1),
(NULL, 1, 2, 1, 1, 1, 1, 1),
(NULL, 1, 3, 1, 1, 1, 1, 1),
(NULL, 1, 4, 1, 1, 1, 1, 1),
(NULL, 1, 5, 1, 1, 1, 1, 1),
(NULL, 1, 6, 1, 1, 1, 1, 1),
(NULL, 1, 7, 1, 1, 1, 1, 1),
(NULL, 1, 8, 1, 1, 1, 1, 1),
(NULL, 1, 9, 1, 1, 1, 1, 1),
(NULL, 1, 10, 1, 1, 1, 1, 1),
(NULL, 1, 11, 1, 1, 1, 1, 1),
(NULL, 1, 12, 1, 1, 1, 1, 1),
(NULL, 1, 13, 1, 1, 1, 1, 1),
(NULL, 1, 14, 1, 1, 1, 1, 1),
(NULL, 1, 15, 1, 1, 1, 1, 1),
(NULL, 1, 16, 1, 1, 1, 1, 1),
(NULL, 1, 17, 1, 1, 1, 1, 1),
(NULL, 1, 18, 1, 1, 1, 1, 1),
(NULL, 1, 19, 1, 1, 1, 1, 1),
(NULL, 1, 20, 1, 1, 1, 1, 1),
(NULL, 1, 21, 1, 1, 1, 1, 1),
(NULL, 1, 22, 1, 1, 1, 1, 1);

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

CREATE TABLE IF NOT EXISTS `mc_admin_access_rel` (
  `id_access_rel` int(7) unsigned NOT NULL AUTO_INCREMENT,
  `id_admin` smallint(5) unsigned NOT NULL,
  `id_role` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id_access_rel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mc_admin_session` (
  `id_admin_session` varchar(150) NOT NULL,
  `id_admin` smallint(5) UNSIGNED NOT NULL,
  `keyuniqid_admin` varchar(50) NOT NULL,
  `ip_session` varchar(50) NOT NULL,
  `browser_admin` varchar(50) NOT NULL,
  `last_modified_session` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expires` timestamp NULL DEFAULT NULL,
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

CREATE TABLE IF NOT EXISTS `mc_logo` (
  `id_logo` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `img_logo` varchar(125) DEFAULT NULL,
  `active_logo` smallint(1) NOT NULL DEFAULT '0',
  `date_register` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_logo`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `mc_logo_content` (
  `id_content` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_logo` smallint(5) UNSIGNED NOT NULL,
  `id_lang` smallint(3) UNSIGNED NOT NULL DEFAULT '1',
  `alt_logo` varchar(70) DEFAULT NULL,
  `title_logo` varchar(70) DEFAULT NULL,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_content`),
  KEY `id_logo` (`id_logo`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

ALTER TABLE `mc_logo_content`
  ADD CONSTRAINT `mc_logo_content_ibfk_1` FOREIGN KEY (`id_logo`) REFERENCES `mc_logo` (`id_logo`) ON DELETE CASCADE ON UPDATE CASCADE;

CREATE TABLE IF NOT EXISTS `mc_country` (
  `id_country` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `iso_country` varchar(5) NOT NULL,
  `name_country` varchar(125) NOT NULL,
  `order_country` int(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_country`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mc_css_inliner` (
  `id_cssi` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `property_cssi` varchar(125) NOT NULL,
  `color_cssi` varchar(50) NOT NULL,
  PRIMARY KEY (`id_cssi`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `mc_css_inliner` (`id_cssi`, `property_cssi`, `color_cssi`) VALUES
(NULL, 'header_bg', '#f2f2f2'),
(NULL, 'header_c', '#ffffff'),
(NULL, 'footer_bg', '#333333'),
(NULL, 'footer_c', '#ffffff');

CREATE TABLE IF NOT EXISTS `mc_lang` (
  `id_lang` smallint(3) unsigned NOT NULL AUTO_INCREMENT,
  `iso_lang` varchar(10) NOT NULL,
  `name_lang` varchar(40) DEFAULT NULL,
  `default_lang` smallint(1) unsigned NOT NULL DEFAULT '0',
  `active_lang` smallint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_lang`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `mc_lang` (`id_lang`, `iso_lang`, `name_lang`, `default_lang`, `active_lang`) VALUES
(NULL, 'fr', 'French', 1, 1);

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
(NULL, 'magix_version', '3.7.8', 'string', 'Version Magix CMS', 'release'),
(NULL, 'vat_rate', '21', 'float', 'VAT Rate', 'catalog'),
(NULL, 'price_display', 'tinc', 'string', 'Price display with or without tax included', 'catalog'),
(NULL, 'product_per_page', 12, 'int', 'Number of product per page in the pages of the catalog', 'catalog'),
(NULL, 'news_per_page', 12, 'int', 'Number of news per page in the news pages', 'news'),
(NULL, 'mail_sender', NULL, 'string', 'Mail sender', 'mail'),
(NULL, 'smtp_enabled', '0', 'int', 'Smtp enabled', 'mail'),
(NULL, 'set_host', NULL, 'string', 'Set host', 'mail'),
(NULL, 'set_port', NULL, 'string', 'Set port', 'mail'),
(NULL, 'set_encryption', NULL, 'string', 'Set encryption', 'mail'),
(NULL, 'set_username', NULL, 'string', 'Set username', 'mail'),
(NULL, 'set_password', NULL, 'string', 'Set password', 'mail'),
(NULL, 'content_css', NULL, 'string', 'css from skin for tinyMCE', 'advanced'),
(NULL, 'concat', '0', 'int', 'concat URL', 'advanced'),
(NULL, 'cache', 'none', 'string', 'Cache template', 'advanced'),
(NULL, 'robots', 'noindex,nofollow', 'string', 'metas robots', 'advanced'),
(NULL, 'css_inliner', '0', 'string', 'CSS inliner', 'advanced'),
(NULL, 'mode', 'dev', 'string', 'Environment types', 'advanced'),
(NULL, 'ssl', '0', 'int', 'SSL protocol', 'advanced'),
(NULL, 'http2', '0', 'int', 'HTTP2 protocol', 'advanced'),
(NULL, 'service_worker', '0', 'int', 'Service Worker', 'advanced'),
(NULL, 'amp', '0', 'int', 'amp', 'advanced'),
(NULL, 'maintenance', '0', 'int', 'Mode maintenance', 'advanced'),
(NULL, 'holder_bg_color', '#ffffff', 'string', 'color bg replacement image', 'advanced'),
(NULL, 'logo_percent', '50', 'int', 'Logo size percentage', 'advanced');

CREATE TABLE IF NOT EXISTS `mc_plugins` (
  `id_plugins` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `version` varchar(10) NOT NULL,
  `home` smallint(3) unsigned NOT NULL DEFAULT '0',
  `about` smallint(3) unsigned NOT NULL DEFAULT '0',
  `pages` smallint(3) unsigned NOT NULL DEFAULT '0',
  `news` smallint(3) unsigned NOT NULL DEFAULT '0',
  `catalog` smallint(3) unsigned NOT NULL DEFAULT '0',
  `category` smallint(3) unsigned NOT NULL DEFAULT '0',
  `product` smallint(3) unsigned NOT NULL DEFAULT '0',
  `seo` smallint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_plugins`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mc_plugins_module` (
  `id_module` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `plugin_name` varchar(200) NOT NULL,
  `module_name` varchar(200) NOT NULL,
  `active` smallint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_module`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `mc_domain` (
  `id_domain` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `url_domain` varchar(175) NOT NULL,
  `tracking_domain` text,
  `default_domain` smallint(1) UNSIGNED NOT NULL DEFAULT '0',
  `canonical_domain` smallint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_domain`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `mc_domain_language` (
  `id_domain_lg` int(5) NOT NULL AUTO_INCREMENT,
  `id_domain` smallint(5) unsigned NOT NULL,
  `id_lang` smallint(3) unsigned NOT NULL,
  `default_lang` smallint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_domain_lg`),
  KEY `id_lang` (`id_lang`),
  KEY `id_domain` (`id_domain`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE `mc_domain_language`
  ADD CONSTRAINT `mc_domain_language_ibfk_2` FOREIGN KEY (`id_lang`) REFERENCES `mc_lang` (`id_lang`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mc_domain_language_ibfk_1` FOREIGN KEY (`id_domain`) REFERENCES `mc_domain` (`id_domain`) ON DELETE CASCADE ON UPDATE CASCADE;

CREATE TABLE IF NOT EXISTS `mc_config_img` (
  `id_config_img` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `module_img` varchar(40) NOT NULL,
  `attribute_img` varchar(40) NOT NULL,
  `width_img` decimal(4,0) NOT NULL,
  `height_img` decimal(4,0) NOT NULL,
  `type_img` varchar(80) NOT NULL,
  `prefix_img` varchar(50) NOT NULL,
  `resize_img` enum('basic','adaptive') NOT NULL,
  PRIMARY KEY (`id_config_img`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `mc_config_img` (`id_config_img`, `module_img`, `attribute_img`, `width_img`, `height_img`, `type_img`, `prefix_img`, `resize_img`) VALUES
(NULL, 'pages', 'pages', '340', '210', 'small', 's', 'adaptive'),
(NULL, 'pages', 'pages', '680', '420', 'medium', 'm', 'adaptive'),
(NULL, 'pages', 'pages', '1200', '1200', 'large', 'l', 'basic'),
(NULL, 'news', 'news', '340', '210', 'small', 's', 'adaptive'),
(NULL, 'news', 'news', '680', '420', 'medium', 'm', 'adaptive'),
(NULL, 'news', 'news', '1200', '1200', 'large', 'l', 'basic'),
(NULL, 'catalog', 'category', '340', '210', 'small', 's', 'adaptive'),
(NULL, 'catalog', 'category', '680', '420', 'medium', 'm', 'adaptive'),
(NULL, 'catalog', 'category', '1200', '1200', 'large', 'l', 'basic'),
(NULL, 'catalog', 'product', '340', '210', 'small', 's', 'adaptive'),
(NULL, 'catalog', 'product', '680', '420', 'medium', 'm', 'adaptive'),
(NULL, 'catalog', 'product', '1200', '1200', 'large', 'l', 'basic'),
(NULL, 'logo', 'logo', '229', '50', 'small', 's', 'adaptive'),
(NULL, 'logo', 'logo', '480', '105', 'medium', 'm', 'adaptive'),
(NULL, 'logo', 'logo', '500', '121', 'large', 'l', 'adaptive');

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
  `seo_desc_page` text,
  `published` smallint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_content`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mc_cms_page` (
  `id_pages` int(7) unsigned NOT NULL AUTO_INCREMENT,
  `id_parent` int(7) unsigned DEFAULT NULL,
  `menu_pages` smallint(1) unsigned DEFAULT '1',
  `order_pages` smallint(5) unsigned NOT NULL DEFAULT '0',
  `date_register` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pages`),
  KEY `id_parent` (`id_parent`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE `mc_cms_page`
  ADD CONSTRAINT `mc_cms_page_ibfk_1` FOREIGN KEY (`id_parent`) REFERENCES `mc_cms_page` (`id_pages`) ON DELETE SET NULL ON UPDATE CASCADE;

CREATE TABLE IF NOT EXISTS `mc_cms_page_content` (
  `id_content` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_pages` int(7) unsigned NOT NULL,
  `id_lang` smallint(3) unsigned NOT NULL DEFAULT '1',
  `name_pages` varchar(150) DEFAULT NULL,
  `url_pages` varchar(150) DEFAULT NULL,
  `resume_pages` text,
  `content_pages` text,
  `link_label_pages` varchar(125) DEFAULT NULL,
  `link_title_pages` varchar(125) DEFAULT NULL,
  `seo_title_pages` varchar(180) DEFAULT NULL,
  `seo_desc_pages` text,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `published_pages` smallint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_content`),
  KEY `id_pages` (`id_pages`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE `mc_cms_page_content`
  ADD CONSTRAINT `mc_cms_page_content_ibfk_1` FOREIGN KEY (`id_pages`) REFERENCES `mc_cms_page` (`id_pages`) ON DELETE CASCADE ON UPDATE CASCADE;

CREATE TABLE IF NOT EXISTS `mc_cms_page_img` (
 `id_img` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
 `id_pages` int(11) UNSIGNED NOT NULL,
 `name_img` varchar(150) NOT NULL,
 `default_img` smallint(1) NOT NULL DEFAULT 0,
 `order_img` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
 PRIMARY KEY (`id_img`),
 KEY `id_pages` (`id_pages`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `mc_cms_page_img`
    ADD CONSTRAINT `mc_cms_page_img_ibfk_1` FOREIGN KEY (`id_pages`) REFERENCES `mc_cms_page` (`id_pages`) ON DELETE CASCADE ON UPDATE CASCADE;

CREATE TABLE IF NOT EXISTS `mc_cms_page_img_content` (
 `id_content` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
 `id_img` int(11) UNSIGNED NOT NULL,
 `id_lang` smallint(3) UNSIGNED NOT NULL,
 `alt_img` varchar(70) DEFAULT NULL,
 `title_img` varchar(70) DEFAULT NULL,
 `caption_img` varchar(125) DEFAULT NULL,
 PRIMARY KEY (`id_content`),
 KEY `id_img` (`id_img`,`id_lang`),
 KEY `id_lang` (`id_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `mc_cms_page_img_content`
    ADD CONSTRAINT `mc_cms_page_img_content_ibfk_1` FOREIGN KEY (`id_img`) REFERENCES `mc_cms_page_img` (`id_img`) ON DELETE CASCADE ON UPDATE CASCADE,
    ADD CONSTRAINT `mc_cms_page_img_content_ibfk_2` FOREIGN KEY (`id_lang`) REFERENCES `mc_lang` (`id_lang`) ON DELETE CASCADE ON UPDATE CASCADE;

-- mc_cms_page_content
CREATE INDEX idx_cms_page_content_lang
    ON mc_cms_page_content(id_pages, id_lang);

-- mc_cms_page_img
CREATE INDEX idx_cms_page_img_default
    ON mc_cms_page_img(id_pages, default_img);

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
(NULL, 'tumblr', NULL),
(NULL, 'tiktok', NULL),
(NULL, 'youtube', NULL),
(NULL, 'linkedin', NULL),
(NULL, 'viadeo', NULL),
(NULL, 'pinterest', NULL),
(NULL, 'instagram', NULL),
(NULL, 'github', NULL),
(NULL, 'soundcloud', NULL),
(NULL, 'viadeo', NULL),
(NULL, 'dailymotion', NULL),
(NULL, 'openinghours', '0');

CREATE TABLE IF NOT EXISTS `mc_about_data` (
  `id_data` smallint(2) unsigned NOT NULL AUTO_INCREMENT,
  `id_lang` smallint(3) unsigned NOT NULL,
  `name_info` varchar(30) DEFAULT NULL,
  `value_info` text,
  PRIMARY KEY (`id_data`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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

CREATE TABLE IF NOT EXISTS `mc_about_op_content` (
  `id_content` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_lang` smallint(3) UNSIGNED NOT NULL,
  `text_mo` text,
  `text_tu` text,
  `text_we` text,
  `text_th` text,
  `text_fr` text,
  `text_sa` text,
  `text_su` text,
  PRIMARY KEY (`id_content`),
  KEY `id_lang` (`id_lang`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

ALTER TABLE `mc_about_op_content`
  ADD CONSTRAINT `mc_about_op_content_ibfk_1` FOREIGN KEY (`id_lang`) REFERENCES `mc_lang` (`id_lang`) ON DELETE CASCADE ON UPDATE CASCADE;

CREATE TABLE IF NOT EXISTS `mc_about_page` (
  `id_pages` int(7) unsigned NOT NULL AUTO_INCREMENT,
  `id_parent` int(7) unsigned DEFAULT NULL,
  `menu_pages` smallint(1) unsigned DEFAULT '1',
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
  `seo_desc_pages` text,
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
    `id_news` int(7) UNSIGNED NOT NULL AUTO_INCREMENT,
    `date_publish` timestamp NULL DEFAULT NULL,
    `date_event_start` timestamp NULL DEFAULT NULL,
    `date_event_end` timestamp NULL DEFAULT NULL,
    `date_register` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id_news`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `mc_news_content` (
    `id_content` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `id_news` int(7) UNSIGNED NOT NULL,
    `id_lang` smallint(3) UNSIGNED NOT NULL,
    `name_news` varchar(150) DEFAULT NULL,
    `longname_news` varchar(150) DEFAULT NULL,
    `url_news` varchar(150) DEFAULT NULL,
    `resume_news` text,
    `content_news` text,
    `link_label_news` varchar(125) DEFAULT NULL,
    `link_title_news` varchar(125) DEFAULT NULL,
    `seo_title_news` varchar(180) DEFAULT NULL,
    `seo_desc_news` text,
    `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `published_news` smallint(1) UNSIGNED DEFAULT '0',
    PRIMARY KEY (`id_content`),
    KEY `id_news` (`id_news`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

ALTER TABLE `mc_news_content`
  ADD CONSTRAINT `mc_news_content_ibfk_1` FOREIGN KEY (`id_news`) REFERENCES `mc_news` (`id_news`) ON DELETE CASCADE ON UPDATE CASCADE;

CREATE TABLE IF NOT EXISTS `mc_news_img` (
    `id_img` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `id_news` int(7) UNSIGNED NOT NULL,
    `name_img` varchar(150) NOT NULL,
    `default_img` smallint(1) UNSIGNED NOT NULL DEFAULT '0',
    `order_img` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
    PRIMARY KEY (`id_img`),
    KEY `id_news` (`id_news`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

ALTER TABLE `mc_news_img`
    ADD FOREIGN KEY (`id_news`) REFERENCES `mc_news`(`id_news`) ON DELETE CASCADE ON UPDATE CASCADE;

CREATE TABLE IF NOT EXISTS `mc_news_img_content` (
    `id_content` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `id_img` int(11) UNSIGNED NOT NULL,
    `id_lang` smallint(3) UNSIGNED NOT NULL,
    `alt_img` varchar(70) DEFAULT NULL,
    `title_img` varchar(70) DEFAULT NULL,
    `caption_img` varchar(125) DEFAULT NULL,
    PRIMARY KEY (`id_content`),
    KEY `id_img` (`id_img`,`id_lang`),
    KEY `id_lang` (`id_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `mc_news_img_content`
    ADD FOREIGN KEY (`id_img`) REFERENCES `mc_news_img`(`id_img`) ON DELETE CASCADE ON UPDATE CASCADE;

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
                                                                                                               
-- mc_news_content
CREATE INDEX idx_news_content_lang
    ON mc_news_content(id_news, id_lang);

-- mc_news
CREATE INDEX idx_news_dates
    ON mc_news(date_publish, date_event_start, date_event_end);

-- mc_news_img
CREATE INDEX idx_news_img_default
    ON mc_news_img(id_news, default_img);

-- mc_news_tag_rel
CREATE INDEX idx_news_tag_rel_lookup
    ON mc_news_tag_rel(id_news, id_tag);

CREATE TABLE IF NOT EXISTS `mc_catalog_cat` (
  `id_cat` int(7) unsigned NOT NULL AUTO_INCREMENT,
  `id_parent` int(7) unsigned DEFAULT NULL,
  `img_cat` varchar(150) DEFAULT NULL,
  `menu_cat` smallint(1) unsigned NOT NULL DEFAULT '1',
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
  `alt_img` varchar(70) DEFAULT NULL,
  `title_img` varchar(70) DEFAULT NULL,
  `caption_img` varchar(125) DEFAULT NULL,
  `link_label_cat` varchar(125) DEFAULT NULL,
  `link_title_cat` varchar(125) DEFAULT NULL,
  `seo_title_cat` varchar(180) DEFAULT NULL,
  `seo_desc_cat` text,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `published_cat` smallint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_content`),
  KEY `id_cat` (`id_cat`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE `mc_catalog_cat`
  ADD CONSTRAINT `mc_catalog_cat_ibfk_1` FOREIGN KEY (`id_parent`) REFERENCES `mc_catalog_cat` (`id_cat`) ON DELETE SET NULL ON UPDATE CASCADE;

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
  `price_p` decimal(20,2) NOT NULL DEFAULT '0.00',
  `price_promo_p` decimal(20,2) NOT NULL DEFAULT '0.00',
  `reference_p` varchar(32) DEFAULT NULL,
  `width_p` decimal(10,2) NOT NULL DEFAULT '0.00',
  `height_p` decimal(10,2) NOT NULL DEFAULT '0.00',
  `depth_p` decimal(10,2) NOT NULL DEFAULT '0.00',
  `weight_p` decimal(10,2) NOT NULL DEFAULT '0.00',
  `availability_p` varchar(30) DEFAULT 'InStock',
  `date_register` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_product`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mc_catalog_product_content` (
  `id_content` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_product` int(11) unsigned NOT NULL,
  `id_lang` smallint(3) unsigned NOT NULL DEFAULT '1',
  `name_p` varchar(125) DEFAULT NULL,
  `longname_p` varchar(125) DEFAULT NULL,
  `url_p` varchar(125) DEFAULT NULL,
  `resume_p` text,
  `content_p` text,
  `link_label_p` varchar(125) DEFAULT NULL,
  `link_title_p` varchar(125) DEFAULT NULL,
  `seo_title_p` varchar(180) DEFAULT NULL,
  `seo_desc_p` text,
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
  `alt_img` varchar(70) DEFAULT NULL,
  `title_img` varchar(70) DEFAULT NULL,
  `caption_img` varchar(125) DEFAULT NULL,
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

-- mc_catalog_cat_content : accès rapide par catégorie + langue
CREATE INDEX idx_cat_content_cat_lang
    ON mc_catalog_cat_content(id_cat, id_lang);

-- mc_catalog_product_content : accès rapide par produit + langue
CREATE INDEX idx_product_content_product_lang
    ON mc_catalog_product_content(id_product, id_lang);

-- mc_catalog_product_img : accès rapide par produit + image par défaut
CREATE INDEX idx_product_img_default
    ON mc_catalog_product_img(id_product, default_img);

-- mc_catalog_product_img_content : déjà un composite (id_img, id_lang),
-- on garde mais on ajoute aussi un index direct sur id_lang si besoin
CREATE INDEX idx_product_img_content_lang
    ON mc_catalog_product_img_content(id_lang);

-- mc_catalog : souvent utilisé pour trouver la catégorie par produit et inversement
CREATE INDEX idx_catalog_product_cat
    ON mc_catalog(id_product, id_cat);

-- mc_catalog : optimisation si on filtre sur la catégorie seule
CREATE INDEX idx_catalog_cat
    ON mc_catalog(id_cat, default_c);

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

CREATE TABLE IF NOT EXISTS `mc_menu` (
  `id_link` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type_link` enum('home','pages','about','about_page','catalog','category','news','plugin','external') NOT NULL,
  `id_page` int(10) unsigned DEFAULT NULL,
  `mode_link` enum('simple','dropdown','mega') NOT NULL DEFAULT 'simple',
  `order_link` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_link`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mc_menu_content` (
  `id_link_content` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_link` int(10) unsigned NOT NULL,
  `id_lang` smallint(3) unsigned NOT NULL,
  `name_link` varchar(50) DEFAULT NULL,
  `title_link` varchar(180) DEFAULT NULL,
  `url_link` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id_link_content`),
  KEY `id_link` (`id_link`),
  KEY `id_lang` (`id_lang`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE `mc_menu_content`
    ADD CONSTRAINT `mc_menu_content_ibfk_1` FOREIGN KEY (`id_link`) REFERENCES `mc_menu`(`id_link`) ON DELETE CASCADE ON UPDATE CASCADE,
    ADD CONSTRAINT `mc_menu_content_ibfk_2` FOREIGN KEY (`id_lang`) REFERENCES `mc_lang`(`id_lang`) ON DELETE CASCADE ON UPDATE CASCADE;


CREATE TABLE IF NOT EXISTS `mc_share_config` (
  `id_share` smallint(3) unsigned NOT NULL AUTO_INCREMENT,
  `facebook` smallint(1) unsigned NOT NULL DEFAULT '1',
  `twitter` smallint(1) unsigned NOT NULL DEFAULT '0',
  `viadeo` smallint(1) unsigned NOT NULL DEFAULT '1',
  `google` smallint(1) unsigned NOT NULL DEFAULT '0',
  `linkedin` smallint(1) unsigned NOT NULL DEFAULT '1',
  `pinterest` smallint(1) unsigned NOT NULL DEFAULT '1',
  `twitter_id` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_share`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `mc_share_config` (`id_share`, `facebook`, `twitter`, `viadeo`, `google`, `linkedin`, `pinterest`, `twitter_id`) VALUES
(1, 1, 1, 1, 0, 1, 1, NULL);

CREATE TABLE IF NOT EXISTS `mc_share_url` (
  `id_share_url` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name_share` varchar(50) NOT NULL,
  `url_share` varchar(400) NOT NULL,
  `icon_share` varchar(50) NOT NULL,
  PRIMARY KEY (`id_share_url`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `mc_share_url` (`id_share_url`, `name_share`, `url_share`, `icon_share`) VALUES
(NULL, 'facebook', 'http://www.facebook.com/share.php?u=%URL%', 'facebook'),
(NULL, 'twitter', 'https://twitter.com/intent/tweet?text=%NAME%&amp;url=%URL%', 'twitter'),
(NULL, 'viadeo', 'http://www.viadeo.com/shareit/share/?url=%URL%&amp;title=%NAME%&amp;overview=%NAME%', 'viadeo'),
(NULL, 'google', 'https://plus.google.com/share?url=%URL%', 'google-plus'),
(NULL, 'linkedin', 'http://www.linkedin.com/shareArticle?mini=true&url=%URL%', 'linkedin'),
(NULL, 'pinterest', 'http://pinterest.com/pin/create/link/?url=%URL%', 'pinterest-p');

CREATE TABLE IF NOT EXISTS `mc_snippet` (
    `id_snippet` int(7) UNSIGNED NOT NULL AUTO_INCREMENT,
    `title_sp` varchar(30) DEFAULT NULL,
    `description_sp` varchar(30) DEFAULT NULL,
    `content_sp` text DEFAULT NULL,
    `order_sp` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
    `date_register` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id_snippet`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

INSERT INTO `mc_snippet` (`id_snippet`, `title_sp`, `description_sp`, `content_sp`, `order_sp`,  `date_register`) VALUES
(1, 'Texte 2 colonnes', NULL, '<div class=\"row\">\r\n<div class=\"col-12 col-xs-6\">\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Architecto aspernatur at atque commodi dolor dolores est eveniet laudantium libero magni, mollitia nemo nisi pariatur recusandae suscipit. Dolorem reprehenderit veniam voluptatem.</p>\r\n</div>\r\n<div class=\"col-12 col-xs-6\">\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Beatae dicta dolorum excepturi exercitationem fugit inventore itaque provident quae quidem! Cumque dignissimos mollitia placeat, quam quis repellat tempora ullam velit vero!</p>\r\n</div>\r\n</div>\r\n<p> </p>',1, '2023-04-05 14:54:39'),
(2, 'Texte 3 colonnes', NULL, '<div class=\"row\">\r\n<div class=\"col-12 col-sm-4\">\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Architecto aspernatur at atque commodi dolor dolores est eveniet laudantium libero magni, mollitia nemo nisi pariatur recusandae suscipit. Dolorem reprehenderit veniam voluptatem.</p>\r\n</div>\r\n<div class=\"col-12 col-sm-4\">\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Beatae dicta dolorum excepturi exercitationem fugit inventore itaque provident quae quidem! Cumque dignissimos mollitia placeat, quam quis repellat tempora ullam velit vero!</p>\r\n</div>\r\n<div class=\"col-12 col-sm-4\">\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Beatae dicta dolorum excepturi exercitationem fugit inventore itaque provident quae quidem! Cumque dignissimos mollitia placeat, quam quis repellat tempora ullam velit vero!</p>\r\n</div>\r\n</div>\r\n<p> </p>', 2, '2023-04-05 14:55:20'),
(3, 'Texte et image', NULL, '<div class=\"display\">\r\n<div class=\"container\">\r\n<div class=\"row row-reversed\">\r\n<div class=\"col-12 col-sm-6\"><img class=\"img-responsive\" src=\"http://via.placeholder.com/802x535\" alt=\"Placeholder\" width=\"802\" height=\"535\" /></div>\r\n<div class=\"col-12 col-sm-6\">\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Adipisci, amet cum deleniti deserunt doloremque inventore ipsa libero maiores, nam rem sequi soluta sunt? Ad commodi, deserunt doloribus illum reiciendis sapiente.</p>\r\n<p> </p>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n<p> </p>', 3, '2023-04-05 14:55:44'),
(4, 'Image et texte', NULL, '<div class=\"display\">\r\n<div class=\"container\">\r\n<div class=\"row\">\r\n<div class=\"col-12 col-sm-6\"><img class=\"img-responsive\" src=\"http://via.placeholder.com/802x535\" alt=\"Placeholder\" width=\"802\" height=\"535\" /></div>\r\n<div class=\"col-12 col-sm-6\">\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Adipisci, amet cum deleniti deserunt doloremque inventore ipsa libero maiores, nam rem sequi soluta sunt? Ad commodi, deserunt doloribus illum reiciendis sapiente.</p>\r\n<p> </p>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n<p> </p>', 4, '2023-04-05 14:56:00'),
(5, 'Texte et vidéo', NULL, '<div class=\"display\">\r\n<div class=\"container\">\r\n<div class=\"row row-reversed\">\r\n<div class=\"col-12 col-sm-6\">\r\n<div class=\"embed-responsive embed-responsive-16by9\"><iframe src=\"https://www.youtube.com/embed/kBgsZ-iTGHs?rel=0&amp;hd=1\" width=\"\" height=\"\" class=\"embed-responsive-item\"> </iframe></div>\r\n</div>\r\n<div class=\"col-12 col-sm-6\">\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Adipisci, amet cum deleniti deserunt doloremque inventore ipsa libero maiores, nam rem sequi soluta sunt? Ad commodi, deserunt doloribus illum reiciendis sapiente.</p>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n<p> </p>', 5, '2023-04-05 14:56:42'),
(6, 'Vidéo et texte', NULL, '<div class=\"display\">\r\n<div class=\"container\">\r\n<div class=\"row\">\r\n<div class=\"col-12 col-sm-6\">\r\n<div class=\"embed-responsive embed-responsive-16by9\"><iframe src=\"https://www.youtube.com/embed/kBgsZ-iTGHs?rel=0&amp;hd=1\" width=\"\" height=\"\" class=\"embed-responsive-item\"> </iframe></div>\r\n</div>\r\n<div class=\"col-12 col-sm-6\">\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Adipisci, amet cum deleniti deserunt doloremque inventore ipsa libero maiores, nam rem sequi soluta sunt? Ad commodi, deserunt doloribus illum reiciendis sapiente.</p>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n<p> </p>', 6, '2023-04-05 14:57:11'),
(7, 'Galerie d\'image manuelle', NULL, '<div class=\"cms-img-gallery\">\r\n<div class=\"col-12 col-xs-6 col-sm-4 col-xl-3\"><a class=\"img-gallery\" title=\"\" href=\"/skin/default/img/snippet/working.jpg\"> <img class=\"img-responsive\" src=\"/skin/default/img/snippet/working.jpg\" alt=\"\" width=\"640\" height=\"426\" /> </a></div>\r\n<div class=\"col-12 col-xs-6 col-sm-4 col-xl-3\"><a class=\"img-gallery\" title=\"\" href=\"/skin/default/img/snippet/working.jpg\"> <img class=\"img-responsive\" src=\"/skin/default/img/snippet/working.jpg\" alt=\"\" width=\"640\" height=\"426\" /> </a></div>\r\n<div class=\"col-12 col-xs-6 col-sm-4 col-xl-3\"><a class=\"img-gallery\" title=\"\" href=\"/skin/default/img/snippet/working.jpg\"> <img class=\"img-responsive\" src=\"/skin/default/img/snippet/working.jpg\" alt=\"\" width=\"640\" height=\"426\" /> </a></div>\r\n<div class=\"col-12 col-xs-6 col-sm-4 col-xl-3\"><a class=\"img-gallery\" title=\"\" href=\"/skin/default/img/snippet/working.jpg\"> <img class=\"img-responsive\" src=\"/skin/default/img/snippet/working.jpg\" alt=\"\" width=\"640\" height=\"426\" /> </a></div>\r\n<div class=\"col-12 col-xs-6 col-sm-4 col-xl-3\"><a class=\"img-gallery\" title=\"\" href=\"/skin/default/img/snippet/working.jpg\"> <img class=\"img-responsive\" src=\"/skin/default/img/snippet/working.jpg\" alt=\"\" width=\"640\" height=\"426\" /> </a></div>\r\n<div class=\"col-12 col-xs-6 col-sm-4 col-xl-3\"><a class=\"img-gallery\" title=\"\" href=\"/skin/default/img/snippet/working.jpg\"> <img class=\"img-responsive\" src=\"/skin/default/img/snippet/working.jpg\" alt=\"\" width=\"640\" height=\"426\" /> </a></div>\r\n<div class=\"col-12 col-xs-6 col-sm-4 col-xl-3\"><a class=\"img-gallery\" title=\"\" href=\"/skin/default/img/snippet/working.jpg\"> <img class=\"img-responsive\" src=\"/skin/default/img/snippet/working.jpg\" alt=\"\" width=\"640\" height=\"426\" /> </a></div>\r\n<div class=\"col-12 col-xs-6 col-sm-4 col-xl-3\"><a class=\"img-gallery\" title=\"\" href=\"/skin/default/img/snippet/working.jpg\"> <img class=\"img-responsive\" src=\"/skin/default/img/snippet/working.jpg\" alt=\"\" width=\"640\" height=\"426\" /> </a></div>\r\n<p> </p>\r\n</div>', 7, '2023-04-05 15:00:02'),
(8, 'Image de galerie manuelle', NULL, '<div class=\"col-12 col-xs-6 col-sm-4 col-xl-3\"><a class=\"img-gallery\" title=\"\" href=\"/skin/default/img/snippet/working.jpg\"> <img class=\"img-responsive\" src=\"/skin/default/img/snippet/working.jpg\" alt=\"\" width=\"640\" height=\"426\" /> </a></div>\r\n<p> </p>', 8, '2023-04-05 15:00:26');