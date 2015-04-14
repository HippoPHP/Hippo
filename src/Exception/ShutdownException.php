<?php

namespace HippoPHP\Hippo\Exception;

/**
 */
class ShutdownException extends \Exception implements ExceptionInterface
{
    /**
     * @var int
     */
    private $exitCode;

    /**
     * @param int $exitCode
     */
    public function __construct($exitCode)
    {
        $this->exitCode = $exitCode;
    }

    /**
     * @return int
     */
    public function getExitCode()
    {
        return $this->exitCode;
    }
}
