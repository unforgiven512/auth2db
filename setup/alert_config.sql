CREATE TABLE IF NOT EXISTS `alert_config` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) default NULL,
  `server` varchar(255) default NULL,
  `server_select` varchar(255) default NULL,
  `log_type` varchar(255) default NULL,
  `severity` varchar(255) default NULL,
  `log_action` varchar(255) default NULL,
  `log_group` varchar(255) default NULL,
  `log_contains` varchar(255) default NULL,
  `log_contains_field` varchar(255) default NULL,
  `log_contains_regex` varchar(255) default NULL,
  `log_contains_list` varchar(255) default NULL,
  `log_exclude` varchar(255) default NULL,
  `log_exclude_field` varchar(255) default NULL,
  `log_exclude_regex` varchar(255) default NULL,
  `log_exclude_list` varchar(255) default NULL,
  `occurrences_number` int(11) default NULL,
  `occurrences_within` int(11) default NULL,
  `criticality` varchar(255) default NULL,
  `active_email` int(255) default NULL,
  `email` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ;

INSERT INTO `alert_config` VALUES ('1', 'SSH Failed', '0', 'auth2db', 'sshd', null, 'Failed', 'ip', '1', 'detalle', 'sshd.+failed', '', '0', 'usuario', '', '', '1', '1', 'High', '0', '');
