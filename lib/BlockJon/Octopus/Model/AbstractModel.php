<?php

namespace Octopus\Model;

use Doctrine\Common\Annotations\AnnotationReader,
    Doctrine\Common\Annotations\AnnotationRegistry,
    Octopus\Annotation as Octopus;

abstract class AbstractModel {

    
    private $_annotationReader;
    
    public function __construct()
    {
        $this->_annotationReader = new AnnotationReader();
        AnnotationRegistry::registerAutoloadNamespace("Octopus\Annotation", realpath(dirname(__FILE__).'/../../'));
    }
    
    /**
     * @Octopus\PropertyName
     */
    protected $id;
    
    /**
     * @Octopus\PropertyName
     */
    protected $dateCreated;
    
    /**
     * @Octopus\PropertyName
     */
    protected $dateLastUpdated;
    
    /**
     * Get the list of properites of this object.
     * 
     * "[Semantical Error] The annotation "@Octopus\Annotation\Foo" in property 
     * Models\Book::$title does not exist, or could not be auto-loaded."
     * 
     * @param \Doctrine\Common\Annotations\AnnotationReader $reader
     * @return array
     */
    private function getFieldNames(AnnotationReader $reader) 
    {
        $fieldNames = array();
        
        $reflectionObject = new \ReflectionObject($this);
        /**
         * @var $property \ReflectionProperty 
         */
        foreach($reflectionObject->getProperties() as $property) {
            $annotation = $reader->getPropertyAnnotation($property, 'Octopus\Annotation\PropertyName');
            if($annotation) {
                $fieldNames[] = $property->getName();
            }
        }
        return $fieldNames;
    }

    public function toArray() {
        $result = array(
            'id' => $this->getId(),
        );
        
        // Get all of the fields which are property names.
        $fields = $this->getFieldNames($this->_annotationReader);
        
        // Detect all of the keys and pull their names and values into an 
        // associative array and return it.
        foreach($fields as $thisFieldName) {
            $result[$thisFieldName] = $this->$thisFieldName;        
        }
        
        return $result;
    }

    /**
     * @param array $data
     */
    public function hydrate(array $data) {
        
        // Get all of the fields which are property names.
        $fields = $this->getFieldNames($this->_annotationReader);
        
        foreach($fields as $thisFieldName) {
            $this->$thisFieldName = $data[$thisFieldName];       
        }

    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }
    
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    public function getDateLastUpdated()
    {
        return $this->dateLastUpdated;
    }

    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;
        return $this;
    }

    public function setDateLastUpdated($dateLastUpdated)
    {
        $this->dateLastUpdated = $dateLastUpdated;
        return $this;
    }

}
