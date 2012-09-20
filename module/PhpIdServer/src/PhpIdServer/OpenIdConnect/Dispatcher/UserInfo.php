<?php

namespace PhpIdServer\OpenIdConnect\Dispatcher;

use PhpIdServer\General\Exception as GeneralException;
use PhpIdServer\OpenIdConnect\Request;
use PhpIdServer\OpenIdConnect\Response;
use PhpIdServer\User\User;


/**
 * Dispatches a "userinfo" request.
 *
 */
class UserInfo extends AbstractDispatcher
{

    /**
     * The user info request.
     * 
     * @var Request\UserInfo
     */
    protected $_request = NULL;

    /**
     * The user info response.
     * 
     * @var Response\UserInfo
     */
    protected $_response = NULL;


    /**
     * Sets the user info request.
     * 
     * @param Request\UserInfo $request
     */
    public function setUserInfoRequest (Request\UserInfo $request)
    {
        $this->_request = $request;
    }


    /**
     * Returns the user info request.
     * 
     * @return Request\UserInfo
     */
    public function getUserInfoRequest ()
    {
        return $this->_request;
    }


    /**
     * Sets the user info response.
     * 
     * @param Response\UserInfo $response
     */
    public function setUserInfoResponse (Response\UserInfo $response)
    {
        $this->_response = $response;
    }


    /**
     * Returns the user info response.
     * 
     * @return Response\UserInfo
     */
    public function getUserInfoResponse ()
    {
        return $this->_response;
    }


    /**
     * Dispatches the user info request.
     * 
     * @throws GeneralException\MissingDependencyException
     * @return Response\UserInfo
     */
    public function dispatch ()
    {
        $userInfoRequest = $this->getUserInfoRequest();
        if (! $userInfoRequest) {
            throw new GeneralException\MissingDependencyException('userinfo request');
        }
        
        /*
         * Validate request.
         */
        if (! $userInfoRequest->isValid()) {
            _dump($request->getInvalidReasons());
            return $this->_errorResponse(Response\Token::ERROR_INVALID_REQUEST);
        }
        
        /*
         * Validate token and retrieve session.
         */
        $sessionManager = $this->getSessionManager();
        if (! $sessionManager) {
            throw new GeneralException\MissingDependencyException('session manager');
        }
        
        $accessToken = $sessionManager->getAccessToken($userInfoRequest->getAuthorizationValue());
        if (! $accessToken) {
            return $this->_errorResponse(Response\Token::ERROR_INVALID_GRANT);
        }
        
        $session = $sessionManager->getSessionByAccessToken($accessToken);
        if (! $session) {
            return $this->_errorResponse(Response\Token::ERROR_INVALID_GRANT);
        }
        
        /*
         * Retrieve user info and return response.
        */
        $user = $sessionManager->getUserFromSession($session);
        if (! $user) {
            return $this->_errorResponse(Response\Token::ERROR_INVALID_GRANT);
        }
        
        // FIXME - validate user data
        

        return $this->_validResponse($user);
    }


    /**
     * Returns the user info response with the user data.
     * 
     * @param User $user
     * @throws GeneralException\MissingDependencyException
     * @return Response\UserInfo
     */
    protected function _validResponse (User $user)
    {
        $userInfoResponse = $this->getUserInfoResponse();
        if (! $userInfoResponse) {
            throw new GeneralException\MissingDependencyException('userinfo response');
        }
        
        $userInfoResponse->setUserEntity($user);
        
        return $userInfoResponse;
    }


    /**
     * Returns an error response with the provided message.
     *
     * @param string $message
     * @throws GeneralException\MissingDependencyException
     * @return Response\Token
     */
    protected function _errorResponse ($message)
    {
        $userInfoResponse = $this->getUserInfoResponse();
        if (! $userInfoResponse) {
            throw new GeneralException\MissingDependencyException('userinfo response');
        }
        
        $userInfoResponse->setError($message);
        
        return $userInfoResponse;
    }
}