<?php

	namespace Hippo;

	use Hippo\Exception\ShutdownException;

	/**
	 * @package Hippo
	 */
	class Environment {
		/**
		 * @var integer
		 */
		private $_exitCode = 0;

		/**
		 * @return integer
		 */
		public function getExitCode() {
			return $this->_exitCode;
		}

		/**
		 * @param integer $exitCode
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
