<?php

namespace PhpIdServer\User\Serializer\Exception;


class AdapterUnserializeException extends \RuntimeException
{


    public function __construct (\Exception $e)
    {
        parent::__construct(sprintf("Unserialize exception: [%s] %s", get_class($e), $e->getMessage()));
    }
}