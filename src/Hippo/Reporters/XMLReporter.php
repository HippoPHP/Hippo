<?php

	namespace Hippo\Reporters;

	use Hippo\CheckResult;
	use XMLWriter;

	/**
	 * Array Reporter.
	 * @package Hippo
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
