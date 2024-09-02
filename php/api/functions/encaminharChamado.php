<?php
include "../../config/dbconnect.php";
include "../../config/httpaccess.php";

$res = array('error' => false, 'msg' => '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($_GET['action'])) {
        $action = $_GET['action'];

        if ($action == "EncaminharChamado") {
            $id_chamado = $mysqli_con->real_escape_string($data['id_chamado']);
            $id_user_tecnico = $mysqli_con->real_escape_string($data['id_user_tecnico']);
            $novoTecnicoResponsavel = $mysqli_con->real_escape_string($data['novoTecnicoResponsavel']);
            $justificativaEncaminhamento = $mysqli_con->real_escape_string($data['justificativaEncaminhamento']);

            $sql_select_tecnico_atual = "SELECT id_user,idfr_code_user FROM users WHERE id_user = '$id_user_tecnico'";
            $res_select_tecnico_atual = $mysqli_con->query($sql_select_tecnico_atual);
            $obj_uatual = $res_select_tecnico_atual->fetch_object();

            if ($res_select_tecnico_atual->num_rows == 1) {
                $idfr_code_user_atual = $obj_uatual->idfr_code_user;

                $sql_select_tecnico_novo = "SELECT id_user,idfr_code_user FROM users WHERE id_user = '$novoTecnicoResponsavel'";
                $res_select_tecnico_novo = $mysqli_con->query($sql_select_tecnico_novo);
                $obj_unovo = $res_select_tecnico_novo->fetch_object();

                if ($res_select_tecnico_novo->num_rows == 1) {
                    $idfr_code_user_novo = $obj_unovo->idfr_code_user;

                    $sql_get_chamado = "SELECT idfr_chamado, id_user, id_user_tecnico, status_chamado, total_acoes FROM chamados WHERE id_chamado = '$id_chamado'";
                    $result_get_chamado = $mysqli_con->query($sql_get_chamado);

                    if ($result_get_chamado && $result_get_chamado->num_rows > 0) {
                        $row = $result_get_chamado->fetch_assoc();
                        $idfr_chamado = $row['idfr_chamado'];
                        $id_user = $row['id_user'];
                        $id_user_tecnico = $row['id_user_tecnico'];
                        $status_chamado = $row['status_chamado'];
                        $total_acoes = $row['total_acoes'];
                        $total_acoes_upd = ++$total_acoes;

                        $mysqli_con->begin_transaction();

                        try {
                            $acao = "USUARIO $idfr_code_user_atual ENCAMINHOU O CHAMADO $idfr_chamado AO USUARIO $idfr_code_user_novo";
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
                                                    VALUES
                                                        ('$id_chamado',
                                                         '$id_user',
                                                         '$id_user_tecnico',
                                                         '$idfr_code_user_atual',
                                                         '$idfr_chamado',
                                                         '$acao',
                                                         '$justificativaEncaminhamento',
                                                         '$id_user_tecnico',
                                                         '$status_chamado')";
                            $result_acompanhamento = $mysqli_con->query($sql_acompanhamento);

                            if ($result_acompanhamento) {
                                $sql_update_chamado = "UPDATE chamados SET 
                                        id_user_tecnico = '$novoTecnicoResponsavel', 
                                        id_usuario_atual = '$novoTecnicoResponsavel', 
                                        id_usuario_anterior = '$id_user_tecnico', 
                                        data_atualizacao = NOW(),
                                        total_acoes = '$total_acoes_upd'
                                    WHERE id_chamado = '$id_chamado'";
                                $result_update_chamado = $mysqli_con->query($sql_update_chamado);

                                if ($result_update_chamado) {
                                    $mysqli_con->commit();
                                    $res['msg'] = "Chamado encaminhado com sucesso!";
                                } else {
                                    throw new Exception("Erro ao atualizar registro: " . $mysqli_con->error);
                                }
                            } else {
                                throw new Exception("Erro ao registrar acompanhamento: " . $mysqli_con->error);
                            }
                        } catch (Exception $e) {
                            // Rollback em caso de erro
                            $mysqli_con->rollback();
                            $res['error'] = true;
                            $res['msg'] = "Erro ao encaminhar chamado: ".$e->getMessage();
                        }
                        /*$sql = "UPDATE chamados SET id_user_tecnico = '$novoTecnicoResponsavel', id_usuario_atual = '$novoTecnicoResponsavel', data_atualizacao = NOW() WHERE id_chamado = '$id_chamado'";
                        $result = $mysqli_con->query($sql);

                        if ($result) {
                            $acao = "USUARIO $id_user_tecnico ENCAMINHOU O CHAMADO $idfr_chamado AO USUARIO $novoTecnicoResponsavel";
                            $sql_acompanhamento = "INSERT INTO acompanhamento (`id_chamado`, `id_user`, `id_user_tecnico`, `idfr_chamado`, `acao`)
                                                    VALUES('$id_chamado', '$id_user','$id_user_tecnico','$idfr_chamado','$acao')";
                            $result_acompanhamento = $mysqli_con->query($sql_acompanhamento);

                            if ($result_acompanhamento) {
                                $res['msg'] = "Chamado encaminhado com sucesso!";
                            }else {
                                $res['error'] = true;
                                $res['msg'] = "Erro ao registrar acompanhamento: " . $mysqli_con->error;
                            }
                        }else{
                            $res['error'] = true;
                            $res['msg'] = "Erro ao encaminhar chamado: " . $mysqli_con->error;
                        }*/
                    } else {
                        $res['error'] = true;
                        $res['msg'] = "Chamado não encontrado!";
                    }
                } else {
                    $res['error'] = true;
                    $res['msg'] = "Erro inesperado! (Novo responsável não encontrado)";
                }
            } else {
                $res['error'] = true;
                $res['msg'] = "Erro inesperado! (Responsável atual não encontrado)";
            }
        } else {
            $res['error'] = true;
            $res['msg'] = "Erro inesperado! (Ação inválida ao encaminhar registro)";
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
