<?php
global $mysqli_con;
include "../../../config/dbconnect.php";
include "../../../config/httpaccess.php";

$res = array('error' => false, 'msg' => '', 'corporacao' => '');

if (isset($_GET['action'])) {
    $action = $_GET['action'];

    if ($action == "getUserCorp") {

        if (isset($_GET['id_user'])){
            $id_user = $_GET['id_user'];

            $sql_select = "SELECT nome_corporacao,idfr_corporacao FROM corporacao LEFT JOIN users ON users.id_corporacao = corporacao.id_corporacao WHERE users.id_user = '$id_user'";

            if (isset($sql_select)) {
                $result_select = $mysqli_con->query($sql_select);

                if ($result_select){
                    $rows = mysqli_num_rows($result_select);
                    $corporacoes = array();

                    if ($rows > 0) {
                        while ($row = $result_select->fetch_assoc()) {
                            $corporacao[] = $row;
                        }

                        $res['error'] = false;
                        $res['msg'] = "Listagem";
                        $res['corporacao'] = $corporacao;
                    } else {
                        $res['error'] = false;
                        $res['msg'] = "Não há dados para exibição";
                    }
                }else {
                    $res['error'] = true;
                    $res['msg'] = "Erro na execução da consulta";
                }
            }else {
                $res['error'] = true;
                $res['msg'] = "Falha ao consultar";
            }
        } else {
            $res['error'] = true;
            $res['msg'] = "Usuário sem atribuição corporativa";
        }
    } else {
        $res['error'] = true;
        $res['msg'] = "Ação inválida";
    }
}else {
    $res['error'] = true;
    $res['msg'] = "Parâmetros insuficientes";
}

$mysqli_con -> close();
header("Content-type: application/json");
echo json_encode($res);
die();

?>