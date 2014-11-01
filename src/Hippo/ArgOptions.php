<?php

	namespace Hippo;

	/**
	 * A container for command-line-interface arguments.
	 * @package Hippo
	 */
	class ArgOptions {
		/**
		 * @var array
		 */
		private $_longOptions;

		/**
		 * @var array
		 */
		private $_shortOptions;

		/**
		 * @var array
		 */
		private $_strayArguments;

		/**
		 * @param string $arg
		 * @param mixed $value
		 */
		public function setShortOption($arg, $value) {
			$this->_shortOptions[$arg] = $value;
		}

		/**
		 * @param string $arg
		 * @param mixed $value
		 */
		public function setLongOption($arg, $value) {
			$this->_longOptions[$arg] = $value;
		}

		/**
		 * @param mixed $value
		 */
		public function addStrayArgument($value) {
			$this->_strayArguments[] = $value;
		}

		/**
		 * @param string $arg
		 */
		public function getShortOption($arg) {
			return isset($this->_shortOptions[$arg]) ? $this->_shortOptions[$arg] : null;
		}

		/**
		 * @param string $arg
		 */
		public function getLongOption($arg) {
			return isset($this->_longOptions[$arg]) ? $this->_longOptions[$arg] : null;
		}

		/**
		 * @return mixed[]
		 */
		public function getStrayArguments() {
			return $this->_strayArguments;
		}
	}
