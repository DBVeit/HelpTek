-- MySQL dump 10.13  Distrib 8.0.21, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: helptek
-- ------------------------------------------------------
-- Server version	8.0.21

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
  `id_user` int unsigned NOT NULL AUTO_INCREMENT,
  `idfr_code_user` varchar(50) DEFAULT NULL,
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
  `user_logado` enum('1','0') DEFAULT '0',
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `username_user_UNIQUE` (`username_user`),
  UNIQUE KEY `email_user_UNIQUE` (`email_user`),
  UNIQUE KEY `id_user_UNIQUE` (`id_user`),
  UNIQUE KEY `idfr_code_user_UNIQUE` (`idfr_code_user`),
  KEY `fk_id_permissao_idx` (`id_permissao`),
  KEY `fk_permissao_user_idx` (`id_permissao`),
  CONSTRAINT `fk_permissao_user` FOREIGN KEY (`id_permissao`) REFERENCES `permissoes` (`id_permissao`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'A01060424','Administrador','Admin','admin','help-tek-sys@outlook.com','5994471abb01112afcc18159f6cc74b4f511b99806da59b3caf5a9c173cacfc5',1,'4',1,'2024-04-06 00:00:00','ADMIN','ADMIN','eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjEiLCJuYW1lIjoiQWRtaW5pc3RyYWRvciIsImV4cGlyZXNfaW4iOjE3MjM5ODM2MTd9.x1oOmWKL9mQ35E9v1RdvmvFK7fPJc1Fw-B8xmhW6D9A','0','1'),(2,'A02060424','Administrador 2','Admin2','ADMIN2','htek2@helptek.com','$2y$10$Ki8Lqclic/mjMEdW89VEzeyg4AWC84KhmsmCDuCnckaoI86hgfL1u',4,'4',1,'2024-04-06 17:49:16','ADMIN','ADMIN','eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjIiLCJuYW1lIjoiQWRtaW5pc3RyYWRvciAyIiwiZXhwaXJlc19pbiI6MTcyMzM4NzA4MX0.4zcURMA_Wzd9fLzXDGhgOJ6ubuyfjMd_d3Jscr8KfqM','1','1'),(3,'A03060424','Administrador 3',NULL,'ADMIN3','htek3@helptek.com','pass123',4,'4',1,'2024-04-06 17:53:34','ADMIN','ADMIN','eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjAwMDAwMDAwMDMiLCJuYW1lIjoiQWRtaW5pc3RyYWRvciAzIiwiZXhwaXJlc19pbiI6MTcxMjY4MTQ1NX0.RFcq366mrv5X6LtDf4Mb_s_zQersh6NNa1sYVrjPwvg','0','0'),(4,'S04060424','Davi Veit','Davi','davi.veit','davi.veit@htek.com','$2y$10$VTbiCn9BRI5NW.TUSOV7hOn0Vvm8yjaB/LD5rUmeuCnh5KLAY5gdO',1,'4',1,'2024-04-06 23:21:34','ADMIN','ADMIN','eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjQiLCJuYW1lIjoiRGF2aSBWZWl0IiwiZXhwaXJlc19pbiI6MTcyMzIyNTQ5MX0.WEONgDPBpiqT1WL7UTtn_21zja9Xp0v69gJRMvWdBtg','0','1'),(7,'A07060424','Davi Veit',NULL,'davi.veit3','davi.veit3@htek.com','xkwjeg57',4,'4',1,'2024-04-06 23:27:58','ADMIN','ADMIN',NULL,'0','0'),(8,'A08070424','Davi Veit',NULL,'davi.veit8','davi.veit8@htek.com','xkwjeg57',4,'4',1,'2024-04-07 22:12:13','ADMIN','ADMIN',NULL,'0','0'),(9,'A09070424','Davi Veit',NULL,'davi.veit9','davi.veit9@htek.com','$2y$10$kKtxcAy8CA02fbc96S1UUubfWnooEJu9k9Z2ncHzxKs4d66NXYguC',4,'4',1,'2024-04-07 23:06:28','ADMIN','ADMIN',NULL,'1','0'),(10,'A010070424','Davi Veit2',NULL,'davi.veit2','davi.veit2@htek.com','$2y$10$7.wXlhLIr1kJm2V2y6neheQzdJ5X30LBU6TIGgRP71JdSWmCoWt0G',4,'4',1,'2024-04-07 23:07:02',NULL,NULL,'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjEwIiwibmFtZSI6IkRhdmkgVmVpdDIiLCJleHBpcmVzX2luIjoxNzIyOTQ0MjY5fQ.N9sNiEowlWCvz1sp2vxgb8hQheiQngiZxPWIh45nujI','1','1'),(11,'T011100624','Davi Beringer Veit','Davi','DBVEIT','davi@helptek.com.br','$2y$10$dR6jE1qAltBtkz0f4MY6xewFAbL/pOnbLxj8O.Z0h5PCmO5TdO6o2',2,'2',1,'2024-06-10 10:55:43',NULL,NULL,'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjExIiwibmFtZSI6IkRhdmkgQmVyaW5nZXIgVmVpdCIsImV4cGlyZXNfaW4iOjE3MTgzNzk0MTR9.qDNAfdQxTCMUkcNo_DJl6EMdfkLmp1JJWXk_t9CyJmM','1','1'),(12,'S012100824','Solicitante Exemplo','Exemplo','Solicitante','exemplosolicitante@htek.com','5994471abb01112afcc18159f6cc74b4f511b99806da59b3caf5a9c173cacfc5',1,'1',1,'2024-08-10 20:49:17',NULL,NULL,'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjEyIiwibmFtZSI6IlNvbGljaXRhbnRlIEV4ZW1wbG8iLCJleHBpcmVzX2luIjoxNzI0MDA4MTA4fQ.7tRzHKaooplhJaSnyklwOAR4scqS8bqV3TO6P2Zrp-k','1','1'),(14,'T014100824','Tecnico Exemplo','Exemplo2','tecnico','exemplotecnico@htek.com','5994471abb01112afcc18159f6cc74b4f511b99806da59b3caf5a9c173cacfc5',2,'2',1,'2024-08-10 20:52:41',NULL,NULL,'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjE0IiwibmFtZSI6IlRlY25pY28gRXhlbXBsbyIsImV4cGlyZXNfaW4iOjE3MjQwMDgxMzB9.NXw4cuX6GGOcSg5u-JFkKJsymGsBFM9o_m8fv2y9ypw','1','1'),(15,'G015100824','Gerente Exemplo','Exemplo3','gerente','exemplogerente@htek.com','5994471abb01112afcc18159f6cc74b4f511b99806da59b3caf5a9c173cacfc5',3,'3',1,'2024-08-10 20:54:49',NULL,NULL,NULL,'1','0'),(17,'A017100824','Administrador Exemplo','Exemplo4','admin4','exemploadmin@htek.com','5994471abb01112afcc18159f6cc74b4f511b99806da59b3caf5a9c173cacfc5',4,'4',1,'2024-08-10 20:55:39',NULL,NULL,'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjE3IiwibmFtZSI6IkFkbWluaXN0cmFkb3IgRXhlbXBsbyIsImV4cGlyZXNfaW4iOjE3MjQwMDgxNjJ9.VMziZA0zFHt2aQbj3aEHClR9rNpTtwXyOqTPYfP8lVw','1','1'),(18,'A018170824','Teste usuário','TesteTesteTeste','testeteste','teste@testeteste.com','8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92',4,'4',1,'2024-08-17 19:51:34',NULL,NULL,NULL,'1','0'),(19,'A019170824','William Bonner','Bonner','wbonner','william.bonner@htek.com','60641f4e4bbee6a2914428efd985ae72a4b6d18375af772d3664fd219e7b7652',4,'4',1,'2024-08-17 20:32:51',NULL,NULL,NULL,'1','0'),(20,'A020170824','Silvio Santos','Senor','SenorAb','senor@htek.com','ba03cde88b6183ac0c6cadae33bb1294cf8fb4f4119ea680c0f9fea2a19d5667',4,'4',1,'2024-08-17 20:37:22',NULL,NULL,NULL,'1','0'),(21,'A021170824','Senor Abravanel','Senor','senorabr','SenorAbr@htek.com','afe3b82642d634fd5c94cfc3c5abf32b3e3fdcbe8a5574d47ba4a8c839858193',4,'4',1,'2024-08-17 21:09:53',NULL,NULL,NULL,'1','0');
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

-- Dump completed on 2024-08-17 23:38:07
