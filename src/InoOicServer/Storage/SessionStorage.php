<?php

namespace InoOicServer\Storage;

use InoOicServer\Session\Session;
use InoOicServer\Storage\Hydrator\SessionHydrator;


class SessionStorage extends AbstractStorage implements SessionStorageInterface
{

    /**
     * @var SessionHydrator
     */
    protected $hydrator;


    /**
     * @return SessionHydrator
     */
    public function getHydrator()
    {
        return $this->hydrator;
    }


    /**
     * @param SessionHydrator $hydrator
     */
    public function setHydrator(SessionHydrator $hydrator)
    {
        $this->hydrator = $hydrator;
    }


    /**
     * {@inhertidoc}
     * @see \InoOicServer\Storage\SessionStorageInterface::loadSession()
     */
    public function loadSession($sessionId)
    {
        $sql = $this->createSql();
        
        $select = $sql->select($this->getSessionTableName());
        $select->where(array(
            Session::FIELD_ID => $sessionId
        ));
        
        $result = $this->executeSqlQuery($select);
        
        if (! $result->count()) {
            return NULL;
        }
        
        $data = (array) $result->current();
        $session = $this->createSession();
        return $this->getHydrator()->hydrateObject($data, $session);
    }


    /**
     * {@inhertidoc}
     * @see \InoOicServer\Storage\SessionStorageInterface::saveSession()
     */
    public function saveSession(Session $session)
    {
        try {
            $sql = $this->getSql();
            $insert = $sql->insert($this->getSessionTableName());
            
            $data = $this->getHydrator()->extractData($session);
            $insert->values($data);
            
            $result = $this->executeSqlQuery($insert);
        } catch (\Exception $e) {
            throw new Exception\SaveException($session, $e);
        }
    }


    /**
     * Creates an empty session entity.
     * 
     * @return \InoOicServer\Storage\Session
     */
    public function createSession()
    {
        return new Session();
    }


    /**
     * Returns the name of the "session" table.
     * 
     * @return string
     */
    protected function getSessionTableName()
    {
        return $this->_options->get(self::OPT_SESSION_TABLE);
    }
}