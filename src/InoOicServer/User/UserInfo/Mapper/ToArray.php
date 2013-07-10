<?php

namespace InoOicServer\User\UserInfo\Mapper;

use InoOicServer\User\UserInterface;


class ToArray extends AbstractMapper
{


    public function getUserInfoData (UserInterface $user)
    {
        return $user->toArray();
    }
}