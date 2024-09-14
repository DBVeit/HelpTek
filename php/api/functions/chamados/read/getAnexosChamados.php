<?php
include "../../../../config/dbconnect.php";
include "../../../../config/httpaccess.php";

$res = array('error' => false, 'msg' => '', 'anexos' => array());

if (isset($_GET['id_chamado'])) {
    $id_chamado = $_GET['id_chamado'];

    if ($id_chamado) {

        $sql_get_anexo = "SELECT * FROM anexos_chamados WHERE id_chamado = '$id_chamado'";

        $result = $mysqli_con->query($sql_get_anexo);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $res['anexos'][] = $row;
            }
        } else {
            $res['error'] = true;
            $res['msg'] = "Não há anexos disponíveis";
        }
    } else {
        $res['error'] = true;
        $res['msg'] = "Chamado não encontrado";
    }
}

$mysqli_con -> close();
header("Content-type: application/json");
echo json_encode($res);
die();

?>