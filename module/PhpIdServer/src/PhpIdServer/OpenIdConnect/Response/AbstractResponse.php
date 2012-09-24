<?php

namespace PhpIdServer\OpenIdConnect\Response;


abstract class AbstractResponse implements ResponseInterface
{

    /**
     * The HTTP response object.
     * 
     * @var \Zend\Http\Response
     */
    protected $_httpResponse = NULL;


    /**
     * Constructor.
     * 
     * @param \Zend\Http\Response $httpResponse
     */
    public function __construct (\Zend\Http\Response $httpResponse)
    {
        $this->_httpResponse = $httpResponse;
    }


    /**
     * Returns the HTTP response object.
     * 
     * @return \Zend\Http\Response
     */
    public function getHttpResponse ()
    {
        $this->_setNoCacheHeaders($this->_httpResponse);
        
        return $this->_httpResponse;
    }


    /**
     * Returns the HTTP response object with no modifications.
     *
     * @return \Zend\Http\Response
     */
    public function getRawHttpResponse ()
    {
        return $this->_httpResponse;
    }


    protected function _setNoCacheHeaders ($httpResponse)
    {
        $httpResponse->getHeaders()
            ->addHeaders(array(
            'Cache-Control' => 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0', 
            'Pragma' => 'no-cache'
        ));
    }
}