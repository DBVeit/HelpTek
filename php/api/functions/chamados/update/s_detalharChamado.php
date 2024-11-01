<?php
global $mysqli_con;
include "../../../../config/dbconnect.php";
include "../../../../config/httpaccess.php";

$res_detalhar = array('error' => false, 'msg' => '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['id_chamado']) && isset($_POST['idfr_chamado']) && isset($_POST['observacao_detalhamento_solicitante']) && isset($_POST['id_user'])) {

        $id_chamado = $mysqli_con->real_escape_string($_POST['id_chamado']);
        $idfr_chamado = $mysqli_con->real_escape_string($_POST['idfr_chamado']);
        $observacao_detalhamento_solicitante = $mysqli_con->real_escape_string($_POST['observacao_detalhamento_solicitante']);
        $id_user = $mysqli_con->real_escape_string($_POST['id_user']);

        if (empty($id_chamado) || empty($id_user) || strlen($observacao_detalhamento_solicitante) > 500 || empty($idfr_chamado)) {
            $res_detalhar['error'] = true;
            $res_detalhar['msg'] = "Erro: Parâmetros inválidos!";
        } else {

            $mysqli_con->begin_transaction();

            try {

                // Preparar a chamada para a stored procedure
                $stmt = $mysqli_con->prepare("CALL DetalharChamado(?, ?, ?)");
                $stmt->bind_param("iis", $id_chamado,$id_user, $observacao_detalhamento_solicitante);


                // Executar a query
                if ($stmt->execute()) {
                    $mysqli_con->commit();
                    $res_detalhar['msg'] = "Detalhamento enviado com sucesso!";
                } else {
                    // Lançar exceção em caso de erro na execução
                    throw new Exception("Erro ao enviar detalhamento: " . $stmt->error);
                }

                $stmt->close();

            } catch (Exception $e) {
                $mysqli_con->rollback();
                $res_detalhar['error'] = true;
                $res_detalhar['msg'] = "Erro ao executar transação: " . $e->getMessage();
            }
        }
    } else {
        $res_detalhar['error'] = true;
        $res_detalhar['msg'] = "Erro ao receber dados de preenchimento!";
    }
} else {
    $res_detalhar['error'] = true;
    $res_detalhar['msg'] = "Método de requisição inválido!";
}
$mysqli_con->close();
header("Content-type: application/json");
echo json_encode($res_detalhar);
die();
?>