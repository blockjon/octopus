<?php

namespace BlockJon\Tests\Octopus\Strategy;

use Octopus\Strategy\Apc;

class ApcTest extends AbstractStrategyTest
{
    
    public function setUp() 
    {
        $this->_strategy = new Apc(array());
    }
    
    public function testCanInstantiateApcStrategy()
    {
        $this->assertEquals('Octopus\Strategy\Apc', get_class($this->_strategy));
    }

    public function testCrud()
    {
        if (!extension_loaded('apc')) {
            $this->markTestSkipped('Apc is not installed.');            
        }
        $option = ini_get('apc.enable_cli');
        if(!(bool)$option) {
            $this->markTestSkipped('This test only works with apc cache enabled on the cli.');
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
