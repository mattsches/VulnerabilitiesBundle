<?php

namespace Mattsches\VulnerabilitiesBundle\Service;

/**
 * Class ComposerLockLoader
 *
 * @author Matthias Gutjahr <mail@matthias-gutjahr.de>
 * @package Mattsches\VulnerabilitiesBundle\Service
 */
class ComposerLockLoader
{
    /**
     * @var string
     */
    protected $composerLock;

    /**
     * @param $composerLock
     */
    public function __construct($composerLock)
    {
        if (file_exists($composerLock)) {
            $this->composerLock = $composerLock;
        }
    }

    /**
     * @param string $composerLock
     */
    public function setComposerLock($composerLock)
    {
        $this->composerLock = $composerLock;
    }

    /**
     * @return string
     */
    public function getComposerLock()
    {
        return $this->composerLock;
    }
}
