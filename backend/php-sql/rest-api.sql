
-- CREATE DATABASE IF NOT EXISTS `db_cordotest` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci	;
-- USE `db_cordotest`;

-- Delete biografi table
-- DROP TABLE `biografi`;

-- Create biografi table
CREATE TABLE IF NOT EXISTS `biografi` (
	`id` int(11) NOT NULL AUTO_INCREMENT ,
	`provinsi` tinytext NOT NULL ,
	`nama` tinytext NOT NULL ,
	PRIMARY KEY (`id`)
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


