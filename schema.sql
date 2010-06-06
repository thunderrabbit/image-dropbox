SET foreign_key_checks = 0;

--
-- Table structure for table `data`
--
CREATE TABLE `monty_data` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `entryid` int(11) NOT NULL,
  `filedata` blob NOT NULL,
  PRIMARY KEY  (`id`),
  FOREIGN KEY (entryid) REFERENCES monty_entries (id) on delete cascade
) ENGINE=InnoDB;

--
-- Table structure for table `namespace`
--
CREATE TABLE `monty_namespace` (
	`id` int(11) NOT NULL auto_increment,
	`name` varchar(255) not null,
	`protected` tinyint default 0,
	`password` varchar(40) default NULL,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB;

--
-- Table structure for table `collection`
--
CREATE TABLE `monty_collection` (
	`id` int(11) NOT NULL auto_increment,
	`title` varchar(25) default NULL,
	`description` varchar(255) default NULL,
	`user` int(11) default NULL,
	PRIMARY KEY (`id`),
	FOREIGN KEY (`user`) REFERENCES `monty_users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

--
-- Table structure for table `entries`
--
CREATE TABLE `monty_entries` (
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
  `user` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY (`namespace`),
  FOREIGN KEY (namespace) REFERENCES monty_namespace (id) on delete set null,
  FOREIGN KEY (user) REFERENCES monty_users (id) on delete set null,
  FOREIGN KEY (parent) REFERENCES monty_entries (id) on delete cascade,
  FOREIGN KEY (child) REFERENCES monty_entries (id) on delete set null
) ENGINE=InnoDB;

--
-- Table structure for table `thumbs`
--
CREATE TABLE `monty_thumbs` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`entry` int(11) NOT NULL,
	`custom` tinyint default 0,
	`data` blob,
	`size` int(11) NOT NULL,
	PRIMARY KEY (`id`),
	FOREIGN KEY (`entry`) REFERENCES `monty_entries` (`id`) on delete cascade
) ENGINE=InnoDB;

--
-- Table structure for table `tagmap`
--
CREATE TABLE `monty_tagmap` (
  `tag` int(11) NOT NULL,
  `entry` int(11) NOT NULL,
  FOREIGN KEY (tag) REFERENCES monty_tags (id) on delete cascade,
  FOREIGN KEY (entry) REFERENCES monty_entries (id) on delete cascade
) ENGINE=InnoDB;

--
-- Table structure for table `tags`
--
CREATE TABLE `monty_tags` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) default NULL,
  `date` int(11) unsigned default 0,
  `namespace` int(11) default NULL,
  FOREIGN KEY (namespace) REFERENCES monty_namespace (id) on delete set null,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB;


--
-- Table structure for table `updates`
--
CREATE TABLE `monty_updates` (
	`id` int(11) NOT NULL auto_increment,
	`entry` int(11) default NULL,
	`ip` varchar(255) NOT NULL,
	`date` int(10) unsigned NOT NULL,
	`change` varchar(50) NOT NULL,
	`field` varchar(50) NOT NULL,
	`from` text NOT NULL,
	`to` text NOT NULL,
  	`namespace` int(11) default NULL,
  	FOREIGN KEY (namespace) REFERENCES monty_namespace (id) on delete set null,
	FOREIGN KEY (entry) REFERENCES monty_entries (id) on delete set null,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB;

--
-- Table structure for table `comments`
--
CREATE TABLE `monty_comments` (
	`id` int(11) NOT NULL auto_increment,
	`entry` int(11) NOT NULL,
	`date` int(10) unsigned NOT NULL,
	`ip` varchar(255) NOT NULL,
	`name` varchar(50) NOT NULL,
	`content` text NOT NULL,
  	`namespace` int(11) default NULL,
  	FOREIGN KEY (namespace) REFERENCES monty_namespace (id) on delete set null,
	FOREIGN KEY (entry) REFERENCES monty_entries (id) on delete cascade,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB;

--
-- Table structure for table `users`
--
CREATE TABLE `monty_users` (
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
CREATE TABLE `monty_namespacemap` (
  `namespace` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `privileges` int(11) default 1,
  FOREIGN KEY (`namespace`) REFERENCES `monty_namespace` (`id`) on delete cascade,
  FOREIGN KEY (`user`) REFERENCES `monty_users` (`id`) on delete cascade
) ENGINE=InnoDB;

SET foreign_key_checks = 1;
