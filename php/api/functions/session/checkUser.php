<?php
include "../../../config/dbconnect.php";
include "../../../config/httpaccess.php";

$res = array('error' => false, 'msg' => '', 'user' => '');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $data = json_decode(file_get_contents('php://input'), true);

    // Validação dos dados do usuário
    $id_user = $_GET['id_user'];
    $session_token = $_GET['session_token'];

    $sql = "SELECT * FROM users WHERE id_user = '$id_user'";
    $result = $mysqli_con->query($sql);
    $obj = $result->fetch_object();

    if ($result && $result->num_rows > 0) {
        $token_user = $obj->token_user;
        $idfr_code_user = $obj->idfr_code_user;
        if ($session_token == $token_user){
            $res['msg'] = "Token válido";
            $res['user'] = $idfr_code_user;
        } else {
            $res['error'] = true;
            $res['msg'] = "Token inválido";
        }
    } else {
        $res['error'] = true;
        $res['msg'] = "Usuário inválido";
    }
} else {
    $res['msg'] = "Método de requisição inválido";
}

$mysqli_con->close();
header("Content-type: application/json");
echo json_encode($res);
die();
?>