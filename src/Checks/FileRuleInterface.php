<?php 

	namespace PHPCheckstyle\PHPCheckstyle\Checks;

	use PHPCheckstyle\PHPCheckstyle\File;

	/**
	 * File rule interface.
	 * Rules implementing this interface will be visited for every file.
	 * @package PHPCheckstyle
	 * @author James Brooks <jbrooksuk@me.com>
	 */
	interface FileRuleInterface {
		public function checkFile(File $file);
	}