<?php

namespace InoOicServer\Oic\Authorize\Context\Validator;

use InoOicServer\Oic\Authorize;


/**
 * Interface for authorize context validators.
 */
interface ValidatorInterface
{


    /**
     * Returns true, if the context has passed the validation.
     * 
     * @param Authorize\Context $context
     * @return boolean
     */
    public function isValid(Authorize\Context $context);


    /**
     * Returns an array of validation fail messages.
     * 
     * @return array
     */
    public function getMessages();
}