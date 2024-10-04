<?php
global $mysqli_con;
include "../../../config/dbconnect.php";
include "../../../config/httpaccess.php";

$res_redef = array('error' => false, 'msg' => '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['id_user']) && isset($_POST['encryptedPassword']) && isset($_POST['encryptedPasswordConf'])) {

        $id_user = $mysqli_con->real_escape_string($_POST['id_user']);
        $senha = $mysqli_con->real_escape_string($_POST['encryptedPassword']);
        $confirma_senha = $mysqli_con->real_escape_string($_POST['encryptedPasswordConf']);
        //$idfr_code_user = $_POST['idfr_code_user'];

        $senha_confirmada = $senha === $confirma_senha ? true : false;

        if ($senha_confirmada) {
            $mysqli_con->begin_transaction();

            try {

                // Preparar a chamada para a stored procedure
                $stmt = $mysqli_con->prepare("CALL RedefineSenha(?,?)");
                $stmt->bind_param("is", $id_user, $senha);

                // Executar a query
                if ($stmt->execute()) {
                    $mysqli_con->commit();
                    $res_redef['msg'] = "Senha alterada com sucesso!";
                } else {
                    throw new Exception("Erro ao redefinir senha!");
                }

                $stmt->close();

            } catch (Exception $e) {
                $mysqli_con->rollback();
                $res_redef['error'] = true;
                $res_redef['msg'] = "Erro ao executar transação: " . $e->getMessage();
            }
        } else {
            $res_redef['error'] = true;
            $res_redef['msg'] = "Senhas incompatíveis!";
        }
    } else {
        $res_redef['error'] = true;
        $res_redef['msg'] = "Erro ao receber dados de preenchimento!";
    }
} else {
    $res_redef['error'] = true;
    $res_redef['msg'] = "Método de requisição inválido!";
}
$mysqli_con->close();
header("Content-type: application/json");
echo json_encode($res_redef);
die();
?>