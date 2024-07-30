<?php
include "../config/dbconnect.php";

if (isset($_SERVER['HTTP_ORIGIN'])){
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 1000');
}

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS'){
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
    }

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
        header("Access-Control-Allow-Headers: Accept, Content-Type, Content-Length, Accept-Encoding, x-CSRF-Token, Authorization");
    }
    exit(0);
}

$res = array('error' => false, 'msg' => '', 'code' => '');

if (isset($_GET['action'])) {
    $action = $_GET['action'];

    if ($action == "login") {
        if (isset($_POST['username']) && isset($_POST['password'])) {
            $username = $mysqli_con->real_escape_string($_POST['username']);
            $password = $mysqli_con->real_escape_string($_POST['password']);

            $sql = "SELECT * FROM users WHERE username_user='$username' AND status_user=1";//Buscar pelo usuario informado
            $result = $mysqli_con->query($sql);//Executa a query e armazena em $result
            $obj = $result->fetch_object();//Retorna o registro de $result como um objeto

            //Se o numero de linhas retornados em $result for maior que 0...
            if ($result->num_rows > 0) {
                $idDB = $obj->id_user;//Obtem o atributo id_user
                $name_userDB = $obj->name_user;//Obtem o atributo name_user
                $passwordDB = $obj->password_user;//Obtem o atributo password_user
                $validUsername = true;
                $validPassword = password_verify($password,$passwordDB);
                /*Compara $password recebido do POST com o valor do atributo salvo no banco de dados
                * armazena o comparativo em $validPassword
                */

                session_start();
                //Se $validPassword for verdadeiro...
                if ($validPassword){
                    $_SESSION['user_id'] = $idDB;

                    $expire_in = time() + 60000;
                    $token = JWT::encode([
                        'id'         => $idDB,
                        'name'       => $name_userDB,
                        'expires_in' => $expire_in,
                    ], $GLOBALS['secretJWT']);
                    $res['token'] = $token;
                    $res['msg'] = "Logado com sucesso!";
                    $res['code'] = 200;
                } else { //Do contrario...
                    $res['error'] = true;
                    $res['msg'] = "E-mail ou senha inválidos!";
                    $res['code'] = 401;
                }
            } else{
                $res['error'] = true;
                $res['msg'] = "E-mail ou senha inválidos!";
                $res['code'] = 401;
            }
        } else {
            $res['error'] = true;
            $res['msg'] = "Parametros inválidos!";
            $res['code'] = 400;
        }
    }
}

$mysqli_con -> close();
header("Content-type: application/json");
echo json_encode($res);
die();

?>