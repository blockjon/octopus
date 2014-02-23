<?php

namespace Octopus; 

use Octopus\Model\AbstractModel,
    Octopus\DAO\AbstractDAO;

class DaoManager
{
    
    public function __construct() 
    {
        
    }
    
    /**
     * The models and daos should be next to each other. Using the PSR0 autoload
     * scheme, we peel off the deepest part of the model's namespace and then 
     * replace it with "Daos" and that's where we try to load a dao from.
     * 
     * @param \Octopus\Model\AbstractModel $model
     * @return \Octopus\Dao\AbstractDao
     */
    public function getDao(AbstractModel $model)
    {
        
        // Explode the model's namespace.
        $namespaceArray = explode('\\', get_class($model));
        
        // Get the name of the model.
        $class = array_pop($namespaceArray);
        
        // Build out the path to where the daos are.
        array_pop($namespaceArray);
        array_push($namespaceArray, 'Daos');
        
        $daoNamespace = implode(',', $namespaceArray);
        
        // Instantiate the dao.
        $fqn = $daoNamespace . '\\' . $class;
        
        $dao = new $fqn();

        return $dao;
        
    }
    
}
