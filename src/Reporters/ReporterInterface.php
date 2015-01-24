<?php

namespace HippoPHP\Hippo\Reporters;

use HippoPHP\Hippo\CheckResult;
use HippoPHP\Hippo\File;

/**
 * Reporters should inherit from this.
 */
interface ReporterInterface
{
    /**
     * Method called at the beginning of a check.
     *
     * @return mixed
     */
    public function start();

    /**
     * Adds a check result to the report.
     *
     * @param File          $file
     * @param CheckResult[] $checkResults
     *
     * @return void
     */
    public function addCheckResults(File $file, array $checkResult);

    /**
     * Method called at the end of a check.
     *
     * @return mixed
     */
    public function finish();
}
