<?php

namespace PhpIdServer\User\UserInfo\Mapper;

use PhpIdServer\User\UserInterface;


/**
 * Provides methods for converting user entity to an array, i.e. maps entity properties to array values.
 */
interface MapperInterface
{


    /**
     * Returns the array representation of a user entity to be dispatched by the userinfo dispatcher.
     * 
     * @param UserInterface $user
     * @return array
     */
    public function getUserInfoData (UserInterface $user);
}