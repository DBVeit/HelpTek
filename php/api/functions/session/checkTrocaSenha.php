<?php
global $mysqli_con;
include "../../../config/dbconnect.php";
include "../../../config/httpaccess.php";

$res = array('error' => false, 'msg' => '', 'code' => 200);

if (isset($_GET['action'])) {
    $action = $_GET['action'];

    // Ação para verificar a necessidade de troca de senha
    if ($action == "checkTrocaSenha") {
        if (isset($_GET['id_user'])) {
            $id_user = $mysqli_con->real_escape_string($_GET['id_user']);

            $sql_select = "SELECT troca_senha FROM users WHERE id_user = '$id_user'";
            $result_select = $mysqli_con->query($sql_select);

            if ($result_select) {
                if ($result_select->num_rows > 0) {
                    $row_select = $result_select->fetch_assoc();
                    // Verifica se a troca de senha é necessária
                    if ($row_select['troca_senha'] == 1) {
                        $res['msg'] = "Alterar senha para continuar";
                        $res['code'] = 428; // Código de status para troca de senha obrigatória
                    } else {
                        $res['msg'] = ""; // Sem mensagem se a troca não for necessária
                        $res['code'] = 200; // Código de status normal
                    }
                } else {
                    $res['error'] = true;
                    $res['msg'] = "Usuário não encontrado";
                    $res['code'] = 404; // Código de status para usuário não encontrado
                }
            } else {
                $res['error'] = true;
                $res['msg'] = "Erro na execução da consulta";
                $res['code'] = 500; // Código de status para erro interno
            }
        } else {
            $res['error'] = true;
            $res['msg'] = "ID do usuário não especificado";
            $res['code'] = 400; // Código de status para requisição mal formada
        }
    } else {
        $res['error'] = true;
        $res['msg'] = "Ação inválida";
        $res['code'] = 400; // Código de status para requisição mal formada
    }
} else {
    $res['error'] = true;
    $res['msg'] = "Parâmetros insuficientes!";
    $res['code'] = 400; // Código de status para requisição mal formada
}

// Fechar a conexão e retornar o resultado
$mysqli_con->close();
header("Content-type: application/json");
echo json_encode($res);
die();
?>