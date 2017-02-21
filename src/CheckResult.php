<?php

/*
 * This file is part of Hippo.
 *
 * (c) James Brooks <james@alt-three.com>
 * (c) Marcin Kurczewski <rr-@sakuya.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HippoPHP\Hippo;

use Countable;

/**
 * Result of running a check.
 */
class CheckResult implements Countable
{
    /**
     * Was modified since last violation retrieval?
     *
     * @var bool
     */
    protected $violationsDirty;

    /**
     * Violations held against the file.
     *
     * @var \HippoPHP\Hippo\Violation[]
     */
    protected $violations = [];

    /**
     * Which file is this check result for.
     *
     * @var \HippoPHP\Hippo\File
     */
    protected $file;

    /**
     * Sets which file is this check result for.
     *
     * @param \HippoPHP\Hippo\File $file
     */
    public function setFile(File $file)
    {
        $this->file = $file;
    }

    /**
     * Returns which file is this check result for.
     *
     * @return \HippoPHP\Hippo\File
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Returns whether check succeeded.
     *
     * @return bool
     */
    public function hasSucceeded()
    {
        return empty($this->violations);
    }

    /**
     * Returns whether check failed.
     *
     * @return bool
     */
    public function hasFailed()
    {
        return empty($this->violations) === false;
    }

    /**
     * @return int|null
     */
    public function getMaximumViolationSeverity()
    {
        $ret = null;
        foreach ($this->violations as $violation) {
            if (($ret === null) || ($violation->getSeverity() > $ret)) {
                $ret = $violation->getSeverity();
            }
        }

        return $ret;
    }

    /**
     * @param \HippoPHP\Hippo\Violation $violation
     */
    public function addViolation(Violation $violation)
    {
        $this->violations[] = $violation;
        $this->violationsDirty = true;
    }

    /**
     * Return all of the violations on the file.
     * Violations are sorted on a line/column basis.
     *
     * @return \HippoPHP\Hippo\Violation[]
     */
    public function getViolations()
    {
        $this->processViolationsIfDirty();

        return $this->violations;
    }

    /**
     * Counts how many violations are in the result.
     *
     * @return int
     *
     * @see Countable::count()
     */
    public function count()
    {
        return count($this->violations);
    }

    /**
     * Resorts the violations array if it's been changed.
     */
    private function processViolationsIfDirty()
    {
        if ($this->violationsDirty) {
            $this->sortViolations();
            $this->violationsDirty = false;
        }
    }

    /**
     * Sorts the violations by line then column.
     */
    private function sortViolations()
    {
        usort($this->violations, function (Violation $a, Violation $b) {
            if ($a->getLine() === $b->getLine()) {
                if ($a->getColumn() === $b->getColumn()) {
                    return 0;
                }

                return $a->getColumn() < $b->getColumn() ? -1 : 1;
            }

            return $a->getLine() < $b->getLine() ? -1 : 1;
        });
    }
}
