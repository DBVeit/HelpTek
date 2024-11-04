<?php
global $mysqli_con;
include "../../../../config/dbconnect.php";
include "../../../../config/httpaccess.php";

$res_atualizacao = array('error' => false, 'data' => []);

if (isset($_GET['action']) && $_GET['action'] == 'getAtualizacoes' && isset($_GET['id_user'])) {
    $id_user = $_GET['id_user']; // Pega o ID do usuário logado

    $sql_select_atualizacoes = "SELECT 
                        chamados.id_chamado,        -- ID do chamado
                        chamados.idfr_chamado,
                        chamados.status_chamado,
                        chamados.data_atualizacao,   -- Data e hora da última atualização no chamado
                        acompanhamento.acao,
                        acompanhamento.data_acao,
                        users.idfr_code_user
                    FROM 
                        chamados
                    JOIN users ON users.id_user = $id_user
                    LEFT JOIN acompanhamento ON acompanhamento.id_chamado = chamados.id_chamado
                    WHERE 
                        ( (chamados.id_user = users.id_user AND users.id_permissao = 1)
                        OR
                            (chamados.id_user_tecnico = users.id_user AND users.id_permissao = 2)
                        )
                        AND acompanhamento.data_acao > users.data_hora_logout  -- Atualizações após o último logout
                    ORDER BY 
                        chamados.data_atualizacao ASC LIMIT 1";
    $result_atualizacoes = $mysqli_con->query($sql_select_atualizacoes);

    if ($result_atualizacoes && mysqli_num_rows($result_atualizacoes) > 0) {
        $chamados = array();
        while ($row = $result_atualizacoes->fetch_assoc()) {
            $status_chamado_ret = $row['status_chamado'];
            switch ($status_chamado_ret) {
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
                case 5:
                    $status_desc = "Detalhar chamado";
                    break;
                }
                $row['status_chamado_desc'] = $status_desc;
                // Monta a mensagem formatada
                $row['mensagem'] = "Chamado: {$row['idfr_chamado']} - Status: $status_desc - Última ação registrada: {$row['acao']}";
                $chamados[] = $row;
        }
        $res_atualizacao['chamados'] = $chamados;
    } else {
        $sql_select_pendentes = "SELECT 
                                    COUNT(*) AS total_pendentes
                                 FROM 
                                    chamados
                                 WHERE 
                                    (id_user = $id_user OR id_user_tecnico = $id_user)
                                    AND status_chamado NOT IN (4, 0)";

        $result_pendentes = $mysqli_con->query($sql_select_pendentes);
        $row_pendentes = $result_pendentes->fetch_assoc();

        if ($row_pendentes['total_pendentes'] > 0) {
            // Se houver chamados pendentes
            $total_pendentes = $row_pendentes['total_pendentes'];
            $res_atualizacao['chamados'] = [];
            $res_atualizacao['msg'] = "Nenhuma atualização disponível. Total de chamados pendentes: ".$total_pendentes;
        } else {
            // Se todos os chamados estão concluídos ou cancelados
            $res_atualizacao['chamados'] = [];
            $res_atualizacao['msg'] = "Você está em dia, não há pendências a exibir!";
        }
    }

    // Consulta de estatísticas
    $data24hAtras = date('Y-m-d H:i:s', strtotime('-24 hours'));
    $sql_select_estatisticas = "SELECT 
                                    COUNT(CASE WHEN data_criacao >= '$data24hAtras' THEN 1 END) AS chamados24h,
                                    COUNT(*) AS totalChamados,
                                    COUNT(CASE WHEN status_chamado = 1 THEN 1 END) AS totalEmAberto,
                                    COUNT(CASE WHEN status_chamado = 2 THEN 1 END) AS totalEmAtendimento,
                                    COUNT(CASE WHEN status_chamado = 4 THEN 1 END) AS totalConcluidos
                                FROM chamados";

    $result_estatisticas = $mysqli_con->query($sql_select_estatisticas);
    if ($result_estatisticas && $result_estatisticas->num_rows > 0) {
        $estatisticas = $result_estatisticas->fetch_assoc();
        $res_atualizacao['estatisticas'] = $estatisticas;
    } else {
        $res_atualizacao['estatisticas'] = [
            'chamados24h' => 0,
            'totalChamados' => 0,
            'totalEmAberto' => 0,
            'totalEmAtendimento' => 0,
            'totalConcluidos' => 0
        ];
    }

    // Checa permissão de gerente para exibir mensagem de relatórios
    $sql_check_permissao = "SELECT id_permissao FROM users WHERE id_user = ".$id_user;
    $result_permissao = $mysqli_con->query($sql_check_permissao);
    if ($result_permissao && $result_permissao->num_rows > 0) {
        $permissao = $result_permissao->fetch_assoc();
        if ($permissao['id_permissao'] == 3) {
            $res_atualizacao['msg_relatorios'] = "Novos relatórios podem estar disponíveis!";
        }
    }

}

$mysqli_con->close();
header("Content-type: application/json");
echo json_encode($res_atualizacao);
die();
?>