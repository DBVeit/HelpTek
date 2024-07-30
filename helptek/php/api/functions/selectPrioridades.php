<?php
include "../../config/dbconnect.php";
include "../../config/httpaccess.php";

$res = array('error' => false, 'msg' => '', 'prioridades' => array());

$sql = "SELECT * FROM prioridades";
$result = $mysqli_con->query($sql);

if ($result) {
    while ($row = $result->fetch_assoc()){
        $res['prioridades'][] = $row;
    }
}else{
    $res['error'] = true;
    $res['msg'] = "Erro ao carregar prioridades";
}

$mysqli_con->close();
header("Content-type: application/json");
echo json_encode($res);
die();

?>