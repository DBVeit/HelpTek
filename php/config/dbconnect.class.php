<?php

class DBConnect
{
    public static function connect()
    {
        $host = "localhost";
        $user = "root";
        $pass = "180317";
        $database = "helptek";

        return new mysqli($host,$user,$pass,$database) or die();
    }
}