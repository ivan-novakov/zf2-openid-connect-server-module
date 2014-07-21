<?php
namespace InoOicServer\Oic\Authorize;

interface AuthorizeRequestFactoryInterface
{

    /**
     * Creates an authorize request entity based on the provided values.
     *
     * @param array $values
     */
    public function createRequest(array $values);
}