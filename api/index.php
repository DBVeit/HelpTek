<?php
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');

date_default_timezone_set("America/Sao_Paulo");

$GLOBALS['secretJWT'] = 'htekapi**';

include_once "classes/autoload.class.php";
new Autoload();

$rota = new Rotas();

//Login c/ JWT
$rota->add('POST','/usuarios/login','Usuarios::login',false);

//Funcoes chamados
$rota->add('GET','/chamados/listar','Chamados::listarTodos',true);
$rota->add('GET','/chamados/listar/[PARAM]','Chamados::listarUnico',true);
$rota->add('POST','/chamados/adicionar','Chamados::adicionar',true);
$rota->add('POST','/chamados/atualizar/[PARAM]','Chamados::atualizar',true);

//Funcoes usuarios


$rota->ir($_GET['path']);