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
     * @var \PDO
     */
    private $db;

    /**
     * UserSeeder constructor.
     * @param \PDO $db
     */
    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    /**
     * @param bool $force
     * @return bool
     */
    public function installIfNeeded($force = false)
    {
        $filepath = substr(DSN, strpos(DSN, ":") + 1);

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
            ['name' => 'user1', 'email' => 'user@user1.com', 'password' => \hash('sha256', 'user1')],
            ['name' => 'user2', 'email' => 'user@user2.com', 'password' => \hash('sha256', 'user2')],
            ['name' => 'user3', 'email' => 'user@user3.com', 'password' => \hash('sha256', 'user3')],
            ['name' => 'user4', 'email' => 'user@user4.com', 'password' => \hash('sha256', 'user4')],
            ['name' => 'user5', 'email' => 'user@user5.com', 'password' => \hash('sha256', 'user5')],
            ['name' => 'user6', 'email' => 'user@user6.com', 'password' => \hash('sha256', 'user6')],
            ['name' => 'user7', 'email' => 'user@user7.com', 'password' => \hash('sha256', 'user7')],
            ['name' => 'user8', 'email' => 'user@user8.com', 'password' => \hash('sha256', 'user8')],
            ['name' => 'user9', 'email' => 'user@user9.com', 'password' => \hash('sha256', 'user9')],
        ];
    }
}
