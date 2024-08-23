<?php
include "../../config/dbconnect.php";
include "../../config/httpaccess.php";

$res = array('error' => false, 'msg' => '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($_GET['action'])) {
        $action = $_GET['action'];

        if ($action == "AtualizaUsuario") {
            $id_user = $mysqli_con->real_escape_string($data['id_user']);
            $name_user = $mysqli_con->real_escape_string($data['name_user']);
            $first_name = $mysqli_con->real_escape_string($data['first_name']);
            $email_user = $mysqli_con->real_escape_string($data['email_user']);
            $id_permissao = $mysqli_con->real_escape_string($data['id_permissao']);

            //$sql_select_user = "SELECT name_user,first_name,email_user,id_permissao FROM users WHERE id_user='$id_user'";

            //$result_sql_select_user = $mysqli_con->query($sql_select_user);

            //while

            $sql_atualiza = "UPDATE users SET name_user = '$name_user', first_name = '$first_name', email_user = '$email_user', id_permissao = '$id_permissao' WHERE id_user = '$id_user'";

            $result_atualiza = $mysqli_con->query($sql_atualiza);

            if ($result_atualiza) {
                /*$acao = "USUARIO $id_user ATUALIZOU O CHAMADO $idfr_chamado";
                $sql_acompanhamento = "INSERT INTO acompanhamento (`id_chamado`, `id_user`, `idfr_chamado`, `acao`) VALUES('$id_chamado', '$id_user','$idfr_chamado','$acao')";
                $result_acompanhamento = $mysqli_con->query($sql_acompanhamento);

                if ($result_acompanhamento){
                    $res['msg'] = "Chamado atualizado com sucesso!";
                } else {
                    $res['error'] = true;
                    $res['msg'] = "Erro ao atualizar chamado: " . $mysqli_con->error;
                }*/

                $res['msg'] = "Dados de usuário atualizados com sucesso!";
            } else {
                $res['error'] = "Erro ao atualizar dados usuário!";
            }
        } else {
            $res['error'] = true;
            $res['msg'] = "Erro inesperado! (Ação inválida ao editar registro)";
        }
    } else {
        $res['error'] = true;
        $res['msg'] = "Erro inesperado! (Ação não especificada)";
    }
} else {
    $res['error'] = true;
    $res['msg'] = "Erro inesperado! (Método de requisição inválido)";
}

$mysqli_con->close();
header("Content-type: application/json");
echo json_encode($res);
?>