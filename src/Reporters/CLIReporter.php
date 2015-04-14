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
    private $firstFile;
    private $fileSystem;
    private $loggedSeverities;

    /**
     * @param FileSystem $fileSystem
     */
    public function __construct(FileSystem $fileSystem)
    {
        $this->fileSystem = $fileSystem;
        $this->loggedSeverities = Violation::getSeverities();
    }

    /**
     * @param int[] $severities
     */
    public function setLoggedSeverities(array $severities)
    {
        $this->loggedSeverities = $severities;
    }

    /**
     * Defined by ReportInterface.
     *
     * @see ReportInterface::start()
     */
    public function start()
    {
        $this->firstFile = true;
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
        if (empty($this->loggedSeverities)) {
            return;
        }

        if ($this->firstFile) {
            $this->firstFile = false;
        } else {
            $this->write(PHP_EOL);
        }

        // TODO: Only output if the file has violations?
        $this->write('Checking '.$file->getFilename().PHP_EOL);

        $violations = [];
        foreach ($checkResults as $checkResult) {
            $violations = array_merge(
                $violations,
                $this->getFilteredViolations($checkResult->getViolations()));
        }

        if ($violations) {
            $this->write($file->getFilename().':'.PHP_EOL);
            $this->write(str_repeat('-', 80).PHP_EOL);

            foreach ($violations as $violation) {
                $this->write('Line '.$violation->getLine());

                if ($violation->getColumn() > 0) {
                    $this->write(':'.$violation->getColumn());
                }

                $this->write(' ('.$violation->getSeverityName().') : ');
                $this->write($violation->getMessage().PHP_EOL);
            }
            $this->write(PHP_EOL);
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
    private function write($content)
    {
        $this->fileSystem->putContent('php://stdout', $content);
    }

    /**
     * @param Violation[] $violations
     *
     * @return Violation[]
     */
    private function getFilteredViolations(array $violations)
    {
        return array_filter($violations, function ($violation) {
            return in_array($violation->getSeverity(), $this->loggedSeverities);
        });
    }
}
