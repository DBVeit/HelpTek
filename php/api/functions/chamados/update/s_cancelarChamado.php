<?php
global $mysqli_con;
include "../../../../config/dbconnect.php";
include "../../../../config/httpaccess.php";

$res_cancela = array('error' => false, 'msg' => '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['id_chamado']) && isset($_POST['idfr_chamado']) && isset($_POST['observacao']) && isset($_POST['id_user']) && isset($_POST['idfr_code_user'])) {

        $id_chamado = $mysqli_con->real_escape_string($_POST['id_chamado']);
        $idfr_chamado = $mysqli_con->real_escape_string($_POST['idfr_chamado']);
        $observacao = $mysqli_con->real_escape_string($_POST['observacao']);
        $id_user = $_POST['id_user'];
        $idfr_code_user = $_POST['idfr_code_user'];
        $mysqli_con->begin_transaction();

        try {

            // Preparar a chamada para a stored procedure
            $stmt = $mysqli_con->prepare("CALL CancelarChamado(?, ?, ?, ?, ?)");
            $stmt->bind_param("iisss", $id_chamado, $id_user, $idfr_chamado, $observacao, $idfr_code_user);


            // Executar a query
            if ($stmt->execute()) {
                $mysqli_con->commit();
                $res_cancela['msg'] = "Chamado cancelado com sucesso!";
            } else {
                // Lançar exceção em caso de erro na execução
                throw new Exception("Erro ao cancelar o chamado: " . $stmt->error);
            }

            $stmt->close();

        } catch (Exception $e) {
            $mysqli_con->rollback();
            $res_cancela['error'] = true;
            $res_cancela['msg'] = "Erro ao executar transação: " . $e->getMessage();
        }
    } else {
        $res_cancela['error'] = true;
        $res_cancela['msg'] = "Erro ao receber dados de preenchimento!";
    }
} else {
    $res_cancela['error'] = true;
    $res_cancela['msg'] = "Método de requisição inválido!";
}
$mysqli_con->close();
header("Content-type: application/json");
echo json_encode($res_cancela);
die();
?>