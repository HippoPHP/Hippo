<?php 

	namespace PHPCheckstyle\PHPCheckstyle;

	use PHPCheckstyle\Exception;
	use PHPCheckstyle\PHPCheckstyle\File;

	class PHPCheckstyle {
		protected $checks = array();
		protected $listenerTokens = array();

		public function check(File $file) {
			$this->_runChecks($file);
		}

		private function _runChecks(File $file) {
			$results = [];
			foreach ($this->checks as $check) {
				$results[] = $check->visitFile($file);
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
