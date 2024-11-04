<?php
$to = "help-tek-sys@outlook.com"; // Substitua pelo seu endereço de e-mail
$subject = "Teste de Envio de Email";
$message = "Este é um e-mail de teste.";
$headers = 'From: help-tek-sys@outlook.com' . "\r\n" .
    'Reply-To: help-tek-sys@outlook.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

if (mail($to, $subject, $message, $headers)) {
    echo "Email enviado para $to com sucesso.";
} else {
    echo "Erro ao enviar o email.";
}
?>
