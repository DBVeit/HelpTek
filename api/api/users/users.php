<?php

if($api == "users"){
    if($method == "GET"){
        include_once("get_users.php");
    }
    if($method == "POST"){
        include_once ("post_users.php");
    }
}