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
END