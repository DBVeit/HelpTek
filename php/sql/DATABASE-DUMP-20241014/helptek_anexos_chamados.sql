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
-- Table structure for table `anexos_chamados`
--

DROP TABLE IF EXISTS `anexos_chamados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `anexos_chamados` (
  `id_anexo` int NOT NULL AUTO_INCREMENT,
  `id_chamado` int DEFAULT NULL,
  `nome_arquivo` varchar(255) DEFAULT NULL,
  `caminho_arquivo` varchar(255) DEFAULT NULL,
  `tamanho_arquivo` int DEFAULT NULL,
  `data_upload` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_anexo`),
  KEY `id_chamado` (`id_chamado`),
  CONSTRAINT `anexos_chamados_ibfk_1` FOREIGN KEY (`id_chamado`) REFERENCES `chamados` (`id_chamado`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `anexos_chamados`
--

LOCK TABLES `anexos_chamados` WRITE;
/*!40000 ALTER TABLE `anexos_chamados` DISABLE KEYS */;
INSERT INTO `anexos_chamados` VALUES (1,64,'HelpTek Logo.png','../../public/uploads/66ca9362623f9.png',11365,'2024-08-25 02:13:54'),(2,71,'Somativa1C_entrega.jpg','../../public/uploads/66e78ac452c26.jpg',107131,'2024-09-16 01:32:52'),(3,72,'Somativa1C_entrega.jpg','../../public/uploads/66ea022eeadca.jpg',107131,'2024-09-17 22:26:54'),(4,72,'455031375_8237080349684520_5553740988874915282_n.jpg','../../public/uploads/66ea022f1a90d.jpg',58430,'2024-09-17 22:26:55'),(5,103,'455031375_8237080349684520_5553740988874915282_n.jpg','../../../public/uploads/66ee0e512a4bd.jpg',58430,'2024-09-21 00:07:45'),(6,105,'favivon.jpg','../../../public/uploads/66f853b5a9316.jpg',3148,'2024-09-28 19:06:30');
/*!40000 ALTER TABLE `anexos_chamados` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-10-14 22:32:49
