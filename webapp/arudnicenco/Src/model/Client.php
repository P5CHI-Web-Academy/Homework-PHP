<?php

namespace App\model;

class Client
{
    private $database;

    function __construct()
    {
        $this->database = new \PDO("sqlite:".DB_PATH);
    }

    function getClient($client_name, $client_password)
    {
        try {
            $dbprepare = $this->database->prepare(
                'SELECT client_name, client_password FROM users WHERE user_name = :client_name'
            );
            $dbprepare->execute([':client_name' => $client_name, ':client_password' => $client_password]);

            $result = $dbprepare->fetchAll(\PDO::FETCH_ASSOC);

            if (count($result) == true && $client_password == $result[0]["client_password"]) {
                return ['client_name' => $client_name];
            }

        } catch (\Exception $database_e) {
            print "Something happened wrong : ".$database_e;
        }
        return null;
    }
}