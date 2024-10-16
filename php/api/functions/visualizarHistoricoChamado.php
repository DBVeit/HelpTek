<?php
global $mysqli_con;
include "../../config/dbconnect.php";
include "../../config/httpaccess.php";

$res = array('error' => false, 'msg' => '', 'historico' => []);

if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $id_chamado = $_GET['id_chamado'];
    //$permission = $_GET['permission'];

    if ($action == "selectHistorico") {

        $sql_select = "SELECT 
	                chamados.id_chamado, 
                    chamados.idfr_chamado,
                    chamados.id_user,
                    chamados.titulo_chamado,
                    chamados.descricao_chamado,
                    chamados.prioridade_chamado,
                    chamados.data_criacao,
                    chamados.status_chamado,
                    acompanhamento.status_chamado,
                    chamados.id_user_tecnico,
                    chamados.data_atualizacao,
                    chamados.descricao_solucao,
                    chamados.justificativa_encam,
                    chamados.data_conclusao,
                    chamados.id_usuario_atual,
                    chamados.id_usuario_anterior,
                    chamados.observacao,
                    acompanhamento.id_acompanhamento,
                    acompanhamento.id_usuario_acao,
                    acompanhamento.acao,
                    DATE_FORMAT(acompanhamento.data_acao, '%d/%m/%Y %H:%i:%s') AS data_acao_fm,
                    acompanhamento.descricao_acao,
                    users.name_user,
                    users.id_permissao
                FROM chamados
                INNER JOIN acompanhamento ON chamados.id_chamado = acompanhamento.id_chamado
                INNER JOIN users ON users.id_user = acompanhamento.id_usuario_acao
                WHERE chamados.id_chamado = '$id_chamado'
                ORDER BY acompanhamento.data_acao ASC";

        $result_select = $mysqli_con->query($sql_select);

        if ($result_select) {
            $historico = array();
            while ($row = $result_select->fetch_assoc()) {
                $historico[] = $row;
            }
            $res['historico'] = $historico;
        } else {
            $res['error'] = true;
            $res['msg'] = "Erro na execução da consulta";
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