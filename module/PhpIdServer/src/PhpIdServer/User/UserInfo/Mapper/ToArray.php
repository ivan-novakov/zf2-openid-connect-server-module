<?php

namespace PhpIdServer\User\UserInfo\Mapper;

use PhpIdServer\User\UserInterface;


class ToArray extends AbstractMapper
{


    public function getUserInfoData (UserInterface $user)
    {
        return $user->toArray();
    }
}