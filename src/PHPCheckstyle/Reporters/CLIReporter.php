<?php 

	namespace PHPCheckstyle\Reporter;

	use PHPCheckstyle\File;

	/**
	 * CLI Reporter.
	 * @package PHPCheckstyle
	 * @author James Brooks <jbrooksuk@me.com>
	 */
	class CLIReporter extends ReportInterface {
		protected $firstFile = TRUE;
		
		/**
		 * Defined by ReportInterface.
		 * @see ReportInterface::addFile()
		 * @param File $file
		 */
		public function addFile(File $file) {
			$violations = $file->getViolations();

			if ($violations) {
				if ($this->firstFile) {
					$this->firstFile = false;
				} else {
					echo PHP_EOL;
				}

				echo $file->getFilename() . ':' . PHP_EOL;
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
	}
