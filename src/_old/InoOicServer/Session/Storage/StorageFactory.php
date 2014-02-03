<?php

namespace InoOicServer\Session\Storage;

use InoOicServer\General\Factory\AbstractAdapterFactory;


class StorageFactory extends AbstractAdapterFactory
{

    const CONFIG_FIELD = 'session_storage';

    const NS = __NAMESPACE__;
}