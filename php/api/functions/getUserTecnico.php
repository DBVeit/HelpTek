<?php
include "../../config/dbconnect.php";
include "../../config/httpaccess.php";

$res = array('error' => false, 'tecnicos' => array(), 'msg' => '');

$sql = "SELECT id_user, idfr_code_user, name_user, email_user, equipe_user FROM users WHERE id_permissao = 2";
$result = $mysqli_con->query($sql);

if ($result && $result->num_rows > 0){
    while ($row = $result->fetch_assoc()){
        $res['tecnicos'][] = $row;
    }
} else {
    $res['error'] = true;
    $res['msg'] = "Nenhum técnico encontrado";
}

$mysqli_con->close();
header("Content-type: application/json");
echo json_encode($res);
?>