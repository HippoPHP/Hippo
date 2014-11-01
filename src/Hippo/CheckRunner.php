<?php

	namespace Hippo;

	use Hippo\Exception;
	use Hippo\File;

	class CheckRunner {
		protected $checks = array();

		/**
		 * @param File $file
		 * @return CheckResult[]
		 */
		public function checkFile(File $file) {
			$results = [];
			foreach ($this->checks as $check) {
				$results[] = $check->checkFile($file);
			}
			return $results;
		}

		private function _verifyResults(File $file, array $checkResults) {
			foreach ($results as $result) {
				$this->reportViolationsIfNeeded($result->getViolations());
			}
		}

		private function _reportViolationsIfNeeded(CheckResult $checkResult) {
			if (!$result->hasSucceeded()) {
				$this->_reportViolations($checkResult);
			}
		}

		private function _reportViolations(CheckResult $checkResult) {
			foreach ($checkResult->getViolations() as $violation) {
				throw new \BadMethodCallException('Not implemented');
			}
		}
	}
