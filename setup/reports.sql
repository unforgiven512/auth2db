CREATE TABLE IF NOT EXISTS `reports` (
  `id` int(11) NOT NULL auto_increment,
  `report_name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `fields_values` varchar(255) NOT NULL,
  `where_values` varchar(255) default NULL,
  `fecha` datetime default NULL,
  PRIMARY KEY  (`id`)
) ;


INSERT INTO reports (report_name,description,fields_values,where_values,fecha) VALUES ('ROOT + SSHD','SSH Login by ROOT','fecha,server,tipo,action,usuario,ip,detalle','usuario = [root] and tipo = [sshd]','2008-04-20 23:45:25');
INSERT INTO reports (report_name,description,fields_values,where_values,fecha) VALUES ('SSH + Failed','Failed SSH Login','fecha,server,tipo,action,usuario,ip,detalle','detalle like [%Failed%]','2008-04-20 23:47:45');
