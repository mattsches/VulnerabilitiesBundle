<?php

namespace Mattsches\VulnerabilitiesBundle\DataCollector;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Doctrine\Common\Cache\Cache;
use SensioLabs\Security\SecurityChecker;
use Mattsches\VulnerabilitiesBundle\Service\ComposerLockLoader;
use Mattsches\VulnerabilitiesBundle\Util\Vulnerabilities;

/**
 * Class VulnerabilitiesDataCollector
 *
 * @author Matthias Gutjahr <mail@matthias-gutjahr.de>
 * @package Mattsches\VulnerabilitiesBundle\DataCollector
 */
class VulnerabilitiesDataCollector extends DataCollector
{
    /**
     * @var SecurityChecker
     */
    protected $checker;

    /**
     * @var ComposerLockLoader
     */
    protected $loader;

    /**
     * @var Cache
     */
    protected $cache;

    /**
     * @var integer
     */
    protected $cacheLifeTime = 604800; // 1 week

    /**
     * @param \SensioLabs\Security\SecurityChecker $checker
     * @param \Mattsches\VulnerabilitiesBundle\Service\ComposerLockLoader $loader
     * @param \Doctrine\Common\Cache\Cache $cache
     */
    public function __construct(SecurityChecker $checker, ComposerLockLoader $loader, Cache $cache = null)
    {
        $this->checker = $checker;
        $this->loader = $loader;
        $this->cache = $cache;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\HttpFoundation\Response $response
     * @param \Exception $exception
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $this->data = $this->call();
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return \Mattsches\VulnerabilitiesBundle\Util\Vulnerabilities|mixed
     */
    protected function call()
    {
        $vulnerabilities = null;
        if ($this->cache !== null) {
            $vulnerabilities = $this->cache->fetch('sensiolabs_security');
            if ($vulnerabilities) {
                return $vulnerabilities;
            }
        }
        try {
            $response = $this->checker->check($this->loader->getComposerLock(), 'json');
        } catch (\RuntimeException $e) {
            return new Vulnerabilities(
                Vulnerabilities::STATUS_ERR
            );
        }
        if ($response == "[]\n") {
            $vulnerabilities = new Vulnerabilities(
                Vulnerabilities::STATUS_OK,
                0
            );
        } else {
            $response = json_decode($response, true);
            $vulnerabilities = new Vulnerabilities(
                Vulnerabilities::STATUS_OK,
                count($response),
                $response
            );
        }
        if ($this->cache !== null) {
            $this->cache->save('sensiolabs_security', $vulnerabilities, $this->getCacheLifeTime());
        }
        return $vulnerabilities;
    }

    /**
     * @param integer $cacheLifeTime
     */
    public function setCacheLifeTime($cacheLifeTime)
    {
        $this->cacheLifeTime = $cacheLifeTime;
    }

    /**
     * @return integer
     */
    public function getCacheLifeTime()
    {
        return $this->cacheLifeTime;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'vulnerabilities';
    }
}
