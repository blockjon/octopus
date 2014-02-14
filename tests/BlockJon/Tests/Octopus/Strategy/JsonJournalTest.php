<?php

namespace BlockJon\Tests\Octopus\Strategy;

use Octopus\Strategy\JsonJournal;

class JsonJournalTest extends AbstractStrategyTest
{
    
    public function setUp() 
    {
        $this->_strategy = new JsonJournal(array('streamorurl' => fopen('php://memory', 'w+')));
    }
    
    public function testCanInstantiateJsonJournal()
    {
        $this->assertEquals('Octopus\Strategy\JsonJournal', get_class($this->_strategy));
    }
    
    public function testCanWriteToJsonJournal()
    {
        $stream = fopen('php://memory', 'w+');
        $instance = new JsonJournal(array('streamorurl' => $stream));
        $data = array(
            'color' => 'blue',
            'year' => 1984,
        );
        $instance->create($data);
    }
    
    public function testC()
    {
        $write_strategies = array(
            $this->_strategy
        );
        $read_strategies = array(
            $this->_strategy,
        );
        $this->_testGoldenCPath($write_strategies, $read_strategies);
    }
    
}
