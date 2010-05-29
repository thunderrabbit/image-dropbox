SET foreign_key_checks = 0;
DROP TABLE `comments`, `data`, `entries`, `namespace`, `tagmap`, `tags`, `thumbs`, `updates`;
SET foreign_key_checks = 1;

--
-- Table structure for table `namespace`
--
DROP TABLE IF EXISTS `namespace`;
CREATE TABLE `namespace` (
	`id` int(11) NOT NULL auto_increment,
	`name` varchar(255) not null,
	`protected` tinyint default 0,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB;

--
-- Table structure for table `entries`
--
DROP TABLE IF EXISTS `entries`;
CREATE TABLE `entries` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) default NULL,
  `type` varchar(25) default NULL,
  `size` bigint(20) default NULL,
  `width` int(11) default NULL,
  `safe` tinyint(4) default 1,
  `height` int(11) default NULL,
  `date` int(10) unsigned default NULL,
  `views` int(10) unsigned default 0,
  `ip` varchar(255) default NULL,
  `password` varchar(40) default NULL,
  `hash` varchar(40) default NULL,
  `parent` int(11) default NULL,
  `child` int(11) default NULL,
  `namespace` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY (`namespace`),
  FOREIGN KEY (namespace) REFERENCES namespace (id) on delete set null,
  FOREIGN KEY (parent) REFERENCES entries (id) on delete cascade,
  FOREIGN KEY (child) REFERENCES entries (id) on delete set null
) ENGINE=InnoDB;

--
-- Table structure for table `data`
--
DROP TABLE IF EXISTS `data`;
CREATE TABLE `data` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `entryid` int(11) NOT NULL,
  `filedata` blob NOT NULL,
  PRIMARY KEY  (`id`),
  FOREIGN KEY (entryid) REFERENCES entries (id) on delete cascade
) ENGINE=InnoDB;

--
-- Table structure for table `thumbs`
--
DROP TABLE IF EXISTS `thumbs`;
CREATE TABLE `thumbs` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`entry` int(11) NOT NULL,
	`custom` tinyint default 0,
	`data` blob,
	`size` int(11) NOT NULL,
	PRIMARY KEY (`id`),
	FOREIGN KEY (`entry`) REFERENCES `entries` (`id`) on delete cascade
) ENGINE=InnoDB;

--
-- Table structure for table `tags`
--
DROP TABLE IF EXISTS `tags`;
CREATE TABLE `tags` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) default NULL,
  `date` int(11) unsigned default 0,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB;


--
-- Table structure for table `tagmap`
--
DROP TABLE IF EXISTS `tagmap`;
CREATE TABLE `tagmap` (
  `tag` int(11) NOT NULL,
  `entry` int(11) NOT NULL,
  FOREIGN KEY (tag) REFERENCES tags (id) on delete cascade,
  FOREIGN KEY (entry) REFERENCES entries (id) on delete cascade
) ENGINE=InnoDB;

--
-- Table structure for table `updates`
--
DROP TABLE IF EXISTS `updates`;
CREATE TABLE `updates` (
	`id` int(11) NOT NULL auto_increment,
	`entry` int(11) default NULL,
	`ip` varchar(255) NOT NULL,
	`date` int(10) unsigned NOT NULL,
	`change` varchar(50) NOT NULL,
	`field` varchar(50) NOT NULL,
	`from` text NOT NULL,
	`to` text NOT NULL,
	FOREIGN KEY (entry) REFERENCES entries (id) on delete set null,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB;

--
-- Table structure for tables `comments`
--
DROP TABLE IF EXISTS `comments`;
CREATE TABLE `comments` (
	`id` int(11) NOT NULL auto_increment,
	`entry` int(11) NOT NULL,
	`date` int(10) unsigned NOT NULL,
	`ip` varchar(255) NOT NULL,
	`name` varchar(50) NOT NULL,
	`content` text NOT NULL,
	FOREIGN KEY (entry) REFERENCES entries (id) on delete cascade,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB;
