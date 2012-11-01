<?php

namespace PhpIdServer\Authentication\Controller;

use PhpIdServer\User\User;
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
            $user = $this->_authenticate($context);
            if (! ($user instanceof User)) {
                throw new Exception\AuthenticationException('No user');
            }
            
            $context->setUser($user);
            $authenticationInfo = $this->_initSuccessAuthenticationInfo();
        } catch (Exception\AuthenticationException $e) {
            $this->_logError(sprintf("Authentication exception: %s (%s)", $e->getError(), $e->getDescription()));
            $authenticationInfo = $this->_initFailureAuthenticationInfo($e->getError(), $e->getDescription());
        } catch (\Exception $e) {
            $this->_logError(sprintf("General exception during authentication: [%s] %s", get_class($e), $e->getMessage()));
            $authenticationInfo = $this->_initFailureAuthenticationInfo('general_error');
        }
        
        $context->setAuthenticationInfo($authenticationInfo);
        $this->_saveContext($context);
        
        $authorizeRoute = 'php-id-server/authorize-endpoint';
        
        $this->_logInfo(sprintf("redirecting back to authorize endpoint '%s'", $authorizeRoute));
        
        return $this->_redirectToRoute($authorizeRoute);
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
    protected function _initSuccessAuthenticationInfo ()
    {
        return Info::factorySuccess($this->getLabel());
    }


    protected function _initFailureAuthenticationInfo ($error, $description = '')
    {
        return Info::factoryFailure($this->getLabel(), $error, $description);
    }


    protected function _formatLogMessage ($message)
    {
        return sprintf("CONTROLLER AUTH [%s] %s", $this->getLabel(), $message);
    }
}