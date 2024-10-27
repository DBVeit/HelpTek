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
            observacao = p_observacao, 
            total_acoes = v_total_acoes 
        WHERE id_chamado = p_id_chamado;
    END IF;

    -- Inserir acompanhamento do chamado
    INSERT INTO acompanhamento (id_chamado, id_user, id_user_tecnico, idfr_code_user, idfr_chamado, acao, descricao_acao, id_usuario_acao, status_chamado)
    VALUES (p_id_chamado, v_id_user, v_id_user_tecnico, v_idfr_code_user, v_idfr_chamado, v_acao, p_observacao, p_id_user, v_status_chamado);

    -- Confirmar a transação
    COMMIT;
END