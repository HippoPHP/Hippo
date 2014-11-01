<?php

	namespace Hippo\Reporters;

	use Hippo\CheckResult;

	/**
	 * Reporters should inherit from this.
	 */
	interface ReportInterface {
		/**
		 * Adds a check result to the report.
		 * @param CheckResult $checkResult
		 * @return void
		 */
		public function addCheckResult(CheckResult $checkResult);
	}
