<?php

namespace BlockJon\Tests\Octopus\Strategy;

use Octopus\Strategy\JsonJournal;

class JsonJournalTest extends \BlockJon\Tests\OctopusTestCase
{
    
    public function testCanInstantiateJsonJournal()
    {
        $stream = fopen('php://memory', 'w+');
        $instance = new JsonJournal(array('streamorurl' => $stream));
        $this->assertEquals('Octopus\Strategy\JsonJournal', get_class($instance));
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
    
}
