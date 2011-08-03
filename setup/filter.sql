CREATE TABLE `filter` (
  `id` int(11) NOT NULL auto_increment,
  `filter` varchar(255) default NULL,
  `regex` varchar(255) default NULL,
  `value` int(11) default '0',
  `enabled` int(11) default '0',
  PRIMARY KEY  (`id`)
) ;
