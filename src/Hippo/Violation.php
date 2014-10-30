<?php 

	namespace Hippo;

	/**
	 * Represents a check violation.
	 * @package Hippo
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
			$severityNames = $this->_getSeverityNames();
			return $severityNames[$this->severity];
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
		 * @param  string $severityName
		 * @return int
		 */
		public function getSeverityFromString($severityName) {
			$severityNames = array_flip($this->_getSeverityNames());
			if (isset($severityNames[$severityName])) {
				return $severityNames[$severityName];
			}
			return null;
		}

		private function _getSeverityNames() {
			return array(
				self::SEVERITY_IGNORE => 'ignore',
				self::SEVERITY_INFO => 'info',
				self::SEVERITY_WARNING => 'warning',
				self::SEVERITY_ERROR => 'error');
		}
	}
