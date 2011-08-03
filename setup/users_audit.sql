CREATE TABLE IF NOT EXISTS `users_audit` (
  `id` int(11) NOT NULL auto_increment,
  `username` varchar(255) NOT NULL,
  `remote_host` varchar(255) default NULL,
  `start_session` datetime default NULL,
  `close_session` datetime default NULL,
  `session_id` varchar(255) default NULL,
  `user_id` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ;

