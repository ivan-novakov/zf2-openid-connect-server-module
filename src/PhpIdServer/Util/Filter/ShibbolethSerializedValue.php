<?php

namespace PhpIdServer\Util\Filter;

use Zend\Filter\FilterInterface;


/**
 * Simple filter class to deal with serialized attribute values set by Shibboleth.
 */
class ShibbolethSerializedValue implements FilterInterface
{

    /**
     * The value delimiter character.
     * 
     * @var string
     */
    protected $delimiter = ';';


    /**
     * {@inheritdoc}
     * @see \Zend\Filter\FilterInterface::filter()
     */
    public function filter($value)
    {
        $emails = explode($this->delimiter, $value);
        if (count($emails)) {
            return $emails[0];
        }
        
        return null;
    }
}