<?php

if ($acao == "" && $param == ""){
    echo json_encode(["ERRO" => "Caminho não encontrado"]);
    exit;
}

if($acao == "listar" && $param == "") {
    $db = DB::connect();
    $result = $db->prepare("SELECT * FROM users");
    $result->execute();
    $obj = $result->fetchAll(PDO::FETCH_ASSOC);

    if ($obj) {
        echo json_encode(["dados_usuarios" => $obj]);
    } else {
        echo json_encode(["dados_usuarios" => "Dados inexistentes"]);
    }
}

if($acao == "listar" && $param != "") {
    $db = DB::connect();
    $result = $db->prepare("SELECT * FROM users WHERE id_user={$param}");
    $result->execute();
    $obj = $result->fetchObject();

    if ($obj) {
        echo json_encode(["dados_usuarios" => $obj]);
    } else {
        echo json_encode(["dados_usuarios" => "Dados inexistentes"]);
    }
}