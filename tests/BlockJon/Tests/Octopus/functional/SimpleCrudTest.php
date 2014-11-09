<?php

namespace BlockJon\Tests\Octopus\Functional;

use Octopus\PersistenceManager;
use Models\Book;

class SimpleCrudTest extends \PHPUnit_Framework_TestCase
{
    public function testCanCreateBook()
    {
        $book = new Book();
        $this->assertInstanceOf('Models\Book', $book);
    }

    public function testCanCreatePersistenceManager()
    {
        $pm = new PersistenceManager();
        $this->assertInstanceOf('Octopus\PersistenceManager', $pm);
    }

    public function testCanFindOctopusAnnotations()
    {
        $persistenceManager = new PersistenceManager();
        $book = new Book();
        $tags = $persistenceManager->getTags($book);
        print_r($tags);
        $this->assertTrue(false);
    }
}
