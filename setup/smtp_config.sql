CREATE TABLE IF NOT EXISTS `smtp_config` (
  `smtp_server` varchar(255) NOT NULL,
  `smtp_port` int(11) default NULL,
  `auth_active` int(11) default NULL,
  `auth_user` varchar(255) default NULL,
  `auth_pass` varchar(255) default NULL,
  `mail_from` varchar(255) default NULL,
  PRIMARY KEY  (`smtp_server`)
) ;


INSERT INTO `smtp_config` VALUES ('localhost',25,0,'','','auth2db@localhost.net');


