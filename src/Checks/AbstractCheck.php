<?php

	namespace HippoPHP\Hippo\Checks;

	use \HippoPHP\Hippo\Checks\CheckInterface;
	use \HippoPHP\Hippo\CheckResult;
	use \HippoPHP\Hippo\Config\Config;
	use \HippoPHP\Hippo\File;
	use \HippoPHP\Hippo\Violation;

	/**
	 * All checks will extend from this Abstract class.
	 * @package Hippo
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

		public function checkFile(File $file, Config $config) {
			$this->checkResult = new CheckResult();
			$this->checkResult->setFile($file);
			$this->checkFileInternal($file, $config);
			return $this->checkResult;
		}

		/**
		 * Set the severity level of the check.
		 * @param int $severity
		 * @return AbstractCheck
		 */
		public function setSeverity($severity) {
			if (null !== ($severity = Violation::getSeverityFromString($severity))) {
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
		protected function addViolation(File $file, $line, $column, $message, $severity = null) {
			$source = get_class($this);

			if (strpos($source, 'Hippo\\Check\\') === 0) {
				$source = 'Hippo\\' . substr($source, strlen('Hippo\\Check\\'));
			}

			if ($severity === null) {
				$severity = $this->severity;
			}

			$this->checkResult->addViolation(new Violation($file, $line, $column, $severity, $message, $source));
		}

		abstract protected function checKFileInternal(File $file, Config $config);
	}
