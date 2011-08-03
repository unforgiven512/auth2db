CREATE TABLE IF NOT EXISTS `alert` (
  `id` int(11) NOT NULL auto_increment,
  `id_alert_config` int(11) default NULL,
  `id_log` int(11) default NULL,
  `alert_name` varchar(255) default NULL,
  `hostname` varchar(255) default NULL,
  `criticality` varchar(255) default NULL,
  `severity` varchar(255) default NULL,
  `occurrences_number` int(11) default NULL,
  `detalle` text,
  `notified_time` datetime default NULL,
  PRIMARY KEY  (`id`)
) ;
