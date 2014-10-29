<?php 

	namespace PHPCheckstyle\Reporter;

	use PHPCheckstyle\CheckResult;
	use XMLWriter;

	/**
	 * Array Reporter.
	 * @package PHPCheckstyle
	 * @author James Brooks <jbrooksuk@me.com>
	 */
	class XMLReporter implements ReportInterface {
		/**
		 * XMLWriter
		 * @var XMLWriter
		 */
		protected $writer;

		/**
		 * Defined by ReportInterface.
		 * @see ReportInterface::addCheckResult()
		 * @param CheckResult $checkResult
		 */
		public function addCheckResult(CheckResult $checkResult) {
			throw new \BadMethodCallException('Not implemented');
		}
	}
