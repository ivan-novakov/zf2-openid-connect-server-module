<?php

namespace InoOicServer\Util;

use Zend\Http;


class CookieManager
{


    /**
     * Extracts the required cookie from the HTTP request.
     * 
     * @param Http\Request $httpRequest
     * @param string $name
     * @return string|null
     */
    public function getCookieValue(Http\Request $httpRequest, $name)
    {
        $value = null;
        $cookieHeader = $httpRequest->getCookie();
        if ($cookieHeader instanceof Http\Header\Cookie) {
            $value = $cookieHeader->offsetGet($name);
        }
        
        return $value;
    }
}