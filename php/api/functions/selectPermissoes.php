<?php
include "../../config/dbconnect.php";
include "../../config/httpaccess.php";

$res = array('error' => false, 'msg' => '', 'permissoes' => array());

$sql = "SELECT * FROM permissoes WHERE id_permissao BETWEEN 1 AND 4";
$result = $mysqli_con->query($sql);

if ($result) {
    while ($row = $result->fetch_assoc()){
        $res['permissoes'][] = $row;
    }
}else{
    $res['error'] = true;
    $res['msg'] = "Erro ao carregar permissões";
}

$mysqli_con->close();
header("Content-type: application/json");
echo json_encode($res);
die();

?>