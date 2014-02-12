<?php

namespace Octopus\DAO;

use Octopus\Model\AbstractModel,
    Rhumsaa\Uuid\Uuid;    

abstract class AbstractDAO
{
    
    private $_write_strategies = array();
    private $_read_strategies = array();
    
    /**
     * @param array $write_strategies
     * @param array $read_strategies
     */
    public function __construct(array $write_strategies = array(), array $read_strategies = array()) 
    {
        $this->_write_strategies = $write_strategies;
        $this->_read_strategies = $read_strategies;
    }
    
    /**
     * @param type $model
     * @return
     */
    public function create(AbstractModel $model)
    {
        
        if ($model->getId() === null) {
            $uuid = (string)Uuid::uuid5(Uuid::NAMESPACE_DNS, uniqid('',true));
            $model->setId($uuid);
        }
        
        // Translate the model into an associative array.
        $data_array = $model->toArray();
        
        // Loop over each of the write strategies executing the create method.
        foreach($this->_write_strategies as $strategy) {
            $strategy->create($data_array);
        }
        
        return $model;
        
    }
    
    /**
     * 
     * @param type $id
     * @return mixed
     */
    public function read($id) 
    {
        $result = null;
        $model = null;        
        // Use the first read strategy that finds the object.
        foreach($this->_read_strategies as $strategy) {
            if($result = $strategy->read($id)) {
                break;
            }
        }
        if($result) {
            $model = new \Models\Book(); // todo: pick class based on precise dao.
            $model->hydrate($result);
        }
        return $model;
    }
    
    public function update(AbstractModel $model)
    {
        // Translate the model into an associative array.
        $data_array = $model->toArray();
        
        // Loop over each of the write strategies executing the create method.
        foreach($this->_write_strategies as $strategy) {
            $strategy->update($data_array);
        }
        
    }
    
    public function delete($id) 
    {
        
    }
    
}
