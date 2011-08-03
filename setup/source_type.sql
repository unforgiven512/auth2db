CREATE TABLE `source_type` (
  `id` int(11) NOT NULL auto_increment,
  `source_type` varchar(255) default NULL,
  `format` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ;


INSERT INTO `source_type` VALUES (1,'syslog','__::__///[]:');
INSERT INTO `source_type` VALUES (2,'access_combined','_-_-_[//:::_-]');
INSERT INTO `source_type` VALUES (3,'apache_error','[___::_]_[]_');

