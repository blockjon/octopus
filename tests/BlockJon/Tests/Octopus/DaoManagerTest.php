<?php

use BlockJon\Tests\OctopusTestCase,
    Octopus\DaoManager,
    Daos\Book as BookDao,
    Models\Book;

class DaoManagerTest extends OctopusTestCase
{
    
    public function testCanInstantiateDaoManager()
    {
        $this->assertTrue(new DaoManager() instanceOf DaoManager);
    }
    
    public function testGetDaoFromModel()
    {
        $daoManager = new DaoManager();
        $this->assertTrue($daoManager->getDao(new Book()) instanceOf BookDao);
    }
    
}
