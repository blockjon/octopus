<?php

namespace Daos;

use Octopus\DAO\AbstractDAO,
    Models\Book;

class BookDao extends AbstractDAO
{
    
    static $modelClassName = '\Models\Book';
    
    static $_config = array(
        'pdosqlite' => array(
            'columns' => array(
                'id',
                'title',
                'author',
            ),
            'table' => 'sometable'
        ),
        'pdomysql' => array(
            'columns' => array(
                'id',
                'title',
                'author',
            ),
            'table' => 'sometable',
            'dbname' => 'test',
            'username' => '',
            'password' => '',
        ),
        'memcache' => array(
            'host' => 'localhost',
            'port' => '11211',
            'expire' => 0
        ),
    );
    
    /**
     * @return \Models\Book
     */
    static public function getModel()
    {
        return new Book;
    }
    
}
