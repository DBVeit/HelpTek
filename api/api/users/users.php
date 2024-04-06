<?php

if($api == "users"){
    if($method == "GET"){

        //var_dump($api);
        //var_dump($method);
        //var_dump($acao);
        if ($acao == "" && $param == ""){
            echo json_encode(["ERRO" => "Caminho não encontrado"]);
        }
        if($acao == "listar" && $param == "") {
            $db = DB::connect();
            $rs = $db->prepare("SELECT * FROM users");
            $rs->execute();
            $obj = $rs->fetchAll(PDO::FETCH_ASSOC);
            //var_dump($obj);

            if ($obj) {
                echo json_encode(["dados_usuarios" => $obj]);
            } else {
                echo json_encode(["dados_usuarios" => "Dados inexistentes"]);
            }
        }

        if($acao == "listar" && $param != "") {
            $db = DB::connect();
            $rs = $db->prepare("SELECT * FROM users WHERE id_user={$param}");
            $rs->execute();
            $obj = $rs->fetchObject();
            //var_dump($obj);

            if ($obj) {
                echo json_encode(["dados_usuarios" => $obj]);
            } else {
                echo json_encode(["dados_usuarios" => "Dados inexistentes"]);
            }
        }
    }
}

//var_dump("teste users");