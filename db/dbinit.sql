CREATE TABLE `users` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `country` varchar(3) DEFAULT NULL,
  `email` varchar(50) NOT NULL,
  `login` varchar(30) NOT NULL,
  `password` varchar(64) NOT NULL,
  `reg_date` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  UNIQUE KEY `login_UNIQUE` (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `disciplines` (
  id INT(6) AUTO_INCREMENT,
  dis VARCHAR(10),
  vid INT(5),
  PRIMARY KEY (id)
)  ENGINE=INNODB DEFAULT CHARSET=UTF8MB4;

CREATE TABLE `duties` (
  id INT(6) AUTO_INCREMENT,
  duts VARCHAR(2),
  vid INT(11),
  iduts VARCHAR(2),
  PRIMARY KEY (id)
)  ENGINE=INNODB DEFAULT CHARSET=UTF8MB4;

CREATE TABLE `volunteers` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `userID` int(6) unsigned NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `country` varchar(3) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `gender` varchar(1) DEFAULT NULL,
  `birthdate` datetime DEFAULT NULL,
  `nickname` varchar(30) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `reg_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `license` varchar(2) DEFAULT NULL,
  `onduts` varchar(50) DEFAULT NULL,
  `oiduts` varchar(50) DEFAULT NULL,
  `expect` varchar(80) DEFAULT NULL,
  `exper` varchar(50) DEFAULT NULL,
  `training` varchar(50) DEFAULT NULL,
  `mapper` varchar(50) DEFAULT NULL,
  `sprint` int(2) DEFAULT NULL,
  `forest` int(2) DEFAULT NULL,
  `coach` varchar(50) DEFAULT NULL,
  `nteam` int(2) DEFAULT NULL,
  `clubs` int(2) DEFAULT NULL,
  `si` int(2) DEFAULT NULL,
  `emit` int(2) DEFAULT NULL,
  `gps` int(2) DEFAULT NULL,
  `itex` varchar(50) DEFAULT NULL,
  `clubev` int(2) DEFAULT NULL,
  `localev` int(2) DEFAULT NULL,
  `natev` int(2) DEFAULT NULL,
  `hlev` int(2) DEFAULT NULL,
  `evorg` varchar(50) DEFAULT NULL,
  `documents` varchar(50) DEFAULT NULL,
  `oskills` varchar(50) DEFAULT NULL,
  `vid` int(11) DEFAULT NULL,
  `natc` int(2) DEFAULT NULL,
  `intc` int(2) DEFAULT NULL,
  `help` varchar(80) DEFAULT NULL,
  `othertime` int(1) DEFAULT NULL,
  `mtbo` int(2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user` (`userID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `langs` (
  id INT(6) AUTO_INCREMENT,
  lang VARCHAR(10),
  vid INT(5),
  level INT(2),
  PRIMARY KEY (id)
)  ENGINE=INNODB DEFAULT CHARSET=UTF8MB4;

CREATE TABLE `maps` (
  id INT(6) AUTO_INCREMENT,
  map LONGBLOB,
  vid INT(5),
  PRIMARY KEY (id)
)  ENGINE=INNODB DEFAULT CHARSET=UTF8MB4;
