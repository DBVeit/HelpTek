<?php
global $mysqli_con;
include "../../../config/dbconnect.php";
include "../../../config/httpaccess.php";

$res = array('error' => false, 'msg' => '');

if (isset($_GET['action'])) {
    $action = $_GET['action'];

    if ($action == "InsertSetor") {

        $id_corp = $mysqli_con->real_escape_string($_POST['corporacao']);
        $nome_setor = $mysqli_con->real_escape_string($_POST['nome_setor']);
        $peso = $mysqli_con->real_escape_string($_POST['peso']);
        $subsetor = $mysqli_con->real_escape_string($_POST['subsetor']);

        if ($subsetor == ""){
            $sql_insert = "INSERT INTO setor(`id_corporacao`,`nome_setor`,`peso`,`data_criacao`) VALUES('$id_corp', '$nome_setor', '$peso', NOW())";
        } else {
            $sql_insert = "INSERT INTO setor(`id_corporacao`,`nome_setor`,`peso`,`data_criacao`,`setor_id`) VALUES('$id_corp', '$nome_setor', '$peso', NOW(), '$subsetor')";
        }

        $query_insert = $mysqli_con->query($sql_insert);

        if ($query_insert) {
            $id_setor = $mysqli_con->insert_id;
            $data_criacao = date('dmy');

            $idfr_setor = "SET0" . $id_corp . $id_setor . $data_criacao;

            $sql_update = "UPDATE setor SET idfr_setor ='$idfr_setor' WHERE id_setor='$id_setor'";
            $result_update = $mysqli_con->query($sql_update);

            if ($result_update) {
                $res['msg'] = "Setor cadastrado com sucesso! Identificador gerado: " . $idfr_setor;
            } else {
                $res['error'] = true;
                $res['msg'] = "Erro ao gerar identificador de setor!";
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
