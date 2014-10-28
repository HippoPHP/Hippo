<?php 

	namespace PHPCheckstyle\PHPCheckstyle\Check;

	use PHPCheckstyle\File;

	/**
	 * Check Interface.
	 * Rules implementing this interface will be visited for every file.
	 * @package PHPCheckstyle
	 * @author James Brooks <jbrooksuk@me.com>
	 */
	interface CheckInterface {
		public function checkFile(File $file);
	}
