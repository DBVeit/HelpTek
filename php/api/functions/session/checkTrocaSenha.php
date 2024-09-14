<?php
include "../../../config/dbconnect.php";
include "../../../config/httpaccess.php";

$res = array('error' => false, 'msg' => '', 'id_user' => '');

if (isset($_GET['action'])) {
    $action = $_GET['action'];

    // Ação para derrubar login ativo
    if ($action == "checkTrocaSenha") {
        if (isset($_GET['id_user'])) {
            $id_user = $mysqli_con->real_escape_string($_GET['id_user']);

            $sql_select = "SELECT troca_senha FROM users WHERE id_user = '$id_user' AND troca_senha = 1";
            $result_select = $mysqli_con->query($sql_select);

            if ($result_select->num_rows > 0) {
                $row_select = $result_select->fetch_assoc();
                $res['msg'] = "Alterar senha para continuar";
                $res['code'] = 428;
            } else {
                $res['msg'] = "";
                $res['code'] = 200;
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