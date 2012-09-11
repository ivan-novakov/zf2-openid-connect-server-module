<?php

namespace PhpIdServer\Client;

use PhpIdServer\Entity\Entity;


class Client extends Entity
{

    const FIELD_ID = 'id';

    const FIELD_TYPE = 'type';

    const FIELD_AUTHENTICATION = 'authentication';

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
     * Returns the client ID.
     * 
     * @return string
     */
    public function getId ()
    {
        return $this->getValue(self::FIELD_ID);
    }


    /**
     * Returns the client type as defined in OAuth2 spec:
     * - confidential
     * - public
     * 
     * @return string
     */
    public function getType ()
    {
        return $this->getValue(self::FIELD_TYPE);
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