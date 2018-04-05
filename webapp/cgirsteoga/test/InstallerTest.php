<?php

namespace Webapp;

use PHPUnit\Framework\TestCase;
use Webapp\Service\UserDAO;

class InstallerTest extends TestCase
{

    public function testCreteCorrectStructureStructure()
    {
        $db = new \PDO('sqlite::memory:');
        Installer::createStructure($db);

        $fields = \array_keys(UserDAO::TABLE_STRUCTURE);
        $fields = \join(',', $fields);

        $sql = \sprintf('SELECT %s FROM %s ', $fields, UserDAO::TABLE_NAME);
        $stm = $db->prepare($sql);

        $this->assertTrue($stm instanceof \PDOStatement);
    }
}
