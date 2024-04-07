<?php

if ($acao == "" && $param == ""){
    echo json_encode(["ERRO" => "Caminho não encontrado"]);
}

if($acao == "listar" && $param == "") {
    $db = DB::connect();
    $result = $db->prepare("SELECT * FROM users");
    $result->execute();
    $obj = $result->fetchAll(PDO::FETCH_ASSOC);
    //var_dump($obj);

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
    //var_dump($obj);

    if ($obj) {
        echo json_encode(["dados_usuarios" => $obj]);
    } else {
        echo json_encode(["dados_usuarios" => "Dados inexistentes"]);
    }
}