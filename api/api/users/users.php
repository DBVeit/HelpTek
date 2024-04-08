<?php

if($api == "users"){
    if($method == "GET"){
        include_once("get_users.php");
    }

    if($method == "POST"){
        include_once ("post_users.php");
    }

    if($method == "POST" && $_POST['_method'] == "PUT"){
        include_once ("put_users.php");
    }

    if($method == "POST" && $_POST['_method'] == "DELETE"){
        include_once ("delete_users.php");
    }
}