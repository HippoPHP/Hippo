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
	}
