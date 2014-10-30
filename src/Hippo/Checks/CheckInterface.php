<?php 

	namespace Hippo\Check;

	use Hippo\File;

	/**
	 * Check Interface.
	 * Rules implementing this interface will be visited for every file.
	 * @package Hippo
	 * @author James Brooks <jbrooksuk@me.com>
	 */
	interface CheckInterface {
		/**
		 * @return CheckResult
		 */
		public function checkFile(File $file);
	}
