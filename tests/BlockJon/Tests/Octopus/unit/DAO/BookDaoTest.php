<?php

namespace BlockJon\Tests\Octopus\Unit\DAO;

use Daos\Book as BookDao;
use \BlockJon\Tests\OctopusTestCase;

class BookDaoTest extends OctopusTestCase
{
    public function testCanInstantiateBookDao()
    {
        $bookDao = new BookDao;
        $this->assertEquals('Daos\Book', get_class($bookDao));
    }
}
