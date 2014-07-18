<?php
namespace InoOicServer\Oic\Authentication\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use InoOicServer\Oic\Authorize\Context\ContextServiceInterface;
use InoOicServer\Oic\User\Authentication\Manager;

abstract class AbstractAuthenticationController extends AbstractActionController
{

    /**
     * @var ContextServiceInterface
     */
    protected $contextService;

    /**
     * @var Manager
     */
    protected $authenticationManager;

    public function __construct(ContextServiceInterface $contextService, Manager $authenticationManager)
    {
        $this->setContextService($contextService);
        $this->setAuthenticationManager($authenticationManager);
    }

    /**
     * @return ContextServiceInterface
     */
    public function getContextService()
    {
        return $this->contextService;
    }

    /**
     * @param ContextServiceInterface $contextService
     */
    public function setContextService(ContextServiceInterface $contextService)
    {
        $this->contextService = $contextService;
    }

    /**
     * @return Manager
     */
    public function getAuthenticationManager()
    {
        return $this->authenticationManager;
    }

    /**
     * @param Manager $authenticationManager
     */
    public function setAuthenticationManager(Manager $authenticationManager)
    {
        $this->authenticationManager = $authenticationManager;
    }

    abstract public function authenticateAction();
}