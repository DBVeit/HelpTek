<?php
include "../../config/dbconnect.php";
include "../../config/httpaccess.php";

$res = array('error' => false, 'msg' => '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $id_chamado = $mysqli_con->real_escape_string($data['id_chamado']);
    $id_user_tecnico = $mysqli_con->real_escape_string($data['id_user_tecnico']);


    $sql_get_chamado = "SELECT idfr_chamado, id_user FROM chamados WHERE id_chamado = '$id_chamado'";
    $result_get_chamado = $mysqli_con->query($sql_get_chamado);

    if ($result_get_chamado && $result_get_chamado->num_rows > 0){
        $row = $result_get_chamado->fetch_assoc();
        $idfr_chamado = $row['idfr_chamado'];
        $id_user = $row['id_user'];

        $sql = "UPDATE chamados SET id_user_tecnico = '$id_user_tecnico', status_chamado = 2, id_usuario_atual = '$id_user_tecnico', data_atualizacao = NOW() WHERE id_chamado = '$id_chamado'";
        $result = $mysqli_con->query($sql);

        if ($result) {
            $acao = "USUARIO $id_user_tecnico ASSUMIU O CHAMADO $idfr_chamado";
            $sql_acompanhamento = "INSERT INTO acompanhamento (`id_chamado`, `id_user`, `id_user_tecnico`, `idfr_chamado`, `acao`)
                                    VALUES('$id_chamado', '$id_user','$id_user_tecnico','$idfr_chamado','$acao')";
            $result_acompanhamento = $mysqli_con->query($sql_acompanhamento);

            if ($result_acompanhamento) {
                $res['msg'] = "Chamado assumido com sucesso!";
            }else {
                $res['error'] = true;
                $res['msg'] = "Erro ao registrar acompanhamento: " . $mysqli_con->error;
            }
        }else{
            $res['error'] = true;
            $res['msg'] = "Erro ao assumir chamado: " . $mysqli_con->error;
        }

    }else {
        $res['error'] = true;
        $res['msg'] = "Chamado não encontrado!";
    }
} else {
    $res['error'] = true;
    $res['msg'] = "Método de requisição inválido!";
}

$mysqli_con->close();
header("Content-type: application/json");
echo json_encode($res);
?>
