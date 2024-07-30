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
-- Table structure for table `chamados`
--

DROP TABLE IF EXISTS `chamados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `chamados` (
  `id_chamado` int NOT NULL AUTO_INCREMENT,
  `id_user` int unsigned DEFAULT NULL,
  `titulo_chamado` varchar(200) NOT NULL,
  `descricao_chamado` varchar(500) NOT NULL,
  `anexo_chamado` varchar(45) DEFAULT NULL,
  `gravidade` int NOT NULL,
  `urgencia` int NOT NULL,
  `tendencia` int NOT NULL,
  `prioridade_chamado` int DEFAULT NULL,
  `data_criacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status_chamado` int NOT NULL DEFAULT '1',
  `id_user_tecnico` int DEFAULT NULL,
  `data_atualizacao` datetime DEFAULT NULL,
  `descricao_solucao` varchar(100) DEFAULT NULL,
  `justificativa_encam` varchar(45) DEFAULT NULL,
  `data_conclusao` datetime DEFAULT NULL,
  `id_usuario_atual` int DEFAULT NULL,
  `ultima_atualizacao` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_chamado`),
  KEY `fk_id_user_idx` (`id_user`) /*!80000 INVISIBLE */,
  KEY `fk_id_user_tecnico_idx` (`id_user_tecnico`),
  KEY `fk_gravidade_idx` (`gravidade`) /*!80000 INVISIBLE */,
  KEY `fk_urgencia_idx` (`urgencia`) /*!80000 INVISIBLE */,
  KEY `fk_tendencia_idx` (`tendencia`),
  CONSTRAINT `fk_gravidade` FOREIGN KEY (`gravidade`) REFERENCES `prioridades` (`gravidade`),
  CONSTRAINT `fk_id_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`),
  CONSTRAINT `fk_tendencia` FOREIGN KEY (`tendencia`) REFERENCES `prioridades` (`tendencia`),
  CONSTRAINT `fk_urgencia` FOREIGN KEY (`urgencia`) REFERENCES `prioridades` (`urgencia`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chamados`
--

LOCK TABLES `chamados` WRITE;
/*!40000 ALTER TABLE `chamados` DISABLE KEYS */;
INSERT INTO `chamados` VALUES (1,1,'Alterar senha do computador','Testado',NULL,0,0,0,2,'2024-04-16 21:39:20',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(3,1,'Impressora parou de funcionar','Testando',NULL,0,0,0,3,'2024-04-16 21:39:20',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(4,1,'VPN não conecta','Testando',NULL,0,0,0,3,'2024-04-16 21:39:20',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(7,NULL,'Erro ao extrair dados no sistema SAP','',NULL,1,1,1,NULL,'2024-04-16 23:42:01',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(8,NULL,'Configurar comutador e senhas para novos funcionários','Teste',NULL,1,1,1,NULL,'2024-04-17 08:05:10',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9,NULL,'Usuário inativo no sistema','',NULL,1,1,1,NULL,'2024-04-17 09:24:43',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(10,NULL,'','',NULL,1,1,1,NULL,'2024-04-17 09:25:42',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(11,NULL,'Teste','TestTste',NULL,1,1,1,NULL,'2024-04-17 10:50:15',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(12,NULL,'teste2','testeetstetste',NULL,1,1,1,NULL,'2024-04-17 11:33:30',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(13,NULL,'Chamado teste','este',NULL,1,2,3,6,'2024-04-17 20:22:34',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(14,NULL,'Teste','Chamado',NULL,1,4,2,8,'2024-04-18 11:47:37',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(15,NULL,'Teste','Chamado',NULL,1,2,4,8,'2024-04-18 11:49:40',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(16,NULL,'Teste','Chaadmado',NULL,2,3,4,24,'2024-04-18 11:57:49',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(17,NULL,'Teste','Chaadmado',NULL,2,3,4,24,'2024-04-18 11:58:38',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(18,1,'Chamado teste','0000000',NULL,1,2,3,6,'2024-05-16 00:00:26',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(19,1,'Chamado teste 2','10545121',NULL,2,5,5,50,'2024-05-16 00:03:11',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `chamados` ENABLE KEYS */;
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
