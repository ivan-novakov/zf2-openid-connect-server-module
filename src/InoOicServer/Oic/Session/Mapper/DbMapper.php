<?php

namespace InoOicServer\Oic\Session\Mapper;

use InoOicServer\Oic\Session\SessionHydrator;
use InoOicServer\Oic\Session\SessionFactory;
use Zend\Stdlib\Hydrator\HydratorInterface;
use InoOicServer\Oic\EntityFactoryInterface;
use Zend\Stdlib\Hydrator\ClassMethods;
use InoOicServer\Oic\Session\Session;
use InoOicServer\Db\AbstractMapper;


class DbMapper extends AbstractMapper implements MapperInterface
{


    public function __construct($dbAdapter, EntityFactoryInterface $factory = null, HydratorInterface $hydrator = null)
    {
        if (null === $factory) {
            $factory = new SessionFactory();
        }
        
        if (null === $hydrator) {
            $hydrator = new SessionHydrator();
        }
        
        parent::__construct($dbAdapter, $factory, $hydrator);
    }


    public function save(Session $session)
    {}


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


    public function fetchByCode($authCode)
    {
        $select = $this->getSql()->select();
        $select->from('session')->join('authorization_code', 'session.id = authorization_code.session_id', array());
        $select->where('authorization_code.code = :code');
        
        return $this->executeSingleEntityQuery($select, array(
            'code' => $authCode
        ));
    }


    public function fetchByAccessToken($accessToken)
    {}


    public function fetchByUserId($userId)
    {}


    public function fetchByAuthSessionId($authSessionId)
    {}
}