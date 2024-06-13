<?php
// resetPassword.php

require '../config/dbconnect.php';

$data = json_decode(file_get_contents("php://input"), true);
$token = $data['token'];
$newPassword = password_hash($data['password'], PASSWORD_BCRYPT);

// Verifique o token e atualize a senha no banco de dados
$query = $db->prepare("SELECT * FROM users WHERE reset_token = :token");
$query->bindParam(':token', $token);
$query->execute();
$user = $query->fetch(PDO::FETCH_ASSOC);

if ($user) {
    $query = $db->prepare("UPDATE users SET password = :password, reset_token = NULL WHERE reset_token = :token");
    $query->bindParam(':password', $newPassword);
    $query->bindParam(':token', $token);
    $query->execute();

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Token inválido.']);
}

