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

CREATE TABLE `volunteers` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `userID` int(6) unsigned NOT NULL,
  `reg_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `name` varchar(60) DEFAULT NULL,
  `country` varchar(3) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `gender` varchar(1) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `nickname` varchar(30) DEFAULT NULL,
  `license` tinyint(1) DEFAULT 0,
  `footO` tinyint(1) DEFAULT NULL,
  `skiO` tinyint(1) DEFAULT NULL,
  `mtbO` tinyint(1) DEFAULT NULL,
  `trailO` tinyint(1) DEFAULT NULL,
  `startO` int(11) DEFAULT NULL,
  `club` varchar(60) DEFAULT NULL,
  `competitorExp` json DEFAULT NULL,
  `languages` json DEFAULT NULL,
  `preferredContinents` json DEFAULT NULL,
  `active` tinyint(1) DEFAULT 1,
  `maxWorkDuration` int(11) DEFAULT NULL,
  `mappingSkilled` tinyint(1) DEFAULT NULL,
  `coachSkilled` tinyint(1) DEFAULT NULL,
  `itSkilled` tinyint(1) DEFAULT NULL,
  `eventSkilled` tinyint(1) DEFAULT NULL,
  `teacherSkilled` tinyint(1) DEFAULT NULL,
  `mappingDesc` json DEFAULT NULL,
  `coachDesc` json DEFAULT NULL,
  `itDesc` json DEFAULT NULL,
  `eventDesc` json DEFAULT NULL,
  `teacherDesc` json DEFAULT NULL,
  `otherSkills` longtext,
  `oworkLocalExp` json DEFAULT NULL,
  `oworkInternationalExp` json DEFAULT NULL,
  `helpDesc` longtext,
  `expectations` longtext,
  `abroadExp` longtext,
  `learning` longtext,
  `maps` json DEFAULT NULL,
  `excluded` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `user` (`userID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `contacts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(45) NOT NULL,
  `fromID` int(10) unsigned NOT NULL,
  `toID` int(10) unsigned NOT NULL,
  `key` varchar(64) NOT NULL,
  `status` varchar(45) NOT NULL,
  `authorID` int(11) NOT NULL,
  `addDate` datetime DEFAULT CURRENT_TIMESTAMP,
  `editDate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_UNIQUE` (`key`),
  KEY `VOLUNTEER` (`fromID`),
  KEY `AUTHOR` (`authorID`),
  KEY `types` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `projects` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userID` int(10) unsigned NOT NULL,
  `name` varchar(100) NOT NULL,
  `status` varchar(45) DEFAULT NULL,
  `webpage` varchar(150) DEFAULT NULL,
  `region` varchar(45) DEFAULT NULL,
  `contact` varchar(45) DEFAULT NULL,
  `position` varchar(45) DEFAULT NULL,
  `country` varchar(45) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `phone` varchar(45) DEFAULT NULL,
  `language` varchar(45) DEFAULT NULL,
  `place` varchar(45) DEFAULT NULL,
  `startDate` date DEFAULT NULL,
  `offer` json DEFAULT NULL,
  `footO` tinyint(1) DEFAULT NULL,
  `mtbO` tinyint(1) DEFAULT NULL,
  `skiO` tinyint(1) DEFAULT NULL,
  `trailO` tinyint(1) DEFAULT NULL,
  `mappingDesc` json DEFAULT NULL,
  `coachDesc` json DEFAULT NULL,
  `itDesc` json DEFAULT NULL,
  `eventDesc` json DEFAULT NULL,
  `teacherDesc` json DEFAULT NULL,
  `oworkLocalExp` json DEFAULT NULL,
  `oworkInternationalExp` json DEFAULT NULL,
  `details` longtext,
  `gender` varchar(2) DEFAULT NULL,
  `age` varchar(15) DEFAULT NULL,
  `license` tinyint(1) DEFAULT NULL,
  `expectedLanguage` varchar(45) DEFAULT NULL,
  `expectedDisciplines` json DEFAULT NULL,
  `expirience` json DEFAULT NULL,
  `other` text,
  `active` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `user` (`userID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `hosts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userID` int(10) unsigned NOT NULL,
  `name` varchar(100) NOT NULL,
  `country` varchar(45) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `languages` json DEFAULT NULL,
  `active` tinyint(1) DEFAULT 1,
  `maxDuration` varchar(45) DEFAULT NULL,
  `offer` json DEFAULT NULL,
  `hostDesc` longtext,
  `guestExpectations` longtext,
  `contacts` json DEFAULT NULL, -- skype, tg, mail, etc
  PRIMARY KEY (`id`),
  KEY `user` (`userID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `guests` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userID` int(10) unsigned NOT NULL,
  `name` varchar(100) NOT NULL,
  `country` varchar(45) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `languages` json DEFAULT NULL,
  `gender` varchar(1) DEFAULT NULL,
  `active` tinyint(1) DEFAULT 1,
  `startO` int(11) DEFAULT NULL,
  `club` varchar(60) DEFAULT NULL,
  `competitorExp` json DEFAULT NULL,
  `oExpectations` longtext DEFAULT NULL,
  `motivation` longtext DEFAULT NULL,
  `healthRestrictions` longtext DEFAULT NULL,
  `offer` longtext DEFAULT NULL,
  `contacts` json DEFAULT NULL, -- skype, tg, mail, etc
  `other` text,
  PRIMARY KEY (`id`),
  KEY `user` (`userID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
