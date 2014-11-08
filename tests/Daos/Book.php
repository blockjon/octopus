<?php

namespace Daos;

use Octopus\DAO\AbstractDAO;

class Book extends AbstractDAO
{
    static $_config = array(
        'pdosqlite' => array(
            'columns' => array(
                'id',
                'dateCreated',
                'dateLastUpdated',
                'title',
                'author',
            ),
            'table' => 'sometable'
        ),
        'pdomysql' => array(
            'columns' => array(
                'id',
                'dateCreated',
                'dateLastUpdated',
                'title',
                'author',
            ),
            'table' => 'sometable',
        ),
    );
}
