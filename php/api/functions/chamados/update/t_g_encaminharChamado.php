<?php
global $mysqli_con;
include "../../../../config/dbconnect.php";
include "../../../../config/httpaccess.php";

$res_encaminha = array('error' => false, 'msg' => '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['id_chamado'])
        && isset($_POST['idfr_chamado'])
        && isset($_POST['id_user_tecnico'])
        && isset($_POST['novoTecnicoResponsavel'])
        && isset($_POST['justificativaEncaminhamento'])) {

        $id_chamado = $mysqli_con->real_escape_string($_POST['id_chamado']);
        $idfr_chamado = $mysqli_con->real_escape_string($_POST['idfr_chamado']);
        $id_user_tecnico = $mysqli_con->real_escape_string($_POST['id_user_tecnico']);
        $novoTecnicoResponsavel = $mysqli_con->real_escape_string($_POST['novoTecnicoResponsavel']);
        $justificativaEncaminhamento = $mysqli_con->real_escape_string($_POST['justificativaEncaminhamento']);

        if (empty($id_chamado) || empty($id_user_tecnico) || empty($novoTecnicoResponsavel) || strlen($justificativaEncaminhamento) > 500) {
            $res_encaminha['error'] = true;
            $res_encaminha['msg'] = "Erro: Parâmetros inválidos!";
        } else {

            $mysqli_con->begin_transaction();

            try {

                // Preparar a chamada para a stored procedure
                $stmt = $mysqli_con->prepare("CALL EncaminharChamado(?, ?, ?, ?)");
                $stmt->bind_param("iiis", $id_chamado, $id_user_tecnico, $novoTecnicoResponsavel, $justificativaEncaminhamento);

                // Executar a query
                if ($stmt->execute()) {
                    $mysqli_con->commit();
                    $res_encaminha['msg'] = "Chamado encaminhado com sucesso!";
                } else {
                    // Lançar exceção em caso de erro na execução
                    throw new Exception("Erro ao encaminhado chamado: " . $stmt->error);
                }

                $stmt->close();

            } catch (Exception $e) {
                $mysqli_con->rollback();
                $res_encaminha['error'] = true;
                $res_encaminha['msg'] = "Erro ao executar transação: " . $e->getMessage();
            }
        }
    } else {
        $res_encaminha['error'] = true;
        $res_encaminha['msg'] = "Erro ao receber dados de preenchimento!";
    }
} else {
    $res_encaminha['error'] = true;
    $res_encaminha['msg'] = "Método de requisição inválido!";
}
$mysqli_con->close();
header("Content-type: application/json");
echo json_encode($res_encaminha);
die();
?>