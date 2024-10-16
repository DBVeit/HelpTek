<?php
global $mysqli_con;
include "../../config/dbconnect.php";
include "../../config/httpaccess.php";

$res = array('error' => false, 'categorias_servico' => array());

$sql = "SELECT * FROM categoria_servico";
$result = $mysqli_con->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $res['categorias_servico'][] = $row;
    }
} else {
    $res['error'] = true;
    $res['msg'] = "Nenhuma categoria encontrada!";
}

$mysqli_con->close();
header("Content-type: application/json");
echo json_encode($res);
?>
