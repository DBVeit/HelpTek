<?php

class Chamados
{
    public function listarTodos()
    {
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

    public function listarUnico($param)
    {
        var_dump("Parametro: ".$param);
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

    public function adicionar()
    {
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

    public function atualizar($param)
    {
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

    public function deletar($param)
    {
        $db = DB::connect();
        $result = $db->prepare("DELETE FROM chamados WHERE id_chamado={$param}");
        $exec = $result->execute();

        if ($exec) {
            echo json_encode(["dados_usuarios" => "Chamado cancelado com sucesso!"]);
        } else {
            echo json_encode(["dados_usuarios" => "Ocorreu um erro inesperado! Tente novamente mais tarde."]);
        }
    }
}