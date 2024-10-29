<?php
global $mysqli_con;
include "../../../../config/dbconnect.php";
include "../../../../config/httpaccess.php";

$res_assume = array('error' => false, 'msg' => '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['id_chamado']) && isset($_POST['id_user_tecnico'])) {

        $id_chamado = $mysqli_con->real_escape_string($_POST['id_chamado']);
        $id_user_tecnico = $mysqli_con->real_escape_string($_POST['id_user_tecnico']);

        // Verificar o status do chamado antes de assumir
        $sql_get_status = "SELECT status_chamado FROM chamados WHERE id_chamado = '$id_chamado'";
        $result_status = $mysqli_con->query($sql_get_status);

        if ($result_status->num_rows > 0) {
            $row = $result_status->fetch_assoc();
            if ($row['status_chamado'] == 2) {
                // O chamado já está em atendimento, retornar erro
                $res_assume['error'] = true;
                $res_assume['msg'] = "Ação indisponível, chamado já assumido por outro técnico";
            } else {
                $mysqli_con->begin_transaction();

                try {

                    // Preparar a chamada para a stored procedure
                    $stmt = $mysqli_con->prepare("CALL AssumirChamado(?, ?)");
                    $stmt->bind_param("ii", $id_chamado, $id_user_tecnico);


                    // Executar a query
                    if ($stmt->execute()) {
                        $mysqli_con->commit();
                        $res_assume['msg'] = "Chamado assumido com sucesso!";
                    } else {
                        // Lançar exceção em caso de erro na execução
                        throw new Exception("Erro ao assumir o chamado: " . $stmt->error);
                    }

                    $stmt->close();

                } catch (Exception $e) {
                    $mysqli_con->rollback();
                    $res_assume['error'] = true;
                    $res_assume['msg'] = "Erro ao executar transação: " . $e->getMessage();
                }
            }
        } else {
            $res_assume['error'] = true;
            $res_assume['msg'] = "Chamado não encontrado.";
        }
    } else {
        $res_assume['error'] = true;
        $res_assume['msg'] = "Erro ao receber dados de preenchimento!";
    }
} else {
    $res_assume['error'] = true;
    $res_assume['msg'] = "Método de requisição inválido!";
}
$mysqli_con->close();
header("Content-type: application/json");
echo json_encode($res_assume);
die();
?>