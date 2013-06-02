
CREATE TABLE IF NOT EXISTS `users` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `acr` varchar(50) NOT NULL,
  `memid` varchar(255) NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `messages` (
	`mid` int(11) NOT NULL AUTO_INCREMENT,
	`uid` int(11),
	`title` text,
	`message` text,
	`msgread` INT(10) UNSIGNED NOT NULL,
	`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`mid`),
	FOREIGN KEY (`uid`) REFERENCES users(uid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `gcm_tokens` (
	`tid` int(11) NOT NULL AUTO_INCREMENT,
	`uid` int(11),
	`token` text,
	PRIMARY KEY (`gcmid`),
	FOREIGN KEY (`uid`) REFERENCES users(uid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `apn_tokens` (
	`tid` int(11) NOT NULL AUTO_INCREMENT,
	`uid` int(11),
	`token` text,
	PRIMARY KEY (`apnid`),
	FOREIGN KEY (`uid`) REFERENCES users(uid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;