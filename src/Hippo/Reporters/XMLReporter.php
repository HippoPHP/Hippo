<?php

	namespace Hippo\Reporters;

	use Hippo\CheckResult;
	use XMLWriter;

	/**
	 * Array Reporter.
	 * @package Hippo
	 * @author James Brooks <jbrooksuk@me.com>
	 */
	class XMLReporter implements ReporterInterface {
		/**
		 * XMLWriter
		 * @var XMLWriter
		 */
		protected $writer;

		public function start() {
			throw new \BadMethodCallException('Not implemented');
		}

		/**
		 * Defined by ReportInterface.
		 * @see ReportInterface::addCheckResult()
		 * @param CheckResult $checkResult
		 */
		public function addCheckResult(CheckResult $checkResult) {
			throw new \BadMethodCallException('Not implemented');
		}

		public function finish() {
			throw new \BadMethodCallException('Not implemented');
		}
	}
