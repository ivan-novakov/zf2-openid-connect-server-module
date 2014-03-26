<?php

namespace InoOicServer\Util\TokenGenerator;

use InoOicServer\Util\OptionsTrait;


class Simple implements TokenGeneratorInterface
{
    use OptionsTrait;

    const OPT_SECRET_SALT = 'secret_salt';

    protected $defaultOptions = array(
        self::OPT_SECRET_SALT => 'qwerty'
    );


    public function __construct(array $options = array())
    {
        $this->setOptions($options);
    }


    public function generate(array $inputValues = array())
    {
        $inputValues[] = $this->getOption(self::OPT_SECRET_SALT);

        return md5(implode('', $inputValues));
    }
}