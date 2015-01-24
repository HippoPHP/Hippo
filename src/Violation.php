<?php

namespace HippoPHP\Hippo;

/**
 * Represents a check violation.
 *
 * @author James Brooks <jbrooksuk@me.com>
 */
class Violation
{
    /**
     * Severities.
     */
    const SEVERITY_IGNORE  = 0;
    const SEVERITY_INFO    = 1;
    const SEVERITY_WARNING = 2;
    const SEVERITY_ERROR   = 3;

    /**
     * Violation severities, in order from most deadly to most peaceful.
     *
     * @return int[]
     */
    public static function getSeverities()
    {
        return [
            self::SEVERITY_ERROR,
            self::SEVERITY_WARNING,
            self::SEVERITY_INFO,
            self::SEVERITY_IGNORE,
        ];
    }

    /**
     * The file that the violation was made on.
     *
     * @var File
     */
    protected $file;

    /**
     * The line number that the violation was made on.
     *
     * @var int
     */
    protected $line;

    /**
     * The column that the violation occurred on.
     *
     * @var int
     */
    protected $column;

    /**
     * The severity of the error.
     *
     * @var int
     */
    protected $severity;

    /**
     * The violation text.
     *
     * @var string
     */
    protected $message;

    /**
     * Creates a new violation.
     *
     * @param int $line
     * @param int $column
     * @param int $severity
     * @param string $message
     *
     * @return void
     */
    public function __construct(File $file, $line, $column, $severity, $message)
    {
        $this->file = $file;
        $this->line = (int) $line;
        $this->column = (int) $column;
        $this->severity = min(self::SEVERITY_ERROR, max(self::SEVERITY_IGNORE, (int) $severity));
        $this->message = $message;
    }

    /**
     * Returns the file of the violation.
     *
     * @return File
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Returns the line number of the violation.
     *
     * @return int
     */
    public function getLine()
    {
        return $this->line;
    }

    /**
     * Returns the column number of the violation.
     *
     * @return int
     */
    public function getColumn()
    {
        return $this->column;
    }

    /**
     * Returns the severity of the violation.
     *
     * @return int
     */
    public function getSeverity()
    {
        return $this->severity;
    }

    /**
     * Returns the named value of the severity.
     *
     * @return string
     */
    public function getSeverityName()
    {
        $severityNames = $this->_getSeverityNames();

        return $severityNames[$this->severity];
    }

    /**
     * Returns the violations message.
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Get a severity level from a severity name.
     *
     * @param  string $severityName
     *
     * @return int
     */
    public static function getSeverityFromString($severityName)
    {
        $severityNames = array_flip(self::_getSeverityNames());
        if (isset($severityNames[$severityName])) {
            return $severityNames[$severityName];
        }

        return;
    }

    /**
     * Array of severity levels to the severity name.
     *
     * @return array
     */
    private static function _getSeverityNames()
    {
        return [
            self::SEVERITY_IGNORE  => 'ignore',
            self::SEVERITY_INFO    => 'info',
            self::SEVERITY_WARNING => 'warning',
            self::SEVERITY_ERROR   => 'error',
        ];
    }
}
