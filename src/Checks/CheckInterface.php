<?php

	namespace HippoPHP\Hippo\Checks;

	use \HippoPHP\Hippo\File;
	use \HippoPHP\Hippo\Config\Config;

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
