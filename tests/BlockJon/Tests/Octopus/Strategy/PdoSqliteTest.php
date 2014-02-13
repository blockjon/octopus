<?php

namespace BlockJon\Tests\Octopus\Strategy;

use Octopus\Strategy\PdoSqlite,
    Daos\BookDao,
    Models\Book;

class PdoSqliteTest extends \BlockJon\Tests\OctopusTestCase
{
    
    protected $_dbh;
    
    public function setUp() 
    {
        $config = BookDao::getConfig('pdosqlite');
        $this->_strategy = new PdoSqlite(
            $config
        );
        $fieldDefinitions = '';
        foreach($config['columns'] as $field) {
            $fieldDefinitions .= "$field varchar(255), ";
        }
        $fieldDefinitions = rtrim($fieldDefinitions);
        $fieldDefinitions = substr($fieldDefinitions, 0, strlen($fieldDefinitions)-1);
        $sql = "CREATE TABLE " . $config['table'] . " ($fieldDefinitions);";
        $handle = $this->_strategy->getPdoHandle()->exec($sql);
    }
    
    public function tearDown() 
    {
        $this->_dbh = null;
    }
    
    public function testCanInstantiatePdoStrategy()
    {
        $instance = new PdoSqlite(array());
        $this->assertEquals('Octopus\Strategy\PdoSqlite', get_class($instance));
    }

    public function testWriteAndReadFromPdoSqlite()
    {
        $write_strategies = array(
            $this->_strategy
        );
        $read_strategies = array(
            $this->_strategy,
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
        
        $this->assertFalse($this->_strategy->test(uniqid()));
        $this->assertTrue($this->_strategy->test($book->getId()));
        
        // Update it.
        $returnedBook->setTitle('foo123');
        $bookDao->update($returnedBook);
        
        // Make sure its updated.
        $returnedBook2 = $bookDao->read($book->getId());
        $this->assertEquals('foo123', $returnedBook2->getTitle());
        
        // Remove it.
        $bookDao->delete($book);
        
        // Ensure it's gone.
        $this->assertFalse($this->_strategy->test($book->getId()));
        $this->assertNull($bookDao->read($book->getId()));
        
    }

}
