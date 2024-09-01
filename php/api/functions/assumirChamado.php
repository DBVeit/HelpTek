<?php
include "../../config/dbconnect.php";
include "../../config/httpaccess.php";

$res = array('error' => false, 'msg' => '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($_GET['action'])) {
        $action = $_GET['action'];

        if ($action == "AssumirChamado") {

            $id_chamado = $mysqli_con->real_escape_string($data['id_chamado']);
            $id_user_tecnico = $mysqli_con->real_escape_string($data['id_user_tecnico']);

            $select_user = "SELECT id_user, idfr_code_user FROM users WHERE id_user = '$id_user_tecnico'";
            $res_select_user = $mysqli_con->query($select_user);
            $obj = $res_select_user->fetch_object();

            if ($res_select_user->num_rows == 1) {
                $idfr_code_user = $obj->idfr_code_user;

                $sql_get_chamado = "SELECT idfr_chamado, id_user, id_usuario_atual, total_acoes FROM chamados WHERE id_chamado = '$id_chamado'";
                $result_get_chamado = $mysqli_con->query($sql_get_chamado);

                if ($result_get_chamado && $result_get_chamado->num_rows > 0) {
                    $row = $result_get_chamado->fetch_assoc();
                    $idfr_chamado = $row['idfr_chamado'];
                    $id_user = $row['id_user'];
                    $id_usuario_atual = $row['id_usuario_atual'];
                    $total_acoes = $row['total_acoes'];
                    $total_acoes_upd = ++$total_acoes;

                    $sql = "UPDATE chamados SET id_user_tecnico = '$id_user_tecnico', status_chamado = 2, id_usuario_atual = '$id_user_tecnico', id_usuario_anterior = '$id_usuario_atual', data_atualizacao = NOW(), total_acoes = '$total_acoes_upd' WHERE id_chamado = '$id_chamado'";
                    $result = $mysqli_con->query($sql);

                    if ($result) {
                        $acao = "USUARIO $idfr_code_user ASSUMIU O CHAMADO $idfr_chamado";
                        $sql_acompanhamento = "INSERT INTO acompanhamento (`id_chamado`, `id_user`, `id_user_tecnico`, `idfr_code_user`, `idfr_chamado`, `acao`, `id_usuario_acao`, `status_chamado`)
                                                VALUES('$id_chamado', '$id_user','$id_user_tecnico','$idfr_code_user','$idfr_chamado','$acao','$id_user_tecnico',2)";
                        $result_acompanhamento = $mysqli_con->query($sql_acompanhamento);

                        if ($result_acompanhamento) {
                            $res['msg'] = "Chamado assumido com sucesso!";
                        } else {
                            $res['error'] = true;
                            $res['msg'] = "Erro ao registrar acompanhamento: " . $mysqli_con->error;
                        }
                    } else {
                        $res['error'] = true;
                        $res['msg'] = "Erro ao assumir chamado: " . $mysqli_con->error;
                    }
                } else {
                    $res['error'] = true;
                    $res['msg'] = "Erro inesperado (Chamado não encontrado)";
                }
            } else {
                $res['error'] = true;
                $res['msg'] = "Erro inesperado! (Usuário não encontrado)";
            }
        } else {
            $res['error'] = true;
            $res['msg'] = "Erro inesperado! (Ação inválida ao assumir chamado)";
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
