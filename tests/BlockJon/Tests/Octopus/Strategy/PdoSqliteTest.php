<?php

namespace BlockJon\Tests\Octopus\Strategy;

use Octopus\Strategy\PdoSqlite,
    Daos\Book as BookDao;

class PdoSqliteTest extends AbstractStrategyTest
{
    
    protected $_dbh;
    
    public function setUp() 
    {
        $config = BookDao::getConfig('pdosqlite');
        $this->_strategy = new PdoSqlite(
            $config
        );
        $fieldDefinitions = '';
        foreach($config['columns'] as $field) {
            $fieldDefinitions .= "$field varchar(255), ";
        }
        $fieldDefinitions = rtrim($fieldDefinitions);
        $fieldDefinitions = substr($fieldDefinitions, 0, strlen($fieldDefinitions)-1);
        $sql = "CREATE TABLE " . $config['table'] . " ($fieldDefinitions);";
        $handle = $this->_strategy->getPdoHandle()->exec($sql);
    }
    
    public function tearDown() 
    {
        $this->_dbh = null;
    }
    
    public function testCanInstantiatePdoStrategy()
    {
        $instance = new PdoSqlite(array());
        $this->assertEquals('Octopus\Strategy\PdoSqlite', get_class($instance));
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
