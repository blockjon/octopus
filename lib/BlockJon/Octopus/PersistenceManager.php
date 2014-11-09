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
        AnnotationRegistry::registerAutoloadNamespace(
            "Octopus\Annotation",
            realpath(dirname(__FILE__) . '/../')
        );
        AnnotationRegistry::registerAutoloadNamespace(
            'Doctrine\ODM\MongoDB\Mapping\Annotations',
            realpath(dirname(__FILE__) . '/../../../vendor/doctrine/mongodb-odm/lib')
        );
    }

    /**
     * Return an indexed array of fields which have Octopus annotations.
     *
     * @param mixed $model
     * @param string $startingWith A string such as "ODM" or "Octopus".
     * @return array
     */
    public function getFieldNamesHavingAnnotationsStartingWith($model, $startingWith)
    {
        $annotationReader = $this->annotationReader;
        $reflectionObject = new \ReflectionObject($model);
        $results = array();

        /**
         * @var $property \ReflectionProperty
         */
        foreach ($reflectionObject->getProperties() as $property) {
            $propertyAnnotations = $annotationReader->getPropertyAnnotations($property);
            foreach ($propertyAnnotations as $annotationObject) {
                $annoationClassName = get_class($annotationObject);
                if (preg_match("~^$startingWith~", $annoationClassName)) {
                    $results[] = $property->getName();
                    continue;
                }
            }
        }
        return $results;
    }

    /**
     * Given a model, create a simple array representation of it's values.
     */
    public function export($model)
    {
        $result = array();
        $fields = $this->getFieldNamesHavingAnnotationsStartingWith($model, 'Doctrine\\\\ODM');

        $reflectionObject = new \ReflectionObject($model);

        foreach ($fields as $thisField) {
            $reflectionProperty = $reflectionObject->getProperty($thisField);
            $reflectionProperty->setAccessible(true);
            $value = $reflectionProperty->getValue($model);
            $result[$thisField] = $value;
        }
        return $result;
    }
}
