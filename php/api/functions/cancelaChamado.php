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

            $sql_cancela = "UPDATE chamados SET status_chamado = 0, observacao = '$observacao', data_atualizacao = NOW() WHERE id_chamado = '$id_chamado'";

            $result_cancela = $mysqli_con->query($sql_cancela);

            if ($result_cancela) {
                $acao = "USUARIO $id_user CANCELOU O CHAMADO $idfr_chamado";
                $sql_acompanhamento = "INSERT INTO acompanhamento (`id_chamado`, `id_user`, `idfr_chamado`, `acao`, `descricao_acao`, `status_chamado`) VALUES('$id_chamado', '$id_user','$idfr_chamado','$acao','$observacao',0)";
                $result_acompanhamento = $mysqli_con->query($sql_acompanhamento);

                if ($result_acompanhamento){
                    $res['msg'] = "Chamado cancelado com sucesso!";
                } else {
                    $res['error'] = true;
                    $res['msg'] = "Erro ao cancelar chamado: " . $mysqli_con->error;
                }
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