<?php

namespace InoOicServer\Oic\Session\Mapper;

use Zend\Db\Adapter\AdapterInterface as DbAdapter;
use Zend\Stdlib\Hydrator\HydratorInterface;
use Zend\Stdlib\Hydrator\ClassMethods;
use InoOicServer\Oic\Session\SessionHydrator;
use InoOicServer\Oic\Session\SessionFactory;
use InoOicServer\Oic\EntityFactoryInterface;
use InoOicServer\Oic\Session\Session;
use InoOicServer\Db\AbstractMapper;
use InoOicServer\Oic\EntityInterface;


/**
 * Session database mapper.
 */
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
        if (null === $factory) {
            $factory = new SessionFactory();
        }
        
        if (null === $hydrator) {
            $hydrator = new SessionHydrator();
        }
        
        parent::__construct($dbAdapter, $factory, $hydrator);
    }


    public function existsEntity($sessionId)
    {
        return (null !== $this->fetch($sessionId));
    }


    /**
     * {@inheritdoc}
     * @see \InoOicServer\Oic\Session\Mapper\MapperInterface::save()
     */
    public function save(Session $session)
    {
        $sessionData = $this->getHydrator()->extract($session);
        //$sessionData = $this->convertDateTimeValues($sessionData);
        
        $this->createOrUpdateEntity($session->getId(), 'session', $sessionData);
    }


    /**
     * {@inhertidoc}
     * @see \InoOicServer\Oic\Session\Mapper\MapperInterface::fetch()
     */
    public function fetch($id)
    {
        $select = $this->getSql()->select();
        $select->from('session');
        $select->where('id = :id');
        
        return $this->executeSingleEntityQuery($select, array(
            'id' => $id
        ));
    }


    /**
     * {@inheritdoc}
     * @see \InoOicServer\Oic\Session\Mapper\MapperInterface::fetchByCode()
     */
    public function fetchByCode($authCode)
    {
        $select = $this->getSql()->select();
        $select->from('session')->join('authorization_code', 'session.id = authorization_code.session_id', array());
        $select->where('authorization_code.code = :code');
        
        return $this->executeSingleEntityQuery($select, array(
            'code' => $authCode
        ));
    }


    /**
     * {@inheritdoc}
     * @see \InoOicServer\Oic\Session\Mapper\MapperInterface::fetchByAccessToken()
     */
    public function fetchByAccessToken($accessToken)
    {
        $select = $this->getSql()->select();
        $select->from('session')->join('access_token', 'id = session_id', array());
        $select->where('token = :token');
        
        return $this->executeSingleEntityQuery($select, array(
            'token' => $accessToken
        ));
    }


    /**
     * {@inheritdoc}
     * @see \InoOicServer\Oic\Session\Mapper\MapperInterface::fetchByUserId()
     */
    public function fetchByUserId($userId)
    {
        $select = $this->getSql()->select();
        $select->from('session')->join('auth_session', 'session.auth_session_id = auth_session.id', array());
        $select->where('user_id = :user_id');
        
        return $this->executeSingleEntityQuery($select, array(
            'user_id' => $userId
        ));
    }


    /**
     * {@inheritdoc}
     * @see \InoOicServer\Oic\Session\Mapper\MapperInterface::fetchByAuthSessionId()
     */
    public function fetchByAuthSessionId($authSessionId)
    {
        $select = $this->getSql()->select();
        $select->from('session')->join('auth_session', 'session.auth_session_id = auth_session.id', array());
        $select->where('auth_session.id = :auth_session_id');
        
        return $this->executeSingleEntityQuery($select, array(
            'auth_session_id' => $authSessionId
        ));
    }
}