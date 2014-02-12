<?php

namespace Octopus\Strategy;

class PdoSqlite extends AbstractStrategy
{

    protected $_dbh;
    
    /**
     * Constructor
     *
     * @param  array $options associative array of options
     * @throws Exception
     * @return void
     */
    public function __construct(array $options = array())
    {
        if (!extension_loaded('pdo')) {
            throw new \Exception('The pdo extension must be loaded for using this strategy.');
        }
        if (!extension_loaded('sqlite3')) {
            throw new \Exception('The sqlite3 extension must be loaded for using this strategy.');
        }
        $this->_dbh = new \PDO('sqlite::memory:');
    }

    /**
     * Test if a cache is available for the given id and (if yes) return it (false else)
     *
     * @param  string $id cache id
     * @return mixed|null
     */
    public function read($id)
    {
        $tmp = apc_fetch($id);
        if (is_array($tmp)) {
            return $tmp[0];
        }
        return null;
    }

    /**
     * Test if a cache is available or not (for the given id)
     *
     * @param  string $id cache id
     * @return int|false (a cache is not available) or "last modified" timestamp (int) of the available cache record
     */
    public function test($id)
    {
        $tmp = apc_fetch($id);
        if (is_array($tmp)) {
            return $tmp[1];
        }
        return false;
    }

    /**
     * Save some string datas into sqlite.
     *
     * @param array Data to store
     * @return boolean true if no problem
     */
    public function create($data_array)
    {
        $lifetime = 60; // todo: set this to something meaningful
        $id = $data_array['id'];
        
        $fields = implode(', ', array_keys($data_array));
        $values = array_values($data_array);
        $questionMarksArray = array();
        $fieldDefinitions = '';
        foreach($data_array as $fieldName => $fieldValue) {
            $fieldDefinitions .= $fieldName . ' varchar(255), ';
            $questionMarksArray[] = '?';
        }
        $fieldDefinitions = trim($fieldDefinitions);
        if(strlen($fieldDefinitions) > 0) {
            $fieldDefinitions = substr($fieldDefinitions, 0, strlen($fieldDefinitions)-1);
        }
        $questionMarksString = implode(',', $questionMarksArray);
        $x = $this->_dbh->exec("CREATE TABLE mytable ($fieldDefinitions);" );
        
        $sql = "INSERT INTO mytable ($fields) VALUES ($questionMarksString)";
        $stmt = $this->_dbh->prepare($sql);
        $result = $stmt->execute($values);

        return $result;
    }
    
    /**
     * Save some string datas into a cache record
     *
     * @param array Data to cache
     * @return boolean true if no problem
     */
    public function update($data_array)
    {
        $lifetime = 60; // todo: set this to something meaningful
        $id = $data_array['id'];
        $result = apc_store($id, array($data_array, time(), $lifetime), $lifetime);
        return $result;
    }

    /**
     * Remove a cache record
     *
     * @param  string $id cache id
     * @return boolean true if no problem
     */
    public function delete($id)
    {
        return apc_delete($id);
    }

}
