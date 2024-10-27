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
END