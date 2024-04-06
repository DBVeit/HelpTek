<?php

class DB{
    public static function connect()
    {
        $host = 'localhost';
        $user = 'root';
        $pass = '180317';
        $base = 'helptek';

        return new PDO("mysql:host={$host};dbname={$base};charset=UTF8;", $user,$pass);
    }
}