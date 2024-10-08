<?php
global $mysqli_con;
include "../../../config/dbconnect.php";
include "../../../config/httpaccess.php";

$res = array('error' => false, 'corps' => array());

$sql = "SELECT * FROM corporacao";
$result = $mysqli_con->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $res['corps'][] = $row;
    }
} else {
    $res['error'] = true;
    $res['msg'] = "Nenhuma setor encontrado!";
}

$mysqli_con->close();
header("Content-type: application/json");
echo json_encode($res);
?>
