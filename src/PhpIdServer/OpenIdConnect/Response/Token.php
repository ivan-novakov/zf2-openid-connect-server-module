<?php

namespace PhpIdServer\OpenIdConnect\Response;

use PhpIdServer\General\Exception as GeneralException;
use PhpIdServer\OpenIdConnect\Entity;


/**
 * Dispatches a token request.
 *
 */
class Token extends AbstractTokenResponse
{
    
    /*
     * Error codes base on the OIC and OAuth2 specs:
     *   - http://openid.net/specs/openid-connect-messages-1_0.html#anchor7
     *   - http://tools.ietf.org/html/draft-ietf-oauth-v2-31#section-5.2
     */
    const ERROR_INVALID_REQUEST = 'invalid_request';

    const ERROR_INVALID_CLIENT = 'invalid_client';

    const ERROR_INVALID_GRANT = 'invalid_grant';

    const ERROR_INVALID_GRANT_EXPIRED = 'invalid_grant_expired';

    const ERROR_INVALID_GRANT_NO_SESSION = 'invalid_grant_no_session';

    const ERROR_UNAUTHORIZED_CLIENT = 'unauthorized_client';

    const ERROR_UNSUPPORTED_GRANT_TYPE = 'unsupported_grant_type';

    const ERROR_INVALID_SCOPE = 'invalid_scope';

    /**
     * The token entity containing actual data.
     *
     * @var Entity\Token
     */
    protected $_tokenEntity = NULL;


    /**
     * Sets the token entity object.
     *
     * @param Entity\Token $entity
     */
    public function setTokenEntity (Entity\Token $entity)
    {
        $this->_tokenEntity = $entity;
    }


    /**
     * Returns the token entity object.
     *
     * @return Entity\Token
     */
    public function getTokenEntity ()
    {
        return $this->_tokenEntity;
    }


    /**
     * Creates and returns the response data.
     * 
     * @return string
     */
    protected function _createResponseContent ()
    {
        $tokenEntity = $this->getTokenEntity();
        if (! $tokenEntity) {
            throw new GeneralException\MissingDependencyException('token entity');
        }
        
        if (! $tokenEntity->getAccessToken()) {
            throw new Exception\MissingFieldException('access token');
        }
        
        return $this->_jsonEncode($tokenEntity->toArray());
    }
}