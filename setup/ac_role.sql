CREATE TABLE IF NOT EXISTS `ac_role` (
  `id` int(11) NOT NULL auto_increment,
  `name_role` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ;


INSERT INTO `ac_role` VALUES ('1', 'Administrator');
INSERT INTO `ac_role` VALUES ('2', 'Analyst');
