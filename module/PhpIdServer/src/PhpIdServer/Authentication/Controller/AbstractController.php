<?php

namespace PhpIdServer\Authentication\Controller;

use PhpIdServer\Util\Options;
use PhpIdServer\Controller\BaseController;
use PhpIdServer\Context;


abstract class AbstractController extends BaseController
{

    /**
     * Options.
     * 
     * @var Options
     */
    protected $_options = NULL;


    public function onDispatch (\Zend\Mvc\MvcEvent $e)
    {
        $this->_options = new Options($e->getRouteMatch()
            ->getParam('options'));
        
        parent::onDispatch($e);
    }


    public function indexAction ()
    {
        return $this->getResponse();
    }


    public function authenticateAction ()
    {
        $context = $this->getServiceLocator()
            ->get('AuthorizeContext');
        
        try {
            $this->_authenticate($context);
        } catch (\Exception $e) {
            $this->_debug(sprintf("Error during authentication: [%s] %s", get_class($e), $e->getMessage()));
        }
        
        $this->_debug('redirecting back to authorize endpoint');
        return $this->plugin('redirect')
            ->toRoute('php-id-server/authorize-endpoint');
    }


    abstract protected function _authenticate (Context\AuthorizeContext $context);


    protected function _getUri ()
    {}
}