<?php

if ($acao == "" && $param == ""){
    echo json_encode(["ERRO" => "Caminho não encontrado!"]);
    exit;
}

if ($acao == "delete" && $param == ""){
    echo json_encode(["ERRO" => "Necessário informar um usuário!"]);
    exit;
}

if ($acao == "delete" && $param != ""){
    $db = DB::connect();
    $result = $db->prepare("DELETE FROM users WHERE id_user={$param}");
    $exec = $result->execute();

    if ($exec) {
        echo json_encode(["dados_usuarios" => "Usuário eliminado com sucesso!"]);
    } else {
        echo json_encode(["dados_usuarios" => "Ocorreu um erro inesperado! Tente novamente mais tarde."]);
    }
}