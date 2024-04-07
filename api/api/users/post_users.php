<?php

if ($acao == "" && $param == ""){
    echo json_encode(["ERRO" => "Caminho não encontrado"]);
}

if ($acao == "adicionar" && $param == ""){

    $sql = "INSERT INTO users (";

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

    //var_dump($sql);

    $db = DB::connect();
    $result = $db->prepare($sql);
    $exec = $result->execute();

    //var_dump($exec);

    if ($exec) {
        echo json_encode(["dados_usuarios" => "Usuário criado com sucesso!"]);
    } else {
        echo json_encode(["dados_usuarios" => "Ocorreu um erro inesperado ao inserir dados! Tente novamente mais tarde."]);
    }
}