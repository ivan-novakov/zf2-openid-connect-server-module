<?php

namespace InoOicServer\OpenIdConnect\IdToken;


class IdToken
{

    /**
     * @var array
     */
    protected $header = array();

    /**
     * @var string
     */
    protected $issuer;

    /**
     * @var string
     */
    protected $subject;

    /**
     * @var string
     */
    protected $audience;

    /**
     * @var string
     */
    protected $expires;

    /**
     * @var string
     */
    protected $issuedAt;

    /**
     * @var string
     */
    protected $nonce;

    /**
     * @var array
     */
    protected $extraFields = array();


    public function getClaims()
    {
        return array(
            'iss' => $this->getIssuer(),
            'sub' => $this->getSubject(),
            'aud' => $this->getAudience(),
            'exp' => $this->getExpires(),
            'iat' => $this->getIssuedAt(),
            'nonce' => $this->getNonce()
        );
    }


    /**
     * @return array
     */
    public function getHeader()
    {
        return $this->header;
    }


    /**
     * @param array $header
     */
    public function setHeader(array $header)
    {
        $this->header = $header;
    }


    /**
     * @return string
     */
    public function getIssuer()
    {
        return $this->issuer;
    }


    /**
     * @param string $issuer
     */
    public function setIssuer($issuer)
    {
        $this->issuer = $issuer;
    }


    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }


    /**
     * @param string $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }


    /**
     * @return string
     */
    public function getAudience()
    {
        return $this->audience;
    }


    /**
     * @param string $audience
     */
    public function setAudience($audience)
    {
        $this->audience = $audience;
    }


    /**
     * @return string
     */
    public function getExpires()
    {
        return $this->expires;
    }


    /**
     * @param string $expires
     */
    public function setExpires($expires)
    {
        $this->expires = $expires;
    }


    /**
     * @return string
     */
    public function getIssuedAt()
    {
        return $this->issuedAt;
    }


    /**
     * @param string $issuedAt
     */
    public function setIssuedAt($issuedAt)
    {
        $this->issuedAt = $issuedAt;
    }


    /**
     * @return string
     */
    public function getNonce()
    {
        return $this->nonce;
    }


    /**
     * @param string $nonce
     */
    public function setNonce($nonce)
    {
        $this->nonce = $nonce;
    }


    /**
     * @return array
     */
    public function getExtraFields()
    {
        return $this->extraFields;
    }


    /**
     * @param array $extraFields
     */
    public function setExtraFields(array $extraFields)
    {
        $this->extraFields = $extraFields;
    }
}