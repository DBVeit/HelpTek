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
-- Table structure for table `acompanhamento`
--

DROP TABLE IF EXISTS `acompanhamento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `acompanhamento` (
  `id_acompanhamento` int NOT NULL AUTO_INCREMENT,
  `id_chamado` int DEFAULT NULL,
  `id_user` int unsigned DEFAULT NULL,
  `id_user_tecnico` int DEFAULT NULL,
  `idfr_code_user` varchar(50) DEFAULT NULL,
  `idfr_chamado` varchar(50) DEFAULT NULL,
  `acao` varchar(200) DEFAULT NULL,
  `data_acao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_acompanhamento`),
  KEY `fk_id_chamado_idx` (`id_chamado`) /*!80000 INVISIBLE */,
  KEY `fk_id_user_solicitante_idx` (`id_user`) /*!80000 INVISIBLE */,
  KEY `fk_idfr_chamado` (`idfr_chamado`),
  CONSTRAINT `fk_id_chamado` FOREIGN KEY (`id_chamado`) REFERENCES `chamados` (`id_chamado`),
  CONSTRAINT `fk_id_user_solicitante` FOREIGN KEY (`id_user`) REFERENCES `chamados` (`id_user`),
  CONSTRAINT `fk_idfr_chamado` FOREIGN KEY (`idfr_chamado`) REFERENCES `chamados` (`idfr_chamado`)
) ENGINE=InnoDB AUTO_INCREMENT=90 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acompanhamento`
--

LOCK TABLES `acompanhamento` WRITE;
/*!40000 ALTER TABLE `acompanhamento` DISABLE KEYS */;
INSERT INTO `acompanhamento` VALUES (1,21,1,NULL,NULL,NULL,NULL,'2024-06-10 21:15:12'),(2,22,1,NULL,NULL,'ID022000000000103062024','USUARIO 0000000001 REGISTROU O CHAMADO ID022000000000103062024','2024-06-10 21:15:12'),(3,23,1,NULL,NULL,'ID023103062024','USUARIO 1 REGISTROU O CHAMADO ID023103062024','2024-06-10 21:15:12'),(4,24,1,NULL,NULL,'ID0241030624','USUARIO 1 REGISTROU O CHAMADO ID0241030624','2024-06-10 21:15:12'),(5,24,1,NULL,NULL,'ID0241030624','USUARIO 1 ATUALIZOU O CHAMADO ID0241030624','2024-06-10 21:15:12'),(11,23,1,1,NULL,'ID023103062024','USUARIO 1 ASSUMIU O CHAMADO ID023103062024','2024-06-10 21:15:12'),(12,24,1,1,NULL,'ID0241030624','USUARIO 1 RESPONDEU AO CHAMADO ID0241030624','2024-06-10 21:15:12'),(13,26,4,NULL,NULL,'ID0264070624','USUARIO 4 REGISTROU O CHAMADO ID0264070624','2024-06-10 21:15:12'),(14,26,4,1,NULL,'ID0264070624','USUARIO 1 ASSUMIU O CHAMADO ID0264070624','2024-06-10 21:15:12'),(16,27,4,NULL,NULL,'ID0274070624','USUARIO 4 REGISTROU O CHAMADO ID0274070624','2024-06-10 21:15:12'),(17,27,4,1,NULL,'ID0274070624','USUARIO 1 ASSUMIU O CHAMADO ID0274070624','2024-06-10 21:15:12'),(18,27,4,1,NULL,'ID0274070624','USUARIO 1 RESPONDEU AO CHAMADO ID0274070624','2024-06-10 21:15:12'),(19,28,4,NULL,NULL,'ID0284070624','USUARIO 4 REGISTROU O CHAMADO ID0284070624','2024-06-10 21:15:12'),(20,28,4,1,NULL,'ID0284070624','USUARIO 1 ASSUMIU O CHAMADO ID0284070624','2024-06-10 21:15:12'),(21,26,4,NULL,NULL,'ID0264070624','USUARIO 4 ATUALIZOU O CHAMADO ID0264070624','2024-06-10 21:15:12'),(22,26,4,NULL,NULL,'ID0264070624','USUARIO 4 ATUALIZOU O CHAMADO ID0264070624','2024-06-10 21:15:12'),(23,26,4,NULL,NULL,'ID0264070624','USUARIO 4 ATUALIZOU O CHAMADO ID0264070624','2024-06-10 21:15:12'),(24,26,4,NULL,NULL,'ID0264070624','USUARIO 4 CANCELOU O CHAMADO ID0264070624','2024-06-10 21:15:12'),(26,29,4,NULL,NULL,'ID0294070624','USUARIO 4 REGISTROU O CHAMADO ID0294070624','2024-06-10 21:15:12'),(27,29,4,1,NULL,'ID0294070624','USUARIO 1 ASSUMIU O CHAMADO ID0294070624','2024-06-10 21:15:12'),(28,27,4,1,NULL,'ID0274070624','USUARIO 1 RESPONDEU AO CHAMADO ID0274070624','2024-06-10 21:15:12'),(29,24,1,1,NULL,'ID0241030624','USUARIO 1 ENCAMINHOU O CHAMADO ID0241030624 AO USUARIO 1','2024-06-10 21:15:12'),(30,30,4,NULL,NULL,'ID0304080624','USUARIO 4 REGISTROU O CHAMADO ID0304080624','2024-06-10 21:15:12'),(31,30,4,1,NULL,'ID0304080624','USUARIO 1 ASSUMIU O CHAMADO ID0304080624','2024-06-10 21:15:12'),(32,27,4,1,NULL,'ID0274070624','USUARIO 1 RESPONDEU AO CHAMADO ID0274070624','2024-06-10 21:15:12'),(33,27,4,1,NULL,'ID0274070624','USUARIO 1 RESPONDEU AO CHAMADO ID0274070624','2024-06-10 21:15:12'),(34,27,4,1,NULL,'ID0274070624','USUARIO 1 RESPONDEU AO CHAMADO ID0274070624','2024-06-10 21:15:12'),(35,24,1,1,NULL,'ID0241030624','USUARIO 1 RESPONDEU AO CHAMADO ID0241030624','2024-06-10 21:15:12'),(37,31,4,NULL,NULL,'ID0314100624','USUARIO 4 REGISTROU O CHAMADO ID0314100624','2024-06-10 21:15:12'),(38,31,4,NULL,NULL,'ID0314100624','USUARIO 4 ATUALIZOU O CHAMADO ID0314100624','2024-06-10 21:15:12'),(39,31,4,NULL,NULL,'ID0314100624','USUARIO 4 CANCELOU O CHAMADO ID0314100624','2024-06-10 21:15:12'),(40,32,4,NULL,NULL,'ID0324100624','USUARIO 4 REGISTROU O CHAMADO ID0324100624','2024-06-10 21:15:12'),(41,32,4,1,NULL,'ID0324100624','USUARIO 1 ASSUMIU O CHAMADO ID0324100624','2024-06-10 21:15:12'),(42,32,4,1,NULL,'ID0324100624','USUARIO 1 ENCAMINHOU O CHAMADO ID0324100624 AO USUARIO 1','2024-06-10 21:15:12'),(45,27,4,1,NULL,'ID0274070624','USUARIO 1 ENCAMINHOU O CHAMADO ID0274070624 AO USUARIO 11','2024-06-10 21:15:12'),(46,32,4,1,NULL,'ID0324100624','USUARIO  ENCAMINHOU O CHAMADO ID0324100624 AO USUARIO 11','2024-06-10 21:25:28'),(47,24,1,1,NULL,'ID0241030624','USUARIO  ENCAMINHOU O CHAMADO ID0241030624 AO USUARIO 11','2024-06-10 21:45:01'),(48,26,4,1,NULL,'ID0264070624','USUARIO 1 ENCAMINHOU O CHAMADO ID0264070624 AO USUARIO 11','2024-06-10 21:56:00'),(49,33,4,NULL,NULL,'ID0334100624','USUARIO 4 REGISTROU O CHAMADO ID0334100624','2024-06-10 23:07:09'),(50,33,4,1,NULL,'ID0334100624','USUARIO 1 ASSUMIU O CHAMADO ID0334100624','2024-06-10 23:07:21'),(51,34,4,NULL,NULL,'ID0344100624','USUARIO 4 REGISTROU O CHAMADO ID0344100624','2024-06-10 23:11:53'),(52,34,4,1,NULL,'ID0344100624','USUARIO 1 ASSUMIU O CHAMADO ID0344100624','2024-06-10 23:12:02'),(53,35,4,NULL,NULL,'ID0354110624','USUARIO 4 REGISTROU O CHAMADO ID0354110624','2024-06-11 22:08:24'),(54,36,4,NULL,NULL,'ID0364110624','USUARIO 4 REGISTROU O CHAMADO ID0364110624','2024-06-11 22:24:26'),(55,37,4,NULL,NULL,'ID0374110624','USUARIO 4 REGISTROU O CHAMADO ID0374110624','2024-06-11 22:36:39'),(56,38,4,NULL,NULL,'ID0384110624','USUARIO 4 REGISTROU O CHAMADO ID0384110624','2024-06-11 22:39:33'),(57,39,4,NULL,NULL,'ID0394110624','USUARIO 4 REGISTROU O CHAMADO ID0394110624','2024-06-11 22:40:52'),(58,40,4,NULL,NULL,'ID0404110624','USUARIO 4 REGISTROU O CHAMADO ID0404110624','2024-06-11 22:42:05'),(59,41,4,NULL,NULL,'ID0414110624','USUARIO 4 REGISTROU O CHAMADO ID0414110624','2024-06-11 22:43:13'),(60,42,4,NULL,NULL,'ID0424110624','USUARIO 4 REGISTROU O CHAMADO ID0424110624','2024-06-11 22:44:06'),(61,43,4,NULL,NULL,'ID0434110624','USUARIO 4 REGISTROU O CHAMADO ID0434110624','2024-06-11 22:49:20'),(62,44,4,NULL,NULL,'ID0444110624','USUARIO 4 REGISTROU O CHAMADO ID0444110624','2024-06-11 22:51:12'),(63,45,4,NULL,NULL,'ID0454110624','USUARIO 4 REGISTROU O CHAMADO ID0454110624','2024-06-11 22:52:41'),(64,46,4,NULL,NULL,'ID0464110624','USUARIO 4 REGISTROU O CHAMADO ID0464110624','2024-06-11 23:24:45'),(65,47,4,NULL,NULL,'ID0474110624','USUARIO 4 REGISTROU O CHAMADO ID0474110624','2024-06-11 23:29:00'),(66,48,4,NULL,NULL,'ID0484110624','USUARIO 4 REGISTROU O CHAMADO ID0484110624','2024-06-11 23:32:48'),(67,49,4,NULL,NULL,'ID0494110624','USUARIO 4 REGISTROU O CHAMADO ID0494110624','2024-06-11 23:35:32'),(68,50,4,NULL,NULL,'ID0504110624','USUARIO 4 REGISTROU O CHAMADO ID0504110624','2024-06-11 23:35:32'),(69,33,4,NULL,NULL,'ID0334100624','USUARIO 4 ATUALIZOU O CHAMADO ID0334100624','2024-06-12 00:03:28'),(70,33,4,NULL,NULL,'ID0334100624','USUARIO 4 CANCELOU O CHAMADO ID0334100624','2024-06-12 00:03:42'),(71,33,4,NULL,NULL,'ID0334100624','USUARIO 4 ATUALIZOU O CHAMADO ID0334100624','2024-06-12 23:14:08'),(72,34,4,NULL,NULL,'ID0344100624','USUARIO 4 ATUALIZOU O CHAMADO ID0344100624','2024-06-12 23:18:43'),(73,34,4,NULL,NULL,'ID0344100624','USUARIO 4 ATUALIZOU O CHAMADO ID0344100624','2024-06-12 23:23:09'),(74,37,4,NULL,NULL,'ID0374110624','USUARIO 4 CANCELOU O CHAMADO ID0374110624','2024-06-12 23:23:47'),(75,34,4,1,NULL,'ID0344100624','USUARIO 1 RESPONDEU AO CHAMADO ID0344100624','2024-06-12 23:28:56'),(76,34,4,1,NULL,'ID0344100624','USUARIO 1 RESPONDEU AO CHAMADO ID0344100624','2024-06-12 23:29:21'),(77,33,4,NULL,NULL,'ID0334100624','USUARIO 4 ATUALIZOU O CHAMADO ID0334100624','2024-06-13 00:26:28'),(78,32,4,NULL,NULL,'ID0324100624','USUARIO 4 CANCELOU O CHAMADO ID0324100624','2024-06-13 00:27:09'),(79,36,4,1,NULL,'ID0364110624','USUARIO 1 ASSUMIU O CHAMADO ID0364110624','2024-06-13 00:39:06'),(80,40,4,1,NULL,'ID0404110624','USUARIO 1 ASSUMIU O CHAMADO ID0404110624','2024-06-13 00:43:16'),(81,41,4,1,NULL,'ID0414110624','USUARIO 1 ASSUMIU O CHAMADO ID0414110624','2024-06-13 00:44:17'),(82,42,4,1,NULL,'ID0424110624','USUARIO 1 ASSUMIU O CHAMADO ID0424110624','2024-06-13 00:45:10'),(83,33,4,1,NULL,'ID0334100624','USUARIO 1 RESPONDEU AO CHAMADO ID0334100624','2024-06-13 00:46:19'),(84,34,4,1,NULL,'ID0344100624','USUARIO 1 RESPONDEU AO CHAMADO ID0344100624','2024-06-13 00:46:37'),(85,34,4,1,NULL,'ID0344100624','USUARIO 1 RESPONDEU AO CHAMADO ID0344100624','2024-06-13 00:47:55'),(86,23,1,1,NULL,'ID023103062024','USUARIO 1 RESPONDEU AO CHAMADO ID023103062024','2024-06-13 00:49:24'),(87,22,1,1,NULL,'ID022000000000103062024','USUARIO 1 ENCAMINHOU O CHAMADO ID022000000000103062024 AO USUARIO 11','2024-06-13 00:56:57'),(88,23,1,1,NULL,'ID023103062024','USUARIO 1 ENCAMINHOU O CHAMADO ID023103062024 AO USUARIO 11','2024-06-13 00:58:21'),(89,28,4,1,NULL,'ID0284070624','USUARIO 1 RESPONDEU AO CHAMADO ID0284070624','2024-06-13 00:58:56');
/*!40000 ALTER TABLE `acompanhamento` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-06-13  1:01:48
