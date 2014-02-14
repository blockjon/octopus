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
    
}
