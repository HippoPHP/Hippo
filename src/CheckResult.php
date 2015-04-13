<?php

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
     * @var array
     */
    protected $violations = [];

    /**
     * Which file is this check result for.
     *
     * @var File
     */
    protected $file;

    /**
     * Sets which file is this check result for.
     *
     * @param File $file
     */
    public function setFile(File $file)
    {
        $this->file = $file;
    }

    /**
     * Returns which file is this check result for.
     *
     * @return File
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
     * @param Violation $violation
     *
     * @return void
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
     * @return Violation[]
     */
    public function getViolations()
    {
        $this->_processViolationsIfDirty();

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
     *
     * @return void
     */
    private function _processViolationsIfDirty()
    {
        if ($this->violationsDirty) {
            $this->_sortViolations();
            $this->violationsDirty = false;
        }
    }

    /**
     * Sorts the violations by line then column.
     *
     * @return void
     */
    private function _sortViolations()
    {
        usort($this->violations, function (Violation $a, Violation $b) {
            if ($a->getLine() === $b->getLine()) {
                if ($a->getColumn() === $b->getColumn()) {
                    return 0;
                }

                return ($a->getColumn() < $b->getColumn() ? -1 : 1);
            }

            return ($a->getLine() < $b->getLine() ? -1 : 1);
        });
    }
}
