<?php

namespace InoOicServer\Oic\Client\Mapper;

use InoOicServer\Util\Options;
use InoOicServer\Util\FileReader;
use InoOicServer\Exception\MissingOptionException;
use InoOicServer\Oic\Client;


/**
 * Client data are stored as a PHP array in a file.
 */
class PhpArrayInFile implements MapperInterface
{

    const OPT_FILE = 'file';

    /**
     * @var Options
     */
    protected $options;

    /**
     * @var FileReader
     */
    protected $fileReader;

    /**
     * @var Client\Factory\FactoryInterface
     */
    protected $clientFactory;


    /**
     * Constructor.
     * 
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->setOptions($options);
    }


    /**
     * @param array $options
     */
    public function setOptions(array $options)
    {
        $this->options = new Options($options);
    }


    /**
     * @return FileReader
     */
    public function getFileReader()
    {
        if (! $this->fileReader instanceof FileReader) {
            $this->fileReader = new FileReader();
        }
        
        return $this->fileReader;
    }


    /**
     * @param FileReder $fileReader
     */
    public function setFileReader(FileReader $fileReader)
    {
        $this->fileReader = $fileReader;
    }


    /**
     * @return Client\Factory\FactoryInterface
     */
    public function getClientFactory()
    {
        if (! $this->clientFactory instanceof Client\Factory\FactoryInterface) {
            $this->clientFactory = new Client\Factory\Factory();
        }
        
        return $this->clientFactory;
    }


    /**
     * @param Client\Factory\FactoryInterface $clientFactory
     */
    public function setClientFactory(Client\Factory\FactoryInterface $clientFactory)
    {
        $this->clientFactory = $clientFactory;
    }


    /**
     * {@inhertidoc}
     * @see \InoOicServer\Oic\Client\Repository\RepositoryInterface::getClientById()
     */
    public function getClientById($id)
    {
        $data = $this->getClientData();
        
        foreach ($data as $record) {
            if (isset($record['id']) && $id === $record['id']) {
                return $this->getClientFactory()->createClient($record);
            }
        }
        
        return null;
    }


    /**
     * Retrieves the data from the file.
     * 
     * @throws MissingOptionException
     * @return array
     */
    protected function getClientData()
    {
        $dataFile = $this->options->get(self::OPT_FILE);
        if (null === $dataFile) {
            throw new MissingOptionException(self::OPT_FILE);
        }
        
        return $this->getFileReader()->readFileAsArray($dataFile);
    }
}