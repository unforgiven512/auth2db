CREATE TABLE IF NOT EXISTS `host_config` (
  `id` int(11) NOT NULL auto_increment,
  `hostname` varchar(255) default NULL,
  `ip` varchar(255) default NULL,
  `type` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ;
