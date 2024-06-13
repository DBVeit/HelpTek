<?php
include "../../config/dbconnect.php";
include "../../config/httpaccess.php";

$res = array('error' => false, 'msg' => '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $id_user = $_POST['id_user'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    $sql_select = "SELECT * FROM users WHERE id_user = '$id_user' AND troca_senha = 1";
    $result_select = $mysqli_con->query($sql_select);

    if ($result_select->num_rows > 0) {
        $sql = "UPDATE users SET password_user = '$new_password', troca_senha = 0 WHERE id_user = '$id_user'";
        $result = $mysqli_con->query($sql);

        if ($result) {
            $res['msg'] = "Senha alterada com sucesso!";
        }else {
            $res['error'] = true;
            $res['msg'] = "Ocorreu um erro ao alterar a senha: " . $mysqli_con->error;
        }
    }else{
        $res['error'] = true;
        $res['msg'] = "Erro ao alterar a senha: Usuário não localizado!" . $mysqli_con->error;
    }
}else{
    $res['error'] = true;
    $res['msg'] = "Erro na requisição: Requisição inválida!" . $mysqli_con->error;
}

$mysqli_con->close();
header("Content-type: application/json");
echo json_encode($res);
?>