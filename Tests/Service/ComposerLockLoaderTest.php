<?php

namespace Mattsches\VulnerabilitiesBundle\Tests\Service;

use Mattsches\VulnerabilitiesBundle\Service\ComposerLockLoader;

/**
 * Class ComposerLockLoaderTest
 *
 * @author Matthias Gutjahr <mail@matthias-gutjahr.de>
 * @package Mattsches\VulnerabilitiesBundle\Tests\Service
 */
class ComposerLoaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ComposerLockLoader
     */
    protected $object;

    /**
     * Setup
     */
    public function setUp()
    {
        $this->object = new ComposerLockLoader(__DIR__ . '/../DataCollector/Fixtures/composer.lock');
    }

    /**
     * @test
     */
    public function testGetTempFileAndContents()
    {
        $contents = file_get_contents(__DIR__ . '/../DataCollector/Fixtures/composer.lock');
        $this->assertSame($contents, file_get_contents($this->object->getComposerLock()));
    }
}
