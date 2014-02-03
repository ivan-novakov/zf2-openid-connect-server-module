<?php

namespace InoOicServer\User\Validator;

use InoOicServer\User\UserInterface;


/**
 * A validator consisting of a list of validators run one after another.
 */
class ChainValidator extends AbstractValidator
{

    /**
     * A list of chained validators to use.
     * @var array
     */
    protected $validators = array();


    /**
     * Adds a validator to the chain.
     * 
     * @param ValidatorInterface $validator
     */
    public function addValidator(ValidatorInterface $validator)
    {
        $this->validators[] = $validator;
    }


    /**
     * Returns all validators.
     * 
     * @return array
     */
    public function getValidators()
    {
        return $this->validators;
    }


    /**
     * {@inheritdoc}
     * @see \InoOicServer\User\Validator\ValidatorInterface::validate()
     */
    public function validate(UserInterface $user)
    {
        foreach ($this->validators as $validator) {
            /* @var $validator ValidatorInterface */
            $validator->validate($user);
        }
    }
}