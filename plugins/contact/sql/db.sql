CREATE TABLE IF NOT EXISTS `mc_contact` (
    `id_contact` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
    `mail_contact` varchar(150) NOT NULL,
    `date_register` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id_contact`),
    KEY `idx_mail_contact` (`mail_contact`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `mc_contact_content` (
    `id_content` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
    `id_contact` smallint(5) unsigned NOT NULL,
    `id_lang` smallint(3) unsigned NOT NULL,
    `published_contact` smallint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id_content`),
    KEY `id_contact` (`id_contact`),
    KEY `id_lang` (`id_lang`),
    UNIQUE KEY `idx_contact_lang` (`id_contact`, `id_lang`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `mc_contact_page` (
    `id_page` smallint(3) unsigned NOT NULL AUTO_INCREMENT,
    `date_register` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id_page`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `mc_contact_page_content` (
    `id_content` smallint(3) unsigned NOT NULL AUTO_INCREMENT,
    `id_page` smallint(3) unsigned NOT NULL,
    `id_lang` smallint(3) unsigned NOT NULL,
    `name_page` varchar(175) DEFAULT NULL,
    `content_page` text,
    `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `published_page` smallint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id_content`),
    KEY `id_page` (`id_page`),
    KEY `id_lang` (`id_lang`),
    UNIQUE KEY `idx_page_lang` (`id_page`, `id_lang`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `mc_contact_config` (
    `id_config` smallint(3) unsigned NOT NULL AUTO_INCREMENT,
    `address_enabled` smallint(1) unsigned NOT NULL DEFAULT '0',
    `address_required` smallint(1) unsigned NOT NULL DEFAULT '0',
    PRIMARY KEY (`id_config`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `mc_contact_config` (`id_config`, `address_enabled`, `address_required`) VALUES
    (1, 0, 0);

ALTER TABLE `mc_contact_content`
    ADD CONSTRAINT `fk_contact_content_lang` FOREIGN KEY (`id_lang`) REFERENCES `mc_lang` (`id_lang`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_contact_content_contact` FOREIGN KEY (`id_contact`) REFERENCES `mc_contact` (`id_contact`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `mc_contact_page_content`
    ADD CONSTRAINT `fk_contact_page_lang` FOREIGN KEY (`id_lang`) REFERENCES `mc_lang` (`id_lang`) ON DELETE CASCADE ON UPDATE CASCADE,
    ADD CONSTRAINT `fk_contact_page_id` FOREIGN KEY (`id_page`) REFERENCES `mc_contact_page` (`id_page`) ON DELETE CASCADE ON UPDATE CASCADE;