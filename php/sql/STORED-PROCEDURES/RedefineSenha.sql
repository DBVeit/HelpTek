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
	END