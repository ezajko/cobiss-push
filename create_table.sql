CREATE TABLE IF NOT EXISTS `gcm_users_cobiss` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gcm_regid` text,
  `acr` varchar(50) NOT NULL,
  `memid` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;



CREATE TABLE IF NOT EXISTS `apn_users_cobiss` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gcm_regid` text,
  `acr` varchar(50) NOT NULL,
  `memid` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;



CREATE TABLE IF NOT EXISTS `members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `acr` varchar(50) NOT NULL,
  `memid` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;