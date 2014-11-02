<?php

	namespace Hippo\Reporters;

	use Hippo\CheckResult;

	/**
	 * CLI Reporter.
	 * @package Hippo
	 * @author James Brooks <jbrooksuk@me.com>
	 */
	class CLIReporter implements ReporterInterface {
		protected $firstFile;

		public function start() {
			$this->firstFile = true;
		}

		/**
		 * Defined by ReportInterface.
		 * @see ReportInterface::addCheckResult()
		 * @param CheckResult $checkResult
		 */
		public function addCheckResult(CheckResult $checkResult) {
			$violations = $checkResult->getViolations();

			if (!$violations) {
				return;
			}

			if ($this->firstFile) {
				$this->firstFile = false;
			} else {
				echo PHP_EOL;
			}

			echo $checkResult->getFile()->getFilename() . ':' . PHP_EOL;
			echo str_repeat('-', 80) . PHP_EOL;

			foreach ($violations as $violation) {
				echo 'Line ' . $violation->getLine();

				if ($violation->getColumn() > 0) {
					echo ':' . $violation->getColumn();
				}

				echo ' ('. $violation->getSeverityName() . ') : ';
				echo $violation->getMessage() . PHP_EOL;
			}

			echo PHP_EOL;
			flush();
		}

		public function finish() {
		}
	}
