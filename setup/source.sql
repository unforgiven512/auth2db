CREATE TABLE `source` (
  `id` int(11) NOT NULL auto_increment,
  `source` varchar(255) default NULL,
  `events` int(11) default '0',
  `enabled` int(11) default '0',
  `lastlog` text,
  PRIMARY KEY  (`id`)
) ;
