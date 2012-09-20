<?php

namespace PhpIdServer\OpenIdConnect\Response;

use PhpIdServer\General\Exception as GeneralException;
use \PhpIdServer\User\User;


class UserInfo extends AbstractTokenResponse
{

    /**
     * The user entity containing actual data.
     *
     * @var User
     */
    protected $_userEntity = NULL;


    /**
     * Sets the user entity object.
     *
     * @param User $user
     */
    public function setUserEntity (User $user)
    {
        $this->_userEntity = $user;
    }


    /**
     * Returns the user entity object.
     *
     * @return User
     */
    public function getUserEntity ()
    {
        return $this->_userEntity;
    }


    /**
     * Returns the JSON encoded user info content.
     * 
     * @throws GeneralException\MissingDependencyException
     * @return string
     */
    protected function _createResponseContent ()
    {
        $user = $this->getUserEntity();
        if (! $user) {
            throw new GeneralException\MissingDependencyException('user entity');
        }
        
        return $this->_jsonEncode($user->toArray());
    }
}