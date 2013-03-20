<?php

namespace PhpIdServer\User\Serializer\Exception;


class AdapterSerializeException extends \RuntimeException
{


    public function __construct (\Exception $e)
    {
        parent::__construct(sprintf("Serialize exception: [%s] %s", get_class($e), $e->getMessage()));
    }
}