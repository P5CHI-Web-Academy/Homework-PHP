<?php

namespace App\Models;

/**
 * Class User
 * @package Models
 */
class User
{
    public $dbc;

    public function __construct()
    {
        $this->dbc = new \PDO('sqlite:ecerednicenko.db');

        try {
            $this->dbc->exec(
                'CREATE TABLE IF NOT EXISTS `homework3` (
              `id` INTEGER PRIMARY KEY AUTOINCREMENT,
              `login` varchar(24) NOT NULL,
              `password` varchar(24) NOT NULL
              )'
            );

        } catch (\PDOException $e) {
            die($e->getMessage());
        }
    }


    /**
     * @param $login
     * @param $password
     * @return bool
     */
    public function loginValidator($login, $password)
    {
        $sql = "SELECT count(*) FROM `homework3` 
                  WHERE `login`=:login AND `password`=:password";

        $query = $this->dbc->prepare($sql);
        $query->execute([':login' => $login, ':password' => $password]);

        $count = $query->fetch(\PDO::FETCH_NUM)[0];


        return !!$count;
    }
}