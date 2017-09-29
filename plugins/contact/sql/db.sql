CREATE TABLE IF NOT EXISTS `mc_contact` (
  `id_contact` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `mail_contact` varchar(45) NOT NULL,
  PRIMARY KEY (`id_contact`),
  KEY `id_lang` (`mail_contact`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mc_contact_content` (
  `id_content` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `id_contact` smallint(5) unsigned NOT NULL,
  `id_lang` smallint(3) unsigned NOT NULL,
  `published_contact` smallint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_content`),
  KEY `id_contact` (`id_contact`,`id_lang`),
  KEY `id_lang` (`id_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE `mc_contact_content`
  ADD CONSTRAINT `mc_contact_content_ibfk_2` FOREIGN KEY (`id_lang`) REFERENCES `mc_lang` (`id_lang`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mc_contact_content_ibfk_1` FOREIGN KEY (`id_contact`) REFERENCES `mc_contact` (`id_contact`) ON DELETE CASCADE ON UPDATE CASCADE;

CREATE TABLE IF NOT EXISTS `mc_contact_config` (
  `id_config` smallint(3) unsigned NOT NULL AUTO_INCREMENT,
  `address_enabled` smallint(3) unsigned NOT NULL,
  `address_required` smallint(3) unsigned NOT NULL,
  PRIMARY KEY (`id_config`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;