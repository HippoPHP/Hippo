<?php

namespace HippoPHP\Hippo\Checks;

use HippoPHP\Hippo\CheckContext;
use HippoPHP\Hippo\CheckResult;
use HippoPHP\Hippo\Config\Config;
use HippoPHP\Hippo\File;
use HippoPHP\Hippo\Violation;

/**
 * All checks will extend from this Abstract class.
 *
 * @author James Brooks <jbrooksuk@me.com>
 */
abstract class AbstractCheck implements CheckInterface
{
    /**
     * Severity that the check will produce.
     *
     * @var int
     */
    protected $severity = Violation::SEVERITY_ERROR;

    /**
     * Result of the check.
     *
     * @var \HippoPHP\Hippo\CheckResult
     */
    protected $checkResult;

    /**
     * Runs checks on the file.
     *
     * @param \HippoPHP\Hippo\CheckContext  $checkContext
     * @param \HippoPHP\Hippo\Config\Config $config
     *
     * @return \HippoPHP\Hippo\CheckResult
     */
    public function checkFile(CheckContext $checkContext, Config $config)
    {
        $this->checkResult = new CheckResult();
        $this->checkResult->setFile($checkContext->getFile());
        $this->checkFileInternal($checkContext, $config);

        return $this->checkResult;
    }

    /**
     * Set the severity level of the check.
     *
     * @param int $severity
     *
     * @return \HippoPHP\Hippo\AbstractCheck
     */
    public function setSeverity($severity)
    {
        if (null !== ($severity = Violation::getSeverityFromString($severity))) {
            $this->severity = $severity;
        }

        return $this;
    }

    /**
     * Add a violation to the current file.
     *
     * @param \HippoPHP\Hippo\File $file
     * @param int                  $line
     * @param int                  $column
     * @param string               $message
     * @param int                  $severity
     */
    protected function addViolation(File $file, $line, $column, $message, $severity = null)
    {
        if ($severity === null) {
            $severity = $this->severity;
        }

        $this->checkResult->addViolation(new Violation($file, $line, $column, $severity, $message));
    }

    abstract protected function checkFileInternal(CheckContext $checkContext, Config $config);
}
