<?php
require_once '../public/jwt.class.php'; 
$secret_key = 'your_secret_key'; //  chave secreta

function checkPermission($required_permission) {
    $headers = apache_request_headers();
    if (isset($headers['Authorization'])) {
        $token = str_replace('Bearer ', '', $headers['Authorization']);
        try {
            $decoded = JWT::decode($token, $secret_key, array('HS256'));
            if ($decoded->id_permissao == $required_permission) {
                return true;
            }
        } catch (Exception $e) {
            return false;
        }
    }
    return false;
}
?>
