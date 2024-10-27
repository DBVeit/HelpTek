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
END