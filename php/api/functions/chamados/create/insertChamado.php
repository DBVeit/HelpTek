<?php
global $mysqli_con;
include "../../../../config/dbconnect.php";
include "../../../../config/httpaccess.php";

$res_insert = array('error' => false, 'msg' => '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['titulo'])
        && isset($_POST['descricao'])
        && isset($_POST['setor'])
        && isset($_POST['peso'])
        && isset($_POST['gravidade'])
        && isset($_POST['urgencia'])
        && isset($_POST['tendencia'])
        && isset($_POST['id_user'])
        && isset($_POST['idfr_code_user'])) {

        $titulo = $mysqli_con->real_escape_string($_POST['titulo']);
        $descricao = $mysqli_con->real_escape_string($_POST['descricao']);
        $id_setor = $mysqli_con->real_escape_string($_POST['setor']);
        $gravidade = $mysqli_con->real_escape_string($_POST['gravidade']);
        $urgencia = $mysqli_con->real_escape_string($_POST['urgencia']);
        $tendencia = $mysqli_con->real_escape_string($_POST['tendencia']);
        $peso = $mysqli_con->real_escape_string($_POST['peso']);
        $prioridade = intval($peso) * intval($gravidade) * intval($urgencia) * intval($tendencia);
        $id_user = $_POST['id_user'];
        $idfr_code_user = $_POST['idfr_code_user'];

        if (empty($titulo) || empty($descricao) || !isset($id_setor) || !isset($gravidade) || !isset($urgencia) || !isset($tendencia) || !isset($peso) || !isset($prioridade)) {
            $res_insert['error'] = true;
            $res_insert['msg'] = "Parâmetros inválidos ou campos obrigatórios faltando!";
        } else {

            $mysqli_con->begin_transaction();

            try {
                // Preparar JSON de anexos
                /*$anexos = [];
                $upload_dir = "../../../public/uploads/"; // Diretório onde os arquivos serão armazenados
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                // Verificar e mover os arquivos
                foreach ($_FILES as $key => $file) {
                    $file_name = $file['name'];
                    $file_tmp = $file['tmp_name'];
                    $file_size = $file['size'];
                    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                    $new_file_name = uniqid() . '.' . $file_ext;
                    $file_dest = $upload_dir . $new_file_name;

                    if (move_uploaded_file($file_tmp, $file_dest)) {
                        $anexos[] = [
                            'nome' => $file_name,
                            'caminho' => $file_dest,
                            'tamanho' => $file_size
                        ];
                    }
                }*/
                $anexos = isset($_POST['anexosUrls']) ? $_POST['anexosUrls'] : [];

                // Converter os anexos para JSON
                $json_anexos = json_encode($anexos);

                // Chamada da stored procedure
                $stmt = $mysqli_con->prepare("CALL InserirChamado(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param(
                    'issiiiiiss',
                    $id_user,           // ID do usuário
                    $titulo,            // Título do chamado
                    $descricao,         // Descrição do chamado
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
                    $res_insert['msg'] = "Chamado registrado com sucesso!";
                } else {
                    throw new Exception("Erro ao registrar o chamado!");
                }

                $stmt->close();

            } catch (Exception $e) {
                $mysqli_con->rollback();
                $res_insert['error'] = true;
                $res_insert['msg'] = "Erro ao executar transação: " . $e->getMessage();
            }
        }
    } else {
        $res_insert['error'] = true;
        $res_insert['msg'] = "Erro ao receber dados de preenchimento!";
    }
} else {
    $res_insert['error'] = true;
    $res_insert['msg'] = "Método de requisição inválido!";
}
$mysqli_con->close();
header("Content-type: application/json");
echo json_encode($res_insert);
die();
?>