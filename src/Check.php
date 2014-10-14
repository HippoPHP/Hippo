<?php 

	/**
	 * Represents a PHPCheckstyle check for checking coding standards.
	 *
	 * @category  PHP
	 * @package   PHPCheckstyle
	 * @author    James Brooks <jbrooksuk@me.com>
	 */
	interface PHPCheckstyle_Check {
		public function register();

		public function process(PHPCheckstyle_File $phpcsFile, $stackPtr);
	}