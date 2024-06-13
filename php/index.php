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
 

require 'api/routes.php';

// Obter a URI e o método da requisição
$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Despachar a requisição para o roteador
$router->dispatch($requestUri, $requestMethod);


?>