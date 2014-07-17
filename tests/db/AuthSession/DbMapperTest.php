<?php
namespace InoOicServerTest\Db\AuthSession;

use InoOicServer\Test\TestCase\AbstractDatabaseTestCase;
use InoOicServer\Oic\AuthSession\Mapper\DbMapper;
use InoOicServer\Oic\AuthSession\AuthSessionHydrator;
use InoOicServer\Oic\AuthSession\AuthSession;

class DbMapperTest extends AbstractDatabaseTestCase
{

    /**
     *
     * @var DbMapper
     */
    protected $mapper;

    public function setUp()
    {
        parent::setUp();
        $this->mapper = new DbMapper($this->getDbAdapter());
    }

    public function testSaveNewAuthSession()
    {
        $authSessionData = array(
            'id' => 'new_dummy_auth_session_id',
            'method' => 'new_dummy_auth',
            'create_time' => '2014-07-12 10:00:00',
            'expiration_time' => '2014-07-12 11:00:00',
            'user_id' => 'new_testuser',
            'user_data' => 'new_fake_user_data'
        );

        $authSession = $this->mapper->getFactory()->createEntityFromData($authSessionData);
        $this->mapper->save($authSession);

        $queryTable = $this->getConnection()->createQueryTable('auth_session', 'SELECT * FROM auth_session');

        $rawTableData = $this->getRawTableData();
        $expectedTable = $this->createArrayDataSet(array(
            'auth_session' => array(
                $this->getRawTableData('auth_session', 0),
                $authSessionData
            )
        ))
            ->getTable('auth_session');

        $this->assertTablesEqual($expectedTable, $queryTable);
    }

    public function testSaveExistingAuthSession()
    {
        $authSessionData = array(
            'id' => 'dummy_auth_session_id',
            'method' => 'new_dummy_auth',
            'create_time' => '2014-07-12 10:00:00',
            'expiration_time' => '2014-07-12 11:00:00',
            'user_id' => 'new_testuser',
            'user_data' => 'new_fake_user_data'
        );

        $authSession = $this->mapper->getFactory()->createEntityFromData($authSessionData);
        $this->mapper->save($authSession);

        $queryTable = $this->getConnection()->createQueryTable('auth_session', 'SELECT * FROM auth_session');

        $rawTableData = $this->getRawTableData();
        $expectedTable = $this->createArrayDataSet(array(
            'auth_session' => array(
                $authSessionData
            )
        ))->getTable('auth_session');

        $this->assertTablesEqual($expectedTable, $queryTable);
    }

    public function testFetch()
    {
        $id = 'dummy_auth_session_id';
        $authSession = $this->mapper->fetch($id);

        $expectedData = $this->getRawTableData('auth_session', 0);

        $this->assertInstanceOf('InoOicServer\Oic\AuthSession\AuthSession', $authSession);
        $this->assertSame($expectedData['id'], $authSession->getId());
        $this->assertSame($expectedData['method'], $authSession->getMethod());
        $this->assertSame($expectedData['create_time'], $this->toDbDateTimeString($authSession->getCreateTime()));
        $this->assertSame($expectedData['expiration_time'], $this->toDbDateTimeString($authSession->getExpirationTime()));
        $this->assertSame($expectedData['user_id'], $authSession->getUserId());
        $this->assertSame($expectedData['user_data'], $authSession->getUserData());
    }

    public function testFetchNotFound()
    {
        $this->assertNull($this->mapper->fetch('non_existent_auth_session_id'));
    }

    // ------------------------
    protected function createRawTableData()
    {
        return array(
            'auth_session' => array(
                array(
                    'id' => 'dummy_auth_session_id',
                    'method' => 'dummy_auth',
                    'create_time' => '2014-07-10 10:00:00',
                    'expiration_time' => '2014-07-10 11:00:00',
                    'user_id' => 'testuser',
                    'user_data' => 'fake_user_data'
                )
            )
        );
    }
}