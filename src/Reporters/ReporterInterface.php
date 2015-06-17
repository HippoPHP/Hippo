<?php

/*
 * This file is part of Hippo.
 *
 * (c) James Brooks <jbrooksuk@me.com>
 * (c) Marcin Kurczewski <rr-@sakuya.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
     */
    public function addCheckResults(File $file, array $checkResult);

    /**
     * Method called at the end of a check.
     *
     * @return mixed
     */
    public function finish();
}
