<?php
include "../../config/dbconnect.php";
include "../../config/httpaccess.php";

$res = array('error' => false, 'msg' => '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($_GET['action'])) {
        $action = $_GET['action'];
        //$action = "AtualizaChamado";

        if ($action == "AtualizaChamado") {
            $id_chamado = $mysqli_con->real_escape_string($data['id_chamado']);
            $id_user = $mysqli_con->real_escape_string($data['id_user']);
            $idfr_chamado = $mysqli_con->real_escape_string($data['idfr_chamado']);
            $titulo = $mysqli_con->real_escape_string($data['titulo_chamado']);
            $descricao = $mysqli_con->real_escape_string($data['descricao_chamado']);
            $gravidade = $mysqli_con->real_escape_string($data['gravidade']);
            $urgencia = $mysqli_con->real_escape_string($data['urgencia']);
            $tendencia = $mysqli_con->real_escape_string($data['tendencia']);

            $sql_atualiza = "UPDATE chamados SET titulo_chamado = '$titulo', descricao_chamado = '$descricao', gravidade = '$gravidade', urgencia = '$urgencia', tendencia = '$tendencia' WHERE id_chamado = '$id_chamado'";

            $result_atualiza = $mysqli_con->query($sql_atualiza);

            if ($result_atualiza) {
                $acao = "USUARIO $id_user ATUALIZOU O CHAMADO $idfr_chamado";
                $sql_acompanhamento = "INSERT INTO acompanhamento (`id_chamado`, `id_user`, `idfr_chamado`, `acao`) VALUES('$id_chamado', '$id_user','$idfr_chamado','$acao')";
                $result_acompanhamento = $mysqli_con->query($sql_acompanhamento);

                if ($result_acompanhamento){
                    $res['msg'] = "Chamado atualizado com sucesso!";
                } else {
                    $res['error'] = true;
                    $res['msg'] = "Erro ao atualizar chamado: " . $mysqli_con->error;
                }
            }
        } else {
            $res['error'] = true;
            $res['msg'] = "Ação inválida";
        }

        if ($action == "CancelaChamado") {
            $id_chamado = $mysqli_con->real_escape_string($data['id_chamado']);
            $id_user = $mysqli_con->real_escape_string($data['id_user']);
            $idfr_chamado = $mysqli_con->real_escape_string($data['idfr_chamado']);

            $sql_cancela = "UPDATE chamados SET status_chamado = 0 WHERE id_chamado = '$id_chamado'";

            $result_cancela = $mysqli_con->query($sql_cancela);

            if ($result_cancela) {
                $acao = "USUARIO $id_user CANCELOU O CHAMADO $idfr_chamado";
                $sql_acompanhamento = "INSERT INTO acompanhamento (`id_chamado`, `id_user`, `idfr_chamado`, `acao`) VALUES('$id_chamado', '$id_user','$idfr_chamado','$acao')";
                $result_acompanhamento = $mysqli_con->query($sql_acompanhamento);

                if ($result_acompanhamento){
                    $res['msg'] = "Chamado cancelado com sucesso!";
                } else {
                    $res['error'] = true;
                    $res['msg'] = "Erro ao atualizar chamado: " . $mysqli_con->error;
                }
            }
        } else {
            $res['error'] = true;
            $res['msg'] = "Ação inválida";
        }
    } else {
        $res['error'] = true;
        $res['msg'] = "Ação não especificada";
    }
} else {
    $res['error'] = true;
    $res['msg'] = "Método de requisição inválido!";
}

$mysqli_con->close();
header("Content-type: application/json");
echo json_encode($res);
?>