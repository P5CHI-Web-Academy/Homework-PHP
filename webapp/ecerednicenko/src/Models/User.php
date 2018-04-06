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
    }

    /**
     * @param $login
     * @param $password
     * @return bool
     */
    public function loginValidator($login, $password)
    {
        $sql = "SELECT * FROM `homework3` 
                  WHERE `login`=:login";

        $query = $this->dbc->prepare($sql);
        $query->execute([':login' => $login]);
        $userData = $query->fetch(\PDO::FETCH_ASSOC);

        return password_verify($password, $userData['password']);
    }
}