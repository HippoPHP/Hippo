<?php

	namespace Hippo\Exception;

	/**
	 * @package Hippo
	 */
	class ShutdownException extends \Exception implements ExceptionInterface {
		/**
		 * @var integer
		 */
		private $_exitCode;

		/**
		 * @param integer $exitCode
		 */
		public function __construct($exitCode) {
			$this->_exitCode = $exitCode;
		}

		/**
		 * @return integer
		 */
		public function getExitCode() {
			return $this->_exitCode;
		}
	}

