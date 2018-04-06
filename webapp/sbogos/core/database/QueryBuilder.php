<?php
namespace App\Core\Database;

class QueryBuilder
{
    protected $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function login($user)
    {
        $statement = $this->pdo->prepare("select * from users where name = :user");

        $statement->execute(['user' => $user]);
        
        return $statement->fetch(\PDO::FETCH_OBJ);
    }

    public function register($user, $password)
    {
        $statement = $this->pdo->prepare("insert into users (name, password) values (:name, :password)");

        $statement->execute(['name' => $user, 'password' => $password]);
    }

    public function get($user)
    {
        $statement = $this->pdo->prepare("select * from users where name = :user limit 1");

        $statement->execute(['user' => $user]);

        $user = $statement->fetch(\PDO::FETCH_OBJ);

        return $user;
    }
}
