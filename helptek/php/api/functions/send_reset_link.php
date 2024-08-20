<?php
// Inclua o autoloader do Composer
require '../../vendor/autoload.php';
include "../../config/dbconnect.php";
include "../../config/httpaccess.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

// Carregar variáveis de ambiente
$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

// Permitir requisições de diferentes origens (CORS)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// Obter dados da solicitação POST
$postData = file_get_contents('php://input');
$request = json_decode($postData);

if (isset($request->email)) {
    $email = $request->email;

    // Verifique se o email existe no banco de dados
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
            $mail->Host = $_ENV['SMTP_HOST'];
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['SMTP_USER'];
            $mail->Password = $_ENV['SMTP_PASS'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = $_ENV['SMTP_PORT'];

            // Recipientes
            $mail->setFrom($_ENV['SMTP_FROM'], $_ENV['SMTP_NAME']);
            $mail->addAddress($email);

            // Conteúdo do email
            $mail->isHTML(true);
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
