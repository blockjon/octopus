<?php

namespace Octopus\Strategy;

abstract class AbstractStrategy
{

    protected $_config;
    
    public function __construct(array $config = array())
    {
        $this->_config = $config;
    }
    
    public function create(array $data_array) 
    {
        throw new Exception(__METHOD__ . ' not implemented for this strategy.');
    }
    
    public function test($id) 
    {
        throw new Exception(__METHOD__ . ' not implemented for this strategy.');
    }
    
    public function read($id) 
    {
         throw new Exception(__METHOD__ . ' not implemented for this strategy.');
    }

    public function update(array $data_array) 
    {
         throw new Exception(__METHOD__ . ' not implemented for this strategy.');
    }
    
    public function delete($id) 
    {
         throw new Exception(__METHOD__ . ' not implemented for this strategy.');
    }
    
}
