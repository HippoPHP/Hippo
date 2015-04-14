<?php

namespace HippoPHP\Hippo;

use HippoPHP\Hippo\Exception\ShutdownException;

/**
 */
class Environment
{
    /**
     * @var int
     */
    private $exitCode = 0;

    /**
     * @return int
     */
    public function getExitCode()
    {
        return $this->exitCode;
    }

    /**
     * @param int $exitCode
     *
     * @return void
     */
    public function setExitCode($exitCode)
    {
        $this->exitCode = $exitCode;
    }

    /**
     * @return void
     */
    public function shutdown()
    {
        throw new ShutdownException($this->exitCode);
    }
}
