<?php

	namespace Hippo\Checks;

	use Hippo\File;
	use Hippo\Config\Config;

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
		public function checkFile(File $file, Config $config);

		/**
		 * @return string
		 */
		public function getConfigRoot();
	}
