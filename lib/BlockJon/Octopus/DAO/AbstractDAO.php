<?php

namespace Octopus\DAO;

use Octopus\Model\AbstractModel,
    Rhumsaa\Uuid\Uuid;    

abstract class AbstractDAO
{
    
    const METHOD_CREATE = 'create';
    const METHOD_READ = 'read';
    const METHOD_UPDATE = 'update';
    const METHOD_DELETE = 'delete';
    
    private $_write_strategies = array();
    private $_read_strategies = array();
    
    protected $_primary_write_backup_strategy;
    
    /**
     * Returns an associative array config for a backend.
     * @param string $key
     * @return array
     */
    public static function getConfig($key) 
    {
        $config = static::$_config;
        return $config[$key];
    }
    
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
     * Create a model.
     * 
     * @param type $model
     * @return void
     * @throws \Exception
     */
    public function create(AbstractModel $model)
    {
        if ($model->getId() === null) {
            $uuid = (string)Uuid::uuid5(Uuid::NAMESPACE_DNS, uniqid('',true));
            $model->setId($uuid);
        }
        // Loop over each of the write strategies executing the create method.
        $this->writeDataChange($model, self::METHOD_CREATE);
    }
    
    /**
     * Get a model.
     * 
     * @param type $id
     * @return null|instance of abstract model
     * @throws \Exception
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
            $modelClassName = $this->getModelClassName();
            $model = new $modelClassName(); // todo: pick class based on precise dao.
            $model->hydrate($result);
        }
        return $model;
    }
    
    /**
     * Update a model.
     * 
     * @param \Octopus\Model\AbstractModel $model
     * @throws \Exception
     */
    public function update(AbstractModel $model)
    {
        // Loop over each of the write strategies executing the create method.
        $this->writeDataChange($model, self::METHOD_UPDATE);
    }
    
    /**
     * Delete a model.
     * 
     * @param type $model
     * @return bool
     * @throws \Exception
     */
    public function delete(AbstractModel $model) 
    {
        return $this->writeDataChange($model, self::METHOD_DELETE);
    }
    
    /**
     * Controller for the write loop.
     * 
     * @param \Octopus\Model\AbstractModel $model
     * @param string $action
     * @throws \Exception
     */
    protected function writeDataChange(AbstractModel $model, $action) 
    {
        foreach($this->_write_strategies as $index => $strategy) {
            try {
                // Apply the write to the given strategy.
                $this->applyWriteStrategy($strategy, $model, $action);
            } catch(\Exception $e) {
                // If this was the first strategy (aka primary) that failed, check 
                // to see if there is a backup strategy. If so, try it.
                if($index === 0 && $this->_primary_write_backup_strategy) {
                    $this->applyWriteStrategy($this->_primary_write_backup_strategy, $model, $action);
                } else {
                    throw $e;
                }
            }
        }
    }
    
    /**
     * Perform an individual write to a strategy.
     * 
     * @param \Octopus\Strategy\AbstractStrategy $strategy
     * @param AbstractModel $model
     * @param string $action
     * @throws \Exception
     * @return void
     */
    protected function applyWriteStrategy(\Octopus\Strategy\AbstractStrategy $strategy, AbstractModel $model, $action) 
    {
        if($action == self::METHOD_DELETE) {
            $worked = $strategy->$action($model->getId());
        } else {
            $worked = $strategy->$action($model->toArray());        
        }
    }
    
    /**
     * Indicate what strategy, if any, should be used if the first write strategy 
     * throws an exception.
     * 
     * @param \Octopus\Strategy\AbstractStrategy $strategy
     */
    public function setPrimaryWriteBackupStrategy(\Octopus\Strategy\AbstractStrategy $strategy)
    {
        $this->_primary_write_backup_strategy = $strategy;
    }
    
    public function getModelClassName()
    {
        return static::$modelClassName;
    }
    
}
