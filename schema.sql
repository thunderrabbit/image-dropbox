--
-- Table structure for table `data`
--

DROP TABLE IF EXISTS `data`;
CREATE TABLE `data` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `entryid` mediumint(8) unsigned NOT NULL default '0',
  `filedata` blob NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `entry_idx` (`entryid`)
) ENGINE=MyISAM AUTO_INCREMENT=739 DEFAULT CHARSET=latin1;

--
-- Table structure for table `entries`
--

DROP TABLE IF EXISTS `entries`;
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
) ENGINE=MyISAM AUTO_INCREMENT=78 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tagmap`
--

DROP TABLE IF EXISTS `tagmap`;
CREATE TABLE `tagmap` (
  `tag` int(11) default NULL,
  `entry` int(11) default NULL,
  KEY `tag` (`tag`),
  KEY `entry` (`entry`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
CREATE TABLE `tags` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=45 DEFAULT CHARSET=latin1;
