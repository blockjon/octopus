<?php

namespace BlockJon\Tests\Octopus\DAO;

use Daos\Book as BookDao;

class BookDaoTest extends \BlockJon\Tests\OctopusTestCase
{
    
    public function testCanInstantiateBookDao()
    {
        $bookDao = new BookDao;
        $this->assertEquals('Daos\Book', get_class($bookDao));
    }
    
}
