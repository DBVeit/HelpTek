<?php
include "../../config/dbconnect.php";
include "../../config/httpaccess.php";
require '../../vendor/autoload.php'; //  PHPMailer 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$res = array('error' => false, 'msg' => '');

if (isset($_GET['action'])) {
    $action = $_GET['action'];

    if ($action == "recover") {
        if (isset($_POST['emailUser'])) {
            $emailUser = $mysqli_con->real_escape_string($_POST['emailUser']);
            $sql = "SELECT * FROM users WHERE email_user='$emailUser' AND status_user=1";
            $result = $mysqli_con->query($sql);
            $obj = $result->fetch_object();

            if ($result->num_rows > 0) {
                $idDB = $obj->id_user;

                // Gerar token de reset de senha
                $resetToken = bin2hex(random_bytes(16));
                $resetTokenExpiration = date('Y-m-d H:i:s', strtotime('+1 hour'));

                // Armazenar o token e a data de expiração no banco de dados
                $sql = "UPDATE users SET reset_token='$resetToken', reset_token_expiration='$resetTokenExpiration' WHERE id_user='$idDB'";
                $result = $mysqli_con->query($sql);

                // Enviar e-mail com o link de reset de senha
                if ($result) {
                    if (sendPasswordResetEmail($emailUser, $resetToken)) {
                        $res['msg'] = "E-Mail para recuperação de acesso enviado, verifique sua caixa de entrada.";
                    } else {
                        $res['error'] = true;
                        $res['msg'] = "Erro ao enviar o e-mail de recuperação.";
                    }
                } else {
                    $res['error'] = true;
                    $res['msg'] = "Erro ao atualizar o banco de dados.";
                }
            } else {
                $res['error'] = true;
                $res['msg'] = "E-Mail não encontrado!";
            }
        } else {
            $res['error'] = true;
            $res['msg'] = "E-Mail não encontrado!";
        }
    } else {
        $res['error'] = true;
        $res['msg'] = "Parâmetros inválidos!";
    }
}

$mysqli_con->close();
header("Content-type: application/json");
echo json_encode($res);
die();

function sendPasswordResetEmail($userEmail, $resetToken) {
    $mail = new PHPMailer(true);
    
    try {
        // Configurações do servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // 
        $mail->SMTPAuth = true;
        $mail->Username = 'contato.helptek@gmail.com'; //  E-mail SMTP
        $mail->Password = 'Abcd@123a'; // Senha SMTP
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587; // Porta SMTP

        // Configurações do remetente
        $mail->setFrom('contato.helptek@gmail.com', 'HelpTek');
        $mail->addAddress($userEmail);

        // Conteúdo do e-mail
        $mail->isHTML(true);
        $mail->Subject = 'Reset de Senha - HelpTek';
        $mail->Body = 'Clique no link para resetar sua senha: <a href="http://helptek.com/reset_password.php?token=' . $resetToken . '">Resetar Senha</a>';
        
        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
?>
