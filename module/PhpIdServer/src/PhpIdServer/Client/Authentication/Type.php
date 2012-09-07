<?php
namespace PhpIdServer\Client\Authentication;


class Type
{

    const NONE = 'none';

    const SECRET = 'secret';

    const PKI = 'pki';

    protected static $_types = array(
        self::NONE, 
        self::SECRET, 
        self::PKI
    );


    static public function isSupported ($type)
    {
        return (in_array($type, self::$_types));
    }
}