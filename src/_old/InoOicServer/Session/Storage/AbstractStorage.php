<?php

namespace InoOicServer\Session\Storage;

use InoOicServer\Session\SessionHydrator;
use InoOicServer\Session\Token;
use InoOicServer\Util\Options;


abstract class AbstractStorage implements StorageInterface
{

    /**
     * Storage options.
     * 
     * @var Options
     */
    protected $_options = NULL;


    /**
     * Constructor.
     * 
     * @param array|Traversable $options
     */
    public function __construct ($options = array())
    {
        $this->_options = new Options($options);
    }


    /**
     * Returns the session object hydrator.
     * 
     * @return SessionHydrator
     */
    public function getSessionHydrator ()
    {
        return new SessionHydrator();
    }


    /**
     * Returns the authorization code object hydrator.
     * 
     * @return Token\AuthorizationCodeHydrator
     */
    public function getAuthorizationCodeHydrator ()
    {
        return new Token\AuthorizationCodeHydrator();
    }


    /**
     * Returns the access token object hydrator.
     * 
     * @return Token\AccessTokenHydrator
     */
    public function getAccessTokenHydrator ()
    {
        return new Token\AccessTokenHydrator();
    }
}