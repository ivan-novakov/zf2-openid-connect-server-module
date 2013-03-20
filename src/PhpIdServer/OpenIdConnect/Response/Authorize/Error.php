<?php

namespace PhpIdServer\OpenIdConnect\Response\Authorize;


class Error extends AbstractAuthorizeResponse
{

    /**
     * Error due to invalid client data:
     *   - user should be notified directly
     *   - user must not be redirected to the client
     * 
     * @var string
     */
    const TYPE_INVALID_CLIENT = 'type_invalid_client';

    /**
     * General authentication error, when the client is valid.
     * 
     * @var string
     */
    const TYPE_INVALID_AUTHENTICATION = 'type_invalid_authentication';
    
    /*
     * General error responses:
     *   - http://tools.ietf.org/html/draft-ietf-oauth-v2-31#section-4.2.1
     */
    const ERROR_INVALID_REQUEST = 'invalid_request';

    const ERROR_UNAUTHORIZED_CLIENT = 'unauthorized_client';

    const ERROR_ACCESS_DENIED = 'access_denied';

    const ERROR_SERVER_ERROR = 'server_error';
    
    /*
     * Invalid client responses
     */
    const ERROR_CLIENT_INVALID_REQUEST = 'client_invalid_request';

    const ERROR_CLIENT_INVALID_CLIENT = 'client_invalid_client';

    protected $_errorType = self::TYPE_INVALID_AUTHENTICATION;

    protected $_errorMessage = '';

    protected $_errorDescription = NULL;


    public function setInvalidClientError ($message, $description = NULL)
    {
        $this->_errorType = self::TYPE_INVALID_CLIENT;
        $this->setError($message, $description);
    }


    public function isInvalidClientError ()
    {
        return ($this->_errorType == self::TYPE_INVALID_CLIENT);
    }


    /**
     * Sets the error message and desrciption.
     * 
     * @param string $message
     * @param string $description
     */
    public function setError ($message, $description = NULL)
    {
        $this->_errorMessage = $message;
        $this->_errorDescription = $description;
    }


    /**
     * Returns the error message.
     * 
     * @return string
     */
    public function getErrorMessage ()
    {
        return $this->_errorMessage;
    }


    /**
     * Returns the error description.
     * 
     * @return string|null
     */
    public function getErrorDescription ()
    {
        return $this->_errorDescription;
    }


    public function getHttpResponse ()
    {
        /*
         * If the error is not client related - return back a redirect with the error message.
         */
        if (! $this->isInvalidClientError()) {
            return parent::getHttpResponse();
        }
        
        /*
         * Otherwise show an error to the user.
         */
        $this->_httpResponse->setContent(sprintf("Error: %s (%s)", $this->_errorMessage, $this->_errorDescription));
        $this->_httpResponse->setStatusCode(400);
        
        $this->_setNoCacheHeaders($this->_httpResponse);
        
        return $this->_httpResponse;
    }


    protected function _constructRedirectUri ()
    {
        return sprintf('%s?%s', parent::_constructRedirectUri(), http_build_query(array(
            'error' => $this->_errorMessage, 
            'error_description' => $this->_errorDescription
        )));
    }
}