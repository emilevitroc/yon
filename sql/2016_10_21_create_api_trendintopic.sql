CREATE TABLE `api_trendingtopic` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `creation` DATETIME NOT NULL,
  `tag` VARCHAR(100) NOT NULL,
  `location` VARCHAR(100) NOT NULL,
  `range` DOUBLE DEFAULT NULL,
  `visible` TINYINT(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `visible` (`visible`)
) ENGINE=INNODB AUTO_INCREMENT=76 DEFAULT CHARSET=latin1;