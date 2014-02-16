<?php

namespace Octopus;   

abstract class AbstractRepository
{
    
    private $_dao;
    
    public function __construct(\Octopus\DAO\AbstractDAO $dao) 
    {
        $this->_dao = $dao;
    }
    
    protected function getDao()
    {
        return $this->_dao;
    }
    
}
