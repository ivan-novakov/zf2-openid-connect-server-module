<?php
namespace InoOicServer\Context\Storage;


interface StorageInterface
{


    public function load ();


    public function save ($context);


    public function clear ();
}