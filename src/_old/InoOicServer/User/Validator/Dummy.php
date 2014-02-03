<?php

namespace InoOicServer\User\Validator;

use InoOicServer\User\UserInterface;


class Dummy extends AbstractValidator
{

    const OPT_VALID = 'valid';

    const OPT_REDIRECT_URI = 'redirect_uri';


    public function validate(UserInterface $user)
    {
        $isValid = (boolean) $this->getOption(self::OPT_VALID, true);
        if (! $isValid) {
            $e = new Exception\InvalidUserException(sprintf("[%s] Invalid user", get_class($this)));
            if ($redirectUri = $this->getOption(self::OPT_REDIRECT_URI)) {
                $e->setRedirectUri($redirectUri);
            }
            throw $e;
        }
        
        return true;
    }
}