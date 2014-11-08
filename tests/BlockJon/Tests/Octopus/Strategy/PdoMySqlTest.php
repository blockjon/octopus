<?php

namespace BlockJon\Tests\Octopus\Strategy;

use \Octopus\Strategy\PdoMySql;
use \Daos\Book as BookDao;

class PdoMySqlTest extends AbstractStrategyTest
{
    protected $_dbh;
    protected $_strategy;
    
//    public function setUp()
//    {
//        parent::setUp();
//        $config = BookDao::getConfig('pdomysql');
//        $this->_strategy = new PdoMySql(
//            $config
//        );
//        $error_code = $this->_strategy->getPdoHandle()->exec("drop table `" . $config['table'] . "` if exists;");
//        $fieldDefinitions = '';
//        foreach ($config['columns'] as $field) {
//            $fieldDefinitions .= "`$field` varchar(255), ";
//        }
//        $fieldDefinitions = rtrim($fieldDefinitions);
//        $fieldDefinitions = substr($fieldDefinitions, 0, strlen($fieldDefinitions)-1);
//        $sql = "CREATE TABLE `" . $config['table'] . "` ($fieldDefinitions);";
//        $error_code = $this->_strategy->getPdoHandle()->exec($sql);
//    }
//
//    public function tearDown()
//    {
//        $config = BookDao::getConfig('pdomysql');
//        $sql = "DROP TABLE `" . $config['table'] . "`;";
//        $error_code = $this->_strategy->getPdoHandle()->exec($sql);
//        parent::tearDown();
//    }
//
//    public function testCanInstantiatePdoStrategy()
//    {
//        $instance = new PdoMySql(array());
//        $this->assertEquals('Octopus\Strategy\PdoMySql', get_class($instance));
//    }
//
//    public function testCrud()
//    {
//        $write_strategies = array(
//            $this->_strategy
//        );
//        $read_strategies = array(
//            $this->_strategy,
//        );
//        $this->_testGoldenCrudPath($write_strategies, $read_strategies);
//    }

    public function testSanity()
    {
        $config = BookDao::getConfig('pdomysql');
        $strategy = new PdoMySql(
            $config
        );
        $this->assertInstanceOf('Octopus\Strategy\PdoMySql', $strategy);
    }
}
