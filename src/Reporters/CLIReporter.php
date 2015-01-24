<?php

namespace HippoPHP\Hippo\Reporters;

use HippoPHP\Hippo\CheckResult;
use HippoPHP\Hippo\File;
use HippoPHP\Hippo\FileSystem;
use HippoPHP\Hippo\Violation;

/**
 * CLI Reporter.
 *
 * @author James Brooks <jbrooksuk@me.com>
 */
class CLIReporter implements ReporterInterface
{
    private $_firstFile;
    private $_fileSystem;
    private $_loggedSeverities;

    /**
     * @param FileSystem $fileSystem
     */
    public function __construct(FileSystem $fileSystem)
    {
        $this->_fileSystem = $fileSystem;
        $this->_loggedSeverities = Violation::getSeverities();
    }

    /**
     * @param int[] $severities
     */
    public function setLoggedSeverities(array $severities)
    {
        $this->_loggedSeverities = $severities;
    }

    /**
     * Defined by ReportInterface.
     *
     * @see ReportInterface::start()
     */
    public function start()
    {
        $this->_firstFile = true;
    }

    /**
     * Defined by ReportInterface.
     *
     * @see ReportInterface::addCheckResults()
     *
     * @param File          $file
     * @param CheckResult[] $checkResults
     */
    public function addCheckResults(File $file, array $checkResults)
    {
        if (empty($this->_loggedSeverities)) {
            return;
        }

        if ($this->_firstFile) {
            $this->_firstFile = false;
        } else {
            $this->_write(PHP_EOL);
        }

        // TODO: Only output if the file has violations?
        $this->_write('Checking '.$file->getFilename().PHP_EOL);

        $violations = [];
        foreach ($checkResults as $checkResult) {
            $violations = array_merge(
                $violations,
                $this->_getFilteredViolations($checkResult->getViolations()));
        }

        if ($violations) {
            $this->_write($file->getFilename().':'.PHP_EOL);
            $this->_write(str_repeat('-', 80).PHP_EOL);

            foreach ($violations as $violation) {
                $this->_write('Line '.$violation->getLine());

                if ($violation->getColumn() > 0) {
                    $this->_write(':'.$violation->getColumn());
                }

                $this->_write(' ('.$violation->getSeverityName().') : ');
                $this->_write($violation->getMessage().PHP_EOL);
            }
            $this->_write(PHP_EOL);
        }

        flush();
    }

    /**
     * Defined by ReportInterface.
     *
     * @see ReportInterface::finish()
     */
    public function finish()
    {
    }

    /**
     * Writes to stdout.
     *
     * @param string $content
     *
     * @return void
     */
    private function _write($content)
    {
        $this->_fileSystem->putContent('php://stdout', $content);
    }

    /**
     * @param Violation[] $violations
     *
     * @return Violation[]
     */
    private function _getFilteredViolations(array $violations)
    {
        return array_filter($violations, function ($violation) {
            return in_array($violation->getSeverity(), $this->_loggedSeverities);
        });
    }
}
