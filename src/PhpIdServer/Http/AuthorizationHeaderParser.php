<?php

namespace PhpIdServer\Http;

use PhpIdServer\Util\Options;
use PhpIdServer\Client;


/**
 * The class is responsible for parsing the "Authorization" HTTP header.
 * 
 * @copyright (c) 2013 Ivan Novakov (http://novakov.cz/)
 * @license http://debug.cz/license/freebsd
 */
class AuthorizationHeaderParser
{

    const OPT_METHOD_REGEXP = 'method_regexp';

    const OPT_PARAMS_REGEXP = 'params_regexp';

    const DELIMITER_PARAM = ';';

    const DELIMITER_ASSIGN = '=';

    /**
     * Options.
     * 
     * @var Options
     */
    protected $_options;

    protected $_defaultOptions = array(
        self::OPT_METHOD_REGEXP => '/^\w+$/',
        self::OPT_PARAMS_REGEXP => '/^[\w;= ]+$/'
    );

    /**
     * A list of errors occurred during parsing.
     * 
     * @var array
     */
    protected $_errors = array();

    /**
     * The raw header value.
     * 
     * @var string
     */
    protected $_rawValue = null;


    public function __construct($options = array())
    {
        $this->setOptions($options);
    }


    public function setOptions($options)
    {
        $this->_options = new Options($options + $this->_defaultOptions);
    }


    public function parse($rawValue)
    {
        $parts = explode(' ', $rawValue, 2);
        if (count($parts) != 2) {
            $this->_addError('Invalid value format');
            return null;
        }
        
        $method = trim($parts[0]);
        $paramString = trim($parts[1]);
        
        if (! preg_match($this->_options->get(self::OPT_METHOD_REGEXP), $method)) {
            $this->_addError(sprintf("Invalid method format: '%s'", $method));
            return null;
        }
        
        if (! preg_match($this->_options->get(self::OPT_PARAMS_REGEXP), $paramString)) {
            $this->_addError(sprintf("Invalid params string format: '%s'", $paramString));
        }
        
        $paramPairs = explode(self::DELIMITER_PARAM, $paramString);
        $params = array();
        foreach ($paramPairs as $pair) {
            $keyValue = explode(self::DELIMITER_ASSIGN, $pair);
            if (count($keyValue) != 2) {
                $this->_addError(sprintf("Invalid key-value pair: '%s'", $pair));
                return null;
            }
             
            $key = trim($keyValue[0]);
            $value = trim($keyValue[1]);
            
            $params[$key] = $value;
        }
        
        return new Client\Authentication\Data($method, $params);
    }


    public function isError()
    {
        return (! empty($this->_errors));
    }


    public function getErrors()
    {
        return $this->_errors;
    }


    protected function _addError($error)
    {
        $this->_errors[] = $error;
    }
}