<?php

namespace InoOicServer\Entity;


interface EntityFactoryInterface
{


    public function createEntity (Array $values = array());
}