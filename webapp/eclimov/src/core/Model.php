<?php

namespace Core;

class Model
{
    protected $dbc;

    public function __construct()
    {
        $this->dbc = $this->get_dbc();
        $this->db_init();
    }

    public function get_dbc(){
        return new \PDO(DB_DSN);
    }

    public function db_init(){
        $this->dbc->exec(
            '
            CREATE TABLE IF NOT EXISTS `users` (
                `id`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
                `name`	TEXT NOT NULL,
                `login`	TEXT NOT NULL UNIQUE,
                `password`	TEXT NOT NULL
            )'
        );
        return true;
    }

    public function db_execute(string $query, array $params): array
    {
        $prepare_sql = $this->dbc->prepare($query);
        $prepare_sql->execute($params);
        return $prepare_sql->fetchAll(\PDO::FETCH_ASSOC); // We need an associative array
    }
}