CREATE TABLE IF NOT EXISTS `ac_item` (
  `id` int(11) NOT NULL auto_increment,
  `name_item` varchar(255) default NULL,
  `desc_item` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ;


INSERT INTO `ac_item` VALUES ('1', 'view_menu', null);
INSERT INTO `ac_item` VALUES ('2', 'view_adv_menu', null);
INSERT INTO `ac_item` VALUES ('3', 'alerts_menu', null);
INSERT INTO `ac_item` VALUES ('4', 'alerts_today', null);
INSERT INTO `ac_item` VALUES ('5', 'alerts_history', null);
INSERT INTO `ac_item` VALUES ('6', 'alerts_config', null);
INSERT INTO `ac_item` VALUES ('7', 'report_menu', null);
INSERT INTO `ac_item` VALUES ('8', 'statistics_menu', null);
INSERT INTO `ac_item` VALUES ('9', 'settings_menu', null);
INSERT INTO `ac_item` VALUES ('10', 'settings_config_hosts', null);
INSERT INTO `ac_item` VALUES ('11', 'settings_config_users', null);
INSERT INTO `ac_item` VALUES ('12', 'settings_config_reports', null);
INSERT INTO `ac_item` VALUES ('13', 'settings_config_alerts', null);
INSERT INTO `ac_item` VALUES ('14', 'settings_config_mailserver', null);
INSERT INTO `ac_item` VALUES ('15', 'settings_config_action', null);
