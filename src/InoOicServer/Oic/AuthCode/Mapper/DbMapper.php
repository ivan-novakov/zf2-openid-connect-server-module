<?php
namespace InoOicServer\Oic\AuthCode\Mapper;

use Zend\Stdlib\Hydrator\HydratorInterface;
use Zend\Db\Adapter\AdapterInterface as DbAdapter;
use InoOicServer\Oic\AuthCode\AuthCode;
use InoOicServer\Db\AbstractMapper;
use InoOicServer\Oic\EntityFactoryInterface;
use InoOicServer\Oic\AuthCode\AuthCodeFactory;
use InoOicServer\Oic\AuthCode\AuthCodeHydrator;

class DbMapper extends AbstractMapper implements MapperInterface
{

    /**
     * Constructor.
     *
     * @param DbAdapter $dbAdapter
     * @param EntityFactoryInterface $factory
     * @param HydratorInterface $hydrator
     */
    public function __construct(DbAdapter $dbAdapter, EntityFactoryInterface $factory = null, HydratorInterface $hydrator = null)
    {
        if (null === $hydrator) {
            $hydrator = new AuthCodeHydrator();
        }

        if (null === $factory) {
            $factory = new AuthCodeFactory();
            $factory->setHydrator($hydrator);
        }

        parent::__construct($dbAdapter, $factory, $hydrator);
    }

    /**
     * {@inheritdoc}
     * @see \InoOicServer\Db\AbstractMapper::existsEntity()
     */
    public function existsEntity($code)
    {
        return (null !== $this->fetch($code));
    }

    /**
     * {@inheritdoc}
     * @see \InoOicServer\Oic\AuthCode\Mapper\MapperInterface::save()
     */
    public function save(AuthCode $authCode)
    {
        $authCodeData = $this->getHydrator()->extract($authCode);
        $this->createOrUpdateEntity($authCode->getCode(), 'authorization_code', $authCodeData);
    }

    /**
     * {@inheritdoc}
     * @see \InoOicServer\Oic\AuthCode\Mapper\MapperInterface::fetch()
     */
    public function fetch($code)
    {
        $select = $this->getSql()->select();
        $select->from('authorization_code');
        $select->where('code = :code');

        return $this->executeSingleEntityQuery($select, array(
            'code' => $code
        ));
    }

    /**
     * {@inheritdoc}
     * @see \InoOicServer\Oic\AuthCode\Mapper\MapperInterface::fetchBySession()
     */
    public function fetchBySession($sessionId, $clientId, $scope = null)
    {
        $select = $this->getSql()->select();
        $select->from('authorization_code');
        $select->where(array(
            'session_id = :session_id',
            'client_id = :client_id'
        ));

        return $this->executeSingleEntityQuery($select, array(
            'session_id' => $sessionId,
            'client_id' => $clientId
        ));
    }

    public function delete($code)
    {}
}