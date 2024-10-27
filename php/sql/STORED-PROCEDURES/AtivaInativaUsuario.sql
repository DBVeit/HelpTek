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
END