<?php
namespace PhpIdServer\Client;


class Client
{

    const FIELD_ID = 'id';

    const FIELD_TYPE = 'type';

    const FIELD_AUTHENTICATION = 'authentication';

    /**
     * Client data.
     * @var \ArrayObject
     */
    protected $_data = NULL;

    /**
     * Authentication info object.
     * 
     * @var Authentication\ClientInfo
     */
    protected $_authenticationInfo = NULL;


    /**
     * Constructor.
     * 
     * @param array $data
     */
    public function __construct (Array $data = array())
    {
        $this->populate($data);
    }


    /**
     * Populates the object with data.
     * 
     * @param array $data
     */
    public function populate (Array $data)
    {
        $this->_authenticationInfo = NULL;
        $this->_data = new \ArrayObject($data);
    }


    /**
     * Returns the client ID.
     * 
     * @return string
     */
    public function getId ()
    {
        return $this->_getValue(self::FIELD_ID);
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
        return $this->_getValue(self::FIELD_TYPE);
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
            $authentication = $this->_getValue(self::FIELD_AUTHENTICATION);
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


    /**
     * Returns a value with the provided index or NULL if the is no such index.
     * 
     * @param string $ey
     * @return mixed|NULL
     */
    protected function _getValue ($key)
    {
        if ($this->_data->offsetExists($key)) {
            return $this->_data->offsetGet($key);
        }
        
        return NULL;
    }
}