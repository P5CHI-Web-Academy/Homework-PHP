<?php

namespace App\Model;


class AbstractModel
{
    protected $db;

    public function __construct($dbAdapter = null)
    {
        $this->db = $dbAdapter ?? new \PDO('sqlite:' . DB_PATH);
    }
}

