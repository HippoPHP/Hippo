<?php

	namespace HippoPHP\Hippo\Reporters;

	use \HippoPHP\Hippo\FileSystem;
	use \HippoPHP\Hippo\CheckResult;
	use \HippoPHP\Hippo\Violation;

	/**
	 * CLI Reporter.
	 * @package Hippo
	 * @author James Brooks <jbrooksuk@me.com>
	 */
	class CLIReporter implements ReporterInterface {
		private $_firstFile;
		private $_fileSystem;
		private $_loggedSeverities;

		/**
		 * @param FileSystem $fileSystem
		 */
		public function __construct(FileSystem $fileSystem) {
			$this->_fileSystem = $fileSystem;
			$this->_loggedSeverities = Violation::getSeverities();
		}

		/**
		 * @param int[] $severities
		 */
		public function setLoggedSeverities(array $severities) {
			$this->_loggedSeverities = $severities;
		}

		/**
		 * Defined by ReportInterface.
		 * @see ReportInterface::start()
		 */
		public function start() {
			$this->_firstFile = true;
		}

		/**
		 * Defined by ReportInterface.
		 * @see ReportInterface::addCheckResult()
		 * @param CheckResult $checkResult
		 */
		public function addCheckResult(CheckResult $checkResult) {
			if (empty($this->_loggedSeverities)) {
				return;
			}

			$violations = $this->_getFilteredViolations($checkResult->getViolations());

			if ($this->_firstFile) {
				$this->_firstFile = false;
			} else {
				$this->_write(PHP_EOL);
			}

			$this->_write('Checking ' . $checkResult->getFile()->getFilename() . PHP_EOL);

			if (!$violations) {
				return;
			}

			$this->_write($checkResult->getFile()->getFilename() . ':' . PHP_EOL);
			$this->_write(str_repeat('-', 80) . PHP_EOL);

			foreach ($violations as $violation) {
				$this->_write('Line ' . $violation->getLine());

				if ($violation->getColumn() > 0) {
					$this->_write(':' . $violation->getColumn());
				}

				$this->_write(' ('. $violation->getSeverityName() . ') : ');
				$this->_write($violation->getMessage() . PHP_EOL);
			}

			$this->_write(PHP_EOL);
			flush();
		}

		/**
		 * Defined by ReportInterface.
		 * @see ReportInterface::finish()
		 */
		public function finish() {
		}

		private function _write($content) {
			return $this->_fileSystem->putContent('php://stdout', $content);
		}

		/**
		 * @param Violation[] $violations
		 * @return Violation[]
		 */
		private function _getFilteredViolations(array $violations) {
			return array_filter($violations, function($violation) {
				return in_array($violation->getSeverity(), $this->_loggedSeverities);
			});
		}
	}
