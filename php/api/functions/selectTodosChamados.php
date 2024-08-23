<?php
include "../../config/dbconnect.php";
include "../../config/httpaccess.php";

$res = array('error' => false, 'msg' => '', 'chamados' => '');

if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $id_user = $_GET['id_user'];
    $permission = isset($_GET['permission']) ? $_GET['permission'] : null;
    /* permission 2 = tecnico
       permission 3 = gt */

    if ($action == "selectTodosChamados") {
        if($id_user && $permission) {
            if ($permission == 3) {
                if (isset($_GET['status_chamado'])){
                    $status_chamado = $_GET['status_chamado'];
                    $sql = "SELECT *,DATE_FORMAT(data_criacao, '%d/%m/%Y') AS data_criacao_fm,DATE_FORMAT(data_atualizacao, '%d/%m/%Y') AS data_atualizacao_fm,DATE_FORMAT(data_conclusao, '%d/%m/%Y') AS data_conclusao_fm,TIMESTAMPDIFF(MINUTE, data_criacao, NOW()) AS minutos_espera FROM chamados WHERE status_chamado = '$status_chamado' ORDER BY prioridade_chamado DESC";
                }else{
                    $sql = "SELECT *,DATE_FORMAT(data_criacao, '%d/%m/%Y') AS data_criacao_fm,DATE_FORMAT(data_atualizacao, '%d/%m/%Y') AS data_atualizacao_fm,DATE_FORMAT(data_conclusao, '%d/%m/%Y') AS data_conclusao_fm,TIMESTAMPDIFF(MINUTE, data_criacao, NOW()) AS minutos_espera FROM chamados ORDER BY prioridade_chamado DESC";
                }
            } elseif ($permission == 2) {
                if (isset($_GET['status_chamado'])){
                    $status_chamado = $_GET['status_chamado'];
                    $sql = "SELECT *,DATE_FORMAT(data_criacao, '%d/%m/%Y') AS data_criacao_fm,DATE_FORMAT(data_atualizacao, '%d/%m/%Y') AS data_atualizacao_fm,DATE_FORMAT(data_conclusao, '%d/%m/%Y') AS data_conclusao_fm,TIMESTAMPDIFF(MINUTE, data_criacao, NOW()) AS minutos_espera FROM chamados WHERE id_user_tecnico='' AND status_chamado = 1 ORDER BY prioridade_chamado DESC";
                }else{
                    $sql = "SELECT *,DATE_FORMAT(data_criacao, '%d/%m/%Y') AS data_criacao_fm,DATE_FORMAT(data_atualizacao, '%d/%m/%Y') AS data_atualizacao_fm,DATE_FORMAT(data_conclusao, '%d/%m/%Y') AS data_conclusao_fm,TIMESTAMPDIFF(MINUTE, data_criacao, NOW()) AS minutos_espera FROM chamados WHERE status_chamado = 1 ORDER BY prioridade_chamado DESC";
                }
            }

            if (isset($sql)){
                $result = $mysqli_con->query($sql);

                if ($result){
                    $num = mysqli_num_rows($result);
                    $chamados = array();

                    if ($num > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $status_chamado_ret = $row['status_chamado'];
                            switch ($status_chamado_ret){
                                case 1:
                                    $status_desc = "Em aberto";
                                    break;
                                case 2:
                                    $status_desc = "Em atendimento";
                                    break;
                                case 3:
                                    $status_desc = "Respondido";
                                    break;
                                case 4:
                                    $status_desc = "Concluido";
                                    break;
                                case 0:
                                    $status_desc = "Cancelado";
                                    break;
                            }
                            $row['status_chamado_desc'] = $status_desc;
                            $chamados[] = $row;
                        }
                        $res['error'] = false;
                        $res['msg'] = "Listagem";
                        $res['chamados'] = $chamados;
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
                $res['msg'] = "Permissão inválida";
            }
        }else {
            $res['error'] = true;
            $res['msg'] = "Usuário ou permissão não definidos";
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