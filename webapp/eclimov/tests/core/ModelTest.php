<?php

use PHPUnit\Framework\TestCase;

final class ModelTest extends TestCase{
    public static function setUpBeforeClass()
    {
        if(!defined('DB_DSN')){
            define('DB_DSN', 'sqlite::memory:');
        }
    }

    public function testModelCreatedSuccessfully(){
        $this->assertInstanceOf(\Core\Model::class, new \Core\Model());
    }

    public function testQueryExecutionReturnsArray(){
        $model = new Core\Model();
        $actual = $model->db_execute('
                SELECT 
                    11 AS column1, 
                    12 AS column2 
                WHERE 1 = :param                
                ',
            array(':param'=>1)
        );
        $this->assertInternalType('array', $actual);
    }
}