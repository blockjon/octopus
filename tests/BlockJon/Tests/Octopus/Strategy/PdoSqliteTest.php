<?php

namespace BlockJon\Tests\Octopus\Strategy;

use Octopus\Strategy\PdoSqlite,
    Daos\BookDao,
    Models\Book;

class PdoSqliteTest extends \BlockJon\Tests\OctopusTestCase
{
    
    public function testCanInstantiatePdoStrategy()
    {
        $instance = new PdoSqlite(array());
        $this->assertEquals('Octopus\Strategy\PdoSqlite', get_class($instance));
    }

    public function testWriteAndReadFromPdoSqlite()
    {
        $strategy = new PdoSqlite();
        $write_strategies = array(
            $strategy
        );
        $read_strategies = array(
            $strategy,
        );
        $bookDao = new BookDao($write_strategies, $read_strategies);
        $book = new Book();
        $title = uniqid();
        $author = uniqid();
        $book->setTitle($title);
        $book->setAuthor($author);
        $bookDao->create($book);
        $this->assertTrue(strlen($book->getId()) == 36);
        $returnedBook = $bookDao->read($book->getId());
//        $this->assertEquals('Models\Book', get_class($returnedBook));
//        $this->assertEquals($id, $returnedBook->getId());
//        $this->assertEquals($title, $returnedBook->getTitle());
//        $this->assertEquals($author, $returnedBook->getAuthor());
    }

}
