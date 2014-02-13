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
    );
    
}
