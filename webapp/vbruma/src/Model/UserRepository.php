<?php

namespace App\Model;


class UserRepository extends AbstractModel
{

    /**
     * Retrieve a user by username/password
     *
     * @param string $username
     * @param string $password
     *
     * @return array|null
     */
    public function get(string $username, string $password):? array
    {
        try {
            $statement = $this->db->prepare(
                'SELECT username, password FROM users WHERE username = :username'
            );

            $statement->execute(
                [
                    ':username' => $username,
                ]
            );
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);

            if (count($result)
                && password_verify(
                    $password,
                    $result[0]['password']
                )
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

