<?php
include "../../config/dbconnect.php";
include "../../config/httpaccess.php";
require_once '../config/dbconnect.php';

$res = array('error' => false, 'msg' => '');

if (isset($_GET['action'])) {
    $action = $_GET['action'];

    if ($action == "InsertChamado") {
        $titulo = $mysqli_con->real_escape_string($_POST['titulo']);
        $descricao = $mysqli_con->real_escape_string($_POST['descricao']);
        $gravidade = $mysqli_con->real_escape_string($_POST['gravidade']);
        $urgencia = $mysqli_con->real_escape_string($_POST['urgencia']);
        $tendencia = $mysqli_con->real_escape_string($_POST['tendencia']);
        //$anexo = $mysqli_con->real_escape_string($_POST['anexo']);

        $prioridade = (intval($gravidade) * intval($urgencia) * intval($tendencia));

        $id_user = $_POST['id_user'];

        $select = "SELECT id_user FROM users WHERE id_user = '$id_user'";
        $res_select = $mysqli_con->query($select);
        $obj = $res_select->fetch_object();

        if ($res_select->num_rows == 1) {
            $id_user = $obj->id_user;

            $sql = "INSERT INTO chamados(`id_user`,`titulo_chamado`,`descricao_chamado`,`gravidade`,`urgencia`,`tendencia`,`prioridade_chamado`) VALUES('$id_user','$titulo','$descricao','$gravidade','$urgencia','$tendencia','$prioridade')";

            $result = $mysqli_con->query($sql);
            $res['msg'] = "Chamado registrado com sucesso!";

            // Adicionar código para registrar métrica
            try {
                $metric = "chamados_criados";
                $query = "INSERT INTO helptek_analytics (metric, value) VALUES ('$metric', 1) ON DUPLICATE KEY UPDATE value = value + 1";
                $stmt = $mysqli_con->prepare($query);
                $stmt->execute();
            } catch (mysqli_sql_exception $e) {
                $res['error'] = true;
                $res['msg'] = "Erro ao registrar métrica de analytics: " . $e->getMessage();
            }

        } else {
            $res['error'] = true;
            $res['msg'] = "Erro ao registrar chamado, acionar TI! Cod.171 ID: " . $id_user;
        }

    } else {
        $res['error'] = true;
        $res['msg'] = "Ocorreu um erro inesperado! Tente novamente mais tarde";
    }
}

$mysqli_con->close();
header("Content-type: application/json");
echo json_encode($res);
die();
?>
