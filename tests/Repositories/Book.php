<?php

namespace Repositories;

use Octopus\AbstractRepository;

class Book extends AbstractRepository
{
    /**
     * @return \ArrayObject
     */
    public function getAllBooks(\PDO $pdo) 
    {   
        $config = $this->getDao()->getConfig('pdosqlite');
        $table = $config['table'];
        $statement = $pdo->prepare('select id from `'.$table.'`');
        $statement->execute();
        $results = array();
        for($i = 0; $row = $statement->fetch(); $i++) {
            $results[] = $this->getDao()->read($row['id']);
        }
        // This simulates some query with the $this->_handle method
        return new \ArrayObject($results);
    }
    
}
