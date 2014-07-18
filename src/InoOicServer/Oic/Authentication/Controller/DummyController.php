<?php
namespace InoOicServer\Oic\Authentication\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use InoOicServer\Oic\Authorize\AuthorizeRequest;
use InoOicServer\Oic\Authorize\Context\Context;
use InoOicServer\Oic\User\Authentication\Status;
use InoOicServer\Oic\User\Authentication\Error;
use InoOicServer\Oic\User\UserInterface;
use InoOicServer\Oic\User\User;

class DummyController extends AbstractAuthenticationController
{

    public function authenticateAction()
    {
        $context = $this->getContextService()->loadContext();
        if (! $context instanceof Context) {
            return $this->errorResponse('missing context');
        }

        $authorizeRequest = $context->getAuthorizeRequest();
        if (! $authorizeRequest instanceof AuthorizeRequest) {
            // error
            return $this->errorResponse('missing request');
        }

        // authenticate BEGIN
        $user = new User();
        $user->setId('testuser');
        $user->setEmail('test.user@email.org');
        $user->setFirstName('Test');
        $user->setFamilyName('User');
        // authenticate END

        return $this->validResponse($user);
    }

    protected function validResponse(UserInterface $user)
    {
        $status = new Status();
        $status->setIdentity($user);
        $status->setMethod('dummy');
        $status->setTime(new \DateTime());
        $status->setAuthenticated(true);

        return $this->response($status);
    }

    protected function errorResponse($message, $description = null)
    {
        $status = new Status();
        $status->setError(new Error($message, $description));

        return $this->response($status);
    }

    protected function response(Status $status)
    {
        $contextService = $this->getContextService();
        $context = $contextService->loadContext();
        if (! $context) {
            $context = $contextService->createContext();
        }

        $context->setAuthStatus($status);
        $this->getContextService()->saveContext($context);

        return $this->redirectBack();
    }

    protected function redirectBack()
    {
        $returnUrl = $this->getAuthenticationManager()->getReturnUrl();
        $this->redirect()->toUrl($returnUrl);
    }
}