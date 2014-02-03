<?php

namespace Octopus\Model;

abstract class AbstractModel
{
    
    protected $_id;
    
    /**
     * Convert object into an associative array.
     * 
     * @return array
     */
    abstract public function toArray();
    
    /**
     * Given an array of data from the data store, re-hydrates the model.
     */
    abstract public function hydrate(array $data);
    
    public function getId()
    {
        return $this->_id;
    }

    public function setId($id)
    {
        $this->_id = $id;
        return $this;
    }

}
