CREATE TABLE IF NOT EXISTS `action_config` (
  `id` int(11) NOT NULL auto_increment,
  `action_name` varchar(255) default NULL,
  `action_alias` varchar(255) default NULL,
  `color` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ;

INSERT INTO `action_config` VALUES (1,'Accepted','','00FF22'),(2,'Failed','','FF9999'),(3,'closed','','EEEEEE'),(4,'opened','','00FF99'),(5,'no such user','','FFBBBB'),(6,'Successful','','00FE22'),(7,'failure','','FF9999');
