<?php

namespace InoOicServer\Oic;

use DateTime;
use Zend\Stdlib\Hydrator\ClassMethods;


abstract class EntityHydrator extends ClassMethods
{


    /**
     * {@inheritdoc}
     * @see \Zend\Stdlib\Hydrator\ClassMethods::extract()
     */
    public function extract($entity)
    {
        $entityData = parent::extract($entity);
        $entityData = $this->convertValues($entityData);
        
        return $entityData;
    }


    /**
     * @param array $entityData
     * @return array
     */
    public function convertValues(array $entityData)
    {
        return $this->convertDateTimeValues($entityData);
    }


    /**
     * Converts a DateTime object into datetime string suitable for database insertion.
     *
     * @param DateTime $dateTime
     * @return string
     */
    public function toDbDateTimeString(DateTime $dateTime)
    {
        return $dateTime->format('Y-m-d H:i:s');
    }


    /**
     * Converts all DateTime values into string representation.
     * 
     * @param array $values
     * @return array
     */
    public function convertDateTimeValues(array $values)
    {
        foreach ($values as &$value) {
            if (isset($value) && ($value instanceof \DateTime)) {
                $value = $this->toDbDateTimeString($value);
            }
        }
        
        return $values;
    }


    /**
     * @param array $values
     * @param array $fields
     * @return array
     */
    public function unsetFields(array $values, array $fields)
    {
        foreach ($fields as $field) {
            unset($values[$field]);
        }
        
        return $values;
    }
}