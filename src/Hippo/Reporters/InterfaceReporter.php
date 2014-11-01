<?php

	namespace Hippo\Reporters;

	use Hippo\CheckResult;

	/**
	 * Reporters should inherit from this.
	 */
	interface InterfaceReporter {
		public function start();

		/**
		 * Adds a check result to the report.
		 * @param CheckResult $checkResult
		 * @return void
		 */
		public function addCheckResult(CheckResult $checkResult);

		public function finish();
	}
