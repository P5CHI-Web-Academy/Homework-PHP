<?php
/**
 * Copyright (c) 2018.
 *
 * @author        Alexander Sterpu <alexander.sterpu@gmail.com>
 *
 */

namespace WebApp;

/**
 * Class UserSeeder
 * @package WebApp
 */
class UserSeeder
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var \PDO
     */
    private $db;

    /**
     * UserSeeder constructor.
     * @param \PDO $db
     */
    public function __construct(Config $config, \PDO $db)
    {
        $this->config = $config;
        $this->db = $db;
    }

    /**
     * @param bool $force
     * @return bool
     */
    public function seedIfNeed($force = false)
    {
        $dsn = $this->config->DSN;
        $filepath = substr($dsn, strpos($dsn, ":") + 1);

        if (\file_exists($filepath) and filesize($filepath) and !$force) {
            return false;
        }

        return $this->installSchema() && $this->importSeedData();
    }

    /**
     * @return bool
     */
    protected function installSchema()
    {
        try {
            $this->db->exec("DROP TABLE IF EXISTS users");
            $this->db->exec("CREATE TABLE users (
		        id INTEGER PRIMARY KEY, 
		        name TEXT, 
		        email TEXT,
		        password TEXT
		    )");
            return true;
        } catch (\Throwable $exception) {
            echo $exception->getMessage();
            return false;
        }
    }

    /**
     * @return bool
     */
    protected function importSeedData()
    {
        try {
            $stm = $this->db->prepare('INSERT INTO users (name, email, password) VALUES (:name, :email, :password)');

            foreach ($this->getSeeds() as $entry) {
                $stm->execute($entry);
            }
        } catch (\Throwable $exception) {
            echo $exception->getMessage();
            return false;
        }

        return true;
    }

    /**
     * @return array
     */
    protected function getSeeds()
    {
        return [
            ['name' => 'user1', 'email' => 'user@user1.com', 'password' => \password_hash('user1', PASSWORD_BCRYPT)],
            ['name' => 'user2', 'email' => 'user@user2.com', 'password' => \password_hash('user2', PASSWORD_BCRYPT)],
            ['name' => 'user3', 'email' => 'user@user3.com', 'password' => \password_hash('user3', PASSWORD_BCRYPT)],
            ['name' => 'user4', 'email' => 'user@user4.com', 'password' => \password_hash('user4', PASSWORD_BCRYPT)],
            ['name' => 'user5', 'email' => 'user@user5.com', 'password' => \password_hash('user5', PASSWORD_BCRYPT)],
            ['name' => 'user6', 'email' => 'user@user6.com', 'password' => \password_hash('user6', PASSWORD_BCRYPT)],
            ['name' => 'user7', 'email' => 'user@user7.com', 'password' => \password_hash('user7', PASSWORD_BCRYPT)],
            ['name' => 'user8', 'email' => 'user@user8.com', 'password' => \password_hash('user8', PASSWORD_BCRYPT)],
            ['name' => 'user9', 'email' => 'user@user9.com', 'password' => \password_hash('user9', PASSWORD_BCRYPT)],
        ];
    }
}
