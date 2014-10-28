<?php 

	namespace PHPCheckstyle\Reporter;

	use PHPCheckstyle\File;

	/**
	 * Array Reporter.
	 * @package PHPCheckstyle
	 * @author James Brooks <jbrooksuk@me.com>
	 */
	class ArrayReporter extends ReportInterface {
		/**
		 * Report array.
		 * @var array
		 */
		protected $report = array();

		/**
		 * Defined by ReportInterface.
		 * @see ReportInterface::addFile()
		 * @param File $file
		 */
		public function addFile(File $file) {
			foreach ($file->getViolations() as $violation) {
				$this->report[$violation->getLine][] = array(
					'column' => $violation->getColumn(),
					'severity' => $violation->getSeverity(),
					'message' => $violation->getMessage(),
					'source' => $violation->getSource()
				);
			}
		}
	}
