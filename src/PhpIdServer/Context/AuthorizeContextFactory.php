<?php

namespace PhpIdServer\Context;


class AuthorizeContextFactory
{


    public function createContext()
    {
        return new AuthorizeContext();
    }
}