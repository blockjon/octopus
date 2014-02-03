<?php

namespace BlockJon\Tests\Octopus\DAO;

use Daos\BookDao,
    Models\Book,
    \Octopus\Strategy\JsonJournal,
    \Octopus\Strategy\Apc;

class BookDaoTest extends \BlockJon\Tests\OctopusTestCase
{
    
    public function testCanInstantiateBookDao()
    {
        $bookDao = new BookDao;
        $this->assertEquals('Daos\BookDao', get_class($bookDao));
    }
    
    public function testCanSaveBook()
    {
        $stream = fopen('php://memory', 'w+');
        $jsonJournalWriteStrategy = new JsonJournal($stream);
        $write_strategies = array(
            $jsonJournalWriteStrategy,
        );
        $bookDao = new BookDao($write_strategies);
        $book = new Book();
        $title = uniqid();
        $author = uniqid();
        $book->setTitle($title);
        $book->setAuthor($author);
        $bookDao->create($book);
        $this->assertTrue(true);
    }
    
    public function testCanLoadBook()
    {
        $apcStrategy = new Apc();
        $write_strategies = array(
            $apcStrategy
        );
        $read_strategies = array(
            $apcStrategy,
        );
        $bookDao = new BookDao($write_strategies, $read_strategies);
        $id = uniqid();
        $book = new Book();
        $title = uniqid();
        $author = uniqid();
        $book->setTitle($title);
        $book->setAuthor($author);
        $book->setId($id);
        $bookDao->create($book);
        $returnedBook = $bookDao->read($id);
        $this->assertEquals('Models\Book', get_class($returnedBook));
        $this->assertEquals($id, $returnedBook->getId());
        $this->assertEquals($title, $returnedBook->getTitle());
        $this->assertEquals($author, $returnedBook->getAuthor());
    }
    
}
