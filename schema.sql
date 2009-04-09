--
-- Database
--
CREATE DATABASE `dropbox`;

GRANT ALL ON dropbox.* TO dropbox@localhost IDENTIFIED BY 'dropbox';
--
-- Table structure for table `data`
--
CREATE TABLE `data` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `entryid` int(11) NOT NULL,
  `filedata` blob NOT NULL,
  PRIMARY KEY  (`id`),
  FOREIGN KEY (entryid) REFERENCES entries (id) on delete cascade
) ENGINE=InnoDB;

--
-- Table structure for table `entries`
--
CREATE TABLE `entries` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) default NULL,
  `type` varchar(25) default NULL,
  `size` bigint(20) default NULL,
  `thumb` blob,
  `width` int(11) default NULL,
  `height` int(11) default NULL,
  `date` int(10) unsigned default NULL,
  `views` int(10) unsigned default NULL,
  `ip` varchar(255) default NULL,
  `password` varchar(20) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB;

--
-- Table structure for table `tagmap`
--
CREATE TABLE `tagmap` (
  `tag` int(11) NOT NULL,
  `entry` int(11) NOT NULL,
  FOREIGN KEY (tag) REFERENCES tags (id) on delete cascade,
  FOREIGN KEY (entry) REFERENCES entries (id) on delete cascade
) ENGINE=InnoDB;

--
-- Table structure for table `tags`
--
CREATE TABLE `tags` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB;
