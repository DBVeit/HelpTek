<?php
include "../../config/dbconnect.php";
include "../../config/httpaccess.php";

$res = array('error' => false, 'msg' => '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($_GET['action'])) {
        $action = $_GET['action'];

        if ($action == "ResponderChamado") {

            $id_chamado = $mysqli_con->real_escape_string($data['id_chamado']);
            $categoria_servico = $mysqli_con->real_escape_string($data['categoriaServico']);
            $categoria_ocorrencia = $mysqli_con->real_escape_string($data['categoriaOcorrencia']);
            $descricao_solucao = $mysqli_con->real_escape_string($data['descricaoSolucao']);
            $id_user_tecnico = $mysqli_con->real_escape_string($data['id_user_tecnico']);

            $sql_users = "SELECT id_user, idfr_code_user FROM users WHERE id_user = '$id_user_tecnico'";
            $result_users = $mysqli_con->query($sql_users);
            $obj = $result_users->fetch_object();

            if ($result_users->num_rows == 1) {
                $idfr_code_user = $obj->idfr_code_user;

                $sql_check_tecnico = "SELECT id_user_tecnico FROM chamados WHERE id_user_tecnico = '$id_user_tecnico' AND id_chamado = '$id_chamado'";
                $result_check_tecnico = $mysqli_con->query($sql_check_tecnico);

                if ($result_check_tecnico && $result_check_tecnico->num_rows > 0) {

                    $sql_get_chamado = "SELECT idfr_chamado, id_user, total_acoes FROM chamados WHERE id_chamado = '$id_chamado'";
                    $result_get_chamado = $mysqli_con->query($sql_get_chamado);

                    if ($result_get_chamado && $result_get_chamado->num_rows > 0) {
                        $row = $result_get_chamado->fetch_assoc();
                        $idfr_chamado = $row['idfr_chamado'];
                        $id_user_chamado = $row['id_user'];
                        $total_acoes = $row['total_acoes'];
                        $total_acoes_upd = ++$total_acoes;

                        $sql = "UPDATE chamados 
                                    SET descricao_solucao = '$descricao_solucao', 
                                        id_categoria_servico = '$categoria_servico', 
                                        id_categoria_ocorrencia = '$categoria_ocorrencia', 
                                        id_usuario_atual = '$id_user_chamado', 
                                        id_usuario_anterior = '$id_user_tecnico', 
                                        data_atualizacao = NOW(),
                                        status_chamado = 3,
                                        total_acoes = '$total_acoes_upd' 
                                    WHERE id_chamado = '$id_chamado'";
                        $result = $mysqli_con->query($sql);

                        if ($result) {
                            $acao = "USUARIO $idfr_code_user RESPONDEU AO CHAMADO $idfr_chamado";
                            $sql_acompanhamento = "INSERT INTO acompanhamento 
                                                                (`id_chamado`, 
                                                                 `id_user`, 
                                                                 `id_user_tecnico`, 
                                                                 `idfr_code_user`, 
                                                                 `idfr_chamado`, 
                                                                 `acao`, 
                                                                 `descricao_acao`, 
                                                                 `id_usuario_acao`, 
                                                                 `status_chamado`)
                                                    VALUES('$id_chamado', '$id_user_chamado','$id_user_tecnico','$idfr_code_user','$idfr_chamado','$acao','$descricao_solucao','$id_user_tecnico',3)";
                            $result_acompanhamento = $mysqli_con->query($sql_acompanhamento);

                            if ($result_acompanhamento) {
                                $res['msg'] = "Atendimento registrado com sucesso!";
                            } else {
                                $res['error'] = true;
                                $res['msg'] = "Erro ao registrar acompanhamento: " . $mysqli_con->error;
                            }
                        } else {
                            $res['error'] = true;
                            $res['msg'] = "Erro ao responder chamado: " . $mysqli_con->error;
                        }
                    } else {
                        $res['error'] = true;
                        $res['msg'] = "Chamado não encontrado!";
                    }
                } else {
                    $res['error'] = true;
                    $res['msg'] = "Técnico não encontrado!";
                }
            } else {
                $res['error'] = true;
                $res['msg'] = "Erro inesperado! (Usuário não encontrado)";
            }
        } else {
            $res['error'] = true;
            $res['msg'] = "Erro inesperado! (Ação inválida)";
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
