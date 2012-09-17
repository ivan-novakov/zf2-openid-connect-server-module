<?php

namespace PhpIdServer\Client;

use PhpIdServer\Entity\Entity;


/**
 * Entity representing the client application.
 *
 * @method string getId()
 * @method string getType()
 * @method string getRedirectUri()
 */
class Client extends Entity
{

    const FIELD_ID = 'id';

    const FIELD_TYPE = 'type';

    const FIELD_AUTHENTICATION = 'authentication';

    const FIELD_REDIRECT_URI = 'redirect_uri';

    protected $_fields = array(
        self::FIELD_ID, 
        self::FIELD_TYPE, 
        self::FIELD_AUTHENTICATION, 
        self::FIELD_REDIRECT_URI
    );

    protected $_idField = self::FIELD_ID;

    /**
     * Authentication info object.
     * 
     * @var Authentication\ClientInfo
     */
    protected $_authenticationInfo = NULL;


    /**
     * Populates the object with data.
     * 
     * @param array $data
     */
    public function populate (Array $data)
    {
        $this->_authenticationInfo = NULL;
        parent::populate($data);
    }





    /**
     * Returns the authentication info for the client.
     * 
     * @throws Exception\IncompleteAuthenticationInfoException
     * @return Authentication\ClientInfo
     */
    public function getAuthenticationInfo ()
    {
        if (! ($this->_authenticationInfo instanceof Authentication\ClientInfo)) {
            $authentication = $this->getValue(self::FIELD_AUTHENTICATION);
            if (! $authentication || ! isset($authentication['type'])) {
                throw new Exception\IncompleteAuthenticationInfoException();
            }
            
            $options = array();
            if (isset($authentication['options']) && is_array($authentication['options'])) {
                $options = $authentication['options'];
            }
            
            $this->_authenticationInfo = new Authentication\ClientInfo($authentication['type'], $options);
        }
        
        return $this->_authenticationInfo;
    }
}