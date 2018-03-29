<?php

namespace App\Model;


class AbstractModel
{
    protected $db;

    public function __construct()
    {
        $this->db = new \PDO('sqlite:' . DB_PATH);
    }
}

