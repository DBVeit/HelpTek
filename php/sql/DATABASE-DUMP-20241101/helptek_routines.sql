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
/*!50003 DROP PROCEDURE IF EXISTS `AssumirChamado` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `AssumirChamado`(
    IN p_id_chamado INT,
    IN p_id_user_tecnico INT
)
BEGIN
    DECLARE v_idfr_chamado VARCHAR(255);
    DECLARE v_id_user INT;
    DECLARE v_id_usuario_atual INT;
    DECLARE v_total_acoes INT;
    DECLARE v_idfr_code_user VARCHAR(255);
    DECLARE v_status_chamado INT;
    
    -- Iniciar a transação
    START TRANSACTION;

    -- Busca as informações do chamado
    SELECT idfr_chamado, id_user, id_usuario_atual, total_acoes, status_chamado
    INTO v_idfr_chamado, v_id_user, v_id_usuario_atual, v_total_acoes, v_status_chamado
    FROM chamados
    WHERE id_chamado = p_id_chamado;

    -- Verifica se o chamado foi encontrado
    IF v_idfr_chamado IS NOT NULL THEN
		-- Validar se o chamado já foi assumido (status em atendimento)
		IF v_status_chamado = 2 THEN
			-- O chamado já está em atendimento, então retorna um sinal de erro
            ROLLBACK;
			SIGNAL SQLSTATE '45000' 
			SET MESSAGE_TEXT = 'Este chamado já foi assumido por outro técnico.';
		ELSE
			-- Incrementa o contador de ações
			SET v_total_acoes = v_total_acoes + 1;

			-- Atualiza o chamado com o técnico assumindo
			UPDATE chamados
			SET id_user_tecnico = p_id_user_tecnico,
				status_chamado = 2,
				id_usuario_atual = p_id_user_tecnico,
				id_usuario_anterior = v_id_user, -- Define o usuário que abriu o chamado como usuário anterior
				data_atualizacao = NOW(),
				total_acoes = v_total_acoes
			WHERE id_chamado = p_id_chamado;

			-- Busca o código do usuário técnico
			SELECT idfr_code_user
			INTO v_idfr_code_user
			FROM users
			WHERE id_user = p_id_user_tecnico;

			-- Insere o registro de acompanhamento
			INSERT INTO acompanhamento (
				id_chamado,
				id_user,
				id_user_tecnico,
				idfr_code_user,
				idfr_chamado,
				acao,
				id_usuario_acao,
				status_chamado
			) VALUES (
				p_id_chamado,
				v_id_user, -- Usuário que abriu o chamado
				p_id_user_tecnico,
				v_idfr_code_user,
				v_idfr_chamado,
				CONCAT('USUARIO ', v_idfr_code_user, ' ASSUMIU O CHAMADO ', v_idfr_chamado),
				p_id_user_tecnico,
				2
			);
            COMMIT;
		END IF;
    ELSE
		ROLLBACK;
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Chamado não encontrado.';
    END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `AvaliarAtendimento` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `AvaliarAtendimento`(
    IN p_id_chamado INT,
    IN p_id_user INT,
    IN p_solicitacao_atendida TINYINT,
    IN p_observacao VARCHAR(255)
)
BEGIN
    DECLARE v_idfr_code_user VARCHAR(50);
    DECLARE v_idfr_chamado VARCHAR(50);
    DECLARE v_id_user INT;
    DECLARE v_id_user_tecnico INT;
    DECLARE v_total_acoes INT;
    DECLARE v_status_chamado INT;
    DECLARE v_acao VARCHAR(255);

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
    END;

    -- Início da transação
    START TRANSACTION;

    -- Obter informações do usuário
    SELECT idfr_code_user INTO v_idfr_code_user 
    FROM users 
    WHERE id_user = p_id_user;

    -- Obter informações do chamado
    SELECT idfr_chamado, id_user, id_user_tecnico, total_acoes 
    INTO v_idfr_chamado, v_id_user, v_id_user_tecnico, v_total_acoes
    FROM chamados 
    WHERE id_chamado = p_id_chamado;

    -- Incrementar o contador de ações
    SET v_total_acoes = v_total_acoes + 1;

    -- Avaliar atendimento e atualizar o chamado
    IF p_solicitacao_atendida = 1 THEN
        SET v_status_chamado = 4;
        SET v_acao = CONCAT('USUARIO ', v_idfr_code_user, ' AVALIOU O CHAMADO ', v_idfr_chamado, ' COMO SOLICITAÇÃO ATENDIDA');
        
        UPDATE chamados 
        SET status_chamado = v_status_chamado, 
            data_atualizacao = NOW(), 
            data_conclusao = NOW(), 
            solicitacao_atendida = p_solicitacao_atendida,
            observacao = p_observacao, 
            total_acoes = v_total_acoes 
        WHERE id_chamado = p_id_chamado;
    ELSE
        SET v_status_chamado = 2;
        SET v_acao = CONCAT('USUARIO ', v_idfr_code_user, ' AVALIOU O CHAMADO ', v_idfr_chamado, ' COMO SOLICITAÇÃO NÃO ATENDIDA');
        
        UPDATE chamados 
        SET status_chamado = v_status_chamado, 
            id_usuario_atual = v_id_user_tecnico, 
            id_usuario_anterior = v_id_user, 
            data_atualizacao = NOW(), 
            solicitacao_atendida = p_solicitacao_atendida,
            observacao = p_observacao, 
            total_acoes = v_total_acoes 
        WHERE id_chamado = p_id_chamado;
    END IF;

    -- Inserir acompanhamento do chamado
    INSERT INTO acompanhamento (id_chamado, id_user, id_user_tecnico, idfr_code_user, idfr_chamado, acao, descricao_acao, id_usuario_acao, status_chamado)
    VALUES (p_id_chamado, v_id_user, v_id_user_tecnico, v_idfr_code_user, v_idfr_chamado, v_acao, p_observacao, p_id_user, v_status_chamado);

    -- Confirmar a transação
    COMMIT;
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
    IN p_observacao_cancelamento VARCHAR(500),
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
            observacao_cancelamento = p_observacao_cancelamento,
            data_conclusao = NOW(),
            data_atualizacao = NOW(), 
            total_acoes = v_total_acoes + 1
        WHERE id_chamado = p_id_chamado;

        -- Registrar o cancelamento no acompanhamento
        SET v_acao = CONCAT('USUARIO ', p_idfr_code_user, ' CANCELOU O CHAMADO ', p_idfr_chamado);
        
        INSERT INTO acompanhamento (
            id_chamado, 
            id_user, 
            idfr_code_user,
            idfr_chamado, 
            acao, 
            descricao_acao, 
            id_usuario_acao,
            status_chamado
        ) 
        VALUES (
            p_id_chamado, 
            p_id_user, 
            p_idfr_code_user,
            p_idfr_chamado, 
            v_acao, 
            p_observacao_cancelamento, 
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
/*!50003 DROP PROCEDURE IF EXISTS `DetalharChamado` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `DetalharChamado`(
    IN p_id_chamado INT,
    IN p_id_user INT,
    IN p_observacao_detalhamento_solicitante VARCHAR(255)
)
BEGIN
    DECLARE v_idfr_code_user VARCHAR(50);
    DECLARE v_idfr_chamado VARCHAR(50);
    DECLARE v_id_user INT;
    DECLARE v_id_user_tecnico INT;
    DECLARE v_total_acoes INT;
    DECLARE v_status_chamado INT;
    DECLARE v_acao VARCHAR(255);
    DECLARE v_row_count INT DEFAULT 0;

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Erro na transação.';
    END;

    -- Início da transação
    START TRANSACTION;
    
    -- Obter informações do chamado e validar o status
    SELECT idfr_chamado, id_user, id_user_tecnico, total_acoes, status_chamado 
    INTO v_idfr_chamado, v_id_user, v_id_user_tecnico, v_total_acoes, v_status_chamado
    FROM chamados 
    WHERE id_chamado = p_id_chamado;

    -- Verificar se o chamado está no status correto (5) antes de atualizar
    IF v_status_chamado != 5 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Status do chamado não permite atualização.';
    END IF;

    -- Incrementar o contador de ações
    SET v_total_acoes = v_total_acoes + 1;
    
    UPDATE chamados 
    SET observacao_detalhamento_solicitante = p_observacao_detalhamento_solicitante,
        id_usuario_atual = v_id_user_tecnico,
        id_usuario_anterior = p_id_user,
        data_atualizacao = NOW(),
        status_chamado = 2,
        total_acoes = v_total_acoes
    WHERE id_chamado = p_id_chamado;
    
    -- Verifique se o UPDATE foi bem-sucedido
    SET v_row_count = ROW_COUNT();
    IF v_row_count = 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Update falhou: nenhum registro atualizado.';
    END IF;
    
    -- Configurar ação para o acompanhamento
    SELECT idfr_code_user INTO v_idfr_code_user FROM users WHERE id_user = p_id_user;
    SET v_acao = CONCAT('USUARIO ', v_idfr_code_user, ' RESPONDEU AO DETALHAMENTO DO CHAMADO ', v_idfr_chamado);
    
    INSERT INTO acompanhamento (
        id_chamado, 
        id_user, 
        id_user_tecnico, 
        idfr_code_user, 
        idfr_chamado, 
        acao, 
        descricao_acao, 
        id_usuario_acao, 
        status_chamado
    ) VALUES (
        p_id_chamado, 
        v_id_user, 
        v_id_user_tecnico, 
        v_idfr_code_user, 
        v_idfr_chamado, 
        v_acao, 
        p_observacao_detalhamento_solicitante, 
        p_id_user, 
        2
    );

    -- Confirmar a transação
    COMMIT;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `EditarChamado` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `EditarChamado`(
	IN p_id_chamado INT,
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
    DECLARE v_status_chamado INT;
    DECLARE v_total_acoes INT;
    DECLARE v_acao VARCHAR(255);
    DECLARE i INT DEFAULT 0;
    DECLARE v_anexo_url VARCHAR(255);
    
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Erro ao editar o chamado.';
    END;
    
     -- Iniciar transação
    START TRANSACTION;
    
    -- Verificar o status do chamado
    SELECT status_chamado, total_acoes, idfr_chamado 
    INTO v_status_chamado, v_total_acoes, v_idfr_chamado 
    FROM chamados 
    WHERE id_chamado = p_id_chamado;
    
    -- Verificar se o chamado já está cancelado ou concluído
    IF v_status_chamado != 4 AND v_status_chamado != 0 THEN
		
        UPDATE chamados
		SET
			titulo_chamado = p_titulo_chamado,
            descricao_chamado = p_descricao_chamado,
            id_setor = p_id_setor,
            gravidade = p_gravidade,
            urgencia = p_urgencia,
            tendencia = p_tendencia,
            prioridade_chamado = p_prioridade_chamado,
            data_atualizacao = NOW(),
            total_acoes = v_total_acoes + 1
		WHERE id_chamado = p_id_chamado;
        
        -- Registrar no acompanhamento
        SET v_acao = CONCAT('USUARIO ', p_idfr_code_user, ' ATUALIZOU O CHAMADO ', v_idfr_chamado);
        
        INSERT INTO acompanhamento (
            id_chamado, 
            id_user, 
            idfr_code_user,
            idfr_chamado, 
            acao, 
            descricao_acao, 
            id_usuario_acao,
            status_chamado
        ) 
        VALUES (
            p_id_chamado, 
            p_id_user, 
            p_idfr_code_user,
            v_idfr_chamado, 
            v_acao, 
            p_descricao_chamado, 
            p_id_user, 
            v_status_chamado
        );
		-- Se houver anexos, inserir na tabela anexos_chamados
		IF JSON_LENGTH(p_anexos) > 0 THEN
            WHILE i < JSON_LENGTH(p_anexos) DO
                -- Extrair URL do anexo
                SET v_anexo_url = JSON_UNQUOTE(JSON_EXTRACT(p_anexos, CONCAT('$[', i, ']')));

                -- Inserir o anexo na tabela anexos_chamados
                INSERT INTO anexos_chamados (id_chamado, caminho_arquivo)
                VALUES (p_id_chamado, v_anexo_url);

                -- Incrementar contador
                SET i = i + 1;
            END WHILE;
        END IF;
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
/*!50003 DROP PROCEDURE IF EXISTS `EncaminharChamado` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `EncaminharChamado`(
    IN p_id_chamado INT,
    IN p_id_user_tecnico INT,
    IN p_novo_tecnico_responsavel INT,
    IN p_justificativa_encaminhamento VARCHAR(255),
    IN p_permission INT
)
BEGIN
    DECLARE v_idfr_code_user_atual VARCHAR(50);
    DECLARE v_idfr_code_user_novo VARCHAR(50);
    DECLARE v_id_user INT;
    DECLARE v_idfr_chamado VARCHAR(50);
    DECLARE v_status_chamado INT;
    DECLARE v_total_acoes INT;
    DECLARE v_acao VARCHAR(255);

    DECLARE EXIT HANDLER FOR SQLEXCEPTION 
    BEGIN
        ROLLBACK;
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Erro ao encaminhar chamado.';
    END;

    -- Inicia a transação
    START TRANSACTION;
    
    -- Obtém informações do chamado
    SELECT id_user, idfr_chamado, status_chamado, total_acoes
    INTO v_id_user, v_idfr_chamado, v_status_chamado, v_total_acoes
    FROM chamados WHERE id_chamado = p_id_chamado;
    
    IF v_idfr_chamado IS NOT NULL THEN
		-- Obtém informações do técnico atual
		SELECT idfr_code_user INTO v_idfr_code_user_atual
		FROM users WHERE id_user = p_id_user_tecnico;

		-- Obtém informações do novo técnico responsável
		SELECT idfr_code_user INTO v_idfr_code_user_novo
		FROM users WHERE id_user = p_novo_tecnico_responsavel;

		-- Incrementa o contador de ações
		SET v_total_acoes = v_total_acoes + 1;

		-- Define a descrição da ação de encaminhamento
		SET v_acao = CONCAT('USUARIO ', v_idfr_code_user_atual, ' ENCAMINHOU O CHAMADO ', v_idfr_chamado, ' AO USUARIO ', v_idfr_code_user_novo);

		-- Insere o registro de acompanhamento
		INSERT INTO acompanhamento (
			id_chamado, 
			id_user, 
			id_user_tecnico, 
			idfr_code_user, 
			idfr_chamado, 
			acao, 
			descricao_acao, 
			id_usuario_acao, 
			status_chamado
		) VALUES (
			p_id_chamado, 
			v_id_user, 
			p_id_user_tecnico, 
			v_idfr_code_user_atual, 
			v_idfr_chamado, 
			v_acao, 
			p_justificativa_encaminhamento, 
			p_id_user_tecnico, 
			v_status_chamado
		);
        
        -- Atualizar status apenas se o usuário for gerente (permission = 3) e o chamado estiver "Em aberto" (status = 1)
        IF p_permission = 3 AND v_status_chamado = 1 THEN
            UPDATE chamados
            SET status_chamado = 2,
                id_user_tecnico = p_novo_tecnico_responsavel,
                id_usuario_atual = p_novo_tecnico_responsavel,
                id_usuario_anterior = v_id_user,
                justificativa_encaminhamento = p_justificativa_encaminhamento,
                data_atualizacao = NOW(),
                total_acoes = v_total_acoes
            WHERE id_chamado = p_id_chamado;
		ELSE
			-- Atualiza o chamado com o novo técnico responsável e incrementa o total de ações
			UPDATE chamados
			SET 
				id_user_tecnico = p_novo_tecnico_responsavel,
				id_usuario_atual = p_novo_tecnico_responsavel,
				id_usuario_anterior = p_id_user_tecnico,
				justificativa_encaminhamento = p_justificativa_encaminhamento,
				data_atualizacao = NOW(),
				total_acoes = v_total_acoes
			WHERE id_chamado = p_id_chamado;
		END IF;

		-- Confirma a transação
		COMMIT;
    ELSE
        -- Chamado não encontrado, fazer rollback e lançar erro
        ROLLBACK;
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Chamado não encontrado.';
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
    DECLARE v_anexo_url VARCHAR(255);
    -- DECLARE v_anexo_nome VARCHAR(255);
    -- DECLARE v_anexo_caminho VARCHAR(255);
    -- DECLARE v_anexo_tamanho INT;
    
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
        
			-- Extrair URL do anexo
            SET v_anexo_url = JSON_UNQUOTE(JSON_EXTRACT(p_anexos, CONCAT('$[', i, ']')));
            -- Extrair valores dos anexos
            -- SET v_anexo_nome = JSON_UNQUOTE(JSON_EXTRACT(p_anexos, CONCAT('$[', i, '].nome')));
            -- SET v_anexo_caminho = JSON_UNQUOTE(JSON_EXTRACT(p_anexos, CONCAT('$[', i, '].caminho')));
            -- SET v_anexo_tamanho = JSON_UNQUOTE(JSON_EXTRACT(p_anexos, CONCAT('$[', i, '].tamanho')));

            -- Inserir o anexo na tabela anexos_chamados
            -- INSERT INTO anexos_chamados (id_chamado, nome_arquivo, caminho_arquivo, tamanho_arquivo)
            -- VALUES(v_id_chamado, v_anexo_nome, v_anexo_caminho, v_anexo_tamanho);
            
            -- Inserir o anexo na tabela anexos_chamados
            INSERT INTO anexos_chamados (id_chamado, caminho_arquivo)
            VALUES(v_id_chamado, v_anexo_url);

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
/*!50003 DROP PROCEDURE IF EXISTS `ResponderChamado` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `ResponderChamado`(
    IN p_id_chamado INT,
    IN p_categoria_servico INT,
    IN p_categoria_ocorrencia INT,
    IN p_descricao_solucao VARCHAR(500),
    IN p_id_user_tecnico INT
)
BEGIN
	DECLARE v_idfr_chamado VARCHAR(50);
    DECLARE v_id_user_chamado INT;
    DECLARE v_total_acoes INT;
    DECLARE v_idfr_code_user VARCHAR(50);
    DECLARE v_acao VARCHAR(255);
    DECLARE EXIT HANDLER FOR SQLEXCEPTION 
    BEGIN
        -- Em caso de erro, realizar rollback
        ROLLBACK;
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Erro ao responder chamado!';
    END;

    -- Iniciar transação
    START TRANSACTION;
    
     -- Validação dos parâmetros
    IF p_id_chamado IS NULL OR p_categoria_servico IS NULL OR 
       p_categoria_ocorrencia IS NULL OR CHAR_LENGTH(p_descricao_solucao) > 500 OR
       p_id_user_tecnico IS NULL THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Parâmetros inválidos para responder chamado';
    END IF;

    SELECT idfr_chamado, id_user, total_acoes 
    INTO v_idfr_chamado, v_id_user_chamado, v_total_acoes
    FROM chamados 
    WHERE id_chamado = p_id_chamado;

    SET v_total_acoes = v_total_acoes + 1;

    UPDATE chamados 
    SET descricao_solucao = p_descricao_solucao,
        id_categoria_servico = p_categoria_servico,
        id_categoria_ocorrencia = p_categoria_ocorrencia,
        id_usuario_atual = v_id_user_chamado,
        id_usuario_anterior = p_id_user_tecnico,
        data_atualizacao = NOW(),
        status_chamado = 3,
        total_acoes = v_total_acoes
    WHERE id_chamado = p_id_chamado;
    
    SELECT idfr_code_user INTO v_idfr_code_user FROM users WHERE id_user = p_id_user_tecnico;
    
    SET v_acao = CONCAT('USUARIO ', v_idfr_code_user, ' RESPONDEU AO CHAMADO ', v_idfr_chamado);
    
    INSERT INTO acompanhamento (
        id_chamado, 
        id_user, 
        id_user_tecnico, 
        idfr_code_user, 
        idfr_chamado, 
        acao, 
        descricao_acao, 
        id_usuario_acao, 
        status_chamado
    ) VALUES (
        p_id_chamado, 
        v_id_user_chamado, 
        p_id_user_tecnico, 
        v_idfr_code_user, 
        v_idfr_chamado, 
        v_acao, 
        p_descricao_solucao, 
        p_id_user_tecnico, 
        3
    );

    COMMIT;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `SolicitarDetalhamento` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `SolicitarDetalhamento`(
    IN p_id_chamado INT,
    IN p_idfr_chamado VARCHAR(50),
    IN p_observacao_detalhamento_tecnico VARCHAR(500),
    IN p_id_user_tecnico INT
)
BEGIN
	DECLARE v_idfr_chamado VARCHAR(50);
    DECLARE v_id_user_chamado INT;
    DECLARE v_total_acoes INT;
    DECLARE v_idfr_code_user VARCHAR(50);
    DECLARE v_acao VARCHAR(255);
    DECLARE EXIT HANDLER FOR SQLEXCEPTION 
    BEGIN
        -- Em caso de erro, realizar rollback
        ROLLBACK;
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Erro ao solicitar detalhamento!';
    END;

    -- Iniciar transação
    START TRANSACTION;
    
    -- Validação dos parâmetros
    IF p_id_chamado IS NULL OR p_observacao_detalhamento_tecnico IS NULL OR 
       p_id_user_tecnico IS NULL THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Parâmetros inválidos para solicitar detalhamento';
    END IF;

    SELECT idfr_chamado, id_user, total_acoes 
    INTO v_idfr_chamado, v_id_user_chamado, v_total_acoes
    FROM chamados 
    WHERE id_chamado = p_id_chamado;

    SET v_total_acoes = v_total_acoes + 1;

    UPDATE chamados 
    SET observacao_detalhamento_tecnico = p_observacao_detalhamento_tecnico,
        id_usuario_atual = v_id_user_chamado,
        id_usuario_anterior = p_id_user_tecnico,
        data_atualizacao = NOW(),
        status_chamado = 5,
        total_acoes = v_total_acoes
    WHERE id_chamado = p_id_chamado;
    
    SELECT idfr_code_user INTO v_idfr_code_user FROM users WHERE id_user = p_id_user_tecnico;
    
    SET v_acao = CONCAT('USUARIO ', v_idfr_code_user, ' SOLICITOU DETALHAMENTO AO CHAMADO ', v_idfr_chamado);
    
    INSERT INTO acompanhamento (
        id_chamado, 
        id_user, 
        id_user_tecnico, 
        idfr_code_user, 
        idfr_chamado, 
        acao, 
        descricao_acao, 
        id_usuario_acao, 
        status_chamado
    ) VALUES (
        p_id_chamado, 
        v_id_user_chamado, 
        p_id_user_tecnico, 
        v_idfr_code_user, 
        v_idfr_chamado, 
        v_acao, 
        p_observacao_detalhamento_tecnico, 
        p_id_user_tecnico, 
        5
    );

    COMMIT;
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

-- Dump completed on 2024-11-01  0:38:00
