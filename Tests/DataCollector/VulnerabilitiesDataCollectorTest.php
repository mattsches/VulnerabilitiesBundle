<?php

namespace Mattsches\VulnerabilitiesBundle\Tests\DataCollector;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Mattsches\VulnerabilitiesBundle\DataCollector\VulnerabilitiesDataCollector;
use Mattsches\VulnerabilitiesBundle\Service\ComposerLockLoader;
use Mattsches\VulnerabilitiesBundle\Util\Vulnerabilities;

/**
 * Class SensiolabsSecurityDataCollectorTest
 *
 * @author Matthias Gutjahr <mail@matthias-gutjahr.de>
 * @package Mattsches\VulnerabilitiesBundle\Tests\DataCollector
 */
class VulnerabilitiesDataCollectorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var VulnerabilitiesDataCollector
     */
    protected $object;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject;
     */
    protected $checker;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->checker = $this->getMock('SensioLabs\Security\SecurityChecker');
    }

    /**
     * @test
     */
    public function testCollect()
    {
        $response = file_get_contents(__DIR__ . '/Fixtures/sensiolabs_security_response.json');
        $this->checker
            ->expects($this->any())
            ->method('check')
            ->will($this->returnValue($response));
        $loader = new ComposerLockLoader(__DIR__ . '/Fixtures/composer.lock');
        $this->object = new VulnerabilitiesDataCollector($this->checker, $loader);
        $this->object->collect(new Request(), new Response());
        /* @var Vulnerabilities $data */
        $data = $this->object->getData();
        $this->assertSame('vulnerabilities', $this->object->getName());
        $this->assertInstanceOf('Mattsches\VulnerabilitiesBundle\Util\Vulnerabilities' ,$data);
        $this->assertObjectHasAttribute('status', $data);
        $this->assertObjectHasAttribute('count', $data);
        $this->assertObjectHasAttribute('details', $data);
        $this->assertEquals(Vulnerabilities::STATUS_OK, $data->getStatus());
        $this->assertEquals(1, $data->getCount());
        $this->assertCount(1, $data->getDetails());
        $this->assertArrayHasKey('zendframework/zendframework', $data->getDetails());
        $this->assertArrayNotHasKey('foobar', $data->getDetails());
    }

    /**
     * @test
     */
    public function testCollectSafe()
    {
        $response = file_get_contents(__DIR__ . '/Fixtures/sensiolabs_security_response_safe.json');
        $this->checker
            ->expects($this->any())
            ->method('check')
            ->will($this->returnValue($response));
        $loader = new ComposerLockLoader(__DIR__ . '/Fixtures/composer_safe.lock');
        $this->object = new VulnerabilitiesDataCollector($this->checker, $loader);
        $this->object->collect(new Request(), new Response());
        /* @var Vulnerabilities $data */
        $data = $this->object->getData();
        $this->assertSame('vulnerabilities', $this->object->getName());
        $this->assertInstanceOf('Mattsches\VulnerabilitiesBundle\Util\Vulnerabilities' ,$data);
        $this->assertObjectHasAttribute('status', $data);
        $this->assertObjectHasAttribute('count', $data);
        $this->assertObjectHasAttribute('details', $data);
        $this->assertEquals(Vulnerabilities::STATUS_OK, $data->getStatus());
        $this->assertEquals(0, $data->getCount());
        $this->assertCount(0, $data->getDetails());
        $this->assertArrayNotHasKey('foobar', $data->getDetails());
    }

    /**
     * @test
     */
    public function testCollectOffline()
    {
        $this->checker
            ->expects($this->any())
            ->method('check')
            ->will($this->throwException(new \RuntimeException()));
        $loader = new ComposerLockLoader(__DIR__ . '/Fixtures/composer_safe.lock');
        $this->object = new VulnerabilitiesDataCollector($this->checker, $loader);
        $this->object->collect(new Request(), new Response());
        /* @var Vulnerabilities $data */
        $data = $this->object->getData();
        $this->assertSame('vulnerabilities', $this->object->getName());
        $this->assertInstanceOf('Mattsches\VulnerabilitiesBundle\Util\Vulnerabilities' ,$data);
        $this->assertObjectHasAttribute('status', $data);
        $this->assertObjectHasAttribute('count', $data);
        $this->assertObjectHasAttribute('details', $data);
        $this->assertEquals(Vulnerabilities::STATUS_ERR, $data->getStatus());
        $this->assertEquals(0, $data->getCount());
        $this->assertCount(0, $data->getDetails());
    }
}
