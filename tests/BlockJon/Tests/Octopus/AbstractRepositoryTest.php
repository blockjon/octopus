<?php

use Repositories\Book as BookRepository,
    Daos\Book as BookDao,
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
        $strategyA = new \Octopus\Strategy\PdoSqlite($pdoConfig);
        $strategyB = new \Octopus\Strategy\PdoSqlite($pdoConfig);
        $this->createBookTestTableIWithPdoHandle($strategyA->getPdoHandle(), $pdoConfig);
        $this->createBookTestTableIWithPdoHandle($strategyB->getPdoHandle(), $pdoConfig);
        
        $w = array(
            $strategyA,
            $strategyB
        );
        
        // Note how I'm only reading from strategyB.
        $r = array(
            $strategyB
        );
        $bookDao = new BookDao($w, $r);
        
        // Save 7 models.
        for($i=0; $i < 7; $i++) {
            $bookDao->create(new Book);
        }
        
        // Load a repository.
        $bookRepository = new BookRepository($bookDao);
        
        // Run a query which returns all of the books.
        $result = $bookRepository->getAllBooks($strategyA->getPdoHandle());
        
        $this->assertTrue($result instanceOf \Traversable);
        $this->assertTrue($result instanceOf \Countable);
        $this->assertEquals(7, count($result));
        foreach($result as $thisBook) {
            $this->assertEquals(36, strlen($thisBook->getId()));
        }
    }
    
}
