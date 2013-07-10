<?php

namespace PhpIdServer\Authentication\Controller;

use PhpIdServer\User\UserFactoryInterface;
use PhpIdServer\User\UserInterface;
use PhpIdServer\Authentication\Info;
use PhpIdServer\Util\Options;
use PhpIdServer\Controller\BaseController;
use Zend\InputFilter\Factory;
use PhpIdServer\Context\AuthorizeContext;
use PhpIdServer\Context\AuthorizeContextManager;
use PhpIdServer\General\Exception as GeneralException;
use PhpIdServer\Context\Exception\MissingContextException;


abstract class AbstractController extends BaseController implements AuthenticationControllerInterface
{

    /**
     * Options.
     * @var Options
     */
    protected $options = null;

    /**
     * User factory.
     * @var UserFactoryInterface
     */
    protected $userFactory = null;

    /**
     * User input filter factory.
     * @var Factory
     */
    protected $userInputFilterFactory = null;

    /**
     * Authorize context manager.
     * @var AuthorizeContextManager
     */
    protected $authorizeContextManager;


    /**
     * Sets the options.
     * 
     * @param array|\Traversable $options
     */
    public function setOptions($options)
    {
        if (! is_array($options)) {
            $options = array();
        }
        
        $this->options = new Options($options);
    }


    /**
     * Returns the value of the option.
     * 
     * @param string $name
     * @param mixed $defaultValue
     * @return mixed|null
     */
    public function getOption($name, $defaultValue = null)
    {
        return $this->options->get($name, $defaultValue);
    }


    /**
     * Returns the label of the authentication controller.
     * 
     * @return string
     */
    public function getLabel()
    {
        return $this->getOption('label', 'unknown');
    }


    /**
     * @return AuthorizeContextManager
     */
    public function getAuthorizeContextManager($throwException = false)
    {
        if (! $this->authorizeContextManager instanceof AuthorizeContextManager && $throwException) {
            throw new GeneralException\MissingDependencyException('authorize context manager');
        }
        return $this->authorizeContextManager;
    }


    /**
     * @param AuthorizeContextManager $authorizeContextManager
     */
    public function setAuthorizeContextManager(AuthorizeContextManager $authorizeContextManager)
    {
        $this->authorizeContextManager = $authorizeContextManager;
    }


    /**
     * Sets the user factory.
     * 
     * @param UserFactoryInterface $userFactory
     */
    public function setUserFactory(UserFactoryInterface $userFactory)
    {
        $this->userFactory = $userFactory;
    }


    /**
     * Returns the user factory.
     * 
     * @return UserFactoryInterface
     */
    public function getUserFactory()
    {
        return $this->userFactory;
    }


    /**
     * Sets the user input filter factory.
     * 
     * @param Factory $userInputFilterFactory
     */
    public function setUserInputFilterFactory(Factory $userInputFilterFactory)
    {
        $this->userInputFilterFactory = $userInputFilterFactory;
    }


    /**
     * Returns the user input filter factory.
     * 
     * @return Factory
     */
    public function getUserInputFilterFactory()
    {
        return $this->userInputFilterFactory;
    }


    public function indexAction()
    {
        return $this->getResponse();
    }


    /**
     * {@inheritdoc}
     * @see \PhpIdServer\Authentication\Controller\AuthenticationControllerInterface::authenticateAction()
     */
    public function authenticateAction()
    {
        $this->logInfo(sprintf("Authentication controller [%s]", $this->getLabel()));
        
        $contextManager = $this->getAuthorizeContextManager();
        $context = $contextManager->loadContext();
        if (! $context) {
            throw new MissingContextException('Missing expected context');
        }
        
        try {
            $user = $this->authenticate();
            if (! ($user instanceof UserInterface)) {
                throw new Exception\AuthenticationException('No user');
            }
            
            $context->setUser($user);
            $authenticationInfo = $this->_initSuccessAuthenticationInfo();
        } catch (Exception\InvalidUserDataException $e) {
            $this->logError(sprintf("Invalid user data exception: %s", $e->getMessage()));
            $authenticationInfo = $this->_initFailureAuthenticationInfo('invalid_user_data', $e->getMessage());
        } catch (Exception\AuthenticationException $e) {
            $this->logError(sprintf("Authentication exception: %s (%s)", $e->getError(), $e->getDescription()));
            $authenticationInfo = $this->_initFailureAuthenticationInfo($e->getError(), $e->getDescription());
        } catch (\Exception $e) {
            $this->logError(sprintf("General exception during authentication: [%s] %s", get_class($e), $e->getMessage()));
            $authenticationInfo = $this->_initFailureAuthenticationInfo('general_error');
        }
        
        $context->setAuthenticationInfo($authenticationInfo);
        $contextManager->persistContext($context);
        
        $authorizeRoute = 'php-id-server/authorize-endpoint';
        
        $this->logInfo(sprintf("redirecting back to authorize endpoint '%s'", $authorizeRoute));
        
        return $this->redirectToRoute($authorizeRoute);
    }


    /**
     * The actual authentication procedure implemented in subclasses.
     * 
     * @return User
     */
    abstract public function authenticate();


    /**
     * Initializes and returns authentication info.
     * 
     * @return \PhpIdServer\Authentication\Controller\Info
     */
    protected function _initSuccessAuthenticationInfo()
    {
        return Info::factorySuccess($this->getLabel());
    }


    protected function _initFailureAuthenticationInfo($error, $description = '')
    {
        return Info::factoryFailure($this->getLabel(), $error, $description);
    }


    protected function formatLogMessage($message)
    {
        return sprintf("CONTROLLER AUTH [%s] %s", $this->getLabel(), $message);
    }
}