<?php

header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');

date_default_timezone_set("America/Sao_Paulo");

$GLOBALS['secretJWT'] = 'htekapi**';

include_once "api/classes/autoload.class.php";
new Autoload();

$rota = new Rotas();

//Login c/ JWT
$rota->add('POST','/usuarios/login','Usuarios::login',false);
//http://localhost/projeto/helptek/php/api/usuarios/login HTTP/1.1


?>