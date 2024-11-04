<?php
global $mysqli_con;
include "../../config/dbconnect.php";
include "../../config/httpaccess.php";
require "../../../../vendor/autoload.php";
require "../../../vendor/autoload.php";
/*use Dotenv\Dotenv;

$_ENV = Dotenv::createImmutable(__DIR__ . '/../../..');
$_ENV->load();*/

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$res_rec = array('error' => false, 'msg' => '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['emailUser'])
        && isset($_POST['new_password_email'])
        && isset($_POST['new_password'])) {

        $emailUser = $mysqli_con->real_escape_string($_POST['emailUser']);
        $new_password_email = $mysqli_con->real_escape_string($_POST['new_password_email']);
        $new_password = $mysqli_con->real_escape_string($_POST['new_password']);

        if (empty($emailUser) || empty($new_password_email) || empty($new_password)) {
            $res_rec['error'] = true;
            $res_rec['msg'] = "Parâmetros inválidos ou campos obrigatórios faltando!";
        } else {

            $sql = "SELECT * FROM users WHERE email_user='$emailUser' AND status_user=1";
            $result = $mysqli_con->query($sql);
            $obj = $result->fetch_object();

            if ($result->num_rows == 1) {
                $idDB = $obj->id_user;

                $mysqli_con->begin_transaction();

                try {
                    // Atualizar a senha no banco de dados
                    $sql = "UPDATE users SET password_user='$new_password', troca_senha=1 WHERE id_user='$idDB'";

                    if ($mysqli_con->query($sql)) {
                        $mysqli_con->commit();

                        $res_rec['msg'] = "Nova senha: ".$new_password_email;
                        // Enviar e-mail de recuperação de senha
                        /*$mail = new PHPMailer(true);

                        try {
                            // Configurações do servidor SMTP
                            /*$mail->isSMTP();
                            $mail->Host = $_ENV['SMTP_HOST']; // Defina o servidor SMTP (exemplo: smtp.gmail.com)
                            $mail->SMTPAuth = true;
                            $mail->Username = $_ENV['SMTP_USER']; // Usuário SMTP
                            $mail->Password = $_ENV['SMTP_PASS']; // Senha SMTP
                            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                            $mail->Port = $_ENV['SMTP_PORT']; // Porta SMTP (587 para TLS)

                            // Recipiente
                            $mail->setFrom($_ENV['SMTP_FROM'], $_ENV['SMTP_NAME']);
                            $mail->addAddress($emailUser);

                            // Conteúdo do e-mail
                            $mail->isHTML(true);
                            $mail->Subject = 'Recuperação de Senha';
                            $mail->Body = "Sua nova senha é: " . $new_password_email;

                            // Enviar e-mail
                            $mail->send();
                            $mail->SMTPDebug = 2; // Nível de depuração do SMTP
                            $mail->Debugoutput = 'html'; // Formato da saída de depuração

                            $res_rec['msg'] = "E-Mail para recuperação de acesso enviado, verifique sua caixa de entrada.";
                        } catch (Exception $e_mail) {
                            //$mysqli_con->rollback();
                            $res_rec['error'] = true;
                            $res_rec['msg'] = "Erro ao enviar o e-mail. Mailer Error: {$mail->ErrorInfo}";
                        }*/
                    }
                } catch (Exception $e_transaction) {
                    //$mysqli_con->rollback();
                    $res_rec['error'] = true;
                    $res_rec['msg'] = "Erro ao executar transação: " . $e_transaction->getMessage();
                }
            } else {
                $res_rec['error'] = true;
                $res_rec['msg'] = "E-Mail não encontrado na base de dados!";
            }
        }
    } else {
        $res_rec['error'] = true;
        $res_rec['msg'] = "Erro ao receber dados de preenchimento!";
    }
} else {
    $res_rec['error'] = true;
    $res_rec['msg'] = "Método de requisição inválido!";
}
$mysqli_con->close();
header("Content-type: application/json");
echo json_encode($res_rec);
die();
?>