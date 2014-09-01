-- Create syntax for TABLE 'billing_banks'
CREATE TABLE `billing_banks` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `bic` varchar(50) NOT NULL DEFAULT '',
  `name` varchar(250) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `bic` (`bic`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'billing_debit_cards'
CREATE TABLE `billing_debit_cards` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned DEFAULT NULL,
  `virtual_user_id` int(11) unsigned DEFAULT NULL,
  `holder` varchar(250) NOT NULL,
  `iban` varchar(50) NOT NULL DEFAULT '' COMMENT 'length varies on countries 16-30 incl. buffer',
  `bic` varchar(20) NOT NULL DEFAULT '' COMMENT 'length varies 8-11 incl. buffer',
  `user_has_accepted_direct_debit` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `virtual_user_id` (`virtual_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
