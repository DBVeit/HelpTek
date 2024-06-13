<?php
include "../../config/dbconnect.php";
include "../../config/httpaccess.php";

$res = array('error' => false, 'msg' => '', 'chamados' => '');

if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $id_user = $_GET['id_user'];

    if ($action == "selectChamados") {
        //$status_chamado = isset($_GET['status_chamado']) ? $_GET['status_chamado'] : '';
        //$sql = "SELECT *,DATE_FORMAT(data_criacao, '%d/%m/%Y') AS data_criacao_fm,TIMESTAMPDIFF(MINUTE, data_criacao, NOW()) AS minutos_espera FROM chamados WHERE id_user='$id_user'";

        if(isset($_GET['status_chamado'])){
            $status_chamado = $_GET['status_chamado'];
            $sql = "SELECT *, DATE_FORMAT(data_criacao, '%d/%m/%Y') AS data_criacao_fm, CONCAT(CAST(TIMESTAMPDIFF(MINUTE, data_criacao, NOW()) AS CHAR), ' minutos') AS minutos_espera FROM chamados WHERE id_user='$id_user' AND status_chamado = '$status_chamado'";
    } else {
        $sql = "SELECT *, DATE_FORMAT(data_criacao, '%d/%m/%Y') AS data_criacao_fm, CONCAT(CAST(TIMESTAMPDIFF(MINUTE, data_criacao, NOW()) AS CHAR), ' minutos') AS minutos_espera FROM chamados WHERE id_user='$id_user'";
    }

        $result = $mysqli_con->query($sql);
        $num = mysqli_num_rows($re3sult);S
        $chamados = array();

        if ($num > 0) {
            while ($row = $result->fetch_assoc()) {
                array_push($chamados, $row);
            }
            $res['error'] = false;
            $res['msg'] = "Listagem";
            $res['chamados'] = $chamados;
        } else {
            $res['error'] = false;
            $res['msg'] = "Não há dados para exibição";
        }
    }
}

$mysqli_con -> close();
header("Content-type: application/json");
echo json_encode($res);
die();

?>