<?php

namespace Daos;

use Octopus\DAO\AbstractDAO;

class BookDao extends AbstractDAO
{
    
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
    
}
