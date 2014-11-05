<?php

	namespace HippoPHP\Hippo\Reporters;

	use \HippoPHP\Hippo\CheckResult;

	/**
	 * Reporters should inherit from this.
	 */
	interface ReporterInterface {
		/**
		 * Method called at the beginning of a check.
		 * @return mixed
		 */
		public function start();

		/**
		 * Adds a check result to the report.
		 * @param CheckResult $checkResult
		 * @return void
		 */
		public function addCheckResult(CheckResult $checkResult);

		/**
		 * Method called at the end of a check.
		 * @return mixed
		 */
		public function finish();
	}
