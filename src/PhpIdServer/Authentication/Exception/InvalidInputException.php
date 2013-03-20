<?php

namespace PhpIdServer\Authentication\Exception;


class InvalidInputException extends \RuntimeException
{

    protected $validationMessages = array();


    public function __construct(array $validationMessages)
    {
        $this->validationMessages = $validationMessages;
        
        parent::__construct(sprintf("Invalid input: %s", $this->_serializeMessages($validationMessages)));
    }


    protected function _serializeMessages(array $messages)
    {
        return \Zend\Json\Json::encode($messages);
    }
}