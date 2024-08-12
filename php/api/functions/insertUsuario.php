<?php
include "../../config/dbconnect.php";
include "../../config/httpaccess.php";

$res = array('error' => false, 'msg' => '');

/*if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);*/
if (isset($_GET['action'])) {
    $action = $_GET['action'];

    if ($action == "InsertUsuario") {

        $nome = $mysqli_con->real_escape_string($_POST['nome']);
        $primeiro_nome = $mysqli_con->real_escape_string($_POST['primeiro_nome']);
        $email = $mysqli_con->real_escape_string($_POST['email']);
        $permissao = $mysqli_con->real_escape_string($_POST['permissao']);
        $user = $mysqli_con->real_escape_string($_POST['user']);
        $senha = $mysqli_con->real_escape_string($_POST['senha']);
        $confirma_senha = $mysqli_con->real_escape_string($_POST['confirma_senha']);

        $senha_confirmada = $senha === $confirma_senha ? true : false;

        if ($senha_confirmada) {
            //$senha_hash = password_hash($senha_confirmada, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users(`name_user`,`first_name`,`username_user`,`email_user`,`password_user`,`id_permissao`,`level_user`) 
                    VALUES('$nome','$primeiro_nome','$user','$email','$senha','$permissao','$permissao')";
        }



        $result = $mysqli_con->query($sql);

        if ($result) {
            $id_user = $mysqli_con->insert_id;
            $data_criacao = date('dmy');

            switch ($permissao) {
                case 1:
                    $typeUser = "S";
                    break;
                case 2:
                    $typeUser = "T";
                    break;
                case 3:
                    $typeUser = "G";
                    break;
                case 4:
                    $typeUser = "A";
                    break;
            }

            $idfr_code_user = $typeUser . "0" . $id_user . $data_criacao;

            $sql_update = "UPDATE users SET idfr_code_user='$idfr_code_user' WHERE id_user='$id_user'";
            $result_update = $mysqli_con->query($sql_update);

            if ($result_update) {
                $res['msg'] = "Usuário cadastrado com sucesso! Identificador gerado: " . $idfr_code_user;
            } else {
                $res['error'] = "Erro ao gerar identificador de usuário!";
            }
        } else {
            $res['error'] = true;
            $res['msg'] = "Erro ao cadastrar usuário!";
        }
    }else{
        $res['error'] = true;
        $res['msg'] = "Ação inválida!";
    }
}else{
    $res['error'] = true;
    $res['msg'] = "Requisição inválida!";
}

$mysqli_con->close();
header("Content-type: application/json");
echo json_encode($res);
?>
