<?php

namespace BlockJon\Tests\Octopus\Strategy;

use Octopus\Strategy\PdoMySql,
    Daos\Book as BookDao;

class PdoMySqlTest extends AbstractStrategyTest
{
    protected $_dbh;
    protected $_strategy;
    
    public function setUp()
    {
        parent::setUp();
        if (!extension_loaded('pdo')) {
            $this->markTestSkipped("pdo extension not installed. skipping test.");
            return;
        }
        $config = BookDao::getConfig('pdomysql');
        try {
            $this->_strategy = new PdoMySql(
                $config
            );
        } catch (\Exception $e) {
            if ($e->getCode() == 2002) {
                // Can't connect to mysql.
                $this->markTestSkipped($e->getMessage());
                return;
            }
        }
        $error_code = $this->_strategy->getPdoHandle()->exec("drop table `" . $config['table'] . "` if exists;");
        $fieldDefinitions = '';
        foreach ($config['columns'] as $field) {
            $fieldDefinitions .= "`$field` varchar(255), ";
        }
        $fieldDefinitions = rtrim($fieldDefinitions);
        $fieldDefinitions = substr($fieldDefinitions, 0, strlen($fieldDefinitions)-1);
        $sql = "CREATE TABLE `" . $config['table'] . "` ($fieldDefinitions);";
        $error_code = $this->_strategy->getPdoHandle()->exec($sql);
    }

    public function tearDown()
    {
        if (!extension_loaded('pdo') || $this->getStatus() == \PHPUnit_Runner_BaseTestRunner::STATUS_SKIPPED) {
            return;
        }
        $config = BookDao::getConfig('pdomysql');
        $sql = "DROP TABLE `" . $config['table'] . "`;";
        $error_code = $this->_strategy->getPdoHandle()->exec($sql);
        parent::tearDown();
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
