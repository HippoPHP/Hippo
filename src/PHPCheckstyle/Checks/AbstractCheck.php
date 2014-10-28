<?php 

	namespace PHPCheckstyle\Check;

	use PHPCheckstyle\File;
	use PHPCheckstyle\Violation;

	/**
	 * All checks will extend from this Abstract class.
	 * @package PHPCheckstyle
	 * @author James Brooks <jbrooksuk@me.com>
	 */
	abstract class AbstractCheck implements CheckInterface {
		/**
		 * Severity that the check will produce.
		 * @var int
		 */
		protected $severity = Violation::SEVERITY_ERROR;

		/**
		 * Result of the check.
		 * @var CheckResult
		 */
		protected $checkResult;

		public function __construct() {
			$this->checkResult = new CheckResult();
		}

		/**
		 * Set the severity level of the check.
		 * @param int $severity
		 * @return AbstractCheck
		 */
		public function setSeverity($severity) {
			if (NULL !== ($severity = Violation::getSeverityFromString($severity))) {
				$this->severity = $severity;
			}

			return $this;
		}

		/**
		 * Add a violation to the current file.
		 * @param File $file
		 * @param int $line
		 * @param int $column
		 * @param string $message
		 * @param int $severity
		 */
		protected function addViolation(File $file, $line, $column, $message, $severity = NULL) {
			$source = get_class($this);

			if (strpos($source, 'PHPCheckstyle\\Check\\') === 0) {
				$source = 'PHPCheckstyle\\' . substr($source, strlen('PHPCheckstyle\\Check\\'));
			}

			if ($severity === NULL) {
				$severity = $this->severity;
			}

			$this->checkResult->addViolation(new Violation($file, $line, $column, $severity, $message, $source));
		}
	}
