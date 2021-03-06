<?php

namespace Octopus\DAO;

use Octopus\Model\AbstractModel,
    Rhumsaa\Uuid\Uuid;    

abstract class AbstractDAO implements GetModelInterface
{
    
    const METHOD_CREATE = 'create';
    const METHOD_READ = 'read';
    const METHOD_UPDATE = 'update';
    const METHOD_DELETE = 'delete';
    
    private $_write_strategies = array();
    private $_read_strategies = array();
    
    protected $_primary_write_backup_strategy;
    
    static $_baseConfig = array(
        'pdosqlite' => array(
            'columns' => array(
            ),
            'table' => null
        ),
        'pdomysql' => array(
            'columns' => array(
            ),
            'table' => null,
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
     * Returns an associative array config for a backend.
     * @param string $key
     * @return array
     */
    public static function getConfig($key) 
    {
        // Load the core config.
        $baseConfig = self::$_baseConfig;
        // Merge in dao specific config.
        $daoConfig = static::$_config;
        $mergedConfig = array_merge($baseConfig, $daoConfig);
        return $mergedConfig[$key];
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
        $currentTime = time();
        if ($model->getId() === null) {
            $uuid = (string)Uuid::uuid5(Uuid::NAMESPACE_DNS, uniqid('',true));
            $model->setId($uuid);
        }
        if($model->getDateCreated() === null) {
            $model->setDateCreated($currentTime);
        }
        $model->setDateLastUpdated($currentTime);
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
            $model = static::getModel();
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
        $model->setDateLastUpdated(time());
        
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
    
    /**
     * @return \Models\Book
     */
    static public function getModel()
    {
        
        // Explode the model's namespace.
        $namespaceArray = explode('\\', get_called_class());
        
        // Get the name of the model.
        $class = array_pop($namespaceArray);
        
        // Build out the path to where the daos are.
        array_pop($namespaceArray);
        array_push($namespaceArray, 'Models');
        
        $daoNamespace = implode(',', $namespaceArray);
        
        // Instantiate the dao.
        $fqn = $daoNamespace . '\\' . $class;
        
        $model = new $fqn();
        
        return $model;
        
    }
    
}
