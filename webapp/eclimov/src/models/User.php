<?php

namespace Model;

use \Core\Model;

class User extends Model
{

    public function authorize_user($login, $password): int
    {
        $q_user = $this->db_execute(
            '
            SELECT 
              users.id,
              users.password
            FROM users 
            WHERE 
              users.login = :login
        ',
            array(':login' => $login)
        );

        if(\count($q_user) && password_verify($password, $q_user[0]['password'])){
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
              users.name,
              users.login
            FROM users 
            WHERE 
              users.id = :user_id
        ',
            array(':user_id' => $user_id)
        );

        return $q_user_info ? $q_user_info[0] : array(); //If empty, return empty array
    }

    //This method isn't really used in the application yet, but is useful for testing/debugging
    public function create_user($name, $login, $password): int {
        $this->db_execute(
            '
            INSERT INTO users(name, login, password)
            VALUES (:name, :login, :password)
        ',
            array(
                ':name'=>$name,
                ':login'=>$login,
                ':password'=>password_hash($password, PASSWORD_DEFAULT)
            )
        );

        return $this->dbc->lastInsertId();
    }
}