<?php
global $mysqli_con;
include "../../../config/dbconnect.php";
include "../../../config/httpaccess.php";

$res = array('error' => false, 'solicitantes' => array(), 'msg' => '');

$sql = "SELECT id_user, idfr_code_user, name_user, email_user, equipe_user FROM users WHERE id_permissao = 1";
$result = $mysqli_con->query($sql);

if ($result && $result->num_rows > 0){
    while ($row = $result->fetch_assoc()){
        $res['solicitantes'][] = $row;
    }
} else {
    $res['error'] = true;
    $res['msg'] = "Nenhum solicitante encontrado";
}

$mysqli_con->close();
header("Content-type: application/json");
echo json_encode($res);
?>