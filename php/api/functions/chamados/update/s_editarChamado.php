<?php
global $mysqli_con;
include "../../../../config/dbconnect.php";
include "../../../../config/httpaccess.php";

$res_edita = array('error' => false, 'msg' => '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['id_chamado'])
        && isset($_POST['titulo_chamado'])
        && isset($_POST['descricao_chamado'])
        && isset($_POST['id_setor'])
        && isset($_POST['peso'])
        && isset($_POST['gravidade'])
        && isset($_POST['urgencia'])
        && isset($_POST['tendencia'])
        && isset($_POST['id_user'])
        && isset($_POST['idfr_code_user'])) {

        $id_chamado = $mysqli_con->real_escape_string($_POST['id_chamado']);
        $titulo_chamado = $mysqli_con->real_escape_string($_POST['titulo_chamado']);
        $descricao_chamado = $mysqli_con->real_escape_string($_POST['descricao_chamado']);
        $id_setor = $mysqli_con->real_escape_string($_POST['id_setor']);
        $gravidade = $mysqli_con->real_escape_string($_POST['gravidade']);
        $urgencia = $mysqli_con->real_escape_string($_POST['urgencia']);
        $tendencia = $mysqli_con->real_escape_string($_POST['tendencia']);
        $peso = $mysqli_con->real_escape_string($_POST['peso']);
        $prioridade = intval($peso) * intval($gravidade) * intval($urgencia) * intval($tendencia);
        $id_user = $_POST['id_user'];
        $idfr_code_user = $_POST['idfr_code_user'];

        if (empty($id_chamado) || empty($titulo_chamado) || !isset($descricao_chamado) || !isset($id_setor) || !isset($gravidade) || !isset($urgencia) || !isset($tendencia) || !isset($peso) || !isset($prioridade)) {
            $res_edita['error'] = true;
            $res_edita['msg'] = "Parâmetros inválidos ou campos obrigatórios faltando!";
        } else {

            $mysqli_con->begin_transaction();

            try {

                $anexos = isset($_POST['anexosUrls']) ? $_POST['anexosUrls'] : [];

                // Converter os anexos para JSON
                $json_anexos = json_encode($anexos);

                // Chamada da stored procedure
                $stmt = $mysqli_con->prepare("CALL EditarChamado(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param(
                    'iissiiiiiss',
                    $id_chamado,
                    $id_user,           // ID do usuário
                    $titulo_chamado,            // Título do chamado
                    $descricao_chamado,         // Descrição do chamado
                    $id_setor,          // ID do setor
                    $gravidade,         // Gravidade
                    $urgencia,          // Urgência
                    $tendencia,         // Tendência
                    $prioridade,        // Prioridade calculada
                    $idfr_code_user,    // Código do usuário
                    $json_anexos        // JSON com os anexos
                );

                // Executar a query
                if ($stmt->execute()) {
                    $mysqli_con->commit();
                    $res_edita['msg'] = "Chamado atualizado com sucesso!";
                } else {
                    throw new Exception("Erro ao atualizar chamado!");
                }

                $stmt->close();

            } catch (Exception $e) {
                $mysqli_con->rollback();
                $res_edita['error'] = true;
                $res_edita['msg'] = "Erro ao executar transação: " . $e->getMessage();
            }
        }
    } else {
        $res_edita['error'] = true;
        $res_edita['msg'] = "Erro ao receber dados de preenchimento!";
    }
} else {
    $res_edita['error'] = true;
    $res_edita['msg'] = "Método de requisição inválido!";
}
$mysqli_con->close();
header("Content-type: application/json");
echo json_encode($res_edita);
die();
?>