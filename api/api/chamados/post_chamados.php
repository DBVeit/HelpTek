<?php

if ($acao == "" && $param == ""){
    echo json_encode(["ERRO" => "Caminho não encontrado"]);
    exit;
}

if ($acao == "adicionar" && $param == ""){
    $sql = "INSERT INTO chamados (";

    $contador = 1;
    foreach (array_keys($_POST) as $indice){
        if (count($_POST) > $contador){
            $sql .= "{$indice},";
        }else {
            $sql .= "{$indice}";
        }
        $contador++;
    }

    $sql .= ") VALUES (";

    $contador = 1;
    foreach (array_values($_POST) as $valor){
        if (count($_POST) > $contador){
            $sql .= "'{$valor}',";
        }else {
            $sql .= "'{$valor}'";
        }
        $contador++;
    }

    $sql .= ")";

    $db = DB::connect();
    $result = $db->prepare($sql);
    $exec = $result->execute();

    if ($exec) {
        echo json_encode(["dados_chamados" => "Chamado registrado com sucesso!"]);
    } else {
        echo json_encode(["dados_chamados" => "Ocorreu um erro inesperado! Tente novamente mais tarde."]);
    }
}