CREATE DATABASE  IF NOT EXISTS `helptek` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `helptek`;
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
-- Table structure for table `permissoes_menus`
--

DROP TABLE IF EXISTS `permissoes_menus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissoes_menus` (
  `id_permissao_menu` int NOT NULL AUTO_INCREMENT,
  `id_permissao` int DEFAULT NULL,
  `id_menu` int DEFAULT NULL,
  `descricao_permissao` varchar(45) DEFAULT NULL,
  `descricao_menu` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id_permissao_menu`),
  KEY `fk_id_permissao_idx` (`id_permissao`),
  KEY `fk_id_menu_idx` (`id_menu`),
  KEY `fk_descricao_permissao_idx` (`descricao_permissao`),
  CONSTRAINT `fk_id_menu` FOREIGN KEY (`id_menu`) REFERENCES `menus` (`id_menu`),
  CONSTRAINT `fk_id_permissao` FOREIGN KEY (`id_permissao`) REFERENCES `permissoes` (`id_permissao`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissoes_menus`
--

LOCK TABLES `permissoes_menus` WRITE;
/*!40000 ALTER TABLE `permissoes_menus` DISABLE KEYS */;
INSERT INTO `permissoes_menus` VALUES (1,1,1,'Solicitante','Atualizacoes'),(2,1,2,'Solicitante','Criar chamado'),(3,1,3,'Solicitante','Meus chamados'),(4,1,7,'Solicitante','Perfil'),(5,2,1,'Tecnico','Atualizacoes'),(6,2,3,'Tecnico','Meus chamados'),(7,2,4,'Tecnico','Todos os chamados'),(8,2,7,'Tecnico','Perfil'),(9,3,1,'Gerente','Atualizacoes'),(10,3,4,'Gerente','Todos os chamados'),(11,3,5,'Gerente','Dashboard'),(12,3,6,'Gerente','Relatorios'),(13,3,7,'Gerente','Perfil'),(14,4,1,'Administrador','Atualizacoes'),(15,4,7,'Administrador','Perfil'),(16,4,8,'Administrador','Configuracoes do sistema'),(17,4,9,'Administrador','Configuracoes de usuarios');
/*!40000 ALTER TABLE `permissoes_menus` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-05-16  1:42:26
