<?php
include "../../config/dbconnect.php";
include "../../config/httpaccess.php";

$res = array('error' => false, 'msg' => '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($_GET['action'])) {
        $action = $_GET['action'];

        if ($action == "AtualizaChamado") {
            $id_chamado = $mysqli_con->real_escape_string($data['id_chamado']);
            $id_user = $mysqli_con->real_escape_string($data['id_user']);
            $idfr_chamado = $mysqli_con->real_escape_string($data['idfr_chamado']);
            $titulo = $mysqli_con->real_escape_string($data['titulo_chamado']);
            $descricao = $mysqli_con->real_escape_string($data['descricao_chamado']);
            $gravidade = $mysqli_con->real_escape_string($data['gravidade']);
            $urgencia = $mysqli_con->real_escape_string($data['urgencia']);
            $tendencia = $mysqli_con->real_escape_string($data['tendencia']);

            $select_user = "SELECT id_user, idfr_code_user FROM users WHERE id_user = '$id_user'";
            $res_select_user = $mysqli_con->query($select_user);
            $obj_u = $res_select_user->fetch_object();

            if ($res_select_user->num_rows == 1) {
                $id_user = $obj_u->id_user;
                $idfr_code_user = $obj_u->idfr_code_user;

                $select_chamado = "SELECT * FROM chamados WHERE id_chamado='$id_chamado'";
                $res_select_chamado = $mysqli_con->query($select_chamado);
                $obj_c = $res_select_chamado->fetch_object();

                if ($res_select_chamado->num_rows == 1) {
                    $status_chamado = $obj_c->status_chamado;
                    $total_acoes = $obj_c->total_acoes;
                    $total_acoes_upd = $total_acoes++;

                    if ($status_chamado != 4 || $status_chamado != 0) {
                        $sql_atualiza = "UPDATE chamados SET titulo_chamado = '$titulo', descricao_chamado = '$descricao', gravidade = '$gravidade', urgencia = '$urgencia', tendencia = '$tendencia', data_atualizacao = NOW(), total_acoes = '$total_acoes' WHERE id_chamado = '$id_chamado'";

                        $result_atualiza = $mysqli_con->query($sql_atualiza);

                        if ($result_atualiza) {

                            $acao = "USUARIO $idfr_code_user ATUALIZOU O CHAMADO $idfr_chamado";
                            $sql_acompanhamento = "INSERT INTO acompanhamento (`id_chamado`, `id_user`, `idfr_code_user`, `idfr_chamado`, `acao`, `descricao_acao`, `id_usuario_acao`, `status_chamado`) VALUES('$id_chamado', '$id_user','$idfr_code_user','$idfr_chamado','$acao','$descricao','$id_user','$status_chamado')";
                            $result_acompanhamento = $mysqli_con->query($sql_acompanhamento);

                            if ($result_acompanhamento) {
                                $res['msg'] = "Chamado atualizado com sucesso!";
                            } else {
                                $res['error'] = true;
                                $res['msg'] = "Erro ao atualizar chamado: " . $mysqli_con->error;
                            }
                        } else {
                            $res['error'] = true;
                            $res['msg'] = "Erro inesperado! (Declaração não pôde ser executada na base)";
                        }
                    } else {
                        $res['error'] = true;
                        $res['msg'] = "Erro! Chamado está concluído ou cancelado, impossível editar!";
                    }
                } else {
                    $res['error'] = true;
                    $res['msg'] = "Erro inesperado! (Chamado não encontrado)";
                }
            } else{
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