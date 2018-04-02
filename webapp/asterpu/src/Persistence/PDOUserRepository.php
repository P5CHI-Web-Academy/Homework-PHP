<?php
/**
 * Copyright (c) 2018.
 *
 * @author        Alexander Sterpu <alexander.sterpu@gmail.com>
 *
 */

namespace WebApp\Persistence;

use WebApp\Model\User;
use WebApp\Model\UserRepository;

/**
 * Class PDOUserRepository
 * @package WebApp\Persistence
 */
class PDOUserRepository implements UserRepository
{
    /**
     * @var \PDO
     */
    private $db;

    /**
     * PDOUserRepository constructor.
     * @param \PDO $db
     */
    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    /**
     * @param string $name
     * @param $password
     * @return bool|User
     */
    public function getUserByNameAndPassword(string $name, string $password)
    {
        $stm = $this->db->prepare("SELECT id, name, email FROM users WHERE name = :name AND password = :password");
        $stm->execute(['name' => $name, 'password' => \hash('sha256', $password)]);

        if ($userdata = $stm->fetch(\PDO::FETCH_ASSOC)) {
            return new User(...array_values($userdata));
        }

        return false;
    }
}
