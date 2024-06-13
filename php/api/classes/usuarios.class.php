<?php
class Usuarios
{
    public function login(){
        if($_POST){
            if(!$_POST['username'] || !$_POST['password']){
                echo json_encode(['ERRO' => 'Necessário preencher todos os campos!']);
                exit();
            } else{
                $username = mysqli_real_escape_string($_POST['username']);
                $password = mysqli_real_escape_string($_POST['password']);

                $mysqli = DBConnect::connect();
                $result = $mysqli->prepare("SELECT * FROM users WHERE username_user=? AND status_user=1");
                $result->bind_param('s', $username);
                $exec = $result->execute();
                $obj = $result->fetch_object();
                $rows = $result->rowCunt();

                if ($rows > 0){
                    $idDB = $obj->id_user;//Obtem o atributo id_user
                    $name_userDB = $obj->name_user;//Obtem o atributo name_user
                    $passwordDB = $obj->password_user;//Obtem o atributo password_user
                    $validUsername = true;
                    $validPassword = password_verify($password,$passwordDB);
                } else{
                    $validUsername = false;
                    $validPassword = false;
                }

                if ($validUsername && $validPassword){
                    $expire_in = time() + 60000;
                    $token = JWT::encode([
                        'id' => $idDB,
                        'expires_in' => $expire_in,
                    ], $GLOBALS['secretJWT']);

                    $mysqli->query("UPDATE users SET token=$token WHERE id_user=$idDB");
                    echo json_encode(['token' => $token, 'data' => JWT::decode($token,$secretJWT)]);
                } else{
                    if (!$validPassword){
                        echo json_encode(['ERROR', 'Usuário e/ou senha inválida']);
                    }
                }
            }
        }else {
            echo json_encode(['ERRO' => 'Usuário e/ou senha inválida']);
            exit;
        }
    }

    public function verificar()
    {
        $headers = apache_request_headers();
        if (isset($headers['authorization'])){
            $token = str_replace("Bearer ", "", $headers['authorization']);
        } elseif (isset($headers['Authorization'])){
            $token = str_replace("Bearer ", "", $headers['Authorization']);
        } else{
            echo json_encode(['ERRO' => 'Usuário não logado ou token inválido']);
            exit;
        }

        $mysqli = DBConnect::connect();
        $result = $mysqli->prepare("SELECT * FROM users WHERE token_user = '{$token}'");
        $exec = $result->execute();
        $obj = $result->fetchObject();
        $rows = $result->rowCount();
        $secretJWT = $GLOBALS['secretJWT'];

        if ($rows > 0):
            $idDB = $obj->id_user;
            $tokenDB = $obj->token_user;
            $decodedJWT = JWT::decode($tokenDB, $secretJWT);
            if($decodedJWT->expires_in > time()){
                return true;
            }else{
                $mysqli->query("UPDATE users SET token_user = '' WHERE id_user = $idDB");
                return false;
            }
        else:
            return false;
        endif;
    }
}
?>