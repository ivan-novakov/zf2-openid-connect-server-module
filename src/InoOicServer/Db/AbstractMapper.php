<?php

namespace InoOicServer\Db;

use Zend\Db\Adapter\Adapter as DbAdapter;


abstract class AbstractMapper
{

    protected $dbAdapter;


    public function __construct(DbAdapter $dbAdapter)
    {}
}