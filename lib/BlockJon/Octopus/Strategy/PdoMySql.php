<?php

namespace Octopus\Strategy;

class PdoMySql extends AbstractStrategy
{

    protected $_dbh;
    
    /**
     * Constructor
     *
     * @param  array $config associative array of options
     * @throws Exception
     * @return void
     */
    public function __construct(array $config = array())
    {
        if (!extension_loaded('pdo')) {
            throw new \Exception('The pdo extension must be loaded for using this strategy.');
        }
        if (!extension_loaded('pdo_mysql')) {
            throw new \Exception('The pdo_mysql extension must be loaded for using this strategy.');
        }
        $dbhost = '';
        $dbname = 'octopustest';
        $username = 'root';
        $password = '';
        $handle = new \PDO('mysql:host=' . $dbhost. ';dbname=' . $dbname . ';charset=utf8', $username, $password);
        $this->_dbh = $handle;
        parent::__construct($config);
    }
    
    /**
     * @return \PDO
     */
    public function getPdoHandle()
    {
        return $this->_dbh;
    }

    /**
     * Test if a cache is available for the given id and (if yes) return it (false else)
     *
     * @param  string $id cache id
     * @return mixed|array
     */
    public function read($id)
    {
        
        $sql = 'SELECT `' . implode('`,`', $this->_config['columns']) . '`
            FROM `' . $this->_config['table'] . '`
            WHERE id = :id
            LIMIT 1';
        $statement = $this->_dbh->prepare($sql, array(\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY));
        $statement->execute(array(':id' => $id));
        $result = $statement->fetch(\PDO::FETCH_ASSOC);
        return $result;
    }

    /**
     * Test if a cache is available or not (for the given id)
     *
     * @param  string $id cache id
     * @return int|false (a cache is not available) or "last modified" timestamp (int) of the available cache record
     */
    public function test($id)
    {
        $result = $this->read($id);
        return $result !== false;
    }

    /**
     * Save some string datas into sqlite.
     *
     * @param array Data to store
     * @return boolean true if no problem
     */
    public function create(array $data_array)
    {
        $fields = implode(',', $this->_config['columns']);
        $values = array();
        $questionMarksArray = array();
        foreach($this->_config['columns'] as $thisColumn) {
            $questionMarksArray[] = '?';
            if(isset($data_array[$thisColumn])) {
                $values[] = $data_array[$thisColumn];
            } else {
                $values[] = null;
            }
        }
        $questionMarksString = implode(',', $questionMarksArray);        
        $sql = "INSERT INTO `" . $this->_config['table'] . "` ($fields) VALUES ($questionMarksString)";
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
    public function update(array $data_array)
    {
        $setTemplate = '';
        $dictionary = array();
        $counter = 1;
        foreach($data_array as $key => $value) {
            $setTemplate .= "$key = :var$counter, ";
            $dictionary["var$counter"] = $value;
            $counter++;
        }
        $dictionary["id"] = $data_array['id'];
        $setTemplate = rtrim($setTemplate);
        $setTemplate = substr($setTemplate, 0, strlen($setTemplate)-1);
        $sql = "update `" . $this->_config['table'] . "` SET $setTemplate WHERE id = :id";
        $stmt = $this->_dbh->prepare($sql);
        $result = $stmt->execute($dictionary);
        return $result === true;
    }

    /**
     * Remove a record
     *
     * @param  string $id cache id
     * @return boolean true if no problem
     */
    public function delete($id)
    {
        $sql = "DELETE FROM `" . $this->_config['table'] . "` WHERE id = :id";
        $statement = $this->_dbh->prepare($sql, array(\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY));
        $result = $statement->execute(array(':id' => $id));
        return $result;
    }

}
