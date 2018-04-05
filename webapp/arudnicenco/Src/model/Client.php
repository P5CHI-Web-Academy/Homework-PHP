<?php

namespace App\model;

class Client
{
    private $database;

    function __construct()
    {
        $this->database = new \PDO('sqlite:'. DB_PATH);
    }

    function get(string $username, string $password):? array
    {
        try {
            $statement = $this->database->prepare('SELECT name, password FROM users WHERE name = :username');

            $statement->execute([':username' => $username,]);
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);

            if (count($result) && password_verify($password, $result[0]['password'])) {
                return ['username' => $username,];
            }
            return null;
        } catch (\Exception $e) {

        }
        return null;
    }

}
