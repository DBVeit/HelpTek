<?php

if ($acao == "" && $param == ""){
    echo json_encode(["ERRO" => "Caminho não encontrado"]);
    exit;
}

if ($acao == "login" && $param == ""){
    Usuarios::login($_POST['username_user'], $_POST['password_user']);
    exit;
}