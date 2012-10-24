<?php

namespace PhpIdServer\Controller;

use PhpIdServer\Context\AuthorizeContext;
use PhpIdServer\Client\Registry;


abstract class BaseController extends \Zend\Mvc\Controller\AbstractActionController
{


    /**
     * Returns the service manager object.
     * 
     * @return \Zend\ServiceManager\ServiceManager
     */
    protected function _getServiceManager ()
    {
        return $this->getServiceLocator();
    }


    protected function _saveContext (AuthorizeContext $context)
    {
        $this->getServiceLocator()
            ->get('ContextStorage')
            ->save($context);
    }


    protected function _debug ($value)
    {
        $this->getServiceLocator()
            ->get('Logger')
            ->debug($value);
    }


    protected function _redirectToRoute ($routeName, Array $params = array(), Array $options = array())
    {
        $path = $this->url()
            ->fromRoute($routeName, $params, $options);
        
        $uri = new \Zend\Uri\Http($this->_getBaseUri());
        $uri->setPath($path);
        
        return $this->redirect()
            ->toUrl($uri->toString());
    }


    protected function _getBaseUri ()
    {
        $uri = $this->getRequest()
            ->getUri();
        $uri->setPath('');
        $uri->setQuery(array());
        $uri->setFragment('');
        
        return $uri;
    }


    protected function _handleException (\Exception $e)
    {
        return $this->_handleError("$e");
    }


    protected function _handleError ($message)
    {
        _dump($message);
        $response = $this->getResponse();
        $response->setStatusCode(500);
        $response->setContent('ERROR: ' . $message);
        
        return $response;
    }
}