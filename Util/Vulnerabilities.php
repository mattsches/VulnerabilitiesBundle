<?php

namespace Mattsches\VulnerabilitiesBundle\Util;

/**
 * Class Vulnerabilities
 * @author Matthias Gutjahr <mail@matthias-gutjahr.de>
 * @package Mattsches\VulnerabilitiesBundle\Util
 */
class Vulnerabilities
{
    /**
     *
     */
    const STATUS_OK = 1;
    /**
     *
     */
    const STATUS_ERR = 0;

    /**
     * @var
     */
    protected $status;
    /**
     * @var
     */
    protected $count;
    /**
     * @var array
     */
    protected $details = array();

    /**
     * @param $status
     * @param $count
     * @param array $details
     */
    public function __construct($status, $count = 0, $details = array())
    {
        $this->setStatus($status);
        $this->setCount($count);
        $this->setDetails($details);
    }

    /**
     * @param $count
     */
    public function setCount($count)
    {
        $this->count = $count;
    }

    /**
     * @return mixed
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @param $details
     */
    public function setDetails($details)
    {
        $this->details = $details;
    }

    /**
     * @return array
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * @param $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }
}
