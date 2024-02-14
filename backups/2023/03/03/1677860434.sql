-- mysqldump-php https://github.com/ifsnop/mysqldump-php
--
-- Host: localhost	Database: agriculture
-- ------------------------------------------------------
-- Server version 	10.4.24-MariaDB
-- Date: Fri, 03 Mar 2023 21:20:34 +0500

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40101 SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `activity_chart`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activity_chart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `activity_time` varchar(15) NOT NULL,
  `activity_type` tinyint(4) NOT NULL,
  `active_status` tinyint(4) NOT NULL DEFAULT 1,
  `delete_status` tinyint(4) NOT NULL DEFAULT 0,
  `time_created` varchar(15) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_chart`
--

LOCK TABLES `activity_chart` WRITE;
/*!40000 ALTER TABLE `activity_chart` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `activity_chart` VALUES (1,6,1,'March',0,1,0,'1677606099','2023-02-28 22:41:39');
/*!40000 ALTER TABLE `activity_chart` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `activity_chart` with 1 row(s)
--

--
-- Table structure for table `backups`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `backups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `extension_id` int(11) DEFAULT NULL,
  `backup_file_path` longtext NOT NULL,
  `backup_file_type` varchar(15) NOT NULL,
  `backup_type` tinyint(4) NOT NULL,
  `backup_method` varchar(15) NOT NULL,
  `active_status` tinyint(4) NOT NULL DEFAULT 1,
  `delete_status` tinyint(4) NOT NULL DEFAULT 0,
  `time_created` varchar(15) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `backups`
--

LOCK TABLES `backups` WRITE;
/*!40000 ALTER TABLE `backups` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `backups` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `backups` with 0 row(s)
--

--
-- Table structure for table `backup_meta`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `backup_meta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `backup_id` int(11) NOT NULL,
  `meta_key` varchar(225) NOT NULL,
  `meta_value` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `backup_meta`
--

LOCK TABLES `backup_meta` WRITE;
/*!40000 ALTER TABLE `backup_meta` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `backup_meta` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `backup_meta` with 0 row(s)
--

--
-- Table structure for table `configurations`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `configurations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `name` varchar(225) NOT NULL,
  `type` varchar(225) NOT NULL,
  `active_status` tinyint(4) NOT NULL DEFAULT 1,
  `delete_status` tinyint(4) NOT NULL DEFAULT 0,
  `time_created` varchar(15) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `configurations`
--

LOCK TABLES `configurations` WRITE;
/*!40000 ALTER TABLE `configurations` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `configurations` VALUES (4,6,'Wheet','type',1,0,'1676047970','2023-02-10 21:52:50'),(5,6,'Corn','type',1,0,'1676047975','2023-02-10 21:52:55'),(7,6,'Approved','class',1,0,'1676050159','2023-02-10 22:29:19'),(8,6,'Haripur','city',1,0,'1676111073','2023-02-11 15:24:33'),(9,6,'Mardan','city',1,0,'1676111078','2023-02-11 15:24:38'),(10,6,'SA-2023','variety',1,0,'1676461902','2023-02-15 16:51:42'),(11,6,'DI Khan','city',1,0,'1677823338','2023-03-03 11:02:18'),(12,6,'Malakand','city',1,0,'1677823346','2023-03-03 11:02:26');
/*!40000 ALTER TABLE `configurations` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `configurations` with 8 row(s)
--

--
-- Table structure for table `extensions`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `extensions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `name` varchar(225) NOT NULL,
  `city` varchar(225) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `active_status` tinyint(4) NOT NULL DEFAULT 1,
  `delete_status` tinyint(4) NOT NULL DEFAULT 0,
  `time_created` varchar(15) NOT NULL,
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `extensions`
--

LOCK TABLES `extensions` WRITE;
/*!40000 ALTER TABLE `extensions` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `extensions` VALUES (1,6,'Takht Bahi Extension','9',NULL,1,0,'1675858993','0000-00-00 00:00:00'),(3,7,'Haripur Extension','8',NULL,1,0,'1676658814','0000-00-00 00:00:00'),(4,6,'Tube Well Extension','8',NULL,1,0,'1677778219','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `extensions` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `extensions` with 3 row(s)
--

--
-- Table structure for table `extension_meta`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `extension_meta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `extension_id` int(11) NOT NULL,
  `meta_key` varchar(225) NOT NULL,
  `meta_value` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `extension_meta`
--

LOCK TABLES `extension_meta` WRITE;
/*!40000 ALTER TABLE `extension_meta` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `extension_meta` VALUES (1,1,'extension_phone_number','03358359438'),(2,1,'extension_manager_id',''),(3,3,'extension_phone_number','03209973190'),(4,3,'extension_manager_id','8'),(5,4,'extension_phone_number','03124613593'),(6,4,'extension_manager_id','12');
/*!40000 ALTER TABLE `extension_meta` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `extension_meta` with 6 row(s)
--

--
-- Table structure for table `farmers`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `farmers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `extension_id` int(11) DEFAULT NULL,
  `farmer_cnic` varchar(225) NOT NULL,
  `farmer_name` varchar(225) NOT NULL,
  `farmer_mobile_number` varchar(225) NOT NULL,
  `farmer_address` longtext NOT NULL,
  `active_status` tinyint(4) NOT NULL DEFAULT 1,
  `delete_status` tinyint(4) NOT NULL DEFAULT 0,
  `time_created` varchar(15) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `farmers`
--

LOCK TABLES `farmers` WRITE;
/*!40000 ALTER TABLE `farmers` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `farmers` VALUES (1,7,1,'1330234228883','Muhammad Saifullah Asif','03124613593','Mirpur',1,0,'1676462574','2023-02-15 17:02:54');
/*!40000 ALTER TABLE `farmers` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `farmers` with 1 row(s)
--

--
-- Table structure for table `finance`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `finance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `extension_id` int(11) NOT NULL,
  `amount` double NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `active_status` tinyint(4) NOT NULL DEFAULT 1,
  `delete_status` tinyint(4) NOT NULL DEFAULT 0,
  `time_created` varchar(15) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `finance`
--

LOCK TABLES `finance` WRITE;
/*!40000 ALTER TABLE `finance` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `finance` VALUES (2,1,963500,NULL,1,0,'1677313784','2023-02-25 13:29:44'),(3,3,398500,NULL,1,0,'1677351888','2023-02-26 00:04:48');
/*!40000 ALTER TABLE `finance` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `finance` with 2 row(s)
--

--
-- Table structure for table `notifications`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `extension_id` int(11) DEFAULT NULL,
  `notify_user_id` varchar(11) DEFAULT NULL,
  `notify_extension_id` varchar(11) DEFAULT NULL,
  `activity_id` int(11) DEFAULT NULL,
  `content` longtext NOT NULL,
  `notify_type` tinyint(4) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `seen_status` tinyint(4) NOT NULL DEFAULT 0,
  `seen_time` varchar(15) NOT NULL,
  `notify_time` varchar(15) NOT NULL,
  `active_status` tinyint(4) NOT NULL DEFAULT 1,
  `delete_status` tinyint(4) NOT NULL DEFAULT 0,
  `time_created` varchar(15) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `notifications` VALUES (31,6,NULL,'0','',1,'Be Ready For Procuring Stock Of Wheet',0,NULL,0,'','1677738600',1,0,'1677692159','2023-03-01 22:35:59'),(32,6,NULL,'0','',0,'Be Ready Financially For Procuring Stock In This Month',0,NULL,0,'','1677696885',1,0,'1677696885','2023-03-01 23:54:45');
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `notifications` with 2 row(s)
--

--
-- Table structure for table `notification_seen`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notification_seen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `notify_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `active_status` tinyint(4) NOT NULL DEFAULT 1,
  `delete_status` tinyint(4) NOT NULL DEFAULT 0,
  `time_created` varchar(15) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notification_seen`
--

LOCK TABLES `notification_seen` WRITE;
/*!40000 ALTER TABLE `notification_seen` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `notification_seen` VALUES (2,32,6,1,0,'1677697227','2023-03-02 00:00:27'),(3,31,6,1,0,'1677739061','2023-03-02 11:37:41');
/*!40000 ALTER TABLE `notification_seen` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `notification_seen` with 2 row(s)
--

--
-- Table structure for table `setting`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `meta_key` varchar(225) NOT NULL,
  `meta_value` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `setting`
--

LOCK TABLES `setting` WRITE;
/*!40000 ALTER TABLE `setting` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `setting` VALUES (1,'admin_email','muhammadsaifullahasif@gmail.com'),(2,'backup_cc_email','msaifullah7243@gmail.com'),(3,'activity_notifications',''),(4,'smtp_configuration','{\"email_address\":\"info@wirecoder.com\",\"email_password\":\"7S@!fullah\",\"email_host\":\"wirecoder.com\"}');
/*!40000 ALTER TABLE `setting` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `setting` with 4 row(s)
--

--
-- Table structure for table `stocks`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stocks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `extension_id` int(11) NOT NULL,
  `stock_source` varchar(225) NOT NULL,
  `supplier_info` varchar(225) NOT NULL,
  `lot_number` varchar(225) NOT NULL,
  `type` varchar(225) NOT NULL,
  `variety` varchar(225) NOT NULL,
  `class` varchar(225) NOT NULL,
  `stock_qty` varchar(225) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `active_status` tinyint(4) NOT NULL DEFAULT 1,
  `delete_status` tinyint(4) NOT NULL DEFAULT 0,
  `time_created` varchar(15) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stocks`
--

LOCK TABLES `stocks` WRITE;
/*!40000 ALTER TABLE `stocks` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `stocks` VALUES (15,7,3,'from_farmer','1330234228883','1234','1','1','1','100',NULL,1,0,'1677430263','2023-02-26 21:51:03'),(16,7,1,'from_farmer','1330234228883','724385524','2','2','1','500',NULL,1,0,'1677430384','2023-02-26 21:53:04');
/*!40000 ALTER TABLE `stocks` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `stocks` with 2 row(s)
--

--
-- Table structure for table `stock_class`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stock_class` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `class_name` varchar(225) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `active_status` tinyint(4) NOT NULL DEFAULT 1,
  `delete_status` tinyint(4) NOT NULL DEFAULT 0,
  `time_created` varchar(15) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_class`
--

LOCK TABLES `stock_class` WRITE;
/*!40000 ALTER TABLE `stock_class` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `stock_class` VALUES (1,7,'Basic',NULL,1,0,'1677088951','2023-02-22 23:02:31'),(2,7,'Pre Basic',NULL,1,0,'1677088956','2023-02-22 23:02:36'),(3,7,'Certified',NULL,1,0,'1677088960','2023-02-22 23:02:40'),(4,7,'Approved',NULL,1,0,'1677088970','2023-02-22 23:02:50');
/*!40000 ALTER TABLE `stock_class` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `stock_class` with 4 row(s)
--

--
-- Table structure for table `stock_cleaning`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stock_cleaning` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `extension_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `stock_id` int(11) NOT NULL,
  `processing_qty` varchar(15) NOT NULL,
  `active_status` tinyint(4) NOT NULL DEFAULT 1,
  `delete_status` tinyint(4) NOT NULL DEFAULT 0,
  `time_created` varchar(15) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_cleaning`
--

LOCK TABLES `stock_cleaning` WRITE;
/*!40000 ALTER TABLE `stock_cleaning` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `stock_cleaning` VALUES (35,7,1,15,27,'50',1,0,'1677433396','2023-02-26 22:43:16'),(36,7,1,16,25,'500',1,0,'1677518689','2023-02-27 22:24:49');
/*!40000 ALTER TABLE `stock_cleaning` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `stock_cleaning` with 2 row(s)
--

--
-- Table structure for table `stock_cleaning_meta`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stock_cleaning_meta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stock_cleaning_id` int(11) NOT NULL,
  `meta_key` varchar(225) NOT NULL,
  `meta_value` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=429 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_cleaning_meta`
--

LOCK TABLES `stock_cleaning_meta` WRITE;
/*!40000 ALTER TABLE `stock_cleaning_meta` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `stock_cleaning_meta` VALUES (405,35,'total_stock_qty','50'),(406,35,'processing_qty','50'),(407,35,'grade_1','45'),(408,35,'small_grains','2'),(409,35,'gundi','1'),(410,35,'broken','1'),(411,35,'straw','0.5'),(412,35,'dust','0.5'),(413,35,'other',''),(414,35,'labour_cost','3000'),(415,35,'packing_bag_cost','0'),(416,35,'miscellaneous_cost','0'),(417,36,'total_stock_qty','500'),(418,36,'processing_qty','500'),(419,36,'grade_1','480'),(420,36,'small_grains','4'),(421,36,'gundi','4'),(422,36,'broken','4'),(423,36,'straw','4'),(424,36,'dust','2'),(425,36,'other','2'),(426,36,'labour_cost','10000'),(427,36,'packing_bag_cost','5000'),(428,36,'miscellaneous_cost','0');
/*!40000 ALTER TABLE `stock_cleaning_meta` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `stock_cleaning_meta` with 24 row(s)
--

--
-- Table structure for table `stock_fumigation`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stock_fumigation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `extension_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `stock_id` int(11) NOT NULL,
  `processing_qty` varchar(15) NOT NULL,
  `active_status` tinyint(4) NOT NULL DEFAULT 1,
  `delete_status` tinyint(4) NOT NULL DEFAULT 0,
  `time_created` varchar(15) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_fumigation`
--

LOCK TABLES `stock_fumigation` WRITE;
/*!40000 ALTER TABLE `stock_fumigation` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `stock_fumigation` VALUES (24,7,3,15,24,'50',1,0,'1677430977','2023-02-26 22:02:57'),(28,7,3,15,26,'50',1,0,'1677433362','2023-02-26 22:42:42');
/*!40000 ALTER TABLE `stock_fumigation` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `stock_fumigation` with 2 row(s)
--

--
-- Table structure for table `stock_fumigation_meta`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stock_fumigation_meta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stock_fumigation_id` int(11) NOT NULL,
  `meta_key` varchar(225) NOT NULL,
  `meta_value` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=141 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_fumigation_meta`
--

LOCK TABLES `stock_fumigation_meta` WRITE;
/*!40000 ALTER TABLE `stock_fumigation_meta` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `stock_fumigation_meta` VALUES (116,24,'total_stock_qty','50'),(117,24,'processing_qty','50'),(118,24,'fumigation_cost','5000'),(119,24,'labour_cost','1000'),(120,24,'miscellaneous_cost','0'),(136,28,'total_stock_qty','50'),(137,28,'processing_qty','50'),(138,28,'fumigation_cost','5000'),(139,28,'labour_cost','3000'),(140,28,'miscellaneous_cost','0');
/*!40000 ALTER TABLE `stock_fumigation_meta` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `stock_fumigation_meta` with 10 row(s)
--

--
-- Table structure for table `stock_meta`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stock_meta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stock_id` int(11) NOT NULL,
  `meta_key` varchar(225) NOT NULL,
  `meta_value` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_meta`
--

LOCK TABLES `stock_meta` WRITE;
/*!40000 ALTER TABLE `stock_meta` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `stock_meta` VALUES (11,15,'form_1_number','123456789'),(12,15,'stock_price','2000'),(13,15,'stock_qty_price','200000'),(14,16,'form_1_number','123456789'),(15,16,'stock_price','1000'),(16,16,'stock_qty_price','500000');
/*!40000 ALTER TABLE `stock_meta` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `stock_meta` with 6 row(s)
--

--
-- Table structure for table `stock_price`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stock_price` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `stock_type_id` int(11) NOT NULL,
  `stock_class_id` int(11) NOT NULL,
  `stock_variety_id` int(11) DEFAULT NULL,
  `purchase_price` double NOT NULL,
  `sale_price` double NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `active_status` tinyint(4) NOT NULL DEFAULT 1,
  `delete_status` tinyint(4) NOT NULL DEFAULT 0,
  `time_created` varchar(15) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_price`
--

LOCK TABLES `stock_price` WRITE;
/*!40000 ALTER TABLE `stock_price` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `stock_price` VALUES (2,7,1,1,NULL,2000,2500,NULL,1,0,'1677089933','2023-02-22 23:18:53'),(3,7,1,2,NULL,2500,3000,NULL,1,0,'1677090061','2023-02-22 23:21:01'),(4,7,1,3,NULL,3000,3500,NULL,1,0,'1677090073','2023-02-22 23:21:13'),(5,7,1,4,NULL,3500,4000,NULL,1,0,'1677090083','2023-02-22 23:21:23'),(6,7,2,1,NULL,1000,1200,NULL,1,0,'1677430094','2023-02-26 21:48:14'),(7,7,2,2,NULL,1500,1700,NULL,1,0,'1677430107','2023-02-26 21:48:27'),(8,7,2,3,NULL,2000,2200,NULL,1,0,'1677430118','2023-02-26 21:48:38'),(9,7,2,4,NULL,2500,2700,NULL,1,0,'1677430130','2023-02-26 21:48:50'),(10,7,3,1,NULL,3000,4000,NULL,1,0,'1677430156','2023-02-26 21:49:16'),(11,7,3,2,NULL,3500,4500,NULL,1,0,'1677430168','2023-02-26 21:49:28'),(12,7,3,3,NULL,4000,5000,NULL,1,0,'1677430175','2023-02-26 21:49:35'),(13,7,3,4,NULL,4500,5500,NULL,1,0,'1677430210','2023-02-26 21:50:10');
/*!40000 ALTER TABLE `stock_price` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `stock_price` with 12 row(s)
--

--
-- Table structure for table `stock_transactions`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stock_transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `extension_id` int(11) NOT NULL,
  `stock_id` int(11) NOT NULL,
  `stock_qty` varchar(15) NOT NULL,
  `stock_status` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `active_status` tinyint(4) NOT NULL DEFAULT 1,
  `delete_status` tinyint(4) NOT NULL DEFAULT 0,
  `time_created` varchar(15) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_transactions`
--

LOCK TABLES `stock_transactions` WRITE;
/*!40000 ALTER TABLE `stock_transactions` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `stock_transactions` VALUES (24,7,3,15,'50',0,NULL,2,0,'1677430263','2023-02-26 21:51:03'),(25,7,1,16,'480',2,NULL,1,0,'1677430384','2023-02-26 21:53:04'),(26,7,1,15,'50',0,NULL,3,0,'1677430889','2023-02-26 22:01:29'),(27,7,1,15,'0',2,NULL,2,0,'1677431248','2023-02-26 22:07:28');
/*!40000 ALTER TABLE `stock_transactions` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `stock_transactions` with 4 row(s)
--

--
-- Table structure for table `stock_type`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stock_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `type` varchar(225) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `active_status` int(11) NOT NULL DEFAULT 1,
  `delete_status` int(11) NOT NULL DEFAULT 0,
  `time_created` varchar(15) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_type`
--

LOCK TABLES `stock_type` WRITE;
/*!40000 ALTER TABLE `stock_type` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `stock_type` VALUES (1,7,'Wheet',NULL,1,0,'1677087854','2023-02-22 22:44:14'),(2,7,'Corn',NULL,1,0,'1677087860','2023-02-22 22:44:20'),(3,7,'Kidney Been',NULL,1,0,'1677087866','2023-02-22 22:44:26');
/*!40000 ALTER TABLE `stock_type` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `stock_type` with 3 row(s)
--

--
-- Table structure for table `stock_variety`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stock_variety` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `stock_type_id` int(11) NOT NULL,
  `variety` varchar(225) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `active_status` tinyint(4) NOT NULL DEFAULT 1,
  `delete_status` tinyint(4) NOT NULL DEFAULT 0,
  `time_created` varchar(15) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_variety`
--

LOCK TABLES `stock_variety` WRITE;
/*!40000 ALTER TABLE `stock_variety` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `stock_variety` VALUES (1,7,1,'SA-2023',NULL,1,0,'1677088479','2023-02-22 22:54:39'),(2,7,2,'SA-7243',NULL,1,0,'1677088488','2023-02-22 22:54:48');
/*!40000 ALTER TABLE `stock_variety` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `stock_variety` with 2 row(s)
--

--
-- Table structure for table `supply`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `supply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `extension_id` int(11) NOT NULL,
  `receive_source` varchar(225) NOT NULL,
  `receiver_detail` varchar(225) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `stock_id` int(11) NOT NULL,
  `stock_qty` int(11) NOT NULL,
  `receiver_info` varchar(225) NOT NULL,
  `receiver_time_created` varchar(15) NOT NULL,
  `receive_status` int(11) NOT NULL,
  `active_status` tinyint(4) NOT NULL DEFAULT 1,
  `delete_status` tinyint(4) NOT NULL DEFAULT 0,
  `time_created` varchar(15) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `supply`
--

LOCK TABLES `supply` WRITE;
/*!40000 ALTER TABLE `supply` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `supply` VALUES (8,7,3,'other_extension','1',15,24,50,'7','1677431248',1,1,0,'1677431047','2023-02-26 22:04:07'),(9,7,1,'to_farmer','1330234228883',15,27,45,'1330234228883','1677482881',1,1,0,'1677482881','2023-02-27 12:28:01');
/*!40000 ALTER TABLE `supply` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `supply` with 2 row(s)
--

--
-- Table structure for table `supply_meta`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `supply_meta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `supply_id` int(11) NOT NULL,
  `meta_key` varchar(225) NOT NULL,
  `meta_value` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=103 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `supply_meta`
--

LOCK TABLES `supply_meta` WRITE;
/*!40000 ALTER TABLE `supply_meta` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `supply_meta` VALUES (79,8,'stock_lot_number','1234'),(80,8,'stock_type','1'),(81,8,'stock_variety','1'),(82,8,'stock_class','1'),(83,8,'stock_status','0'),(84,8,'stock_price',''),(85,8,'driver_cnic','1330234228883'),(86,8,'driver_name','Muhammad Saifullah Asif'),(87,8,'driver_mobile_number','03124613593'),(88,8,'driver_address','Mirpur'),(89,8,'vehicle_number','SA-7243'),(90,8,'labour_cost','1000'),(91,8,'packing_bag_cost','0'),(92,8,'miscellaneous_cost','0'),(93,9,'form_1_number','72438552'),(94,9,'stock_lot_number','1234'),(95,9,'stock_type','1'),(96,9,'stock_variety','1'),(97,9,'stock_class','1'),(98,9,'stock_status','2'),(99,9,'stock_sale_price','2500'),(100,9,'labour_cost','5000'),(101,9,'packing_bag_cost','0'),(102,9,'miscellaneous_cost','0');
/*!40000 ALTER TABLE `supply_meta` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `supply_meta` with 24 row(s)
--

--
-- Table structure for table `transactions`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `extension_id` int(11) NOT NULL,
  `finance_id` int(11) NOT NULL,
  `stock_id` int(11) DEFAULT NULL,
  `ref_id` int(11) NOT NULL,
  `amount` double NOT NULL,
  `type` int(11) NOT NULL,
  `trans_flow` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `active_status` tinyint(4) NOT NULL DEFAULT 1,
  `delete_status` tinyint(4) NOT NULL DEFAULT 0,
  `time_created` varchar(15) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transactions`
--

LOCK TABLES `transactions` WRITE;
/*!40000 ALTER TABLE `transactions` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `transactions` VALUES (49,7,3,3,15,24,202000,0,0,NULL,1,0,'1677430263','2023-02-26 21:51:03'),(50,7,3,3,16,25,509500,0,0,NULL,1,0,'1677430384','2023-02-26 21:53:04'),(52,7,3,3,15,24,6000,1,0,NULL,1,0,'1677430977','2023-02-26 22:02:57'),(53,7,3,3,15,8,1000,4,0,NULL,1,0,'1677431047','2023-02-26 22:04:07'),(54,7,3,3,15,8,125000,3,1,NULL,1,0,'1677431047','2023-02-26 22:04:07'),(55,7,1,2,15,27,126000,5,0,NULL,1,0,'1677431248','2023-02-26 22:07:28'),(63,7,3,3,15,28,8000,1,0,NULL,1,0,'1677433362','2023-02-26 22:42:42'),(64,7,1,2,15,35,3000,2,0,NULL,1,0,'1677433396','2023-02-26 22:43:16'),(65,7,1,2,15,9,5000,4,0,NULL,1,0,'1677482881','2023-02-27 12:28:01'),(66,7,1,2,15,9,112500,3,1,NULL,1,0,'1677482881','2023-02-27 12:28:01'),(67,7,1,2,16,36,15000,2,0,NULL,1,0,'1677518689','2023-02-27 22:24:49');
/*!40000 ALTER TABLE `transactions` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `transactions` with 11 row(s)
--

--
-- Table structure for table `transaction_meta`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transaction_meta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction_id` int(11) NOT NULL,
  `meta_key` varchar(225) NOT NULL,
  `meta_value` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=248 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaction_meta`
--

LOCK TABLES `transaction_meta` WRITE;
/*!40000 ALTER TABLE `transaction_meta` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `transaction_meta` VALUES (174,49,'total_amount','202000'),(175,49,'stock_price','200000'),(176,49,'labour_cost','1000'),(177,49,'miscellaneous_cost','500'),(178,49,'packing_bag_cost','500'),(179,50,'total_amount','509500'),(180,50,'stock_price','500000'),(181,50,'labour_cost','5000'),(182,50,'miscellaneous_cost','2000'),(183,50,'packing_bag_cost','2500'),(184,51,'total_amount','1000'),(185,51,'labour_cost','1000'),(186,51,'miscellaneous_cost','0'),(187,51,'packing_bag_cost','0'),(188,52,'total_amount','6000'),(189,52,'labour_cost','1000'),(190,52,'miscellaneous_cost','0'),(191,52,'fumigation_cost','5000'),(192,53,'total_amount','1000'),(193,53,'labour_cost','1000'),(194,53,'miscellaneous_cost','0'),(195,53,'packing_bag_cost','0'),(196,54,'total_amount','125000'),(197,54,'stock_price','125000'),(198,55,'total_amount','126000'),(199,55,'stock_price','125000'),(200,55,'labour_cost','1000'),(201,55,'miscellaneous_cost','0'),(202,56,'total_amount','7000'),(203,56,'labour_cost','2000'),(204,56,'miscellaneous_cost','0'),(205,56,'fumigation_cost','5000'),(206,57,'total_amount','7500'),(207,57,'labour_cost','2500'),(208,57,'miscellaneous_cost','0'),(209,57,'fumigation_cost','5000'),(210,58,'total_amount','5000'),(211,58,'labour_cost','5000'),(212,58,'miscellaneous_cost','0'),(213,58,'packing_bag_cost','0'),(214,59,'total_amount','7500'),(215,59,'labour_cost','5000'),(216,59,'miscellaneous_cost','0'),(217,59,'packing_bag_cost','2500'),(218,60,'total_amount','7500'),(219,60,'labour_cost','5000'),(220,60,'miscellaneous_cost','0'),(221,60,'packing_bag_cost','2500'),(222,61,'total_amount','7500'),(223,61,'labour_cost','5000'),(224,61,'miscellaneous_cost','0'),(225,61,'packing_bag_cost','2500'),(226,62,'total_amount','4500'),(227,62,'labour_cost','1500'),(228,62,'miscellaneous_cost','0'),(229,62,'fumigation_cost','3000'),(230,63,'total_amount','8000'),(231,63,'labour_cost','3000'),(232,63,'miscellaneous_cost','0'),(233,63,'fumigation_cost','5000'),(234,64,'total_amount','3000'),(235,64,'labour_cost','3000'),(236,64,'miscellaneous_cost','0'),(237,64,'packing_bag_cost','0'),(238,65,'total_amount','5000'),(239,65,'labour_cost','5000'),(240,65,'miscellaneous_cost','0'),(241,65,'packing_bag_cost','0'),(242,66,'total_amount','112500'),(243,66,'stock_price','112500'),(244,67,'total_amount','15000'),(245,67,'labour_cost','10000'),(246,67,'miscellaneous_cost','0'),(247,67,'packing_bag_cost','5000');
/*!40000 ALTER TABLE `transaction_meta` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `transaction_meta` with 74 row(s)
--

--
-- Table structure for table `users`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_login` varchar(225) NOT NULL,
  `user_pass` varchar(225) NOT NULL,
  `display_name` varchar(225) NOT NULL,
  `extension_id` int(11) DEFAULT NULL,
  `city` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `role` tinyint(4) NOT NULL DEFAULT 0,
  `type` tinyint(4) NOT NULL DEFAULT 1,
  `active_status` tinyint(4) NOT NULL DEFAULT 1,
  `delete_status` tinyint(4) NOT NULL DEFAULT 0,
  `time_created` varchar(15) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `users` VALUES (6,'muhammadsaifullahasif@gmail.com','$2y$10$I0qvTczbC2H8QCSGcfmFFObPuhrgoYB6gO9u5LIZ2merVEi0/GhW.','Muhammad Saifullah Asif',NULL,NULL,NULL,0,0,1,0,'1675775721','2023-02-07 18:15:21'),(7,'msaifullah7243@gmail.com','$2y$10$kTvVSbQFcrQTq/fEnDAUau9mFJ1XtK4EZYn9IppFK/YWNxlRAUZBG','Muhammad Saifullah',NULL,8,NULL,0,1,1,0,'1675853901','2023-02-08 15:58:22'),(8,'asfand5840@gmail.com','$2y$10$I0qvTczbC2H8QCSGcfmFFObPuhrgoYB6gO9u5LIZ2merVEi0/GhW.','Asfandyar Ahmed Awan',3,8,NULL,1,0,1,0,'1676658730','2023-02-17 23:32:10'),(12,'sheryar@gmail.com','$2y$10$cI9FFo7uyNOEda9bThUbueV9XQZHYYb1kBVboiidWea229uBIjtv2','Sheryar Ahmed Awan',4,8,NULL,1,1,1,0,'1677781239','2023-03-02 23:20:39');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `users` with 4 row(s)
--

--
-- Table structure for table `user_meta`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_meta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `meta_key` varchar(225) NOT NULL,
  `meta_value` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=107 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_meta`
--

LOCK TABLES `user_meta` WRITE;
/*!40000 ALTER TABLE `user_meta` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `user_meta` VALUES (41,6,'first_name','Muhammad'),(42,6,'last_name','Saifullah Asif'),(43,6,'phone_number','03124613593'),(44,6,'address','Mirpur'),(45,6,'email_address','muhammadsaifullahasif@gmail.com'),(46,6,'profile_image','{\"image_name\":\"1675775721_1670426569615.jpg\",\"image_path\":\"http://localhost/agriculture/media/users/1675775721_1670426569615.jpg\",\"image_size\":965791,\"image_type\":\"jpg\"}'),(47,6,'user_role',''),(48,6,'session_tokens',''),(49,7,'first_name','Muhammad'),(50,7,'last_name','Saifullah'),(51,7,'phone_number','03358359438'),(52,7,'address','Mirpur'),(53,7,'email_address','msaifullah7243@gmail.com'),(54,7,'profile_image','{\"image_name\":\"1675853901_1647847675944.jpg\",\"image_path\":\"http://localhost/agriculture/media/users/1675853901_1647847675944.jpg\",\"image_size\":313549,\"image_type\":\"jpg\"}'),(55,7,'user_role',''),(56,7,'session_tokens',''),(59,8,'first_name','Asfandyar'),(60,8,'last_name','Ahmed Awan'),(61,8,'phone_number','03209973190'),(62,8,'address','Haripur'),(63,8,'email_address','asfand5840@gmail.com'),(64,8,'profile_image','{\"image_name\":\"\",\"image_path\":\"\",\"image_size\":\"\",\"image_type\":\"\"}'),(65,8,'user_role',''),(66,8,'session_tokens',''),(91,12,'first_name','Sheryar'),(92,12,'last_name','Ahmed Awan'),(93,12,'phone_number','03462209029'),(94,12,'address','Haripur'),(95,12,'email_address','sheryar@gmail.com'),(96,12,'profile_image','{\"image_name\":\"\",\"image_path\":\"\",\"image_size\":\"\",\"image_type\":\"\"}'),(97,12,'user_role',''),(98,12,'session_tokens','');
/*!40000 ALTER TABLE `user_meta` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `user_meta` with 32 row(s)
--

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;
/*!40101 SET AUTOCOMMIT=@OLD_AUTOCOMMIT */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on: Fri, 03 Mar 2023 21:20:35 +0500
