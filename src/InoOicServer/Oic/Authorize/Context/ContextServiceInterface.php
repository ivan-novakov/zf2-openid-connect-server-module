<?php

namespace InoOicServer\Oic\Authorize\Context;


interface ContextServiceInterface
{


    public function createContext();


    public function saveContext(Context $context);


    public function loadContext();


    public function clearContext();
}