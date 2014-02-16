<?php

use Repositories\Book as BookRepository,
    Daos\BookDao,
    Models\Book;

class AbstractRepositoryTest extends \BlockJon\Tests\OctopusTestCase
{
    
    public function testCanInstantiate()
    {
        $bookRepository = new BookRepository(new BookDao());
        $this->assertInstanceOf('Repositories\Book', $bookRepository);
    }
    
    public function testCanIterateOverResultsUsingRespository() 
    {
        $pdoConfig = BookDao::getConfig('pdosqlite');
        $pdoSqliteStratgy = new \Octopus\Strategy\PdoSqlite($pdoConfig);
        $this->createBookTestTableIWithPdoHandle($pdoSqliteStratgy->getPdoHandle(), $pdoConfig);
        
        $apcStrategy = new \Octopus\Strategy\Apc(array());
        
        $w = array(
            $pdoSqliteStratgy,
            $apcStrategy
        );
        
        // Note how I'm only reading from APC! Cool.
        $r = array(
            $apcStrategy
        );
        $bookDao = new \Daos\BookDao($w, $r);
        
        // Save 7 models.
        for($i=0; $i < 7; $i++) {
            $bookDao->create(new Book);
        }
        
        // Load a repository.
        $bookRepository = new BookRepository($bookDao);
        
        // Run a query which returns all of the books.
        $result = $bookRepository->getAllBooks($pdoSqliteStratgy->getPdoHandle());
        
        $this->assertTrue($result instanceOf \Traversable);
        $this->assertTrue($result instanceOf \Countable);
        $this->assertEquals(7, count($result));
        foreach($result as $thisBook) {
            $this->assertEquals(36, strlen($thisBook->getId()));
        }
    }
    
}
