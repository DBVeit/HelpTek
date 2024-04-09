<?php

if ($acao == "" && $param == ""){
    echo json_encode(["ERRO" => "Caminho não encontrado!"]);
    exit;
}

if ($acao == "update" && $param == ""){
    echo json_encode(["ERRO" => "Necessário informar um chamado!"]);
    exit;
}

if ($acao == "update" && $param != ""){
    array_shift($_POST);

    $sql = "UPDATE chamados SET";

    $contador = 1;
    foreach (array_keys($_POST) as $indice){
        if (count($_POST) > $contador){
            $sql .= " {$indice} = '{$_POST[$indice]}', ";
        }else {
            $sql .= " {$indice} = '{$_POST[$indice]}' ";
        }
        $contador++;
    }

    $sql .= "WHERE id_chamado = {$param}";

    $db = DB::connect();
    $result = $db->prepare($sql);
    $exec = $result->execute();

    if ($exec) {
        echo json_encode(["dados_chamados" => "Chamado atualizado com sucesso!"]);
    } else {
        echo json_encode(["dados_chamados" => "Ocorreu um erro inesperado! Tente novamente mais tarde."]);
    }
}