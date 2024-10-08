<?php
global $mysqli_con;
include "../../config/dbconnect.php";
include "../../config/httpaccess.php";

$res = array('error' => false, 'msg' => '', 'chamados' => '');

if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $id_user = $_GET['id_user'];
    //$permission = $_GET['permission'];
    $permission = $_GET['permission'] ?? null;

    if ($action == "selectChamados") {
        if($id_user && $permission) {
            if ($permission == 1) {
                if (isset($_GET['status_chamado'])){
                    $status_chamado = $_GET['status_chamado'];
                    $sql = "SELECT *,
                                DATE_FORMAT(data_criacao, '%d/%m/%Y') AS data_criacao_fm,
                                DATE_FORMAT(data_atualizacao, '%d/%m/%Y') AS data_atualizacao_fm,
                                DATE_FORMAT(data_conclusao, '%d/%m/%Y') AS data_conclusao_fm,
                                -- Cálculo de dias, horas e minutos de espera
                                CONCAT(
                                    FLOOR(TIMESTAMPDIFF(MINUTE, data_criacao, IF(data_conclusao IS NOT NULL, data_conclusao, NOW())) / 1440), ' dia(s), ',  -- 1440 minutos em um dia
                                    MOD(FLOOR(TIMESTAMPDIFF(MINUTE, data_criacao, IF(data_conclusao IS NOT NULL, data_conclusao, NOW())) / 60), 24), ' hora(s), ',  -- 60 minutos em uma hora, mod 24 para horas no intervalo de um dia
                                    MOD(TIMESTAMPDIFF(MINUTE, data_criacao, IF(data_conclusao IS NOT NULL, data_conclusao, NOW())), 60), ' minuto(s)'  -- minutos restantes após calcular horas e dias
                                ) AS tempo_espera, 
                                users.name_user AS usuario_chamado,
                                tecnico.name_user AS tecnico_responsavel
                            FROM chamados 
                            INNER JOIN users ON users.id_user = chamados.id_user
                            LEFT JOIN users AS tecnico ON tecnico.id_user = chamados.id_user_tecnico AND tecnico.id_permissao = 2
                            LEFT JOIN setor ON setor.id_setor = chamados.id_setor
                            WHERE chamados.id_user='$id_user' AND status_chamado = '$status_chamado' 
                            ORDER BY 
                                CASE 
                                    WHEN status_chamado NOT IN (0, 4) THEN 1
                                    ELSE 2
                                END,
                                CASE 
                                    WHEN prioridade_chamado > 100 THEN 1 -- Crítica
                                    WHEN prioridade_chamado > 60 THEN 2  -- Alta
                                    WHEN prioridade_chamado > 20 THEN 3  -- Média
                                    ELSE 4                               -- Baixa
                                END,
                                data_criacao DESC";
                }else{
                    $sql = "SELECT *,
                                DATE_FORMAT(data_criacao, '%d/%m/%Y') AS data_criacao_fm,
                                DATE_FORMAT(data_atualizacao, '%d/%m/%Y') AS data_atualizacao_fm,
                                DATE_FORMAT(data_conclusao, '%d/%m/%Y') AS data_conclusao_fm,
                                CONCAT(
                                    FLOOR(TIMESTAMPDIFF(MINUTE, data_criacao, IF(data_conclusao IS NOT NULL, data_conclusao, NOW())) / 1440), ' dia(s), ',  -- 1440 minutos em um dia
                                    MOD(FLOOR(TIMESTAMPDIFF(MINUTE, data_criacao, IF(data_conclusao IS NOT NULL, data_conclusao, NOW())) / 60), 24), ' hora(s), ',  -- 60 minutos em uma hora, mod 24 para horas no intervalo de um dia
                                    MOD(TIMESTAMPDIFF(MINUTE, data_criacao, IF(data_conclusao IS NOT NULL, data_conclusao, NOW())), 60), ' minuto(s)'  -- minutos restantes após calcular horas e dias
                                ) AS tempo_espera, 
                                users.name_user AS usuario_chamado,
                                tecnico.name_user AS tecnico_responsavel
                            FROM chamados 
                            INNER JOIN users ON users.id_user = chamados.id_user
                            LEFT JOIN users AS tecnico ON tecnico.id_user = chamados.id_user_tecnico AND tecnico.id_permissao = 2
                            LEFT JOIN setor ON setor.id_setor = chamados.id_setor
                            WHERE chamados.id_user='$id_user' 
                            ORDER BY 
                                CASE 
                                    WHEN status_chamado NOT IN (0, 4) THEN 1
                                    ELSE 2
                                END,
                                CASE 
                                    WHEN prioridade_chamado > 100 THEN 1 -- Crítica
                                    WHEN prioridade_chamado > 60 THEN 2  -- Alta
                                    WHEN prioridade_chamado > 20 THEN 3  -- Média
                                    ELSE 4                               -- Baixa
                                END,
                                data_criacao DESC";
                }
            } elseif ($permission == 2) {
                if (isset($_GET['status_chamado'])){
                    $status_chamado = $_GET['status_chamado'];
                    $sql = "SELECT *,
                                DATE_FORMAT(data_criacao, '%d/%m/%Y') AS data_criacao_fm,
                                DATE_FORMAT(data_atualizacao, '%d/%m/%Y') AS data_atualizacao_fm,
                                DATE_FORMAT(data_conclusao, '%d/%m/%Y') AS data_conclusao_fm,
                                CONCAT(
                                    FLOOR(TIMESTAMPDIFF(MINUTE, data_criacao, IF(data_conclusao IS NOT NULL, data_conclusao, NOW())) / 1440), ' dia(s), ',  -- 1440 minutos em um dia
                                    MOD(FLOOR(TIMESTAMPDIFF(MINUTE, data_criacao, IF(data_conclusao IS NOT NULL, data_conclusao, NOW())) / 60), 24), ' hora(s), ',  -- 60 minutos em uma hora, mod 24 para horas no intervalo de um dia
                                    MOD(TIMESTAMPDIFF(MINUTE, data_criacao, IF(data_conclusao IS NOT NULL, data_conclusao, NOW())), 60), ' minuto(s)'  -- minutos restantes após calcular horas e dias
                                ) AS tempo_espera,  
                                users.name_user AS usuario_chamado,
                                tecnico.name_user AS tecnico_responsavel
                            FROM chamados 
                            INNER JOIN users ON users.id_user = chamados.id_user
                            LEFT JOIN users AS tecnico ON tecnico.id_user = chamados.id_user_tecnico AND tecnico.id_permissao = 2
                            LEFT JOIN setor ON setor.id_setor = chamados.id_setor
                            WHERE id_user_tecnico='$id_user' AND status_chamado = '$status_chamado' 
                            ORDER BY 
                                CASE 
                                    WHEN status_chamado NOT IN (0, 4) THEN 1
                                    ELSE 2
                                END,
                                CASE 
                                    WHEN prioridade_chamado > 100 THEN 1 -- Crítica
                                    WHEN prioridade_chamado > 60 THEN 2  -- Alta
                                    WHEN prioridade_chamado > 20 THEN 3  -- Média
                                    ELSE 4                               -- Baixa
                                END,
                                data_criacao DESC";
                }else{
                    $sql = "SELECT *,
                                DATE_FORMAT(data_criacao, '%d/%m/%Y') AS data_criacao_fm,
                                DATE_FORMAT(data_atualizacao, '%d/%m/%Y') AS data_atualizacao_fm,
                                DATE_FORMAT(data_conclusao, '%d/%m/%Y') AS data_conclusao_fm,
                                CONCAT(
                                    FLOOR(TIMESTAMPDIFF(MINUTE, data_criacao, IF(data_conclusao IS NOT NULL, data_conclusao, NOW())) / 1440), ' dia(s), ',  -- 1440 minutos em um dia
                                    MOD(FLOOR(TIMESTAMPDIFF(MINUTE, data_criacao, IF(data_conclusao IS NOT NULL, data_conclusao, NOW())) / 60), 24), ' hora(s), ',  -- 60 minutos em uma hora, mod 24 para horas no intervalo de um dia
                                    MOD(TIMESTAMPDIFF(MINUTE, data_criacao, IF(data_conclusao IS NOT NULL, data_conclusao, NOW())), 60), ' minuto(s)'  -- minutos restantes após calcular horas e dias
                                ) AS tempo_espera, 
                                users.name_user AS usuario_chamado,
                                tecnico.name_user AS tecnico_responsavel
                            FROM chamados 
                            INNER JOIN users ON users.id_user = chamados.id_user
                            LEFT JOIN users AS tecnico ON tecnico.id_user = chamados.id_user_tecnico AND tecnico.id_permissao = 2
                            LEFT JOIN setor ON setor.id_setor = chamados.id_setor
                            WHERE id_user_tecnico='$id_user' 
                            ORDER BY
                                CASE 
                                    WHEN status_chamado NOT IN (0, 4) THEN 1
                                    ELSE 2
                                END,
                                CASE 
                                    WHEN prioridade_chamado > 100 THEN 1 -- Crítica
                                    WHEN prioridade_chamado > 60 THEN 2  -- Alta
                                    WHEN prioridade_chamado > 20 THEN 3  -- Média
                                    ELSE 4                               -- Baixa
                                END,
                                data_criacao DESC";
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
                            $prioridade_chamado_ret = $row['prioridade_chamado'];
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
                            $data_criacao = new DateTime($row['data_criacao']);
                            switch ($prioridade_chamado_ret){
                                case $prioridade_chamado_ret <= 20:
                                    $prioridade_desc = "Baixa";
                                    $prazo = $data_criacao->add(new DateInterval('P14D'));
                                    $prazo_desc = "Máx. 14 dias";
                                    break;
                                case $prioridade_chamado_ret > 20 && $prioridade_chamado_ret <= 60:
                                    $prioridade_desc = "Média";
                                    $prazo = $data_criacao->add(new DateInterval('P7D'));
                                    $prazo_desc = "Máx. 7 dias";
                                    break;
                                case $prioridade_chamado_ret > 60 && $prioridade_chamado_ret <= 100:
                                    $prioridade_desc = "Alta";
                                    $prazo = $data_criacao->add(new DateInterval('P2D'));
                                    $prazo_desc = "Máx. 2 dias";
                                    break;
                                case $prioridade_chamado_ret > 100:
                                    $prioridade_desc = "Crítica";
                                    $prazo = $data_criacao->add(new DateInterval('P1D'));
                                    $prazo_desc = "Máx. 1 dia";
                                    break;
                            }
                            $row['status_chamado_desc'] = $status_desc;
                            $row['prioridade_chamado_desc'] = $prioridade_desc;
                            $row['prazo_desc'] = $prazo_desc;
                            $row['prazo'] = $prazo->format('d/m/Y');
                            $chamados[] = $row;
                        }
                        $res['chamados'] = $chamados;
                    } else {
                        $res['error'] = false;
                        $res['msg'] = "Não existem dados para visualização";
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