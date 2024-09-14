<?php
include "../../config/dbconnect.php";
include "../../config/httpaccess.php";

$res = array('error' => false, 'msg' => '', 'id_user' => '');

if (isset($_GET['action'])) {
    $action = $_GET['action'];

    // Ação para realizar logout
    if ($action == "logout") {
        if (isset($_GET['id_user'])) {
            $id_user = $mysqli_con->real_escape_string($_GET['id_user']);
            var_dump($id_user);

            $sql_select = "SELECT * FROM users WHERE id_user = '$id_user'";
            $result_select = $mysqli_con->query($sql_select);

            if ($result_select->num_rows > 0) {
                $row_select = $result_select->fetch_assoc();

                $updateQuery = "UPDATE users SET user_logado = 0, token_user = NULL WHERE id_user = '$id_user'";
                $mysqli_con->query($updateQuery);

                if ($mysqli_con->affected_rows > 0) {
                    $res['msg'] = "Logout efetuado com sucesso.";
                    $res['code'] = 200;
                } else {
                    $res['error'] = true;
                    $res['msg'] = "Erro ao efetuar logout.";
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