<?php
include "../../config/dbconnect.php";
include "../../config/httpaccess.php";

$res = array('error' => false, 'msg' => '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($_GET['action'])) {
        $action = $_GET['action'];

        if ($action == "CancelaChamado") {
            $id_chamado = $mysqli_con->real_escape_string($data['id_chamado']);
            $id_user = $mysqli_con->real_escape_string($data['id_user']);
            $idfr_chamado = $mysqli_con->real_escape_string($data['idfr_chamado']);
            $observacao = $mysqli_con->real_escape_string($data['observacao']);

            $select_user = "SELECT id_user, idfr_code_user FROM users WHERE id_user = '$id_user'";
            $res_select_user = $mysqli_con->query($select_user);
            $obj_u = $res_select_user->fetch_object();

            if ($res_select_user->num_rows == 1) {
                $idfr_code_user = $obj_u->idfr_code_user;

                $sql_get_chamado = "SELECT id_chamado, idfr_chamado, id_user, id_user_tecnico, status_chamado, total_acoes FROM chamados WHERE id_chamado = '$id_chamado'";
                $result_get_chamado = $mysqli_con->query($sql_get_chamado);

                if ($result_get_chamado && $result_get_chamado->num_rows > 0) {
                    $row = $result_get_chamado->fetch_assoc();
                    $idfr_chamado = $row['idfr_chamado'];
                    $id_user_tecnico = $row['id_user_tecnico'];
                    $status_chamado = $row['status_chamado'];
                    $total_acoes = $row['total_acoes'];
                    $total_acoes_upd = ++$total_acoes;

                    if ($status_chamado != 4 && $status_chamado != 0) {
                        $sql_cancela = "UPDATE chamados SET 
                                            status_chamado = 0, 
                                            observacao = '$observacao',
                                            data_conclusao = NOW(),
                                            data_atualizacao = NOW(), 
                                            total_acoes = '$total_acoes_upd' 
                                        WHERE id_chamado = '$id_chamado'";
                        $result_cancela = $mysqli_con->query($sql_cancela);

                        if ($result_cancela) {
                            $acao = "USUARIO $idfr_code_user CANCELOU O CHAMADO $idfr_chamado";
                            $sql_acompanhamento = "INSERT INTO acompanhamento 
                                                        (`id_chamado`, 
                                                         `id_user`, 
                                                         `idfr_chamado`, 
                                                         `acao`, 
                                                         `descricao_acao`, 
                                                         `id_usuario_acao`,
                                                         `status_chamado`) 
                                                    VALUES 
                                                        ('$id_chamado', 
                                                         '$id_user',
                                                         '$idfr_chamado',
                                                         '$acao',
                                                         '$observacao',
                                                         '$id_user',
                                                         0)";
                            $result_acompanhamento = $mysqli_con->query($sql_acompanhamento);

                            if ($result_acompanhamento) {
                                $res['msg'] = "Chamado cancelado com sucesso!";
                            } else {
                                $res['error'] = true;
                                $res['msg'] = "Erro ao registrar acompanhamento: " . $mysqli_con->error;
                            }
                        } else {
                            $res['error'] = true;
                            $res['msg'] = "Erro ao cancelar chamado: " . $mysqli_con->error;
                        }
                    } else {
                        $res['error'] = true;
                        $res['msg'] = "Erro inesperado! (Chamado já está concluído ou cancelado)";
                    }
                } else {
                    $res['error'] = true;
                    $res['msg'] = "Erro inesperado! (Chamado não encontrado)";
                }
            } else {
                $res['error'] = true;
                $res['msg'] = "Erro inesperado! (Usuário não encontrado)";
            }
        } else {
            $res['error'] = true;
            $res['msg'] = "Erro inesperado! (Ação inválida ao cancelar registro)";
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