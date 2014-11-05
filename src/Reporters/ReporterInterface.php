<?php

	namespace HippoPHP\Hippo\Reporters;

	use \HippoPHP\Hippo\FileSystem;
	use \HippoPHP\Hippo\CheckResult;

	/**
	 * Reporters should inherit from this.
	 */
	interface ReporterInterface {
		public function start();

		/**
		 * Adds a check result to the report.
		 * @param CheckResult $checkResult
		 * @return void
		 */
		public function addCheckResult(CheckResult $checkResult);

		public function finish();
	}
