<?php

namespace BlockJon\Tests\Octopus\Strategy;

use Octopus\Strategy\Apc,
    Daos\BookDao,
    Models\Book;

class ApcTest extends \BlockJon\Tests\OctopusTestCase
{
    
    public function testCanInstantiatePdoStrategy()
    {
        $instance = new Apc(array());
        $this->assertEquals('Octopus\Strategy\Apc', get_class($instance));
    }

    public function testWriteAndReadFromApc()
    {
        $strategy = new Apc(array());
        $write_strategies = array(
            $strategy,
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
        
        // Ensure the book is returned.
        $returnedBook = $bookDao->read($book->getId());
        $this->assertEquals('Models\Book', get_class($returnedBook));
        $this->assertEquals($book->getId(), $returnedBook->getId());
        $this->assertEquals($title, $returnedBook->getTitle());
        $this->assertEquals($author, $returnedBook->getAuthor());
        
        $this->assertFalse($strategy->test(uniqid()));
        $this->assertTrue($strategy->test($book->getId()));
        
        // Update it.
        $returnedBook->setTitle('foo123');
        $bookDao->update($returnedBook);
        
        // Make sure its updated.
        $returnedBook2 = $bookDao->read($book->getId());
        $this->assertEquals('foo123', $returnedBook2->getTitle());
        
        // Remove it.
        $bookDao->delete($book);
        
        // Ensure it's gone.
        $this->assertFalse($strategy->test($book->getId()));
        $this->assertNull($bookDao->read($book->getId()));
        
    }

}
