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
        $book = new Book;
        $persistentFields = $persistenceManager->getFieldNamesHavingAnnotationsStartingWith($book, "Octopus");
        $this->assertTrue(is_array($persistentFields));
        $this->assertEquals(1, count($persistentFields));
    }

    public function testCanSnapshot()
    {
        $book = new Book;
        $book->setTitle("Awesome PHP");
        $book->setAuthor("Jonathan Block");

        $persistenceManager = new PersistenceManager();
        $exported = $persistenceManager->export($book);

        $this->assertTrue(is_array($exported));
        $this->assertTrue(count($exported) == 3);
        $this->assertTrue(array_key_exists("id", $exported));
        $this->assertTrue(array_key_exists("title", $exported));
        $this->assertTrue(array_key_exists("author", $exported));
        $this->assertNull($exported["id"]);
        $this->assertEquals("Awesome PHP", $exported["title"]);
        $this->assertEquals("Jonathan Block", $exported["author"]);
    }
}
