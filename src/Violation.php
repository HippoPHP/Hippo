<?php 

	namespace PHPCheckstyle\PHPCheckstyle;

	class Violation {
		const SEVERITY_IGNORE  = 0;
		const SEVERITY_INFO    = 1;
		const SEVERITY_WARNING = 2;
		const SEVERITY_ERROR   = 3;

		protected $line;
		protected $column;
		protected $severity;
		protected $message;
		protected $source;

		public function __construct($line, $column, $severity, $message, $source) {
			$this->line = (int) $line;
			$this->column = (int) $column;
			$this->severity = min(3, max(0, (int) $severity));
			$this->message = $message;
			$this->source = $source;
		}

		public function getLine() {
			return $this->line;
		}

		public function getColumn() {
			return $this->column;
		}

		public function getSeverity() {
			return $this->severity;
		}

		public function getSeverityName() {
			switch ($this->severity) {
				case self::SEVERITY_IGNORE:
					return 'ignore';
				case self::SEVERITY_INFO:
					return 'info';
				case self::SEVERITY_WARNING:
					return 'warning';
				case self::SEVERITY_error:
					return 'error';
			}
		}

		public function getMessage() {
			return $this->message;
		}

		public function getSource() {
			return $this->source;
		}

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