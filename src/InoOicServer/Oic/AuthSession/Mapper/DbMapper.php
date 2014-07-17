<?php

namespace InoOicServer\Oic\AuthSession\Mapper;

use Zend\Db\Adapter\AdapterInterface as DbAdapter;
use InoOicServer\Oic\AuthSession\AuthSession;
use InoOicServer\Oic\AuthSession\AuthSessionFactory;
use InoOicServer\Oic\AuthSession\AuthSessionHydrator;
use InoOicServer\Db\AbstractMapper;
use InoOicServer\Oic\EntityInterface;


class DbMapper extends AbstractMapper implements MapperInterface
{


    /**
     * Constructor.
     *
     * @param DbAdapter $dbAdapter
     * @param EntityFactoryInterface $factory
     * @param HydratorInterface $hydrator
     */
    public function __construct(DbAdapter $dbAdapter, EntityFactoryInterface $factory = null,
        HydratorInterface $hydrator = null)
    {
        if (null === $hydrator) {
            $hydrator = new AuthSessionHydrator();
        }

        if (null === $factory) {
            $factory = new AuthSessionFactory();
            $factory->setHydrator($hydrator);
        }

        parent::__construct($dbAdapter, $factory, $hydrator);
    }


    /**
     * {@inheritdoc}
     * @see \InoOicServer\Db\AbstractMapper::existsEntity()
     */
    public function existsEntity($authSessionId)
    {
        return (null !== $this->fetch($authSessionId));
    }


    /**
     * {@inheritdoc}
     * @see \InoOicServer\Oic\AuthSession\Mapper\MapperInterface::save()
     */
    public function save(AuthSession $authSession)
    {
        $authSessionData = $this->getHydrator()->extract($authSession);

        $this->createOrUpdateEntity($authSession->getId(), 'auth_session', $authSessionData);
    }


    /**
     * {@inheritdoc}
     * @see \InoOicServer\Oic\AuthSession\Mapper\MapperInterface::fetch()
     */
    public function fetch($id)
    {
        $select = $this->getSql()->select();
        $select->from('auth_session');
        $select->where('id = :id');

        return $this->executeSingleEntityQuery($select, array(
            'id' => $id
        ));
    }
}