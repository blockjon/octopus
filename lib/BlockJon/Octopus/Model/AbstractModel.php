<?php

namespace Octopus\Model;

abstract class AbstractModel {

    protected $_id;

//    
//    /**
//     * Convert object into an associative array.
//     * 
//     * @return array
//     */
//    abstract public function toArray();
//    
//    /**
//     * Given an array of data from the data store, re-hydrates the model.
//     */
//    abstract public function hydrate(array $data);

    public function toArray() {
        $result = array(
            'id' => $this->getId(),
        );
        // Detect all of the keys and pull their names and values into an 
        // associative array and return it.
        $result['title'] = $this->title;
        $result['author'] = $this->author;
        return $result;
    }

    /**
     * @param array $data
     */
    public function hydrate(array $data) {
        // Detect all of the keys and pull their names and values into an 
        // associative array and return it.
        $this->setId($data['id']);
        
        $this->title = $data['title'];
        $this->author = $data['author'];
    }

    public function getId() {
        return $this->_id;
    }

    public function setId($id) {
        $this->_id = $id;
        return $this;
    }

}
