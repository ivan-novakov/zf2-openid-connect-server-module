<?php
namespace InoOicServer\Oic\Session\Hash;

use InoOicServer\Crypto\Hash\PhpHash;
use InoOicServer\Oic\AuthSession\AuthSession;

class SessionHashGenerator extends PhpHash implements SessionHashGeneratorInterface
{

    /**
     * {@inheritdoc}
     * @see \InoOicServer\Oic\Session\Hash\SessionHashGeneratorInterface::generateSessionHash()
     */
    public function generateSessionHash(AuthSession $authSession, $salt, $algo = null)
    {
        $data = $authSession->getId() . $authSession->getCreateTime()->getTimestamp();

        return $this->generateHash($data, $salt, $algo);
    }
}