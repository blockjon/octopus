<?php

namespace BlockJon\Tests\Octopus\Strategy;

use Octopus\Strategy\PdoMySql,
    Daos\BookDao,
    Models\Book;

class PdoMySqlTest extends AbstractStrategyTest
{
    
    protected $_dbh;
    
    public function setUp() 
    {
        $config = BookDao::getConfig('pdomysql');
        $this->_strategy = new PdoMySql(
            $config
        );
        $error_code = $this->_strategy->getPdoHandle()->exec("drop table `" . $config['table'] . "` if exists;");
        $fieldDefinitions = '';
        foreach($config['columns'] as $field) {
            $fieldDefinitions .= "`$field` varchar(255), ";
        }
        $fieldDefinitions = rtrim($fieldDefinitions);
        $fieldDefinitions = substr($fieldDefinitions, 0, strlen($fieldDefinitions)-1);
        $sql = "CREATE TABLE `" . $config['table'] . "` ($fieldDefinitions);";
        $error_code = $this->_strategy->getPdoHandle()->exec($sql);
    }
    
    public function tearDown() 
    {
        $config = BookDao::getConfig('pdomysql');
        $sql = "DROP TABLE `" . $config['table'] . "`;";
        $error_code = $this->_strategy->getPdoHandle()->exec($sql);
    }
    
    public function testCanInstantiatePdoStrategy()
    {
        $instance = new PdoMySql(array());
        $this->assertEquals('Octopus\Strategy\PdoMySql', get_class($instance));
    }

    public function testCrud()
    {
        $write_strategies = array(
            $this->_strategy
        );
        $read_strategies = array(
            $this->_strategy,
        );
        $this->_testGoldenCrudPath($write_strategies, $read_strategies);
    }

}
