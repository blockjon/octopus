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
     * Return an indexed array of fields which are persistent.
     *
     * @param $model
     * @return array
     */
    public function getPersistentOctopusFieldNames($model)
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
                if (preg_match('~^Octopus~', $annoationClassName)) {
                    $results[] = $property->getName();
                }
            }
        }

        return $results;
    }
}
