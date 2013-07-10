<?php

namespace PhpIdServer\Client;

use PhpIdServer\Entity\Entity;


/**
 * Entity representing the client application.
 *
 * @copyright (c) 2013 Ivan Novakov (http://novakov.cz/)
 * @license http://debug.cz/license/freebsd
 * 
 * @method string getId()
 * @method string getType()
 * @method string|array getRedirectUri()
 * @method string getUserAuthenticationHandler()
 */
class Client extends Entity
{

    const FIELD_ID = 'id';

    const FIELD_TYPE = 'type';

    const FIELD_AUTHENTICATION = 'authentication';

    const FIELD_REDIRECT_URI = 'redirect_uri';

    const FIELD_USER_AUTHENTICATION_HANDLER = 'user_authentication_handler';

    const AUTH_FIELD_METHOD = 'method';

    const AUTH_FIELD_OPTIONS = 'options';

    protected $_fields = array(
        self::FIELD_ID,
        self::FIELD_TYPE,
        self::FIELD_AUTHENTICATION,
        self::FIELD_REDIRECT_URI,
        self::FIELD_USER_AUTHENTICATION_HANDLER
    );

    protected $_idField = self::FIELD_ID;

    /**
     * Authentication info object.
     * 
     * @var Authentication\Info
     */
    protected $_authenticationInfo = NULL;


    /**
     * Populates the object with data.
     * 
     * @param array $data
     */
    public function populate(Array $data)
    {
        $this->_authenticationInfo = NULL;
        parent::populate($data);
    }


    /**
     * Returns the authentication info for the client.
     * 
     * @throws Exception\IncompleteAuthenticationInfoException
     * @return Authentication\Info
     */
    public function getAuthenticationInfo()
    {
        if (! ($this->_authenticationInfo instanceof Authentication\Info)) {
            $authentication = $this->getValue(self::FIELD_AUTHENTICATION);
            if (! $authentication || ! isset($authentication[self::AUTH_FIELD_METHOD])) {
                throw new Exception\IncompleteAuthenticationInfoException(sprintf("Missing configuration field: '%s'", self::AUTH_FIELD_METHOD));
            }
            
            $options = array();
            if (isset($authentication[self::AUTH_FIELD_OPTIONS]) && is_array($authentication[self::AUTH_FIELD_OPTIONS])) {
                $options = $authentication[self::AUTH_FIELD_OPTIONS];
            }
            
            $this->_authenticationInfo = new Authentication\Info($authentication[self::AUTH_FIELD_METHOD], $options);
        }
        
        return $this->_authenticationInfo;
    }
    
    
    public function hasRedirectUri($redirectUri)
    {
        // FIXME - string comparison - consider security
        return (in_array($redirectUri, $this->getRedirectUri()));
    }
}