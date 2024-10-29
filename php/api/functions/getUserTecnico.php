<?php
global $mysqli_con;
include "../../config/dbconnect.php";
include "../../config/httpaccess.php";

$res = array('error' => false, 'tecnicos' => array(), 'msg' => '');

if (isset($_GET['id_user_session']) && isset($_GET['permission_user_session'])) {

    $id_user_session = $mysqli_con->real_escape_string($_GET['id_user_session']);
    $permission_user_session =$mysqli_con->real_escape_string($_GET['permission_user_session']);

    if ($permission_user_session == 2) {
        $sql = "SELECT id_user, idfr_code_user, name_user, email_user, equipe_user FROM users WHERE id_permissao = 2 AND id_user <> '$id_user_session'";
    } else {
        $sql = "SELECT id_user, idfr_code_user, name_user, email_user, equipe_user FROM users WHERE id_permissao = 2";
    }
    $result = $mysqli_con->query($sql);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $res['tecnicos'][] = $row;
        }
    } else {
        $res['error'] = true;
        $res['msg'] = "Nenhum técnico encontrado";
    }
}

$mysqli_con->close();
header("Content-type: application/json");
echo json_encode($res);
?>