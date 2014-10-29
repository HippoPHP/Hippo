<?php 

	namespace PHPCheckstyle\Reporter;

	use PHPCheckstyle\CheckResult;

	/**
	 * CLI Reporter.
	 * @package PHPCheckstyle
	 * @author James Brooks <jbrooksuk@me.com>
	 */
	class CLIReporter implements ReportInterface {
		protected $firstFile = TRUE;
		
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
				$this->firstFile = FALSE;
			} else {
				echo PHP_EOL;
			}

			echo $checkResult->getFile()->getFilename() . ':' . PHP_EOL;
			echo str_repeat('-', 80) . PHP_EOL;

			foreach ($violations as $violation) {
				echo $violation->getLine() . ':';

				if ($violation->getColumn() > 0) {
					echo $violation->getColumn() . ':';
				}

				echo $violation->getSeverityName() . ': ';
				echo $violation->getMessage() . PHP_EOL;
			}

			flush();
		}
	}
