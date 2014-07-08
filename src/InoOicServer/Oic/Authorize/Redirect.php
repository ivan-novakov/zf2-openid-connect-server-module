<?php

namespace InoOicServer\Oic\Authorize;


class Redirect
{

    const TO_AUTHENTICATION = 'to_authentication';

    const TO_RESPONSE = 'to_response';

    const TO_URL = 'to_url';

    protected $types = array(
        self::TO_AUTHENTICATION,
        self::TO_RESPONSE,
        self::TO_URL
    );

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $url;


    /**
     * Constructor. 
     * 
     * @param string $type
     * @param string $url
     */
    public function __construct($type, $url = null)
    {
        $this->setType($type);
        
        if (null !== $url) {
            $this->setUrl($url);
        }
    }


    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }


    /**
     * @param string $type
     * @throws \InvalidArgumentException
     */
    public function setType($type)
    {
        if (! in_array($type, $this->types, true)) {
            throw new \InvalidArgumentException(sprintf("Invalid type '%s'", $type));
        }
        
        $this->type = $type;
    }


    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }


    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }
}