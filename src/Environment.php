<?php

	namespace HippoPHP\Hippo;

	use HippoPHP\Hippo\Exception\ShutdownException;

	/**
	 * @package Hippo
	 */
	class Environment {
		/**
		 * @var int
		 */
		private $_exitCode = 0;

		/**
		 * @return int
		 */
		public function getExitCode() {
			return $this->_exitCode;
		}

		/**
		 * @param int $exitCode
		 * @return void
		 */
		public function setExitCode($exitCode) {
			$this->_exitCode = $exitCode;
		}

		/**
		 * @return void
		 */
		public function shutdown() {
			throw new ShutdownException($this->_exitCode);
		}
	}
