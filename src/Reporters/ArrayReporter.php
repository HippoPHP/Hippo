<?php

	namespace HippoPHP\Hippo\Reporters;

	use \HippoPHP\Hippo\CheckResult;
	use \HippoPHP\Hippo\File;
	use \HippoPHP\Hippo\Violation;

	/**
	 * Array Reporter.
	 * @package Hippo
	 * @author James Brooks <jbrooksuk@me.com>
	 */
	class ArrayReporter implements ReporterInterface {
		/**
		 * Report array.
		 * @var array
		 */
		protected $report = array();

		/**
		 * Defined by ReportInterface.
		 * @see ReportInterface::start()
		 */
		public function start() {
		}

		/**
		 * Defined by ReportInterface.
		 * @see ReportInterface::addCheckResults()
		 * @param File $file
		 * @param CheckResult $checkResults
		 */
		public function addCheckResults(File $file, array $checkResults) {
			foreach ($checkResults as $checkResult) {
				foreach ($checkResult->getViolations() as $violation) {
					$key = $this->_getArrayKey($violation);
					if (!isset($this->report[$key])) {
						$this->report[$key] = array();
					}

					$this->report[$key][] = array(
						'file' => $file->getFilename(),
						'line' => $violation->getLine(),
						'column' => $violation->getColumn(),
						'severity' => $violation->getSeverity(),
						'message' => $violation->getMessage(),
						'source' => $violation->getSource()
					);
				}
			}
		}

		/**
		 * Defined by ReportInterface.
		 * @see ReportInterface::finish()
		 */
		public function finish() {
			return $this->report;
		}

		/**
		 * Returns the reports array.
		 * @return array
		 */
		public function getReport() {
			return $this->report;
		}

		/**
		 * Generates a key for a violation.
		 * @param Violation $violation
		 * @return string
		 */
		private function _getArrayKey(Violation $violation) {
			return $violation->getFile()->getFilename() . ':' . $violation->getLine();
		}
	}
