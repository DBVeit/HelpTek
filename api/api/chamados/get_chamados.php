<?php

if ($acao == "" && $param == ""){
    echo json_encode(["ERRO" => "Caminho não encontrado"]);
    exit;
}

if($acao == "listar" && $param == "") {
    $db = DB::connect();
    $result = $db->prepare("SELECT * FROM chamados");
    $result->execute();
    $obj = $result->fetchAll(PDO::FETCH_ASSOC);

    if ($obj) {
        echo json_encode(["dados_chamados" => $obj]);
    } else {
        echo json_encode(["dados_chamados" => "Dados inexistentes"]);
    }
}

if($acao == "listar" && $param != "") {
    $db = DB::connect();
    $result = $db->prepare("SELECT * FROM chamados WHERE id_chamado={$param}");
    $result->execute();
    $obj = $result->fetchObject();

    if ($obj) {
        echo json_encode(["dados_chamados" => $obj]);
    } else {
        echo json_encode(["dados_chamados" => "Dados inexistentes"]);
    }
}