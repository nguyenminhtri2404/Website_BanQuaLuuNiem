<?php
class Database
{
    public static function getConnect()
    {
        // $host = "sql207.byethost24.com";
        // $db = "b24_36611326_triss_souvernir";
        // $username = "b24_36611326";
        // $password = "Tri123456@";
        $host = "localhost";
        $db = "triss_souvenir";
        $username = "triss_admin";
        $password = "TcG6fa-e0KXIl2An";
        //$password = "bamnklzk23dXT0EF";

        $dsn = "mysql:host=$host;dbname=$db;charset=UTF8";

        try {
            $pdo = new PDO($dsn, $username, $password);
        
            if ($pdo) {
                return $pdo;
            }
        } catch (PDOException $ex) {
            echo $ex->getMessage();
            exit;
        }
    }

}