<?php

namespace InoOicServer\Oic\Authorize\Response;

use InoOicServer\Oic\Error;


class ClientErrorResponse implements ResponseInterface
{

    /**
     * @var Error
     */
    protected $error;


    /**
     * @return Error
     */
    public function getError()
    {
        return $this->error;
    }


    /**
     * @param Error $error
     */
    public function setError(Error $error)
    {
        $this->error = $error;
    }
}