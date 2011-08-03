-- MySQL dump 10.11
--
-- Host: localhost    Database: authlog
-- ------------------------------------------------------
-- Server version	5.0.38-Ubuntu_0ubuntu1-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `event_config`
--

DROP TABLE IF EXISTS `event_config`;
CREATE TABLE `event_config` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) default NULL,
  `email_active` int(255) default NULL,
  `email` varchar(255) default NULL,
  `active` int(11) default NULL,
  `fecha` datetime default NULL,
  `ban_active` int(11) default NULL,
  `ban_period` varchar(255) default NULL,
  `ban_time` int(11) default NULL,
  `ban_check_period` varchar(255) default NULL,
  `ban_check_time` int(11) default NULL,
  `ban_check_maxretry` int(11) default NULL,
  `ban_check_interval` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `event_config`
--

LOCK TABLES `event_config` WRITE;
/*!40000 ALTER TABLE `event_config` DISABLE KEYS */;
INSERT INTO `event_config` VALUES (1,'Ban semana + Email',1,'ezequielvera@yahoo.com.ar',1,NULL,1,'DAY',7,'DAY',7,5,'DAY'),(2,'Ban Hora + Email',1,'ezequielvera@yahoo.com.ar',1,NULL,1,'HOUR',1,'HOUR',1,5,'ALL');
/*!40000 ALTER TABLE `event_config` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2007-06-11  4:36:08
