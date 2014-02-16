<?php

namespace Octopus\Strategy;

class Memcache extends AbstractStrategy
{
    
    protected $_mch;
    protected $_expire; // lifetime

    /**
     * Constructor
     *
     * @param  array $options associative array of options
     * @throws Exception
     * @return void
     */
    public function __construct(array $options = array())
    {
        if (!extension_loaded('memcache')) {
            throw new \Exception('The memcache extension must be loaded for using this strategy.');
        }
        $this->_mch = new \Memcache();
        $this->_mch->connect($options['host'], $options['port']);
        $this->_expire = $options['expire'];
    }

    /**
     * Test if a cache is available for the given id and (if yes) return it (false else)
     *
     * @param  string $id cache id
     * @return mixed|null
     */
    public function read($id)
    {
        $result = $this->_mch->get($id);
        if ($result !== false) {
            return $result;
        }
        return null;
    }

    /**
     * Test if a cache is available or not (for the given id)
     *
     * @param  string $id cache id
     * @return boolean
     */
    public function test($id)
    {
        $result = $this->read($id);
        if ($result !== null) {
            return true;
        }
        return false;
    }

    /**
     * Save some string datas into a cache record
     *
     * @param array Data to cache
     * @return boolean
     */
    public function create(array $data_array)
    {
        $id = $data_array['id'];
        $result = $this->_mch->set($id, $data_array, \MEMCACHE_COMPRESSED, $this->_expire);
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
        $id = $data_array['id'];
        $result = $this->_mch->set($id, $data_array, \MEMCACHE_COMPRESSED, $this->_expire);
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
        return $this->_mch->delete($id);
    }

}
