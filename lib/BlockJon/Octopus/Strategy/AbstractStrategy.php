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
         $this->throwException(__FUNCTION__);
    }
    
    public function test($id) 
    {
         $this->throwException(__FUNCTION__);
    }
    
    public function read($id) 
    {
         $this->throwException(__FUNCTION__);
    }

    public function update(array $data_array) 
    {
         $this->throwException(__FUNCTION__);
    }
    
    public function delete($id) 
    {
         $this->throwException(__FUNCTION__);
    }
    
    private function throwException($method) 
    {
         throw new Exception\InvalidStrategyMethod('Method ' . $method . '() not implemented for strategy ' . get_class($this) . '.');        
    }
    
}
