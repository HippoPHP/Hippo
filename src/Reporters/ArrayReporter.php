<?php

	namespace HippoPHP\Hippo\Reporters;

	use HippoPHP\Hippo\CheckResult;

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

		public function start() {
		}

		/**
		 * Defined by ReportInterface.
		 * @see ReportInterface::addCheckResult()
		 * @param CheckResult $checkResult
		 */
		public function addCheckResult(CheckResult $checkResult) {
			foreach ($checkResult->getViolations() as $violation) {
				$key = $this->_getArrayKey($violation);
				if (!isset($this->report[$key])) {
					$this->report[$key] = array();
				}

				$this->report[$key][] = array(
					'file' => $violation->getFile()->getFilename(),
					'line' => $violation->getLine(),
					'column' => $violation->getColumn(),
					'severity' => $violation->getSeverity(),
					'message' => $violation->getMessage(),
					'source' => $violation->getSource()
				);
			}
		}

		public function finish() {
		}

		public function getArray() {
			return $this->report;
		}

		private function _getArrayKey(Violation $violation) {
			return $violation->getFile()->getFilename() . ':' . $violation->getLine();
		}
	}
