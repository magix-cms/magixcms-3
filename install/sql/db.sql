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
(NULL, 'catalog', 1);

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
(NULL, 'backend_controller_domain', 'domain');

CREATE TABLE IF NOT EXISTS `mc_setting` (
  `id_setting` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `value` text,
  `type` varchar(8) NOT NULL DEFAULT 'string',
  `label` text,
  PRIMARY KEY (`id_setting`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `mc_setting` (`id_setting`, `name`, `value`, `type`, `label`) VALUES
(NULL, 'theme', 'default', 'string', 'site theme'),
(NULL, 'webmaster', '', 'string', 'google webmasterTools'),
(NULL, 'analytics', '', 'string', 'google analytics'),
(NULL, 'editor', 'openFilemanager', 'string', 'tinymce'),
(NULL, 'magix_version', '3.0.0', 'string', 'Version Magix CMS'),
(NULL, 'content_css', NULL, 'string', NULL),
(NULL, 'concat', '0', 'string', NULL),
(NULL, 'cache', 'none', 'string', NULL),
(NULL, 'googleplus', NULL, 'string', 'Google plus'),
(NULL, 'robots', 'noindex,nofollow', 'string', 'metas robots'),
(NULL, 'css_inliner', '1', 'string', 'CSS inliner'),
(NULL, 'mode', 'dev', 'string', 'Environment types'),
(NULL, 'ssl', '0', 'string', 'SSL protocol');

CREATE TABLE IF NOT EXISTS `mc_plugins` (
  `id_plugins` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `version` varchar(10) NOT NULL,
  PRIMARY KEY (`id_plugins`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mc_domain` (
  `id_domain` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `url_domain` varchar(175) NOT NULL,
  `default_domain` smallint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_domain`)
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
  `title` varchar(150) NOT NULL,
  `content` text,
  `seo_title` varchar(180) DEFAULT NULL,
  `seo_desc` varchar(180) DEFAULT NULL,
  `published` smallint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_content`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;