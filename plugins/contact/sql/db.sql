CREATE TABLE IF NOT EXISTS `mc_contact` (
  `id_contact` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `id_lang` smallint(3) unsigned NOT NULL,
  `mail_contact` varchar(45) NOT NULL,
  PRIMARY KEY (`id_contact`),
  KEY `id_lang` (`mail_contact`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mc_contact_config` (
  `id_config` smallint(3) unsigned NOT NULL AUTO_INCREMENT,
  `address_enabled` smallint(3) unsigned NOT NULL,
  `address_required` smallint(3) unsigned NOT NULL,
  PRIMARY KEY (`id_config`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;