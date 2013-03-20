<?php
namespace PhpIdServer\Client\Registry\Storage;
use PhpIdServer\Client\Client;
use PhpIdServer\Util\Options;


class SingleJsonFileStorage implements StorageInterface
{

    /**
     * Options.
     * 
     * @var Options
     */
    protected $_options = NULL;

    /**
     * Raw client data loaded from the JSON file.
     * 
     * @var array
     */
    protected $_rawData = NULL;


    /**
     * Constructor.
     * 
     * @param array|\Traversable $options
     */
    public function __construct ($options)
    {
        $this->_options = new Options($options);
    }


    /**
     * (non-PHPdoc)
     * @see \PhpIdServer\Client\Registry\Storage\StorageInterface::getClientById()
     */
    public function getClientById ($clientId)
    {
        $rawData = $this->getRawData();
        $this->_validateData($rawData);
        
        $clientData = $this->_getClientDataById($clientId, $rawData['clients']);
        if (NULL === $clientData) {
            return NULL;
        }
        
        return new Client($clientData);
    }


    /**
     * Returns raw data from storage.
     * 
     * @return array
     */
    public function getRawData ()
    {
        if (NULL === $this->_rawData || ! is_array($this->_rawData)) {
            $this->_rawData = $this->_loadData();
        }
        
        return $this->_rawData;
    }


    /**
     * Returns raw client data for the supplied client ID.
     * 
     * @param string $clientId
     * @param array $rawClientsData
     * @return array|NULL
     */
    protected function _getClientDataById ($clientId, Array $rawClientsData)
    {
        foreach ($rawClientsData as $data) {
            if (isset($data['id']) && $clientId === $data['id']) {
                return $data;
            }
        }
        
        return NULL;
    }


    /**
     * Validates the raw data from storage.
     * 
     * @param array $data
     * @throws Exception\InvalidDataException
     */
    protected function _validateData (Array $data)
    {
        if (! isset($data['clients']) || ! is_array($data['clients'])) {
            throw new Exception\InvalidDataException("No 'clients' index found");
        }
    }


    /**
     * Returns raw client data as an array.
     * 
     * @throws Exception\LoadDataException
     * @return array
     */
    protected function _loadData ()
    {
        $jsonFile = $this->_options->get('json_file');
        if (! $jsonFile) {
            throw new Exception\LoadDataException("Missing configuration option 'json_file'");
        }
        
        if (! file_exists($jsonFile)) {
            throw new Exception\LoadDataException(sprintf("File not found '%s'", $jsonFile));
        }
        
        if (! is_file($jsonFile) || ! is_readable($jsonFile)) {
            throw new Exception\LoadDataException(sprintf("Invalid file '%s'", $jsonFile));
        }
        
        $contents = file_get_contents($jsonFile);
        if (FALSE === $contents) {
            throw new Exception\LoadDataException(sprintf("Error reading from file '%s'", $jsonFile));
        }
        
        try {
            $data = \Zend\Json\Json::decode($contents, \Zend\Json\Json::TYPE_ARRAY);
        } catch (\Exception $e) {
            throw new Exception\LoadDataException(sprintf("Exception while decoding JSON: [%s] %s", get_class($e), $e->getMessage()));
        }
        //_dump($data);
        return $data;
    }
}