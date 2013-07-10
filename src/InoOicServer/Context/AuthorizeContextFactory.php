<?php

namespace InoOicServer\Context;


class AuthorizeContextFactory
{


    public function createContext()
    {
        return new AuthorizeContext();
    }
}