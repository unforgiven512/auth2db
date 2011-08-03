CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL auto_increment,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) default NULL,
  `access_level` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ;


INSERT INTO `users` VALUES (1,'admin','21232f297a57a5a743894a0e4a801fc3','admin@localhost.localdomain','1');
