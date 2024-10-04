<?php
global $mysqli_con;
include "../../../config/dbconnect.php";
include "../../../config/httpaccess.php";

$res_statuser = array('error' => false, 'msg' => '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['id_user']) && isset($_POST['status_user'])) {

        $id_user = $mysqli_con->real_escape_string($_POST['id_user']);
        $status_user = $mysqli_con->real_escape_string($_POST['status_user']);
        //$idfr_code_user = $_POST['idfr_code_user'];

        $mysqli_con->begin_transaction();

        try {

            // Preparar a chamada para a stored procedure
            $stmt = $mysqli_con->prepare("CALL AtivaInativaUsuario(?,?)");
            $stmt->bind_param("ii", $id_user, $status_user);

            // Executar a query
            if ($stmt->execute()) {
                $mysqli_con->commit();
                $res_statuser['msg'] = "Usuário atualizado com sucesso!";
            } else {
                throw new Exception("Erro ao atualizar usuário!");
            }

            $stmt->close();

        } catch (Exception $e) {
            $mysqli_con->rollback();
            $res_statuser['error'] = true;
            $res_statuser['msg'] = "Erro ao executar transação: " . $e->getMessage();
        }
    } else {
        $res_statuser['error'] = true;
        $res_statuser['msg'] = "Erro ao receber dados de preenchimento!";
    }
} else {
    $res_statuser['error'] = true;
    $res_statuser['msg'] = "Método de requisição inválido!";
}
$mysqli_con->close();
header("Content-type: application/json");
echo json_encode($res_statuser);
die();
?>