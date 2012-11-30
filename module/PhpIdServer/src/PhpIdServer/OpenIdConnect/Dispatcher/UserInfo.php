<?php

namespace PhpIdServer\OpenIdConnect\Dispatcher;

use PhpIdServer\General\Exception as GeneralException;
use PhpIdServer\OpenIdConnect\Request;
use PhpIdServer\OpenIdConnect\Response;
use PhpIdServer\User\UserInterface;
use PhpIdServer\User;


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
    protected $_request = null;

    /**
     * The user info response.
     * 
     * @var Response\UserInfo
     */
    protected $_response = null;

    /**
     * The userinfo mapper.
     * 
     * @var User\UserInfo\Mapper\MapperInterface
     */
    protected $_userInfoMapper = null;


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
     * Sets the userinfo mapper.
     * 
     * @param User\UserInfo\Mapper\MapperInterface $mapper
     */
    public function setUserInfoMapper (User\UserInfo\Mapper\MapperInterface $mapper)
    {
        $this->_userInfoMapper = $mapper;
    }


    /**
     * Returns the userinfo mapper.
     * 
     * @return User\UserInfo\Mapper\MapperInterface
     */
    public function getUserInfoMapper ()
    {
        if (null === $this->_userInfoMapper) {
            $this->_userInfoMapper = new User\UserInfo\Mapper\ToArray();
        }
        
        return $this->_userInfoMapper;
    }


    /**
     * Dispatches the user info request.
     * 
     * @throws GeneralException\MissingDependencyException
     * @return Response\UserInfo
     */
    public function dispatch ()
    {
        $request = $this->getUserInfoRequest();
        if (! $request) {
            throw new GeneralException\MissingDependencyException('userinfo request');
        }
        
        /*
         * Validate request.
         */
        if (! $request->isValid()) {
            return $this->_errorResponse(Response\UserInfo::ERROR_INVALID_REQUEST, sprintf("Reasons: %s", implode(', ', $request->getInvalidReasons())));
        }
        
        /*
         * Validate token and retrieve session.
         */
        $sessionManager = $this->getSessionManager(true);
        
        $accessToken = $sessionManager->getAccessToken($request->getAuthorizationValue());
        if (! $accessToken) {
            return $this->_errorResponse(Response\UserInfo::ERROR_INVALID_TOKEN_NOT_FOUND, 'No such access token');
        }
        
        if ($accessToken->isExpired()) {
            return $this->_errorResponse(Response\UserInfo::ERROR_INVALID_TOKEN_EXPIRED, 'Expired access token');
        }
        
        $session = $sessionManager->getSessionByAccessToken($accessToken);
        if (! $session) {
            return $this->_errorResponse(Response\UserInfo::ERROR_INVALID_TOKEN_NO_SESSION, 'No session associated with the access token');
        }
        
        /*
         * Retrieve user info and return response.
        */
        $user = $sessionManager->getUserFromSession($session);
        if (! $user) {
            return $this->_errorResponse(Response\UserInfo::ERROR_INVALID_TOKEN_NO_USER_DATA, 'Could not extract user data');
        }
        
        // FIXME - validate user data
        

        return $this->_validResponse($user);
    }


    /**
     * Returns the user info response with the user data.
     * 
     * @param UserInterface $user
     * @throws GeneralException\MissingDependencyException
     * @return Response\UserInfo
     */
    protected function _validResponse (UserInterface $user)
    {
        $userInfoResponse = $this->getUserInfoResponse();
        if (! $userInfoResponse) {
            throw new GeneralException\MissingDependencyException('userinfo response');
        }
        
        $userInfoResponse->setUserData($this->getUserInfoMapper()
            ->getUserInfoData($user));
        
        return $userInfoResponse;
    }


    /**
     * Returns an error response with the provided message.
     *
     * @param string $message
     * @throws GeneralException\MissingDependencyException
     * @return Response\Token
     */
    protected function _errorResponse ($message, $description = NULL)
    {
        $userInfoResponse = $this->getUserInfoResponse();
        if (! $userInfoResponse) {
            throw new GeneralException\MissingDependencyException('userinfo response');
        }
        
        $userInfoResponse->setError($message, $description);
        
        return $userInfoResponse;
    }
}