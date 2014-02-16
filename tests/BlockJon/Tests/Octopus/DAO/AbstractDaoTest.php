<?php

namespace BlockJon\Tests\Octopus\DAO;

use Daos\BookDao,
    Models\Book,
    Octopus\Strategy\PdoSqlite,
    Octopus\Strategy\Memcache;

class AbstractDaoTest extends \BlockJon\Tests\OctopusTestCase
{
    
    public function testAllWriteStrategiesUsedDuringMultiStrategyWrite()
    {
        $pdoSqliteStratgy = new PdoSqlite(
            BookDao::getConfig('pdosqlite')
        );
        $memcacheStratgy = new Memcache(
            BookDao::getConfig('memcache')
        );
        $w = array(
            $pdoSqliteStratgy,
            $memcacheStratgy
        );
        $r = array(
            $pdoSqliteStratgy,
        );
        $bookDaoMock = $this->getMock('\Daos\BookDao', array('applyWriteStrategy'), array($w, $r));
        $bookDaoMock->expects($this->exactly(2))
                    ->method('applyWriteStrategy');
        $bookDaoMock->create(new Book);
    }
    
    /**
     * @expectedException \RuntimeException
     */
    public function testExceptionThrownToCallingContextFromStrategyException()
    {
        $pdoConfig = BookDao::getConfig('pdosqlite');
        $pdoSqliteStrategyMock = $this->getMock('\Octopus\Strategy\PdoSqlite', array('create'), array($pdoConfig));
        $pdoSqliteStrategyMock  ->expects($this->any())
                                ->method('create')
                                ->will($this->throwException(new \RuntimeException));
        $this->createBookTestTableIWithPdoHandle($pdoSqliteStrategyMock->getPdoHandle(), $pdoConfig);
        
        $w = array(
            $pdoSqliteStrategyMock,
        );
        $r = array(
            $pdoSqliteStrategyMock,
        );
        $bookDao = new BookDao($w, $r);
        $bookDao->create(new Book);
    }
    
    public function testExceptionThrownAgainstPrimaryWriteStrategyTriggersBackupStrategy()
    {
        $pdoConfig = BookDao::getConfig('pdosqlite');
        
        // This one triggers an exception.
        $pdoSqliteStrategyMockBroken = $this->getMock('\Octopus\Strategy\PdoSqlite', array('create'), array($pdoConfig));
        $pdoSqliteStrategyMockBroken  ->expects($this->once())
                                ->method('create')
                                ->will($this->throwException(new \RuntimeException));

        // This one should be used because there was an exception thrown.
        $pdoSqliteStrategyMockBackup = $this->getMock('\Octopus\Strategy\PdoSqlite', array('create'), array($pdoConfig));
        $pdoSqliteStrategyMockBackup  ->expects($this->once())
                                ->method('create');

        $this->createBookTestTableIWithPdoHandle($pdoSqliteStrategyMockBroken->getPdoHandle(), $pdoConfig);
        
        $w = array(
            $pdoSqliteStrategyMockBroken,
        );
        $r = array(
            $pdoSqliteStrategyMockBroken,
        );
        $bookDao = new BookDao($w, $r);
        $bookDao->setPrimaryWriteBackupStrategy($pdoSqliteStrategyMockBackup);
        $bookDao->create(new Book);
    }
    
    public function testBackupStrategyNotTriggeredUnderNormalCircumstances()
    {
        $pdoConfig = BookDao::getConfig('pdosqlite');
        
        // This one triggers an exception.
        $pdoSqliteStrategyMock = $this->getMock('\Octopus\Strategy\PdoSqlite', array('create'), array($pdoConfig));
        $pdoSqliteStrategyMock  ->expects($this->once())
                                ->method('create');

        // This one should be used because there was an exception thrown.
        $pdoSqliteStrategyMockBackup = $this->getMock('\Octopus\Strategy\PdoSqlite', array('create'), array($pdoConfig));
        $pdoSqliteStrategyMockBackup  ->expects($this->never())
                                      ->method('create');

        $this->createBookTestTableIWithPdoHandle($pdoSqliteStrategyMock->getPdoHandle(), $pdoConfig);
        
        $w = array(
            $pdoSqliteStrategyMock,
        );
        $r = array(
            $pdoSqliteStrategyMock,
        );
        $bookDao = new BookDao($w, $r);
        $bookDao->setPrimaryWriteBackupStrategy($pdoSqliteStrategyMockBackup);
        $bookDao->create(new Book);
    }
    
}
