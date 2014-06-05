<?php

namespace InoOicServer\Oic\AuthSession;

use InoOicServer\Oic\User;
use InoOicServer\Oic\User\UserInterface;
use InoOicServer\Crypto\Hash\HashGeneratorInterface;
use InoOicServer\Util\OptionsTrait;
use InoOicServer\Util\DateTimeUtil;


class AuthSessionFactory implements AuthSessionFactoryInterface
{
    
    use OptionsTrait;

    /**
     * Salt to be used in auth session ID generation.
     */
    const OPT_SALT = 'salt';

    /**
     * Session age in seconds.
     */
    const OPT_AGE = 'age';

    /**
     * @var HashGeneratorInterface
     */
    protected $hashGenerator;

    /**
     * @var DateTimeUtil
     */
    protected $dateTimeUtil;

    /**
     * @var array
     */
    protected $defaultOptions = array(
        self::OPT_SALT => 'some secret salt - CHANGE IT!',
        self::OPT_AGE => 3600
    );


    /**
     * Constructor.
     * 
     * @param HashGeneratorInterface $hashGenerator
     * @param array $options
     */
    public function __construct(HashGeneratorInterface $hashGenerator, array $options = array())
    {
        $this->setHashGenerator($hashGenerator);
        $this->setOptions($options);
    }


    /**
     * @return HashGeneratorInterface
     */
    public function getHashGenerator()
    {
        return $this->hashGenerator;
    }


    /**
     * @param HashGeneratorInterface $hashGenerator
     */
    public function setHashGenerator(HashGeneratorInterface $hashGenerator)
    {
        $this->hashGenerator = $hashGenerator;
    }


    /**
     * @return DateTimeUtil
     */
    public function getDateTimeUtil()
    {
        if (! $this->dateTimeUtil instanceof DateTimeUtil) {
            $this->dateTimeUtil = new DateTimeUtil();
        }
        
        return $this->dateTimeUtil;
    }


    /**
     * @param DateTimeUtil $dateTimeUtil
     */
    public function setDateTimeUtil(DateTimeUtil $dateTimeUtil)
    {
        $this->dateTimeUtil = $dateTimeUtil;
    }


    public function createAuthSession(User\Authentication\Status $authStatus, $age, $salt)
    {
        if (! $authStatus->isAuthenticated()) {
            throw new Exception\UnauthenticatedUserException('Unauthenticated user - cannot create auth session');
        }
        
        $user = $authStatus->getIdentity();
        if (! $user instanceof UserInterface) {
            throw new Exception\UnknownIdentityException('Missing user identity in authentication status');
        }
        
        $createTime = $authStatus->getTime();
        
        $authSessionId = $this->getHashGenerator()->generate(array(
            $user->getId(),
            $createTime->getTimestamp(),
            $salt
        ));
        
        $authSession = new AuthSession();
        $authSession->setId($authSessionId);
        $authSession->setMethod($authStatus->getMethod());
        
        $authSession->setCreateTime($createTime);
        $authSession->setExpirationTime($this->getDateTimeUtil()
            ->createExpireDateTime($createTime, 'PT' . $age . 'S'));
        $authSession->setUser($user);
        
        return $authSession;
    }
}