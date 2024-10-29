<?php
global $mysqli_con;
include "../../../../config/dbconnect.php";
include "../../../../config/httpaccess.php";

$res_avalia = array('error' => false, 'msg' => '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['id_chamado'])
        && isset($_POST['observacao'])
        && isset($_POST['solicitacao_atendida'])
        && isset($_POST['id_user'])) {

        $id_chamado = $mysqli_con->real_escape_string($_POST['id_chamado']);
        $id_user = $mysqli_con->real_escape_string($_POST['id_user']);
        $solicitacao_atendida = $mysqli_con->real_escape_string($_POST['solicitacao_atendida']);
        $observacao = $mysqli_con->real_escape_string($_POST['observacao']);

        if (empty($id_chamado) || empty($id_user) || !isset($solicitacao_atendida) || empty($observacao)) {
            $res_avalia['error'] = true;
            $res_avalia['msg'] = "Parâmetros inválidos ou campos obrigatórios faltando!";
        } else {

            $mysqli_con->begin_transaction();

            try {
                // Preparar a chamada para a stored procedure
                $stmt = $mysqli_con->prepare("CALL AvaliarAtendimento(?, ?, ?, ?)");
                $stmt->bind_param("iiis", $id_chamado, $id_user, $solicitacao_atendida, $observacao);

                if ($stmt->execute()) {
                    $res_avalia['msg'] = "Atendimento avaliado com sucesso!";
                } else {
                    throw new Exception("Erro ao avaliar chamado: " . $stmt->error);
                }

                $stmt->close();
            } catch (Exception $e) {
                $res_avalia['error'] = true;
                $res_avalia['msg'] = $e->getMessage();
            }
        }
    } else {
        $res_avalia['error'] = true;
        $res_avalia['msg'] = "Erro ao receber dados de preenchimento!";
    }
} else {
    $res_avalia['error'] = true;
    $res_avalia['msg'] = "Método de requisição inválido!";
}
$mysqli_con->close();
header("Content-type: application/json");
echo json_encode($res_avalia);
die();
?>