<?php

namespace InoOicServer\Session\Storage\Exception;

use InoOicServer\Entity\Entity;


class SaveException extends \RuntimeException
{


    public function __construct (Entity $entity,\Exception $e)
    {
        $message = sprintf("Error saving entinty %s: [%s] %s", $entity, get_class($e), $e->getMessage());
        parent::__construct($message, 0, $e);
    }
}