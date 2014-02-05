<?php

namespace InoOicServer\Util;

use InoOicServer\Exception\InvalidFileFormatException;
use InoOicServer\Exception\InvalidFileException;


/**
 * File reading operations.
 */
class FileReader
{


    /**
     * Tries to read a filename with a PHP array defined in it.
     * 
     * @param string $filename
     * @throws InvalidFileException
     * @throws InvalidFileFormatException
     * @return array
     */
    public function readFileAsArray($filename)
    {
        $this->checkFile($filename);
        
        $data = require $filename;
        
        if (! is_array($data)) {
            throw new InvalidFileFormatException(sprintf("Invalid file format in '%s', expected PHP array", $filename));
        }
        
        return $data;
    }


    public function checkFile($filename)
    {
        if (! file_exists($filename)) {
            throw new InvalidFileException(sprintf("Non-existent file '%s'", $filename));
        }
        
        if (! is_readable($filename)) {
            throw new InvalidFileException(sprintf("Cannot read file '%s'", $filename));
        }
        
        if (! is_file($filename)) {
            throw new InvalidFileException(sprintf("Not a file '%s'", $filename));
        }
    }
}