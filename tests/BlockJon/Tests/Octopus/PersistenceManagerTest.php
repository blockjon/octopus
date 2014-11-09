<?php

use BlockJon\Tests\OctopusTestCase,
    Octopus\PersistenceManager,
    Daos\Book as BookDao,
    Models\Book;

class PersistenceManagerTest extends OctopusTestCase
{

    public function testCanInstantiatePersistenceManager()
    {
        $this->assertTrue(new PersistenceManager() instanceOf PersistenceManager);
    }

    public function testGetDaoFromModel()
    {
        $persistenceManager = new PersistenceManager();
        $this->assertTrue($persistenceManager->getDao(new Book()) instanceOf BookDao);
    }
}
