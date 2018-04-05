<?php

namespace Webapp;

use Webapp\Helper\PasswordHelper;
use Webapp\Service\UserDAO;

/**
 * Class Installer
 * @package Webapp
 */
class Installer
{
    protected static $sqlLiteDbFile;
    protected static $dsn;

    public static function install()
    {
        $connection = \explode(':', WebApp::DSN);

        if (!isset($connection[1])) {
            echo \sprintf("Invalid connection string: %s\n", WebApp::DSN);

            return;
        }

        static::$sqlLiteDbFile = $connection[1];
        static::$dsn = WebApp::DSN;

        if (static::isInstalled()) {
            echo 'Database already installed'.PHP_EOL;

            return;
        }

        if ($db = static::installSqliteDatabase()) {
            echo 'Import demo data'.PHP_EOL;
            static::importDemoData($db);
        }
    }

    /**
     * @return bool
     */
    protected static function isInstalled()
    {
        if (\file_exists(static::$sqlLiteDbFile)) {
            return true;
        }

        return false;
    }

    /**
     * Install database
     * @return \PDO|null
     */
    protected static function installSqliteDatabase()
    {
        try {
            $pathInfo = \pathinfo(static::$sqlLiteDbFile);
            $path = $pathInfo['dirname'];

            if (!\is_dir($path)) {
                echo 'Creating directory for database'.PHP_EOL;
                \mkdir($path, 0777, true);
            }
            echo 'Creating database'.PHP_EOL;
            $db = new \PDO(static::$dsn);

            echo 'Creating database structure'.PHP_EOL;
            self::createStructure($db);

            return $db;

        } catch (\Throwable $exception) {
            echo $exception->getMessage();

            return null;
        }
    }

    public static function createStructure(\PDO $db)
    {
        $fields = UserDAO::TABLE_STRUCTURE;
        \array_walk(
            $fields,
            function (&$data, $field) {
                $data = \sprintf('%s %s', $field, $data);
            }
        );

        $sql = \sprintf(
            'CREATE TABLE %s (  %s );',
            UserDAO::TABLE_NAME,
            \join(',', $fields)
        );
        $db->query($sql);
    }

    /**
     * @param \PDO $db
     */
    public static function importDemoData(\PDO $db)
    {
        try {
            $tableStructure = UserDAO::TABLE_STRUCTURE;
            unset($tableStructure[UserDAO::ID]);

            $fieldList = \array_keys($tableStructure);

            $fields = \join(',', $fieldList);
            $placeHolders = \join(
                ',',
                \array_map(
                    function ($field) {
                        return \sprintf(':%s', $field);
                    },
                    $fieldList
                )
            );

            $sql = \sprintf('INSERT INTO %s (%s) VALUES (%s);', UserDAO::TABLE_NAME, $fields, $placeHolders);

            $stm = $db->prepare($sql);

            $passwordHelper = new PasswordHelper();

            foreach (self::getDemoData() as $entry) {
                $entry['password'] = $passwordHelper->hash($entry['password']);
                $stm->execute($entry);
            }

        } catch (\Throwable $exception) {
            echo $exception->getMessage();
        }
    }

    protected static function getDemoData()
    {
        return [
            ['user_name' => 'user1', 'password' => 'user1', 'full_name' => 'User 1'],
            ['user_name' => 'user2', 'password' => 'user2', 'full_name' => 'User 2'],
            ['user_name' => 'user3', 'password' => 'user3', 'full_name' => 'User 3'],
            ['user_name' => 'user4', 'password' => 'user4', 'full_name' => 'User 4'],
            ['user_name' => 'user5', 'password' => 'user5', 'full_name' => 'User 5'],
            ['user_name' => 'user6', 'password' => 'user6', 'full_name' => 'User 6'],
            ['user_name' => 'user7', 'password' => 'user7', 'full_name' => 'User 7'],
            ['user_name' => 'user8', 'password' => 'user8', 'full_name' => 'User 8'],
            ['user_name' => 'user9', 'password' => 'user9', 'full_name' => 'User 9'],
            ['user_name' => 'user45545', 'password' => 'user45545', 'full_name' => 'User without GitHub account'],
        ];
    }
}
