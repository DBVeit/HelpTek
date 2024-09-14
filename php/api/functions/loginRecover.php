<?php
include "../../config/dbconnect.php";
include "../../config/httpaccess.php";

$res = array('error' => false, 'msg' => '');

if (isset($_GET['action'])) {
    $action = $_GET['action'];

    if ($action == "recover") {
        if (isset($_POST['emailUser'])) {
            $emailUser = $mysqli_con->real_escape_string($_POST['emailUser']);
            $new_password_email = $mysqli_con->real_escape_string($_POST['new_password_email']);
            $new_password = $mysqli_con->real_escape_string($_POST['new_password']);

            $sql = "SELECT * FROM users WHERE email_user='$emailUser' AND status_user=1";//Buscar pelo email informado
            $result = $mysqli_con->query($sql);//Executa a query e armazena em $result
            $obj = $result->fetch_object();//Retorna o registro de $result como um objeto

            //Se o numero de linhas retornados em $result for maior que 0...
            if ($result->num_rows > 0) {
                $idDB = $obj->id_user;//Obtem o atributo id_user
                $validEmail = true;

                $sql = "UPDATE users SET password_user='$new_password',troca_senha=1 WHERE id_user='$idDB'";
                $result = $mysqli_con->query($sql);//Executa a query e armazena em $result

                //sendMail();

                // Envia o e-mail
                $to = $emailUser;
                $subject = "Recuperação de Senha";
                $message = "Sua nova senha é: " . $new_password_email;
                $headers = "From: contato.helptek@gmail.com";

                if (mail($to, $subject, $message, $headers)) {
                    $res['msg'] = "E-Mail para recuperação de acesso enviado, verifique sua caixa de entrada.";
                } else {
                    $res['error'] = true;
                    $res['msg'] = "Erro ao enviar o e-mail.";
                }

                //$res['msg'] = "E-Mail para recuperação de acesso enviado, verifique sua caixa de entrada. Nova senha: ".$newpass;
            }else{
                $res['error'] = true;
                $res['msg'] = "E-Mail não encontrado!";
            }
        } else{
            $res['error'] = true;
            $res['msg'] = "E-Mail não encontrado!";
        }
    } else {
        $res['error'] = true;
        $res['msg'] = "Parametros inválidos!";
    }
}

$mysqli_con -> close();
header("Content-type: application/json");
echo json_encode($res);
die();

?>