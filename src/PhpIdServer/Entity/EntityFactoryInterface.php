<?php

namespace PhpIdServer\Entity;


interface EntityFactoryInterface
{


    public function createEntity (Array $values = array());
}