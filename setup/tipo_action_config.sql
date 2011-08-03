CREATE TABLE IF NOT EXISTS `tipo_action_config` (
  `id` int(11) NOT NULL auto_increment,
  `tipo_name` varchar(255) default NULL,
  `action_name` varchar(255) default NULL,
  `action_alias` varchar(255) default NULL,
  `color` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ;
