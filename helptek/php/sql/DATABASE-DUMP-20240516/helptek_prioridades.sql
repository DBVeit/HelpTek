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
-- Table structure for table `prioridades`
--

DROP TABLE IF EXISTS `prioridades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `prioridades` (
  `id_prioridade` int NOT NULL AUTO_INCREMENT,
  `gravidade` int NOT NULL,
  `urgencia` int NOT NULL,
  `tendencia` int NOT NULL,
  `descricao_categoria` varchar(60) DEFAULT NULL,
  `categoria_prioridade` varchar(45) DEFAULT NULL,
  `valor_prioridade` int DEFAULT NULL,
  PRIMARY KEY (`id_prioridade`),
  KEY `fk_gravidade_idx` (`gravidade`) /*!80000 INVISIBLE */,
  KEY `fk_urgencia_idx` (`urgencia`) /*!80000 INVISIBLE */,
  KEY `fk_tendencia_idx` (`tendencia`) /*!80000 INVISIBLE */
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prioridades`
--

LOCK TABLES `prioridades` WRITE;
/*!40000 ALTER TABLE `prioridades` DISABLE KEYS */;
INSERT INTO `prioridades` VALUES (1,1,0,0,'1 - O dano é pouco importante','gravidade',1),(2,2,0,0,'2 - O dano é relativamente importante','gravidade',2),(3,3,0,0,'3 - O dano é importante','gravidade',3),(4,4,0,0,'4 - O dano é muio importante','gravidade',4),(5,5,0,0,'5 - O dano é extremamente importante','gravidade',5),(6,0,1,0,'1 - Não há pressa','urgencia',1),(7,0,2,0,'2 - Pode-se aguardar','urgencia',2),(8,0,3,0,'3 - Tomar uma ação relativamente urgente','urgencia',3),(9,0,4,0,'4 - Tomar uma ação urgente','urgencia',4),(10,0,5,0,'5 - Tomar uma ação bastante urgente','urgencia',5),(11,0,0,1,'1 - Tende à desaparecer completamente','tendencia',1),(12,0,0,2,'2 - Tende à melhorar','tendencia',2),(13,0,0,3,'3 - Tende à permanecer','tendencia',3),(14,0,0,4,'4 - Tende à piorar','tendencia',4),(15,0,0,5,'5 - Tende à piorar muito','tendencia',5);
/*!40000 ALTER TABLE `prioridades` ENABLE KEYS */;
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
