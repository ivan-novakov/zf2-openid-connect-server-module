<?php

namespace PhpIdServer\OpenIdConnect;


class Request
{

    const REQUEST_METHOD_SIMPLE = 'simple';

    const REQUEST_METHOD_PARAMETER = 'parameter';

    const REQUEST_METHOD_FILE = 'file';

    /**
     * The HTTP request object.
     *
     * @var \Zend\Http\Request
     */
    protected $_httpRequest = NULL;


    /**
     * Constructor.
     *
     * @param \Zend\Http\Request $httpRequest            
     */
    public function __construct (\Zend\Http\Request $httpRequest)
    {
        $this->_httpRequest = $httpRequest;
    }


    /**
     * Returns the request method as specified in the specs - simple, parameter,
     * file.
     *
     * @return string
     */
    public function getRequestMethod ()
    {}
}