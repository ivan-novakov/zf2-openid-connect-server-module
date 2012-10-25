<?php

namespace PhpIdServer\Authentication\Controller;

use PhpIdServer\Authentication\Info;
use PhpIdServer\Util\Options;
use PhpIdServer\Controller\BaseController;
use PhpIdServer\Context;


abstract class AbstractController extends BaseController implements AuthenticationControllerInterface
{

    protected $_logIdent = 'abstract authentication';

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


    /**
     * Returns the value of the option.
     * 
     * @param string $name
     * @param mixed $defaultValue
     * @return mixed|null
     */
    public function getOption ($name, $defaultValue = null)
    {
        return $this->_options->get($name, $defaultValue);
    }


    /**
     * Returns the label of the authentication controller.
     * 
     * @return string
     */
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
        $this->_logInfo(sprintf("Authentication controller [%s]", $this->getLabel()));
        
        $context = $this->getServiceLocator()
            ->get('AuthorizeContext');
        
        try {
            $this->_authenticate($context);
        } catch (\Exception $e) {
            return $this->_handleException($e, 'Authentication exception');
        }
        
        $this->_debug('redirecting back to authorize endpoint');
        
        return $this->_redirectToRoute('php-id-server/authorize-endpoint');
    }


    /**
     * The actual authentication procedure implemented in subclasses.
     * 
     * @param Context\AuthorizeContext $context
     */
    abstract protected function _authenticate (Context\AuthorizeContext $context);


    /**
     * Initializes and returns authentication info.
     * 
     * @return \PhpIdServer\Authentication\Controller\Info
     */
    protected function _initAuthenticationInfo ()
    {
        $authenticationInfo = new Info(array(
            Info::FIELD_METHOD => $this->getLabel(), 
            Info::FIELD_TIME => new \DateTime('now')
        ));
        
        return $authenticationInfo;
    }


    protected function _formatLogMessage ($message)
    {
        return sprintf("CONTROLLER AUTH [%s] %s", $this->getLabel(), $message);
    }
}