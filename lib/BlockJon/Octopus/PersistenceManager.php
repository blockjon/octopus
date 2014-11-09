<?php

namespace Octopus;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;

class PersistenceManager
{

    private $annotationReader;

    public function __construct()
    {
        $this->annotationReader = new AnnotationReader();
        $pathToAnnotationsFolder = realpath(dirname(__FILE__) . "/Annotations");
        AnnotationRegistry::registerAutoloadNamespace('Octopus\Annotations', $pathToAnnotationsFolder);
    }

    /**
     * Get the list of properties of this object.
     *
     * @param AnnotationReader $reader
     * @return array
     */
    protected function getFieldNames($model)
    {
        $annotationReader = $this->annotationReader;

        $fieldNames = array();

        $reflectionObject = new \ReflectionObject($model);

        /**
         * @var $property \ReflectionProperty
         */
        foreach ($reflectionObject->getProperties() as $property) {
            $annotation = $annotationReader->getPropertyAnnotation($property, 'Octopus\Annotations');
            if ($annotation) {
                $fieldNames[] = $property->getName();
            }
        }
        return $fieldNames;
    }

    public function getTags($model)
    {
        $x = $this->getFieldNames($model);
        return $x;
    }
}
