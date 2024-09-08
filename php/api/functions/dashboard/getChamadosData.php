<?php
include "../../../config/dbconnect.php";
include "../../../config/httpaccess.php";

$res = array('error' => false, 'data' => []);

if (isset($_GET['action']) && $_GET['action'] == 'getChamadosData') {
    // Consulta para buscar dados de chamados (exemplo por status)
    $sql_select = "SELECT status_chamado, COUNT(*) as total 
            FROM chamados 
            GROUP BY status_chamado";

    $result_select = $mysqli_con->query($sql_select);

    if ($result_select) {
        while ($row = $result_select->fetch_assoc()) {
            $res['data'][] = $row;
        }
    } else {
        $res['error'] = true;
        $res['msg'] = "Erro ao buscar dados";
    }
}

$mysqli_con->close();
header("Content-type: application/json");
echo json_encode($res);
die();
?>
