
CREATE TABLE `view` (
  `id` int(11) NOT NULL auto_increment,
  `view` varchar(255) default NULL,
  `regex` varchar(255) default NULL,
  `regex2` varchar(255) default NULL,
  `value` int(11) default '0',
  `color` varchar(255) default NULL,
  `enabled` int(11) default '0',
  PRIMARY KEY  (`id`)
) ;

INSERT INTO `view` VALUES (1, 'sudo', '(?P<p1>(?<=sudo: )\\w+).+(?P<p2>(?<=USER=)\\w+)', '(?P<p1>(?<=sudo: )\\w+).+(?P<p2>(?<=USER=)\\w+)', '0', '9999CC', '1') ;
INSERT INTO `view` VALUES (2, 'root by', '(?P<p1>su).+(?P<p2>user root).+(?P<p3>(?<=by)(?:\\W+\\w+))', '(?P<p1>su).+(?P<p2>user root).+(?P<p3>(?<=by)(?:\\W+\\w+))', '0', 'FF4444', '1') ;
INSERT INTO `view` VALUES (3, 'to root', '(?P<p1>su).+(?P<p3>(?<=from )(?:\\w+)).+(?P<p2>to root)', '(?P<p1>su).+(?P<p3>(?<=from )(?:\\w+)).+(?P<p2>to root)', '0', 'FF6666', '1') ;
