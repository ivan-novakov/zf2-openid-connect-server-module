<?php
namespace InoOicServer\Client\Validation\Rule;
use InoOicServer\Client\Client;


interface RuleInterface
{


    /**
     * Returns true if the client satisfies the rule.
     * 
     * @param Client $client
     * @return boolean
     */
    public function validate (Client $client);
}