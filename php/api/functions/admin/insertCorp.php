<?php
include "../../../config/dbconnect.php";
include "../../../config/httpaccess.php";

$res = array('error' => false, 'msg' => '');

if (isset($_GET['action'])) {
    $action = $_GET['action'];

    if ($action == "InsertCorp") {

        $nome_corporacao = $mysqli_con->real_escape_string($_POST['nome_corporacao']);

        $sql_insert = "INSERT INTO corporacao(`nome_corporacao`,`data_criacao`) VALUES('$nome_corporacao', NOW())";

        $query_insert = $mysqli_con->query($sql_insert);

        if ($query_insert) {
            $id_corp = $mysqli_con->insert_id;
            $data_criacao = date('dmy');

            $idfr_corporacao = "CORP0" . $id_corp . $data_criacao;

            $sql_update = "UPDATE corporacao SET idfr_corporacao ='$idfr_corporacao' WHERE id_corporacao='$id_corp'";
            $result_update = $mysqli_con->query($sql_update);

            if ($result_update) {
                $res['msg'] = "Corporação cadastrada com sucesso! Identificador gerado: " . $idfr_corporacao;
            } else {
                $res['error'] = true;
                $res['msg'] = "Erro ao gerar identificador corporativo!";
            }
        } else {
            $res['error'] = true;
            $res['msg'] = "Falha ao executar cadastro!";
        }
    } else {
        $res['error'] = true;
        $res['msg'] = "Ação inválida!";
    }
}else{
    $res['error'] = true;
    $res['msg'] = "Requisição inválida!";
}

$mysqli_con->close();
header("Content-type: application/json");
echo json_encode($res);
?>
