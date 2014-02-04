<?php

namespace InoOicServer\Oic\Authorize;

use Zend\Http;


class AuthorizeService
{


    public function initialDispatch(Http\Request $httpRequest)
    {
        // validate HTTP request
        // identify and validate client (application)
        // check if there is active authorize context, if true, skip to response endpoint (create redirect)
        // create new Authorize\Context
        // create Authorize\Request
        // save Authorize\Request to context
        // save client to context
        // create and return redirect to the authentication endpoint
    }


    public function dispatch(Http\Request $httpRequest)
    {
        // check context
        // check user authentication, client and request from context
        // check for existing session and auth. code, if true - re-use them
        // otherwise create new session and an auth. code
        // create Authorize\Response
        // create the corresponding HTTP response
    }
}