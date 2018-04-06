<?php
namespace App\Core\Database;

class Connection
{
    public static function make()
    {
        try {

            return new \PDO('sqlite:../app/database.db');

        } catch (\PDOException $e) {

            die($e->getMessage());
        }
    }
}
