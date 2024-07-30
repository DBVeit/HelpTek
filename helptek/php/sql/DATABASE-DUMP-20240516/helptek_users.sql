-- MySQL dump 10.13  Distrib 8.0.21, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: helptek
-- ------------------------------------------------------
-- Server version	8.0.30

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id_user` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `name_user` varchar(45) NOT NULL,
  `first_name` varchar(45) DEFAULT NULL,
  `username_user` varchar(45) NOT NULL,
  `email_user` varchar(45) NOT NULL,
  `password_user` varchar(256) NOT NULL,
  `id_permissao` int DEFAULT NULL,
  `level_user` enum('1','2','3','4') NOT NULL DEFAULT '1',
  `status_user` tinyint NOT NULL DEFAULT '1',
  `date_user` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `funcao_user` varchar(45) DEFAULT NULL,
  `equipe_user` varchar(45) DEFAULT NULL,
  `token_user` longtext,
  `troca_senha` enum('1','0') DEFAULT '1',
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `username_user_UNIQUE` (`username_user`),
  UNIQUE KEY `email_user_UNIQUE` (`email_user`),
  UNIQUE KEY `id_user_UNIQUE` (`id_user`),
  KEY `fk_id_permissao_idx` (`id_permissao`),
  KEY `fk_permissao_user_idx` (`id_permissao`),
  CONSTRAINT `fk_permissao_user` FOREIGN KEY (`id_permissao`) REFERENCES `permissoes` (`id_permissao`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (0000000001,'Administrador','Admin','admin','help-tek-sys@outlook.com','$2y$10$zmWcL8v3zrtyydDe8sU8u.Y8sC65849GIGlBSIuvqSUczUmwi3.O.',NULL,'4',1,'2024-04-06 00:00:00','ADMIN','ADMIN',NULL,'1'),(0000000002,'Administrador 2',NULL,'ADMIN2','htek2@helptek.com','$2y$10$0HYu3oqJVdTqpObEz41JSuq0QYb3JuUfPm79aFntHsydZ8U4R4DJG',NULL,'4',1,'2024-04-06 17:49:16','ADMIN','ADMIN','eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjAwMDAwMDAwMDIiLCJuYW1lIjoiQWRtaW5pc3RyYWRvciAyIiwiZXhwaXJlc19pbiI6MTcxMjkyNTcxNH0.ryfn000KUFPkwsfCjEzBW6CNSBptQIYLJAkxGBYTfcM','0'),(0000000003,'Administrador 3',NULL,'ADMIN3','htek3@helptek.com','pass123',NULL,'4',1,'2024-04-06 17:53:34','ADMIN','ADMIN','eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjAwMDAwMDAwMDMiLCJuYW1lIjoiQWRtaW5pc3RyYWRvciAzIiwiZXhwaXJlc19pbiI6MTcxMjY4MTQ1NX0.RFcq366mrv5X6LtDf4Mb_s_zQersh6NNa1sYVrjPwvg','0'),(0000000004,'Davi Veit',NULL,'davi.veit','davi.veit@htek.com','81dc9bdb52d04dc20036dbd8313ed055',NULL,'4',1,'2024-04-06 23:21:34','ADMIN','ADMIN',NULL,'0'),(0000000007,'Davi Veit',NULL,'davi.veit3','davi.veit3@htek.com','xkwjeg57',NULL,'4',1,'2024-04-06 23:27:58','ADMIN','ADMIN',NULL,'0'),(0000000008,'Davi Veit',NULL,'davi.veit8','davi.veit8@htek.com','xkwjeg57',NULL,'4',1,'2024-04-07 22:12:13','ADMIN','ADMIN',NULL,'0'),(0000000009,'Davi Veit',NULL,'davi.veit9','davi.veit9@htek.com','$2y$10$kKtxcAy8CA02fbc96S1UUubfWnooEJu9k9Z2ncHzxKs4d66NXYguC',NULL,'4',1,'2024-04-07 23:06:28','ADMIN','ADMIN',NULL,'1'),(0000000010,'Davi Veit2',NULL,'davi.veit2','davi.veit2@htek.com','dcd077c7a01461bc76d62579372a3fa0',NULL,'4',1,'2024-04-07 23:07:02',NULL,NULL,NULL,'0');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-05-16 15:08:45
