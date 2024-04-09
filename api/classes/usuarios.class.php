<?php

class Usuarios
{
    public static function login($username_user, $password_user)
    {
        $username_user = addslashes(htmlspecialchars($username_user)) ?? '';
        $password_user = addslashes(htmlspecialchars($password_user)) ?? '';
        $secretJWT = $GLOBALS['secretJWT'];

        $db = DB::connect();
        $result = $db->prepare("SELECT * FROM users WHERE username_user = '{$username_user}'");
        $exec = $result->execute();
        $obj = $result->fetchObject();
        $rows = $result->rowCount();

        if ($rows > 0){
            $idDB = $obj->id_user;
            $name_userDB = $obj->name_user;
            $passwordDB = $obj->password_user;
            $validUsername = true;
            $validPassword = password_verify($password_user,$passwordDB);
        }else{
            $validUsername = false;
            $validPassword = false;
        }

        if ($validUsername and $validPassword){
            $expire_in = time() + 60000;
            $token_user     = JWT::encode([
                'id'         => $idDB,
                'name'       => $name_userDB,
                'expires_in' => $expire_in,
            ], $GLOBALS['secretJWT']);

            $db->query("UPDATE users SET token_user = '$token_user' WHERE id_user = $idDB");
            echo json_encode(['token' => $token_user, 'data' => JWT::decode($token_user,$secretJWT)]);
        }else{
            if (!$validUsername || !$validPassword){
                echo json_encode(['ERRO', 'Usuário e/ou senha inválida']);
            }
        }

    }

    public static function verificar()
    {
        $headers = apache_request_headers();
        if(isset($headers['Authorization'])){
            $token = str_replace("Bearer ", "", $headers['Authorization']);
        }else{
            echo json_encode(['ERRO' => 'Usuário não logado ou token inválido']);
            exit;
        }
        $db = DB::connect();
        $result = $db->prepare("SELECT * FROM users WHERE token_user = '{$token}'");
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
                $db->query("UPDATE users SET token_user = '' WHERE id_user = $idDB");
                return false;
            }
        else:
            return false;
        endif;
    }
}