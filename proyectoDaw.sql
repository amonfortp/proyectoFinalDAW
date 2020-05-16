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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chat`
--

LOCK TABLES `chat` WRITE;
/*!40000 ALTER TABLE `chat` DISABLE KEYS */;
INSERT INTO `chat` VALUES (1,87,86,'http://chat.monbarter/8687');
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
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `etiquetas`
--

LOCK TABLES `etiquetas` WRITE;
/*!40000 ALTER TABLE `etiquetas` DISABLE KEYS */;
INSERT INTO `etiquetas` VALUES (64,'Deporte'),(65,'Telefonía'),(66,'Tecnología');
/*!40000 ALTER TABLE `etiquetas` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
INSERT INTO `messages` VALUES (1,87,1,'Me interesa su publicacion - <a href=\"/publicacion/45\">Telefono</a>','2020-05-16 16:28:38',NULL,1),(2,87,1,'1','2020-05-16 16:30:11',NULL,1),(3,87,1,'Hola','2020-05-16 16:30:18',NULL,1),(4,87,1,'Me interesa su publicacion - <a href=\"/publicacion/45\">Telefono</a>','2020-05-16 18:06:07',NULL,1),(5,87,1,'1','2020-05-16 18:10:39',NULL,1),(6,87,1,'Hola','2020-05-16 18:22:46',NULL,1),(7,87,1,'123123','2020-05-16 18:22:51',NULL,1),(8,87,1,'123123','2020-05-16 18:23:07',NULL,1),(9,87,1,'1','2020-05-16 18:23:09',NULL,1),(10,87,1,'123','2020-05-16 18:23:31',NULL,1),(11,87,1,'123','2020-05-16 18:26:57',NULL,1),(12,87,1,'12312','2020-05-16 18:27:04',NULL,1),(13,87,1,'123','2020-05-16 18:27:58',NULL,1),(14,87,1,'231','2020-05-16 18:28:05',NULL,1),(15,87,1,'1231','2020-05-16 18:28:06',NULL,1),(16,87,1,'31','2020-05-16 18:28:07',NULL,1),(17,87,1,'12312','2020-05-16 18:33:31',NULL,1),(18,87,1,'1','2020-05-16 19:02:58',NULL,1),(19,87,1,'12','2020-05-16 19:17:51',NULL,1);
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
INSERT INTO `migration_versions` VALUES ('20200410174717','2020-04-10 17:47:57'),('20200413203840','2020-04-13 20:38:51'),('20200419105110','2020-04-19 10:51:23'),('20200419114148','2020-04-19 11:41:53'),('20200420161045','2020-04-20 16:11:41'),('20200420175658','2020-04-20 17:57:42'),('20200422162426','2020-04-22 16:24:33'),('20200422173245','2020-04-22 17:35:36'),('20200423171025','2020-04-23 17:10:39'),('20200423172557','2020-04-23 17:26:27'),('20200428203819','2020-04-28 20:38:38'),('20200430181744','2020-04-30 18:17:56'),('20200502182129','2020-05-02 18:21:42'),('20200502184511','2020-05-02 18:45:38'),('20200502185303','2020-05-02 18:53:10'),('20200503181854','2020-05-03 18:19:40'),('20200515152628','2020-05-15 15:26:43'),('20200516111517','2020-05-16 11:15:28');
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `provincias`
--

LOCK TABLES `provincias` WRITE;
/*!40000 ALTER TABLE `provincias` DISABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `publicacion`
--

LOCK TABLES `publicacion` WRITE;
/*!40000 ALTER TABLE `publicacion` DISABLE KEYS */;
INSERT INTO `publicacion` VALUES (45,86,'Descripción 1','a:2:{i:0;s:42:\"img/user1@gmail.com/Telefono/Telefono1.jpg\";i:1;s:42:\"img/user1@gmail.com/Telefono/Telefono2.jpg\";}',1,'2010-05-01 00:00:00','Telefono','Producto'),(46,87,'Descripción 2','a:2:{i:0;s:38:\"img/user2@gmail.com/Pelota/Pelota1.jpg\";i:1;s:38:\"img/user2@gmail.com/Pelota/Pelota2.jpg\";}',1,'2010-05-02 00:00:00','Pelota','Producto');
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
INSERT INTO `publicacion_etiquetas` VALUES (45,65),(45,66),(46,64);
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
) ENGINE=InnoDB AUTO_INCREMENT=89 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (86,'user1@gmail.com','[]','$argon2id$v=19$m=65536,t=4,p=1$Tgl1/7oCCUcF5yKrlvROnw$R6YH+DwTOSUGOz5HmNRyXJJivcffalZ0oVeNbML0yoE','user1','2020-05-16',NULL,NULL,'img/comun/circulo.png',0),(87,'user2@gmail.com','[]','$argon2id$v=19$m=65536,t=4,p=1$/KoHWV2K9WBfIFsQPUbIAg$FZMivbpLJc70zlZbdtkSfc9VJPDYD8KfiNMsYwiWCx4','user2','2020-05-16',NULL,NULL,'img/comun/circulo.png',0),(88,'user3@gmail.com','[]','$argon2id$v=19$m=65536,t=4,p=1$OqSXbucsGFaPZ2ed9M91ew$kxyhL36bX9xfQcxWiypxy4HvpWwURyxgQZDk/EAaPME','user3','2020-05-16',NULL,NULL,'img/comun/circulo.png',0);
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

-- Dump completed on 2020-05-16 19:20:24
