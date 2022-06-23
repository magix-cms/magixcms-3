-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : mar. 24 août 2021 à 09:05
-- Version du serveur : 8.0.26
-- Version de PHP : 8.0.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `magixcms3`
--

-- --------------------------------------------------------

--
-- Structure de la table `mc_about`
--

DROP TABLE IF EXISTS `mc_about`;
CREATE TABLE IF NOT EXISTS `mc_about` (
  `id_info` smallint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name_info` varchar(30) NOT NULL,
  `value_info` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_info`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `mc_about`
--

INSERT INTO `mc_about` (`id_info`, `name_info`, `value_info`) VALUES
(1, 'name', NULL),
(2, 'type', 'org'),
(3, 'eshop', '0'),
(4, 'tva', NULL),
(5, 'adress', NULL),
(6, 'street', NULL),
(7, 'postcode', NULL),
(8, 'city', NULL),
(9, 'mail', NULL),
(10, 'click_to_mail', '0'),
(11, 'crypt_mail', '1'),
(12, 'phone', NULL),
(13, 'mobile', NULL),
(14, 'click_to_call', '1'),
(15, 'fax', NULL),
(16, 'languages', 'French'),
(17, 'facebook', NULL),
(18, 'twitter', NULL),
(19, 'google', NULL),
(20, 'linkedin', NULL),
(21, 'viadeo', NULL),
(22, 'pinterest', NULL),
(23, 'instagram', NULL),
(24, 'github', NULL),
(25, 'soundcloud', NULL),
(26, 'viadeo', NULL),
(27, 'openinghours', '0');

-- --------------------------------------------------------

--
-- Structure de la table `mc_about_data`
--

DROP TABLE IF EXISTS `mc_about_data`;
CREATE TABLE IF NOT EXISTS `mc_about_data` (
  `id_data` smallint UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_lang` smallint UNSIGNED NOT NULL,
  `name_info` varchar(30) DEFAULT NULL,
  `value_info` text,
  PRIMARY KEY (`id_data`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `mc_about_op`
--

DROP TABLE IF EXISTS `mc_about_op`;
CREATE TABLE IF NOT EXISTS `mc_about_op` (
  `id_day` smallint UNSIGNED NOT NULL AUTO_INCREMENT,
  `day_abbr` varchar(2) NOT NULL,
  `open_day` smallint UNSIGNED DEFAULT '0',
  `noon_time` smallint UNSIGNED DEFAULT '0',
  `open_time` varchar(5) DEFAULT NULL,
  `close_time` varchar(5) DEFAULT NULL,
  `noon_start` varchar(5) DEFAULT NULL,
  `noon_end` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`id_day`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `mc_about_op`
--

INSERT INTO `mc_about_op` (`id_day`, `day_abbr`, `open_day`, `noon_time`, `open_time`, `close_time`, `noon_start`, `noon_end`) VALUES
(1, 'Mo', 1, 1, NULL, NULL, NULL, NULL),
(2, 'Tu', 0, 0, NULL, NULL, NULL, NULL),
(3, 'We', 0, 0, NULL, NULL, NULL, NULL),
(4, 'Th', 0, 0, NULL, NULL, NULL, NULL),
(5, 'Fr', 0, 0, NULL, NULL, NULL, NULL),
(6, 'Sa', 0, 0, NULL, NULL, NULL, NULL),
(7, 'Su', 0, 0, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `mc_about_op_content`
--

DROP TABLE IF EXISTS `mc_about_op_content`;
CREATE TABLE IF NOT EXISTS `mc_about_op_content` (
  `id_content` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_lang` smallint UNSIGNED NOT NULL,
  `text_mo` text,
  `text_tu` text,
  `text_we` text,
  `text_th` text,
  `text_fr` text,
  `text_sa` text,
  `text_su` text,
  PRIMARY KEY (`id_content`),
  KEY `id_lang` (`id_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `mc_about_page`
--

DROP TABLE IF EXISTS `mc_about_page`;
CREATE TABLE IF NOT EXISTS `mc_about_page` (
  `id_pages` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_parent` int UNSIGNED DEFAULT NULL,
  `menu_pages` smallint UNSIGNED DEFAULT '1',
  `order_pages` smallint UNSIGNED NOT NULL DEFAULT '0',
  `date_register` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pages`),
  KEY `id_parent` (`id_parent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `mc_about_page_content`
--

DROP TABLE IF EXISTS `mc_about_page_content`;
CREATE TABLE IF NOT EXISTS `mc_about_page_content` (
  `id_content` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_pages` int UNSIGNED NOT NULL,
  `id_lang` smallint UNSIGNED NOT NULL DEFAULT '1',
  `name_pages` varchar(150) DEFAULT NULL,
  `url_pages` varchar(150) DEFAULT NULL,
  `resume_pages` text,
  `content_pages` text,
  `seo_title_pages` varchar(180) DEFAULT NULL,
  `seo_desc_pages` text,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `published_pages` smallint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_content`),
  KEY `id_pages` (`id_pages`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `mc_admin_access`
--

DROP TABLE IF EXISTS `mc_admin_access`;
CREATE TABLE IF NOT EXISTS `mc_admin_access` (
  `id_access` smallint UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_role` smallint UNSIGNED NOT NULL,
  `id_module` int UNSIGNED NOT NULL,
  `view` smallint UNSIGNED NOT NULL DEFAULT '0',
  `append` smallint UNSIGNED NOT NULL DEFAULT '0',
  `edit` smallint UNSIGNED NOT NULL DEFAULT '0',
  `del` smallint UNSIGNED NOT NULL DEFAULT '0',
  `action` smallint UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_access`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `mc_admin_access`
--

INSERT INTO `mc_admin_access` (`id_access`, `id_role`, `id_module`, `view`, `append`, `edit`, `del`, `action`) VALUES
(1, 1, 1, 1, 1, 1, 1, 1),
(2, 1, 2, 1, 1, 1, 1, 1),
(3, 1, 3, 1, 1, 1, 1, 1),
(4, 1, 4, 1, 1, 1, 1, 1),
(5, 1, 5, 1, 1, 1, 1, 1),
(6, 1, 6, 1, 1, 1, 1, 1),
(7, 1, 7, 1, 1, 1, 1, 1),
(8, 1, 8, 1, 1, 1, 1, 1),
(9, 1, 9, 1, 1, 1, 1, 1),
(10, 1, 10, 1, 1, 1, 1, 1),
(11, 1, 11, 1, 1, 1, 1, 1),
(12, 1, 12, 1, 1, 1, 1, 1),
(13, 1, 13, 1, 1, 1, 1, 1),
(14, 1, 14, 1, 1, 1, 1, 1),
(15, 1, 15, 1, 1, 1, 1, 1),
(16, 1, 16, 1, 1, 1, 1, 1),
(17, 1, 17, 1, 1, 1, 1, 1),
(18, 1, 18, 1, 1, 1, 1, 1),
(19, 1, 19, 1, 1, 1, 1, 1),
(20, 1, 20, 1, 1, 1, 1, 1),
(21, 1, 21, 1, 1, 1, 1, 1),
(22, 1, 22, 1, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `mc_admin_access_rel`
--

DROP TABLE IF EXISTS `mc_admin_access_rel`;
CREATE TABLE IF NOT EXISTS `mc_admin_access_rel` (
  `id_access_rel` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_admin` smallint UNSIGNED NOT NULL,
  `id_role` smallint UNSIGNED NOT NULL,
  PRIMARY KEY (`id_access_rel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `mc_admin_employee`
--

DROP TABLE IF EXISTS `mc_admin_employee`;
CREATE TABLE IF NOT EXISTS `mc_admin_employee` (
  `id_admin` smallint UNSIGNED NOT NULL AUTO_INCREMENT,
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
  `active_admin` smallint UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_admin`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `mc_admin_employee`
--

INSERT INTO `mc_admin_employee` (`id_admin`, `keyuniqid_admin`, `title_admin`, `lastname_admin`, `firstname_admin`, `pseudo_admin`, `email_admin`, `phone_admin`, `address_admin`, `postcode_admin`, `city_admin`, `country_admin`, `passwd_admin`, `last_change_admin`, `change_passwd`, `active_admin`) VALUES
(1, '0d35d554eeed47f6a4eaa658d61bbaf3', 'm', 'Salvatore Di Salvo', 'Salvatore', NULL, 'salvatore@web-solution-way.com', NULL, NULL, NULL, NULL, NULL, '$2y$10$J2Z3JtkcJC1qY6uiATWjc.lVtOoWaHSxZZKfkub1PRqORYaJSW4u.', '2021-04-13 06:51:52', NULL, 1);

-- --------------------------------------------------------

--
-- Structure de la table `mc_admin_role_user`
--

DROP TABLE IF EXISTS `mc_admin_role_user`;
CREATE TABLE IF NOT EXISTS `mc_admin_role_user` (
  `id_role` smallint UNSIGNED NOT NULL AUTO_INCREMENT,
  `role_name` varchar(50) NOT NULL,
  PRIMARY KEY (`id_role`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `mc_admin_role_user`
--

INSERT INTO `mc_admin_role_user` (`id_role`, `role_name`) VALUES
(1, 'administrator');

-- --------------------------------------------------------

--
-- Structure de la table `mc_admin_session`
--

DROP TABLE IF EXISTS `mc_admin_session`;
CREATE TABLE IF NOT EXISTS `mc_admin_session` (
  `id_admin_session` varchar(150) NOT NULL,
  `id_admin` smallint UNSIGNED NOT NULL,
  `keyuniqid_admin` varchar(50) NOT NULL,
  `ip_session` varchar(25) NOT NULL,
  `browser_admin` varchar(50) NOT NULL,
  `last_modified_session` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expires` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_admin_session`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `mc_catalog`
--

DROP TABLE IF EXISTS `mc_catalog`;
CREATE TABLE IF NOT EXISTS `mc_catalog` (
  `id_catalog` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_product` int UNSIGNED NOT NULL,
  `id_cat` int UNSIGNED NOT NULL,
  `default_c` smallint UNSIGNED NOT NULL DEFAULT '0',
  `order_p` int UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_catalog`),
  KEY `id_product` (`id_product`,`id_cat`),
  KEY `id_cat` (`id_cat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `mc_catalog_cat`
--

DROP TABLE IF EXISTS `mc_catalog_cat`;
CREATE TABLE IF NOT EXISTS `mc_catalog_cat` (
  `id_cat` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_parent` int UNSIGNED DEFAULT NULL,
  `img_cat` varchar(150) DEFAULT NULL,
  `menu_cat` smallint UNSIGNED NOT NULL DEFAULT '1',
  `order_cat` smallint UNSIGNED NOT NULL DEFAULT '0',
  `date_register` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_cat`),
  KEY `id_parent` (`id_parent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `mc_catalog_cat_content`
--

DROP TABLE IF EXISTS `mc_catalog_cat_content`;
CREATE TABLE IF NOT EXISTS `mc_catalog_cat_content` (
  `id_content` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_cat` int UNSIGNED NOT NULL,
  `id_lang` smallint UNSIGNED NOT NULL DEFAULT '1',
  `name_cat` varchar(150) DEFAULT NULL,
  `url_cat` varchar(150) DEFAULT NULL,
  `resume_cat` text,
  `content_cat` text,
  `alt_img` varchar(70) DEFAULT NULL,
  `title_img` varchar(70) DEFAULT NULL,
  `caption_img` varchar(125) DEFAULT NULL,
  `seo_title_cat` varchar(180) DEFAULT NULL,
  `seo_desc_cat` text,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `published_cat` smallint UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_content`),
  KEY `id_cat` (`id_cat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `mc_catalog_data`
--

DROP TABLE IF EXISTS `mc_catalog_data`;
CREATE TABLE IF NOT EXISTS `mc_catalog_data` (
  `id_data` smallint UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_lang` smallint UNSIGNED NOT NULL,
  `name_info` varchar(30) DEFAULT NULL,
  `value_info` text,
  PRIMARY KEY (`id_data`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `mc_catalog_product`
--

DROP TABLE IF EXISTS `mc_catalog_product`;
CREATE TABLE IF NOT EXISTS `mc_catalog_product` (
  `id_product` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `price_p` decimal(20,2) NOT NULL DEFAULT '0.00',
  `reference_p` varchar(32) DEFAULT NULL,
  `width_p` decimal(20,2) NOT NULL DEFAULT '0.00',
  `height_p` decimal(20,2) NOT NULL DEFAULT '0.00',
  `depth_p` decimal(20,2) NOT NULL DEFAULT '0.00',
  `weight_p` decimal(20,2) NOT NULL DEFAULT '0.00',
  `date_register` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_product`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `mc_catalog_product_content`
--

DROP TABLE IF EXISTS `mc_catalog_product_content`;
CREATE TABLE IF NOT EXISTS `mc_catalog_product_content` (
  `id_content` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_product` int UNSIGNED NOT NULL,
  `id_lang` smallint UNSIGNED NOT NULL DEFAULT '1',
  `name_p` varchar(125) DEFAULT NULL,
  `longname_p` varchar(125) DEFAULT NULL,
  `url_p` varchar(125) DEFAULT NULL,
  `resume_p` text,
  `content_p` text,
  `seo_title_p` varchar(180) DEFAULT NULL,
  `seo_desc_p` text,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `published_p` smallint UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_content`),
  KEY `id_product` (`id_product`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `mc_catalog_product_img`
--

DROP TABLE IF EXISTS `mc_catalog_product_img`;
CREATE TABLE IF NOT EXISTS `mc_catalog_product_img` (
  `id_img` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_product` int UNSIGNED NOT NULL,
  `name_img` varchar(150) NOT NULL,
  `default_img` smallint NOT NULL DEFAULT '0',
  `order_img` smallint UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_img`),
  KEY `id_product` (`id_product`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `mc_catalog_product_img_content`
--

DROP TABLE IF EXISTS `mc_catalog_product_img_content`;
CREATE TABLE IF NOT EXISTS `mc_catalog_product_img_content` (
  `id_content` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_img` int UNSIGNED NOT NULL,
  `id_lang` smallint UNSIGNED NOT NULL,
  `alt_img` varchar(70) DEFAULT NULL,
  `title_img` varchar(70) DEFAULT NULL,
  `caption_img` varchar(125) DEFAULT NULL,
  PRIMARY KEY (`id_content`),
  KEY `id_img` (`id_img`,`id_lang`),
  KEY `id_lang` (`id_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `mc_catalog_product_rel`
--

DROP TABLE IF EXISTS `mc_catalog_product_rel`;
CREATE TABLE IF NOT EXISTS `mc_catalog_product_rel` (
  `id_rel` int NOT NULL AUTO_INCREMENT,
  `id_product` int UNSIGNED NOT NULL,
  `id_product_2` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id_rel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `mc_cms_page`
--

DROP TABLE IF EXISTS `mc_cms_page`;
CREATE TABLE IF NOT EXISTS `mc_cms_page` (
  `id_pages` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_parent` int UNSIGNED DEFAULT NULL,
  `menu_pages` smallint UNSIGNED DEFAULT '1',
  `order_pages` smallint UNSIGNED NOT NULL DEFAULT '0',
  `date_register` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pages`),
  KEY `id_parent` (`id_parent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `mc_cms_page_content`
--

DROP TABLE IF EXISTS `mc_cms_page_content`;
CREATE TABLE IF NOT EXISTS `mc_cms_page_content` (
  `id_content` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_pages` int UNSIGNED NOT NULL,
  `id_lang` smallint UNSIGNED NOT NULL DEFAULT '1',
  `name_pages` varchar(150) DEFAULT NULL,
  `url_pages` varchar(150) DEFAULT NULL,
  `resume_pages` text,
  `content_pages` text,
  `seo_title_pages` varchar(180) DEFAULT NULL,
  `seo_desc_pages` text,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `published_pages` smallint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_content`),
  KEY `id_pages` (`id_pages`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `mc_cms_page_img`
--

DROP TABLE IF EXISTS `mc_cms_page_img`;
CREATE TABLE IF NOT EXISTS `mc_cms_page_img` (
  `id_img` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_pages` int UNSIGNED NOT NULL,
  `name_img` varchar(150) NOT NULL,
  `default_img` smallint NOT NULL DEFAULT '0',
  `order_img` smallint UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_img`),
  KEY `id_pages` (`id_pages`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `mc_cms_page_img_content`
--

DROP TABLE IF EXISTS `mc_cms_page_img_content`;
CREATE TABLE IF NOT EXISTS `mc_cms_page_img_content` (
  `id_content` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_img` int UNSIGNED NOT NULL,
  `id_lang` smallint UNSIGNED NOT NULL,
  `alt_img` varchar(70) DEFAULT NULL,
  `title_img` varchar(70) DEFAULT NULL,
  `caption_img` varchar(125) DEFAULT NULL,
  PRIMARY KEY (`id_content`),
  KEY `id_img` (`id_img`,`id_lang`),
  KEY `id_lang` (`id_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `mc_config`
--

DROP TABLE IF EXISTS `mc_config`;
CREATE TABLE IF NOT EXISTS `mc_config` (
  `idconfig` smallint UNSIGNED NOT NULL AUTO_INCREMENT,
  `attr_name` varchar(20) NOT NULL,
  `status` smallint UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`idconfig`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `mc_config`
--

INSERT INTO `mc_config` (`idconfig`, `attr_name`, `status`) VALUES
(1, 'pages', 1),
(2, 'news', 1),
(3, 'catalog', 1),
(4, 'about', 1);

-- --------------------------------------------------------

--
-- Structure de la table `mc_config_img`
--

DROP TABLE IF EXISTS `mc_config_img`;
CREATE TABLE IF NOT EXISTS `mc_config_img` (
  `id_config_img` smallint UNSIGNED NOT NULL AUTO_INCREMENT,
  `module_img` enum('catalog','news','pages','logo','plugins') NOT NULL,
  `attribute_img` varchar(40) NOT NULL,
  `width_img` decimal(4,0) NOT NULL,
  `height_img` decimal(4,0) NOT NULL,
  `type_img` enum('small','medium','large') NOT NULL,
  `resize_img` enum('basic','adaptive') NOT NULL,
  PRIMARY KEY (`id_config_img`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `mc_config_img`
--

INSERT INTO `mc_config_img` (`id_config_img`, `module_img`, `attribute_img`, `width_img`, `height_img`, `type_img`, `resize_img`) VALUES
(1, 'pages', 'page', '250', '155', 'small', 'adaptive'),
(2, 'pages', 'page', '500', '309', 'medium', 'adaptive'),
(3, 'pages', 'page', '1000', '1000', 'large', 'basic'),
(4, 'news', 'news', '250', '155', 'small', 'adaptive'),
(5, 'news', 'news', '500', '309', 'medium', 'adaptive'),
(6, 'news', 'news', '1000', '1000', 'large', 'basic'),
(7, 'catalog', 'category', '250', '155', 'small', 'adaptive'),
(8, 'catalog', 'category', '500', '309', 'medium', 'adaptive'),
(9, 'catalog', 'category', '1000', '1000', 'large', 'basic'),
(10, 'catalog', 'product', '250', '155', 'small', 'adaptive'),
(11, 'catalog', 'product', '500', '309', 'medium', 'adaptive'),
(12, 'catalog', 'product', '1000', '1000', 'large', 'basic'),
(13, 'logo', 'logo', '500', '121', 'large', 'adaptive'),
(14, 'logo', 'logo', '480', '105', 'medium', 'adaptive'),
(15, 'logo', 'logo', '229', '50', 'small', 'adaptive'),
(16, 'logo', 'page', '500', '309', 'medium', 'adaptive'),
(17, 'logo', 'news', '500', '309', 'medium', 'adaptive'),
(18, 'logo', 'category', '500', '309', 'medium', 'adaptive'),
(19, 'logo', 'product', '500', '309', 'medium', 'adaptive');

-- --------------------------------------------------------

--
-- Structure de la table `mc_country`
--

DROP TABLE IF EXISTS `mc_country`;
CREATE TABLE IF NOT EXISTS `mc_country` (
  `id_country` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `iso_country` varchar(5) NOT NULL,
  `name_country` varchar(125) NOT NULL,
  `order_country` int UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_country`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `mc_css_inliner`
--

DROP TABLE IF EXISTS `mc_css_inliner`;
CREATE TABLE IF NOT EXISTS `mc_css_inliner` (
  `id_cssi` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `property_cssi` varchar(125) NOT NULL,
  `color_cssi` varchar(50) NOT NULL,
  PRIMARY KEY (`id_cssi`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `mc_css_inliner`
--

INSERT INTO `mc_css_inliner` (`id_cssi`, `property_cssi`, `color_cssi`) VALUES
(1, 'header_bg', '#f2f2f2'),
(2, 'header_c', '#ffffff'),
(3, 'footer_bg', '#333333'),
(4, 'footer_c', '#ffffff');

-- --------------------------------------------------------

--
-- Structure de la table `mc_domain`
--

DROP TABLE IF EXISTS `mc_domain`;
CREATE TABLE IF NOT EXISTS `mc_domain` (
  `id_domain` smallint UNSIGNED NOT NULL AUTO_INCREMENT,
  `url_domain` varchar(175) NOT NULL,
  `tracking_domain` text,
  `default_domain` smallint UNSIGNED NOT NULL DEFAULT '0',
  `canonical_domain` smallint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_domain`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `mc_domain_language`
--

DROP TABLE IF EXISTS `mc_domain_language`;
CREATE TABLE IF NOT EXISTS `mc_domain_language` (
  `id_domain_lg` int NOT NULL AUTO_INCREMENT,
  `id_domain` smallint UNSIGNED NOT NULL,
  `id_lang` smallint UNSIGNED NOT NULL,
  `default_lang` smallint UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_domain_lg`),
  KEY `id_lang` (`id_lang`),
  KEY `id_domain` (`id_domain`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `mc_home_page`
--

DROP TABLE IF EXISTS `mc_home_page`;
CREATE TABLE IF NOT EXISTS `mc_home_page` (
  `id_page` smallint UNSIGNED NOT NULL AUTO_INCREMENT,
  `date_register` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_page`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `mc_home_page_content`
--

DROP TABLE IF EXISTS `mc_home_page_content`;
CREATE TABLE IF NOT EXISTS `mc_home_page_content` (
  `id_content` smallint UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_page` smallint UNSIGNED NOT NULL,
  `id_lang` smallint UNSIGNED NOT NULL,
  `title_page` varchar(150) NOT NULL,
  `content_page` text,
  `seo_title_page` varchar(180) DEFAULT NULL,
  `seo_desc_page` text,
  `published` smallint UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_content`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `mc_lang`
--

DROP TABLE IF EXISTS `mc_lang`;
CREATE TABLE IF NOT EXISTS `mc_lang` (
  `id_lang` smallint UNSIGNED NOT NULL AUTO_INCREMENT,
  `iso_lang` varchar(10) NOT NULL,
  `name_lang` varchar(40) DEFAULT NULL,
  `default_lang` smallint UNSIGNED NOT NULL DEFAULT '0',
  `active_lang` smallint UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_lang`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `mc_lang`
--

INSERT INTO `mc_lang` (`id_lang`, `iso_lang`, `name_lang`, `default_lang`, `active_lang`) VALUES
(1, 'fr', 'French', 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `mc_logo`
--

DROP TABLE IF EXISTS `mc_logo`;
CREATE TABLE IF NOT EXISTS `mc_logo` (
  `id_logo` smallint UNSIGNED NOT NULL AUTO_INCREMENT,
  `img_logo` varchar(125) DEFAULT NULL,
  `active_logo` smallint NOT NULL DEFAULT '0',
  `date_register` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_logo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `mc_logo_content`
--

DROP TABLE IF EXISTS `mc_logo_content`;
CREATE TABLE IF NOT EXISTS `mc_logo_content` (
  `id_content` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_logo` smallint UNSIGNED NOT NULL,
  `id_lang` smallint UNSIGNED NOT NULL DEFAULT '1',
  `alt_logo` varchar(70) DEFAULT NULL,
  `title_logo` varchar(70) DEFAULT NULL,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_content`),
  KEY `id_logo` (`id_logo`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `mc_menu`
--

DROP TABLE IF EXISTS `mc_menu`;
CREATE TABLE IF NOT EXISTS `mc_menu` (
  `id_link` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `type_link` enum('home','pages','about','about_page','catalog','category','news','plugin','external') NOT NULL,
  `id_page` int UNSIGNED DEFAULT NULL,
  `mode_link` enum('simple','dropdown','mega') NOT NULL DEFAULT 'simple',
  `order_link` int UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_link`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `mc_menu_content`
--

DROP TABLE IF EXISTS `mc_menu_content`;
CREATE TABLE IF NOT EXISTS `mc_menu_content` (
  `id_link_content` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_link` int UNSIGNED NOT NULL,
  `id_lang` smallint UNSIGNED NOT NULL,
  `name_link` varchar(50) DEFAULT NULL,
  `title_link` varchar(180) DEFAULT NULL,
  `url_link` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id_link_content`),
  KEY `id_link` (`id_link`),
  KEY `id_lang` (`id_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `mc_module`
--

DROP TABLE IF EXISTS `mc_module`;
CREATE TABLE IF NOT EXISTS `mc_module` (
  `id_module` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `class_name` varchar(50) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_module`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `mc_module`
--

INSERT INTO `mc_module` (`id_module`, `class_name`, `name`) VALUES
(1, 'backend_controller_dashboard', 'dashboard'),
(2, 'backend_controller_employee', 'employee'),
(3, 'backend_controller_access', 'access'),
(4, 'backend_controller_language', 'language'),
(5, 'backend_controller_country', 'country'),
(6, 'backend_controller_domain', 'domain'),
(7, 'backend_controller_setting', 'setting'),
(8, 'backend_controller_home', 'home'),
(9, 'backend_controller_pages', 'pages'),
(10, 'backend_controller_files', 'files'),
(11, 'backend_controller_about', 'about'),
(12, 'backend_controller_news', 'news'),
(13, 'backend_controller_webservice', 'webservice'),
(14, 'backend_controller_category', 'category'),
(15, 'backend_controller_catalog', 'catalog'),
(16, 'backend_controller_product', 'product'),
(17, 'backend_controller_seo', 'seo'),
(18, 'backend_controller_theme', 'theme'),
(19, 'backend_controller_plugins', 'plugins'),
(20, 'backend_controller_translate', 'translate'),
(21, 'backend_controller_logo', 'logo'),
(22, 'backend_controller_snippet', 'snippet');

-- --------------------------------------------------------

--
-- Structure de la table `mc_news`
--

DROP TABLE IF EXISTS `mc_news`;
CREATE TABLE IF NOT EXISTS `mc_news` (
  `id_news` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `img_news` varchar(125) DEFAULT NULL,
  `date_register` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_news`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `mc_news_content`
--

DROP TABLE IF EXISTS `mc_news_content`;
CREATE TABLE IF NOT EXISTS `mc_news_content` (
  `id_content` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_news` int UNSIGNED NOT NULL,
  `id_lang` smallint UNSIGNED NOT NULL,
  `name_news` varchar(150) DEFAULT NULL,
  `url_news` varchar(150) DEFAULT NULL,
  `resume_news` text,
  `content_news` text,
  `alt_img` varchar(70) DEFAULT NULL,
  `title_img` varchar(70) DEFAULT NULL,
  `caption_img` varchar(125) DEFAULT NULL,
  `seo_title_news` varchar(180) DEFAULT NULL,
  `seo_desc_news` text,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_publish` timestamp NULL DEFAULT NULL,
  `published_news` smallint UNSIGNED DEFAULT '0',
  PRIMARY KEY (`id_content`),
  KEY `id_news` (`id_news`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `mc_news_tag`
--

DROP TABLE IF EXISTS `mc_news_tag`;
CREATE TABLE IF NOT EXISTS `mc_news_tag` (
  `id_tag` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_lang` smallint UNSIGNED NOT NULL,
  `name_tag` varchar(50) NOT NULL,
  PRIMARY KEY (`id_tag`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `mc_news_tag_rel`
--

DROP TABLE IF EXISTS `mc_news_tag_rel`;
CREATE TABLE IF NOT EXISTS `mc_news_tag_rel` (
  `id_rel` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_news` int UNSIGNED NOT NULL,
  `id_tag` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id_rel`),
  KEY `id_tag` (`id_tag`),
  KEY `id_news` (`id_news`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `mc_plugins`
--

DROP TABLE IF EXISTS `mc_plugins`;
CREATE TABLE IF NOT EXISTS `mc_plugins` (
  `id_plugins` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `version` varchar(10) NOT NULL,
  `home` smallint UNSIGNED NOT NULL DEFAULT '0',
  `about` smallint UNSIGNED NOT NULL DEFAULT '0',
  `pages` smallint UNSIGNED NOT NULL DEFAULT '0',
  `news` smallint UNSIGNED NOT NULL DEFAULT '0',
  `catalog` smallint UNSIGNED NOT NULL DEFAULT '0',
  `category` smallint UNSIGNED NOT NULL DEFAULT '0',
  `product` smallint UNSIGNED NOT NULL DEFAULT '0',
  `seo` smallint UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_plugins`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `mc_plugins_module`
--

DROP TABLE IF EXISTS `mc_plugins_module`;
CREATE TABLE IF NOT EXISTS `mc_plugins_module` (
  `id_module` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `plugin_name` varchar(200) NOT NULL,
  `module_name` varchar(200) NOT NULL,
  `active` smallint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_module`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `mc_seo`
--

DROP TABLE IF EXISTS `mc_seo`;
CREATE TABLE IF NOT EXISTS `mc_seo` (
  `id_seo` smallint UNSIGNED NOT NULL AUTO_INCREMENT,
  `level_seo` varchar(30) NOT NULL,
  `attribute_seo` varchar(50) NOT NULL,
  `type_seo` enum('title','description') NOT NULL,
  PRIMARY KEY (`id_seo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `mc_seo_content`
--

DROP TABLE IF EXISTS `mc_seo_content`;
CREATE TABLE IF NOT EXISTS `mc_seo_content` (
  `id_content` smallint UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_seo` smallint UNSIGNED NOT NULL,
  `id_lang` smallint UNSIGNED NOT NULL,
  `content_seo` text NOT NULL,
  PRIMARY KEY (`id_content`),
  KEY `id_seo` (`id_seo`),
  KEY `id_lang` (`id_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `mc_setting`
--

DROP TABLE IF EXISTS `mc_setting`;
CREATE TABLE IF NOT EXISTS `mc_setting` (
  `id_setting` smallint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `value` text,
  `type` varchar(8) NOT NULL DEFAULT 'string',
  `label` text,
  `category` varchar(20) NOT NULL,
  PRIMARY KEY (`id_setting`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `mc_setting`
--

INSERT INTO `mc_setting` (`id_setting`, `name`, `value`, `type`, `label`, `category`) VALUES
(1, 'theme', 'default', 'string', 'site theme', 'theme'),
(2, 'analytics', NULL, 'string', 'google analytics', 'google'),
(3, 'magix_version', '3.3.3', 'string', 'Version Magix CMS', 'release'),
(4, 'content_css', NULL, 'string', 'css from skin for tinyMCE', 'general'),
(5, 'concat', '0', 'int', 'concat URL', 'general'),
(6, 'cache', 'none', 'string', 'Cache template', 'general'),
(7, 'robots', 'noindex,nofollow', 'string', 'metas robots', 'general'),
(8, 'css_inliner', '0', 'string', 'CSS inliner', 'general'),
(9, 'mode', 'dev', 'string', 'Environment types', 'general'),
(10, 'ssl', '0', 'int', 'SSL protocol', 'general'),
(11, 'service_worker', '0', 'int', 'Service Worker', 'general'),
(12, 'vat_rate', '21', 'float', 'VAT Rate', 'catalog'),
(13, 'price_display', 'tinc', 'string', 'Price display with or without tax included', 'catalog'),
(14, 'amp', '0', 'int', 'amp', 'general'),
(15, 'mail_sender', NULL, 'string', 'Mail sender', 'mail'),
(16, 'smtp_enabled', '0', 'int', 'Smtp enabled', 'mail'),
(17, 'set_host', NULL, 'string', 'Set host', 'mail'),
(18, 'set_port', NULL, 'string', 'Set port', 'mail'),
(19, 'set_encryption', NULL, 'string', 'Set encryption', 'mail'),
(20, 'set_username', NULL, 'string', 'Set username', 'mail'),
(21, 'set_password', NULL, 'string', 'Set password', 'mail');

-- --------------------------------------------------------

--
-- Structure de la table `mc_share_config`
--

DROP TABLE IF EXISTS `mc_share_config`;
CREATE TABLE IF NOT EXISTS `mc_share_config` (
  `id_share` smallint UNSIGNED NOT NULL AUTO_INCREMENT,
  `facebook` smallint UNSIGNED NOT NULL DEFAULT '1',
  `twitter` smallint UNSIGNED NOT NULL DEFAULT '0',
  `viadeo` smallint UNSIGNED NOT NULL DEFAULT '1',
  `google` smallint UNSIGNED NOT NULL DEFAULT '1',
  `linkedin` smallint UNSIGNED NOT NULL DEFAULT '1',
  `pinterest` smallint UNSIGNED NOT NULL DEFAULT '1',
  `twitter_id` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_share`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `mc_share_config`
--

INSERT INTO `mc_share_config` (`id_share`, `facebook`, `twitter`, `viadeo`, `google`, `linkedin`, `pinterest`, `twitter_id`) VALUES
(1, 1, 1, 1, 1, 1, 1, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `mc_share_url`
--

DROP TABLE IF EXISTS `mc_share_url`;
CREATE TABLE IF NOT EXISTS `mc_share_url` (
  `id_share_url` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name_share` varchar(50) NOT NULL,
  `url_share` varchar(400) NOT NULL,
  `icon_share` varchar(50) NOT NULL,
  PRIMARY KEY (`id_share_url`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `mc_share_url`
--

INSERT INTO `mc_share_url` (`id_share_url`, `name_share`, `url_share`, `icon_share`) VALUES
(1, 'facebook', 'http://www.facebook.com/share.php?u=%URL%', 'facebook'),
(2, 'twitter', 'https://twitter.com/intent/tweet?text=%NAME%&amp;url=%URL%', 'twitter'),
(3, 'viadeo', 'http://www.viadeo.com/shareit/share/?url=%URL%&amp;title=%NAME%&amp;overview=%NAME%', 'viadeo'),
(4, 'google', 'https://plus.google.com/share?url=%URL%', 'google-plus'),
(5, 'linkedin', 'http://www.linkedin.com/shareArticle?mini=true&url=%URL%', 'linkedin'),
(6, 'pinterest', 'http://pinterest.com/pin/create/link/?url=%URL%', 'pinterest-p');

-- --------------------------------------------------------

--
-- Structure de la table `mc_snippet`
--

DROP TABLE IF EXISTS `mc_snippet`;
CREATE TABLE IF NOT EXISTS `mc_snippet` (
  `id_snippet` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `title_sp` varchar(30) DEFAULT NULL,
  `description_sp` varchar(30) DEFAULT NULL,
  `content_sp` text,
  `date_register` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_snippet`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `mc_webservice`
--

DROP TABLE IF EXISTS `mc_webservice`;
CREATE TABLE IF NOT EXISTS `mc_webservice` (
  `id_ws` smallint UNSIGNED NOT NULL AUTO_INCREMENT,
  `key_ws` varchar(125) DEFAULT NULL,
  `status_ws` smallint UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_ws`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `mc_about_op_content`
--
ALTER TABLE `mc_about_op_content`
  ADD CONSTRAINT `mc_about_op_content_ibfk_1` FOREIGN KEY (`id_lang`) REFERENCES `mc_lang` (`id_lang`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `mc_about_page`
--
ALTER TABLE `mc_about_page`
  ADD CONSTRAINT `mc_about_page_ibfk_1` FOREIGN KEY (`id_parent`) REFERENCES `mc_about_page` (`id_pages`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `mc_about_page_content`
--
ALTER TABLE `mc_about_page_content`
  ADD CONSTRAINT `mc_about_page_content_ibfk_1` FOREIGN KEY (`id_pages`) REFERENCES `mc_about_page` (`id_pages`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `mc_catalog`
--
ALTER TABLE `mc_catalog`
  ADD CONSTRAINT `mc_catalog_ibfk_1` FOREIGN KEY (`id_product`) REFERENCES `mc_catalog_product` (`id_product`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mc_catalog_ibfk_2` FOREIGN KEY (`id_cat`) REFERENCES `mc_catalog_cat` (`id_cat`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `mc_catalog_cat`
--
ALTER TABLE `mc_catalog_cat`
  ADD CONSTRAINT `mc_catalog_cat_ibfk_1` FOREIGN KEY (`id_parent`) REFERENCES `mc_catalog_cat` (`id_cat`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `mc_catalog_cat_content`
--
ALTER TABLE `mc_catalog_cat_content`
  ADD CONSTRAINT `mc_catalog_cat_content_ibfk_1` FOREIGN KEY (`id_cat`) REFERENCES `mc_catalog_cat` (`id_cat`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `mc_catalog_product_content`
--
ALTER TABLE `mc_catalog_product_content`
  ADD CONSTRAINT `mc_catalog_product_content_ibfk_1` FOREIGN KEY (`id_product`) REFERENCES `mc_catalog_product` (`id_product`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `mc_catalog_product_img`
--
ALTER TABLE `mc_catalog_product_img`
  ADD CONSTRAINT `mc_catalog_product_img_ibfk_1` FOREIGN KEY (`id_product`) REFERENCES `mc_catalog_product` (`id_product`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `mc_catalog_product_img_content`
--
ALTER TABLE `mc_catalog_product_img_content`
  ADD CONSTRAINT `mc_catalog_product_img_content_ibfk_1` FOREIGN KEY (`id_img`) REFERENCES `mc_catalog_product_img` (`id_img`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mc_catalog_product_img_content_ibfk_2` FOREIGN KEY (`id_lang`) REFERENCES `mc_lang` (`id_lang`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `mc_cms_page`
--
ALTER TABLE `mc_cms_page`
  ADD CONSTRAINT `mc_cms_page_ibfk_1` FOREIGN KEY (`id_parent`) REFERENCES `mc_cms_page` (`id_pages`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `mc_cms_page_content`
--
ALTER TABLE `mc_cms_page_content`
  ADD CONSTRAINT `mc_cms_page_content_ibfk_1` FOREIGN KEY (`id_pages`) REFERENCES `mc_cms_page` (`id_pages`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `mc_cms_page_img`
--
ALTER TABLE `mc_cms_page_img`
  ADD CONSTRAINT `mc_cms_page_img_ibfk_1` FOREIGN KEY (`id_pages`) REFERENCES `mc_cms_page` (`id_pages`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `mc_cms_page_img_content`
--
ALTER TABLE `mc_cms_page_img_content`
  ADD CONSTRAINT `mc_cms_page_img_content_ibfk_1` FOREIGN KEY (`id_img`) REFERENCES `mc_cms_page_img` (`id_img`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mc_cms_page_img_content_ibfk_2` FOREIGN KEY (`id_lang`) REFERENCES `mc_lang` (`id_lang`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `mc_domain_language`
--
ALTER TABLE `mc_domain_language`
  ADD CONSTRAINT `mc_domain_language_ibfk_1` FOREIGN KEY (`id_domain`) REFERENCES `mc_domain` (`id_domain`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mc_domain_language_ibfk_2` FOREIGN KEY (`id_lang`) REFERENCES `mc_lang` (`id_lang`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `mc_logo_content`
--
ALTER TABLE `mc_logo_content`
  ADD CONSTRAINT `mc_logo_content_ibfk_1` FOREIGN KEY (`id_logo`) REFERENCES `mc_logo` (`id_logo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `mc_news_content`
--
ALTER TABLE `mc_news_content`
  ADD CONSTRAINT `mc_news_content_ibfk_1` FOREIGN KEY (`id_news`) REFERENCES `mc_news` (`id_news`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `mc_news_tag_rel`
--
ALTER TABLE `mc_news_tag_rel`
  ADD CONSTRAINT `mc_news_tag_rel_ibfk_1` FOREIGN KEY (`id_tag`) REFERENCES `mc_news_tag` (`id_tag`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mc_news_tag_rel_ibfk_2` FOREIGN KEY (`id_news`) REFERENCES `mc_news` (`id_news`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `mc_seo_content`
--
ALTER TABLE `mc_seo_content`
  ADD CONSTRAINT `mc_seo_content_ibfk_1` FOREIGN KEY (`id_seo`) REFERENCES `mc_seo` (`id_seo`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
