<?php 

	namespace PHPCheckstyle\PHPCheckstyle\Check;

	use PHPCheckstyle\File;

	/**
	 * Token Check Interface.
	 * Rules implementing this interface will be able to register 
	 * for certain tokens and be notified for each occurence.
	 * @package PHPCheckstyle
	 * @author James Brooks <jbrooksuk@me.com>
	 */
	interface TokenCheckInterface {
		/**
		 * Get all of the tokens the interface should listen for.
		 * @return array
		 */
		public function getListenerTokens();

		/**
		 * Check a file for rule violations at the given position.
		 * @param  File   $file
		 * @return void
		 */
		public function visitToken(File $file);
	}