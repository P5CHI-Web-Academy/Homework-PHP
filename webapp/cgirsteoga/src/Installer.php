<?php

namespace Webapp;

use Webapp\Model\PasswordHelper;
use Webapp\Model\UserDAO;

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

        if (static::installSqliteDatabase()) {
            static::importDemoData();
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
     * @return bool
     */
    protected static function installSqliteDatabase()
    {
        try {
            $pathInfo = \pathinfo(static::$sqlLiteDbFile);
            $path = $pathInfo['dirname'];

            if (!is_dir($path)) {
                echo 'Creating directory for database'.PHP_EOL;
                mkdir($path, 0777, true);
            }
            echo 'Creating database'.PHP_EOL;
            $db = new \PDO(static::$dsn);

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
            echo 'Creating database structure'.PHP_EOL;
            $db->query($sql);

            return true;

        } catch (\Throwable $exception) {
            echo $exception->getMessage();

            return false;
        }
    }

    protected static function importDemoData()
    {
        try {
            $db = new \PDO(static::$dsn);

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

            echo 'Import demo data'.PHP_EOL;
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
            ['user_name' => 'user1', 'password' => 'user1', 'fullName' => 'User 1'],
            ['user_name' => 'user2', 'password' => 'user2', 'fullName' => 'User 2'],
            ['user_name' => 'user3', 'password' => 'user3', 'fullName' => 'User 3'],
            ['user_name' => 'user4', 'password' => 'user4', 'fullName' => 'User 4'],
            ['user_name' => 'user5', 'password' => 'user5', 'fullName' => 'User 5'],
            ['user_name' => 'user6', 'password' => 'user6', 'fullName' => 'User 6'],
            ['user_name' => 'user7', 'password' => 'user7', 'fullName' => 'User 7'],
            ['user_name' => 'user8', 'password' => 'user8', 'fullName' => 'User 8'],
            ['user_name' => 'user9', 'password' => 'user9', 'fullName' => 'User 9'],
        ];
    }
}
