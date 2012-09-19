<?php

namespace PhpIdServer\OpenIdConnect\Response;

use \PhpIdServer\User\User;


class UserInfo extends AbstractResponse
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
}