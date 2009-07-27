SET foreign_key_checks = 0;

--
-- Database
--
CREATE DATABASE IF NOT EXISTS `dropbox_dev`;

--
-- User access
--
GRANT SELECT,UPDATE,INSERT,DELETE,LOCK TABLES ON dropbox.* TO dropbox@localhost IDENTIFIED BY 'dropbox';

--
-- Table structure for table `data`
--
DROP TABLE IF EXISTS `dropbox_dev`.`data`;
CREATE TABLE `dropbox_dev`.`data` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `entryid` int(11) NOT NULL,
  `filedata` blob NOT NULL,
  PRIMARY KEY  (`id`),
  FOREIGN KEY (entryid) REFERENCES entries (id) on delete cascade
) ENGINE=InnoDB;

--
-- Table structure for table `namespace`
--
DROP TABLE IF EXISTS `dropbox_dev`.`namespace`;
CREATE TABLE `dropbox_dev`.`namespace` (
	`id` int(11) NOT NULL auto_increment,
	`name` varchar(255) not null,
	`protected` tinyint default 0,
	`password` varchar(40) default NULL,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB;

--
-- Table structure for table `entries`
--
DROP TABLE IF EXISTS `dropbox_dev`.`entries`;
CREATE TABLE `dropbox_dev`.`entries` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) default NULL,
  `type` varchar(25) default NULL,
  `size` bigint(20) default NULL,
  `width` int(11) default NULL,
  `safe` tinyint(4) default 1,
  `height` int(11) default NULL,
  `date` int(10) unsigned default NULL,
  `views` int(10) unsigned default NULL,
  `ip` varchar(255) default NULL,
  `password` varchar(40) default NULL,
  `hash` varchar(40) default NULL,
  `parent` int(11) default NULL,
  `child` int(11) default NULL,
  `namespace` int(11) default NULL,
  `user` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY (`namespace`),
  FOREIGN KEY (namespace) REFERENCES namespace (id) on delete set null,
  FOREIGN KEY (user) REFERENCES users (id) on delete set null,
  FOREIGN KEY (parent) REFERENCES entries (id) on delete cascade,
  FOREIGN KEY (child) REFERENCES entries (id) on delete set null
) ENGINE=InnoDB;

--
-- Table structure for table `thumbs`
--
DROP TABLE IF EXISTS `dropbox_dev`.`thumbs`;
CREATE TABLE `dropbox_dev`.`thumbs` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`entry` int(11) NOT NULL,
	`custom` tinyint default 0,
	`data` blob,
	`size` int(11) NOT NULL,
	PRIMARY KEY (`id`),
	FOREIGN KEY (`entry`) REFERENCES `entries` (`id`) on delete cascade
) ENGINE=InnoDB;

--
-- Table structure for table `tagmap`
--
DROP TABLE IF EXISTS `dropbox_dev`.`tagmap`;
CREATE TABLE `dropbox_dev`.`tagmap` (
  `tag` int(11) NOT NULL,
  `entry` int(11) NOT NULL,
  FOREIGN KEY (tag) REFERENCES tags (id) on delete cascade,
  FOREIGN KEY (entry) REFERENCES entries (id) on delete cascade
) ENGINE=InnoDB;

--
-- Table structure for table `tags`
--
DROP TABLE IF EXISTS `dropbox_dev`.`tags`;
CREATE TABLE `dropbox_dev`.`tags` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) default NULL,
  `date` int(11) unsigned default 0,
  `namespace` int(11) default NULL,
  FOREIGN KEY (namespace) REFERENCES namespace (id) on delete set null,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB;


--
-- Table structure for table `updates`
--
DROP TABLE IF EXISTS `dropbox_dev`.`updates`;
CREATE TABLE `dropbox_dev`.`updates` (
	`id` int(11) NOT NULL auto_increment,
	`entry` int(11) default NULL,
	`ip` varchar(255) NOT NULL,
	`date` int(10) unsigned NOT NULL,
	`change` varchar(50) NOT NULL,
	`from` text NOT NULL,
	`to` text NOT NULL,
  	`namespace` int(11) default NULL,
  	FOREIGN KEY (namespace) REFERENCES namespace (id) on delete set null,
	FOREIGN KEY (entry) REFERENCES entries (id) on delete set null,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB;

--
-- Table structure for table `comments`
--
DROP TABLE IF EXISTS `dropbox_dev`.`comments`;
CREATE TABLE `dropbox_dev`.`comments` (
	`id` int(11) NOT NULL auto_increment,
	`entry` int(11) NOT NULL,
	`date` int(10) unsigned NOT NULL,
	`ip` varchar(255) NOT NULL,
	`name` varchar(50) NOT NULL,
	`content` text NOT NULL,
  	`namespace` int(11) default NULL,
  	FOREIGN KEY (namespace) REFERENCES namespace (id) on delete set null,
	FOREIGN KEY (entry) REFERENCES entries (id) on delete cascade,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB;

--
-- Table structure for table `users`
--
DROP TABLE IF EXISTS `dropbox_dev`.`users`;
CREATE TABLE `dropbox_dev`.`users` (
	`id` int(11) NOT NULL auto_increment,
	`username` varchar(12) NOT NULL,
	`password` varchar(40) NOT NULL,
	`salt` varchar(40) NOT NULL,
	`email` varchar(255) NOT NULL,
	`email_hash` varchar(32) NOT NULL,
	`alias` varchar(50) NOT NULL,
	`joindate` int(11) unsigned NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB;

--
-- Table structure for table `namespacemap`
--
DROP TABLE IF EXISTS `dropbox_dev`.`namespacemap`;
CREATE TABLE `dropbox_dev`.`namespacemap` (
  `namespace` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `privileges` int(11) default 1,
  FOREIGN KEY (`namespace`) REFERENCES `namespace` (`id`) on delete cascade,
  FOREIGN KEY (`user`) REFERENCES `users` (`id`) on delete cascade
) ENGINE=InnoDB;

SET foreign_key_checks = 1;
