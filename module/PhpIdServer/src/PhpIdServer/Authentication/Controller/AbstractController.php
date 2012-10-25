<?php

namespace PhpIdServer\Authentication\Controller;

use PhpIdServer\Authentication\Info;
use PhpIdServer\Util\Options;
use PhpIdServer\Controller\BaseController;
use PhpIdServer\Context;


abstract class AbstractController extends BaseController implements AuthenticationControllerInterface
{

    /**
     * Options.
     * 
     * @var Options
     */
    protected $_options = NULL;


    /**
     * Sets the options.
     * 
     * @param array|\Traversable $options
     */
    public function setOptions ($options)
    {
        if (! is_array($options)) {
            $options = array();
        }
        
        $this->_options = new Options($options);
    }


    public function getOption ($name, $defaultValue = null)
    {
        return $this->_options->get($name, $defaultValue);
    }


    public function getLabel ()
    {
        return $this->getOption('label', 'unknown');
    }


    public function indexAction ()
    {
        return $this->getResponse();
    }


    public function authenticateAction ()
    {
        $this->_debug(sprintf("Authentication controller [%s]", $this->getLabel()));
        
        $context = $this->getServiceLocator()
            ->get('AuthorizeContext');
        
        try {
            $this->_authenticate($context);
        } catch (\Exception $e) {
            $this->_debug(sprintf("Error during authentication: [%s] %s", get_class($e), $e->getMessage()));
            return $this->_errorResponse('');
        }
        
        $this->_debug('redirecting back to authorize endpoint');
        
        return $this->_redirectToRoute('php-id-server/authorize-endpoint');
    }


    abstract protected function _authenticate (Context\AuthorizeContext $context);


    protected function _errorResponse ($message)
    {
        $response = $this->getResponse();
        
        $response->setStatusCode(400);
        $response->setContent('Authentication error');
        
        return $response;
    }


    /**
     * Initializes and returns authentication info.
     * 
     * @return \PhpIdServer\Authentication\Controller\Info
     */
    protected function _initAuthenticationInfo ()
    {
        $authenticationInfo = new Info(array(
            Info::FIELD_METHOD => $this->_options->get('label'), 
            Info::FIELD_TIME => new \DateTime('now')
        ));
        
        return $authenticationInfo;
    }
}