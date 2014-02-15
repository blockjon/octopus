<?php

namespace BlockJon\Tests\Octopus\Strategy;

use Octopus\Strategy\Memcache,
    Daos\BookDao,
    Models\Book;

class MemcacheTest extends AbstractStrategyTest
{
    
    public function setUp() 
    {
        $this->_strategy = new Memcache(array());
    }
    
    public function testCanInstantiatePdoStrategy()
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