<?php

if($api == "chamados"){

    if($method == "GET"){
        include_once("get_chamados.php");
    }

    if (Usuarios::verificar()) {
        if ($method == "POST") {
            include_once("post_chamados.php");
        }

        if ($method == "POST" && $_POST['_method'] == "PUT") {
            include_once("put_chamados.php");
        }

        if ($method == "POST" && $_POST['_method'] == "DELETE") {
            include_once("delete_chamados.php");
        }
    }
}