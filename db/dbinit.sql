CREATE TABLE `users` (
  id INT(6) AUTO_INCREMENT,
  name VARCHAR(20),
  country VARCHAR(20),
  email VARCHAR(30),
  login VARCHAR(20),
  password VARCHAR(64),
  reg_date DATETIME default now(),
  gender VARCHAR(1),
  birthdate DATETIME,
  nickname VARCHAR(20),
  phone VARCHAR(15),
  license INT(2),
  PRIMARY KEY (id)
)  ENGINE=INNODB DEFAULT CHARSET=UTF8MB4;

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

CREATE TABLE `experience` (
  id INT(6) AUTO_INCREMENT,
  onduts VARCHAR(50),
  oiduts VARCHAR(50),
  expect VARCHAR(80),
  exper VARCHAR(50),
  training VARCHAR(50),
  mapper VARCHAR(50),
  sprint INT(2),
  forest INT(2),
  coach VARCHAR(50),
  nteam INT(2),
  clubs INT(2),
  si INT(2),
  emit INT(2),
  gps INT(2),
  itex VARCHAR(50),
  clubev INT(2),
  localev INT(2),
  natev INT(2),
  hlev INT(2),
  evorg VARCHAR(50),
  documents VARCHAR(50),
  oskills VARCHAR(50),
  vid INT(11),
  natc INT(2),
  intc INT(2),
  help VARCHAR(80),
  othertime INT(1),
  mtbo INT(2),
  PRIMARY KEY (id)
)  ENGINE=INNODB DEFAULT CHARSET=UTF8MB4;

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
