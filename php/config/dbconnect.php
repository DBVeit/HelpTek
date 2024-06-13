<?php

$host = "localhost";
$user = "root";
$pass = "";
$database = "helptek";

$mysqli_con = new mysqli($host,$user,$pass,$database) or die($mysqli_con->error);

if (isset($mysqli_con)){
    $con_info = "Conexao bem-sucedida";
}else{
    $con_info = "Erro ao conectar!";
}

?>