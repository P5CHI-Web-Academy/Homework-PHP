<?php

namespace App\Model;


class User
{

    private $db;

    public function __construct()
    {
        $this->db = new \PDO('sqlite:' . DB_PATH);
    }

    /**
     * Retrieve a user by username/password
     *
     * @param string $username
     * @param string $password
     *
     * @return array|null
     */
    public function getUser(string $username, string $password):? array
    {
        try {
            $statement = $this->db->prepare(
                'SELECT name, password FROM users WHERE name = :username'
            );

            $statement->execute(
                [
                    ':username' => $username,
                ]
            );
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);

            if (count($result)
                && $password == $result[0]['password']
            ) {
                return [
                    'username' => $username,
                ];
            }

            return null;
        } catch (\Exception $e) {
            // log error
        }

        return null;
    }
}

