<?php

namespace InoOicServerTest\Util;

use InoOicServer\Util\DateTimeUtil;


class DateTimeUtilTest extends \PHPUnit_Framework_Testcase
{

    protected $dt;


    public function setUp()
    {
        $this->dt = new DateTimeUtil();
    }


    public function testCreateDateTime()
    {
        $dtString = '2010-09-08 07:06:05';
        $dateTime = $this->dt->createDateTime($dtString);
        $this->assertInstanceOf('DateTime', $dateTime);
        $this->assertSame($dtString, $dateTime->format('Y-m-d H:i:s'));
    }


    /**
     * @dataProvider expireDateTimeProvider
     */
    public function testCreateExpireDateTimeWithStringInterval($base, $interval, $expectedResult)
    {
        $baseDateTime = new \DateTime($base);
        $expireDateTime = $this->dt->createExpireDateTime($baseDateTime, $interval);
        $result = $expireDateTime->format('Y-m-d H:i:s');
        $this->assertSame($expectedResult, $result);
    }


    /**
     * @dataProvider expireDateTimeProvider
     */
    public function testCreateExpireDateTimeWithObjectInterval($base, $interval, $expectedResult)
    {
        $baseDateTime = new \DateTime($base);
        $interval = new \DateInterval($interval);
        $expireDateTime = $this->dt->createExpireDateTime($baseDateTime, $interval);
        $result = $expireDateTime->format('Y-m-d H:i:s');
        $this->assertSame($expectedResult, $result);
    }
    
    /*
     * -------------------
     */
    public function expireDateTimeProvider()
    {
        return array(
            array(
                '2010-09-08 07:06:05',
                'PT1H',
                '2010-09-08 08:06:05'
            ),
            array(
                '2010-09-08 07:06:05',
                'PT1M',
                '2010-09-08 07:07:05'
            ),
            array(
                '2010-09-08 07:06:05',
                'PT1S',
                '2010-09-08 07:06:06'
            ),
            array(
                '2010-09-08 07:06:05',
                'PT12H',
                '2010-09-08 19:06:05'
            ),
            array(
                '2010-09-08 07:06:05',
                'PT24H30M',
                '2010-09-09 07:36:05'
            )
        );
    }
}