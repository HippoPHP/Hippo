<?php 

	namespace PHPCheckstyle\Check;

	use PHPCheckstyle\File;

	/**
	 * Check Interface.
	 * Rules implementing this interface will be visited for every file.
	 * @package PHPCheckstyle
	 * @author James Brooks <jbrooksuk@me.com>
	 */
	interface CheckInterface {
		/**
		 * @return CheckResult
		 */
		public function checkFile(File $file);
	}
