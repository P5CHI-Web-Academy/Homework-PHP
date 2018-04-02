<?php
namespace App\Core\Database;

class Connection
{
    public static function make()
    {
        try {

            return new \PDO("sqlite:sbogos.sqlite");

        } catch (\PDOException $e) {

            die($e->getMessage());
        }
    }
}
