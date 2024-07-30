<?php
// Inclua o autoloader do Composer
require '../../vendor/autoload.php';
include "../../config/dbconnect.php";
include "../../config/httpaccess.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Permitir requisições de diferentes origens (CORS)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// Obter dados da solicitação POST
$postData = file_get_contents('php://input');
$request = json_decode($postData);

if (isset($request->email)) {
    $email = $request->email;

    // Verifique se o email existe no banco de dados (considerando 'email_user' como campo de email)
    $sql = "SELECT * FROM users WHERE email_user = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Gere uma nova senha aleatória
        function generateRandomPassword($length = 10) {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomPassword = '';
            for ($i = 0; $i < $length; $i++) {
                $randomPassword .= $characters[rand(0, $charactersLength - 1)];
            }
            return $randomPassword;
        }

        $newPassword = generateRandomPassword();
        $newPasswordHashed = password_hash($newPassword, PASSWORD_BCRYPT);

        // Atualize a senha do usuário no banco de dados
        $sql = "UPDATE users SET password = ? WHERE email_user = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $newPasswordHashed, $email);
        $stmt->execute();

        // Configure o PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Configurações do servidor
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';  // Servidor SMTP do Gmail
            $mail->SMTPAuth = true;
            $mail->Username = 'contato.helptek@gmail.com'; // Seu endereço de email SMTP
            $mail->Password = 'xxhw porl ejrk ndfm'; // Senha gerada para apps
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Habilitar criptografia TLS
            $mail->Port = 587; // Porta TCP para TLS

            // Recipientes
            $mail->setFrom('contato.helptek@gmail.com', 'HelpTek');
            $mail->addAddress($email); // Adicionar um destinatário

            // Conteúdo do email
            $mail->isHTML(true); // Definir o formato do email como HTML
            $mail->Subject = 'Recuperação de Senha';
            $mail->Body    = "Sua nova senha é: " . $newPassword;

            $mail->send();
            echo json_encode(['message' => 'Um email com a nova senha foi enviado para o seu email.', 'status' => 'success']);
        } catch (Exception $e) {
            echo json_encode(['message' => "Erro ao enviar o email. Mailer Error: {$mail->ErrorInfo}", 'status' => 'error']);
        }
    } else {
        echo json_encode(['message' => 'Email não encontrado.', 'status' => 'error']);
    }
} else {
    echo json_encode(['message' => 'Email é necessário.', 'status' => 'error']);
}
?>
