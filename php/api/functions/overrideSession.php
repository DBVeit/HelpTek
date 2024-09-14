<?php
include "../../config/dbconnect.php";
include "../../config/httpaccess.php";

$res = array('error' => false, 'msg' => '', 'username' => '');

if (isset($_GET['action'])) {
    $action = $_GET['action'];

    // Ação para derrubar login ativo
    if ($action == "derrubarLogin") {
        if (isset($_GET['username'])) {
            $username = $mysqli_con->real_escape_string($_GET['username']);

            $sql_select = "SELECT * FROM users WHERE username_user = '$username'";
            $result_select = $mysqli_con->query($sql_select);

            if ($result_select->num_rows > 0) {
                $row_select = $result_select->fetch_assoc();
                $id_user = $row_select['id_user'];

                $updateQuery = "UPDATE users SET user_logado = 0, token_user = NULL WHERE id_user = '$id_user'";
                $mysqli_con->query($updateQuery);

                if ($mysqli_con->affected_rows > 0) {
                    $res['msg'] = "Login anterior derrubado com sucesso. Faça login novamente.";
                    $res['code'] = 200;
                } else {
                    $res['error'] = true;
                    $res['msg'] = "Erro ao derrubar o login anterior.";
                    $res['code'] = 500;
                }
            } else {
                $res['error'] = true;
                $res['msg'] = "Usuário não encontrado";
                $res['code'] = 500;
            }
        }
    } else {
        $res['error'] = true;
        $res['msg'] = "Ação inválida";
    }
} else {
    $res['error'] = true;
    $res['msg'] = "Parametros inválidos!";
    $res['code'] = 400;
}

$mysqli_con->close();
header("Content-type: application/json");
echo json_encode($res);
?>