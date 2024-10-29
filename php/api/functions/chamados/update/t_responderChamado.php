<?php
global $mysqli_con;
include "../../../../config/dbconnect.php";
include "../../../../config/httpaccess.php";

$res_responde = array('error' => false, 'msg' => '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['id_chamado'])
        && isset($_POST['idfr_chamado'])
        && isset($_POST['id_user_tecnico'])
        && isset($_POST['id_categoria_servico'])
        && isset($_POST['id_categoria_ocorrencia'])
        && isset($_POST['descricao_solucao'])) {

        $id_chamado = $mysqli_con->real_escape_string($_POST['id_chamado']);
        $idfr_chamado = $mysqli_con->real_escape_string($_POST['idfr_chamado']);
        $id_user_tecnico = $mysqli_con->real_escape_string($_POST['id_user_tecnico']);
        $categoria_servico = $mysqli_con->real_escape_string($_POST['id_categoria_servico']);
        $categoria_ocorrencia = $mysqli_con->real_escape_string($_POST['id_categoria_ocorrencia']);
        $descricao_solucao = $mysqli_con->real_escape_string($_POST['descricao_solucao']);

        if (empty($id_chamado) || empty($id_user_tecnico) || empty($categoria_servico) ||
            empty($categoria_ocorrencia) || strlen($descricao_solucao) > 500) {
            $res_responde['error'] = true;
            $res_responde['msg'] = "Erro: Parâmetros inválidos!";
        } else {

            $mysqli_con->begin_transaction();

            try {

                // Preparar a chamada para a stored procedure
                $stmt = $mysqli_con->prepare("CALL ResponderChamado(?, ?, ?, ?, ?)");
                $stmt->bind_param("iiisi", $id_chamado, $categoria_servico, $categoria_ocorrencia, $descricao_solucao, $id_user_tecnico);


                // Executar a query
                if ($stmt->execute()) {
                    $mysqli_con->commit();
                    $res_responde['msg'] = "Atendimento registrado com sucesso!";
                } else {
                    // Lançar exceção em caso de erro na execução
                    throw new Exception("Erro ao responder chamado: " . $stmt->error);
                }

                $stmt->close();

            } catch (Exception $e) {
                $mysqli_con->rollback();
                $res_responde['error'] = true;
                $res_responde['msg'] = "Erro ao executar transação: " . $e->getMessage();
            }
        }
    } else {
        $res_responde['error'] = true;
        $res_responde['msg'] = "Erro ao receber dados de preenchimento!";
    }
} else {
    $res_responde['error'] = true;
    $res_responde['msg'] = "Método de requisição inválido!";
}
$mysqli_con->close();
header("Content-type: application/json");
echo json_encode($res_responde);
die();
?>