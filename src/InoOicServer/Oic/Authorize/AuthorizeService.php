<?php

namespace InoOicServer\Oic\Authorize;

use InoOicServer\Oic\Authorize\Request\Request;
use Zend\Http;


class AuthorizeService
{


    public function processRequest(Request $request)
    {
        // identify and validate client (application)
        // create new Authorize\Context
        // save Authorize\Request to context
        // save client to context
        // check if there is active/valid (authentication) session, if true, skip to response endpoint (create redirect)
        // create and return the corresponding Authorize\Response
    }


    public function processResponse(ResponseInterface $response = null)
    {
        // check context
        // check client and request from context
        // check authorize request (from context), if there is active/valid (authentication) session, 
        //   if true, check for existing auth. code and create it if missing, then skip to create response
        // otherwise check user authentication and create new session and an auth. code
        // create and return the corresponding Authorize\Response
    }
}