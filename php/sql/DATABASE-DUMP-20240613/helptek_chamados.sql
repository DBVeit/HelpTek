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
-- Table structure for table `chamados`
--

DROP TABLE IF EXISTS `chamados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `chamados` (
  `id_chamado` int NOT NULL AUTO_INCREMENT,
  `idfr_chamado` varchar(50) DEFAULT NULL,
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
  `id_categoria_servico` int DEFAULT NULL,
  `id_categoria_ocorrencia` int DEFAULT NULL,
  `justificativa_encam` varchar(45) DEFAULT NULL,
  `data_conclusao` datetime DEFAULT NULL,
  `id_usuario_atual` int DEFAULT NULL,
  `ultima_atualizacao` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_chamado`),
  UNIQUE KEY `idfr_chamado_UNIQUE` (`idfr_chamado`),
  KEY `fk_id_user_idx` (`id_user`) /*!80000 INVISIBLE */,
  KEY `fk_id_user_tecnico_idx` (`id_user_tecnico`),
  KEY `fk_gravidade_idx` (`gravidade`) /*!80000 INVISIBLE */,
  KEY `fk_urgencia_idx` (`urgencia`) /*!80000 INVISIBLE */,
  KEY `fk_tendencia_idx` (`tendencia`),
  KEY `fk_id_categoria_servico_idx` (`id_categoria_servico`),
  KEY `fk_id_categoria_ocorrencia_idx` (`id_categoria_ocorrencia`),
  CONSTRAINT `fk_gravidade` FOREIGN KEY (`gravidade`) REFERENCES `prioridades` (`gravidade`),
  CONSTRAINT `fk_id_categoria_ocorrencia` FOREIGN KEY (`id_categoria_ocorrencia`) REFERENCES `categoria_ocorrencia` (`id_categoria_ocorrencia`),
  CONSTRAINT `fk_id_categoria_servico` FOREIGN KEY (`id_categoria_servico`) REFERENCES `categoria_servico` (`id_categoria_servico`),
  CONSTRAINT `fk_id_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`),
  CONSTRAINT `fk_tendencia` FOREIGN KEY (`tendencia`) REFERENCES `prioridades` (`tendencia`),
  CONSTRAINT `fk_urgencia` FOREIGN KEY (`urgencia`) REFERENCES `prioridades` (`urgencia`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chamados`
--

LOCK TABLES `chamados` WRITE;
/*!40000 ALTER TABLE `chamados` DISABLE KEYS */;
INSERT INTO `chamados` VALUES (1,NULL,1,'Alterar senha do computador','Testado',NULL,1,1,1,1,'2024-04-16 21:39:20',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(3,NULL,1,'Impressora parou de funcionar','Testando',NULL,1,1,1,1,'2024-04-16 21:39:20',2,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(4,NULL,1,'VPN não conecta','Testando',NULL,1,1,1,1,'2024-04-16 21:39:20',2,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(7,NULL,NULL,'Erro ao extrair dados no sistema SAP','',NULL,1,1,1,1,'2024-04-16 23:42:01',2,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(8,NULL,NULL,'Configurar comutador e senhas para novos funcionários','Teste',NULL,1,1,1,1,'2024-04-17 08:05:10',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9,NULL,NULL,'Usuário inativo no sistema','',NULL,1,1,1,1,'2024-04-17 09:24:43',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(10,NULL,NULL,'','',NULL,1,1,1,1,'2024-04-17 09:25:42',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(11,NULL,NULL,'Teste','TestTste',NULL,1,1,1,1,'2024-04-17 10:50:15',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(12,NULL,NULL,'teste2','testeetstetste',NULL,1,1,1,1,'2024-04-17 11:33:30',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(13,NULL,NULL,'Chamado teste','este',NULL,1,2,3,6,'2024-04-17 20:22:34',2,1,'2024-06-13 00:43:55',NULL,NULL,NULL,NULL,NULL,1,NULL),(14,NULL,NULL,'Teste','Chamado',NULL,1,4,2,8,'2024-04-18 11:47:37',2,1,'2024-06-13 00:38:50',NULL,NULL,NULL,NULL,NULL,1,NULL),(15,NULL,NULL,'Teste','Chamado',NULL,1,2,4,8,'2024-04-18 11:49:40',2,1,'2024-06-13 00:42:56',NULL,NULL,NULL,NULL,NULL,1,NULL),(16,NULL,NULL,'Teste','Chaadmado',NULL,2,3,4,24,'2024-04-18 11:57:49',2,1,'2024-06-10 23:06:09',NULL,NULL,NULL,NULL,NULL,1,NULL),(17,NULL,NULL,'Teste','Chaadmado',NULL,2,3,4,24,'2024-04-18 11:58:38',2,1,'2024-06-13 00:38:05',NULL,NULL,NULL,NULL,NULL,1,NULL),(18,NULL,1,'Chamado teste','0000000',NULL,1,2,3,6,'2024-05-16 00:00:26',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(19,NULL,1,'Chamado teste 2','10545121',NULL,2,5,5,50,'2024-05-16 00:03:11',2,1,'2024-06-10 18:43:23',NULL,NULL,NULL,NULL,NULL,1,NULL),(20,NULL,1,'Teste','Teste',NULL,1,2,3,6,'2024-06-03 15:47:01',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(21,NULL,1,'Outro chamado teste','Another',NULL,2,5,3,30,'2024-06-03 16:20:38',2,1,'2024-06-07 23:15:48',NULL,NULL,NULL,NULL,NULL,1,NULL),(22,'ID022000000000103062024',1,'Novo chamado teste','Novo chamado teste',NULL,1,2,3,6,'2024-06-03 16:46:42',2,11,'2024-06-13 00:56:57',NULL,NULL,NULL,NULL,NULL,11,NULL),(23,'ID023103062024',1,'Novo chamado teste','Novo chamado teste 2',NULL,1,2,3,6,'2024-06-03 16:48:18',3,11,'2024-06-13 00:58:21','Testar novamente',1,2,NULL,NULL,11,NULL),(24,'ID0241030624',1,'Chamado teste 3','Chamado urgente',NULL,3,4,5,20,'2024-06-03 16:49:07',3,11,'2024-06-10 21:45:01','Testar novamente',2,1,NULL,NULL,11,NULL),(26,'ID0264070624',4,'Acesso ao sistema','Favor liberar acesso.',NULL,4,4,3,18,'2024-06-07 21:52:58',0,11,'2024-06-10 21:56:00','Acesso concedido, favor verificar.',1,3,NULL,NULL,11,NULL),(27,'ID0274070624',4,'Sistema apresenta tela de erro','Tela de erro em anexo',NULL,4,4,5,80,'2024-06-07 22:11:33',3,11,'2024-06-10 21:07:30','Resolvido',1,1,NULL,NULL,11,NULL),(28,'ID0284070624',4,'Liberar programas','Solicito acesso aos programas',NULL,1,2,3,6,'2024-06-07 22:37:19',3,1,'2024-06-13 00:58:56','Liberado',1,3,NULL,NULL,4,NULL),(29,'ID0294070624',4,'Teste','Teste',NULL,1,1,1,1,'2024-06-07 23:16:38',2,1,'2024-06-07 23:16:49',NULL,NULL,NULL,NULL,NULL,1,NULL),(30,'ID0304080624',4,'Chamado teste 08/06','Teste',NULL,1,2,3,6,'2024-06-08 18:58:02',2,1,'2024-06-09 12:30:51',NULL,NULL,NULL,NULL,NULL,1,NULL),(31,'ID0314100624',4,'Problema ao fazer login','Favor verificar erro',NULL,2,2,3,6,'2024-06-10 18:44:27',0,NULL,'2024-06-10 18:45:18',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(32,'ID0324100624',4,'Problema ao fazer login','Favor verificar erro',NULL,2,4,3,24,'2024-06-10 18:45:56',0,11,'2024-06-13 00:27:08',NULL,NULL,NULL,NULL,NULL,11,NULL),(33,'ID0334100624',4,'Problema ao fazer login','Erro',NULL,5,5,1,125,'2024-06-10 23:07:09',3,1,'2024-06-13 00:46:19','Testar',1,1,NULL,NULL,4,NULL),(34,'ID0344100624',4,'Problema ao fazer login','Erro',NULL,1,5,1,125,'2024-06-10 23:11:53',3,1,'2024-06-13 00:47:55','Testar',1,1,NULL,NULL,4,NULL),(35,'ID0354110624',4,'Teste','Teste',NULL,1,1,1,1,'2024-06-11 22:08:23',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(36,'ID0364110624',4,'Teste','Teste',NULL,1,2,3,6,'2024-06-11 22:24:25',2,1,'2024-06-13 00:39:06',NULL,NULL,NULL,NULL,NULL,1,NULL),(37,'ID0374110624',4,'Teste','Teste',NULL,4,4,5,80,'2024-06-11 22:36:39',0,NULL,'2024-06-12 23:23:47',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(38,'ID0384110624',4,'Teste','Chamado',NULL,1,1,1,1,'2024-06-11 22:39:32',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(39,'ID0394110624',4,'Teste','Chamado',NULL,1,1,1,1,'2024-06-11 22:40:52',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(40,'ID0404110624',4,'Teste 2','Chamado teste',NULL,1,2,3,6,'2024-06-11 22:42:04',2,1,'2024-06-13 00:43:16',NULL,NULL,NULL,NULL,NULL,1,NULL),(41,'ID0414110624',4,'Teste 2','Chamado teste',NULL,1,2,3,6,'2024-06-11 22:43:12',2,1,'2024-06-13 00:44:17',NULL,NULL,NULL,NULL,NULL,1,NULL),(42,'ID0424110624',4,'Teste 2','Chamado teste',NULL,1,2,3,6,'2024-06-11 22:44:05',2,1,'2024-06-13 00:45:10',NULL,NULL,NULL,NULL,NULL,1,NULL),(43,'ID0434110624',4,'Chamado teste','Erro',NULL,1,2,3,6,'2024-06-11 22:49:19',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(44,'ID0444110624',4,'Chamado teste','Erro',NULL,1,2,3,6,'2024-06-11 22:51:11',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(45,'ID0454110624',4,'Chamado teste','Erro',NULL,1,2,3,6,'2024-06-11 22:52:41',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(46,'ID0464110624',4,'Chamado teste','Teste',NULL,1,2,3,6,'2024-06-11 23:24:44',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(47,'ID0474110624',4,'Teste','Teste',NULL,1,1,1,1,'2024-06-11 23:29:00',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(48,'ID0484110624',4,'Chamado teste','Chamado teste',NULL,1,2,3,6,'2024-06-11 23:32:47',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(49,'ID0494110624',4,'Chamado teste','1234',NULL,1,1,1,1,'2024-06-11 23:35:26',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(50,'ID0504110624',4,'Chamado teste','1234',NULL,1,1,1,1,'2024-06-11 23:35:32',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
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

-- Dump completed on 2024-06-13  1:01:48
