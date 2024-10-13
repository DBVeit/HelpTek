<?php
include "../../../config/dbconnect.php";
include "../../../config/httpaccess.php";

$res = array('error' => false, 'msg' => '', 'code' => '', 'setores' => '');

if (isset($_GET['action'])) {
    $action = $_GET['action'];

    if ($action == "selectSetor") {

        $sql_select = "SELECT 
                            setor.id_setor, 
                            setor.idfr_setor, 
                            setor.nome_setor, 
                            setor.peso, 
                            DATE_FORMAT(setor.data_criacao_setor, '%d/%m/%Y') AS data_criacao_fm, 
                            corporacao.id_corporacao, 
                            corporacao.nome_corporacao 
                        FROM setor
                        INNER JOIN corporacao ON setor.id_corporacao = corporacao.id_corporacao;";

        if (isset($sql_select)) {
            $result_select = $mysqli_con->query($sql_select);

            if ($result_select){
                $rows = mysqli_num_rows($result_select);
                $setores = array();

                if ($rows > 0) {
                    while ($row = $result_select->fetch_assoc()) {
                        $setores[] = $row;
                    }

                    $res['error'] = false;
                    $res['msg'] = "Listagem";
                    $res['setores'] = $setores;
                } else {
                    $res['error'] = true;
                    $res['msg'] = "Não há dados para exibição";
                    $res['code'] = 204;
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