<?php

class DBConnect
{
    public static function connect()
    {
        $host = "localhost";
        $user = "root";
        $pass = "";
        $database = "helptek";

        
        return new mysqli($host,$user,$pass,$database) or die();
    }
}