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
  `idfr_chamado` varchar(50) DEFAULT NULL,
  `acao` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id_acompanhamento`),
  KEY `fk_id_chamado_idx` (`id_chamado`) /*!80000 INVISIBLE */,
  KEY `fk_id_user_solicitante_idx` (`id_user`) /*!80000 INVISIBLE */,
  KEY `fk_id_user_tecnico_idx` (`id_user_tecnico`) /*!80000 INVISIBLE */,
  KEY `fk_idfr_chamado` (`idfr_chamado`),
  CONSTRAINT `fk_id_chamado` FOREIGN KEY (`id_chamado`) REFERENCES `chamados` (`id_chamado`),
  CONSTRAINT `fk_id_user_solicitante` FOREIGN KEY (`id_user`) REFERENCES `chamados` (`id_user`),
  CONSTRAINT `fk_id_user_tecnico` FOREIGN KEY (`id_user_tecnico`) REFERENCES `chamados` (`id_user_tecnico`),
  CONSTRAINT `fk_idfr_chamado` FOREIGN KEY (`idfr_chamado`) REFERENCES `chamados` (`idfr_chamado`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acompanhamento`
--

LOCK TABLES `acompanhamento` WRITE;
/*!40000 ALTER TABLE `acompanhamento` DISABLE KEYS */;
INSERT INTO `acompanhamento` VALUES (1,21,1,NULL,NULL,NULL),(2,22,1,NULL,'ID022000000000103062024','USUARIO 0000000001 REGISTROU O CHAMADO ID022000000000103062024'),(3,23,1,NULL,'ID023103062024','USUARIO 1 REGISTROU O CHAMADO ID023103062024'),(4,24,1,NULL,'ID0241030624','USUARIO 1 REGISTROU O CHAMADO ID0241030624');
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

-- Dump completed on 2024-06-03 17:41:55
