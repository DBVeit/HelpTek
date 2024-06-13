<?php
include "../../config/dbconnect.php";
include "../../config/httpaccess.php";

$res = array('error' => false, 'msg' => '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $id_user = $_POST['id_user'];
    $session_token = $_POST['session_token'];

    $sql_select = "SELECT * FROM users WHERE id_user = '$id_user' AND user_logado = 1";
    $result_select = $mysqli_con->query($sql_select);

    if ($result_select->num_rows > 0) {
        $sql = "UPDATE users SET user_logado = 0, token_user = '$session_token' WHERE id_user = '$id_user'";
        $result = $mysqli_con->query($sql);

        if ($result) {
            $res['msg'] = "Sessão fechada, iniciando nova sessão. Token atualizado!";
        }else {
            $res['error'] = true;
            $res['msg'] = "Ocorreu um erro ao atualiza o token: " . $mysqli_con->error;
        }
    }else{
        $res['error'] = true;
        $res['msg'] = "Erro ao atualizar o token: Usuário não localizado!" . $mysqli_con->error;
    }
}else{
    $res['error'] = true;
    $res['msg'] = "Erro na requisição: Requisição inválida!" . $mysqli_con->error;
}

$mysqli_con->close();
header("Content-type: application/json");
echo json_encode($res);
?>