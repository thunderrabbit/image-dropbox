-- Use this file to update an existing database install to the schema of this version

SET foreign_key_checks = 0;

-- collection table doesn't exist create it
CREATE TABLE `collection` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(25) default NULL,
  `description` varchar(255) default NULL,
  `user` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `user` (`user`),
  CONSTRAINT `collection_ibfk_1` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

-- namespacemap table doesn't exist create it
CREATE TABLE `namespacemap` (
  `namespace` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `privileges` int(11) default '1',
  KEY `namespace` (`namespace`),
  KEY `user` (`user`),
  CONSTRAINT `namespacemap_ibfk_1` FOREIGN KEY (`namespace`) REFERENCES `namespace` (`id`) ON DELETE CASCADE,
  CONSTRAINT `namespacemap_ibfk_2` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- users table doesn't exist create it
CREATE TABLE `users` (
  `id` int(11) NOT NULL auto_increment,
  `username` varchar(25) NOT NULL,
  `password` varchar(40) NOT NULL,
  `salt` varchar(40) NOT NULL,
  `email` varchar(255) NOT NULL,
  `alias` varchar(50) NOT NULL,
  `email_hash` varchar(32) default NULL,
  `joindate` int(11) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB;


-- comments table has changes
ALTER TABLE `comments` ADD `namespace` int(11) default NULL;
ALTER TABLE `comments` ADD KEY `namespace` (`namespace`);
ALTER TABLE `comments` ADD CONSTRAINT `comments_update_1` FOREIGN KEY (`namespace`) REFERENCES `namespace` (`id`) ON DELETE SET NULL;

-- entries table has changes
ALTER TABLE `entries` ADD `user` int(11) default NULL;
ALTER TABLE `entries` ADD KEY `user` (`user`);
ALTER TABLE `entries` ADD CONSTRAINT `entries_update_1` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE SET NULL;

-- namespace table has changes
ALTER TABLE `namespace` ADD `password` varchar(40) default NULL;

-- tags table has changes
ALTER TABLE `tags` ADD `namespace` int(11) default NULL;
ALTER TABLE `tags` ADD KEY `namespace` (`namespace`);
ALTER TABLE `tags` ADD CONSTRAINT `tags_update_1` FOREIGN KEY (`namespace`) REFERENCES `namespace` (`id`) ON DELETE SET NULL;

-- thumbs table has changes
ALTER TABLE `thumbs` ADD `namespace` int(11) default NULL;

-- updates table has changes
ALTER TABLE `updates` ADD `namespace` int(11) default NULL;
ALTER TABLE `updates` ADD KEY `namespace` (`namespace`);
ALTER TABLE `updates` ADD CONSTRAINT `updates_update_1` FOREIGN KEY (`namespace`) REFERENCES `namespace` (`id`) ON DELETE SET NULL;

SET foreign_key_checks = 1;
