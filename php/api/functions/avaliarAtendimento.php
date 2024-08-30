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

            $sql_get_chamado = "SELECT idfr_chamado, id_user, id_user_tecnico FROM chamados WHERE id_chamado = '$id_chamado'";
            $result_get_chamado = $mysqli_con->query($sql_get_chamado);

            if ($result_get_chamado && $result_get_chamado->num_rows > 0) {
                $row = $result_get_chamado->fetch_assoc();
                $idfr_chamado = $row['idfr_chamado'];
                $id_user = $row['id_user'];
                $id_user_tecnico = $row['id_user_tecnico'];

                if ($solicitacao_atendida == 1) {
                    $avaliacao = "SIM (SOLICITACAO ATENDIDA)";
                    $sql = "UPDATE chamados SET status_chamado = 4, data_atualizacao = NOW(), data_conclusao = NOW(), observacao = '$observacao' WHERE id_chamado = '$id_chamado'";
                    $result = $mysqli_con->query($sql);
                } else {
                    $avaliacao = "NAO (SOLICITACAO NAO ATENDIDA)";
                    $sql = "UPDATE chamados SET status_chamado = 2, id_usuario_atual = '$id_user_tecnico', id_usuario_anterior = '$id_user', data_atualizacao = NOW(), observacao = '$observacao' WHERE id_chamado = '$id_chamado'";
                    $result = $mysqli_con->query($sql);
                }

                if ($result) {
                    $acao = "USUARIO $id_user AVALIOU O CHAMADO $idfr_chamado COMO $avaliacao";
                    $descricao_acao = $observacao;
                    $sql_acompanhamento = "INSERT INTO acompanhamento (`id_chamado`, `id_user`, `id_user_tecnico`, `idfr_chamado`, `acao`, `descricao_acao`)
                                            VALUES('$id_chamado', '$id_user','$id_user_tecnico','$idfr_chamado','$acao','$descricao_acao')";
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
