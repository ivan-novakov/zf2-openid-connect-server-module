<?php

namespace InoOicServer\Util\TokenGenerator;

use InoOicServer\Util\OptionsTrait;


/**
 * Simple token generator implementation.
 */
class Simple implements TokenGeneratorInterface
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
     * @see \InoOicServer\Util\TokenGenerator\TokenGeneratorInterface::generate()
     */
    public function generate(array $inputValues = array())
    {
        $inputValues[] = $this->getOption(self::OPT_SECRET_SALT);
        
        return md5(implode('', $inputValues));
    }
}