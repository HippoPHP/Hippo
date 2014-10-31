<?php

	namespace Hippo\Check;

	use Hippo\File;

	/**
	 * Token Check Interface.
	 * Rules implementing this interface will be able to register
	 * for certain tokens and be notified for each occurence.
	 * @package Hippo
	 * @author James Brooks <jbrooksuk@me.com>
	 */
	interface TokenCheckInterface extends CheckInterface {
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
