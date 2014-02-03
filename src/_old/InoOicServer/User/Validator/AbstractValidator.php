<?php

namespace InoOicServer\User\Validator;

use Zend\Session;
use InoOicServer\General\Component;


abstract class AbstractValidator extends Component implements ValidatorInterface
{

    /**
     * Session container.
     * @var Session\Container
     */
    protected $sessionContainer;


    /**
     * @return Session\Container
     */
    public function getSessionContainer()
    {
        return $this->sessionContainer;
    }


    /**
     * @param Session\Container $sessionContainer
     */
    public function setSessionContainer(Session\Container $sessionContainer)
    {
        $this->sessionContainer = $sessionContainer;
    }
}