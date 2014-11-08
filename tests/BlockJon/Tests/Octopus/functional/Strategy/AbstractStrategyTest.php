<?php

namespace BlockJon\Tests\Octopus\Functional\Strategy;

use Octopus\Strategy\PdoSqlite,
    Daos\Book as BookDao,
    Models\Book;

abstract class AbstractStrategyTest extends \BlockJon\Tests\OctopusTestCase
{
    
    /**
     * Run a series of CRUD methods to ensure they all seem to work.
     * 
     * @param array $write_strategies
     * @param array $read_strategies
     */
    protected function _testGoldenCrudPath(array $write_strategies, array $read_strategies)
    {
        
        // Create a DAO.
        $bookDao = new BookDao($write_strategies, $read_strategies);
        
        // Make a book and set its properties.
        $book = new Book();
        $title = uniqid();
        $author = uniqid();
        $book->setTitle($title);
        $book->setAuthor($author);
        
        // Run the "C" create function.
        $bookDao->create($book);
        
        // Ensure a UUID was defined as the id.
        $this->assertTrue(strlen($book->getId()) == 36);
        
        // Do a "R"ead and ensure everything comes back.
        $returnedBook = $bookDao->read($book->getId());
        $this->assertEquals('Models\Book', get_class($returnedBook));
        $this->assertEquals($book->getId(), $returnedBook->getId());
        $this->assertEquals($title, $returnedBook->getTitle());
        $this->assertEquals($author, $returnedBook->getAuthor());
        
        // Check to see if the test function works.
        $this->assertFalse($this->_strategy->test(uniqid()));
        $this->assertTrue($this->_strategy->test($book->getId()));
        
        // "U"pdate the record.
        $returnedBook->setTitle('foo123');
        $bookDao->update($returnedBook);
        
        // Verify the integrity of the update.
        $returnedBook2 = $bookDao->read($book->getId());
        $this->assertEquals('foo123', $returnedBook2->getTitle());
        
        // "R"emove it
        $bookDao->delete($book);
        
        // Ensure it's gone.
        $this->assertFalse($this->_strategy->test($book->getId()));
        $this->assertNull($bookDao->read($book->getId()));
        
    }
    
    /**
     * Only the create function should work. Other methods should trigger 
     * exceptions indicating those RUD methods are not supported by this
     * strategy.
     * 
     * @param array $write_strategies
     * @param array $read_strategies
     */
    protected function _testGoldenCPath(array $write_strategies, array $read_strategies)
    {
        
        // Create a DAO.
        $bookDao = new BookDao($write_strategies, $read_strategies);
        
        // Make a book and set its properties.
        $book = new Book();
        $title = uniqid();
        $author = uniqid();
        $book->setTitle($title);
        $book->setAuthor($author);
        
        // Run the "C" create function.
        $bookDao->create($book);
        
        // Ensure a UUID was defined as the id.
        $this->assertTrue(strlen($book->getId()) == 36);
        
        $correctReadExceptionDetected = false;
        $correctUpdateExceptionDetected = false;
        $correctDeleteExceptionDetected = false;
        
        // Do a "R"ead and ensure everything comes back.
        try {
            $returnedBook = $bookDao->read($book->getId());
        } catch (\Octopus\Strategy\Exception\InvalidStrategyMethod $e) {
            $correctReadExceptionDetected = true;
        }
        
        // "U"pdate the record.
        try {
            $book->setTitle('foo123');
            $bookDao->update($book);            
        } catch (\Octopus\Strategy\Exception\InvalidStrategyMethod $e) {
            $correctUpdateExceptionDetected = true;
        }
        
        // "R"emove it
        try {
            $bookDao->delete($book);        
        } catch (\Octopus\Strategy\Exception\InvalidStrategyMethod $ex) {
            $correctDeleteExceptionDetected = true;
        }
        
        $this->assertTrue($correctReadExceptionDetected);
        $this->assertTrue($correctUpdateExceptionDetected);
        $this->assertTrue($correctDeleteExceptionDetected);
                
    }
    
}
