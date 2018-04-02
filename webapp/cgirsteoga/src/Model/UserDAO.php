<?php

namespace Webapp\Model;


class UserDAO
{
    const ID = 'id';
    const USER_NAME = 'user_name';
    const PASSWORD = 'password';
    const FULL_NAME = 'fullName';

    const TABLE_NAME = 'users';
    const TABLE_STRUCTURE = [
        self::ID => 'INTEGER PRIMARY KEY AUTOINCREMENT',
        self::USER_NAME => 'VARCHAR (100)',
        self::PASSWORD => 'VARCHAR (100)',
        self::FULL_NAME => 'VARCHAR (100)',
    ];

    const BASE_SELECT_SQL = 'SELECT id, user_name, password, fullName FROM users ';

    /** @var \PDO */
    protected $db;

    /**
     * UserDAO constructor.
     * @param \PDO $dbConnection
     */
    public function __construct(\PDO $dbConnection)
    {
        $this->db = $dbConnection;
    }

    /**
     * @param int $id
     * @return null|User
     */
    public function getById(int $id)
    {
        return $this->find([static::ID => $id]);
    }

    /**
     * @param string $userName
     * @return null|User
     */
    public function getByUserName(string $userName)
    {
        return $this->find([static::USER_NAME => $userName]);
    }

    /**
     * @param array $criteria
     * @return null|User
     */
    public function find(array $criteria)
    {
        $params = [];
        foreach ($criteria as $field => $value) {
            $params[] = \sprintf('%s=:%s', $field, $field);
        }
        $condition = \join(' AND ', $params);

        $sql = \sprintf('%s WHERE %s ', static::BASE_SELECT_SQL, $condition);
        $stm = $this->db->prepare($sql);
        $stm->execute($criteria);

        $result = $stm->fetch(\PDO::FETCH_ASSOC);
        if ($result) {
            return $this->loadUser($result);
        } else {
            return null;
        }
    }

    /**
     * @param array $userData
     * @return User
     */
    protected function loadUser(array $userData)
    {
        return (new User())
            ->setId($userData[static::ID])
            ->setFullName($userData[static::FULL_NAME])
            ->setUserName($userData[static::USER_NAME])
            ->setPassword($userData[static::PASSWORD]);
    }
}
