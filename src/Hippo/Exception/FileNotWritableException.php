<?php

	namespace Hippo\Exception;

	/**
	 * @package Hippo
	 */
	class FileNotWritableException extends \Exception implements ExceptionInterface {
		/**
		 * @param string $path
		 */
		public function __construct($path) {
			parent::__construct('File not writable: ' . $path);
		}
	}
