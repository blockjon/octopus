<?php

namespace BlockJon\Tests\Octopus\Functional\Strategy;

use Octopus\Strategy\Memcache,
    Daos\Book as BookDao;

class MemcacheTest extends AbstractStrategyTest
{
    
    public function setUp() 
    {
        $config = BookDao::getConfig('memcache');
        $this->_strategy = new Memcache($config);
    }
    
    public function testCanInstantiateMemcacheStrategy()
    {
        $this->assertEquals('Octopus\Strategy\Memcache', get_class($this->_strategy));
    }

    public function testCrud()
    {
        if (!extension_loaded('memcache')) {
            $this->markTestSkipped('This test only works with memcache cache enabled.');
        }
        $write_strategies = array(
            $this->_strategy
        );
        $read_strategies = array(
            $this->_strategy,
        );
        $this->_testGoldenCrudPath($write_strategies, $read_strategies);
    }

}
