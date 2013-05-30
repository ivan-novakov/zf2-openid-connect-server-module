<?php

namespace PhpIdServer\Authentication\Controller;


class DummyController extends AbstractController
{


    /**
     * {@inheritdoc}
     * @see \PhpIdServer\Authentication\Controller\AbstractController::authenticate()
     */
    public function authenticate()
    {
        $user = $this->getUserFactory()->createUser($this->getOption('identity'));
        return $user;
    }
}