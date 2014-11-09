<?php

namespace Octopus\DAO;

use Octopus\Model\AbstractModel;
use Rhumsaa\Uuid\Uuid;
use Octopus\Strategy\AbstractStrategy;

abstract class AbstractDAO implements GetModelInterface
{
    const METHOD_CREATE = 'create';
    const METHOD_READ = 'read';
    const METHOD_UPDATE = 'update';
    const METHOD_DELETE = 'delete';
    
    private $writeStrategies = array();
    private $readStrategies = array();
    
    protected $primaryWriteBackupStrategy;
    
    static protected $baseConfig = array(
        'pdosqlite' => array(
            'columns' => array(
            ),
            'table' => null
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
        $baseConfig = self::$baseConfig;
        // Merge in dao specific config.
        $daoConfig = static::$_config;
        $mergedConfig = array_merge($baseConfig, $daoConfig);
        return $mergedConfig[$key];
    }
    
    /**
     * @param array $writeStrategies
     * @param array $readStrategies
     */
    public function __construct(array $writeStrategies = array(), array $readStrategies = array())
    {
        $this->writeStrategies = $writeStrategies;
        $this->readStrategies = $readStrategies;
    }
    
    /**
     * Create a model.
     * 
     * @param AbstractModel $model
     * @return bool
     * @throws \Exception
     */
    public function create(AbstractModel $model)
    {
        $currentTime = time();
        if ($model->getId() === null) {
            $uuid = (string) Uuid::uuid5(Uuid::NAMESPACE_DNS, uniqid('', true));
            $model->setId($uuid);
        }
        if ($model->getDateCreated() === null) {
            $model->setDateCreated($currentTime);
        }
        $model->setDateLastUpdated($currentTime);
        // Loop over each of the write strategies executing the create method.
        return $this->writeDataChange($model, self::METHOD_CREATE);
    }
    
    /**
     * Get a model.
     * 
     * @param string $id
     * @return null|instance of abstract model
     * @throws \Exception
     */
    public function read($id)
    {
        $result = null;
        $model = null;
        // Use the first read strategy that finds the object.
        foreach ($this->readStrategies as $strategy) {
            if ($result = $strategy->read($id)) {
                break;
            }
        }
        if ($result) {
            $model = static::getModel();
            $model->hydrate($result);
        }
        return $model;
    }
    
    /**
     * Update a model.
     * 
     * @param AbstractModel $model
     * @return bool
     * @throws \Exception
     */
    public function update(AbstractModel $model)
    {
        $model->setDateLastUpdated(time());
        
        // Loop over each of the write strategies executing the create method.
        return $this->writeDataChange($model, self::METHOD_UPDATE);
    }
    
    /**
     * Delete a model.
     * 
     * @param AbstractModel $model
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
     * @param AbstractModel $model
     * @param string $action
     * @throws \Exception
     * @return bool
     */
    protected function writeDataChange(AbstractModel $model, $action)
    {
        foreach ($this->writeStrategies as $index => $strategy) {
            try {
                // Apply the write to the given strategy.
                $this->applyWriteStrategy($strategy, $model, $action);
            } catch (\Exception $e) {
                // If this was the first strategy (aka primary) that failed, check
                // to see if there is a backup strategy. If so, try it.
                if ($index === 0 && $this->primaryWriteBackupStrategy) {
                    $this->applyWriteStrategy($this->primaryWriteBackupStrategy, $model, $action);
                } else {
                    throw $e;
                }
            }
        }
        return true;
    }
    
    /**
     * Perform an individual write to a strategy.
     * 
     * @param AbstractStrategy $strategy
     * @param AbstractModel $model
     * @param string $action
     * @throws \Exception
     * @return bool
     */
    protected function applyWriteStrategy(AbstractStrategy $strategy, AbstractModel $model, $action)
    {
        if ($action == self::METHOD_DELETE) {
            $worked = $strategy->$action($model->getId());
        } else {
            $worked = $strategy->$action($model->toArray());
        }
        return $worked;
    }
    
    /**
     * Indicate what strategy, if any, should be used if the first write strategy 
     * throws an exception.
     * 
     * @param AbstractStrategy $strategy
     */
    public function setPrimaryWriteBackupStrategy(AbstractStrategy $strategy)
    {
        $this->primaryWriteBackupStrategy = $strategy;
    }
    
    /**
     * Return a new model object that this DAO manages.
     *
     * @return AbstractModel
     */
    public static function getModel()
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
        $fullyQualifiedName = $daoNamespace . '\\' . $class;
        
        $model = new $fullyQualifiedName();
        
        return $model;
    }
}
