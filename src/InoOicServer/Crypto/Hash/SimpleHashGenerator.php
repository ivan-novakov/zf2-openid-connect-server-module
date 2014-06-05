<?php

namespace InoOicServer\Crypto\Hash;

use InoOicServer\Util\OptionsTrait;


/**
 * Simple token generator implementation.
 */
class SimpleHashGenerator implements HashGeneratorInterface
{
    use OptionsTrait;

    const OPT_SECRET_SALT = 'secret_salt';

    /**
     * @var array
     */
    protected $defaultOptions = array(
        self::OPT_SECRET_SALT => 'qwerty'
    );


    /**
     * Constructor.
     * 
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        $this->setOptions($options);
    }


    /**
     * {@inheritdoc}
     * @see \InoOicServer\Crypto\Hash\HashGeneratorInterface::generate()
     */
    public function generate(array $inputValues = array())
    {
        $inputValues[] = $this->getOption(self::OPT_SECRET_SALT);
        
        return md5(implode('', $inputValues));
    }
}