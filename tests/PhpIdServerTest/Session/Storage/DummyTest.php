<?php

namespace PhpIdServerTest\Session\Storage;

use PhpIdServer\Session\Storage;


class DummyTest extends AbstractStorageTestCase
{

    /**
     * Storage object.
     * 
     * @var Storage\Dummy
     */
    protected $_storage = NULL;


    public function setUp ()
    {
        $this->_storage = new Storage\Dummy();
    }
}