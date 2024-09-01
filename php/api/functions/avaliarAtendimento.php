<?php
include "../../config/dbconnect.php";
include "../../config/httpaccess.php";

$res = array('error' => false, 'msg' => '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($_GET['action'])) {
        $action = $_GET['action'];

        if ($action == "AvaliarAtendimento") {

            $id_chamado = $mysqli_con->real_escape_string($data['id_chamado']);
            $id_user = $mysqli_con->real_escape_string($data['id_user']);
            $solicitacao_atendida = $mysqli_con->real_escape_string($data['solicitacao_atendida']);
            $observacao = $mysqli_con->real_escape_string($data['observacao']);

            $select_user = "SELECT id_user, idfr_code_user FROM users WHERE id_user = '$id_user'";
            $res_select_user = $mysqli_con->query($select_user);
            $obj_u = $res_select_user->fetch_object();

            if ($res_select_user->num_rows == 1) {
                $idfr_code_user = $obj_u->idfr_code_user;

                $sql_get_chamado = "SELECT idfr_chamado, id_user, id_user_tecnico, total_acoes FROM chamados WHERE id_chamado = '$id_chamado'";
                $result_get_chamado = $mysqli_con->query($sql_get_chamado);

                if ($result_get_chamado && $result_get_chamado->num_rows > 0) {
                    $row = $result_get_chamado->fetch_assoc();
                    $idfr_chamado = $row['idfr_chamado'];
                    $id_user = $row['id_user'];
                    $id_user_tecnico = $row['id_user_tecnico'];
                    $total_acoes = $row['total_acoes'];
                    $total_acoes_upd = ++$total_acoes;

                    if ($solicitacao_atendida == 1) {
                        $avaliacao = "SIM (SOLICITACAO ATENDIDA)";
                        $status_chamado = 4;
                        $sql = "UPDATE chamados SET status_chamado = '$status_chamado', data_atualizacao = NOW(), data_conclusao = NOW(), observacao = '$observacao', total_acoes = '$total_acoes_upd' WHERE id_chamado = '$id_chamado'";
                        $result = $mysqli_con->query($sql);
                    } else {
                        $avaliacao = "NAO (SOLICITACAO NAO ATENDIDA)";
                        $status_chamado = 2;
                        $sql = "UPDATE chamados SET status_chamado = '$status_chamado', id_usuario_atual = '$id_user_tecnico', id_usuario_anterior = '$id_user', data_atualizacao = NOW(), observacao = '$observacao', total_acoes = '$total_acoes_upd' WHERE id_chamado = '$id_chamado'";
                        $result = $mysqli_con->query($sql);
                    }

                    if ($result) {
                        $acao = "USUARIO $idfr_code_user AVALIOU O CHAMADO $idfr_chamado COMO $avaliacao";
                        $descricao_acao = $observacao;
                        $sql_acompanhamento = "INSERT INTO acompanhamento (`id_chamado`, `id_user`, `id_user_tecnico`, `idfr_code_user`, `idfr_chamado`, `acao`, `descricao_acao`, `id_usuario_acao`, `status_chamado`)
                                                VALUES('$id_chamado', '$id_user','$id_user_tecnico','$idfr_code_user','$idfr_chamado','$acao','$descricao_acao','$id_user','$status_chamado')";
                        $result_acompanhamento = $mysqli_con->query($sql_acompanhamento);

                        if ($result_acompanhamento) {
                            $res['msg'] = "Chamado avaliado com sucesso!";
                        } else {
                            $res['error'] = true;
                            $res['msg'] = "Erro ao registrar acompanhamento: " . $mysqli_con->error;
                        }
                    } else {
                        $res['error'] = true;
                        $res['msg'] = "Erro ao avaliar chamado: " . $mysqli_con->error;
                    }
                } else {
                    $res['error'] = true;
                    $res['msg'] = "Chamado não encontrado!";
                }
            } else {
                $res['error'] = true;
                $res['msg'] = "Erro inesperado! (Usuário não encontrado)";
            }
        } else {
            $res['error'] = true;
            $res['msg'] = "Erro inesperado! (Ação inválida ao editar registro)";
        }
    } else {
        $res['error'] = true;
        $res['msg'] = "Erro inesperado! (Ação não especificada)";
    }
} else {
    $res['error'] = true;
    $res['msg'] = "Erro inesperado! (Método de requisição inválido)";
}

$mysqli_con->close();
header("Content-type: application/json");
echo json_encode($res);
?>
