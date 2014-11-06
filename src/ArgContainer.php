<?php

	namespace HippoPHP\Hippo;

	/**
	 * A container for command-line-interface arguments.
	 * @package Hippo
	 */
	class ArgContainer {
		/**
		 * @var array
		 */
		private $_longOptions = array();

		/**
		 * @var array
		 */
		private $_shortOptions = array();

		/**
		 * @var array
		 */
		private $_strayArguments = array();

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

		/**
		 * @return array
		 */
		public function getLongOptions() {
			return $this->_longOptions;
		}

		/**
		 * @return array
		 */
		public function getShortOptions() {
			return $this->_shortOptions;
		}

		/**
		 * @return array
		 */
		public function getAllOptions() {
			return array_merge($this->_longOptions, $this->_shortOptions);
		}
	}
