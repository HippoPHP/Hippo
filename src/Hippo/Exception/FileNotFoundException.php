<?php

	namespace HippoPHP\Hippo\Exception;

	/**
	 * @package Hippo
	 */
	class FileNotFoundException extends \Exception implements ExceptionInterface {
		/**
		 * @param string $path
		 */
		public function __construct($path) {
			parent::__construct('File not found: ' . $path);
		}
	}
