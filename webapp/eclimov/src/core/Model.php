<?php

namespace Core;

class Model
{
    private $dbc;

    public function __construct()
    {
        $this->dbc = new \PDO('sqlite:'.$_SERVER['DOCUMENT_ROOT'].'/../src/'.'database.db');
        $this->dbc->exec(
            '
            CREATE TABLE `users` (
                `id`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
                `name`	TEXT NOT NULL,
                `login`	TEXT NOT NULL UNIQUE,
                `password`	TEXT NOT NULL
            )'
        );
    }

    public function db_execute(string $query, array $params): array
    {
        $prepare_sql = $this->dbc->prepare($query);
        $prepare_sql->execute($params);

        return $prepare_sql->fetchAll();
    }
}