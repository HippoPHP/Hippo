<?php 

	namespace PHPCheckstyle;

	/**
	 * Represents a check violation.
	 * @package PHPCheckstyle
	 * @author James Brooks <jbrooksuk@me.com>
	 */
	class Violation {
		/**
		 * Severities.
		 */
		const SEVERITY_IGNORE  = 0;
		const SEVERITY_INFO    = 1;
		const SEVERITY_WARNING = 2;
		const SEVERITY_ERROR   = 3;

		/**
		 * The file that the violation was made on.
		 * @var File
		 */
		protected $file;

		/**
		 * The line number that the violation was made on.
		 * @var int
		 */
		protected $line;

		/**
		 * The column that the violation occurred on.
		 * @var int
		 */
		protected $column;

		/**
		 * The severity of the error.
		 * @var int
		 */
		protected $severity;

		/**
		 * The violation text.
		 * @var string
		 */
		protected $message;

		/**
		 * The source of the error.
		 * @var string
		 */
		protected $source;

		/**
		 * Creates a new violation.
		 * @param int $line
		 * @param int $column
		 * @param int $severity
		 * @param string $message
		 * @param string $source
		 * @return void
		 */
		public function __construct(File $file, $line, $column, $severity, $message, $source) {
			$this->file = $file;
			$this->line = (int) $line;
			$this->column = (int) $column;
			$this->severity = min(self::SEVERITY_ERROR, max(self::SEVERITY_IGNORE, (int) $severity));
			$this->message = $message;
			$this->source = $source;
		}

		/**
		 * Returns the file of the violation.
		 * @return File
		 */
		public function getFile() {
			return $this->file;
		}

		/**
		 * Returns the line number of the violation.
		 * @return int
		 */
		public function getLine() {
			return $this->line;
		}

		/**
		 * Returns the column number of the violation.
		 * @return int
		 */
		public function getColumn() {
			return $this->column;
		}

		/**
		 * Returns the severity of the violation.
		 * @return int
		 */
		public function getSeverity() {
			return $this->severity;
		}

		/**
		 * Returns the named value of the severity.
		 * @return string
		 */
		public function getSeverityName() {
			switch ($this->severity) {
				case self::SEVERITY_IGNORE:
					return 'ignore';
				case self::SEVERITY_INFO:
					return 'info';
				case self::SEVERITY_WARNING:
					return 'warning';
				case self::SEVERITY_ERROR:
					return 'error';
			}
		}

		/**
		 * Returns the violations message.
		 * @return string
		 */
		public function getMessage() {
			return $this->message;
		}

		/**
		 * Returns the source rule of the error.
		 * @return string
		 */
		public function getSource() {
			return $this->source;
		}

		/**
		 * Get a severity level from a severity name.
		 * @param  string $severity
		 * @return int
		 */
		public function getSeverityFromString($severity) {
			switch ($severity) {
				case 'ignore':
					return self::SEVERITY_IGNORE;
				case 'info':
					return self::SEVERITY_INFO;
				case 'warning':
					return self::SEVERITY_WARNING;
				case 'error':
					return self::SEVERITY_ERROR;
			}

			return NULL;
		}
	}
