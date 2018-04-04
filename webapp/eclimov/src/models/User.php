<?php

namespace Model;

use \Core\Model;

class User extends Model
{

    public function authorize_user($login, $password): int
    {
        $q_user = $this->db_execute(
            '
            SELECT users.id
            FROM users 
            WHERE 
              users.login = :login
              AND users.password = :password
        ',
            array(':login' => $login, ':password' => $password)
        );
        if (\count($q_user)) {
            return $q_user[0]['id'];
        }

        return 0;
    }

    public function get_user_info($user_id): array
    {
        $q_user_info = $this->db_execute(
            '
            SELECT 
              users.id,
              users.name
            FROM users 
            WHERE 
              users.id = :user_id
        ',
            array(':user_id' => $user_id)
        );

        return $q_user_info[0];
    }
}