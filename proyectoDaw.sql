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
-- Table structure for table `chat`
--

DROP TABLE IF EXISTS `chat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `chat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario1_id` int(11) NOT NULL,
  `usuario2_id` int(11) NOT NULL,
  `topic` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_659DF2AAC100AB93` (`usuario1_id`),
  KEY `IDX_659DF2AAD3B5047D` (`usuario2_id`),
  CONSTRAINT `FK_659DF2AAC100AB93` FOREIGN KEY (`usuario1_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_659DF2AAD3B5047D` FOREIGN KEY (`usuario2_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chat`
--

LOCK TABLES `chat` WRITE;
/*!40000 ALTER TABLE `chat` DISABLE KEYS */;
/*!40000 ALTER TABLE `chat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `etiquetas`
--

DROP TABLE IF EXISTS `etiquetas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `etiquetas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `etiqueta` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=97 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `etiquetas`
--

LOCK TABLES `etiquetas` WRITE;
/*!40000 ALTER TABLE `etiquetas` DISABLE KEYS */;
INSERT INTO `etiquetas` VALUES (94,'Deporte'),(95,'Telefonía'),(96,'Tecnología');
/*!40000 ALTER TABLE `etiquetas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `filtros`
--

DROP TABLE IF EXISTS `filtros`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `filtros` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `provincia_id` int(11) DEFAULT NULL,
  `usuario_prop_id` int(11) NOT NULL,
  `orden_fecha` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `etiqueta_id` int(11) DEFAULT NULL,
  `tipo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `titulo` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `activo` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_9E72BE644E7121AF` (`provincia_id`),
  KEY `IDX_9E72BE64A79555FA` (`usuario_prop_id`),
  KEY `IDX_9E72BE64D53DA3AB` (`etiqueta_id`),
  CONSTRAINT `FK_9E72BE644E7121AF` FOREIGN KEY (`provincia_id`) REFERENCES `provincias` (`id`),
  CONSTRAINT `FK_9E72BE64A79555FA` FOREIGN KEY (`usuario_prop_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_9E72BE64D53DA3AB` FOREIGN KEY (`etiqueta_id`) REFERENCES `etiquetas` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `filtros`
--

LOCK TABLES `filtros` WRITE;
/*!40000 ALTER TABLE `filtros` DISABLE KEYS */;
/*!40000 ALTER TABLE `filtros` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `chat_id` int(11) NOT NULL,
  `mensajes` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `time_envio` datetime NOT NULL,
  `publicacion_id` int(11) DEFAULT NULL,
  `visto` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_DB021E96DB38439E` (`usuario_id`),
  KEY `IDX_DB021E961A9A7125` (`chat_id`),
  KEY `IDX_DB021E969ACBB5E7` (`publicacion_id`),
  CONSTRAINT `FK_DB021E961A9A7125` FOREIGN KEY (`chat_id`) REFERENCES `chat` (`id`),
  CONSTRAINT `FK_DB021E969ACBB5E7` FOREIGN KEY (`publicacion_id`) REFERENCES `publicacion` (`id`),
  CONSTRAINT `FK_DB021E96DB38439E` FOREIGN KEY (`usuario_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;
UNLOCK TABLES;

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
INSERT INTO `migration_versions` VALUES ('20200410174717','2020-04-10 17:47:57'),('20200413203840','2020-04-13 20:38:51'),('20200419105110','2020-04-19 10:51:23'),('20200419114148','2020-04-19 11:41:53'),('20200420161045','2020-04-20 16:11:41'),('20200420175658','2020-04-20 17:57:42'),('20200422162426','2020-04-22 16:24:33'),('20200422173245','2020-04-22 17:35:36'),('20200423171025','2020-04-23 17:10:39'),('20200423172557','2020-04-23 17:26:27'),('20200428203819','2020-04-28 20:38:38'),('20200430181744','2020-04-30 18:17:56'),('20200502182129','2020-05-02 18:21:42'),('20200502184511','2020-05-02 18:45:38'),('20200502185303','2020-05-02 18:53:10'),('20200503181854','2020-05-03 18:19:40'),('20200515152628','2020-05-15 15:26:43'),('20200516111517','2020-05-16 11:15:28'),('20200518195132','2020-05-18 19:51:45'),('20200519081918','2020-05-19 08:19:34'),('20200519082247','2020-05-19 08:23:01'),('20200519082354','2020-05-19 08:24:00'),('20200519094659','2020-05-19 09:47:04'),('20200520110014','2020-05-20 11:00:25');
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
  `activo` tinyint(1) NOT NULL,
  `fecha_publicacion` datetime NOT NULL,
  `titulo` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_62F2085FDB38439E` (`usuario_id`),
  CONSTRAINT `FK_62F2085FDB38439E` FOREIGN KEY (`usuario_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `publicacion`
--

LOCK TABLES `publicacion` WRITE;
/*!40000 ALTER TABLE `publicacion` DISABLE KEYS */;
INSERT INTO `publicacion` VALUES (61,111,'Descripción 1','a:2:{i:0;s:42:\"img/user1@gmail.com/Telefono/Telefono1.jpg\";i:1;s:42:\"img/user1@gmail.com/Telefono/Telefono2.jpg\";}',1,'2010-05-01 00:00:00','Telefono','Producto'),(62,112,'Descripción 2','a:2:{i:0;s:38:\"img/user2@gmail.com/Pelota/Pelota1.jpg\";i:1;s:38:\"img/user2@gmail.com/Pelota/Pelota2.jpg\";}',1,'2010-05-02 00:00:00','Pelota','Producto');
/*!40000 ALTER TABLE `publicacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `publicacion_etiquetas`
--

DROP TABLE IF EXISTS `publicacion_etiquetas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `publicacion_etiquetas` (
  `publicacion_id` int(11) NOT NULL,
  `etiquetas_id` int(11) NOT NULL,
  PRIMARY KEY (`publicacion_id`,`etiquetas_id`),
  KEY `IDX_C253DA249ACBB5E7` (`publicacion_id`),
  KEY `IDX_C253DA24A6A0FAE6` (`etiquetas_id`),
  CONSTRAINT `FK_C253DA249ACBB5E7` FOREIGN KEY (`publicacion_id`) REFERENCES `publicacion` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_C253DA24A6A0FAE6` FOREIGN KEY (`etiquetas_id`) REFERENCES `etiquetas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `publicacion_etiquetas`
--

LOCK TABLES `publicacion_etiquetas` WRITE;
/*!40000 ALTER TABLE `publicacion_etiquetas` DISABLE KEYS */;
INSERT INTO `publicacion_etiquetas` VALUES (61,95),(61,96),(62,94);
/*!40000 ALTER TABLE `publicacion_etiquetas` ENABLE KEYS */;
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
  `reputacion` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`),
  KEY `IDX_8D93D6494E7121AF` (`provincia_id`),
  CONSTRAINT `FK_8D93D6494E7121AF` FOREIGN KEY (`provincia_id`) REFERENCES `provincias` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=114 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (111,'user1@gmail.com','[]','$argon2id$v=19$m=65536,t=4,p=1$7/xkvJ9M3mntrTXHkyBgjw$BQxf7hpku/E7buiCu+IFNmvCrZO3UcQMnOidgzZmPiM','user1','2020-05-21',NULL,NULL,'img/comun/circulo.png',0),(112,'user2@gmail.com','[]','$argon2id$v=19$m=65536,t=4,p=1$bbjSEWL42BnHbqAoYhZmKA$fq1JhdM8GynRaoQqOqOtTV7pYWHG36pI6oeqO0DjMQ4','user2','2020-05-21',NULL,NULL,'img/comun/circulo.png',0),(113,'user3@gmail.com','[]','$argon2id$v=19$m=65536,t=4,p=1$nEhYHfdp/9DdxfP+NqNpPA$hJUiMPJ9Za75DiqaKFFlY9/VHi12JIGo106+H/McT8g','user3','2020-05-21',NULL,NULL,'img/comun/circulo.png',0);
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

-- Dump completed on 2020-05-21 13:15:46
