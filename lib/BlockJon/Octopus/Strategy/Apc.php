<?php

namespace Octopus\Strategy;

class Apc extends AbstractStrategy
{

    /**
     * Constructor
     *
     * @param  array $options associative array of options
     * @throws Exception
     * @return void
     */
    public function __construct(array $options = array())
    {
        if (!extension_loaded('apc')) {
            throw new \Exception('The apc extension must be loaded for using this strategy.');
        }
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
     * @return boolean
     */
    public function test($id)
    {
        $tmp = apc_fetch($id);
        if (is_array($tmp)) {
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
        $lifetime = 60; // todo: set this to something meaningful
        $id = $data_array['id'];
        $result = apc_store($id, array($data_array, time(), $lifetime), $lifetime);
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
