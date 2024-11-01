<?php
global $mysqli_con;
include "../../../../config/dbconnect.php";
include "../../../../config/httpaccess.php";

$res_detalhamento = array('error' => false, 'msg' => '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['id_chamado']) && isset($_POST['idfr_chamado']) && isset($_POST['observacao_detalhamento_tecnico']) && isset($_POST['id_user_tecnico'])) {

        $id_chamado = $mysqli_con->real_escape_string($_POST['id_chamado']);
        $idfr_chamado = $mysqli_con->real_escape_string($_POST['idfr_chamado']);
        $observacao_detalhamento_tecnico = $mysqli_con->real_escape_string($_POST['observacao_detalhamento_tecnico']);
        $id_user_tecnico = $mysqli_con->real_escape_string($_POST['id_user_tecnico']);

        if (empty($id_chamado) || empty($id_user_tecnico) || strlen($observacao_detalhamento_tecnico) > 500 || empty($idfr_chamado)) {
            $res_detalhamento['error'] = true;
            $res_detalhamento['msg'] = "Erro: Parâmetros inválidos!";
        } else {

            $mysqli_con->begin_transaction();

            try {

                // Preparar a chamada para a stored procedure
                $stmt = $mysqli_con->prepare("CALL SolicitarDetalhamento(?, ?, ?, ?)");
                $stmt->bind_param("iisi", $id_chamado, $idfr_chamado, $observacao_detalhamento_tecnico, $id_user_tecnico);


                // Executar a query
                if ($stmt->execute()) {
                    $mysqli_con->commit();
                    $res_detalhamento['msg'] = "Solicitação de detalhamento enviada com sucesso!";
                } else {
                    // Lançar exceção em caso de erro na execução
                    throw new Exception("Erro ao solicitar detalhamento: " . $stmt->error);
                }

                $stmt->close();

            } catch (Exception $e) {
                $mysqli_con->rollback();
                $res_detalhamento['error'] = true;
                $res_detalhamento['msg'] = "Erro ao executar transação: " . $e->getMessage();
            }
        }
    } else {
        $res_detalhamento['error'] = true;
        $res_detalhamento['msg'] = "Erro ao receber dados de preenchimento!";
    }
} else {
    $res_detalhamento['error'] = true;
    $res_detalhamento['msg'] = "Método de requisição inválido!";
}
$mysqli_con->close();
header("Content-type: application/json");
echo json_encode($res_detalhamento);
die();
?>