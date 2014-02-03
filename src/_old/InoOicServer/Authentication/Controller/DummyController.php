<?php

namespace InoOicServer\Authentication\Controller;


class DummyController extends AbstractController
{


    /**
     * {@inheritdoc}
     * @see \InoOicServer\Authentication\Controller\AbstractController::authenticate()
     */
    public function authenticate()
    {
        $user = $this->getUserFactory()->createUser($this->getOption('identity'));
        return $user;
    }
}