<?php

namespace PhpIdServer\Controller;

use PhpIdServer\Context\AuthorizeContext;
use PhpIdServer\Context\Storage\StorageInterface;
use Zend\Log\Logger;


abstract class BaseController extends \Zend\Mvc\Controller\AbstractActionController
{

    /**
     * @var StorageInterface
     */
    protected $contextStorage = null;

    /**
     * @var Logger
     */
    protected $logger = null;

    protected $logIdent = 'abstract base';


    /**
     * Sets the context storage.
     * 
     * @param StorageInterface $contextStorage
     */
    public function setContextStorage(StorageInterface $contextStorage)
    {
        $this->contextStorage = $contextStorage;
    }


    /**
     * Returns the context storage.
     * 
     * @return StorageInterface
     */
    public function getContextStorage()
    {
        return $this->contextStorage;
    }


    /**
     * Sets the logger.
     * 
     * @param Logger $logger
     */
    public function setLogger(Logger $logger)
    {
        $this->logger = $logger;
    }


    /**
     * Returns the logger.
     * 
     * @return Logger
     */
    public function getLogger()
    {
        return $this->logger;
    }


    protected function saveContext(AuthorizeContext $context)
    {
        $this->getContextStorage()->save($context);
    }


    protected function clearContext()
    {
        $this->getContextStorage()->clear();
    }


    protected function _debug($message)
    {
        $this->_logDebug($message);
    }


    protected function _logDebug($message)
    {
        $this->_log($message, \Zend\Log\Logger::DEBUG);
    }


    protected function _logInfo($message)
    {
        $this->_log($message, \Zend\Log\Logger::INFO);
    }


    protected function _logError($message)
    {
        $this->_log($message, \Zend\Log\Logger::ERR);
    }


    protected function _log($message, $priority = \Zend\Log\Logger::INFO)
    {
        // $this->_getServiceManager()->get('Logger')->log($priority, $this->_formatLogMessage($message));
        $logger = $this->getLogger();
        if ($logger instanceof Logger) {
            $logger->log($priority, $this->_formatLogMessage($message));
        }
    }


    protected function _formatLogMessage($message)
    {
        return sprintf("CONTROLLER [%s] %s", $this->logIdent, $message);
    }


    protected function _redirectToRoute($routeName, Array $params = array(), Array $options = array())
    {
        $path = $this->url()->fromRoute($routeName, $params, $options);
        
        $uri = new \Zend\Uri\Http($this->_getBaseUri());
        $uri->setPath($path);
        
        return $this->redirect()->toUrl($uri->toString());
    }


    protected function _getBaseUri()
    {
        $uri = $this->getRequest()->getUri();
        $uri->setPath('');
        $uri->setQuery(array());
        $uri->setFragment('');
        
        return $uri;
    }


    /**
     * Handles an exception - returns an error.
     * 
     * @param \Exception $e
     * @param string $label
     */
    protected function _handleException(\Exception $e, $label = 'Exception')
    {
        _dump("$e");
        return $this->_handleError(sprintf("%s: [%s] %s", $label, get_class($e), $e->getMessage()));
    }


    /**
     * Handles an application error.
     * 
     * @param string $message
     * @return \Zend\Stdlib\ResponseInterface
     */
    protected function _handleError($message)
    {
        $this->_logError($message);
        $this->_logInfo('returnning error response...');
        $response = $this->getResponse();
        $response->setStatusCode(500);
        
        return $response;
    }
}