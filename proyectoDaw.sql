-- MySQL dump 10.13  Distrib 5.7.28, for Linux (x86_64)
--
-- Host: localhost    Database: proyectoDaw
-- ------------------------------------------------------
-- Server version	5.7.28-0ubuntu0.18.04.4

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
-- Table structure for table `migration_versions`
--

DROP TABLE IF EXISTS `migration_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migration_versions` (
  `version` varchar(14) COLLATE utf8mb4_unicode_ci NOT NULL,
  `executed_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migration_versions`
--

LOCK TABLES `migration_versions` WRITE;
/*!40000 ALTER TABLE `migration_versions` DISABLE KEYS */;
INSERT INTO `migration_versions` VALUES ('20200410174717','2020-04-10 17:47:57'),('20200413203840','2020-04-13 20:38:51'),('20200419105110','2020-04-19 10:51:23'),('20200419114148','2020-04-19 11:41:53'),('20200420161045','2020-04-20 16:11:41'),('20200420175658','2020-04-20 17:57:42'),('20200422162426','2020-04-22 16:24:33');
/*!40000 ALTER TABLE `migration_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `provincias`
--

DROP TABLE IF EXISTS `provincias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `provincias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `provincia` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `provincias`
--

LOCK TABLES `provincias` WRITE;
/*!40000 ALTER TABLE `provincias` DISABLE KEYS */;
INSERT INTO `provincias` VALUES (1,'Araba/Álava'),(2,'Albacete'),(3,'Alicante/Alacant'),(4,'Almería'),(5,'Ávila'),(6,'Badajoz'),(7,'Islas Baleares/Illes Balears'),(8,'Barcelona'),(9,'Burgos'),(10,'Cáceres'),(11,'Cádiz'),(12,'Castellón/Castelló'),(13,'Ciudad Real'),(14,'Córdoba'),(15,'La Coruña'),(16,'Cuenca'),(17,'Girona'),(18,'Granada'),(19,'Guadalajara'),(20,'Gipuzkoa'),(21,'Huelva'),(22,'Huesca'),(23,'Jaén'),(24,'León'),(25,'Lleida'),(26,'La Rioja'),(27,'Lugo'),(28,'Madrid'),(29,'Málaga'),(30,'Murcia'),(31,'Navarra'),(32,'Ourense'),(33,'Asturias'),(34,'Palencia'),(35,'Las Palmas'),(36,'Pontevedra'),(37,'Salamanca'),(38,'Santa Cruz de Tenerife'),(39,'Cantabria'),(40,'Segovia'),(41,'Sevilla'),(42,'Soria'),(43,'Tarragona'),(44,'Teruel'),(45,'Toledo'),(46,'Valencia/València'),(47,'Valladolid'),(48,'Bizkaia'),(49,'Zamora'),(50,'Zaragoza'),(51,'Ceuta'),(52,'Melilla');
/*!40000 ALTER TABLE `provincias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `publicacion`
--

DROP TABLE IF EXISTS `publicacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `publicacion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `descripcion` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `imagenes` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `nombre_publicacion` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `activo` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_62F2085FDB38439E` (`usuario_id`),
  CONSTRAINT `FK_62F2085FDB38439E` FOREIGN KEY (`usuario_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `publicacion`
--

LOCK TABLES `publicacion` WRITE;
/*!40000 ALTER TABLE `publicacion` DISABLE KEYS */;
INSERT INTO `publicacion` VALUES (7,32,'Descripción 1','a:2:{s:7:\"imagen1\";s:70:\"/home/dwes/proyectoFinal/public/img/user1@gmail.com/prueba/prueba1.jpg\";s:7:\"imagen2\";s:70:\"/home/dwes/proyectoFinal/public/img/user1@gmail.com/prueba/prueba2.jpg\";}','Titulo 1',1),(8,33,'Descripción 2','a:2:{s:7:\"imagen1\";s:70:\"/home/dwes/proyectoFinal/public/img/user2@gmail.com/prueba/prueba1.jpg\";s:7:\"imagen2\";s:70:\"/home/dwes/proyectoFinal/public/img/user2@gmail.com/prueba/prueba2.jpg\";}','Titulo 2',1);
/*!40000 ALTER TABLE `publicacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nick_name` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_login` date NOT NULL,
  `rol` int(11) DEFAULT NULL,
  `provincia_id` int(11) DEFAULT NULL,
  `imagen_perfil` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`),
  KEY `IDX_8D93D6494E7121AF` (`provincia_id`),
  CONSTRAINT `FK_8D93D6494E7121AF` FOREIGN KEY (`provincia_id`) REFERENCES `provincias` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (32,'user1@gmail.com','[]','$argon2id$v=19$m=65536,t=4,p=1$3KGBMtvmg9pdXCIw7G4wMQ$ZYZRl34WrgydKlpomci5B/uMc6EISznh3uh17O2L9H0','user1','2020-04-22',NULL,NULL,'https://placekitten.com/640/360'),(33,'user2@gmail.com','[]','$argon2id$v=19$m=65536,t=4,p=1$Gy5Xv1vWXCbAV8hyuaVMeA$c3NovRPILkGjJiwkCSjz+fNY5c0W2osEBlTaLZ4tgQo','user2','2020-04-22',NULL,NULL,'https://placekitten.com/640/360'),(34,'user3@gmail.com','[]','$argon2id$v=19$m=65536,t=4,p=1$TpRzlOvs+5/AYKBFXFnJlg$EwBheWuj4b/uwnbP36nGYffRIqEAqpZs8dZgZkR+So4','user3','2020-04-22',NULL,NULL,'https://placekitten.com/640/360');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-04-22 19:13:28
