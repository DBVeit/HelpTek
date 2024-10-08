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
-- Dumping events for database 'helptek'
--

--
-- Dumping routines for database 'helptek'
--
/*!50003 DROP PROCEDURE IF EXISTS `AtivaInativaUsuario` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `AtivaInativaUsuario`(
    IN p_id_user INT,
    IN p_status_user INT
)
BEGIN
	-- Iniciar transação
    START TRANSACTION;
     
     UPDATE users
        SET 
            status_user = p_status_user,
            troca_senha = 1,
            user_logado = 0
        WHERE id_user = p_id_user;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `CancelarChamado` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `CancelarChamado`(
    IN p_id_chamado INT,
    IN p_id_user INT,
    IN p_idfr_chamado VARCHAR(50),
    IN p_observacao VARCHAR(500),
    IN p_idfr_code_user VARCHAR(50)
)
BEGIN
    -- Declarar variáveis
    DECLARE v_status_chamado INT;
    DECLARE v_total_acoes INT;
    DECLARE v_id_user_tecnico INT;
    DECLARE v_acao VARCHAR(255);
    DECLARE EXIT HANDLER FOR SQLEXCEPTION 
    BEGIN
        -- Em caso de erro, realizar rollback
        ROLLBACK;
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Erro ao cancelar o chamado!';
    END;

    -- Iniciar transação
    START TRANSACTION;

    -- Verificar o status do chamado
    SELECT status_chamado, total_acoes, id_user_tecnico 
    INTO v_status_chamado, v_total_acoes, v_id_user_tecnico 
    FROM chamados 
    WHERE id_chamado = p_id_chamado;

    -- Verificar se o chamado já está cancelado ou concluído
    IF v_status_chamado != 4 AND v_status_chamado != 0 THEN
        -- Atualizar o chamado para status cancelado
        UPDATE chamados 
        SET 
            status_chamado = 0, -- 0 significa "cancelado"
            observacao = p_observacao,
            data_conclusao = NOW(),
            data_atualizacao = NOW(), 
            total_acoes = v_total_acoes + 1
        WHERE id_chamado = p_id_chamado;

        -- Registrar o cancelamento no acompanhamento
        SET v_acao = CONCAT('USUARIO ', p_idfr_code_user, ' CANCELOU O CHAMADO ', p_idfr_chamado);
        
        INSERT INTO acompanhamento (
            id_chamado, 
            id_user, 
            idfr_chamado, 
            acao, 
            descricao_acao, 
            id_usuario_acao,
            status_chamado
        ) 
        VALUES (
            p_id_chamado, 
            p_id_user, 
            p_idfr_chamado, 
            v_acao, 
            p_observacao, 
            p_id_user, 
            0
        );

        -- Commit da transação
        COMMIT;
    ELSE
        -- Se o chamado já está concluído ou cancelado, lançar erro
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Chamado já está concluído ou cancelado!';
    END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `InserirChamado` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `InserirChamado`(
	IN p_id_user INT,
    IN p_titulo_chamado VARCHAR(200),
    IN p_descricao_chamado VARCHAR(500),
    IN p_id_setor INT,
    IN p_gravidade INT,
    IN p_urgencia INT,
    IN p_tendencia INT,
    IN p_prioridade_chamado INT,
    IN p_idfr_code_user VARCHAR(50),
    IN p_anexos JSON -- JSON array com os arquivos anexos (nome, caminho, tamanho)
)
BEGIN
	DECLARE v_id_chamado INT;
    DECLARE v_idfr_chamado VARCHAR(50);
    DECLARE v_acao VARCHAR(255);
    DECLARE i INT DEFAULT 0;
    DECLARE v_anexo_nome VARCHAR(255);
    DECLARE v_anexo_caminho VARCHAR(255);
    DECLARE v_anexo_tamanho INT;
    
     -- Iniciar transação
    START TRANSACTION;

    -- Inserir novo chamado
	INSERT INTO chamados(id_user,titulo_chamado,descricao_chamado,id_setor,gravidade,urgencia,tendencia,prioridade_chamado,data_atualizacao,id_usuario_atual,total_acoes)
	VALUES(p_id_user,p_titulo_chamado,p_descricao_chamado,p_id_setor,p_gravidade,p_urgencia,p_tendencia,p_prioridade_chamado,NOW(),p_id_user,1);
	
    -- Obter o último ID inserido
    SET v_id_chamado = LAST_INSERT_ID();

	-- Montar identificador de chamado
    SET v_idfr_chamado = CONCAT('ID0', v_id_chamado, p_id_user, DATE_FORMAT(NOW(), '%d%m%y'));
    
    -- Atualizar chamado com o IDFR
    UPDATE chamados SET idfr_chamado = v_idfr_chamado WHERE id_chamado = v_id_chamado;

    -- Montar ação
    SET v_acao = CONCAT('USUARIO ', p_idfr_code_user, ' REGISTROU O CHAMADO ', v_idfr_chamado);
    
     -- Inserir acompanhamento do chamado
    INSERT INTO acompanhamento (id_chamado, id_user, idfr_code_user, idfr_chamado, acao, descricao_acao, id_usuario_acao, status_chamado)
    VALUES (v_id_chamado, p_id_user, p_idfr_code_user, v_idfr_chamado, v_acao, p_descricao_chamado, p_id_user, 1);
    
    -- Se houver anexos, inserir na tabela anexos_chamados
    IF JSON_LENGTH(p_anexos) > 0 THEN
        WHILE i < JSON_LENGTH(p_anexos) DO
            -- Extrair valores dos anexos
            SET v_anexo_nome = JSON_UNQUOTE(JSON_EXTRACT(p_anexos, CONCAT('$[', i, '].nome')));
            SET v_anexo_caminho = JSON_UNQUOTE(JSON_EXTRACT(p_anexos, CONCAT('$[', i, '].caminho')));
            SET v_anexo_tamanho = JSON_UNQUOTE(JSON_EXTRACT(p_anexos, CONCAT('$[', i, '].tamanho')));

            -- Inserir o anexo na tabela anexos_chamados
            INSERT INTO anexos_chamados (id_chamado, nome_arquivo, caminho_arquivo, tamanho_arquivo)
            VALUES(v_id_chamado, v_anexo_nome, v_anexo_caminho, v_anexo_tamanho);

            -- Incrementar contador
            SET i = i + 1;
        END WHILE;
    END IF;

    -- Commit da transação
    COMMIT;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `RedefineSenha` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `RedefineSenha`(
    IN p_id_user INT,
    IN p_senha VARCHAR(256)
)
BEGIN
	-- Iniciar transação
    START TRANSACTION;
     
     UPDATE users
        SET 
            password_user = p_senha,
            troca_senha = 1,
            user_logado = 0
        WHERE id_user = p_id_user;
	END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-10-07 22:37:43
