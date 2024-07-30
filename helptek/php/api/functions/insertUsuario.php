<?php
include "../../config/dbconnect.php";
include "../../config/httpaccess.php";

$res = array('error' => false, 'msg' => '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_GET['action'])) {
        $action = $_GET['action'];

        if ($action == "InsertUsuario") {

            // Utilize $_POST para acessar os dados enviados via FormData
            $nome = $mysqli_con->real_escape_string($_POST['nome']);
            $primeiro_nome = $mysqli_con->real_escape_string($_POST['primeiro_nome']);
            $email = $mysqli_con->real_escape_string($_POST['email']);
            $permissao = $mysqli_con->real_escape_string($_POST['permissao']);
            $user = $mysqli_con->real_escape_string($_POST['usuario']);
            $senha = $mysqli_con->real_escape_string($_POST['senha']);
            $confirma_senha = $mysqli_con->real_escape_string($_POST['confirma_senha']);

            if ($senha !== $confirma_senha) {
                $res['error'] = true;
                $res['msg'] = "As senhas não coincidem.";
                echo json_encode($res);
                exit;
            } else {
                $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
            }

            

            // Verifica se o email ou nome de usuário já existem
            $check_sql = "SELECT email_user, username_user FROM users WHERE email_user = '$email' OR username_user = '$user'";
            $check_result = $mysqli_con->query($check_sql);

            if ($check_result && $check_result->num_rows > 0) {
                $check_row = $check_result->fetch_assoc();
                if ($check_row['email_user'] == $email) {
                    $res['error'] = true;
                    $res['msg'] = "Erro: Email já cadastrado.";
                    echo json_encode($res);
                    exit;
                } elseif ($check_row['username_user'] == $user) {
                    $res['error'] = true;
                    $res['msg'] = "Erro: Nome de usuário já existe.";
                    echo json_encode($res);
                    exit;
                }
            }

            $sql = "INSERT INTO users(`name_user`,`first_name`,`username_user`,`email_user`,`password_user`,`id_permissao`,`level_user`) 
                    VALUES('$nome','$primeiro_nome','$user','$email','$senha_hash','$permissao','$permissao')";

            if ($mysqli_con->query($sql)) {
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
                if ($mysqli_con->query($sql_update)) {
                    $res['msg'] = "Usuário cadastrado com sucesso! Identificador gerado: " . $idfr_code_user;
                } else {
                    $res['error'] = true;
                    $res['msg'] = "Erro ao gerar identificador de usuário!";
                }
            } else {
                if ($mysqli_con->errno == 1062) {
                    // Erro de chave duplicada (duplicidade de email ou nome de usuário)
                    if (strpos($mysqli_con->error, 'email_user_UNIQUE') !== false) {
                        $res['error'] = true;
                        $res['msg'] = "Erro: Email já cadastrado.";
                    } elseif (strpos($mysqli_con->error, 'username_user_UNIQUE') !== false) {
                        $res['error'] = true;
                        $res['msg'] = "Erro: Nome de usuário já existe.";
                    } else {
                        $res['error'] = true;
                        $res['msg'] = "Erro ao cadastrar usuário.";
                    }
                } else {
                    $res['error'] = true;
                    $res['msg'] = "Erro ao cadastrar usuário.";
                }
            }
        } else {
            $res['error'] = true;
            $res['msg'] = "Ação inválida!";
        }
    } else {
        $res['error'] = true;
        $res['msg'] = "Requisição inválida!";
    }

    $mysqli_con->close();
    header("Content-type: application/json");
    echo json_encode($res);
}
?>
