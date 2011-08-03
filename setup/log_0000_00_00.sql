CREATE TABLE `log_0000_00_00` (
  `id` int(11) NOT NULL auto_increment,
  `fecha` datetime NOT NULL,
  `server` varchar(255) default NULL,
  `tipo` varchar(255) default NULL,
  `pid` int(11) NOT NULL,
  `action` varchar(255) default NULL,
  `usuario` varchar(255) default NULL,
  `ip` varchar(255) default NULL,
  `machine` varchar(255) default NULL,
  `detalle` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
);
